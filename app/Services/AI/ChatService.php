<?php

namespace App\Services\AI;

use App\Models\AIConversation;
use App\Services\Departments\ComplianceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ItsmService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use App\Services\Departments\SalesService;
use Illuminate\Support\Str;

/**
 * The Nexora AI chatbot's brain. For factual "what is X right now"
 * questions, answers straight from the database — no Gemini call, no
 * tokens spent. For reasoning questions ("why is X happening"), calls
 * Gemini with the latest stored AI report as context, using
 * PromptBuilder so no long system prompt is retyped per request.
 */
class ChatService
{
    public function __construct(
        protected AIRouter $router,
        protected PromptBuilder $promptBuilder,
        protected ReportGenerator $reportGenerator,
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected ManufacturingService $manufacturingService,
        protected ProcurementService $procurementService,
        protected ComplianceService $complianceService,
        protected ItsmService $itsmService,
        protected SalesService $salesService,
    ) {}

    /**
     * @return array{message: string, used_ai: bool}
     */
    public function ask(string $sessionId, string $message, ?int $userId = null): array
    {
        AIConversation::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'role' => 'user',
            'message' => $message,
            'used_ai' => false,
        ]);

        $dbAnswer = $this->tryDatabaseLookup($message);

        if ($dbAnswer !== null) {
            $this->storeAssistantReply($sessionId, $userId, $dbAnswer, usedAi: false);

            return ['message' => $dbAnswer, 'used_ai' => false];
        }

        $answer = $this->askGemini($message);
        $this->storeAssistantReply($sessionId, $userId, $answer, usedAi: true);

        return ['message' => $answer, 'used_ai' => true];
    }

    /**
     * Straightforward factual questions are answered from the database
     * directly — no AI call. Returns null if the question doesn't match
     * a known lookup pattern, meaning it should go to Gemini instead.
     */
    protected function tryDatabaseLookup(string $message): ?string
    {
        $q = Str::lower($message);

        $rules = [
            fn () => (Str::contains($q, 'revenue') && Str::contains($q, 'today'))
                ? 'Today\'s revenue is ₱'.number_format($this->salesService->revenueTrend(1)[0]['total'], 2).'.'
                : null,

            fn () => Str::contains($q, ['low stock', 'low-stock'])
                ? 'There are currently '.$this->inventoryService->lowStockCount().' item(s) at or below their reorder threshold.'
                : null,

            fn () => Str::contains($q, ['out of stock', 'out-of-stock'])
                ? 'There are currently '.$this->inventoryService->outOfStockCount().' item(s) out of stock.'
                : null,

            fn () => Str::contains($q, ['open tickets', 'itsm tickets', 'support tickets'])
                ? 'There are currently '.$this->itsmService->openTicketsCount().' open ITSM ticket(s).'
                : null,

            fn () => Str::contains($q, ['machines down', 'machine status', 'downtime'])
                ? 'There are currently '.$this->manufacturingService->machinesDownCount().' machine(s) down, with '
                    .$this->manufacturingService->totalDowntimeMinutesToday().' total downtime minutes today.'
                : null,

            fn () => Str::contains($q, ['open risks', 'compliance risks', 'risk count'])
                ? 'There are currently '.$this->complianceService->openRisksCount().' open compliance risk(s), '
                    .$this->complianceService->highSeverityRisksCount().' of which are high or critical severity.'
                : null,

            fn () => Str::contains($q, ['open orders', 'open purchase orders', 'procurement orders'])
                ? 'There are currently '.$this->procurementService->openOrdersCount().' open procurement order(s) '
                    .'worth ₱'.number_format($this->procurementService->openOrdersValue(), 2).'.'
                : null,

            fn () => (Str::contains($q, 'profit margin'))
                ? 'The current profit margin is '.$this->financeService->profitMarginPercent().'%.'
                : null,

            fn () => (Str::contains($q, 'overdue') && Str::contains($q, ['payment', 'invoice']))
                ? 'There are '.$this->financeService->overduePaymentsCount().' overdue payment(s) totaling ₱'
                    .number_format($this->financeService->overduePaymentsTotal(), 2).'.'
                : null,
        ];

        foreach ($rules as $rule) {
            $answer = $rule();

            if ($answer !== null) {
                return $answer;
            }
        }

        return null;
    }

    /**
     * Questions that need reasoning ("why is revenue declining?") go to
     * Gemini with the latest stored AI report as context — never with a
     * fresh full aggregation, and never re-stating the system prompt from
     * scratch (PromptBuilder owns that).
     */
    protected function askGemini(string $message): string
    {
        $context = $this->reportGenerator->getCurrentReport() ?? [
            'note' => 'No AI report has been generated yet.',
        ];

        $response = $this->router->provider()->generate(
            $this->promptBuilder->systemPrompt(),
            $this->promptBuilder->chatPrompt($message, $context),
        );

        return $response['content'];
    }

    protected function storeAssistantReply(string $sessionId, ?int $userId, string $message, bool $usedAi): void
    {
        AIConversation::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'role' => 'assistant',
            'message' => $message,
            'used_ai' => $usedAi,
        ]);
    }
}

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
    ) {
    }

    /**
     * @return array{message: string, used_ai: bool}
     */
    public function ask(
        string $sessionId,
        string $message,
        ?int $userId = null
    ): array {
        AIConversation::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'role' => 'user',
            'message' => $message,
            'used_ai' => false,
        ]);

        $dbAnswer = $this->tryDatabaseLookup($message);

        if ($dbAnswer !== null) {
            $this->storeAssistantReply(
                $sessionId,
                $userId,
                $dbAnswer,
                usedAi: false
            );

            return [
                'message' => $dbAnswer,
                'used_ai' => false,
            ];
        }

        try {
            $answer = $this->askGemini($message);

            $this->storeAssistantReply(
                $sessionId,
                $userId,
                $answer,
                usedAi: true
            );

            return [
                'message' => $answer,
                'used_ai' => true,
            ];
        } catch (\Throwable $e) {
            \Log::error('[NexoraAI] Chat AI request failed', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            $message = 'Nexora AI is temporarily unavailable. Please try again in a moment.';

            $this->storeAssistantReply(
                $sessionId,
                $userId,
                $message,
                usedAi: false
            );

            return [
                'message' => $message,
                'used_ai' => false,
            ];
        }
    }
    protected function tryDatabaseLookup(string $message): ?string
    {
        $q = Str::lower($message);

        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        if (Str::contains($q, ['out of stock', 'out-of-stock'])) {
            $items = $this->inventoryService->outOfStockItems();

            if ($items->isEmpty()) {
                return 'There are currently no products out of stock.';
            }

            $answer = 'There are currently ' . $items->count()
                . " products out of stock.\n\n";

            foreach ($items as $item) {
                $answer .= '- **' . $item['name'] . "** — Out of stock.\n";
            }

            return trim($answer);
        }

        if (Str::contains($q, ['low stock', 'low-stock'])) {
            $items = $this->inventoryService->lowStockItems();

            if ($items->isEmpty()) {
                return 'There are currently no low-stock products.';
            }

            $answer = 'There are currently ' . $items->count()
                . " products below their reorder threshold.\n\n";

            foreach ($items as $item) {
                $answer .= '- **' . $item['name'] . "** — Low stock.\n";
            }

            return trim($answer);
        }

        /*
        |--------------------------------------------------------------------------
        | Finance
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, 'revenue')
            && Str::contains($q, 'today')
        ) {
            $trend = $this->salesService->revenueTrend(1);
            $revenue = $trend[0]['total'] ?? 0;

            return 'Today\'s revenue is ₱'
                . number_format($revenue, 2) . '.';
        }

        if (Str::contains($q, 'profit margin')) {
            return 'The current profit margin is '
                . $this->financeService->profitMarginPercent() . '%.';
        }

        if (
            Str::contains($q, 'overdue')
            && Str::contains($q, ['payment', 'payments', 'invoice', 'invoices'])
        ) {
            return 'There are '
                . $this->financeService->overduePaymentsCount()
                . ' overdue payment(s) totaling ₱'
                . number_format(
                    $this->financeService->overduePaymentsTotal(),
                    2
                )
                . '.';
        }

        /*
        |--------------------------------------------------------------------------
        | Manufacturing
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'machines down',
                'machine down',
                'machine status',
                'downtime',
            ])
        ) {
            return 'There are currently '
                . $this->manufacturingService->machinesDownCount()
                . ' machine(s) down, with '
                . $this->manufacturingService->totalDowntimeMinutesToday()
                . ' total downtime minutes today.';
        }

        /*
        |--------------------------------------------------------------------------
        | Compliance and Risk
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, ['high severity', 'high-severity'])
            && Str::contains($q, ['risk', 'risks'])
        ) {
            $count = $this->complianceService->highSeverityRisksCount();

            if ($count === 0) {
                return 'There are currently no high or critical severity risks.';
            }

            return 'There are currently ' . $count
                . ' high or critical severity risk(s).';
        }

        if (
            Str::contains($q, [
                'open risks',
                'compliance risks',
                'risk count',
            ])
        ) {
            return 'There are currently '
                . $this->complianceService->openRisksCount()
                . ' open compliance risk(s), '
                . $this->complianceService->highSeverityRisksCount()
                . ' of which are high or critical severity.';
        }

        /*
        |--------------------------------------------------------------------------
        | Procurement
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'open orders',
                'open purchase orders',
                'procurement orders',
            ])
        ) {
            return 'There are currently '
                . $this->procurementService->openOrdersCount()
                . ' open procurement order(s) worth ₱'
                . number_format(
                    $this->procurementService->openOrdersValue(),
                    2
                )
                . '.';
        }

        /*
        |--------------------------------------------------------------------------
        | ITSM
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'open tickets',
                'itsm tickets',
                'support tickets',
            ])
        ) {
            return 'There are currently '
                . $this->itsmService->openTicketsCount()
                . ' open ITSM ticket(s).';
        }

        return null;
    }

    protected function askGemini(string $message): string
    {
        $context = $this->reportGenerator->getCurrentReport() ?? [
            'note' => 'No AI report has been generated yet.',
        ];

        $response = $this->router->provider()->generate(
            $this->promptBuilder->systemPrompt(),
            $this->promptBuilder->chatPrompt(
                $message,
                $context
            ),
        );

        return $response['content'];
    }

    protected function storeAssistantReply(
        string $sessionId,
        ?int $userId,
        string $message,
        bool $usedAi
    ): void {
        AIConversation::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'role' => 'assistant',
            'message' => $message,
            'used_ai' => $usedAi,
        ]);
    }
}
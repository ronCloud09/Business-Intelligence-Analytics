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
        $q = Str::lower(trim($message));

        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'out of stock',
                'out-of-stock',
                'stocked out',
                'no stock',
            ])
        ) {
            $count = $this->inventoryService->outOfStockCount();

            return "There are currently {$count} item(s) out of stock.";
        }

        if (
            Str::contains($q, [
                'low stock',
                'low-stock',
                'running low',
                'below reorder',
            ])
        ) {
            $count = $this->inventoryService->lowStockCount();

            return "There are currently {$count} item(s) at or below their reorder threshold.";
        }

        if (
            Str::contains($q, 'inventory') &&
            Str::contains($q, ['value', 'worth'])
        ) {
            return 'The current inventory value is ₱'
                . number_format(
                    $this->inventoryService->totalInventoryValue(),
                    2
                ) . '.';
        }

        if (
            Str::contains($q, ['how many', 'total']) &&
            Str::contains($q, ['sku', 'products', 'inventory items'])
        ) {
            return 'There are currently '
                . $this->inventoryService->totalSkus()
                . ' SKU(s) in inventory.';
        }

        /*
        |--------------------------------------------------------------------------
        | FINANCE
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'profit margin',
                'current margin',
            ])
        ) {
            return 'The current profit margin is '
                . $this->financeService->profitMarginPercent()
                . '%.';
        }

        if (
            Str::contains($q, 'overdue') &&
            Str::contains($q, [
                'payment',
                'payments',
                'invoice',
                'invoices',
                'receivable',
                'receivables',
            ])
        ) {
            $count = $this->financeService->overduePaymentsCount();
            $total = $this->financeService->overduePaymentsTotal();

            return "There are {$count} overdue payment(s) totaling ₱"
                . number_format($total, 2) . '.';
        }

        /*
        |--------------------------------------------------------------------------
        | MANUFACTURING
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'machines down',
                'machine down',
                'machine status',
                'machines offline',
                'machine offline',
            ])
        ) {
            $count = $this->manufacturingService->machinesDownCount();

            return "There are currently {$count} machine(s) down.";
        }

        if (
            Str::contains($q, [
                'downtime',
                'downtime today',
                'production downtime',
                'machine downtime',
            ])
        ) {
            $minutes = $this->manufacturingService
                ->totalDowntimeMinutesToday();

            return "There are currently {$minutes} total downtime minutes today.";
        }

        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'open purchase orders',
                'open procurement orders',
                'procurement orders open',
                'purchase orders open',
                'open orders',
            ])
        ) {
            $count = $this->procurementService->openOrdersCount();

            return "There are currently {$count} open procurement order(s).";
        }

        if (
            Str::contains($q, [
                'procurement',
                'purchase order',
                'purchase orders',
            ]) &&
            Str::contains($q, [
                'value',
                'worth',
                'total amount',
            ])
        ) {
            $value = $this->procurementService->openOrdersValue();

            return 'Open procurement orders are currently worth ₱'
                . number_format($value, 2) . '.';
        }

        /*
        |--------------------------------------------------------------------------
        | COMPLIANCE AND RISK
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'high severity risk',
                'high severity risks',
                'high risk',
                'high risks',
                'critical risk',
                'critical risks',
                'serious risks',
            ])
        ) {
            $count = $this->complianceService
                ->highSeverityRisksCount();

            if ($count <= 0) {
                return 'There are currently no high or critical severity risks.';
            }

            return "There are currently {$count} high or critical severity risk(s).";
        }

        if (
            Str::contains($q, [
                'open risks',
                'open risk',
                'compliance risks',
                'compliance risk',
                'risk count',
                'how many risks',
            ])
        ) {
            $open = $this->complianceService->openRisksCount();
            $high = $this->complianceService
                ->highSeverityRisksCount();

            return "There are currently {$open} open compliance risk(s). "
                . "{$high} are high or critical severity.";
        }

        /*
        |--------------------------------------------------------------------------
        | ITSM
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'open tickets',
                'open ticket',
                'itsm tickets',
                'itsm ticket',
                'support tickets',
                'support ticket',
                'unresolved tickets',
                'unresolved ticket',
                'it tickets',
            ])
        ) {
            $count = $this->itsmService->openTicketsCount();

            return "There are currently {$count} open ITSM ticket(s).";
        }

        /*
        |--------------------------------------------------------------------------
        | SALES
        |--------------------------------------------------------------------------
        */

        if (
            Str::contains($q, [
                'revenue',
                'sales',
            ]) &&
            Str::contains($q, [
                'today',
                'today\'s',
                'current day',
            ])
        ) {
            $trend = $this->salesService->revenueTrend(1);
            $revenue = $trend[0]['total'] ?? 0;

            return 'Today\'s revenue is ₱'
                . number_format($revenue, 2) . '.';
        }

        /*
        |--------------------------------------------------------------------------
        | NO DIRECT DATABASE MATCH
        |--------------------------------------------------------------------------
        */

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
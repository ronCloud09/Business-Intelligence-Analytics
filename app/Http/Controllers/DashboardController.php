<?php

namespace App\Http\Controllers;

use App\Services\Departments\ComplianceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\SalesService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected SalesService $salesService,
        protected ManufacturingService $manufacturingService,
        protected ComplianceService $complianceService,
    ) {
    }

    /**
     * Show the Executive Dashboard.
     *
     * Every card is computed here from real department services — the
     * single source of truth shared with the AI aggregators. Order
     * Fulfillment isn't connected yet, so that portion of Operational
     * Efficiency stays a clearly-labeled placeholder until that
     * department's data is synced in, same pattern as Manufacturing
     * was before it was connected.
     */
    public function index(): View
    {
        $finance = $this->financeService->getSnapshot();
        $inventory = $this->inventoryService->getSnapshot();
        $sales = $this->salesService->getSnapshot();

        $totalRevenue = $sales['total_revenue'];
        $grossProfit = $finance['revenue'] - $finance['expenses'];
        $totalOrders = $sales['total_orders'];
        $inventoryValue = $inventory['inventory_value'];

        $kpis = [
            [
                'icon' => 'dollar-sign',
                'label' => 'Total Revenue',
                'value' => '₱' . number_format($totalRevenue, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'pie-chart',
                'label' => 'Gross Profit',
                'value' => '₱' . number_format($grossProfit, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'shopping-cart',
                'label' => 'Orders',
                'value' => number_format($totalOrders),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'package',
                'label' => 'Inventory Value',
                'value' => '₱' . number_format($inventoryValue, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'truck',
                'label' => 'On-Time Delivery',
                'value' => 'N/A',
                'change' => 'Pending Fulfillment sync',
                'change_class' => 'change-up',
            ],
        ];

        $topProducts = $this->salesService->topProductsDetailed(10);

        $operationalEfficiency = $this->buildOperationalEfficiency();

        return view('dashboard', [
            'kpis' => $kpis,
            'topProducts' => $topProducts,
            'operationalEfficiency' => $operationalEfficiency,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildOperationalEfficiency(): array
    {
        $completionRate = $this->manufacturingService->completionRatePercent();
        $qualityRate = $this->manufacturingService->qcPassRatePercent();
        $overdueBuilds = $this->manufacturingService->overdueBuildsCount();
        $manufacturingHealth = round(($completionRate + $qualityRate) / 2, 1);

        // Order Fulfillment isn't connected yet — placeholder pending sync.
        $fulfillmentRate = 91.3;
        $delayedShipments = '—';
        $returnRate = '—';

        $overallHealth = round(($manufacturingHealth + $fulfillmentRate) / 2, 1);

        [$overallStatus, $overallClass] = $this->healthStatus($overallHealth);
        [$mfgStatus, $mfgClass] = $this->healthStatus($manufacturingHealth);
        [$flfStatus, $flfClass] = $this->healthStatus($fulfillmentRate);

        $openRisks = $this->complianceService->openRisksCount();
        $risksBySeverity = $this->complianceService->risksBySeverity();
        $totalSeverity = array_sum($risksBySeverity) ?: 1;
        $criticalPct = (int) round((($risksBySeverity['critical'] ?? 0) / $totalSeverity) * 100);
        $warningPct = (int) round((($risksBySeverity['high'] ?? 0) / $totalSeverity) * 100);
        $minorPct = max(0, 100 - $criticalPct - $warningPct);

        return [
            'overall' => [
                'percent' => $overallHealth,
                'status' => $overallStatus,
                'class' => $overallClass,
            ],
            'summary_text' => "Manufacturing is running at {$completionRate}% completion with a {$qualityRate}% QC pass rate. Fulfillment metrics are pending integration with the Order Fulfillment system.",
            'manufacturing' => [
                'percent' => $manufacturingHealth,
                'health' => $mfgStatus,
                'class' => $mfgClass,
                'detail' => 'Live data synced from the Manufacturing department.',
                'metrics' => [
                    ['icon' => 'check-circle', 'label' => 'Completion Rate', 'value' => $completionRate . '%'],
                    ['icon' => 'shield-check', 'label' => 'Quality Pass Rate', 'value' => $qualityRate . '%'],
                    ['icon' => 'clock-alert', 'label' => 'Overdue Builds', 'value' => $overdueBuilds],
                ],
            ],
            'fulfillment' => [
                'percent' => $fulfillmentRate,
                'health' => $flfStatus,
                'class' => $flfClass,
                'detail' => 'Placeholder — pending Order Fulfillment department connection.',
                'metrics' => [
                    ['icon' => 'package-check', 'label' => 'Fulfillment Rate', 'value' => $fulfillmentRate . '%'],
                    ['icon' => 'clock-alert', 'label' => 'Delayed Shipments', 'value' => $delayedShipments],
                    ['icon' => 'rotate-ccw', 'label' => 'Return Rate (30d)', 'value' => $returnRate],
                ],
            ],
            'risks' => [
                'total_active' => $openRisks,
                'severity_breakdown' => [
                    'critical' => $criticalPct,
                    'warning' => $warningPct,
                    'minor' => $minorPct,
                ],
                'top_issues' => $this->complianceService->topIssuesByAge(5),
            ],
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function healthStatus(float $value): array
    {
        if ($value >= 80) {
            return ['Healthy', 'health-green'];
        }

        if ($value >= 60) {
            return ['Stable', 'health-yellow'];
        }

        if ($value >= 40) {
            return ['Warning', 'health-orange'];
        }

        return ['Critical', 'health-red'];
    }
}

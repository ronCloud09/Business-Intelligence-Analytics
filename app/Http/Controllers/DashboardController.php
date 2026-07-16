<?php

namespace App\Http\Controllers;

use App\Services\Departments\ComplianceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\SalesService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected SalesService $salesService,
        protected ManufacturingService $manufacturingService,
        protected ComplianceService $complianceService,
        protected FulfillmentService $fulfillmentService,
    ) {
    }

    /**
     * Show the Executive Dashboard.
     *
     * Every card is computed here from real department services — the
     * single source of truth shared with the AI aggregators.
     */
    public function index(): View
    {
        // Cache the heavy department snapshots for 60 seconds
        $finance = Cache::remember('dashboard_finance_snapshot', 60, fn () => $this->financeService->getSnapshot());
        $inventory = Cache::remember('dashboard_inventory_snapshot', 60, fn () => $this->inventoryService->getSnapshot());
        $sales = Cache::remember('dashboard_sales_snapshot', 60, fn () => $this->salesService->getSnapshot());
        $topProducts = Cache::remember('dashboard_top_products', 60, fn () => $this->salesService->topProductsDetailed(10));

        $totalRevenue = $sales['total_revenue'];
        $grossProfit = $finance['revenue'] - $finance['expenses'];
        $totalOrders = $sales['total_orders'];
        $inventoryValue = $inventory['inventory_value'];

        $fulfillmentRate = Cache::remember('dashboard_fulfillment_rate', 60, fn () => $this->fulfillmentService->fulfillmentRatePercent());

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
                'value' => $fulfillmentRate !== null ? $fulfillmentRate . '%' : 'N/A',
                'change' => $fulfillmentRate !== null
                    ? ''
                    : $this->fulfillmentService->pendingOrdersCount() . ' orders queued, no shipments yet',
                'change_class' => 'change-up',
            ],
        ];

        $operationalEfficiency = Cache::remember('dashboard_operational_efficiency', 60, fn () => $this->buildOperationalEfficiency());

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

        $fulfillmentRate = $this->fulfillmentService->fulfillmentRatePercent();
        $delayedShipments = $this->fulfillmentService->delayedShipmentsCount();
        $hasFulfillmentData = $fulfillmentRate !== null;

        $fulfillmentHealthValue = $hasFulfillmentData ? $fulfillmentRate : null;

        [$overallStatus, $overallClass, $overallHealth] = $this->computeOverall($manufacturingHealth, $fulfillmentHealthValue);
        [$mfgStatus, $mfgClass] = $this->healthStatus($manufacturingHealth);

        if ($hasFulfillmentData) {
            [$flfStatus, $flfClass] = $this->healthStatus($fulfillmentRate);
            $flfPercent = $fulfillmentRate;
        } else {
            $flfStatus = 'No Data';
            $flfClass = 'health-yellow';
            $flfPercent = 0;
        }

        $openRisks = $this->complianceService->openRisksCount();
        $risksBySeverity = $this->complianceService->risksBySeverity();
        $totalSeverity = array_sum($risksBySeverity) ?: 1;
        $criticalPct = (int) round((($risksBySeverity['critical'] ?? 0) / $totalSeverity) * 100);
        $warningPct = (int) round((($risksBySeverity['high'] ?? 0) / $totalSeverity) * 100);
        $minorPct = max(0, 100 - $criticalPct - $warningPct);

        $lowStockMaterials = $this->fulfillmentService->lowStockPackingMaterialsCount();

        $summaryText = "Manufacturing is running at {$completionRate}% completion with a {$qualityRate}% QC pass rate. ";
        $summaryText .= $hasFulfillmentData
            ? "Fulfillment is at {$fulfillmentRate}% on-time."
            : 'Order Fulfillment has ' . $this->fulfillmentService->pendingOrdersCount() . ' orders queued with no shipment history yet.';

        return [
            'overall' => [
                'percent' => $overallHealth,
                'status' => $overallStatus,
                'class' => $overallClass,
            ],
            'summary_text' => $summaryText,
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
                'percent' => $flfPercent,
                'health' => $flfStatus,
                'class' => $flfClass,
                'detail' => $hasFulfillmentData
                    ? 'Live data synced from the Order Fulfillment department.'
                    : 'No shipments recorded yet — metrics will populate once deliveries begin.',
                'metrics' => [
                    ['icon' => 'package-check', 'label' => 'Fulfillment Rate', 'value' => $hasFulfillmentData ? $fulfillmentRate . '%' : 'No data yet'],
                    ['icon' => 'clock-alert', 'label' => 'Delayed Shipments', 'value' => $delayedShipments ?? 'No data yet'],
                    ['icon' => 'box', 'label' => 'Low Stock Packing Supplies', 'value' => $lowStockMaterials],
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

    protected function computeOverall(float $manufacturingHealth, ?float $fulfillmentHealth): array
    {
        $overallHealth = $fulfillmentHealth === null
            ? $manufacturingHealth
            : round(($manufacturingHealth + $fulfillmentHealth) / 2, 1);

        [$status, $class] = $this->healthStatus($overallHealth);

        return [$status, $class, $overallHealth];
    }

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
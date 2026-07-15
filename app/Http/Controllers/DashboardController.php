<?php

namespace App\Http\Controllers;

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
    ) {
    }

    public function index(): View
    {
        $finance = $this->financeService->getSnapshot();
        $inventory = $this->inventoryService->getSnapshot();
        $sales = $this->salesService->getSnapshot();
        $workOrderStatus = $this->manufacturingService->workOrderStatusBreakdown();
        $qcVerdicts = $this->manufacturingService->qcVerdictBreakdown();

        return view('dashboard', [
            'totalRevenue' => $sales['total_revenue'],
            'grossProfit' => $finance['revenue'] - $finance['expenses'],
            'totalOrders' => $sales['total_orders'],
            'inventoryValue' => $inventory['inventory_value'],
            'topProducts' => $sales['top_products'],
            'workOrderStatusLabels' => array_keys($workOrderStatus),
            'workOrderStatusValues' => array_values($workOrderStatus),
            'qcVerdictLabels' => array_keys($qcVerdicts),
            'qcVerdictValues' => array_values($qcVerdicts),
        ]);
    }
}

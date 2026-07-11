<?php

namespace App\Http\Controllers;

use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\SalesService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected SalesService $salesService,
    ) {}

    /**
     * Show the Executive Dashboard.
     *
     * KPI cards are backed by the department services (single source of
     * truth shared with the AI aggregators). Chart/forecast widgets that
     * depend on modules outside the current scope (logistics, vendor
     * performance) remain visual placeholders until those modules exist.
     */
    public function index(): View
    {
        $finance = $this->financeService->getSnapshot();
        $inventory = $this->inventoryService->getSnapshot();
        $sales = $this->salesService->getSnapshot();

        return view('dashboard', [
            'totalRevenue' => $sales['total_revenue'],
            'grossProfit' => $finance['revenue'] - $finance['expenses'],
            'totalOrders' => $sales['total_orders'],
            'inventoryValue' => $inventory['inventory_value'],
            'topProducts' => $sales['top_products'],
        ]);
    }
}

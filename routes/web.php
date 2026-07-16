<?php

use App\Models\InventoryDeptStockLevel;
use App\Models\ManufacturingMachine;
use App\Models\ManufacturingWorkOrder;
use App\Models\FulfillmentShipment;
use App\Models\FulfillmentPackingMaterial;
use App\Models\ComplianceRisk;
use App\Models\ItsmTicket;
use App\Models\ProcurementOrder;
use App\Models\FinanceDeptInvoice;

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Services\DataService;

// LIVE MONITOR

use Illuminate\Http\Request;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\SalesService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\ComplianceService;
use App\Services\Departments\ItsmService;

Route::get('/api/live-feed', function () {
    $financeService = app(App\Services\Departments\FinanceService::class);
    $inventoryService = app(App\Services\Departments\InventoryService::class);
    $manufacturingService = app(App\Services\Departments\ManufacturingService::class);
    $fulfillmentService = app(App\Services\Departments\FulfillmentService::class);
    $complianceService = app(App\Services\Departments\ComplianceService::class);
    $procurementService = app(App\Services\Departments\ProcurementService::class);
    $itsmService = app(App\Services\Departments\ItsmService::class);

    $alerts = [];
    $critical = $warning = $info = 0;

    $latestTimestamp = function ($model, $column = 'updated_at') {
        try {
            $record = $model::latest($column)->first();
            return $record && $record->$column ? $record->$column->toIso8601String() : now()->toIso8601String();
        } catch (\Throwable) {
            return now()->toIso8601String();
        }
    };

    // Inventory — low stock
    $lowStock = $inventoryService->lowStockCount();
    if ($lowStock > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'alert-triangle',
            'department' => 'Inventory',
            'title' => "{$lowStock} Items Low Stock",
            'description' => 'Stock levels have fallen below reorder thresholds.',
            'timestamp' => $latestTimestamp(InventoryDeptStockLevel::class),
            'metrics' => [
                ['label' => 'Low Stock', 'value' => $lowStock],
                ['label' => 'Out of Stock', 'value' => $inventoryService->outOfStockCount()],
            ],
        ];
    }

    // Manufacturing — machines down
    $down = $manufacturingService->machinesDownCount();
    if ($down > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'cpu',
            'department' => 'Manufacturing',
            'title' => "{$down} Machines Down",
            'description' => 'Production equipment offline or under maintenance.',
            'timestamp' => $latestTimestamp(ManufacturingMachine::class, 'last_status_change_at'),
            'metrics' => [
                ['label' => 'Down', 'value' => $down],
                ['label' => 'Completion Rate', 'value' => $manufacturingService->completionRatePercent() . '%'],
            ],
        ];
    }

    // Manufacturing — overdue builds
    $overdue = $manufacturingService->overdueBuildsCount();
    if ($overdue > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'clock-alert',
            'department' => 'Manufacturing',
            'title' => "{$overdue} Overdue Builds",
            'description' => 'Work orders past their due date.',
            'timestamp' => $latestTimestamp(ManufacturingWorkOrder::class),
            'metrics' => [
                ['label' => 'Overdue', 'value' => $overdue],
                ['label' => 'QC Pass Rate', 'value' => $manufacturingService->qcPassRatePercent() . '%'],
            ],
        ];
    }

    // Procurement — open orders
    $openPO = $procurementService->openOrdersCount();
    if ($openPO > 0) {
        $info++;
        $alerts[] = [
            'severity' => 'info',
            'icon' => 'file-text',
            'department' => 'Procurement',
            'title' => "{$openPO} Open Purchase Orders",
            'description' => 'Active purchase orders awaiting delivery or approval.',
            'timestamp' => $latestTimestamp(ProcurementOrder::class, 'created_at'),
            'metrics' => [
                ['label' => 'Open POs', 'value' => $openPO],
                ['label' => 'Total Value', 'value' => '₱' . number_format($procurementService->openOrdersValue())],
            ],
        ];
    }

    // Fulfillment — delayed shipments
    $delayed = $fulfillmentService->delayedShipmentsCount();
    if ($delayed && $delayed > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'truck',
            'department' => 'Fulfillment',
            'title' => "{$delayed} Delayed Shipments",
            'description' => 'Shipments past their due date.',
            'timestamp' => $latestTimestamp(FulfillmentShipment::class),
            'metrics' => [
                ['label' => 'Delayed', 'value' => $delayed],
                ['label' => 'Fulfillment Rate', 'value' => $fulfillmentService->fulfillmentRatePercent() . '%'],
            ],
        ];
    }

    // Fulfillment — low packing materials
    $lowPacking = $fulfillmentService->lowStockPackingMaterialsCount();
    if ($lowPacking > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'box',
            'department' => 'Fulfillment',
            'title' => "{$lowPacking} Packing Materials Low",
            'description' => 'Packing supplies below reorder threshold.',
            'timestamp' => $latestTimestamp(FulfillmentPackingMaterial::class),
            'metrics' => [
                ['label' => 'Low Stock', 'value' => $lowPacking],
                ['label' => 'Pending Orders', 'value' => $fulfillmentService->pendingOrdersCount()],
            ],
        ];
    }

    // Compliance — open risks
    $openRisks = $complianceService->openRisksCount();
    if ($openRisks > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'shield',
            'department' => 'Compliance',
            'title' => "{$openRisks} Open Compliance Risks",
            'description' => 'Active risks requiring review.',
            'timestamp' => $latestTimestamp(ComplianceRisk::class, 'identified_date'),
            'metrics' => [
                ['label' => 'Open Risks', 'value' => $openRisks],
                ['label' => 'High Severity', 'value' => $complianceService->highSeverityRisksCount()],
            ],
        ];
    }

    // ITSM — open tickets
    $openTickets = $itsmService->openTicketsCount();
    if ($openTickets > 0) {
        $info++;
        $alerts[] = [
            'severity' => 'info',
            'icon' => 'ticket',
            'department' => 'ITSM',
            'title' => "{$openTickets} Open Tickets",
            'description' => 'Active IT service tickets requiring attention.',
            'timestamp' => $latestTimestamp(ItsmTicket::class, 'opened_at'),
            'metrics' => [
                ['label' => 'Open', 'value' => $openTickets],
                ['label' => 'Critical', 'value' => $itsmService->criticalTicketsCount()],
            ],
        ];
    }

    // Finance — overdue payments
    $overduePayments = $financeService->overduePaymentsCount();
    if ($overduePayments > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'dollar-sign',
            'department' => 'Finance',
            'title' => "{$overduePayments} Overdue Invoices",
            'description' => 'Payments past due.',
            'timestamp' => $latestTimestamp(FinanceDeptInvoice::class),
            'metrics' => [
                ['label' => 'Overdue', 'value' => $overduePayments],
                ['label' => 'Total Due', 'value' => '₱' . number_format($financeService->overduePaymentsTotal())],
            ],
        ];
    }
    
    // Sort alerts by timestamp descending (most recent first)
    usort($alerts, fn($a, $b) => strtotime($b['timestamp']) - strtotime($a['timestamp']));

    return response()->json([
        'alerts' => $alerts,
        'summary' => [
            'critical' => $critical,
            'warning' => $warning,
            'info' => $info,
        ],
    ]);
});
// LIVE MONITOR PAGE END BLOCK

// Home redirects to signin
Route::redirect('/', '/signin')->name('home');

// Sign-in page
Route::get('/signin', function () {
    return view('signIn');
})->name('signin');

// Contact us page
Route::get('/contactus', function () {
    return view('contactus');
})->name('contactus');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// AI Insights
Route::get('/ai-insights', [AIInsightsController::class, 'index'])
    ->name('ai-insights');

// Department Analytics
Route::get('/department-analytics', function () {
    return view('department-analytics', [
        'departments' => DataService::getDepartmentList(),
    ]);
})->name('department-analytics');

// Department Analytics API
Route::get('/api/department/{dept}', function ($dept) {
    return response()->json(DataService::getDepartment($dept));
});

// Live Monitor Page
Route::get('/live-monitor', function () {
    return view('live-monitor');
})->name('live-monitor');

// Login processing
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

// NEXORA AI — foundation endpoints
Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});
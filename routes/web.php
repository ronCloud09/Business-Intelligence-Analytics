<?php

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentAnalyticsController;
use Illuminate\Support\Facades\Route;

use App\Services\DataService;

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
Route::get('/dashboard', function () {
    return view('dashboard', [
        'kpis' => DataService::getKpis(),
        'forecastBoxes' => DataService::getForecastBoxes(),
        'salesForecast' => DataService::getSalesForecast(),
        'topProducts' => DataService::getTopProducts(),
        'operationalEfficiency' => DataService::getOperationalEfficiency(),
        'briefAlerts' => DataService::getBriefAlerts(),
    ]);
})->name('dashboard');

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

// ============================================================
// LIVE DATA API (DEMO ONLY - DELETE WHEN REAL DB IS CONNECTED)
// ============================================================
Route::get('/api/live-data', function () {

    $scenario = request('scenario', 'random');
    $range = request('range', '7d');

    $formatChange = function($change, $isPercent = true) {
        if ($change == 0) return '0%';
        $prefix = $change >= 0 ? '↑ ' : '↓ ';
        $suffix = $isPercent ? '%' : '';
        return $prefix . abs($change) . $suffix;
    };
    $changeClass = function($change) {
        if ($change == 0) return 'change-neutral';
        return $change >= 0 ? 'change-up' : 'change-down';
    };
    
    $prev = cache('live_data_prev', [
        'revenue' => 2350000, 'gross_profit' => 830000, 'orders' => 3800,
        'inventory' => 1100000, 'delivery' => 91.5, 'repeat' => 42.5,
        'demand' => 12, 'growth' => 12, 'completion' => 94, 'quality' => 97.5,
        'fulfillment' => 92.0, 'delayed' => 45, 'returns' => 3.2, 'overdue' => 5,
    ]);

    // DEMO: Force values into specific ranges based on scenario button
    // DELETE this whole switch block when connecting real database
    switch($scenario) {
        case 'healthy':
            $completionRate = rand(90, 99);
            $qualityRate = rand(93, 100);
            $fulfillmentRate = rand(88, 99);
            $revenue = $prev['revenue'] + rand(30000, 100000);
            $orders = $prev['orders'] + rand(50, 200);
            $onTimeDelivery = rand(92, 98) . '.' . rand(1, 9);
            $delayedShipments = rand(10, 30);
            $returnRate = rand(1, 3) . '.' . rand(1, 9);
            $overdueBuilds = rand(0, 2);
            $grossProfit = round($revenue * (rand(33, 40) / 100));
            $inventoryValue = $prev['inventory'] + rand(20000, 60000);
            $repeatPurchaseRate = rand(45, 55) . '.' . rand(1, 9);
            $highDemandProducts = rand(15, 25);
            $revenueGrowth = rand(12, 20);
            break;
        case 'stable':
            $completionRate = rand(75, 89);
            $qualityRate = rand(80, 95);
            $fulfillmentRate = rand(70, 89);
            $revenue = $prev['revenue'] + rand(-30000, 30000);
            $orders = $prev['orders'] + rand(-100, 100);
            $onTimeDelivery = rand(80, 92) . '.' . rand(1, 9);
            $delayedShipments = rand(30, 50);
            $returnRate = rand(3, 6) . '.' . rand(1, 9);
            $overdueBuilds = rand(2, 5);
            $grossProfit = round($revenue * (rand(30, 38) / 100));
            $inventoryValue = $prev['inventory'] + rand(-30000, 30000);
            $repeatPurchaseRate = rand(35, 48) . '.' . rand(1, 9);
            $highDemandProducts = rand(8, 16);
            $revenueGrowth = rand(6, 14);
            break;
        case 'warning':
            $completionRate = rand(55, 74);
            $qualityRate = rand(60, 82);
            $fulfillmentRate = rand(50, 69);
            $revenue = $prev['revenue'] + rand(-80000, -10000);
            $orders = $prev['orders'] + rand(-200, -50);
            $onTimeDelivery = rand(65, 80) . '.' . rand(1, 9);
            $delayedShipments = rand(50, 70);
            $returnRate = rand(6, 9) . '.' . rand(1, 9);
            $overdueBuilds = rand(5, 10);
            $grossProfit = round($revenue * (rand(28, 35) / 100));
            $inventoryValue = $prev['inventory'] + rand(-50000, -10000);
            $repeatPurchaseRate = rand(25, 38) . '.' . rand(1, 9);
            $highDemandProducts = rand(4, 10);
            $revenueGrowth = rand(2, 8);
            break;
        case 'critical':
            $completionRate = rand(25, 54);
            $qualityRate = rand(30, 62);
            $fulfillmentRate = rand(20, 49);
            $revenue = $prev['revenue'] + rand(-150000, -50000);
            $orders = $prev['orders'] + rand(-400, -100);
            $onTimeDelivery = rand(40, 65) . '.' . rand(1, 9);
            $delayedShipments = rand(70, 90);
            $returnRate = rand(9, 15) . '.' . rand(1, 9);
            $overdueBuilds = rand(10, 20);
            $grossProfit = round($revenue * (rand(20, 30) / 100));
            $inventoryValue = $prev['inventory'] + rand(-80000, -30000);
            $repeatPurchaseRate = rand(15, 28) . '.' . rand(1, 9);
            $highDemandProducts = rand(2, 6);
            $revenueGrowth = rand(1, 5);
            break;
        default: // random
            $completionRate = rand(40, 99);
            $qualityRate = rand(50, 100);
            $fulfillmentRate = rand(35, 99);
            $revenue = $prev['revenue'] + rand(-80000, 100000);
            $orders = $prev['orders'] + rand(-200, 200);
            $onTimeDelivery = rand(50, 98) . '.' . rand(1, 9);
            $delayedShipments = rand(20, 80);
            $returnRate = rand(2, 12) . '.' . rand(1, 9);
            $overdueBuilds = rand(0, 15);
            $grossProfit = round($revenue * (rand(28, 38) / 100));
            $inventoryValue = $prev['inventory'] + rand(-50000, 50000);
            $repeatPurchaseRate = rand(25, 55) . '.' . rand(1, 9);
            $highDemandProducts = rand(5, 25);
            $revenueGrowth = rand(5, 20);
    }
    // END DEMO BLOCK

    // Clean up decimal values
    $qualityRate = round((float)$qualityRate, 1);
    $fulfillmentRate = round((float)$fulfillmentRate, 1);
    $onTimeDelivery = round((float)$onTimeDelivery, 1);
    $returnRate = round((float)$returnRate, 1);
    $repeatPurchaseRate = round((float)$repeatPurchaseRate, 1);
    
    $revenueChange = round((($revenue - $prev['revenue']) / $prev['revenue']) * 100, 1);
    $profitChange = round((($grossProfit - $prev['gross_profit']) / $prev['gross_profit']) * 100, 1);
    $ordersChange = round((($orders - $prev['orders']) / $prev['orders']) * 100, 1);
    $inventoryChange = round((($inventoryValue - $prev['inventory']) / $prev['inventory']) * 100, 1);
    $deliveryChange = round($onTimeDelivery - $prev['delivery'], 1);
    $repeatChange = round($repeatPurchaseRate - $prev['repeat'], 1);
    $demandChange = $highDemandProducts - $prev['demand'];
    $growthChange = $revenueGrowth - $prev['growth'];
    
    cache(['live_data_prev' => [
        'revenue' => $revenue, 'gross_profit' => $grossProfit, 'orders' => $orders,
        'inventory' => $inventoryValue, 'delivery' => $onTimeDelivery, 'repeat' => $repeatPurchaseRate,
        'demand' => $highDemandProducts, 'growth' => $revenueGrowth, 'completion' => $completionRate,
        'quality' => $qualityRate, 'fulfillment' => $fulfillmentRate, 'delayed' => $delayedShipments,
        'returns' => $returnRate, 'overdue' => $overdueBuilds,
    ]], now()->addHours(1));
    
    $overallHealth = round(($completionRate + $qualityRate + $fulfillmentRate) / 3, 1);
    $mfgHealth = round(($completionRate + $qualityRate) / 2, 1);
    
    // Green: 100-80 | Yellow: 79-60 | Orange: 59-40 | Red: below 40
    $getHealthStatus = function($val) {
        if ($val >= 80) return ['Healthy', 'health-green'];
        if ($val >= 60) return ['Stable', 'health-yellow'];
        if ($val >= 40) return ['Warning', 'health-orange'];
        return ['Critical', 'health-red'];
    };
    
    [$overallStatus, $overallClass] = $getHealthStatus($overallHealth);
    [$mfgStatus, $mfgClass] = $getHealthStatus($mfgHealth);
    [$flfStatus, $flfClass] = $getHealthStatus($fulfillmentRate);

    $salesData = match($range) {
        '7d' => ['labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 'sales' => array_map(fn() => rand(100, 250), range(1,7))],
        '1m' => ['labels' => ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], 'sales' => array_map(fn() => rand(800, 1400), range(1,4))],
        '1y' => ['labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'], 'sales' => array_map(fn() => rand(8000, 13000), range(1,12))],
        default => ['labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 'sales' => array_map(fn() => rand(100, 250), range(1,7))],
    };
    
    return response()->json([
        'revenue' => '₱' . number_format($revenue),
        'revenue_change' => $formatChange($revenueChange),
        'revenue_class' => $changeClass($revenueChange),
        'gross_profit' => '₱' . number_format($grossProfit),
        'profit_change' => $formatChange($profitChange),
        'profit_class' => $changeClass($profitChange),
        'orders' => number_format($orders),
        'orders_change' => $formatChange($ordersChange),
        'orders_class' => $changeClass($ordersChange),
        'inventory_value' => '₱' . number_format($inventoryValue),
        'inventory_change' => $formatChange($inventoryChange),
        'inventory_class' => $changeClass($inventoryChange),
        'on_time_delivery' => $onTimeDelivery . '%',
        'delivery_change' => $formatChange($deliveryChange),
        'delivery_class' => $changeClass($deliveryChange),
        'repeat_purchase_rate' => $repeatPurchaseRate . '%',
        'repeat_change' => $formatChange($repeatChange),
        'repeat_class' => $changeClass($repeatChange),
        'high_demand_products' => $highDemandProducts,
        'demand_change' => $formatChange($demandChange, false),
        'demand_class' => $changeClass($demandChange),
        'revenue_growth' => '↑ ' . $revenueGrowth . '%',
        'growth_change' => $formatChange($growthChange),
        'growth_class' => $changeClass($growthChange),
        'overall_percent' => $overallHealth,
        'overall_status' => $overallStatus,
        'overall_class' => $overallClass,
        'mfg_percent' => $mfgHealth,
        'mfg_status' => $mfgStatus,
        'mfg_class' => $mfgClass,
        'mfg_completion' => $completionRate . '%',
        'mfg_quality' => $qualityRate . '%',
        'mfg_overdue' => $overdueBuilds,
        'flf_percent' => $fulfillmentRate,
        'flf_status' => $flfStatus,
        'flf_class' => $flfClass,
        'flf_fulfillment' => $fulfillmentRate . '%',
        'flf_delayed' => $delayedShipments,
        'flf_returns' => $returnRate . '%',
        'sales_labels' => $salesData['labels'],
        'sales_data' => $salesData['sales'],
        'updated_at' => now()->format('H:i:s'),
        'top_products' => [
            ['name' => 'Gaming PC Alpha', 'units_sold' => rand(200, 260), 'prev_units' => 210, 'revenue' => rand(450000, 520000), 'coverage' => rand(10, 30), 'stock_status' => 'Low Stock', 'stock_class' => 'bg-high'],
            ['name' => 'RTX 4060 GPU', 'units_sold' => rand(160, 210), 'prev_units' => 200, 'revenue' => rand(250000, 300000), 'coverage' => rand(15, 35), 'stock_status' => 'Low Stock', 'stock_class' => 'bg-high'],
            ['name' => 'Gaming Monitor 27"', 'units_sold' => rand(140, 180), 'prev_units' => 145, 'revenue' => rand(180000, 230000), 'coverage' => rand(45, 65), 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => 'Mechanical Keyboard', 'units_sold' => rand(120, 160), 'prev_units' => 130, 'revenue' => rand(110000, 150000), 'coverage' => rand(70, 85), 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Gaming Mouse Pro', 'units_sold' => rand(110, 150), 'prev_units' => 140, 'revenue' => rand(70000, 90000), 'coverage' => rand(80, 95), 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'USB-C Headset', 'units_sold' => rand(100, 130), 'prev_units' => 100, 'revenue' => rand(80000, 100000), 'coverage' => rand(40, 55), 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => '1TB NVMe SSD', 'units_sold' => rand(90, 120), 'prev_units' => 95, 'revenue' => rand(140000, 170000), 'coverage' => rand(85, 98), 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Gaming Chair Pro', 'units_sold' => rand(80, 100), 'prev_units' => 88, 'revenue' => rand(250000, 290000), 'coverage' => rand(60, 80), 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Webcam 4K', 'units_sold' => rand(65, 85), 'prev_units' => 85, 'revenue' => rand(55000, 70000), 'coverage' => rand(25, 45), 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => 'WiFi 6 Router', 'units_sold' => rand(50, 75), 'prev_units' => 55, 'revenue' => rand(85000, 105000), 'coverage' => rand(88, 99), 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
        ],
    ]);
})->name('api.live-data');

// Login processing
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

// NEXORA AI — foundation endpoints (Package 1-2). Full Intelligence
// Center UI and manual refresh button are wired in later packages.
Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});

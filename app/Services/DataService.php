<?php

namespace App\Services;

class DataService
{
    // ============================================================
    // DASHBOARD KPI CARDS
    // ============================================================
    public static function getKpis(): array
    {
        return [
            [
                'icon' => 'dollar-sign',
                'label' => 'Total Revenue',
                'value' => self::formatCurrency(0),
                'change' => '0%',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'pie-chart',
                'label' => 'Gross Profit',
                'value' => self::formatCurrency(0),
                'change' => '0%',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'shopping-cart',
                'label' => 'Orders',
                'value' => '0',
                'change' => '0%',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'package',
                'label' => 'Inventory Value',
                'value' => self::formatCurrency(0),
                'change' => '0%',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'truck',
                'label' => 'On-Time Delivery',
                'value' => '0%',
                'change' => '0%',
                'change_class' => 'change-up',
            ],
        ];
    }

    // ============================================================
    // FORECAST SUB-BOXES
    // ============================================================
    public static function getSalesForecast(): array
    {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'sales' => [0, 0, 0, 0, 0, 0, 0],
            'forecast' => [0, 0, 0, 0, 0, 0, 0],
        ];
    }

    public static function getForecastBoxes(): array
    {
        return [
            ['icon' => 'clock', 'label' => 'Forecast Accuracy', 'value' => '0%', 'change' => '0%', 'change_class' => 'change-up'],
            ['icon' => 'star', 'label' => 'High Demand Products', 'value' => '0', 'change' => '0', 'change_class' => 'change-up'],
            ['icon' => 'trending-up', 'label' => 'Revenue Growth', 'value' => '0%', 'change' => '0%', 'change_class' => 'change-up'],
        ];
    }

    // ============================================================
    // TOP 10 PRODUCTS
    // ============================================================
    public static function getTopProducts(): array
    {
        return [
            ['name' => 'Gaming PC Alpha', 'units_sold' => 240, 'prev_units' => 210, 'revenue' => 480000, 'coverage' => 15, 'stock_status' => 'Low Stock', 'stock_class' => 'bg-high'],
            ['name' => 'RTX 4060 GPU', 'units_sold' => 185, 'prev_units' => 200, 'revenue' => 277500, 'coverage' => 22, 'stock_status' => 'Low Stock', 'stock_class' => 'bg-high'],
            ['name' => 'Gaming Monitor 27"', 'units_sold' => 160, 'prev_units' => 145, 'revenue' => 208000, 'coverage' => 55, 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => 'Mechanical Keyboard', 'units_sold' => 145, 'prev_units' => 130, 'revenue' => 130500, 'coverage' => 78, 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Gaming Mouse Pro', 'units_sold' => 132, 'prev_units' => 140, 'revenue' => 79200, 'coverage' => 85, 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'USB-C Headset', 'units_sold' => 118, 'prev_units' => 100, 'revenue' => 94400, 'coverage' => 48, 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => '1TB NVMe SSD', 'units_sold' => 105, 'prev_units' => 95, 'revenue' => 157500, 'coverage' => 90, 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Gaming Chair Pro', 'units_sold' => 92, 'prev_units' => 88, 'revenue' => 276000, 'coverage' => 70, 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
            ['name' => 'Webcam 4K', 'units_sold' => 78, 'prev_units' => 85, 'revenue' => 62400, 'coverage' => 35, 'stock_status' => 'Adequate', 'stock_class' => 'bg-med'],
            ['name' => 'WiFi 6 Router', 'units_sold' => 65, 'prev_units' => 55, 'revenue' => 97500, 'coverage' => 92, 'stock_status' => 'In Stock', 'stock_class' => 'bg-low'],
        ];
    }

    // ============================================================
    // OPERATIONAL EFFICIENCY
    // ============================================================
    public static function getOperationalEfficiency(): array
    {
        $completionRate = 94;
        $qualityRate = 98.2;
        $overdueBuilds = 3;
        $fulfillmentRate = 91.3;
        $delayedShipments = 47;
        $returnRate = 2.1;


        $overallHealth = round(($completionRate + $qualityRate + $fulfillmentRate) / 3, 1);
        $manufacturingHealth = round(($completionRate + $qualityRate) / 2, 1);


        $getHealthStatus = function ($val) {
            if ($val >= 80)
                return ['Healthy', 'health-green'];
            if ($val >= 60)
                return ['Stable', 'health-yellow'];
            if ($val >= 40)
                return ['Warning', 'health-orange'];
            return ['Critical', 'health-red'];
        };

        [$overallStatus, $overallClass] = $getHealthStatus($overallHealth);
        [$mfgStatus, $mfgClass] = $getHealthStatus($manufacturingHealth);
        [$flfStatus, $flfClass] = $getHealthStatus($fulfillmentRate);

        return [
            'overall' => [
                'percent' => $overallHealth,
                'status' => $overallStatus,
                'class' => $overallClass,
            ],
            'summary_text' => 'Operations are performing well overall. Key metrics are within acceptable thresholds with minor deviations in fulfillment timelines.',
            'manufacturing' => [
                'percent' => $manufacturingHealth,
                'health' => $mfgStatus,
                'class' => $mfgClass,
                'detail' => 'Production output is on track.',
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
                'detail' => 'Fulfillment is stable with minor delays.',
                'metrics' => [
                    ['icon' => 'package-check', 'label' => 'Fulfillment Rate', 'value' => $fulfillmentRate . '%'],
                    ['icon' => 'clock-alert', 'label' => 'Delayed Shipments', 'value' => $delayedShipments],
                    ['icon' => 'rotate-ccw', 'label' => 'Return Rate (30d)', 'value' => $returnRate . '%'],
                ],
            ],
            'risks' => '18 missing parts • 12 delayed shipments • 3 overdue builds • 5 supplier delays • 2 quality holds',
        ];
    }

    // ============================================================
    // SYSTEM ALERTS
    // ============================================================
    public static function getAlerts(): array
    {
        return [];
    }

    public static function getBriefAlerts(): array
    {
        return [];
    }

    // ============================================================
    // AI INSIGHTS
    // ============================================================
    public static function getExecutiveSummary(): array
    {
        return [];
    }

    public static function getRecommendations(): array
    {
        return [];
    }

    public static function getRisks(): array
    {
        return [];
    }

    // ============================================================
    // DEPARTMENT ANALYTICS
    // ============================================================
    public static function getDepartment(string $dept): array
    {
        return [
            'title' => $dept,
            'desc' => '',
            'stats' => [],
            'leftTitle' => 'Chart 1',
            'rightTitle' => 'Chart 2',
            'bottomCards' => [],
        ];
    }

    public static function getDepartmentList(): array
    {
        return [
            'itsm' => 'ITSM, Compliance & Risk Mgmt',
            'ecommerce' => 'E-Commerce & CRM',
            'inventory' => 'Inventory & Warehouse',
            'manufacturing' => 'Manufacturing & Productions',
            'bi' => 'Business Intelligence & Analytics',
            'procurement' => 'Procurement',
            'finance' => 'Finance & Accounting',
            'fulfillment' => 'Order Fulfillment',
            'hr' => 'Human Resources',
        ];
    }

    // ============================================================
    // AI CHAT BOT
    // ============================================================
    public static function getAiResponse(string $message): string
    {
        return 'AI module is ready. Ask me about your business data.';
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private static function formatCurrency($amount): string
    {
        return '₱' . number_format($amount, 0);
    }
}
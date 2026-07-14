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
        return [];
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

        // Calculate health percentages
        $overallHealth = round(($completionRate + $qualityRate + $fulfillmentRate) / 3, 1);
        $manufacturingHealth = round(($completionRate + $qualityRate) / 2, 1);
        $fulfillmentHealth = round($fulfillmentRate, 1);

        // Determine status based on percentages
        $overallStatus = $overallHealth >= 90 ? 'Healthy' : ($overallHealth >= 75 ? 'Stable' : 'At Risk');
        $overallClass = $overallHealth >= 90 ? 'health-green' : ($overallHealth >= 75 ? 'health-orange' : 'health-red');
        $mfgStatus = $manufacturingHealth >= 90 ? 'Good' : ($manufacturingHealth >= 75 ? 'Stable' : 'At Risk');
        $mfgClass = $manufacturingHealth >= 90 ? 'health-green' : ($manufacturingHealth >= 75 ? 'health-orange' : 'health-red');
        $flfStatus = $fulfillmentHealth >= 90 ? 'Good' : ($fulfillmentHealth >= 75 ? 'Stable' : 'At Risk');
        $flfClass = $fulfillmentHealth >= 90 ? 'health-green' : ($fulfillmentHealth >= 75 ? 'health-orange' : 'health-red');

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
                'detail' => 'Production output is on track. Line B is operating at 86% efficiency.',
                'metrics' => [
                    ['icon' => 'check-circle', 'label' => 'Completion Rate', 'value' => $completionRate . '%'],
                    ['icon' => 'shield-check', 'label' => 'Quality Pass Rate', 'value' => $qualityRate . '%'],
                    ['icon' => 'clock-alert', 'label' => 'Overdue Builds', 'value' => $overdueBuilds],
                ],
            ],
            'fulfillment' => [
                'percent' => $fulfillmentHealth,
                'health' => $flfStatus,
                'class' => $flfClass,
                'detail' => 'Fulfillment is stable with minor delays in Metro Manila zone.',
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
    // SYSTEM ALERTS (Dashboard & AI Insights)
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
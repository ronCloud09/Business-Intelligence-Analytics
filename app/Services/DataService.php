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
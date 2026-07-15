@extends('layouts.app')

@section('content')
    <div id="dashboard-view" class="tab-content active-tab" style="display:block;">
        <div class="subheader-bar">
            <div class="subheader-title">
                <h3>Executive Dashboard</h3>
                <p>Real-time overview of business performance, sales forecast, and operational efficiency.</p>
            </div>
            <div class="subheader-controls">
                <div class="control-date-selector">
                    <i data-lucide="calendar" class="control-icon-sm"></i>
                    {{ now()->format('M d') }} - {{ now()->addDays(7)->format('M d, Y') }}
                </div>
                <button class="control-btn" data-tip="Refresh Data">
                    <i data-lucide="refresh-cw" class="control-icon"></i>
                </button>
            </div>
        </div>
        <div class="content-container">
            <section class="kpi-grid">
                @foreach($kpis as $kpi)
                    <div class="kpi-card">
                        <div class="kpi-icon-container"><i data-lucide="{{ $kpi['icon'] }}" class="kpi-icon"></i></div>
                        <div class="kpi-details">
                            <div class="kpi-label">{{ $kpi['label'] }}</div>
                            <div class="kpi-value">{{ $kpi['value'] }}</div>
                            <div class="kpi-change {{ $kpi['change_class'] }}">{{ $kpi['change'] }}</div>
                        </div>
                    </div>
                @endforeach
            </section>

            <div class="dashboard-layout-grid">
                <div class="section-column">
                    <div class="ui-card" style="flex: none;">
                        <div class="card-header">
                            <div class="card-title">Historical Sales Trend <span class="info-dot"
                                    data-tooltip="Tracks sales performance over the selected time period.">i</span></div>
                            <select id="salesRange" class="control-date-selector chart-range-select"
                                onchange="changeSalesRange()">
                                <option value="7d">7 Days</option>
                                <option value="1m">1 Month</option>
                                <option value="1y">1 Year</option>
                            </select>
                        </div>
                        <div class="placeholder-graph-box chart-box"><canvas id="salesTrendChart"></canvas></div>
                        <div class="forecast-sub-row">
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="rotate-cw" class="sub-icon"></i>Repeat Purchase
                                    Rate</div>
                                <div class="sub-box-val" id="subRepeatRate">0%</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subRepeatChange">↑ 0%</div>
                            </div>
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="star" class="sub-icon"></i>High Demand Products
                                </div>
                                <div class="sub-box-val" id="subHighDemand">0</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subDemandChange">↑ 0</div>
                            </div>
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="trending-up" class="sub-icon"></i>Revenue Growth
                                </div>
                                <div class="sub-box-val" id="subRevenueGrowth">0%</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subGrowthChange">↑ 0%</div>
                            </div>
                        </div>
                    </div>

                    <div class="ui-card fixed-target-height-card" style="flex: 1;">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Products Driving Growth <span class="info-dot"
                                        data-tooltip="Top selling products ranked by units sold this month with inventory coverage and revenue data.">i</span>
                                </div>
                                <p style="font-size: 11px; color: var(--slate-500); margin-top: 2px;">Top 10 Products</p>
                            </div>
                        </div>
                        <div class="scrollable-card-body">
                            <table class="product-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Units Sold</th>
                                        <th>vs Last 30 Days</th>
                                        <th>Inventory Coverage</th>
                                        <th>Stock Status</th>
                                        <th class="text-right">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $product)
                                        @php
                                            $change = $product['prev_units'] > 0 ? round((($product['units_sold'] - $product['prev_units']) / $product['prev_units']) * 100) : 0;
                                            $coverageClass = $product['coverage'] >= 80 ? 'health-green' : ($product['coverage'] >= 60 ? 'health-yellow' : ($product['coverage'] >= 40 ? 'health-orange' : 'health-red'));
                                            $stockDays = round($product['coverage'] * 0.365);
                                            $stockTooltip = match ($product['stock_status']) {
                                                'Low Stock' => 'Below reorder threshold. Restock urgently.',
                                                'Adequate' => 'Stock is sufficient but monitor closely.',
                                                default => 'Stock levels are healthy.'
                                            };
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $index + 1 }}.</strong> {{ $product['name'] }}</td>
                                            <td>{{ number_format($product['units_sold']) }}</td>
                                            <td>
                                                <span class="{{ $change >= 0 ? 'change-up' : 'change-down' }}"
                                                    data-tip="{{ number_format($product['prev_units']) }} units last month → {{ number_format($product['units_sold']) }} units this month">
                                                    {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <div class="coverage-cell"
                                                    data-tip="{{ $stockDays }} days of stock remaining at current sales rate">
                                                    <span class="coverage-text">{{ $product['coverage'] }}%</span>
                                                    <div class="coverage-bar">
                                                        <div class="coverage-fill {{ $coverageClass }}"
                                                            style="width: {{ $product['coverage'] }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge {{ $product['stock_class'] }}"
                                                    data-tip="{{ $stockTooltip }}">{{ $product['stock_status'] }}</span></td>
                                            <td class="text-right">₱{{ number_format($product['revenue']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center;color:var(--slate-500);padding:2rem;">No
                                                product data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="section-column">
                    <div class="ui-card top-row-card" style="flex:1; min-height:0;">
                        <div class="card-header">
                            <div class="card-title">Operational Efficiency <span class="info-dot"
                                    data-tooltip="Comprehensive overview of operational health, manufacturing, and fulfillment performance.">i</span>
                            </div>
                        </div>
                        <div class="op-health-row">
                            <div class="op-health-card">
                                <div class="op-donut op-donut-lg">
                                    <svg viewBox="0 0 36 36" class="op-donut-svg">
                                        <path class="op-donut-track"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="op-donut-fill {{ $operationalEfficiency['overall']['class'] }}"
                                            stroke-dasharray="{{ $operationalEfficiency['overall']['percent'] }}, 100"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <span
                                        class="op-donut-text op-donut-text-lg">{{ $operationalEfficiency['overall']['percent'] }}%</span>
                                </div>
                                <div class="op-health-info">
                                    <h4>Overall Efficiency</h4>
                                    <div class="op-health-badge {{ $operationalEfficiency['overall']['class'] }}">
                                        {{ $operationalEfficiency['overall']['status'] }}</div>
                                </div>
                            </div>
                            <div class="op-summary-card">
                                <div class="op-summary-header">
                                    <i data-lucide="check-circle" class="op-summary-check"></i>
                                    <h4>Operations Summary</h4>
                                </div>
                                <p>{{ $operationalEfficiency['summary_text'] }}</p>
                            </div>
                        </div>
                        <div class="op-health-row">
                            <div class="op-dept-full-card">
                                <div class="op-health-card" style="border:none; padding:0 0 0.75rem 0;">
                                    <div class="op-donut">
                                        <svg viewBox="0 0 36 36" class="op-donut-svg">
                                            <path class="op-donut-track"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path
                                                class="op-donut-fill {{ $operationalEfficiency['manufacturing']['class'] }}"
                                                stroke-dasharray="{{ $operationalEfficiency['manufacturing']['percent'] }}, 100"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span
                                            class="op-donut-text">{{ $operationalEfficiency['manufacturing']['percent'] }}%</span>
                                    </div>
                                    <div class="op-health-info">
                                        <h4>Manufacturing Health</h4>
                                        <div class="op-health-badge {{ $operationalEfficiency['manufacturing']['class'] }}">
                                            {{ $operationalEfficiency['manufacturing']['health'] }}</div>
                                    </div>
                                </div>
                                <div class="op-metrics-mini">
                                    @foreach($operationalEfficiency['manufacturing']['metrics'] as $metric)
                                        <div class="op-metric-item">
                                            <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                            <span class="op-metric-label">{{ $metric['label'] }}</span>
                                            <span class="op-metric-val">{{ $metric['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="op-dept-full-card">
                                <div class="op-health-card" style="border:none; padding:0 0 0.75rem 0;">
                                    <div class="op-donut">
                                        <svg viewBox="0 0 36 36" class="op-donut-svg">
                                            <path class="op-donut-track"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="op-donut-fill {{ $operationalEfficiency['fulfillment']['class'] }}"
                                                stroke-dasharray="{{ $operationalEfficiency['fulfillment']['percent'] }}, 100"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span
                                            class="op-donut-text">{{ $operationalEfficiency['fulfillment']['percent'] }}%</span>
                                    </div>
                                    <div class="op-health-info">
                                        <h4>Order Fulfillment Health</h4>
                                        <div class="op-health-badge {{ $operationalEfficiency['fulfillment']['class'] }}">
                                            {{ $operationalEfficiency['fulfillment']['health'] }}</div>
                                    </div>
                                </div>
                                <div class="op-metrics-mini">
                                    @foreach($operationalEfficiency['fulfillment']['metrics'] as $metric)
                                        <div class="op-metric-item">
                                            <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                            <span class="op-metric-label">{{ $metric['label'] }}</span>
                                            <span class="op-metric-val">{{ $metric['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Row 3: Key Operational Risks --}}
                        <div class="op-risks-card">
                            <div class="op-risks-header">
                                <div class="op-risks-title-row">
                                    <i data-lucide="alert-triangle" class="op-risks-icon"></i>
                                    <h4>Key Operational Risks</h4>
                                    <span
                                        class="op-health-badge health-red">{{ $operationalEfficiency['risks']['total_active'] }}
                                        Active</span>
                                </div>
                                <a href="{{ route('ai-insights') }}" class="view-ai-btn">View All Risks</a>
                            </div>

                            <div class="op-severity-section">
                                <span class="op-severity-label">Severity Breakdown</span>
                                <div class="op-severity-bar">
                                    <div class="op-severity-seg health-red"
                                        style="width: {{ $operationalEfficiency['risks']['severity_breakdown']['critical'] }}%;">
                                    </div>
                                    <div class="op-severity-seg health-orange"
                                        style="width: {{ $operationalEfficiency['risks']['severity_breakdown']['warning'] }}%;">
                                    </div>
                                    <div class="op-severity-seg health-yellow"
                                        style="width: {{ $operationalEfficiency['risks']['severity_breakdown']['minor'] }}%;">
                                    </div>
                                </div>
                                <div class="op-severity-legend">
                                    <span><span class="op-legend-dot health-red"></span>Critical
                                        {{ $operationalEfficiency['risks']['severity_breakdown']['critical'] }}%</span>
                                    <span><span class="op-legend-dot health-orange"></span>Warning
                                        {{ $operationalEfficiency['risks']['severity_breakdown']['warning'] }}%</span>
                                    <span><span class="op-legend-dot health-yellow"></span>Minor
                                        {{ $operationalEfficiency['risks']['severity_breakdown']['minor'] }}%</span>
                                </div>
                            </div>

                            <div class="op-issues-section">
                                <span class="op-severity-label">Top Issues by Age</span>
                                <table class="op-issues-table">
                                    @foreach($operationalEfficiency['risks']['top_issues'] as $issue)
                                        <tr>
                                            <td>{{ $issue['issue'] }}</td>
                                            <td>{{ $issue['category'] }}</td>
                                            <td class="op-issues-days">
                                                <span
                                                    class="op-health-badge health-{{ $issue['days_open'] >= 10 ? 'red' : ($issue['days_open'] >= 5 ? 'orange' : 'yellow') }}">
                                                    {{ $issue['days_open'] }}d
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let salesTrendChart;

        // Draw a dashed vertical line at the hovered point
        const verticalLinePlugin = {
            id: 'verticalLine',
            afterDraw(chart) {
                if (chart.tooltip?._active?.length) {
                    const activePoint = chart.tooltip._active[0];
                    const ctx = chart.ctx;
                    const x = activePoint.element.x;
                    const topY = chart.scales.y.top;
                    const bottomY = chart.scales.y.bottom;

                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(x, topY);
                    ctx.lineTo(x, bottomY);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#1B6FC8';
                    ctx.setLineDash([4, 4]);
                    ctx.stroke();
                    ctx.restore();
                }
            }
        };

        function initSalesChart() {
            const ctx = document.getElementById('salesTrendChart');
            if (!ctx) return;

            // Start with 7-day labels so the x-axis is never blank
            salesTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#1B6FC8',
                        backgroundColor: 'rgba(27,111,200,0.15)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#1B6FC8',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0B1E3D',
                            bodyColor: '#0B1E3D',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            cornerRadius: 6,
                            displayColors: false,
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#E2E8F0' } },
                        x: { grid: { display: false } }
                    }
                },
                plugins: [verticalLinePlugin]
            });

            // Fetch real data immediately and update
            changeSalesRange();
        }

        async function changeSalesRange() {
            const range = document.getElementById('salesRange')?.value || '7d';
            try {
                const res = await fetch(`/api/sales-forecast?range=${range}`);
                const data = await res.json();
                if (salesTrendChart) {
                    salesTrendChart.data.labels = data.labels;
                    salesTrendChart.data.datasets[0].data = data.sales;
                    salesTrendChart.update();
                }
            } catch (e) {
                console.error('Failed to load forecast:', e);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initSalesChart();
        });
    </script>
@endsection
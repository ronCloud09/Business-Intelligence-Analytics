@extends('layouts.app')

@section('content')
<div id="dashboard-view" class="tab-content active-tab" style="display:block;">
    <div class="subheader-bar">
        <div class="subheader-title">
            <h3>Executive Dashboard</h3>
            <p>Real-time overview of business performance, sales forecast, and operational efficiency.</p>
        </div>
        <div class="subheader-controls">
            <div style="display:flex; gap:0.5rem; margin-top:0.5rem;">
                <button onclick="setScenario('healthy')" class="scenario-btn scenario-healthy">🟢 Healthy</button>
                <button onclick="setScenario('stable')" class="scenario-btn scenario-stable">🟡 Stable</button>
                <button onclick="setScenario('warning')" class="scenario-btn scenario-warning">🟠 Warning</button>
                <button onclick="setScenario('critical')" class="scenario-btn scenario-critical">🔴 Critical</button>
                <button onclick="setScenario('random')" class="scenario-btn scenario-random">🎲 Random</button>
            </div>
            <div class="control-date-selector">
                <i data-lucide="calendar" class="control-icon-sm"></i>
                {{ now()->format('M d') }} - {{ now()->addDays(7)->format('M d, Y') }}
            </div>
            <button class="control-btn" title="Refresh Data">
                <i data-lucide="refresh-cw" class="control-icon"></i>
            </button>
        </div>
    </div>
    <div class="content-container">

        {{-- AI Executive Brief --}}
        @if(!empty($briefAlerts))
        <div class="insight-card alerts-card-compact" id="dashboardAlertsCard">
            <div class="alerts-header-row">
                <div class="card-title" style="font-size:14px;">
                    <i data-lucide="sparkles" class="sub-icon" style="color: var(--corporate-blue);"></i>
                    AI Executive Brief
                    <span class="info-dot" data-tooltip="AI-generated summary of critical events and alerts across all departments.">i</span>
                </div>
                <a href="{{ route('ai-insights') }}" class="view-ai-btn">
                    <i data-lucide="arrow-right" class="view-ai-icon"></i>
                    View in AI Insights
                </a>
            </div>
            <p style="font-size:11px; color: var(--slate-500); margin-bottom: 0.75rem;">Here's what you need to know today</p>
            <div class="brief-alerts-grid">
                @foreach($briefAlerts as $alert)
                <div class="brief-alert-card brief-alert-{{ $alert['type'] }}">
                    <div class="brief-alert-card-icon">
                        <i data-lucide="{{ $alert['icon'] }}" class="brief-alert-icon"></i>
                    </div>
                    <strong>{{ $alert['title'] }}</strong>
                    <p>{{ $alert['detail'] }}</p>
                    <span class="alert-mini-time">{{ $alert['time'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- KPI Cards --}}
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
                {{-- Sales Intelligence --}}
                <div class="ui-card top-row-card">
                    <div class="card-header">
                        <div class="card-title">Historical Sales Trend <span class="info-dot" data-tooltip="Tracks sales performance over the selected time period.">i</span></div>
                        <select id="salesRange" class="control-date-selector chart-range-select" onchange="changeSalesRange()">
                            <option value="7d">7 Days</option>
                            <option value="1m">1 Month</option>
                            <option value="1y">1 Year</option>
                        </select>
                    </div>
                    <div class="placeholder-graph-box chart-box"><canvas id="salesTrendChart"></canvas></div>
                    <div class="forecast-sub-row">
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="rotate-cw" class="sub-icon"></i>Repeat Purchase Rate</div>
                            <div class="sub-box-val" id="subRepeatRate">0%</div>
                            <div class="kpi-change change-up" style="font-size:0.75rem;" id="subRepeatChange">↑ 0%</div>
                        </div>
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="star" class="sub-icon"></i>High Demand Products</div>
                            <div class="sub-box-val" id="subHighDemand">0</div>
                            <div class="kpi-change change-up" style="font-size:0.75rem;" id="subDemandChange">↑ 0</div>
                        </div>
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="trending-up" class="sub-icon"></i>Revenue Growth</div>
                            <div class="sub-box-val" id="subRevenueGrowth">0%</div>
                            <div class="kpi-change change-up" style="font-size:0.75rem;" id="subGrowthChange">↑ 0%</div>
                        </div>
                    </div>
                </div>
                {{-- Top Products --}}
                <div class="ui-card fixed-target-height-card">
                    <div class="card-header">
                        <div class="card-title">Top 10 Products This Month <span class="info-dot" data-tooltip="Highest-selling products ranked by units sold this month.">i</span></div>
                    </div>
                    <div class="scrollable-card-body">
                        <table>
                            <thead><tr><th>#</th><th>Product Name</th><th>Units Sold</th><th>Revenue</th><th>Stock Status</th></tr></thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product['rank'] }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['units_sold'] }}</td>
                                    <td>₱{{ number_format($product['revenue'], 0) }}</td>
                                    <td><span class="badge {{ $product['stock_class'] }}">{{ $product['stock_status'] }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" style="text-align:center;color:var(--slate-500);">No product data available</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Operational Efficiency --}}
            <div class="ui-card top-row-card" style="flex:1;">
                <div class="card-header">
                    <div class="card-title">Operational Efficiency <span class="info-dot" data-tooltip="Comprehensive overview of operational health, manufacturing, and fulfillment performance.">i</span></div>
                </div>

                {{-- Row 1: Overall Health Donut + Summary --}}
                <div class="op-health-row">
                    <div class="op-health-card">
                        <div class="op-donut">
                            <svg viewBox="0 0 36 36" class="op-donut-svg">
                                <path class="op-donut-track" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="op-donut-fill {{ $operationalEfficiency['overall']['class'] }}" 
                                    stroke-dasharray="{{ $operationalEfficiency['overall']['percent'] }}, 100"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <span class="op-donut-text">{{ $operationalEfficiency['overall']['percent'] }}%</span>
                        </div>
                        <div class="op-health-info">
                            <h4>Overall Efficiency</h4>
                            <div class="op-health-badge {{ $operationalEfficiency['overall']['class'] }}">{{ $operationalEfficiency['overall']['status'] }}</div>
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

                {{-- Row 2: Manufacturing + Fulfillment --}}
                <div class="op-health-row">
                    {{-- Manufacturing Card --}}
                    <div class="op-dept-full-card">
                        <div class="op-health-card" style="border:none; padding:0 0 0.625rem 0;">
                            <div class="op-donut op-donut-sm">
                                <svg viewBox="0 0 36 36" class="op-donut-svg">
                                    <path class="op-donut-track" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="op-donut-fill {{ $operationalEfficiency['manufacturing']['class'] }}" 
                                        stroke-dasharray="{{ $operationalEfficiency['manufacturing']['percent'] }}, 100"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="op-donut-text op-donut-text-sm">{{ $operationalEfficiency['manufacturing']['percent'] }}%</span>
                            </div>
                            <div class="op-health-info">
                                <h4>Manufacturing Health</h4>
                                <div class="op-health-badge {{ $operationalEfficiency['manufacturing']['class'] }}">{{ $operationalEfficiency['manufacturing']['health'] }}</div>
                                <span class="op-health-detail">{{ $operationalEfficiency['manufacturing']['detail'] }}</span>
                            </div>
                        </div>
                        <div class="op-metrics-mini">
                            @foreach($operationalEfficiency['manufacturing']['metrics'] as $metric)
                            <div class="op-metric-item">
                                <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                <span class="op-metric-val">{{ $metric['value'] }}</span>
                                <span class="op-metric-label">{{ $metric['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fulfillment Card --}}
                    <div class="op-dept-full-card">
                        <div class="op-health-card" style="border:none; padding:0 0 0.625rem 0;">
                            <div class="op-donut op-donut-sm">
                                <svg viewBox="0 0 36 36" class="op-donut-svg">
                                    <path class="op-donut-track" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="op-donut-fill {{ $operationalEfficiency['fulfillment']['class'] }}" 
                                        stroke-dasharray="{{ $operationalEfficiency['fulfillment']['percent'] }}, 100"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="op-donut-text op-donut-text-sm">{{ $operationalEfficiency['fulfillment']['percent'] }}%</span>
                            </div>
                            <div class="op-health-info">
                                <h4>Order Fulfillment Health</h4>
                                <div class="op-health-badge {{ $operationalEfficiency['fulfillment']['class'] }}">{{ $operationalEfficiency['fulfillment']['health'] }}</div>
                                <span class="op-health-detail">{{ $operationalEfficiency['fulfillment']['detail'] }}</span>
                            </div>
                        </div>
                        <div class="op-metrics-mini">
                            @foreach($operationalEfficiency['fulfillment']['metrics'] as $metric)
                            <div class="op-metric-item">
                                <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                <span class="op-metric-val">{{ $metric['value'] }}</span>
                                <span class="op-metric-label">{{ $metric['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Row 4: Key Operational Risks --}}
                <div class="op-risks-card">
                    <div class="op-risks-header">
                        <div class="op-risks-icon-wrap">
                            <i data-lucide="alert-triangle" class="op-risks-icon"></i>
                        </div>
                        <div>
                            <h4>Key Operational Risks</h4>
                            <p class="op-risks-sub">Active issues requiring attention</p>
                        </div>
                    </div>
                    <div class="op-risks-tags">
                        @foreach(explode(' • ', $operationalEfficiency['risks']) as $risk)
                        <span class="op-risk-tag">{{ $risk }}</span>
                        @endforeach
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

function initSalesChart() {
    const ctx = document.getElementById('salesTrendChart');
    if (!ctx) return;
    salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            datasets: [{
                label: 'Sales',
                data: [0,0,0,0,0,0,0],
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
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#E2E8F0' } }, x: { grid: { display: false } } }
        }
    });
}

function changeSalesRange() {
    fetchLiveData();
}

function updatePage(data) {
    // KPI cards with changes
    const kpiValues = document.querySelectorAll('.kpi-value');
    const kpiChanges = document.querySelectorAll('.kpi-change');
    if (kpiValues[0]) kpiValues[0].textContent = data.revenue;
    if (kpiChanges[0]) { kpiChanges[0].textContent = data.revenue_change; kpiChanges[0].className = 'kpi-change ' + data.revenue_class; }
    if (kpiValues[1]) kpiValues[1].textContent = data.gross_profit;
    if (kpiChanges[1]) { kpiChanges[1].textContent = data.profit_change; kpiChanges[1].className = 'kpi-change ' + data.profit_class; }
    if (kpiValues[2]) kpiValues[2].textContent = data.orders;
    if (kpiChanges[2]) { kpiChanges[2].textContent = data.orders_change; kpiChanges[2].className = 'kpi-change ' + data.orders_class; }
    if (kpiValues[3]) kpiValues[3].textContent = data.inventory_value;
    if (kpiChanges[3]) { kpiChanges[3].textContent = data.inventory_change; kpiChanges[3].className = 'kpi-change ' + data.inventory_class; }
    if (kpiValues[4]) kpiValues[4].textContent = data.on_time_delivery;
    if (kpiChanges[4]) { kpiChanges[4].textContent = data.delivery_change; kpiChanges[4].className = 'kpi-change ' + data.delivery_class; }

    // Sub boxes
    document.getElementById('subRepeatRate').textContent = data.repeat_purchase_rate;
    const repeatChange = document.getElementById('subRepeatChange');
    repeatChange.textContent = data.repeat_change;
    repeatChange.className = 'kpi-change ' + data.repeat_class;
    repeatChange.style.fontSize = '0.75rem';
    
    document.getElementById('subHighDemand').textContent = data.high_demand_products;
    const demandChange = document.getElementById('subDemandChange');
    demandChange.textContent = data.demand_change;
    demandChange.className = 'kpi-change ' + data.demand_class;
    demandChange.style.fontSize = '0.75rem';
    
    document.getElementById('subRevenueGrowth').textContent = data.revenue_growth;
    const growthChange = document.getElementById('subGrowthChange');
    growthChange.textContent = data.growth_change;
    growthChange.className = 'kpi-change ' + data.growth_class;
    growthChange.style.fontSize = '0.75rem';

    // Donut charts - update colors properly
    const donutTexts = document.querySelectorAll('.op-donut-text');
    const donutFills = document.querySelectorAll('.op-donut-fill');
    const statusTexts = document.querySelectorAll('.op-status-text');
    const badges = document.querySelectorAll('.op-health-badge');

        // Overall
    if (donutTexts[0]) donutTexts[0].textContent = data.overall_percent + '%';
    if (donutFills[0]) { 
        donutFills[0].setAttribute('stroke-dasharray', data.overall_percent + ', 100'); 
        donutFills[0].setAttribute('class', 'op-donut-fill ' + data.overall_class);
    }
    if (statusTexts[0]) { 
        statusTexts[0].textContent = data.overall_status; 
        statusTexts[0].setAttribute('class', 'op-status-text ' + data.overall_class);
    }

    if (badges[0]) { 
        badges[0].textContent = data.overall_status; 
        badges[0].setAttribute('class', 'op-health-badge ' + data.overall_class);
    }

        // Operations Summary - update text based on health
    const summaryText = document.querySelector('.op-summary-card p');
    if (summaryText) {
        if (data.overall_class === 'health-green') {
            summaryText.textContent = 'Operations are performing well across all departments. Key metrics are within optimal thresholds.';
        } else if (data.overall_class === 'health-yellow') {
            summaryText.textContent = 'Operations are stable with minor deviations. Some metrics require monitoring for potential issues.';
        } else if (data.overall_class === 'health-orange') {
            summaryText.textContent = 'Warning: Several operational metrics are below targets. Immediate attention may be required in affected areas.';
        } else {
            summaryText.textContent = 'Critical: Operations are significantly underperforming. Urgent action required across multiple departments.';
        }
    }

    // Manufacturing
    if (donutTexts[1]) donutTexts[1].textContent = data.mfg_percent + '%';
    if (donutFills[1]) { 
        donutFills[1].setAttribute('stroke-dasharray', data.mfg_percent + ', 100'); 
        donutFills[1].setAttribute('class', 'op-donut-fill ' + data.mfg_class);
    }
    if (badges[1]) { 
        badges[1].textContent = data.mfg_status; 
        badges[1].setAttribute('class', 'op-health-badge ' + data.mfg_class);
    }

    // Fulfillment
    if (donutTexts[2]) donutTexts[2].textContent = data.flf_percent + '%';
    if (donutFills[2]) { 
        donutFills[2].setAttribute('stroke-dasharray', data.flf_percent + ', 100'); 
        donutFills[2].setAttribute('class', 'op-donut-fill ' + data.flf_class);
    }
    if (badges[2]) { 
        badges[2].textContent = data.flf_status; 
        badges[2].setAttribute('class', 'op-health-badge ' + data.flf_class);
    }
    // Metrics
    const metricVals = document.querySelectorAll('.op-metric-val');
    if (metricVals[0]) metricVals[0].textContent = data.mfg_completion;
    if (metricVals[1]) metricVals[1].textContent = data.mfg_quality;
    if (metricVals[2]) metricVals[2].textContent = data.mfg_overdue;
    if (metricVals[3]) metricVals[3].textContent = data.flf_fulfillment;
    if (metricVals[4]) metricVals[4].textContent = data.flf_delayed;
    if (metricVals[5]) metricVals[5].textContent = data.flf_returns;

    // Sales chart
    if (salesTrendChart) {
        salesTrendChart.data.labels = data.sales_labels;
        salesTrendChart.data.datasets[0].data = data.sales_data;
        salesTrendChart.update();
    }
}

async function fetchLiveData() {
    try {
        const range = document.getElementById('salesRange')?.value || '7d';
        const res = await fetch('/api/live-data?range=' + range);
        const data = await res.json();
        updatePage(data);
    } catch(e) { console.log('Update:', e); }
}

document.addEventListener('DOMContentLoaded', () => {
    initSalesChart();
    fetchLiveData();
    setInterval(fetchLiveData, 5000);
});




let currentScenario = 'random';

function setScenario(scenario) {
    currentScenario = scenario;
    fetchLiveData();
}

// Update fetchLiveData to send scenario
async function fetchLiveData() {
    try {
        const range = document.getElementById('salesRange')?.value || '7d';
        const res = await fetch(`/api/live-data?range=${range}&scenario=${currentScenario}`);
        const data = await res.json();
        updatePage(data);
    } catch(e) { console.log('Update:', e); }
}
</script>
@endsection
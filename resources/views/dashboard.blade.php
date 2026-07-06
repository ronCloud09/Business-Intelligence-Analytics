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
                May 9 - May 15, 2026
            </div>
            <button class="control-btn" title="Refresh Data">
                <i data-lucide="refresh-cw" class="control-icon"></i>
            </button>
        </div>
    </div>
    <div class="content-container">
        <section class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon-container"><i data-lucide="dollar-sign" class="kpi-icon"></i></div>
                <div class="kpi-details">
                    <div class="kpi-label">Total Revenue</div>
                    <div class="kpi-value">₱2,400,000</div>
                    <div class="kpi-change change-up">↑ 12.25%</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-container"><i data-lucide="pie-chart" class="kpi-icon"></i></div>
                <div class="kpi-details">
                    <div class="kpi-label">Gross Profit</div>
                    <div class="kpi-value">₱840,432</div>
                    <div class="kpi-change change-up">↑ 9.3%</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-container"><i data-lucide="shopping-cart" class="kpi-icon"></i></div>
                <div class="kpi-details">
                    <div class="kpi-label">Orders</div>
                    <div class="kpi-value">3,842</div>
                    <div class="kpi-change change-up">↑ 8.7%</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-container"><i data-lucide="package" class="kpi-icon"></i></div>
                <div class="kpi-details">
                    <div class="kpi-label">Inventory Value</div>
                    <div class="kpi-value">₱1,125,860</div>
                    <div class="kpi-change change-up">↑ 8.7%</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon-container"><i data-lucide="truck" class="kpi-icon"></i></div>
                <div class="kpi-details">
                    <div class="kpi-label">On-Time Delivery</div>
                    <div class="kpi-value">91.3%</div>
                    <div class="kpi-change change-down">↓ 3.2%</div>
                </div>
            </div>
        </section>

        <div class="dashboard-layout-grid">
            <div class="section-column">
                <div class="ui-card top-row-card">
                    <div class="card-header">
                        <div class="card-title">Sales Intelligence (Forecast) <span class="info-dot" data-tooltip="Displays historical sales data alongside AI-powered forecast projections. The solid line shows actual sales, while the dashed line represents predicted future values based on trend analysis and machine learning models.">i</span></div>
                        <select id="salesForecastRange" class="control-date-selector chart-range-select" onchange="onSalesForecastRangeChange()">
                            <option value="7d">7 Days</option>
                            <option value="1m">1 Month</option>
                            <option value="1y">1 Year</option>
                            <option value="history">History</option>
                        </select>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-swatch legend-solid"></span>Sales</span>
                        <span class="legend-item"><span class="legend-swatch legend-dashed"></span>Forecast</span>
                    </div>
                    <div class="placeholder-graph-box chart-box"><canvas id="salesForecastChart"></canvas></div>
                    
                    <div class="forecast-sub-row">
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="clock" class="sub-icon"></i>Forecast Accuracy</div>
                            <div class="sub-box-val">92%</div>
                            <div class="kpi-change change-up" style="font-size: 0.75rem;">↑ 3.6%</div>
                        </div>
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="star" class="sub-icon"></i>High Demand Products</div>
                            <div class="sub-box-val">12</div>
                            <div class="kpi-change change-up" style="font-size: 0.75rem;">↑ 2 new lines</div>
                        </div>
                        <div class="sub-box">
                            <div class="sub-box-label"><i data-lucide="trending-up" class="sub-icon"></i>Revenue Growth</div>
                            <div class="sub-box-val">↑ 12%</div>
                            <div style="color: #64748b; font-size: 0.75rem;">Next Month</div>
                        </div>
                    </div>
                </div>

                <div class="ui-card fixed-target-height-card">
                    <div class="card-header">
                        <div class="card-title">Top 10 Products This Month <span class="info-dot" data-tooltip="Highest-selling products ranked by units sold this month. Includes revenue contribution and stock status to help identify which products are driving sales and which may need restocking.">i</span></div>
                    </div>
                    <div class="scrollable-card-body">
                        <table>
                            <thead>
                                <tr><th>#</th><th>Product Name</th><th>Units Sold</th><th>Revenue</th><th>Stock Status</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>1</td><td>Gaming PC Alpha</td><td>240</td><td>₱480,000</td><td><span class="badge bg-high">Low Stock</span></td></tr>
                                <tr><td>2</td><td>RTX 4060 GPU</td><td>185</td><td>₱277,500</td><td><span class="badge bg-high">Low Stock</span></td></tr>
                                <tr><td>3</td><td>Gaming Monitor 27"</td><td>160</td><td>₱208,000</td><td><span class="badge bg-med">Adequate</span></td></tr>
                                <tr><td>4</td><td>Mechanical Keyboard</td><td>145</td><td>₱130,500</td><td><span class="badge bg-low">In Stock</span></td></tr>
                                <tr><td>5</td><td>Gaming Mouse Pro</td><td>132</td><td>₱79,200</td><td><span class="badge bg-low">In Stock</span></td></tr>
                                <tr><td>6</td><td>USB-C Headset</td><td>118</td><td>₱94,400</td><td><span class="badge bg-med">Adequate</span></td></tr>
                                <tr><td>7</td><td>1TB NVMe SSD</td><td>105</td><td>₱157,500</td><td><span class="badge bg-low">In Stock</span></td></tr>
                                <tr><td>8</td><td>Gaming Chair Pro</td><td>92</td><td>₱276,000</td><td><span class="badge bg-low">In Stock</span></td></tr>
                                <tr><td>9</td><td>Webcam 4K</td><td>78</td><td>₱62,400</td><td><span class="badge bg-med">Adequate</span></td></tr>
                                <tr><td>10</td><td>WiFi 6 Router</td><td>65</td><td>₱97,500</td><td><span class="badge bg-low">In Stock</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section-column">
                <div class="ui-card top-row-card">
                    <div class="card-header">
                        <div class="card-title">Operational Efficiency <span class="info-dot" data-tooltip="Key metrics tracking warehouse and fulfillment performance. Overall Efficiency combines throughput, labor utilization, and order accuracy. Downtime represents the percentage of time machinery is offline due to maintenance or failures.">i</span></div>
                    </div>
                    <div class="op-efficiency-grid">
                        <div class="op-box">
                            <div class="op-box-icon"><i data-lucide="gauge" class="op-icon"></i></div>
                            <div class="op-meta"><h4>Overall Efficiency</h4><p>86%</p></div>
                        </div>
                        <div class="op-box">
                            <div class="op-box-icon"><i data-lucide="truck" class="op-icon"></i></div>
                            <div class="op-meta"><h4>Avg Fulfillment</h4><p>2.8 days</p></div>
                        </div>
                        <div class="op-box">
                            <div class="op-box-icon"><i data-lucide="check-circle" class="op-icon"></i></div>
                            <div class="op-meta"><h4>On-time Delivery</h4><p>91.3%</p></div>
                        </div>
                        <div class="op-box">
                            <div class="op-box-icon"><i data-lucide="alert-triangle" class="op-icon"></i></div>
                            <div class="op-meta"><h4>Total Downtime</h4><p>14.2%</p></div>
                        </div>
                    </div>
                    <div class="split-pie-placeholders">
                        <div class="pie-col">
                            <span class="label">Vendor Performance <span class="info-dot info-dot-inline" data-tooltip="Breakdown of supplier delivery performance. On-time deliveries meet the scheduled window, Late deliveries arrive outside the window but are still accepted, and Failed deliveries are rejected or returned.">i</span></span>
                            <div class="placeholder-graph-box chart-box"><canvas id="vendorPerformanceChart"></canvas></div>
                            
                        </div>
                        <div class="pie-col">
                            <span class="label">Machine Downtime <span class="info-dot info-dot-inline" data-tooltip="Real-time status of production machinery. Running indicates active operation, Idle means powered but not producing, and Down represents equipment offline due to maintenance, failure, or changeover.">i</span></span>
                            <div class="placeholder-graph-box chart-box"><canvas id="machineDowntimeChart"></canvas></div>
                            
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
    function parseCSVList(str) { return str.split(',').map(s => s.trim()).filter(s => s.length > 0); }
    function parseNumberList(str) { return parseCSVList(str).map(Number).filter(n => !isNaN(n)); }
    function parseNumberListWithNulls(str) { return str.split(',').map(s => { const trimmed = s.trim(); if (trimmed.length === 0) return null; const n = Number(trimmed); return isNaN(n) ? null : n; }); }
    const chartColors = ['#1B6FC8', '#4A9EE8', '#7BBEF0', '#16A34A', '#D97706', '#DC2626', '#0EA5E9'];
    let salesForecastChart, vendorPerformanceChart, machineDowntimeChart;
    const salesForecastPresets = {
        '7d': { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], sales: [120, 150, 90, 200, 170, null, null], forecast: [null, null, null, null, 170, 220, 180] },
        '1m': { labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'], sales: [980, 1120, 1045, null], forecast: [null, null, 1045, 1180] },
        '1y': { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], sales: [9800, 10200, 9700, 11000, 10800, 11500, null, null, null, null, null, null], forecast: [null, null, null, null, null, 11500, 11800, 12100, 12400, 12700, 13000, 13300] },
        'history': { labels: ['2021', '2022', '2023', '2024', '2025', '2026'], sales: [82000, 95000, 101000, 118000, 132000, null], forecast: [null, null, null, null, 132000, 145000] }
    };
    function buildSalesForecastDatasets(labels, sales, forecast) { return { labels: labels, datasets: [{ label: 'Sales', data: sales, borderColor: '#1B6FC8', backgroundColor: 'rgba(27, 111, 200, 0.1)', tension: 0.35, fill: true, pointRadius: 3, spanGaps: false }, { label: 'Forecast', data: forecast, borderColor: '#1B6FC8', backgroundColor: 'transparent', borderDash: [6, 5], tension: 0.35, fill: false, pointRadius: 3, spanGaps: true }] }; }
    function initSalesForecastChart() { const ctx = document.getElementById('salesForecastChart'); const preset = salesForecastPresets['7d']; salesForecastChart = new Chart(ctx, { type: 'line', data: buildSalesForecastDatasets(preset.labels, preset.sales, preset.forecast), options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } }); }
    function onSalesForecastRangeChange() { const range = document.getElementById('salesForecastRange').value; const preset = salesForecastPresets[range]; if (!preset) return; salesForecastChart.data = buildSalesForecastDatasets(preset.labels, preset.sales, preset.forecast); salesForecastChart.update(); }
    function updateSalesForecastChart() {
        const range = document.getElementById('salesForecastRange').value; const preset = salesForecastPresets[range]; const labels = preset.labels;
        const salesInput = document.getElementById('salesForecastValues').value.trim(); const forecastInput = document.getElementById('salesForecastPredictedValues').value.trim();
        if (salesInput === '' && forecastInput === '') { salesForecastChart.data = buildSalesForecastDatasets(labels, preset.sales, preset.forecast); salesForecastChart.update(); return; }
        const sales = salesInput ? parseNumberListWithNulls(salesInput) : [...preset.sales]; const forecast = forecastInput ? parseNumberListWithNulls(forecastInput) : [...preset.forecast];
        if (sales.length !== labels.length || (forecast.length > 0 && forecast.length !== labels.length)) { alert('Sales and forecast (if provided) must match the number of labels for the selected range (' + labels.length + ' entries).'); return; }
        salesForecastChart.data = buildSalesForecastDatasets(labels, sales, forecast.length ? forecast : labels.map(() => null)); salesForecastChart.update();
    }
    function makeDonutChart(canvasId, labels, values) { const ctx = document.getElementById(canvasId); return new Chart(ctx, { type: 'doughnut', data: { labels: labels, datasets: [{ data: values, backgroundColor: chartColors, borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } } } }); }
    function updateDonutChart(chart, labelsId, valuesId) { const labels = parseCSVList(document.getElementById(labelsId).value); const values = parseNumberList(document.getElementById(valuesId).value); if (labels.length === 0 || values.length === 0 || labels.length !== values.length) { alert('Please enter matching labels and values (e.g. On-time,Late,Failed and 70,20,10).'); return null; } chart.data.labels = labels; chart.data.datasets[0].data = values; chart.update(); return chart; }
    function updateVendorPerformanceChart() { updateDonutChart(vendorPerformanceChart, 'vendorPerformanceLabels', 'vendorPerformanceValues'); }
    function updateMachineDowntimeChart() { updateDonutChart(machineDowntimeChart, 'machineDowntimeLabels', 'machineDowntimeValues'); }
    document.addEventListener('DOMContentLoaded', () => { initSalesForecastChart(); vendorPerformanceChart = makeDonutChart('vendorPerformanceChart', ['On-time', 'Late', 'Failed'], [70, 20, 10]); machineDowntimeChart = makeDonutChart('machineDowntimeChart', ['Running', 'Idle', 'Down'], [60, 25, 15]); });
</script>   
@endsection
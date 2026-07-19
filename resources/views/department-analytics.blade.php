@extends('layouts.app')

@section('content')
    <div class="tab-content active-tab" style="display:block;">
        <div class="subheader-bar">
            <div class="subheader-title">
                <h3 id="deptTitle">Department Analytics</h3>
                <p id="deptDesc">Deep dive into each department's key performance indicators and trends.</p>
            </div>
            <div class="subheader-controls">
                <select id="deptSelector" class="control-date-selector chart-range-select" onchange="switchDepartment()"
                    style="width:280px;">
                    <option value="itsm">ITSM, Compliance & Risk Management</option>
                    <option value="ecommerce">E-Commerce</option>
                    <option value="inventory">Inventory & Warehouse</option>
                    <option value="manufacturing">Manufacturing & Productions</option>
                    <option value="bi">Business Intelligence & Analytics</option>
                    <option value="procurement">Procurement</option>
                    <option value="finance">Finance & Accounting</option>
                    <option value="fulfillment">Order Fulfillment</option>
                    <option value="hr">Human Resources</option>
                </select>
            </div>
        </div>
        <div class="content-container">
            <div class="dept-top-row">
                <div class="dept-big-card">
                    <div class="card-header">
                        <div class="card-title" id="deptCard1Title">Trend Overview</div>
                    </div>
                    <div class="placeholder-graph-box chart-box"><canvas id="deptChart1"></canvas></div>
                </div>
                <div class="dept-big-card">
                    <div class="card-header">
                        <div class="card-title" id="deptCard2Title">Performance Chart</div>
                    </div>
                    <div class="placeholder-graph-box chart-box"><canvas id="deptChart2"></canvas></div>
                </div>
                <div class="dept-stats-col" id="deptStats"></div>
            </div>
            <div class="dashboard-layout-grid" id="deptBottomRow"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function buildStats(data) {
            const stats = [];

            const mappings = {
                'catalog_value': { icon: 'dollar-sign', label: 'Catalog Value', format: v => '₱' + Number(v).toLocaleString() },
                'total_products': { icon: 'shopping-cart', label: 'Products Listed', format: v => Number(v).toLocaleString() },
                'sold_out_count': { icon: 'x-circle', label: 'Sold Out Items', format: v => v },
                'average_rating': { icon: 'star', label: 'Average Rating', format: v => Number(v).toFixed(2) },
                'total_skus': { icon: 'package', label: 'Total SKUs', format: v => v },
                'low_stock_count': { icon: 'alert-triangle', label: 'Low Stock Items', format: v => v },
                'inventory_value': { icon: 'package', label: 'Inventory Value', format: v => '₱' + Number(v).toLocaleString() },
                'open_orders': { icon: 'file-text', label: 'Open Orders', format: v => v },
                'open_orders_value': { icon: 'dollar-sign', label: 'Open Orders Value', format: v => '₱' + Number(v).toLocaleString() },
                'revenue': { icon: 'dollar-sign', label: 'Revenue', format: v => '₱' + Number(v).toLocaleString() },
                'expenses': { icon: 'trending-down', label: 'Expenses', format: v => '₱' + Number(v).toLocaleString() },
                'open_tickets': { icon: 'ticket', label: 'Open Tickets', format: v => v },
                'critical_tickets': { icon: 'alert-circle', label: 'Critical Tickets', format: v => v },
                'total_downtime_minutes': { icon: 'clock', label: 'Downtime (min)', format: v => v + ' min' },
                'machines_down': { icon: 'cpu', label: 'Machines Down', format: v => v },
                'production_rate_percent': { icon: 'gauge', label: 'Production Rate', format: v => v + '%' },
                'defect_rate_percent': { icon: 'x-circle', label: 'Defect Rate', format: v => v + '%' },
                'overdue_payments': { icon: 'clock-alert', label: 'Overdue Payments', format: v => '₱' + Number(v).toLocaleString() },
                'compliance_score_percent': { icon: 'shield', label: 'Compliance Score', format: v => v + '%' },
                'open_risks': { icon: 'alert-triangle', label: 'Open Risks', format: v => v },
                'connected_sources': { icon: 'database', label: 'Departments Connected', format: v => v + '/8' },
                'total_records': { icon: 'file-text', label: 'Total Records', format: v => Number(v).toLocaleString() },
                'reports_generated': { icon: 'file-bar-chart', label: 'Reports Generated', format: v => v },
                'ai_generations': { icon: 'brain', label: 'AI Generations', format: v => v },
            };

            for (const [key, value] of Object.entries(data)) {
                if (mappings[key] && typeof value === 'number') {
                    stats.push({
                        icon: mappings[key].icon,
                        label: mappings[key].label,
                        value: mappings[key].format(value),
                        change: '',
                        cls: 'change-up'
                    });
                }
            }

            if (stats.length === 0) {
                stats.push({ icon: 'info', label: 'No data', value: '—', change: '', cls: 'change-up' });
                stats.push({ icon: 'info', label: 'No data', value: '—', change: '', cls: 'change-up' });
                stats.push({ icon: 'info', label: 'No data', value: '—', change: '', cls: 'change-up' });
                stats.push({ icon: 'info', label: 'No data', value: '—', change: '', cls: 'change-up' });
            }

            return stats.slice(0, 4).map(s => `
            <div class="dept-stat-card">
                <div class="dept-stat-icon"><i data-lucide="${s.icon}" class="kpi-icon"></i></div>
                <div class="dept-stat-info">
                    <div class="kpi-label">${s.label}</div>
                    <div class="kpi-value" style="font-size:16px;">${s.value}</div>
                    <div class="kpi-change ${s.cls}" style="font-size:10px;">${s.change}</div>
                </div>
            </div>
        `).join('');
        }

        function buildBottomCards(data) {
            const cards = [];

            if (data.top_products && data.top_products.length > 0) {
                cards.push({
                    title: 'Top Products',
                    headers: ['Product', 'Category', 'Price'],
                    rows: data.top_products.map(p => [p.product_name, p.source, '₱' + Number(p.price).toLocaleString()])
                });
            }

            if (data.revenue_by_category && data.revenue_by_category.length > 0) {
                cards.push({
                    title: 'Revenue by Category',
                    headers: ['Category', 'Total'],
                    rows: data.revenue_by_category.map(r => [r.category, '₱' + Number(r.total).toLocaleString()])
                });
            }

            if (data.orders_by_status) {
                cards.push({
                    title: 'Orders by Status',
                    headers: ['Status', 'Count'],
                    rows: Object.entries(data.orders_by_status).map(([k, v]) => [k, v])
                });
            }

            if (data.tickets_by_priority) {
                cards.push({
                    title: 'Tickets by Priority',
                    headers: ['Priority', 'Count'],
                    rows: Object.entries(data.tickets_by_priority).map(([k, v]) => [k, v])
                });
            }

            if (data.last_sync_times && Object.keys(data.last_sync_times).length > 0) {
                cards.push({
                    title: 'Department Sync Status',
                    headers: ['Department', 'Last Sync'],
                    rows: Object.entries(data.last_sync_times).slice(0, 5).map(([k, v]) => [k.replace('_', ' '), v])
                });
            }

            if (data.expense_summary) {
                let trackedTotal = 0;
                const rows = [];

                for (const [key, value] of Object.entries(data.expense_summary)) {
                    if (key !== 'total_expenses' && Number(value) > 0) {
                        rows.push([key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()), '₱' + Number(value).toLocaleString()]);
                        trackedTotal += Number(value);
                    }
                }

                const total = Number(data.expense_summary.total_expenses) || 0;
                const other = total - trackedTotal;

                if (other > 0) {
                    rows.push(['Other', '₱' + other.toLocaleString()]);
                }

                if (rows.length > 0) {
                    cards.push({
                        title: 'Monthly Expense Summary',
                        headers: ['Category', 'Amount'],
                        rows: rows
                    });
                }
            }

            if (cards.length === 0) {
                return '<p style="color:var(--slate-500);text-align:center;padding:2rem;">No additional data available</p>';
            }

            return cards.map(c => `
            <div class="ui-card">
                <div class="card-header"><div class="card-title">${c.title}</div></div>
                <div class="scrollable-card-body">
                    <table>
                        <thead><tr>${c.headers.map(h => `<th>${h}</th>`).join('')}</tr></thead>
                        <tbody>${c.rows.map(r => `<tr>${r.map(d => `<td>${d}</td>`).join('')}</tr>`).join('')}</tbody>
                    </table>
                </div>
            </div>
        `).join('');
        }

        const deptNames = {
            ecommerce: 'E-Commerce',
            inventory: 'Inventory & Warehouse',
            manufacturing: 'Manufacturing & Productions',
            procurement: 'Procurement',
            finance: 'Finance & Accounting',
            fulfillment: 'Order Fulfillment',
            itsm: 'ITSM, Compliance & Risk Management',
            bi: 'Business Intelligence & Analytics',
            hr: 'Human Resources',
        };

        const deptDescs = {
            ecommerce: 'Catalog value, product availability, and customer ratings across the storefront.',
            inventory: 'Stock levels, valuation, and category breakdown.',
            manufacturing: 'Machine status, production output, and quality metrics.',
            procurement: 'Purchase orders, supplier performance, and spending.',
            finance: 'Revenue, expenses, profit margins, and transaction status.',
            fulfillment: 'Delivery performance, tracking, and shipping status.',
            itsm: 'IT service management, compliance tracking, and risk assessment.',
            bi: 'Data source health, records synced, and system connectivity.',
            hr: 'Human Resources module — coming soon.',
        };

        let deptChart1 = null;
        let deptChart2 = null;

        function destroyCharts() {
            if (deptChart1) { deptChart1.destroy(); deptChart1 = null; }
            if (deptChart2) { deptChart2.destroy(); deptChart2 = null; }
        }

        function renderChart(canvasId, chartData) {
            if (!chartData || !chartData.data || chartData.data.length === 0) return null;

            const ctx = document.getElementById(canvasId);
            if (!ctx) return null;

            const labels = chartData.data.map(d => {
                const raw = d.label || d.date || '';
                if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) {
                    const date = new Date(raw + 'T00:00:00');
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }
                return raw;
            });
            const values = chartData.data.map(d => d.value || d.total || 0);
            const colors = ['#1B6FC8', '#4A9EE8', '#7BBEF0', '#16A34A', '#D97706', '#DC2626', '#0EA5E9', '#EAB308'];

            if (chartData.type === 'doughnut') {
                return new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }] },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
                    }
                });
            }

            if (chartData.type === 'bar') {
                return new Chart(ctx, {
                    type: 'bar',
                    data: { labels: labels, datasets: [{ data: values, backgroundColor: colors[0], borderRadius: 4, maxBarThickness: 40 }] },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: '#E2E8F0' } }, x: { grid: { display: false } } }
                    }
                });
            }

            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{ data: values, borderColor: '#1B6FC8', backgroundColor: 'rgba(27,111,200,0.1)', tension: 0.35, fill: true, pointRadius: 3, pointBackgroundColor: '#1B6FC8' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: '#E2E8F0' } }, x: { grid: { display: false } } }
                }
            });
        }

        async function switchDepartment() {
            const dept = document.getElementById('deptSelector').value;

            document.getElementById('deptTitle').textContent = deptNames[dept] || dept;
            document.getElementById('deptDesc').textContent = deptDescs[dept] || '';
            document.getElementById('deptStats').innerHTML = '<p style="color:var(--slate-500);text-align:center;padding:1rem;">Loading…</p>';
            document.getElementById('deptBottomRow').innerHTML = '';
            destroyCharts();

            try {
                const res = await fetch('/api/department/' + dept);
                const data = await res.json();

                document.getElementById('deptCard1Title').textContent = data.chart1?.label || 'Overview';
                document.getElementById('deptCard2Title').textContent = data.chart2?.label || 'Breakdown';
                document.getElementById('deptStats').innerHTML = buildStats(data);

                deptChart1 = renderChart('deptChart1', data.chart1);
                deptChart2 = renderChart('deptChart2', data.chart2);
                document.getElementById('deptBottomRow').innerHTML = buildBottomCards(data);

                lucide.createIcons();
            } catch (e) {
                document.getElementById('deptStats').innerHTML = '<p style="color:var(--danger);text-align:center;padding:1rem;">Failed to load data</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => switchDepartment());    
    </script>
@endsection
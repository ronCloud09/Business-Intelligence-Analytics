@extends('layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block;">
    <div class="subheader-bar">
        <div class="subheader-title">
            <h3 id="deptTitle">Department Analytics</h3>
            <p id="deptDesc">Deep dive into each department's key performance indicators and trends.</p>
        </div>
        <div class="subheader-controls">
            <select id="deptSelector" class="control-date-selector chart-range-select" onchange="switchDepartment()" style="width:280px;">
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
        {{-- Top Row: 2 big squares + 4 stats --}}
        <div class="dept-top-row">
            <div class="dept-big-card">
                <div class="card-header"><div class="card-title" id="deptCard1Title">Trend Overview</div></div>
                <div class="placeholder-graph-box chart-box"><canvas id="deptChart1"></canvas></div>
            </div>
            <div class="dept-big-card">
                <div class="card-header"><div class="card-title" id="deptCard2Title">Performance Chart</div></div>
                <div class="placeholder-graph-box chart-box"><canvas id="deptChart2"></canvas></div>
            </div>
            <div class="dept-stats-col" id="deptStats"></div>
        </div>

        {{-- Bottom Row: Cards --}}
        <div class="dashboard-layout-grid" id="deptBottomRow"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const deptData = {
    itsm: {
        title: 'ITSM, Compliance & Risk Management',
        desc: 'IT service management, compliance tracking, and risk assessment metrics.',
        stats: [
            { icon: 'ticket', label: 'Open Tickets', value: '47', change: '↑ 12%', cls: 'change-up' },
            { icon: 'shield', label: 'Compliance Score', value: '96%', change: '↑ 2%', cls: 'change-up' },
            { icon: 'alert-triangle', label: 'High Risks', value: '3', change: '↓ 1', cls: 'change-up' },
            { icon: 'check-circle', label: 'Audits Passed', value: '12/12', change: '100%', cls: 'change-up' }
        ],
        leftTitle: 'Ticket Volume Trend',
        rightTitle: 'Risk by Category',
        bottomCards: [
            { title: 'Recent Incidents', type: 'table', rows: [['Server outage', 'Resolved', '2h ago'], ['VPN issue', 'In progress', '5h ago'], ['Email delay', 'Resolved', '1d ago']], headers: ['Incident', 'Status', 'Time'] },
            { title: 'Compliance Checklist', type: 'table', rows: [['SOC 2', 'Passed', 'Mar 2026'], ['ISO 27001', 'Passed', 'Jan 2026'], ['GDPR', 'In review', 'Due Jun 2026']], headers: ['Standard', 'Status', 'Date'] }
        ]
    },
    ecommerce: {
        title: 'E-Commerce',
        desc: 'Online sales performance, customer acquisition, and retention metrics.',
        stats: [
            { icon: 'dollar-sign', label: 'Total Revenue', value: '₱2.4M', change: '↑ 12.25%', cls: 'change-up' },
            { icon: 'shopping-cart', label: 'Orders', value: '3,842', change: '↑ 8.7%', cls: 'change-up' },
            { icon: 'users', label: 'New Customers', value: '1,240', change: '↑ 15.3%', cls: 'change-up' },
            { icon: 'percent', label: 'Conversion Rate', value: '4.8%', change: '↑ 0.5%', cls: 'change-up' }
        ],
        leftTitle: 'Sales Performance Trend',
        rightTitle: 'Revenue by Channel',
        bottomCards: [
            { title: 'Top Selling Products', type: 'table', rows: [['Gaming PC Alpha', '240', '₱480K'], ['RTX 4060 GPU', '185', '₱277K'], ['Monitor 27"', '160', '₱208K'], ['Keyboard Mech', '145', '₱130K'], ['Mouse Pro', '132', '₱79K']], headers: ['Product', 'Units', 'Revenue'] },
            { title: 'Customer Segments', type: 'table', rows: [['Retail', '65%', '↑ 8%'], ['Business', '25%', '↑ 12%'], ['Education', '10%', '↑ 3%']], headers: ['Segment', 'Share', 'Growth'] }
        ]
    },
    inventory: {
        title: 'Inventory & Warehouse',
        desc: 'Stock levels, turnover rates, and warehouse capacity metrics.',
        stats: [
            { icon: 'package', label: 'Total SKUs', value: '8,450', change: '↑ 3%', cls: 'change-up' },
            { icon: 'alert-triangle', label: 'Low Stock Items', value: '12', change: '↓ 4', cls: 'change-up' },
            { icon: 'trending-up', label: 'Turnover Rate', value: '5.2x', change: '↑ 0.8x', cls: 'change-up' },
            { icon: 'warehouse', label: 'Capacity Used', value: '72%', change: '↑ 5%', cls: 'change-down' }
        ],
        leftTitle: 'Inventory Value by Category',
        rightTitle: 'Stock Movement Trend',
        bottomCards: [
            { title: 'Low Stock Alerts', type: 'table', rows: [['RTX 4060', '32', '50', 'Low'], ['SSD 1TB', '18', '25', 'Low'], ['GPU RX 7800', '55', '30', 'OK']], headers: ['Item', 'Stock', 'Min', 'Status'] },
            { title: 'Warehouse Utilization', type: 'table', rows: [['Zone A', '85%', 'GPUs'], ['Zone B', '62%', 'Monitors'], ['Zone C', '71%', 'Accessories']], headers: ['Zone', 'Full', 'Category'] }
        ]
    }
};

// Fill remaining departments with same structure
['manufacturing','bi','procurement','finance','fulfillment','hr'].forEach(d => {
    if (!deptData[d]) deptData[d] = deptData.itsm; // placeholder
});

function buildStats(stats) {
    return stats.map(s => `
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

function buildBottomCards(cards) {
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

function switchDepartment() {
    const dept = document.getElementById('deptSelector').value;
    const data = deptData[dept];
    
    document.getElementById('deptTitle').textContent = data.title;
    document.getElementById('deptDesc').textContent = data.desc;
    document.getElementById('deptCard1Title').textContent = data.leftTitle;
    document.getElementById('deptCard2Title').textContent = data.rightTitle;
    document.getElementById('deptStats').innerHTML = buildStats(data.stats);
    document.getElementById('deptBottomRow').innerHTML = buildBottomCards(data.bottomCards);
    
    lucide.createIcons();
}

document.addEventListener('DOMContentLoaded', () => switchDepartment());
</script>
@endsection
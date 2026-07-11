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
// Real KPI data, computed server-side by the department services
// (app/Services/Departments/*) — the single source of truth shared with
// the AI aggregators. See DepartmentAnalyticsController.
const deptData = @json($departments);

// Business Intelligence, Order Fulfillment, and Human Resources don't
// have a backing module yet, so their tabs fall back to the ITSM shape
// as a visual placeholder until those modules are built.
['bi', 'fulfillment', 'hr'].forEach(d => {
    if (!deptData[d]) deptData[d] = deptData.itsm; // placeholder — no module yet
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
@extends('layouts.app')

@section('content')
<div id="ai-insights-view" class="tab-content active-tab" style="display:block;">
    <div class="subheader-bar">
        <div class="subheader-title">
            <h3>AI Insights Center</h3>
            <p>AI-generated business insights, recommendations, and alerts.</p>
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
        <div class="insight-card alerts-card-compact" id="alertsCard">
            <div class="alerts-header-row">
                <h3>Recent System Alerts</h3>
            </div>
            <div class="alerts-row">
                <div class="alert-mini alert-mini-critical" data-alert-id="1" onclick="showAlertDetail(event, this)">
                    <i data-lucide="alert-triangle" class="alert-mini-icon"></i>
                    <span class="alert-mini-title">GPU Stock Critical</span>
                    <span class="alert-mini-time">5m</span>
                </div>
                <div class="alert-mini alert-mini-warning" data-alert-id="2" onclick="showAlertDetail(event, this)">
                    <i data-lucide="alert-circle" class="alert-mini-icon"></i>
                    <span class="alert-mini-title">Delivery Drop</span>
                    <span class="alert-mini-time">18m</span>
                </div>
                <div class="alert-mini alert-mini-danger" data-alert-id="3" onclick="showAlertDetail(event, this)">
                    <i data-lucide="truck" class="alert-mini-icon"></i>
                    <span class="alert-mini-title">Fleet Delay</span>
                    <span class="alert-mini-time">45m</span>
                </div>
                <div class="alert-mini alert-mini-info" data-alert-id="4" onclick="showAlertDetail(event, this)">
                    <i data-lucide="user-plus" class="alert-mini-icon"></i>
                    <span class="alert-mini-title">New User Added</span>
                    <span class="alert-mini-time">3h</span>
                </div>
                <div class="alert-mini alert-mini-success" data-alert-id="5" onclick="showAlertDetail(event, this)">
                    <i data-lucide="check-circle" class="alert-mini-icon"></i>
                    <span class="alert-mini-title">System Updated</span>
                    <span class="alert-mini-time">2d</span>
                </div>
            </div>
            <div class="alert-detail-panel" id="alertDetailPanel" style="display:none;">
                <div class="alert-detail-header">
                    <div class="alert-detail-header-left">
                        <div>
                            <h4 id="alertDetailTitle"></h4>
                            <span id="alertDetailTime"></span>
                        </div>
                    </div>
                    <button class="alert-detail-close" onclick="closeAlertDetail()">&times;</button>
                </div>
                <div class="alert-detail-body" id="alertDetailBody"></div>
            </div>
        </div>

        <div class="ai-insights-grid">
            <div class="insight-card">
                <h3>Executive Summary <span class="info-dot" data-tooltip="AI-generated overview of the most critical business metrics and performance indicators across all modules. Highlights areas of concern and positive trends that require executive attention.">i</span></h3>
                <div class="card-subtitle">Waiting for system connection...</div>
                <div class="insight-list">
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-green"><i data-lucide="bar-chart-3" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ AI Core Module Pending ]</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Core metrics analysis is waiting for framework integration.</p></div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-orange"><i data-lucide="alert-triangle" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ AI Core Module Pending ]</strong> Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Inventory safety metrics are unlinked.</p></div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-red"><i data-lucide="alert-circle" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ AI Core Module Pending ]</strong> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-blue"><i data-lucide="info" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ AI Core Module Pending ]</strong> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p><div class="sub-text">Framework offline</div></div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-green"><i data-lucide="check-circle" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ AI Core Module Pending ]</strong> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><div class="sub-text">Framework offline</div></div>
                    </div>
                </div>
            </div>

            <div class="insight-card">
                <h3>Top Recommendations <span class="info-dot" data-tooltip="Prioritized actionable recommendations generated by the AI engine. Sorted by potential business impact, these suggestions help optimize operations, reduce costs, and capitalize on emerging market opportunities.">i</span></h3>
                <div class="card-subtitle">&nbsp;</div>
                <div class="insight-list">
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-num">1</div>
                        <div class="insight-text-wrapper"><p><strong>[ Strategy Optimization Target ]</strong></p><div class="sub-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit temporarily.</div></div>
                        <span class="mock-badge mb-high-impact">High Impact</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-num">2</div>
                        <div class="insight-text-wrapper"><p><strong>[ Strategy Optimization Target ]</strong></p><div class="sub-text">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div></div>
                        <span class="mock-badge mb-high-impact">High Impact</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-num">3</div>
                        <div class="insight-text-wrapper"><p><strong>[ Strategy Optimization Target ]</strong></p><div class="sub-text">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</div></div>
                        <span class="mock-badge mb-med-impact">Medium Impact</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-num">4</div>
                        <div class="insight-text-wrapper"><p><strong>[ Strategy Optimization Target ]</strong></p><div class="sub-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</div></div>
                        <span class="mock-badge mb-med-impact">Medium Impact</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-num">5</div>
                        <div class="insight-text-wrapper"><p><strong>[ Strategy Optimization Target ]</strong></p><div class="sub-text">Excepteur sint occaecat cupidatat non proident, sunt in culpa.</div></div>
                        <span class="mock-badge mb-low-impact">Low Impact</span>
                    </div>
                </div>
            </div>

            <div class="insight-card">
                <h3>Risk Detection <span class="info-dot" data-tooltip="Automated risk monitoring across supply chain, operations, and financial domains. Each risk is scored by severity and probability. High-risk items require immediate attention, while lower tiers should be reviewed regularly.">i</span></h3>
                <div class="card-subtitle">&nbsp;</div>
                <div class="insight-list">
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-red"><i data-lucide="alert-triangle" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ System Risk Evaluation ]</strong></p><div class="sub-text">Lorem ipsum dolor sit amet, consectetur modules pending.</div></div>
                        <span class="mock-badge mb-high">High</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-orange"><i data-lucide="alert-circle" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ System Risk Evaluation ]</strong></p><div class="sub-text">Sed do eiusmod tempor incididunt ut labore telemetry analysis.</div></div>
                        <span class="mock-badge mb-medium">Medium</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-orange"><i data-lucide="truck" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ System Risk Evaluation ]</strong></p><div class="sub-text">Ut enim ad minim veniam, quis nostrud connection validation.</div></div>
                        <span class="mock-badge mb-medium">Medium</span>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon-circle bg-icon-green"><i data-lucide="shield" class="insight-icon-sm"></i></div>
                        <div class="insight-text-wrapper"><p><strong>[ System Risk Evaluation ]</strong></p><div class="sub-text">Duis aute irure dolor in reprehenderit structure offline.</div></div>
                        <span class="mock-badge mb-low">Low</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showAlertDetail(e, element) {
        e.stopPropagation();
        document.querySelectorAll('.alert-mini').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        const alertId = element.getAttribute('data-alert-id');
        const panel = document.getElementById('alertDetailPanel');
        const title = document.getElementById('alertDetailTitle');
        const time = document.getElementById('alertDetailTime');
        const body = document.getElementById('alertDetailBody');
        let detailHTML = '';
        switch(alertId) {
            case '1':
                title.textContent = 'GPU Stock Critical';
                time.textContent = '5 min ago • High Priority';
                detailHTML = `<p><strong>RTX 4060 GPU</strong> stock has fallen below the reorder threshold of <strong>50 units</strong>.</p><table><thead><tr><th>Item</th><th>Current Stock</th><th>Threshold</th><th>Supplier</th><th>Lead Time</th></tr></thead><tbody><tr><td>RTX 4060</td><td style="color:#DC2626;font-weight:700;">32 units</td><td>50 units</td><td>TechSource Inc.</td><td>5-7 days</td></tr><tr><td>RTX 4070</td><td>48 units</td><td>40 units</td><td>TechSource Inc.</td><td>5-7 days</td></tr><tr><td>RX 7800</td><td>55 units</td><td>30 units</td><td>AMD Supply Co.</td><td>3-4 days</td></tr></tbody></table><p style="margin-top:0.5rem;color:#DC2626;"><strong>Action:</strong> Expedite PO #4521 or risk stockout in 4 days.</p>`;
                break;
            case '2':
                title.textContent = 'Delivery Rate Dropping';
                time.textContent = '18 min ago • Medium Priority';
                detailHTML = `<p>On-time delivery rate has fallen <strong>3.2%</strong> this week to <strong style="color:#D97706;">91.3%</strong> (target: 95%).</p><div class="detail-row"><span class="detail-label">Affected Region</span><span class="detail-value">Metro Manila</span></div><div class="detail-row"><span class="detail-label">Late Deliveries</span><span class="detail-value">47 orders (up from 22)</span></div><div class="detail-row"><span class="detail-label">Main Cause</span><span class="detail-value">Traffic congestion / rerouting</span></div><div class="detail-row"><span class="detail-label">Affected Partner</span><span class="detail-value">Flash Express Logistics</span></div><p style="margin-top:0.5rem;color:#D97706;"><strong>Action:</strong> Review route optimization for Metro Manila zone.</p>`;
                break;
            case '3':
                title.textContent = 'Fleet Delay Reported';
                time.textContent = '45 min ago • Medium Priority';
                detailHTML = `<p>Logistics partner <strong>Flash Express</strong> reports a <strong style="color:#9A3412;">2-hour delay</strong> on fleet #FE-224.</p><div class="detail-row"><span class="detail-label">Fleet ID</span><span class="detail-value">FE-224</span></div><div class="detail-row"><span class="detail-label">Vehicle Count</span><span class="detail-value">8 trucks</span></div><div class="detail-row"><span class="detail-label">Orders Affected</span><span class="detail-value">34 orders</span></div><div class="detail-row"><span class="detail-label">Assembly Line Impact</span><span class="detail-value">Line B (Gaming PCs) - slowed</span></div><div class="detail-row"><span class="detail-label">Est. Recovery</span><span class="detail-value">4:30 PM today</span></div><p style="margin-top:0.5rem;color:#9A3412;"><strong>Action:</strong> Notify affected customers. Reassign Line B to afternoon shift.</p>`;
                break;
            case '4':
                title.textContent = 'New User Onboarded';
                time.textContent = '3 hrs ago • Info';
                detailHTML = `<p>A new user has been added to the system.</p><div class="detail-row"><span class="detail-label">Name</span><span class="detail-value">Maria Santos</span></div><div class="detail-row"><span class="detail-label">Department</span><span class="detail-value">Finance</span></div><div class="detail-row"><span class="detail-label">Role</span><span class="detail-value">Financial Analyst</span></div><div class="detail-row"><span class="detail-label">Access Level</span><span class="detail-value">View & Export Reports</span></div><div class="detail-row"><span class="detail-label">Added By</span><span class="detail-value">Admin - Carlos Reyes</span></div><p style="margin-top:0.5rem;color:#1E40AF;"><strong>Note:</strong> Standard onboarding workflow initiated.</p>`;
                break;
            case '5':
                title.textContent = 'System Update Complete';
                time.textContent = '2 days ago • Success';
                detailHTML = `<p>NEXORA BI <strong>v1.0.0</strong> has been deployed successfully.</p><div class="detail-row"><span class="detail-label">Version</span><span class="detail-value">v1.0.0 (build 2026.05.07)</span></div><div class="detail-row"><span class="detail-label">Deployed By</span><span class="detail-value">DevOps - Juan Cruz</span></div><div class="detail-row"><span class="detail-label">Changelog</span><span class="detail-value">Dashboard v2, AI Insights, Chat Bot</span></div><div class="detail-row"><span class="detail-label">Downtime</span><span class="detail-value">0 min (zero-downtime deploy)</span></div><div class="detail-row"><span class="detail-label">Invoice #</span><span class="detail-value">INV-2026-0892</span></div><p style="margin-top:0.5rem;color:#166534;"><strong>Status:</strong> All systems operational. Monitoring active.</p>`;
                break;
        }
        body.innerHTML = detailHTML;
        panel.style.display = 'block';
        lucide.createIcons();
    }
    function closeAlertDetail() {
        document.getElementById('alertDetailPanel').style.display = 'none';
        document.querySelectorAll('.alert-mini').forEach(el => el.classList.remove('active'));
    }
    document.addEventListener('click', function(e) {
        const panel = document.getElementById('alertDetailPanel');
        const alertsCard = document.getElementById('alertsCard');
        if (panel && panel.style.display === 'block' && !alertsCard.contains(e.target)) { closeAlertDetail(); }
    });
</script>
@endsection
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

        <div class="insight-card" id="alertsCard">
            <div class="alerts-header-row">
                <h3>Recent System Alerts</h3>
            </div>
            <p style="font-size:11px; color: var(--slate-500); margin-bottom: 0.75rem;">Full details for all active alerts</p>
            
            <div class="alerts-scroll-row">
                <div class="alert-square alert-square-critical">
                    <div class="alert-square-icon"><i data-lucide="alert-triangle"></i></div>
                    <strong>GPU Stock Critical</strong>
                    <span class="alert-square-time">5 min ago • High Priority</span>
                    <p>RTX 4060 down to 32 units (threshold: 50)</p>
                    <table>
                        <tr><td>RTX 4060</td><td style="color:#DC2626;font-weight:700;">32 units</td></tr>
                        <tr><td>RTX 4070</td><td>48 units</td></tr>
                        <tr><td>RX 7800</td><td>55 units</td></tr>
                    </table>
                    <p class="alert-square-action">Action: Expedite PO #4521</p>
                </div>

                <div class="alert-square alert-square-warning">
                    <div class="alert-square-icon"><i data-lucide="alert-circle"></i></div>
                    <strong>Delivery Rate Drop</strong>
                    <span class="alert-square-time">18 min ago • Medium</span>
                    <p>On-time fell 3.2% to 91.3% (target: 95%)</p>
                    <div class="detail-row"><span>Region</span><span>Metro Manila</span></div>
                    <div class="detail-row"><span>Late Orders</span><span>47 (up from 22)</span></div>
                    <div class="detail-row"><span>Partner</span><span>Flash Express</span></div>
                    <p class="alert-square-action">Action: Review route optimization</p>
                </div>

                <div class="alert-square alert-square-danger">
                    <div class="alert-square-icon"><i data-lucide="truck"></i></div>
                    <strong>Fleet Delay</strong>
                    <span class="alert-square-time">45 min ago • Medium</span>
                    <p>Flash Express fleet #FE-224 — 2hr delay</p>
                    <div class="detail-row"><span>Vehicles</span><span>8 trucks</span></div>
                    <div class="detail-row"><span>Orders Affected</span><span>34</span></div>
                    <div class="detail-row"><span>Line Impact</span><span>Line B slowed</span></div>
                    <p class="alert-square-action">Action: Notify customers</p>
                </div>

                <div class="alert-square alert-square-info">
                    <div class="alert-square-icon"><i data-lucide="user-plus"></i></div>
                    <strong>New User Added</strong>
                    <span class="alert-square-time">3 hrs ago • Info</span>
                    <p>Maria Santos onboarded to Finance</p>
                    <div class="detail-row"><span>Role</span><span>Financial Analyst</span></div>
                    <div class="detail-row"><span>Access</span><span>View & Export</span></div>
                    <div class="detail-row"><span>Added By</span><span>Carlos Reyes</span></div>
                    <p class="alert-square-action">Note: Standard workflow initiated</p>
                </div>

                <div class="alert-square alert-square-success">
                    <div class="alert-square-icon"><i data-lucide="check-circle"></i></div>
                    <strong>System Updated</strong>
                    <span class="alert-square-time">2 days ago • Success</span>
                    <p>NEXORA BI v1.0.0 deployed</p>
                    <div class="detail-row"><span>Version</span><span>build 2026.05.07</span></div>
                    <div class="detail-row"><span>By</span><span>Juan Cruz</span></div>
                    <div class="detail-row"><span>Downtime</span><span>0 min</span></div>
                    <p class="alert-square-action">Status: All systems operational</p>
                </div>
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

</script>
@endsection
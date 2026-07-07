<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora - BI Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <link rel="icon" type="image/png" href="{{ asset('images/Nexora_Logo_Transparent.png') }}">
</head>
<body>
    <header class="header">
        <a href="{{ route('signin') }}" class="nexora-logo" id="headerLogoBtn">
            <img src="{{ asset('images/Banner Transparent.png') }}" alt="Nexora Logo">
        </a>
        <div class="header-right">
            <div class="header-profile-wrap" id="headerProfileWrap">
                <button class="header-profile-btn" id="headerProfileBtn">
                    <i data-lucide="user" class="profile-icon"></i>
                    <span class="notification-badge" id="notificationBadge">3</span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-header">
                        <h3>Notifications</h3>
                        <button class="notification-mark-read" onclick="markAllRead()">Mark all as read</button>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread" data-notif="1">
                            <div class="notification-dot"></div>    
                            <div class="notification-icon bg-icon-red">
                                <i data-lucide="alert-triangle" class="notif-icon-sm"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">Inventory Alert</p>
                                <p class="notification-desc">RTX 4060 GPU stock below reorder threshold</p>
                                <span class="notification-time">5 min ago</span>
                            </div>
                        </div>
                        <div class="notification-item unread" data-notif="2">
                            <div class="notification-dot"></div>
                            <div class="notification-icon bg-icon-orange">
                                <i data-lucide="alert-circle" class="notif-icon-sm"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">On-Time Delivery Drop</p>
                                <p class="notification-desc">Delivery rate fell 3.2% this week to 91.3%</p>
                                <span class="notification-time">18 min ago</span>
                            </div>
                        </div>
                        <div class="notification-item unread" data-notif="3">
                            <div class="notification-dot"></div>
                            <div class="notification-icon bg-icon-blue">
                                <i data-lucide="trending-up" class="notif-icon-sm"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">Revenue Milestone</p>
                                <p class="notification-desc">Monthly revenue exceeded ₱2.4M forecast</p>
                                <span class="notification-time">2 hrs ago</span>
                            </div>
                        </div>
                        <div class="notification-item" data-notif="4">
                            <div class="notification-dot"></div>
                            <div class="notification-icon bg-icon-green">
                                <i data-lucide="file-text" class="notif-icon-sm"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">Monthly Report Ready</p>
                                <p class="notification-desc">May 2026 executive summary available</p>
                                <span class="notification-time">1 day ago</span>
                            </div>
                        </div>
                        <div class="notification-item" data-notif="5">
                            <div class="notification-dot"></div>
                            <div class="notification-icon bg-icon-green">
                                <i data-lucide="check-circle" class="notif-icon-sm"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">System Update Complete</p>
                                <p class="notification-desc">NEXORA BI v1.0.0 deployed successfully</p>
                                <span class="notification-time">2 days ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="app-body">
        <aside>
            <div class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <div class="nav-item-title">
                        <i data-lucide="layout-dashboard" class="nav-icon"></i>
                        Dashboard
                    </div>
                    <div class="nav-item-sub">Executive Overview</div>
                </a>
                <a href="{{ route('ai-insights') }}" class="nav-item {{ request()->routeIs('ai-insights') ? 'active' : '' }}" data-tooltip="AI Insights">
                    <div class="nav-item-title">
                        <i data-lucide="brain" class="nav-icon"></i>
                        AI Insights
                    </div>
                    <div class="nav-item-sub">Recommendations</div>
                </a>
                <a href="{{ route('department-analytics') }}" class="nav-item {{ request()->routeIs('department-analytics') ? 'active' : '' }}" data-tooltip="Department Analytics">
                    <div class="nav-item-title">
                        <i data-lucide="building-2" class="nav-icon"></i>
                        Department Analytics
                    </div>
                    <div class="nav-item-sub">KPI Deep Dive</div>
                </a>
            </div>
            <div class="sidebar-footer">
                <i data-lucide="info" class="footer-icon"></i>
                <span>NEXORA BI v1.0.0</span>
            </div>
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Floating AI Chat Bot -->
    <div class="ai-chat-bot" id="aiChatBot">
        <button class="ai-chat-toggle" id="aiChatToggle" title="NEXORA AI Business Analyst">
            <img src="{{ asset('images/Nexora_Logo_Transparent.png') }}" class="chat-toggle-logo" alt="Nexora">
        </button>
        <div class="ai-chat-window" id="aiChatWindow">
            <div class="ai-chat-header">
                <div class="ai-chat-header-left">
                    <img src="{{ asset('images/Nexora_Logo_Transparent.png') }}" class="chat-header-logo" alt="Nexora">
                    <div>
                        <h4>NEXORA AI Business Analyst</h4>
                        <p>Ask me anything about your business</p>
                    </div>
                </div>
                <button class="ai-chat-close" id="aiChatClose"><i data-lucide="x" class="chat-close-icon"></i></button>
            </div>
            <div class="ai-chat-messages" id="aiChatMessages">
                <div class="ai-message ai-message-bot">
                    <div class="ai-message-avatar"><img src="{{ asset('images/Nexora_Logo_Transparent.png') }}" class="msg-avatar-logo" alt="Nexora"></div>
                    <div class="ai-message-content"><p>Hello! I'm your NEXORA AI Business Analyst. Since NEXORA BI gathers data across enterprise modules, I can help you transform data into actionable insights. What would you like to know?</p></div>
                </div>
            </div>
            <div class="ai-chat-input-container">
                <div class="ai-suggestion-chips" id="aiSuggestionChips">
                    <button class="ai-chip" onclick="sendAiMessage('Give me a summary of overall business performance.')">Business summary</button>
                    <button class="ai-chip" onclick="sendAiMessage('Explain insights from this week\'s activity.')">Weekly insights</button>
                    <button class="ai-chip" onclick="sendAiMessage('What are the top risks I should be aware of?')">Risk alerts</button>
                    <button class="ai-chip" onclick="sendAiMessage('Show me revenue trends and forecast.')">Revenue forecast</button>
                </div>
                <div class="ai-chat-input-row">
                    <input type="text" class="ai-chat-input" id="aiChatInput" placeholder="Type your question here..." onkeypress="handleAiChatKeypress(event)">
                    <button class="ai-chat-send" id="aiChatSend" onclick="sendAiMessage()"><i data-lucide="send" class="send-icon"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', () => {
            const chatToggle = document.getElementById('aiChatToggle');
            const chatClose = document.getElementById('aiChatClose');
            const chatBot = document.getElementById('aiChatBot');
            chatToggle.addEventListener('click', () => { chatBot.classList.toggle('ai-chat-open'); });
            chatClose.addEventListener('click', () => { chatBot.classList.remove('ai-chat-open'); });
            const profileBtn = document.getElementById('headerProfileBtn');
            const profileWrap = document.getElementById('headerProfileWrap');
            const dropdown = document.getElementById('notificationDropdown');
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('active');
            });
            document.addEventListener('click', (e) => {
                if (!profileWrap.contains(e.target)) { dropdown.classList.remove('active'); }
            });
            dropdown.addEventListener('click', (e) => {
                const notifItem = e.target.closest('.notification-item');
                if (notifItem && notifItem.classList.contains('unread')) {
                    notifItem.classList.remove('unread');
                    updateBadgeCount();
                }
            });
        });
        function updateBadgeCount() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const badge = document.getElementById('notificationBadge');
            if (unreadCount > 0) { badge.textContent = unreadCount; badge.style.display = 'flex'; }
            else { badge.textContent = ''; badge.style.display = 'none'; }
        }
        function markAllRead() {
            document.querySelectorAll('.notification-item.unread').forEach(item => item.classList.remove('unread'));
            updateBadgeCount();
        }
        function sendAiMessage(presetMessage) {
            const input = document.getElementById('aiChatInput');
            const message = presetMessage || input.value.trim();
            if (!message) return;
            const messagesContainer = document.getElementById('aiChatMessages');
            const userMsg = document.createElement('div');
            userMsg.className = 'ai-message ai-message-user';
            userMsg.innerHTML = `<div class="ai-message-content"><p>${escapeHtml(message)}</p></div>`;
            messagesContainer.appendChild(userMsg);
            if (!presetMessage) input.value = '';
            setTimeout(() => {
                const botMsg = document.createElement('div');
                botMsg.className = 'ai-message ai-message-bot';
                botMsg.innerHTML = `<div class="ai-message-avatar"><img src="{{ asset('images/Nexora_Logo_Transparent.png') }}" class="msg-avatar-logo" alt="Nexora"></div><div class="ai-message-content"><p>${getAiResponse(message)}</p></div>`;
                messagesContainer.appendChild(botMsg);
                lucide.createIcons();
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 800);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        function handleAiChatKeypress(event) { if (event.key === 'Enter') { sendAiMessage(); } }
        function escapeHtml(text) { const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
        function getAiResponse(message) {
            const lowerMsg = message.toLowerCase();
            if (lowerMsg.includes('summary') || lowerMsg.includes('overall')) { return 'Based on the current dashboard data, total revenue stands at ₱2,400,000 with a 12.25% increase. Gross profit is ₱840,432 (up 9.3%). Key concern: On-time delivery has dropped 3.2% to 91.3%. I recommend reviewing the supply chain metrics for potential bottlenecks.'; }
            else if (lowerMsg.includes('insight') || lowerMsg.includes('week')) { return 'This week\'s key insights: 1) Sales show strong upward momentum with 3,842 orders processed. 2) Gaming PC Alpha and RTX 4060 GPU are flagged as high-demand products with elevated risk levels. 3) Operational efficiency is at 86% with machine downtime at 14.2% — above the target threshold of 10%.'; }
            else if (lowerMsg.includes('risk')) { return 'Top risks detected: 1) High-demand products (Gaming PC Alpha, RTX 4060 GPU) may face stockout. 2) On-time delivery rate declining (91.3%, down 3.2%). 3) Machine downtime at 14.2% exceeds operational targets. 4) Vendor late deliveries account for 20% of shipments.'; }
            else if (lowerMsg.includes('revenue') || lowerMsg.includes('forecast') || lowerMsg.includes('trend')) { return 'Revenue forecast indicates continued growth. Current month shows ₱2.4M with a projected 12% increase next month. The sales forecast chart shows upward trajectory with 92% forecast accuracy. High-demand product lines are expected to drive 60% of the growth.'; }
            else { return 'I understand you\'re asking about "' + message + '". While my full AI capabilities are being integrated, I can help with business summaries, weekly insights, risk analysis, and revenue trends. Feel free to ask about any of these topics, or check the Dashboard and AI Insights pages for detailed analytics.'; }
        }
    </script>
    @yield('scripts')
</body>
</html>
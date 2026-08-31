<?php
/**
 * Hospital Call System - Dashboard
 * King Khalid Hospital, Hail
 * v3.1 — No login required. Direct access.
 */
defined('HOSPITAL_CALL_SYSTEM') or define('HOSPITAL_CALL_SYSTEM', true);
$pageTitle = 'Dashboard';
$pageName = 'dashboard';
$bodyClass = 'app-page';
include_once 'includes/header.php';
?>
<!-- v3.1 — No session check; the system is open without credentials. -->

<div class="tc" id="tc" aria-live="polite" aria-atomic="true"></div>

<div class="ann-ov" id="annOv" role="dialog" aria-modal="true" aria-labelledby="annTitle" aria-hidden="true">
    <div class="ann-modal" id="annModal">
        <div class="ann-pulse-rings" aria-hidden="true"><span></span><span></span><span></span></div>
        <div class="ann-icon-wrap" id="annIconWrap"><i class="fas fa-bullhorn" id="annIcon" aria-hidden="true"></i></div>
        <div class="ann-type-badge" id="annType">ANNOUNCEMENT</div>
        <h2 class="ann-title" id="annTitle">Broadcasting</h2>
        <div class="ann-text-en" id="annTextEn"></div>
        <div class="ann-text-ar" id="annTextAr"></div>
        <div class="ann-wave" aria-hidden="true"><div class="wave-bars"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>
        <div class="ann-progress-bar" aria-hidden="true"><div class="ann-progress-fill" id="annProgress"></div></div>
        <button class="ann-close-btn" onclick="closeAnn()" aria-label="Dismiss announcement"><i class="fas fa-times" aria-hidden="true"></i> <span data-i18n="misc.dismiss">Dismiss</span></button>
    </div>
</div>

<div class="loc-ov" id="locOv" role="dialog" aria-modal="true" aria-labelledby="locTitle" aria-hidden="true">
    <div class="loc-modal">
        <div class="loc-header">
            <div>
                <h3 id="locTitle"><i class="fas fa-map-marker-alt me-2"></i><span data-i18n="loc.title">Select Your Location</span></h3>
                <p data-i18n="loc.desc">Choose the department you are calling from</p>
            </div>
            <button class="loc-close" onclick="closeLoc()"><i class="fas fa-times"></i></button>
        </div>
        <div class="loc-grid" id="loList" role="listbox"></div>
    </div>
</div>

<div id="sbOverlay" onclick="closeMobileSB()" aria-hidden="true"></div>

<div id="page-dashboard">

    <aside class="sidebar" id="SB" aria-label="Main navigation sidebar">
        <div class="sb-head">
            <div class="sb-logo-wrap">
                <img src="assets/images/logo-transparent.png" alt="HHC Logo" style="width:44px;height:44px;border-radius:10px;object-fit:contain;box-shadow:0 2px 8px rgba(0,0,0,0.3);background:rgba(255,255,255,0.15);padding:3px;">
            </div>
            <div class="sb-brand">
                <h2>King Khalid Hospital</h2>
                <p>Call System v3.0</p>
            </div>
        </div>

        <nav class="sb-nav" aria-label="Main navigation">
            <div class="sb-section-label" data-i18n="nav.main">Main</div>
            <a href="#" class="sb-nav-item active" id="nav-home" onclick="showTab('home'); return false;" aria-current="page">
                <i class="fas fa-th-large"></i><span data-i18n="nav.dashboard">Dashboard</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-callboard" onclick="showTab('callboard'); return false;">
                <i class="fas fa-broadcast-tower"></i><span data-i18n="nav.callboard">Call Board</span>
                <span class="sb-badge" data-i18n="misc.live">Live</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-scheduled" onclick="showTab('scheduled'); return false;">
                <i class="fas fa-clock"></i><span data-i18n="nav.scheduled">Scheduled</span>
            </a>

            <div class="sb-section-label" data-i18n="nav.emergency_section">Emergency</div>
            <a href="#" class="sb-nav-item" id="nav-emergency" onclick="showTab('emergency'); return false;">
                <i class="fas fa-exclamation-triangle"></i><span data-i18n="nav.emergency">Emergency Codes</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-doctors" onclick="showTab('doctors'); return false;">
                <i class="fas fa-user-md"></i><span data-i18n="nav.doctors">Call Doctor</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-staff" onclick="showTab('staff'); return false;">
                <i class="fas fa-users"></i><span data-i18n="nav.staff">Call Staff</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-custom" onclick="showTab('custom'); return false;">
                <i class="fas fa-bullhorn"></i><span data-i18n="nav.custom">Custom Announcement</span>
            </a>

            <div class="sb-section-label" data-i18n="nav.reports">Reports</div>
            <a href="#" class="sb-nav-item" id="nav-logs" onclick="showTab('logs'); return false;">
                <i class="fas fa-history"></i><span data-i18n="nav.logs">Call Logs</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-analytics" onclick="showTab('analytics'); return false;">
                <i class="fas fa-chart-bar"></i><span data-i18n="nav.analytics">Analytics</span>
            </a>

            <div class="sb-section-label" data-i18n="nav.manage">Manage</div>
            <a href="#" class="sb-nav-item" id="nav-manage-doctors" onclick="showTab('manage-doctors'); return false;">
                <i class="fas fa-stethoscope"></i><span data-i18n="nav.manage_doctors">Doctors</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-manage-depts" onclick="showTab('manage-depts'); return false;">
                <i class="fas fa-hospital"></i><span data-i18n="nav.manage_depts">Departments</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-manage-codes" onclick="showTab('manage-codes'); return false;">
                <i class="fas fa-shield-halved"></i><span data-i18n="nav.manage_codes">Custom Codes</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-dept-schedules" onclick="showTab('dept-schedules'); return false;">
                <i class="fas fa-calendar-alt"></i><span data-i18n="nav.dept_schedules">Dept Schedules</span>
            </a>

            <div class="sb-section-label" data-i18n="nav.system">System</div>
            <a href="#" class="sb-nav-item" id="nav-audio" onclick="showTab('audio'); return false;">
                <i class="fas fa-sliders"></i><span data-i18n="nav.audio">Audio Control</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-themes" onclick="showTab('themes'); return false;">
                <i class="fas fa-palette"></i><span data-i18n="nav.themes">Themes</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-settings" onclick="showTab('settings'); return false;">
                <i class="fas fa-cog"></i><span data-i18n="nav.settings">Settings</span>
            </a>

            <div class="sb-section-label" data-i18n="nav.smart_section">Smart Features</div>
            <a href="#" class="sb-nav-item" id="nav-sos" onclick="showTab('sos'); return false;">
                <i class="fas fa-triangle-exclamation"></i><span data-i18n="nav.sos">Quick SOS</span>
                <span class="sb-badge sb-badge-red">SOS</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-tvboard" onclick="showTab('tvboard'); return false;">
                <i class="fas fa-tv"></i><span data-i18n="nav.tvboard">TV Board</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-handover" onclick="showTab('handover'); return false;">
                <i class="fas fa-clipboard-list"></i><span data-i18n="nav.handover">Shift Handover</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-quiethours" onclick="showTab('quiethours'); return false;">
                <i class="fas fa-moon"></i><span data-i18n="nav.quiethours">Quiet Hours</span>
            </a>
            <a href="#" class="sb-nav-item" id="nav-visit-hours" onclick="showTab('visit-hours'); return false;">
                <i class="fas fa-door-open"></i><span data-i18n="nav.visit_hours">Visiting Hours</span>
                <span class="sb-badge sb-badge-green" data-i18n="misc.new">NEW</span>
            </a>
        </nav>

        <div class="sb-footer">
            <div class="sb-user-info">
                <div class="sb-avatar" id="sbAv">A</div>
                <div class="sb-user-text">
                    <div class="sb-uname" id="sbName">Administrator</div>
                    <div class="sb-urole" id="sbRole">ADMIN</div>
                </div>
                <button class="sb-collapse-btn" onclick="toggleSB()" title="Collapse sidebar">
                    <i class="fas fa-chevron-left" id="sbTI"></i>
                </button>
            </div>
            <a href="#" class="sb-nav-item sb-logout" onclick="doLogout(); return false;">
                <i class="fas fa-sign-out-alt"></i><span data-i18n="nav.signout">Sign Out</span>
            </a>
        </div>
    </aside>

    <div class="main-wrap" id="MW">

        <header class="topbar" role="banner">
            <button class="topbar-menu-btn" onclick="toggleSB()" aria-label="Toggle sidebar menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-location" onclick="openLoc()" role="button" tabindex="0">
                <i class="fas fa-map-marker-alt"></i>
                <span id="locLabel">Emergency Room</span>
                <i class="fas fa-chevron-down top-chevron"></i>
            </div>
            <div class="topbar-search-wrap" id="topbarSearchWrap">
                <i class="fas fa-search topbar-search-icon"></i>
                <input type="text" class="topbar-search-input" id="globalSearch" placeholder="Search doctors, staff, departments..." autocomplete="off" onkeyup="handleGlobalSearch(this.value)">
                <div class="topbar-search-results" id="globalSearchResults"></div>
            </div>
            <div class="topbar-spacer"></div>
            <div class="topbar-clock" id="clk" role="timer">00:00:00</div>
            <button class="topbar-btn topbar-timer-btn" id="shiftTimerBtn" title="Active Shift Timers" onclick="showTab('handover')" style="display:none;">
                <i class="fas fa-hourglass-half"></i>
                <span class="timer-count-badge" id="timerCountBadge">0</span>
            </button>
            <button class="topbar-btn" title="Notifications"><i class="fas fa-bell"></i><span class="notif-dot"></span></button>
            <button class="topbar-btn lang-toggle-btn" id="langToggleBtn" onclick="toggleLang()" title="Switch Language / تغيير اللغة"><i class="fas fa-globe"></i><span id="langBtnText">العربية</span></button>
            <button class="topbar-btn" onclick="showTab('settings')" title="Settings"><i class="fas fa-cog"></i></button>
            <div class="topbar-avatar" id="tbAv" onclick="showTab('settings')" title="Account" role="button" tabindex="0">A</div>
        </header>

        

        <main id="main-content" class="tab-content-wrapper" role="main">
            <?php
            include 'pages/home.php';
            include 'pages/callboard.php';
            include 'pages/emergency.php';
            include 'pages/doctors.php';
            include 'pages/staff.php';
            include 'pages/custom.php';
            include 'pages/logs.php';
            include 'pages/analytics.php';
            include 'pages/settings.php';
            include 'pages/scheduled.php';
            include 'pages/audio.php';
            include 'pages/manage-doctors.php';
            include 'pages/manage-depts.php';
            include 'pages/dept-employees.php';
            include 'pages/manage-codes.php';
            include 'pages/dept-schedules.php';
            include 'pages/sos.php';
            include 'pages/tvboard.php';
            include 'pages/handover.php';
            include 'pages/quiethours.php';
            include 'pages/themes.php';
            include 'pages/visit-hours.php';
            ?>
        </main>
    </div>
</div>

<?php include 'pages/modals.php'; ?>

<!-- ===== CHATBOT WIDGET ===== -->
<button class="chatbot-fab" id="chatBotBtn" title="Assistant — خالد" aria-label="Open support assistant">
    <img src="assets/images/logo-transparent.png" alt="Khalid Bot" class="chatbot-fab-logo">
    <i class="fas fa-comment-dots chatbot-fab-icon"></i>
    <i class="fas fa-times chatbot-fab-close"></i>
    <span class="chatbot-badge" id="chatBadge">1</span>
</button>

<div class="chatbot-panel" id="chatBotPanel" role="dialog" aria-label="Support Assistant">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar chatbot-avatar-logo">
                <img src="assets/images/logo-transparent.png" alt="Khalid">
                <span class="chatbot-online-dot"></span>
            </div>
            <div>
                <div style="font-weight:800;font-size:0.95rem;">Khalid — خالد</div>
                <div style="font-size:0.72rem;opacity:0.85;">KKH Support Assistant • Online</div>
            </div>
        </div>
        <button id="chatCloseBtn" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.9rem;" aria-label="Close chat">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="chatbot-messages" id="chatMessages"></div>
    <div class="chatbot-input-area">
        <input type="text" class="chatbot-input" id="chatInput" placeholder="Type a question... / اكتب سؤالاً..." autocomplete="off">
        <button class="chatbot-send-btn" id="chatSendBtn" aria-label="Send message">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

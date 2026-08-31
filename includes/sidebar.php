<?php
/**
 * Hospital Call System - Sidebar Template
 * Fully Responsive with Mobile Bottom Navigation
 * King Khalid Hospital, Hail
 */

// Prevent direct access
if (!defined('HOSPITAL_CALL_SYSTEM')) {
    exit('Direct access not permitted');
}

// Active page/tab for highlighting
$activeTab = $activeTab ?? 'home';

// Navigation items structure
$navItems = [
    'main' => [
        'title' => 'Main',
        'items' => [
            ['tab' => 'home', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'badge' => null],
            ['tab' => 'callboard', 'icon' => 'fa-broadcast-tower', 'label' => 'Call Board', 'badge' => 'LIVE'],
            ['tab' => 'scheduled', 'icon' => 'fa-clock', 'label' => 'Scheduled', 'badge' => null],
        ]
    ],
    'emergency' => [
        'title' => 'Emergency',
        'items' => [
            ['tab' => 'emergency', 'icon' => 'fa-exclamation-triangle', 'label' => 'Emergency Codes', 'badge' => null],
            ['tab' => 'doctors', 'icon' => 'fa-user-md', 'label' => 'Call Doctor', 'badge' => null],
            ['tab' => 'staff', 'icon' => 'fa-users', 'label' => 'Call Staff', 'badge' => null],
            ['tab' => 'custom', 'icon' => 'fa-bullhorn', 'label' => 'Custom Announcement', 'badge' => null],
        ]
    ],
    'reports' => [
        'title' => 'Reports',
        'items' => [
            ['tab' => 'logs', 'icon' => 'fa-history', 'label' => 'Call Logs', 'badge' => null],
            ['tab' => 'analytics', 'icon' => 'fa-chart-bar', 'label' => 'Analytics', 'badge' => null],
        ]
    ],
    'manage' => [
        'title' => 'Manage',
        'items' => [
            ['tab' => 'manage-doctors', 'icon' => 'fa-stethoscope', 'label' => 'Doctors', 'badge' => null],
            ['tab' => 'manage-depts', 'icon' => 'fa-hospital', 'label' => 'Departments', 'badge' => null],
            ['tab' => 'manage-codes', 'icon' => 'fa-shield-halved', 'label' => 'Custom Codes', 'badge' => null],
        ]
    ],
    'system' => [
        'title' => 'System',
        'items' => [
            ['tab' => 'audio', 'icon' => 'fa-sliders', 'label' => 'Audio Control', 'badge' => null],
            ['tab' => 'settings', 'icon' => 'fa-cog', 'label' => 'Settings', 'badge' => null],
        ]
    ]
];

// Bottom navigation items (mobile only - simplified)
$bottomNavItems = [
    ['tab' => 'home', 'icon' => 'fa-th-large', 'label' => 'Home'],
    ['tab' => 'callboard', 'icon' => 'fa-broadcast-tower', 'label' => 'Call'],
    ['tab' => 'emergency', 'icon' => 'fa-exclamation-triangle', 'label' => 'Code'],
    ['tab' => 'doctors', 'icon' => 'fa-user-md', 'label' => 'Doctor'],
    ['tab' => 'settings', 'icon' => 'fa-cog', 'label' => 'Settings'],
];
?>

<!-- Toast Notifications Container -->
<div class="tc" id="tc" aria-live="polite" aria-atomic="true"></div>

<!-- Announcement Overlay -->
<div class="ann-ov" id="annOv" role="dialog" aria-modal="true" aria-labelledby="annTitle">
    <div class="ann-modal" id="annModal">
        <div class="ann-pulse-rings" aria-hidden="true">
            <span></span><span></span><span></span>
        </div>
        <div class="ann-icon-wrap" id="annIconWrap">
            <i class="fas fa-bullhorn" id="annIcon" aria-hidden="true"></i>
        </div>
        <div class="ann-type-badge" id="annType">ANNOUNCEMENT</div>
        <h2 class="ann-title" id="annTitle">Broadcasting</h2>
        <div class="ann-text-en" id="annTextEn"></div>
        <div class="ann-text-ar" id="annTextAr"></div>
        <div class="ann-wave" aria-hidden="true">
            <div class="wave-bars">
                <span></span><span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>
        <div class="ann-progress-bar" aria-hidden="true">
            <div class="ann-progress-fill" id="annProgress"></div>
        </div>
        <button class="ann-close-btn" onclick="closeAnn()" aria-label="Dismiss announcement">
            <i class="fas fa-times" aria-hidden="true"></i> <span data-i18n="ann.dismiss">Dismiss</span>
        </button>
    </div>
</div>

<!-- Location Picker Overlay -->
<div class="loc-ov" id="locOv" role="dialog" aria-modal="true" aria-labelledby="locTitle">
    <div class="loc-modal">
        <div class="loc-header">
            <div>
                <h3 id="locTitle"><i class="fas fa-map-marker-alt me-2" style="color:var(--primary)" aria-hidden="true"></i>Select Your Location</h3>
                <p>Choose the department you are calling from</p>
            </div>
            <button class="loc-close" onclick="closeLoc()" aria-label="Close location picker">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="loc-grid" id="loList" role="listbox" aria-label="Departments"></div>
    </div>
</div>

<!-- Sidebar (Desktop/Tablet) -->
<aside class="sidebar" id="SB" aria-label="Main navigation sidebar">
    <div class="sb-head">
        <div class="sb-logo-wrap">
            <img src="https://nuclearmed.org/wp-content/uploads/2021/07/HHC-300.png" 
                 alt="King Khalid Hospital Logo"
                 onerror="this.outerHTML='<span class=sb-logo-fallback aria-hidden=true>⚕</span>'">
        </div>
        <div class="sb-brand">
            <h2>King Khalid Hospital</h2>
            <p>Call System v3.0</p>
        </div>
    </div>

    <nav class="sb-nav" aria-label="Main navigation">
        <?php foreach ($navItems as $section => $sectionData): ?>
            <div class="sb-section-label"><?= htmlspecialchars($sectionData['title']) ?></div>
            <?php foreach ($sectionData['items'] as $item): ?>
                <a href="#" 
                   class="sb-nav-item <?= $activeTab === $item['tab'] ? 'active' : '' ?>"
                   onclick="showTab('<?= $item['tab'] ?>'); return false;"
                   id="nav-<?= $item['tab'] ?>"
                   aria-current="<?= $activeTab === $item['tab'] ? 'page' : 'false' ?>">
                    <i class="fas <?= $item['icon'] ?>" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                    <?php if ($item['badge']): ?>
                        <span class="sb-badge"><?= htmlspecialchars($item['badge']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>

    <div class="sb-footer">
        <div class="sb-user-info">
            <div class="sb-avatar" id="sbAv" aria-hidden="true">A</div>
            <div class="sb-user-text">
                <div class="sb-uname" id="sbName">Administrator</div>
                <div class="sb-urole" id="sbRole">ADMIN</div>
            </div>
            <button class="sb-collapse-btn" onclick="toggleSB()" title="Collapse sidebar" aria-label="Toggle sidebar">
                <i class="fas fa-chevron-left" id="sbTI" aria-hidden="true"></i>
            </button>
        </div>
        <a href="#" class="sb-nav-item sb-logout" onclick="doLogout(); return false;" aria-label="Sign out">
            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
            <span>Sign Out</span>
        </a>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="main-wrap" id="MW">
    <!-- Topbar -->
    <header class="topbar" role="banner">
        <button class="topbar-menu-btn" onclick="toggleSB()" aria-label="Toggle sidebar menu" aria-expanded="false" aria-controls="SB">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>
        
        <div class="topbar-location" onclick="openLoc()" role="button" tabindex="0" aria-label="Change department" title="Change department">
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
            <span id="locLabel">Emergency Room</span>
            <i class="fas fa-chevron-down top-chevron" aria-hidden="true"></i>
        </div>
        
        <div class="topbar-spacer"></div>
        
        <div class="topbar-clock" id="clk" aria-label="Current time" role="timer">00:00:00</div>
        
        <button class="topbar-btn" title="Notifications" aria-label="Notifications" aria-haspopup="true">
            <i class="fas fa-bell" aria-hidden="true"></i>
            <span class="notif-dot" aria-hidden="true"></span>
        </button>
        
        <button class="topbar-btn" onclick="showTab('settings')" title="Settings" aria-label="Settings">
            <i class="fas fa-cog" aria-hidden="true"></i>
        </button>
        
        <div class="topbar-avatar" id="tbAv" onclick="showTab('settings')" title="Account" role="button" tabindex="0" aria-label="Account settings">A</div>
    </header>

    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav" aria-label="Mobile bottom navigation">
        <?php foreach ($bottomNavItems as $item): ?>
            <a href="#" 
               class="bottom-nav-item <?= $activeTab === $item['tab'] ? 'active' : '' ?>"
               onclick="showTab('<?= $item['tab'] ?>'); return false;"
               aria-label="<?= htmlspecialchars($item['label']) ?>"
               aria-current="<?= $activeTab === $item['tab'] ? 'page' : 'false' ?>">
                <i class="fas <?= $item['icon'] ?>" aria-hidden="true"></i>
                <span><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Main Content Area -->
    <main id="main-content" class="tab-content-wrapper" role="main">
<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-themes">
    <div class="page-head">
        <h1><i class="fas fa-palette me-2"></i><span data-i18n="theme.title">Theme & Colors</span></h1>
        <p data-i18n="theme.sub">Customize the system appearance with creative color themes</p>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h2><i class="fas fa-swatchbook me-2"></i><span data-i18n="theme.choose">Choose Your Theme</span></h2>
            <div class="ms-auto">
                <button class="btn-outline-sm" onclick="applyTheme('default')"><i class="fas fa-undo me-1"></i> <span data-i18n="theme.reset">Reset Default</span></button>
            </div>
        </div>
        <div class="card-body">
            <div class="theme-grid" id="themeGrid"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle me-2"></i><span data-i18n="theme.about">About Themes</span></h2>
        </div>
        <div class="card-body">
            <div class="alert-info-box">
                <p data-i18n="theme.info_text">Themes change the entire system appearance including the sidebar, topbar, buttons, cards, and all pages. Your selection is saved automatically and persists across sessions.</p>
            </div>
        </div>
    </div>
</div>

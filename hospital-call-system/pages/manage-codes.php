<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-manage-codes">
    <div class="page-head">
        <h1><i class="fas fa-shield-halved me-2"></i><span data-i18n="codes.title">Custom Emergency Codes</span></h1>
        <p data-i18n="codes.sub">Create codes with your own color, icon, and announcement</p>
    </div>
    <div class="toolbar-row">
        <button class="btn-add" onclick="openModal('ccAddModal')">
            <i class="fas fa-plus"></i>
            <span data-i18n="codes.add_btn">Add Custom Code</span>
        </button>
        <button onclick="loadCodes().then(function(){renderCustomCodeCards();})" class="btn-refresh">
            <i class="fas fa-sync me-1"></i>
            <span data-i18n="misc.refresh">Refresh</span>
        </button>
    </div>
    <div class="ccode-grid" id="customCodeCards">
        <div class="empty-state" style="grid-column:1/-1">
            <i class="fas fa-plus-circle"></i>
            <p data-i18n="dept.no_custom_codes">No custom codes yet</p>
        </div>
    </div>
</div>

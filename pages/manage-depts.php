<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-manage-depts">
    <div class="page-head">
        <h1><i class="fas fa-hospital me-2"></i><span data-i18n="dept.title">Manage Departments</span></h1>
        <p data-i18n="dept.sub">Add hospital departments and locations</p>
    </div>
    <div class="toolbar-row">
        <button class="btn-add" onclick="openModal('deptAddModal')">
            <i class="fas fa-plus"></i>
            <span data-i18n="dept.add_btn">Add Department</span>
        </button>
        <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="deptSearch" placeholder="Search departments…" onkeyup="renderDeptCards()" data-i18n-placeholder="dept.search_ph">
        </div>
        <button onclick="loadLocations()" class="btn-refresh">
            <i class="fas fa-sync me-1"></i>
            <span data-i18n="misc.refresh">Refresh</span>
        </button>
    </div>
    <div class="dept-grid" id="deptCards"></div>
</div>

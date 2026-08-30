<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-manage-doctors">
    <div class="page-head">
        <h1><i class="fas fa-stethoscope me-2"></i><span data-i18n="mdr.title">Manage Staff</span></h1>
        <p data-i18n="mdr.sub">Add, edit, and manage all medical and non-medical staff</p>
    </div>
    <div class="toolbar-row">
        <button class="btn-add" onclick="openModal('drAddModal')">
            <i class="fas fa-user-plus"></i>
            <span data-i18n="mdr.add_btn">Add Staff</span>
        </button>
        <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="staffSearch" placeholder="Search by name, specialty…" onkeyup="renderDoctorCards()" data-i18n-placeholder="mdr.search_placeholder">
        </div>
        <select class="panel-select" id="staffTypeFilter" onchange="renderDoctorCards()">
            <option value="" data-i18n="mdr.filter_all">All Types</option>
            <option value="doctor" data-i18n="staff.doctor">Doctors</option>
            <option value="nurse" data-i18n="staff.nurse">Nurses</option>
            <option value="technician" data-i18n="staff.technician">Technicians</option>
            <option value="paramedic" data-i18n="staff.paramedic">Paramedics</option>
            <option value="admin" data-i18n="staff.admin">Admin</option>
        </select>
        <button onclick="loadDoctors()" class="btn-refresh">
            <i class="fas fa-sync me-1"></i>
            <span data-i18n="misc.refresh">Refresh</span>
        </button>
    </div>
    <div class="doctor-grid" id="doctorCards">
        <div class="empty-state" style="grid-column:1/-1">
            <i class="fas fa-users"></i>
            <p data-i18n="staff.no_found">No staff added yet</p>
        </div>
    </div>
</div>

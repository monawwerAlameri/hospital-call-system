<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-dept-employees">
    <div class="page-head">
        <div class="dept-emp-page-header">
            <div class="dept-emp-back-wrap">
                <button class="btn-back-dept" onclick="showTab('manage-depts')">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="dept-emp-title-block">
                    <h1 id="deptEmpPageTitle"><i class="fas fa-users me-2"></i><span data-i18n="demp.title">Department Employees</span></h1>
                    <p id="deptEmpPageSub" data-i18n="demp.sub">View and manage all staff members in this department</p>
                </div>
            </div>
            <div class="dept-emp-header-info" id="deptEmpHeaderInfo" style="display:none;">
                <div class="dept-emp-stat-chip" id="deptEmpStatCount">
                    <i class="fas fa-users"></i>
                    <span id="deptEmpCountNum">0</span>
                    <span data-i18n="demp.staff">Staff</span>
                </div>
                <div class="dept-emp-stat-chip teal" id="deptEmpStatDept">
                    <i class="fas fa-hospital"></i>
                    <span id="deptEmpDeptCode">—</span>
                </div>
            </div>
        </div>
    </div>

    <div class="toolbar-row" id="deptEmpToolbar">
        <button class="btn-add btn-add-emp" onclick="toggleDeptEmpAddForm()">
            <i class="fas fa-user-plus"></i>
            <span data-i18n="demp.add_btn">Add Employee</span>
        </button>
        <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="deptEmpSearch" placeholder="Search employees…" onkeyup="filterDeptEmployees()" data-i18n-placeholder="demp.search_ph">
        </div>
        <select class="panel-select" id="deptEmpGenderFilter" onchange="filterDeptEmployees()" style="max-width:150px;">
            <option value="" data-i18n="demp.all_genders">All</option>
            <option value="male" data-i18n="demp.male">Male</option>
            <option value="female" data-i18n="demp.female">Female</option>
        </select>
        <button class="btn-refresh" onclick="loadCurrentDeptEmployees()">
            <i class="fas fa-sync me-1"></i>
            <span data-i18n="demp.refresh">Refresh</span>
        </button>
    </div>

    <div class="dept-emp-add-form-wrap" id="deptEmpAddFormWrap" style="display:none;">
        <div class="dept-emp-add-card">
            <div class="dept-emp-add-header">
                <div class="dept-emp-add-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h3 data-i18n="demp.new_employee">New Employee</h3>
                    <p data-i18n="demp.fill_details">Fill in the employee details below</p>
                </div>
                <button class="icon-btn red sml" onclick="toggleDeptEmpAddForm()" style="margin-left:auto;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="panel-label"><i class="fas fa-user me-1"></i> <span data-i18n="demp.name_en">Name (EN) *</span></label>
                    <input class="panel-input" id="dempName" type="text" placeholder="Ahmed Al-Zahrani">
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="panel-label"><i class="fas fa-user me-1"></i> <span data-i18n="demp.name_ar">Name (AR)</span></label>
                    <input class="panel-input" id="dempNameAr" type="text" placeholder="أحمد الزهراني" dir="rtl">
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="panel-label"><i class="fas fa-id-badge me-1"></i> <span data-i18n="demp.emp_id">Employee ID</span></label>
                    <input class="panel-input" id="dempEmpId" type="text" placeholder="EMP-001">
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="panel-label"><i class="fas fa-briefcase me-1"></i> <span data-i18n="demp.role">Role / Position</span></label>
                    <input class="panel-input" id="dempRole" type="text" placeholder="Head Nurse">
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="panel-label"><i class="fas fa-phone me-1"></i> <span data-i18n="demp.phone">Phone</span></label>
                    <input class="panel-input" id="dempPhone" type="tel" placeholder="0551234567">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="panel-label"><i class="fas fa-hashtag me-1"></i> <span data-i18n="demp.ext">Extension</span></label>
                    <input class="panel-input" id="dempExt" type="text" placeholder="2101">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="panel-label"><i class="fas fa-venus-mars me-1"></i> <span data-i18n="demp.gender">Gender</span></label>
                    <select class="panel-select" id="dempGender">
                        <option value="male" data-i18n="demp.male">Male</option>
                        <option value="female" data-i18n="demp.female">Female</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 flex-wrap align-items-end">
                    <button class="btn-call blue" onclick="saveDeptEmpNew()">
                        <i class="fas fa-save"></i>
                        <span data-i18n="demp.save">Save Employee</span>
                    </button>
                    <button class="btn-cancel" onclick="toggleDeptEmpAddForm()">
                        <span data-i18n="misc.cancel">Cancel</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="dept-emp-grid" id="deptEmpPageGrid">
        <div class="empty-state" style="grid-column:1/-1">
            <i class="fas fa-users"></i>
            <p data-i18n="demp.select_dept">Select a department to view employees</p>
        </div>
    </div>

    <input type="hidden" id="currentDeptEmpId">
</div>

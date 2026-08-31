<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>

<div class="modal-ov" id="drAddModal" role="dialog" aria-modal="true" aria-labelledby="drAddModalTitle" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="drAddModalTitle"><i class="fas fa-user-plus me-2"></i><span data-i18n="modal.add_staff">Add Staff Member</span></h3>
            <button class="modal-close" onclick="closeModal('drAddModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="panel-label" for="drAddName">Name (EN) *</label><input class="panel-input" type="text" id="drAddName" placeholder="Ahmed Al-Ghamdi"></div>
                <div class="col-md-6"><label class="panel-label" for="drAddNameAr">Name (AR)</label><input class="panel-input" type="text" id="drAddNameAr" placeholder="أحمد الغامدي" dir="rtl"></div>
                <div class="col-md-6"><label class="panel-label" for="drAddType">Staff Type</label><select class="panel-select" id="drAddType"><option value="doctor">Doctor</option><option value="nurse">Nurse</option><option value="technician">Technician</option><option value="paramedic">Paramedic</option><option value="admin">Administrator</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="drAddGender">Gender</label><select class="panel-select" id="drAddGender"><option value="male">Male</option><option value="female">Female</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="drAddSpec">Specialty / Role</label><select class="panel-select" id="drAddSpec"></select></div>
                <div class="col-md-6"><label class="panel-label" for="drAddLevel">Level / Grade</label><select class="panel-select" id="drAddLevel"><option>Consultant</option><option>Specialist</option><option>Specialist</option><option>Resident</option><option>Intern</option><option>Staff Nurse</option><option>Head Nurse</option><option>Technician</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="drAddDept">Department</label><select class="panel-select" id="drAddDept"></select></div>
                <div class="col-md-6"><label class="panel-label" for="drAddExt">Extension</label><input class="panel-input" type="text" id="drAddExt" placeholder="2675"></div>
                <div class="col-12"><label class="panel-label" for="drAddPhone">Phone Mobile</label><input class="panel-input" type="tel" id="drAddPhone" placeholder="05xxxxxxxx"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call blue" onclick="addDoctor()"><i class="fas fa-save"></i> Save Staff</button>
                <button onclick="closeModal('drAddModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="editStaffModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen me-2"></i><span data-i18n="modal.edit_staff">Edit Staff Details</span></h3>
            <button class="modal-close" onclick="closeModal('editStaffModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="esId">
            <div class="row g-3">
                <div class="col-md-6"><label class="panel-label" for="esName">Name (EN) *</label><input class="panel-input" type="text" id="esName"></div>
                <div class="col-md-6"><label class="panel-label" for="esNameAr">Name (AR)</label><input class="panel-input" type="text" id="esNameAr" dir="rtl"></div>
                <div class="col-md-6"><label class="panel-label" for="esType">Staff Type</label><select class="panel-select" id="esType"><option value="doctor">Doctor</option><option value="nurse">Nurse</option><option value="technician">Technician</option><option value="paramedic">Paramedic</option><option value="admin">Administrator</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="esGender">Gender</label><select class="panel-select" id="esGender"><option value="male">Male</option><option value="female">Female</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="esSpec">Specialty / Role</label><select class="panel-select" id="esSpec"></select></div>
                <div class="col-md-6"><label class="panel-label" for="esLevel">Level / Grade</label><select class="panel-select" id="esLevel"><option>Consultant</option><option>Specialist</option><option>Specialist</option><option>Resident</option><option>Intern</option><option>Staff Nurse</option><option>Head Nurse</option><option>Technician</option></select></div>
                <div class="col-md-6"><label class="panel-label" for="esDept">Department</label><select class="panel-select" id="esDept"></select></div>
                <div class="col-md-6"><label class="panel-label" for="esExt">Extension</label><input class="panel-input" type="text" id="esExt"></div>
                <div class="col-12"><label class="panel-label" for="esPhone">Phone Mobile</label><input class="panel-input" type="tel" id="esPhone" placeholder="05xxxxxxxx"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call purple" onclick="saveEditStaff()"><i class="fas fa-save"></i> Save Changes</button>
                <button onclick="closeModal('editStaffModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="viewStaffModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box" style="max-width:400px">
        <div class="modal-header">
            <h3 data-i18n="view.staff_details">Staff Details</h3>
            <button class="modal-close" onclick="closeModal('viewStaffModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="vsBody" style="display:flex;flex-direction:column;gap:12px"></div>
        <div class="modal-footer"><button onclick="closeModal('viewStaffModal')" class="btn-cancel">Close</button></div>
    </div>
</div>

<div class="modal-ov" id="editDeptModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen me-2"></i><span data-i18n="modal.edit_dept">Edit Department</span></h3>
            <button class="modal-close" onclick="closeModal('editDeptModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edId">
            <div class="row g-3">
                <div class="col-md-6"><label class="panel-label" for="edName">Name (EN) *</label><input class="panel-input" type="text" id="edName"></div>
                <div class="col-md-6"><label class="panel-label" for="edNameAr">Name (AR)</label><input class="panel-input" type="text" id="edNameAr" dir="rtl"></div>
                <div class="col-md-4"><label class="panel-label" for="edCat">Category</label><select class="panel-select" id="edCat"><option value="medical">Medical</option><option value="admin">Admin</option><option value="technical">Technical</option><option value="general">General</option></select></div>
                <div class="col-md-4"><label class="panel-label" for="edFloor">Floor</label><input class="panel-input" type="text" id="edFloor" placeholder="Ground Floor"></div>
                <div class="col-md-4"><label class="panel-label" for="edExt">Extension</label><input class="panel-input" type="text" id="edExt" placeholder="2300"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call purple" onclick="saveEditDept()"><i class="fas fa-save"></i> Save Changes</button>
                <button onclick="closeModal('editDeptModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="deptAddModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-hospital me-2"></i><span data-i18n="modal.add_dept">Add Department</span></h3>
            <button class="modal-close" onclick="closeModal('deptAddModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="panel-label" for="deptAddName">Name (EN) *</label><input class="panel-input" type="text" id="deptAddName" placeholder="Burn Unit"></div>
                <div class="col-md-6"><label class="panel-label" for="deptAddNameAr">Name (AR)</label><input class="panel-input" type="text" id="deptAddNameAr" placeholder="وحدة الحروق" dir="rtl"></div>
                <div class="col-md-4"><label class="panel-label" for="deptAddCode">Code *</label><input class="panel-input" type="text" id="deptAddCode" placeholder="BURN" style="text-transform:uppercase"></div>
                <div class="col-md-4"><label class="panel-label" for="deptAddCat">Category</label><select class="panel-select" id="deptAddCat"><option value="medical">Medical</option><option value="admin">Admin</option><option value="technical">Technical</option><option value="general">General</option></select></div>
                <div class="col-md-4"><label class="panel-label" for="deptAddFloor">Floor</label><input class="panel-input" type="text" id="deptAddFloor" placeholder="Ground Floor"></div>
                <div class="col-12"><label class="panel-label" for="deptAddExt">Extension</label><input class="panel-input" type="text" id="deptAddExt" placeholder="2300"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call blue" onclick="addDept()"><i class="fas fa-save"></i> Save</button>
                <button onclick="closeModal('deptAddModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="ccAddModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-shield-halved me-2"></i><span data-i18n="modal.add_code">Add Custom Emergency Code</span></h3>
            <button class="modal-close" onclick="closeModal('ccAddModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="panel-label" for="ccName">Code Name *</label><input class="panel-input" type="text" id="ccName" placeholder="Code Orange"></div>
                <div class="col-md-6"><label class="panel-label" for="ccDesc">Description</label><input class="panel-input" type="text" id="ccDesc" placeholder="Mass Casualty"></div>
                <div class="col-md-4"><label class="panel-label" for="ccColor">Background Color</label><div class="color-row"><input type="color" id="ccColor" value="#e05c00"><input class="panel-input" id="ccColorHex" value="#e05c00" style="flex:1" oninput="document.getElementById('ccColor').value=this.value"></div></div>
                <div class="col-md-4"><label class="panel-label" for="ccTextColor">Text Color</label><div class="color-row"><input type="color" id="ccTextColor" value="#ffffff"></div></div>
                <div class="col-md-4"><label class="panel-label" for="ccPriority">Priority</label><select class="panel-select" id="ccPriority"><option value="critical">Critical</option><option value="high" selected>High</option><option value="normal">Normal</option></select></div>
                <div class="col-12"><label class="panel-label" for="ccIcon">Icon (Font Awesome class)</label><div style="display:flex;gap:8px;align-items:center"><div class="icon-preview" id="ccIconPreview"><i class="fas fa-exclamation-triangle"></i></div><input class="panel-input" id="ccIcon" value="fa-exclamation-triangle" oninput="document.getElementById('ccIconPreview').innerHTML='<i class=fas '+this.value+'></i>'"></div></div>
                <div class="col-12"><label class="panel-label" for="ccMsgEn">Announcement (EN)</label><textarea class="panel-textarea" id="ccMsgEn" rows="3" placeholder="Code Orange... Code Orange... {loc}. All staff respond."></textarea></div>
                <div class="col-12"><label class="panel-label" for="ccMsgAr">Announcement (AR)</label><textarea class="panel-textarea" id="ccMsgAr" rows="2" dir="rtl" placeholder="اختياري"></textarea></div>
                <div class="col-12"><label class="panel-label" for="ccAction">Required Action Note</label><input class="panel-input" type="text" id="ccAction" placeholder="What staff should do…"></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call blue" onclick="addCustomCode()"><i class="fas fa-save"></i> Save Code</button>
                <button onclick="closeModal('ccAddModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="schedEditModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box" style="max-width:960px;">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-alt me-2"></i><span data-i18n="sched.editor_title">Schedule Editor</span></h3>
            <button class="modal-close" onclick="closeModal('schedEditModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="panel-label">Title (EN)</label><input class="panel-input" id="schedTitleEn" placeholder="Monthly Duty Schedule"></div>
                <div class="col-md-6"><label class="panel-label">Title (AR)</label><input class="panel-input" id="schedTitleAr" dir="rtl" placeholder="جدول المناوبات الشهري"></div>
                <div class="col-md-6"><label class="panel-label">Approved By</label><input class="panel-input" id="schedApprovedBy" placeholder="Department Head Name"></div>
                <div class="col-md-6"><label class="panel-label">Approver Title</label><input class="panel-input" id="schedApproverTitle" placeholder="Head of Department"></div>
            </div>
            <div class="sched-editor-table-wrap" id="schedEditorTable"></div>
            <p style="font-size:.75rem;color:var(--text-muted);margin-top:.5rem;">Click cells to cycle: M → E → N → O → C → V → clear</p>
            <div class="modal-actions">
                <button class="btn-call blue" onclick="saveScheduleData()"><i class="fas fa-save"></i> Save</button>
                <button onclick="closeModal('schedEditModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="timerAddModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-hourglass-half me-2"></i><span data-i18n="sched.add_timer">Add Shift Timer</span></h3>
            <button class="modal-close" onclick="closeModal('timerAddModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-12"><label class="panel-label">Department</label><select class="panel-select" id="timerDept"></select></div>
                <div class="col-md-6"><label class="panel-label">Employee Name</label><input class="panel-input" id="timerEmpName" placeholder="Employee name"></div>
                <div class="col-md-6"><label class="panel-label">Operation Number</label><input class="panel-input" id="timerOpNum" placeholder="OP-001"></div>
                <div class="col-md-6"><label class="panel-label">Shift Type</label><select class="panel-select" id="timerShiftType"><option value="morning">Morning (07:00-15:00)</option><option value="evening">Evening (15:00-23:00)</option><option value="night">Night (23:00-07:00)</option><option value="custom">Custom</option></select></div>
                <div class="col-md-6"><label class="panel-label">End Time</label><input class="panel-input" type="datetime-local" id="timerEndTime"></div>
                <div class="col-12"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" id="timerAutoAnn" checked style="width:18px;height:18px;accent-color:var(--primary);"><span data-i18n="sched.timer_auto">Auto-announce when shift ends</span></label></div>
            </div>
            <div class="modal-actions">
                <button class="btn-call blue" onclick="addShiftTimer()"><i class="fas fa-save"></i> Save</button>
                <button onclick="closeModal('timerAddModal')" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-ov" id="deptEmpModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-box" style="max-width:800px;">
        <div class="modal-header">
            <h3><i class="fas fa-users me-2"></i><span id="deptEmpTitle" data-i18n="modal.dept_employees">Department Employees</span></h3>
            <button class="modal-close" onclick="closeModal('deptEmpModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="deptEmpDeptId">
            <div class="toolbar-row mb-3"><button class="btn-add" onclick="toggleEmpForm()"><i class="fas fa-plus"></i> Add Employee</button></div>
            <div id="deptEmpForm" style="display:none;margin-bottom:1rem;" class="card p-3">
                <div class="row g-2">
                    <div class="col-md-4"><input class="panel-input" id="empName" placeholder="Name (EN)"></div>
                    <div class="col-md-4"><input class="panel-input" id="empNameAr" placeholder="الاسم (AR)" dir="rtl"></div>
                    <div class="col-md-4"><input class="panel-input" id="empRole" placeholder="Role"></div>
                    <div class="col-md-4"><input class="panel-input" id="empPhone" placeholder="Phone"></div>
                    <div class="col-md-4"><input class="panel-input" id="empExt" placeholder="Extension"></div>
                    <div class="col-md-4"><button class="btn-call blue" onclick="saveDeptEmployee()" style="width:100%"><i class="fas fa-save"></i> Save</button></div>
                </div>
            </div>
            <div id="deptEmpList"></div>
        </div>
    </div>
</div>

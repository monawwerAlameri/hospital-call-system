<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-dept-schedules">
    <div class="page-head">
        <h1><i class="fas fa-calendar-alt me-2"></i><span data-i18n="sched.title">Department Schedules</span></h1>
        <p data-i18n="sched.sub">View and manage shift schedules for all departments</p>
    </div>
    <div class="toolbar-row" style="flex-wrap:wrap;gap:10px;">
        <select class="panel-select" id="schedDeptSelect" onchange="loadDeptSchedule()" style="max-width:280px;"><option value="" data-i18n="sched.select_dept">-- Select Department --</option></select>
        <select class="panel-select" id="schedMonth" onchange="loadDeptSchedule()" style="max-width:160px;">
            <?php
            $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            $curMonth = date('F');
            foreach ($months as $m) {
                $sel = $m === $curMonth ? ' selected' : '';
                echo "<option value=\"$m\"$sel>$m</option>";
            }
            ?>
        </select>
        <input type="number" class="panel-input" id="schedYear" value="<?= date('Y') ?>" style="max-width:100px;" onchange="loadDeptSchedule()">
        <button class="btn-add" onclick="openScheduleEditor()"><i class="fas fa-edit"></i> <span data-i18n="sched.edit_btn">Edit Schedule</span></button>
        <button class="btn-refresh" onclick="loadDeptSchedule()"><i class="fas fa-sync me-1"></i> <span data-i18n="misc.refresh">Refresh</span></button>
        <button class="btn-refresh" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;" onclick="printSchedule()"><i class="fas fa-print me-1"></i> Print</button>
    </div>
    <div class="sched-info-bar" id="schedInfoBar" style="display:none;">
        <div class="row g-3">
            <div class="col-md-4"><div class="sched-info-item"><label data-i18n="sched.head">Department Head</label><span id="schedHeadName">—</span></div></div>
            <div class="col-md-4"><div class="sched-info-item"><label data-i18n="sched.approved_by">Approved By</label><span id="schedApprover">—</span></div></div>
            <div class="col-md-4"><div class="sched-info-item"><label data-i18n="sched.employees">Total Employees</label><span id="schedEmpCount">0</span></div></div>
        </div>
    </div>
    <div class="sched-legend" id="schedLegend" style="display:none;">
        <span class="sched-legend-item"><span class="sched-dot" style="background:#3b82f6;"></span> <span data-i18n="sched.morning">Morning (M)</span></span>
        <span class="sched-legend-item"><span class="sched-dot" style="background:#f59e0b;"></span> <span data-i18n="sched.evening">Evening (E)</span></span>
        <span class="sched-legend-item"><span class="sched-dot" style="background:#6366f1;"></span> <span data-i18n="sched.night">Night (N)</span></span>
        <span class="sched-legend-item"><span class="sched-dot" style="background:#ef4444;"></span> <span data-i18n="sched.off">Off (O)</span></span>
        <span class="sched-legend-item"><span class="sched-dot" style="background:#10b981;"></span> <span data-i18n="sched.oncall">On-Call (C)</span></span>
        <span class="sched-legend-item"><span class="sched-dot" style="background:#ec4899;"></span> <span data-i18n="sched.vacation">Vacation (V)</span></span>
    </div>
    <div class="sched-table-wrap" id="schedTableWrap">
        <div class="empty-state" style="padding:3rem 0;">
            <i class="fas fa-calendar-alt" style="font-size:2.5rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <p data-i18n="sched.select_prompt">Select a department to view its schedule</p>
        </div>
    </div>
    <div class="sched-timer-section" id="schedTimerSection" style="display:none;margin-top:1.5rem;">
        <div class="card">
            <div class="card-header">
                <div class="stat-icon" style="width:38px;height:38px;background:linear-gradient(135deg,#f59e0b,#d97706);"><i class="fas fa-hourglass-half"></i></div>
                <div><h2 data-i18n="sched.active_timers">Active Shift Timers</h2><p data-i18n="sched.timer_desc">Countdown to shift changes with auto-announcements</p></div>
                <button class="btn-add ms-auto" onclick="openAddTimerModal()"><i class="fas fa-plus"></i> <span data-i18n="sched.add_timer">Add Timer</span></button>
            </div>
            <div class="card-body"><div id="activeTimersList"></div></div>
        </div>
    </div>
</div>

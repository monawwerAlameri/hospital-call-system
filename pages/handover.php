<?php /* Shift Handover Page */ ?>
<div class="tab-content" id="tab-handover">
    <div class="page-head ho-page-head">
        <div class="ho-head-left">
            <div class="ho-head-icon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <h1 data-i18n="ho.title">Shift Handover</h1>
                <p data-i18n="ho.sub">Log shift notes and broadcast handover announcements</p>
            </div>
        </div>
    </div>

    <div class="ho-layout">
        <div class="ho-form-col">
            <div class="ho-form-card">
                <div class="ho-form-header">
                    <div class="ho-form-icon"><i class="fas fa-pen-to-square"></i></div>
                    <div>
                        <h3 data-i18n="ho.new_entry">New Handover Entry</h3>
                        <p data-i18n="ho.new_entry_sub">Document shift transition details</p>
                    </div>
                </div>

                <div class="ho-shift-row">
                    <div class="ho-shift-block outgoing">
                        <div class="ho-shift-label">
                            <i class="fas fa-arrow-right-from-bracket"></i>
                            <span data-i18n="ho.shift_from">Outgoing Shift</span>
                        </div>
                        <select class="panel-select" id="hoShiftFrom">
                            <option value="morning">Morning (07:00 – 15:00)</option>
                            <option value="evening">Evening (15:00 – 23:00)</option>
                            <option value="night">Night (23:00 – 07:00)</option>
                        </select>
                    </div>
                    <div class="ho-shift-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="ho-shift-block incoming">
                        <div class="ho-shift-label">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            <span data-i18n="ho.shift_to">Incoming Shift</span>
                        </div>
                        <select class="panel-select" id="hoShiftTo">
                            <option value="evening">Evening (15:00 – 23:00)</option>
                            <option value="night">Night (23:00 – 07:00)</option>
                            <option value="morning">Morning (07:00 – 15:00)</option>
                        </select>
                    </div>
                </div>

                <div class="ho-field-group">
                    <label class="panel-label"><i class="fas fa-hospital me-1"></i> <span data-i18n="ho.department">Department</span></label>
                    <select class="panel-select" id="hoDept">
                        <option value="" data-i18n="ho.all_depts">All Departments</option>
                    </select>
                </div>

                <div class="ho-field-group">
                    <label class="panel-label"><i class="fas fa-file-alt me-1"></i> <span data-i18n="ho.notes">Handover Notes</span></label>
                    <textarea class="panel-textarea" id="hoNotes" rows="5" placeholder="Patient updates, pending tasks, critical alerts, medication notes..."></textarea>
                </div>

                <div class="ho-priority-field">
                    <label class="panel-label"><i class="fas fa-layer-group me-1"></i> <span data-i18n="ho.priority">Priority Level</span></label>
                    <div class="ho-priority-chips">
                        <label class="ho-priority-chip routine">
                            <input type="radio" name="hoPriorityRadio" value="routine" onchange="document.getElementById('hoPriority').value='routine'" checked>
                            <i class="fas fa-minus-circle"></i>
                            <span data-i18n="ho.priority_routine">Routine</span>
                        </label>
                        <label class="ho-priority-chip important">
                            <input type="radio" name="hoPriorityRadio" value="important" onchange="document.getElementById('hoPriority').value='important'">
                            <i class="fas fa-exclamation-circle"></i>
                            <span data-i18n="ho.priority_important">Important</span>
                        </label>
                        <label class="ho-priority-chip critical">
                            <input type="radio" name="hoPriorityRadio" value="critical" onchange="document.getElementById('hoPriority').value='critical'">
                            <i class="fas fa-triangle-exclamation"></i>
                            <span data-i18n="ho.priority_critical">Critical</span>
                        </label>
                    </div>
                    <input type="hidden" id="hoPriority" value="routine">
                </div>

                <div class="ho-actions">
                    <button class="btn-call blue ho-save-btn" onclick="saveHandover()">
                        <i class="fas fa-save"></i>
                        <span data-i18n="ho.save">Save Entry</span>
                    </button>
                    <button class="btn-call ho-broadcast-btn" onclick="broadcastHandover()">
                        <i class="fas fa-bullhorn"></i>
                        <span data-i18n="ho.broadcast">Broadcast &amp; Save</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="ho-log-col">
            <div class="ho-log-card">
                <div class="ho-log-header">
                    <div class="ho-log-title">
                        <i class="fas fa-history"></i>
                        <h3 data-i18n="ho.log">Handover Log</h3>
                    </div>
                    <button class="btn-refresh sml" onclick="loadHandoverLog()">
                        <i class="fas fa-sync"></i>
                    </button>
                </div>
                <div id="handoverLog" class="ho-log-list">
                    <div class="empty-state">
                        <i class="fas fa-clipboard"></i>
                        <p data-i18n="ho.no_entries">No handover entries yet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

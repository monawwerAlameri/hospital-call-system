<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-scheduled">
    <div class="page-head">
        <h1><i class="fas fa-clock me-2"></i><span data-i18n="sch.title">Scheduled Announcements</span></h1>
        <p data-i18n="sch.sub">Set timed announcements — time selection is optional</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon blue"><i class="fas fa-plus"></i></div>
                    <div><h3>New Scheduled</h3><p>Time is optional</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="schTitle">Title</label><input class="panel-input" type="text" id="schTitle" placeholder="e.g. Morning Shift Reminder"></div>
                    <div class="panel-field">
                        <label class="panel-label" for="schMsg">Announcement Message</label>
                        <textarea class="panel-textarea" id="schMsg" rows="3" placeholder="Full announcement text…"></textarea>
                        <button onclick="previewScheduled()" class="btn-preview mt-2"><i class="fas fa-play me-1"></i>Preview</button>
                    </div>
                    <div class="panel-field"><label class="panel-label" for="schTime">Scheduled Time <span class="text-muted fw-400">(optional)</span></label><input type="datetime-local" class="panel-input" id="schTime"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="panel-label" for="schDoctor">Doctor (opt)</label><select class="panel-select" id="schDoctor"><option value="">-- None --</option></select></div>
                        <div class="col-6"><label class="panel-label" for="schLoc">Location (opt)</label><select class="panel-select" id="schLoc"></select></div>
                        <div class="col-12"><label class="panel-label" for="schRole">Target Role (opt)</label><select class="panel-select" id="schRole"><option value="">-- All Staff --</option></select></div>
                    </div>
                    <div class="panel-field mt-2"><label class="panel-label" for="schRepeat">Repeat</label><select class="panel-select" id="schRepeat"><option value="once">Once</option><option value="daily">Daily</option><option value="weekly">Weekly</option><option value="none">No repeat</option></select></div>
                    <div class="voice-toggle mt-2" role="group">
                        <button class="voice-btn sch-voice" data-gender="male" onclick="sv('sch','male')"><i class="fas fa-mars"></i> Male</button>
                        <button class="voice-btn sch-voice voice-active female" data-gender="female" onclick="sv('sch','female')"><i class="fas fa-venus"></i> Female</button>
                    </div>
                    <button class="btn-call blue mt-3" onclick="saveScheduled()"><i class="fas fa-save"></i> Save Announcement</button>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-list me-2"></i>Upcoming</h2></div>
                <div class="card-body">
                    <div class="sched-grid" id="scheduledList">
                        <div class="empty-state"><i class="fas fa-clock"></i><p>No scheduled announcements yet</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

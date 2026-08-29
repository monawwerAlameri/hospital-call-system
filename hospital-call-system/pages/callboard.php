<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-callboard">
    <div class="page-head">
        <h1><i class="fas fa-broadcast-tower me-2"></i><span data-i18n="cb.title">Call Board</span></h1>
        <p data-i18n="cb.sub">Full announcement panel — all call types in one view</p>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="stat-icon red" style="width:38px;height:38px;"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <h2 data-i18n="cb.emergency_codes">Emergency Codes</h2>
                <p><span data-i18n="cb.broadcasting_from">Broadcasting from:</span> <strong id="cbLoc">Emergency Room</strong></p>
            </div>
            <button onclick="openLoc()" class="btn-call blue ms-auto" style="width:auto;padding:8px 16px;font-size:.78rem">
                <i class="fas fa-map-marker-alt"></i> <span data-i18n="cb.change_loc">Change Location</span>
            </button>
        </div>
        <div class="card-body">
            <div class="code-grid" id="cgCB"></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon blue"><i class="fas fa-user-md"></i></div>
                    <div><h3 data-i18n="cb.call_doctor">Call On-Call Doctor</h3><p data-i18n="cb.physician_paging">Physician paging</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="cbSp" data-i18n="form.specialty">Specialty</label><select class="panel-select" id="cbSp"></select></div>
                    <div class="panel-field"><label class="panel-label" for="cbLv" data-i18n="form.level">Level</label><select class="panel-select" id="cbLv"></select></div>
                    <div class="panel-field"><label class="panel-label" for="cbEx" data-i18n="form.extension">Extension</label><input class="panel-input" type="text" id="cbEx" placeholder="e.g. 2675"></div>
                    <div class="panel-field"><label class="panel-label" for="cbFr" data-i18n="form.calling_from">Calling From</label><select class="panel-select" id="cbFr"></select></div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn" id="vmCB" onclick="sv('cb','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn voice-active female" id="vfCB" onclick="sv('cb','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call blue" onclick="callDoc('cb')"><i class="fas fa-phone-alt"></i> <span data-i18n="form.call_now">Call Now</span></button>
                    <div class="speaking-indicator" id="spkD"><div class="wave-mini"><span></span><span></span><span></span><span></span><span></span></div>Broadcasting doctor page…</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon green"><i class="fas fa-users"></i></div>
                    <div><h3 data-i18n="cb.call_staff">Call Staff / Admin</h3><p data-i18n="cb.personnel_paging">Personnel paging</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="cbSR" data-i18n="form.staff_role">Staff Role</label><select class="panel-select" id="cbSR"></select></div>
                    <div class="panel-field"><label class="panel-label" for="cbSE" data-i18n="form.extension">Extension</label><input class="panel-input" type="text" id="cbSE" placeholder="e.g. 2241"></div>
                    <div class="panel-field"><label class="panel-label" for="cbSL" data-i18n="form.report_to">Report To Location</label><select class="panel-select" id="cbSL"></select></div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn voice-active male" id="vmST" onclick="sv('st','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn" id="vfST" onclick="sv('st','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call green" onclick="callSt()"><i class="fas fa-phone-alt"></i> <span data-i18n="form.call_now">Call Now</span></button>
                    <div class="speaking-indicator" id="spkS"><div class="wave-mini"><span></span><span></span><span></span><span></span><span></span></div>Broadcasting staff page…</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon purple"><i class="fas fa-bullhorn"></i></div>
                    <div><h3 data-i18n="cb.custom_ann">Custom Announcement</h3><p data-i18n="cb.free_text">Free-text broadcast</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="cbCA" data-i18n="form.message">Message</label><textarea class="panel-textarea" id="cbCA" rows="4" placeholder="Type your announcement…"></textarea></div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn" id="vmCA" onclick="sv('ca','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn voice-active female" id="vfCA" onclick="sv('ca','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call purple" onclick="bcastCA()"><i class="fas fa-broadcast-tower"></i> <span data-i18n="form.broadcast">Broadcast</span></button>
                    <div class="speaking-indicator" id="spkC"><div class="wave-mini"><span></span><span></span><span></span><span></span><span></span></div>Broadcasting…</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="stat-icon blue" style="width:38px;height:38px;"><i class="fas fa-signal"></i></div>
            <h2 data-i18n="cb.live_feed">Live Call Feed</h2>
        </div>
        <div class="card-body">
            <ul class="feed-list" id="cbFeed">
                <li class="feed-empty"><span data-i18n="cb.waiting">Waiting for announcements…</span></li>
            </ul>
        </div>
    </div>
</div>

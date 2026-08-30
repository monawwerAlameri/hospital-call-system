<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-staff">
    <div class="page-head">
        <h1><i class="fas fa-users me-2"></i><span data-i18n="st.title">Call Staff</span></h1>
        <p data-i18n="st.sub">Page non-medical staff — security, housekeeping, maintenance, etc.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon green"><i class="fas fa-id-badge"></i></div>
                    <div><h3 data-i18n="st.select_role">Select Role & Call</h3><p data-i18n="st.select_role_sub">Choose role, location, extension</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="stRl" data-i18n="form.staff_role">Staff Role</label><select class="panel-select" id="stRl"></select></div>
                    <div class="panel-field"><label class="panel-label" for="stEx" data-i18n="form.extension">Extension</label><input class="panel-input" type="text" id="stEx" placeholder="e.g. 2241"></div>
                    <div class="panel-field"><label class="panel-label" for="stLc" data-i18n="form.report_to">Report To</label><select class="panel-select" id="stLc"></select></div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn voice-active male" id="vmST2" onclick="sv('st2','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn" id="vfST2" onclick="sv('st2','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call green" onclick="callStFull()"><i class="fas fa-phone-alt"></i> <span data-i18n="form.call_now">Call Now</span></button>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-clock me-2"></i><span data-i18n="st.recent">Recent Staff Calls</span></h2></div>
                <div class="card-body">
                    <ul class="feed-list" id="stLog">
                        <li class="feed-empty"><span data-i18n="st.no_calls">No staff calls yet</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

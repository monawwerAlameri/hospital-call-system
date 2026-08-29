<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-doctors">
    <div class="page-head">
        <h1><i class="fas fa-user-md me-2"></i><span data-i18n="dr.title">Call Doctor</span></h1>
        <p data-i18n="dr.sub">Page an on-call doctor by specialty and level</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon blue"><i class="fas fa-stethoscope"></i></div>
                    <div><h3 data-i18n="dr.find">Find & Call</h3><p data-i18n="dr.find_sub">Select specialty, level, extension</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field"><label class="panel-label" for="drSp" data-i18n="form.specialty">Specialty</label><select class="panel-select" id="drSp"></select></div>
                    <div class="panel-field"><label class="panel-label" for="drLv" data-i18n="form.level">Level</label><select class="panel-select" id="drLv"></select></div>
                    <div class="panel-field"><label class="panel-label" for="drEx" data-i18n="form.extension">Extension</label><input class="panel-input" type="text" id="drEx" placeholder="e.g. 2675"></div>
                    <div class="panel-field"><label class="panel-label" for="drFr" data-i18n="form.calling_from">Calling From</label><select class="panel-select" id="drFr"></select></div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn" id="vmDR" onclick="sv('dr','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn voice-active female" id="vfDR" onclick="sv('dr','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call blue" onclick="callDoc('dr')"><i class="fas fa-phone-alt"></i> <span data-i18n="form.call_now">Call Now</span></button>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-address-book me-2"></i><span data-i18n="dr.directory">Doctor Directory</span></h2></div>
                <div class="card-body">
                    <div class="doctor-grid" id="drDirectory"></div>
                </div>
            </div>
        </div>
    </div>
</div>

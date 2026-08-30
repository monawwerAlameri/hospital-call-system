<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-custom">
    <div class="page-head">
        <h1><i class="fas fa-bullhorn me-2"></i><span data-i18n="cust.title">Custom Announcement</span></h1>
        <p data-i18n="cust.sub">Compose and broadcast any message hospital-wide</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon purple"><i class="fas fa-pen-fancy"></i></div>
                    <div><h3 data-i18n="cust.compose">Compose</h3><p data-i18n="cust.compose_sub">Type or select a template</p></div>
                </div>
                <div class="panel-body">
                    <div class="panel-field">
                        <label class="panel-label" data-i18n="cust.quick_templates">Quick Templates</label>
                        <div class="template-btns" id="tmplBtns"></div>
                    </div>
                    <div class="panel-field">
                        <label class="panel-label" for="fcMsg" data-i18n="form.message">Message</label>
                        <textarea class="panel-textarea" id="fcMsg" rows="5" placeholder="Type your announcement…"></textarea>
                    </div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn" id="vmFC" onclick="sv('fc','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn voice-active female" id="vfFC" onclick="sv('fc','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call purple" onclick="bcastFull()"><i class="fas fa-broadcast-tower"></i> <span data-i18n="form.broadcast">Broadcast</span></button>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-history me-2"></i><span data-i18n="cust.history">Announcement History</span></h2></div>
                <div class="card-body">
                    <ul class="feed-list" id="custHist">
                        <li class="feed-empty"><span>No custom announcements yet</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

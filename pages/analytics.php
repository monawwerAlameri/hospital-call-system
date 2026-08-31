<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-analytics">
    <div class="page-head">
        <h1><i class="fas fa-chart-bar me-2"></i><span data-i18n="an.title">Analytics</span></h1>
        <p data-i18n="an.sub">Call statistics for this session</p>
    </div>
    <div class="stats-grid" id="aSg"></div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h2 data-i18n="an.by_type">By Call Type</h2></div>
                <div class="card-body" id="aType"><p class="no-data">No data yet</p></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h2 data-i18n="an.by_location">By Location</h2></div>
                <div class="card-body" id="aLoc"><p class="no-data">No data yet</p></div>
            </div>
        </div>
    </div>
</div>

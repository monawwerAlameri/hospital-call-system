<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-emergency">
    <div class="page-head">
        <h1><i class="fas fa-exclamation-triangle me-2"></i><span data-i18n="em.title">Emergency Codes</span></h1>
        <p data-i18n="em.sub">Hospital emergency broadcast system — immediate response required</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div style="flex:1">
                        <h2 data-i18n="em.activate">Activate Emergency Code</h2>
                        <p style="font-size:0.73rem;color:var(--text-muted);margin-top:2px">
                            <span data-i18n="misc.location">Location:</span> <strong id="emLoc">Emergency Room</strong>
                            <button onclick="openLoc()" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:.73rem;font-weight:700;margin-left:8px"><i class="fas fa-edit"></i> <span data-i18n="form.change">Change</span></button>
                        </p>
                    </div>
                </div>
                <div class="card-body"><div class="code-grid" id="cgEM" style="gap:16px"></div></div>
            </div>
            <div class="card mt-4">
                <div class="card-header"><h2><i class="fas fa-book-medical me-2"></i><span data-i18n="em.reference">Emergency Code Reference Guide</span></h2></div>
                <div style="overflow-x:auto">
                    <table class="data-table">
                        <thead><tr><th data-i18n="em.col_code">Code</th><th data-i18n="em.col_meaning">Meaning</th><th data-i18n="em.col_action">Required Action</th></tr></thead>
                        <tbody id="codeRef"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-clock me-2"></i><span data-i18n="em.recent">Recent Activations</span></h2></div>
                <div class="card-body">
                    <ul class="feed-list" id="emFeed">
                        <li class="feed-empty"><span data-i18n="em.no_codes">No emergency codes activated yet</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

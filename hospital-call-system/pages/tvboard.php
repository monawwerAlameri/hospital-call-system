<?php /* TV Display Board Page */ ?>
<div class="tab-content" id="tab-tvboard">
    <div class="page-head tv-page-head">
        <div class="tv-head-left">
            <div class="tv-head-icon"><i class="fas fa-tv"></i></div>
            <div>
                <h1 data-i18n="tv.title">TV Display Board</h1>
                <p data-i18n="tv.sub">Real-time announcement board for department displays</p>
            </div>
        </div>
        <span class="live-badge"><i class="fas fa-circle" style="font-size:.45rem"></i> LIVE</span>
    </div>

    <div class="tv-stats-row">
        <div class="tv-stat-chip purple">
            <div class="tv-stat-icon"><i class="fas fa-broadcast-tower"></i></div>
            <div class="tv-stat-info">
                <div class="tv-stat-val" id="tvScreenCount">0</div>
                <div class="tv-stat-lbl" data-i18n="tv.active_screens">Active Screens</div>
            </div>
        </div>
        <div class="tv-stat-chip green">
            <div class="tv-stat-icon"><i class="fas fa-bullhorn"></i></div>
            <div class="tv-stat-info">
                <div class="tv-stat-val" id="tvTodayCount">0</div>
                <div class="tv-stat-lbl" data-i18n="tv.today_broadcasts">Today's Broadcasts</div>
            </div>
        </div>
        <div class="tv-stat-chip amber">
            <div class="tv-stat-icon"><i class="fas fa-clock"></i></div>
            <div class="tv-stat-info">
                <div class="tv-stat-val" id="tvLastTime" style="font-size:.9rem">—</div>
                <div class="tv-stat-lbl" data-i18n="tv.last_broadcast">Last Broadcast</div>
            </div>
        </div>
    </div>

    <div class="tv-layout">
        <div class="tv-compose-card">
            <div class="tv-compose-header">
                <div class="tv-compose-icon"><i class="fas fa-pencil-alt"></i></div>
                <div>
                    <h3 data-i18n="tv.compose">Compose Board Message</h3>
                    <p data-i18n="tv.compose_sub">Message will appear on all connected screens</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="panel-label" for="tvMsgEn"><i class="fas fa-globe me-1"></i> <span data-i18n="tv.msg_en">Message (English)</span></label>
                    <textarea class="panel-textarea" id="tvMsgEn" rows="4" placeholder="Attention all staff — visiting hours are now open..."></textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="panel-label" for="tvMsgAr"><i class="fas fa-language me-1"></i> <span data-i18n="tv.msg_ar">Message (Arabic)</span></label>
                    <textarea class="panel-textarea" id="tvMsgAr" rows="4" dir="rtl" placeholder="تنبيه للكوادر — ساعات الزيارة بدأت..."></textarea>
                </div>
            </div>

            <div class="tv-options-row">
                <div class="tv-option-group">
                    <label class="panel-label" for="tvDuration"><i class="fas fa-hourglass-half me-1"></i> <span data-i18n="tv.duration">Display Duration</span></label>
                    <select class="panel-select" id="tvDuration">
                        <option value="30">30 seconds</option>
                        <option value="60" selected>1 minute</option>
                        <option value="300">5 minutes</option>
                        <option value="600">10 minutes</option>
                        <option value="0">Until dismissed</option>
                    </select>
                </div>
                <div class="tv-option-group">
                    <label class="panel-label" for="tvPriority"><i class="fas fa-layer-group me-1"></i> <span data-i18n="tv.priority">Priority</span></label>
                    <select class="panel-select" id="tvPriority">
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="tv-option-group send-group">
                    <label class="panel-label" style="opacity:0">.</label>
                    <button class="btn-call blue tv-send-btn" onclick="sendTVBoard()">
                        <i class="fas fa-paper-plane"></i>
                        <span data-i18n="tv.send_btn">Send to Board</span>
                    </button>
                </div>
            </div>

            <div class="tv-board-preview" id="tvBoardPreview">
                <div class="tv-preview-bar">
                    <div class="tv-preview-dots"><span></span><span></span><span></span></div>
                    <span class="tv-preview-label" data-i18n="tv.preview">LIVE BOARD PREVIEW</span>
                    <span class="live-badge"><i class="fas fa-circle" style="font-size:.45rem"></i> LIVE</span>
                </div>
                <div id="tvPreviewMsg" class="tv-preview-msg" data-i18n="tv.no_msg">No active message — board is clear</div>
            </div>
        </div>
    </div>
</div>

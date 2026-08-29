<?php /* Quiet Hours Page */ ?>
<div class="tab-content" id="tab-quiethours">
    <div class="page-head qh-page-head">
        <div class="qh-head-content">
            <div class="qh-head-icon"><i class="fas fa-moon"></i></div>
            <div>
                <h1 data-i18n="qh.title">Quiet Hours</h1>
                <p data-i18n="qh.sub">Restrict non-emergency announcements during designated rest periods</p>
            </div>
        </div>
        <div class="qh-status-badge" id="qhStatusBadge">
            <i class="fas fa-circle" id="quietStatusIcon"></i>
            <span id="quietStatusLabel" data-i18n="quiet.inactive">Inactive</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="qh-config-card">
                <div class="qh-card-header">
                    <div class="qh-card-header-icon" style="background:linear-gradient(135deg,#1e40af,#3b82f6)">
                        <i class="fas fa-sliders"></i>
                    </div>
                    <div>
                        <h3 data-i18n="qh.config">Configuration</h3>
                        <p data-i18n="qh.config_sub">Set the active hours and days</p>
                    </div>
                </div>

                <div class="qh-enable-row">
                    <label class="qh-toggle-label">
                        <div class="toggle-switch">
                            <input type="checkbox" id="quietEnabled" onchange="toggleQuietHours(this.checked)">
                            <span class="toggle-slider"></span>
                        </div>
                        <div>
                            <span class="fw-700" data-i18n="qh.enable">Enable Quiet Hours</span>
                            <span class="qh-toggle-sub" data-i18n="qh.enable_sub">Mute non-emergency announcements</span>
                        </div>
                    </label>
                </div>

                <div class="qh-time-row">
                    <div class="qh-time-block">
                        <div class="qh-time-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">
                            <i class="fas fa-moon"></i>
                        </div>
                        <div class="qh-time-field">
                            <label class="panel-label" for="quietStart" data-i18n="qh.start_time">Start Time</label>
                            <input type="time" class="panel-input qh-time-input" id="quietStart" value="22:00">
                        </div>
                    </div>
                    <div class="qh-time-arrow"><i class="fas fa-arrow-right"></i></div>
                    <div class="qh-time-block">
                        <div class="qh-time-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
                            <i class="fas fa-sun"></i>
                        </div>
                        <div class="qh-time-field">
                            <label class="panel-label" for="quietEnd" data-i18n="qh.end_time">End Time</label>
                            <input type="time" class="panel-input qh-time-input" id="quietEnd" value="06:00">
                        </div>
                    </div>
                </div>

                <div class="qh-days-section">
                    <label class="panel-label mb-2" data-i18n="qh.repeat_days">Active Days</label>
                    <div class="qh-days-grid" id="qhDays">
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Sun" checked><span>Sun</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Mon" checked><span>Mon</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Tue" checked><span>Tue</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Wed" checked><span>Wed</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Thu" checked><span>Thu</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Fri"><span>Fri</span></label>
                        <label class="qh-day-chip"><input type="checkbox" class="quietDay" value="Sat"><span>Sat</span></label>
                    </div>
                </div>

                <button class="btn-call blue qh-save-btn" onclick="saveQuietHours()">
                    <i class="fas fa-save"></i>
                    <span data-i18n="qh.save">Save Configuration</span>
                </button>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="qh-info-card">
                <div class="qh-card-header">
                    <div class="qh-card-header-icon" style="background:linear-gradient(135deg,#059669,#34d399)">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div>
                        <h3 data-i18n="qh.info">About Quiet Hours</h3>
                        <p data-i18n="qh.info_sub">How it works</p>
                    </div>
                </div>

                <div class="qh-info-text" data-i18n="qh.info_text">
                    During quiet hours, only emergency codes (Code Blue, Code Red, etc.) will be announced. All other announcements will be queued and delivered after quiet hours end.
                </div>

                <div class="qh-allowed-section">
                    <div class="qh-allowed-header">
                        <i class="fas fa-check-circle" style="color:#059669"></i>
                        <span class="fw-700" data-i18n="qh.allowed_codes">Always Allowed Codes</span>
                    </div>
                    <div class="qh-allowed-chips" id="qhAllowedCodes">
                        <span class="qh-code-chip" style="background:rgba(21,73,192,.12);color:#1549c0"><i class="fas fa-heart-pulse"></i> Code Blue</span>
                        <span class="qh-code-chip" style="background:rgba(220,38,38,.12);color:#dc2626"><i class="fas fa-fire"></i> Code Red</span>
                        <span class="qh-code-chip" style="background:rgba(190,24,93,.12);color:#be185d"><i class="fas fa-baby"></i> Code Pink</span>
                        <span class="qh-code-chip" style="background:rgba(30,41,59,.12);color:#1e293b"><i class="fas fa-shield-halved"></i> Code Black</span>
                    </div>
                </div>

                <div class="qh-desc-rows">
                    <div class="qh-desc-row"><i class="fas fa-volume-xmark" style="color:#7c3aed"></i><span data-i18n="qh.desc1">Non-emergency pages will be silenced</span></div>
                    <div class="qh-desc-row"><i class="fas fa-clock" style="color:#d97706"></i><span data-i18n="qh.desc2">Announcements queued automatically</span></div>
                    <div class="qh-desc-row"><i class="fas fa-bell" style="color:#059669"></i><span data-i18n="qh.desc3">Emergency codes always broadcast immediately</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

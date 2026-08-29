<?php /* Quick SOS Wall Page */ ?>
<div class="tab-content" id="tab-sos">
    <div class="page-head sos-page-head">
        <div class="sos-head-left">
            <div class="sos-head-icon"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <h1 data-i18n="sos.title">Quick SOS Wall</h1>
                <p data-i18n="sos.sub">One-tap emergency broadcast for critical situations</p>
            </div>
        </div>
        <div class="sos-live-indicator">
            <span class="live-badge"><i class="fas fa-circle" style="font-size:.45rem"></i> LIVE</span>
        </div>
    </div>

    <div class="sos-grid">
        <button class="sos-card sos-blue" onclick="sendQuickSOS('blue')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-heart-pulse"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_blue">Code Blue</span>
                    <small class="sos-card-desc" data-i18n="sos.cardiac">Cardiac Arrest</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-red" onclick="sendQuickSOS('red')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-fire"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_red">Code Red</span>
                    <small class="sos-card-desc" data-i18n="sos.fire">Fire Emergency</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-pink" onclick="sendQuickSOS('pink')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-baby"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_pink">Code Pink</span>
                    <small class="sos-card-desc" data-i18n="sos.infant">Infant Alert</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-black" onclick="sendQuickSOS('black')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-shield-halved"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_black">Code Black</span>
                    <small class="sos-card-desc" data-i18n="sos.threat">Security Threat</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-orange" onclick="sendQuickSOS('orange')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-person-falling-burst"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_orange">Code Orange</span>
                    <small class="sos-card-desc" data-i18n="sos.mass_casualty">Mass Casualty</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-yellow" onclick="sendQuickSOS('yellow')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-person-walking-arrow-loop-left"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_yellow">Code Yellow</span>
                    <small class="sos-card-desc" data-i18n="sos.missing_patient">Missing Patient</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-silver" onclick="sendQuickSOS('silver')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-gun"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_silver">Code Silver</span>
                    <small class="sos-card-desc" data-i18n="sos.armed_person">Armed Person</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>

        <button class="sos-card sos-white" onclick="sendQuickSOS('white')">
            <div class="sos-card-inner">
                <div class="sos-card-icon"><i class="fas fa-snowflake"></i></div>
                <div class="sos-card-content">
                    <span class="sos-card-name" data-i18n="sos.code_white">Code White</span>
                    <small class="sos-card-desc" data-i18n="sos.infrastructure">Infrastructure</small>
                </div>
                <div class="sos-card-arrow"><i class="fas fa-broadcast-tower"></i></div>
            </div>
            <div class="sos-card-ripple"></div>
        </button>
    </div>

    <div class="sos-log-card">
        <div class="sos-log-header">
            <div class="sos-log-title">
                <i class="fas fa-clock-rotate-left"></i>
                <h3 data-i18n="sos.recent">Recent SOS Events</h3>
            </div>
            <button class="btn-refresh sml" onclick="renderSOSLog()">
                <i class="fas fa-sync"></i>
            </button>
        </div>
        <div id="sosLog" class="sos-log-list">
            <div class="empty-state" style="padding:2rem 0">
                <i class="fas fa-shield-heart"></i>
                <p data-i18n="sos.no_events">No SOS events — all clear</p>
            </div>
        </div>
    </div>
</div>

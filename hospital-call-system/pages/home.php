<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content active" id="tab-home">
    <div class="page-head">
        <svg class="svg-deco-corner" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="60" cy="60" r="55" fill="none" stroke="#1F2A6D" stroke-width="1.5"/>
            <circle cx="60" cy="60" r="40" fill="none" stroke="#1F6F8B" stroke-width="1"/>
            <rect x="52" y="25" width="16" height="70" rx="4" fill="#1FA971"/>
            <rect x="25" y="52" width="70" height="16" rx="4" fill="#1FA971"/>
        </svg>
        <h1 data-i18n="dash.title">Dashboard</h1>
        <p id="dgr" data-i18n="dash.greeting">Good day — King Khalid Hospital Internal Call System</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-broadcast-tower" aria-hidden="true"></i></div>
            <div class="stat-info">
                <div class="stat-val" id="stT">0</div>
                <div class="stat-lbl" data-i18n="dash.total_calls">Total Calls Today</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-exclamation-circle" aria-hidden="true"></i></div>
            <div class="stat-info">
                <div class="stat-val" id="stE">0</div>
                <div class="stat-lbl" data-i18n="dash.emergency_codes">Emergency Codes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-user-md" aria-hidden="true"></i></div>
            <div class="stat-info">
                <div class="stat-val" id="stD">0</div>
                <div class="stat-lbl" data-i18n="dash.doctor_pages">Doctor Pages</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users" aria-hidden="true"></i></div>
            <div class="stat-info">
                <div class="stat-val" id="stS">0</div>
                <div class="stat-lbl" data-i18n="dash.staff_calls">Staff Calls</div>
            </div>
        </div>
    </div>

    <div class="heartbeat-line" aria-hidden="true">
        <svg viewBox="0 0 600 32" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path class="hb-path" d="M0,16 L120,16 L140,16 L155,4 L165,28 L175,4 L185,28 L195,16 L210,16 L310,16 L330,16 L345,6 L355,26 L365,6 L375,26 L385,16 L400,16 L500,16 L520,16 L535,8 L545,24 L555,8 L565,24 L575,16 L600,16"/>
        </svg>
    </div>

    <div class="quick-access-grid">
        <button class="qa-btn qa-emergency" onclick="showTab('emergency')">
            <div class="qa-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <span data-i18n="dash.qa_emergency">Emergency Codes</span>
        </button>
        <button class="qa-btn qa-doctor" onclick="showTab('doctors')">
            <div class="qa-icon"><i class="fas fa-user-md"></i></div>
            <span data-i18n="dash.qa_doctor">Call Doctor</span>
        </button>
        <button class="qa-btn qa-staff" onclick="showTab('staff')">
            <div class="qa-icon"><i class="fas fa-users"></i></div>
            <span data-i18n="dash.qa_staff">Call Staff</span>
        </button>
        <button class="qa-btn qa-announce" onclick="showTab('custom')">
            <div class="qa-icon"><i class="fas fa-bullhorn"></i></div>
            <span data-i18n="dash.qa_announce">Announcement</span>
        </button>
        <button class="qa-btn qa-schedule" onclick="showTab('dept-schedules')">
            <div class="qa-icon"><i class="fas fa-calendar-alt"></i></div>
            <span data-i18n="dash.qa_schedule">Dept Schedules</span>
        </button>
        <button class="qa-btn qa-handover" onclick="showTab('handover')">
            <div class="qa-icon"><i class="fas fa-clipboard-list"></i></div>
            <span data-i18n="dash.qa_handover">Shift Handover</span>
        </button>
        <button class="qa-btn qa-tv" onclick="showTab('tvboard')">
            <div class="qa-icon"><i class="fas fa-tv"></i></div>
            <span data-i18n="dash.qa_tv">TV Board</span>
        </button>
        <button class="qa-btn qa-quiet" onclick="showTab('quiethours')">
            <div class="qa-icon"><i class="fas fa-moon"></i></div>
            <span data-i18n="dash.qa_quiet">Quiet Hours</span>
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="stat-icon red" style="width:38px;height:38px;"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i></div>
            <div>
                <h2 data-i18n="dash.quick_emergency">Quick Emergency Codes</h2>
                <p><span data-i18n="dash.quick_emergency_desc">Click to broadcast immediately from</span> <strong id="hLoc">Emergency Room</strong></p>
            </div>
        </div>
        <div class="card-body">
            <div class="code-grid" id="cgH"></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div class="stat-icon blue" style="width:38px;height:38px;"><i class="fas fa-history" aria-hidden="true"></i></div>
                    <h2 data-i18n="dash.recent_calls">Recent Calls</h2>
                </div>
                <div class="card-body">
                    <ul class="feed-list" id="rcList">
                        <li class="feed-empty">
                            <i class="fas fa-satellite-dish" aria-hidden="true"></i>
                            <span data-i18n="dash.no_calls">No calls yet this session</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon blue"><i class="fas fa-user-md" aria-hidden="true"></i></div>
                    <div>
                        <h3 data-i18n="dash.quick_doctor">Quick Call Doctor</h3>
                        <p data-i18n="dash.quick_doctor_sub">On-call paging</p>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-field">
                        <label class="panel-label" for="qSp" data-i18n="form.specialty">Specialty</label>
                        <select class="panel-select" id="qSp"></select>
                    </div>
                    <div class="panel-field">
                        <label class="panel-label" for="qLv" data-i18n="form.level">Level</label>
                        <select class="panel-select" id="qLv"></select>
                    </div>
                    <div class="panel-field">
                        <label class="panel-label" for="qEx" data-i18n="form.extension">Extension</label>
                        <input class="panel-input" type="text" id="qEx" placeholder="e.g. 2675" maxlength="6">
                    </div>
                    <div class="voice-toggle" role="group">
                        <button class="voice-btn" id="vmQ" onclick="sv('q','male')"><i class="fas fa-mars"></i> <span data-i18n="form.male">Male</span></button>
                        <button class="voice-btn voice-active female" id="vfQ" onclick="sv('q','female')"><i class="fas fa-venus"></i> <span data-i18n="form.female">Female</span></button>
                    </div>
                    <button class="btn-call blue" onclick="callDoc('q')"><i class="fas fa-phone-alt"></i> <span data-i18n="form.call_now">Call Now</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

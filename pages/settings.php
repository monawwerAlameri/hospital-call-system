<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-settings">
    <div class="page-head">
        <h1><i class="fas fa-cog me-2"></i><span data-i18n="set.title">Settings</span></h1>
        <p data-i18n="set.sub">Voice configuration, audio profile, and system preferences</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-volume-up me-2"></i>Voice & Audio</h2></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="settings-range-label" for="sRpt">Announcement Repeat Count</label>
                        <select class="form-input" id="sRpt">
                            <option value="1">Once</option>
                            <option value="2" selected>Twice (Recommended)</option>
                            <option value="3">Three times</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <div class="settings-range-label"><label for="sRt">Speech Rate (Airport: 0.62)</label><span id="sRtV">0.62</span></div>
                        <input type="range" class="form-range" id="sRt" min="0.4" max="1.2" step="0.02" value="0.62">
                    </div>
                    <div class="form-group mb-3">
                        <div class="settings-range-label"><label for="sPM">Male Voice Pitch</label><span id="sPMV">0.75</span></div>
                        <input type="range" class="form-range" id="sPM" min="0.4" max="1.3" step="0.05" value="0.75">
                    </div>
                    <div class="form-group mb-4">
                        <div class="settings-range-label"><label for="sPF">Female Voice Pitch</label><span id="sPFV">1.18</span></div>
                        <input type="range" class="form-range" id="sPF" min="0.8" max="2.0" step="0.05" value="1.18">
                    </div>
                    <div class="audio-test-card mb-4">
                        <p class="text-uppercase small fw-700 text-muted mb-3"><i class="fas fa-bell me-2"></i>Airport Chime Test</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <button onclick="window.Audio?.dingDong?.()" class="btn-call blue" style="width:auto;padding:10px 18px;font-size:.82rem"><i class="fas fa-bell"></i> Play Chimes</button>
                            <button onclick="window.Audio?.emergencyAlert?.()" class="btn-call red" style="width:auto;padding:10px 18px;font-size:.82rem"><i class="fas fa-triangle-exclamation"></i> Emergency Alert</button>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button onclick="testVoice('male')" class="btn-call blue" style="width:auto;padding:10px 18px;font-size:.82rem"><i class="fas fa-mars"></i> Test Male Voice</button>
                        <button onclick="testVoice('female')" class="btn-call purple" style="width:auto;padding:10px 18px;font-size:.82rem"><i class="fas fa-venus"></i> Test Female Voice</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-user-circle me-2"></i>Account Information</h2></div>
                <div class="card-body">
                    <div class="account-info-card mb-4">
                        <div class="sb-avatar" id="setAv" style="width:56px;height:56px;font-size:1.3rem">A</div>
                        <div>
                            <div class="fs-5 fw-800" id="setNm">Administrator</div>
                            <div class="small text-muted" id="setEm">admin@hospital.sa</div>
                            <div class="small text-primary fw-700 text-uppercase mt-1" id="setRl">admin</div>
                        </div>
                    </div>
                    <div class="form-group mb-3"><label class="form-label">Department</label><input class="form-input" id="setDept" value="—" readonly></div>
                    <div class="form-group mb-3"><label class="form-label">Hospital</label><input class="form-input" value="King Khalid Hospital — Hail" readonly></div>
                    <div class="form-group mb-4"><label class="form-label">System Version</label><input class="form-input" value="v3.0.0 — 2026" readonly></div>
                    <button class="btn-call red" onclick="doLogout()"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header"><h2><i class="fas fa-network-wired me-2"></i>System Status</h2></div>
                <div class="card-body">
                    <div class="system-status-list">
                        <div class="status-item"><span class="fw-600">TTS Engine</span><span class="log-badge green">Active</span></div>
                        <div class="status-item"><span class="fw-600">Audio Chime</span><span class="log-badge green">Web Audio API</span></div>
                        <div class="status-item"><span class="fw-600">Network</span><span class="log-badge blue">LAN Only</span></div>
                        <div class="status-item"><span class="fw-600">Uptime Mode</span><span class="log-badge green">24/7 Offline</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

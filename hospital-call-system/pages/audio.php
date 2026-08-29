<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-audio">
    <div class="page-head">
        <h1><i class="fas fa-sliders me-2"></i><span data-i18n="audio.title">Audio Control Panel</span></h1>
        <p data-i18n="audio.sub">Configure voice profiles, speech speed, pauses, and chime settings</p>
    </div>
    <div class="card mb-4">
        <div class="card-header"><h2><i class="fas fa-globe me-2"></i><span data-i18n="audio.global_voice">Global Voice Settings</span></h2></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="settings-range-label"><label for="sRt2" data-i18n="audio.speech_rate">Speech Rate</label><span id="sRtV2">0.62</span></div>
                    <input type="range" class="form-range" id="sRt2" min="0.4" max="1.2" step="0.02" value="0.62">
                </div>
                <div class="col-md-3">
                    <div class="settings-range-label"><label for="sPause" data-i18n="audio.pause">Pause (ms)</label><span id="sPauseV">700</span></div>
                    <input type="range" class="form-range" id="sPause" min="300" max="2000" step="50" value="700">
                </div>
                <div class="col-md-3">
                    <div class="settings-range-label"><label for="sPM2" data-i18n="set.male_pitch">Male Pitch</label><span id="sPMV2">0.78</span></div>
                    <input type="range" class="form-range" id="sPM2" min="0.4" max="1.3" step="0.05" value="0.78">
                </div>
                <div class="col-md-3">
                    <div class="settings-range-label"><label for="sPF2" data-i18n="set.female_pitch">Female Pitch</label><span id="sPFV2">1.10</span></div>
                    <input type="range" class="form-range" id="sPF2" min="0.8" max="2.0" step="0.05" value="1.10">
                </div>
            </div>
            <div class="section-divider mt-4"><span data-i18n="audio.test_audio">Test Audio</span></div>
            <div class="d-flex gap-2 flex-wrap">
                <button onclick="testChime()" class="btn-call blue" style="width:auto;padding:10px 20px;font-size:.84rem"><i class="fas fa-bell"></i> Ding-Dong Chime</button>
                <button onclick="testEmergency()" class="btn-call red" style="width:auto;padding:10px 20px;font-size:.84rem"><i class="fas fa-triangle-exclamation"></i> Emergency Beeps</button>
                <button onclick="testVoice('male')" class="btn-call blue" style="width:auto;padding:10px 20px;font-size:.84rem"><i class="fas fa-mars"></i> Test Male</button>
                <button onclick="testVoice('female')" class="btn-call purple" style="width:auto;padding:10px 20px;font-size:.84rem"><i class="fas fa-venus"></i> Test Female</button>
            </div>
            <div class="section-divider mt-4"><span data-i18n="audio.save_to_system">Save to System</span></div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button onclick="saveAudioSettings()" class="btn-call green" style="width:auto;padding:10px 24px;font-size:.86rem"><i class="fas fa-save"></i> <span data-i18n="audio.save_settings_db">Save Settings to Database</span></button>
                <span class="small text-muted" id="audioSaveStatus"></span>
            </div>
        </div>
    </div>
    <div class="section-divider"><span data-i18n="audio.voice_per_type">Voice Per Announcement Type</span></div>
    <div class="audio-profiles-grid">
        <div class="audio-profile-card">
            <div class="audio-profile-type">🚨 Emergency Code</div>
            <h4>Code Announcements</h4>
            <p class="small text-muted mb-3">Authoritative slightly-male leaning tone</p>
            <div class="voice-toggle" role="group">
                <button class="voice-btn" onclick="sv('emg','male')"><i class="fas fa-mars"></i> Male</button>
                <button class="voice-btn voice-active female" onclick="sv('emg','female')"><i class="fas fa-venus"></i> Female</button>
            </div>
        </div>
        <div class="audio-profile-card">
            <div class="audio-profile-type">👨‍⚕️ Doctor Page</div>
            <h4>Doctor Paging</h4>
            <p class="small text-muted mb-3">Professional female voice preferred</p>
            <div class="voice-toggle" role="group">
                <button class="voice-btn" onclick="sv('drp','male')"><i class="fas fa-mars"></i> Male</button>
                <button class="voice-btn voice-active female" onclick="sv('drp','female')"><i class="fas fa-venus"></i> Female</button>
            </div>
        </div>
        <div class="audio-profile-card">
            <div class="audio-profile-type">🔧 Staff Call</div>
            <h4>Staff Paging</h4>
            <p class="small text-muted mb-3">Clear male voice</p>
            <div class="voice-toggle" role="group">
                <button class="voice-btn voice-active male" onclick="sv('stp','male')"><i class="fas fa-mars"></i> Male</button>
                <button class="voice-btn" onclick="sv('stp','female')"><i class="fas fa-venus"></i> Female</button>
            </div>
        </div>
        <div class="audio-profile-card">
            <div class="audio-profile-type">📢 Custom</div>
            <h4>Custom Announcement</h4>
            <p class="small text-muted mb-3">Select voice per use</p>
            <div class="voice-toggle" role="group">
                <button class="voice-btn" onclick="sv('cst','male')"><i class="fas fa-mars"></i> Male</button>
                <button class="voice-btn voice-active female" onclick="sv('cst','female')"><i class="fas fa-venus"></i> Female</button>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header"><h2><i class="fas fa-keyboard me-2"></i><span data-i18n="audio.tts_test">Custom TTS Test</span></h2></div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="panel-label" for="ttsTest" data-i18n="audio.tts_text">Text to Speak</label>
                    <textarea class="panel-textarea" id="ttsTest" rows="3" placeholder="Type any text to test speech synthesis..."></textarea>
                </div>
                <div class="col-md-4">
                    <div class="voice-toggle mb-2" role="group">
                        <button class="voice-btn" onclick="sv('tt','male')"><i class="fas fa-mars"></i> Male</button>
                        <button class="voice-btn voice-active female" onclick="sv('tt','female')"><i class="fas fa-venus"></i> Female</button>
                    </div>
                    <button class="btn-call blue" onclick="testCustomTTS()" style="width:100%"><i class="fas fa-play"></i> <span data-i18n="audio.speak">Speak</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

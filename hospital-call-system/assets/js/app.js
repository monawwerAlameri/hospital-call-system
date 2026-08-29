// ============================================================
//  HOSPITAL CALL SYSTEM — APP ENGINE  v2.1
//  Full frontend-backend integration
// ============================================================

// Custom TTS Test function (Audio Control page)
function testCustomTTS() {
    const text = val('ttsTest');
    if (!text) { toast(LANG === 'ar' ? 'أدخل نصاً أولاً' : 'Enter text first', 'error', 'Test'); return; }
    const g = VS.tt || 'female';
    const isAr = /[\u0600-\u06FF]/.test(text);
    if (isAr) {
        Audio.announce('', g, 'custom', null, text);
    } else {
        Audio.announce(fixPronunciation(text), g, 'custom');
    }
    toast(LANG === 'ar' ? 'جاري التشغيل...' : 'Playing ' + g + ' voice…', 'info', 'TTS Test');
}


let CU = null;
let LOC = { c: 'ER', n: 'Emergency Room', ar: 'قسم الطوارئ' };
let LOGS = [];
let SBC = false;
let ST = { t: 0, e: 0, d: 0, s: 0, c: 0 };
let VS = { q: 'female', cb: 'female', st: 'male', st2: 'male', ca: 'female', dr: 'female', fc: 'female' };

// Dynamic data loaded from DB
let DB_CODES = [];
let DB_LOCS = [];
let DB_SPECS = [];
let DB_ROLES = [];
let DB_DOCTORS = [];
let DB_SCHEDULED = [];

// ===== DEMO USERS (fallback if no DB) =====
const DEMO_USERS = [
    { id: 1, name: 'System Administrator', email: 'admin@hospital.sa', password: 'Admin@1234', role: 'admin', gender: 'male', dept: 'IT Department' },
    { id: 2, name: 'Dr. Sara Al-Rashidi', email: 'sara@hospital.sa', password: 'Admin@1234', role: 'operator', gender: 'female', dept: 'Emergency Room' },
    { id: 3, name: 'Mohammed Al-Qahtani', email: 'mohammed@hospital.sa', password: 'Admin@1234', role: 'operator', gender: 'male', dept: 'Intensive Care Unit' },
];

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    tick(); setInterval(tick, 1000);
    loadAllData();
    loadSavedSession();
    initSettingsSliders();
    loadAudioSettings();
    checkScheduled();
    setInterval(checkScheduled, 60000);
    if (document.getElementById('custHist')) loadCustomHistory();
});

// ===== LOAD ALL DATA FROM API =====
async function loadAllData() {
    await Promise.all([loadCodes(), loadLocations(), loadSpecs(), loadRoles(), loadDoctors(), loadScheduled()]);
    setupDropdowns();
    renderCodeGrids();
    renderCodeRefTable();
    renderTemplateButtons();
}

async function apiFetch(url, opts = {}) {
    try {
        const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...opts });
        return await r.json();
    } catch (e) { return { success: false, error: e.message }; }
}

async function loadCodes() {
    const r = await apiFetch('api/codes.php');
    if (r.success && r.data.length) {
        DB_CODES = r.data;
    } else {
        // Fallback to static CODES defined in data.js
        DB_CODES = CODES.map(c => ({
            code_key: c.id, name: c.n, name_ar: c.ar, description: c.d,
            color: c.bg, text_color: c.cl, icon: c.ic,
            priority: c.priority, msg_en: c.msg_en, msg_ar: c.msg_ar,
            action_note: CODE_ACTIONS[c.id] || ''
        }));
    }
    renderCodeGrids();
    renderCodeRefTable();
}

async function loadLocations() {
    const r = await apiFetch('api/locations.php');
    if (r.success && r.data.length) {
        DB_LOCS = r.data;
        // Merge Arabic into LOCS global
        r.data.forEach(l => {
            const ex = LOCS.find(x => x.c === l.code);
            if (!ex) LOCS.push({ c: l.code, n: l.name, ar: l.name_ar || l.name });
            else { ex.n = l.name; ex.ar = l.name_ar || l.name; ex.id = l.id; }
        });
    } else {
        DB_LOCS = LOCS;
    }
    setupDropdowns();
    renderDeptCards();
}

async function loadSpecs() {
    const r = await apiFetch('api/specialties.php');
    if (r.success && r.data) DB_SPECS = r.data;
    else DB_SPECS = SPECS.map((s, i) => ({ id: i + 1, name: s.en, name_ar: s.ar }));
}

async function loadRoles() {
    const r = await apiFetch('api/roles.php');
    if (r.success && r.data) DB_ROLES = r.data;
    else DB_ROLES = ROLES.map((r2, i) => ({ id: i + 1, name: r2.en, name_ar: r2.ar, default_gender: r2.gender || 'any' }));
}

async function loadDoctors() {
    const r = await apiFetch('api/doctors.php');
    DB_DOCTORS = r.success ? (r.data || []) : [];
    renderDoctorCards();
}

async function loadScheduled() {
    const r = await apiFetch('api/scheduled.php');
    DB_SCHEDULED = r.success ? (r.data || []) : [];
    renderScheduledCards();
}

// ===== CLOCK =====
function tick() {
    const now = new Date();
    const el = document.getElementById('clk');
    if (el) el.textContent = now.toLocaleTimeString('en-US', { hour12: false });
    const h = now.getHours();
    const g = h < 12 ? 'Good morning' : h < 17 ? 'Good afternoon' : 'Good evening';
    const ge = document.getElementById('dgr');
    if (ge && CU) ge.textContent = `${g}, ${CU.name.split(' ')[0]} — King Khalid Hospital`;
}

// ===== AUTH =====
function showPage(p) {
    const pageMap = {
        'landing': 'index.php',
        'login': 'login.php',
        'register': 'register.php',
        'dashboard': 'dashboard.php'
    };
    if (pageMap[p]) {
        window.location.href = pageMap[p];
    }
}

async function doLogin() {
    const em = document.getElementById('lEmail')?.value.trim();
    const pw = document.getElementById('lPass')?.value;
    if (!em || !pw) { toast('Fill all fields', 'error', 'Login'); return; }

    // Try API first
    const r = await apiFetch('api/auth.php', {
        method: 'POST', body: JSON.stringify({ action: 'login', email: em, password: pw })
    });
    let user = null;
    if (r.success && r.user) {
        user = r.user;
    } else {
        // Fallback demo
        user = DEMO_USERS.find(u => u.email === em && u.password === pw);
        if (user) user = { ...user, dept: user.dept };
    }
    if (!user) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Login Failed', text: 'Invalid email or password. Please try again.', icon: 'error', confirmButtonColor: '#1F6F8B' });
        } else { toast('Invalid credentials', 'error', 'Login Failed'); }
        return;
    }

    CU = user;
    const dLoc = LOCS.find(l => l.n === (user.dept || user.department));
    if (dLoc) LOC = dLoc;
    saveSession();
    sessionStorage.setItem('hcs_tab', 'home');
    sessionStorage.setItem('hcs_welcome', '1');
    showPage('dashboard');
}

async function doRegister() {
    const n = document.getElementById('rName')?.value.trim();
    const em = document.getElementById('rEmail')?.value.trim();
    const pw = document.getElementById('rPass')?.value;
    const pwc = document.getElementById('rPassConfirm')?.value;
    const gn = document.getElementById('rGender')?.value;
    const dp = document.getElementById('rDept')?.value;
    const ph = document.getElementById('rPhone')?.value?.trim() || '';
    if (!n || !em || !pw) { toast('Fill required fields', 'error', 'Register'); return; }
    if (pw.length < 6) { toast('Password min 6 chars', 'error', 'Register'); return; }
    if (pwc && pw !== pwc) { toast('Passwords do not match', 'error', 'Register'); return; }

    const r = await apiFetch('api/auth.php', {
        method: 'POST', body: JSON.stringify({ action: 'register', name: n, email: em, password: pw, gender: gn, department: dp, phone: ph })
    });
    if (r.success) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Account Created!', text: 'Your account has been created. Please sign in.', icon: 'success', confirmButtonColor: '#1F6F8B', confirmButtonText: 'Sign In' }).then(() => { window.location.href = 'login.php'; });
        } else { toast('Account created! Sign in.', 'success', 'Registered'); setTimeout(() => { window.location.href = 'login.php'; }, 1200); }
    } else { toast(r.error || 'Registration failed', 'error', 'Error'); }
}

function doLogout() {
    CU = null;
    clearSession();
    if (typeof Audio !== 'undefined' && Audio.cancelSpeech) Audio.cancelSpeech();
    window.location.href = 'index.php';
}

function updateUIForUser() {
    if (!CU) return;
    const ini = CU.name.charAt(0).toUpperCase();
    ['sbAv', 'tbAv', 'setAv'].forEach(id => { const e = document.getElementById(id); if (e) e.textContent = ini; });
    setElText('sbName', CU.name.split(' ').slice(0, 2).join(' '));
    setElText('sbRole', (CU.role || 'operator').toUpperCase());
    setElText('setNm', CU.name); setElText('setEm', CU.email);
    setElText('setRl', CU.role || 'operator'); setElText('setDept', CU.dept || CU.department || '—');
}

// ===== SESSION =====
function saveSession() {
    try {
        sessionStorage.setItem('hcs_user', JSON.stringify(CU));
        sessionStorage.setItem('hcs_loc', JSON.stringify(LOC));
    } catch (e) { }
}
function saveCurrentTab(tab) {
    try { sessionStorage.setItem('hcs_tab', tab); } catch (e) { }
}
function loadSavedSession() {
    try {
        const u = sessionStorage.getItem('hcs_user');
        const l = sessionStorage.getItem('hcs_loc');
        const t = sessionStorage.getItem('hcs_tab') || 'home';
        const onDashboard = document.getElementById('tab-home') !== null;
        if (u) {
            CU = JSON.parse(u);
            updateUIForUser();
            if (onDashboard) {
                showTab(t);
                const welcomed = sessionStorage.getItem('hcs_welcome');
                if (welcomed) {
                    sessionStorage.removeItem('hcs_welcome');
                    setTimeout(() => toast(`Welcome, ${CU.name.split(' ')[0]}!`, 'success', 'Signed In'), 600);
                }
            } else {
                window.location.href = 'dashboard.php';
                return;
            }
        }
        if (l) { LOC = JSON.parse(l); updateLocLabels(); }
    } catch (e) { }
}
function clearSession() {
    sessionStorage.removeItem('hcs_user');
    sessionStorage.removeItem('hcs_loc');
    sessionStorage.removeItem('hcs_tab');
}

// ===== SIDEBAR =====
function toggleSB() {
    const sb = document.getElementById('SB');
    const mw = document.getElementById('MW');
    const isMobile = window.innerWidth <= 900;
    if (isMobile) {
        const isOpen = sb?.classList.contains('mobile-open');
        sb?.classList.toggle('mobile-open', !isOpen);
        const overlay = document.getElementById('sbOverlay');
        if (overlay) overlay.classList.toggle('show', !isOpen);
    } else {
        SBC = !SBC;
        sb?.classList.toggle('collapsed', SBC);
        mw?.classList.toggle('expanded', SBC);
        const ti = document.getElementById('sbTI');
        if (ti) ti.className = SBC ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
    }
}

function closeMobileSB() {
    document.getElementById('SB')?.classList.remove('mobile-open');
    document.getElementById('sbOverlay')?.classList.remove('show');
}

// ===== TABS =====
function showTab(t) {
    document.querySelectorAll('.tab-content').forEach(x => x.classList.remove('active'));
    document.querySelectorAll('.sb-nav-item').forEach(x => x.classList.remove('active'));
    document.querySelectorAll('.bnav-item').forEach(x => x.classList.remove('active'));
    document.getElementById('tab-' + t)?.classList.add('active');
    document.getElementById('nav-' + t)?.classList.add('active');
    document.querySelectorAll('.bnav-item[data-tab="' + t + '"]').forEach(x => x.classList.add('active'));
    saveCurrentTab(t);
    if (t === 'analytics') updateAnalytics();
    if (t === 'manage-doctors') renderDoctorCards();
    if (t === 'manage-depts') renderDeptCards();
    if (t === 'manage-codes') { loadCodes().then(function() { renderCustomCodeCards(); }); }
    if (t === 'scheduled') renderScheduledCards();
    if (t === 'dept-employees') { loadCurrentDeptEmployees(); }
    if (t === 'dept-schedules') { loadDepartments().then(function() { populateDeptDropdowns(); initSchedDefaults(); }); }
    if (t === 'handover') { loadDepartments().then(function() { populateDeptDropdowns(); }); loadHandoverLog(); }
    if (t === 'tvboard') { loadTVMessages(); }
    if (t === 'quiethours') { loadQuietHoursConfig(); }
    if (t === 'themes') { if (typeof renderThemeCards === 'function') renderThemeCards(); }
    if (window.innerWidth <= 900) closeMobileSB();
}

// ===== DROPDOWN SETUP =====
function setupDropdowns() {
    const specHtml = (DB_SPECS.length ? DB_SPECS : SPECS.map(s => ({ name: s.en }))).map(s => `<option value="${s.name}">${s.name}</option>`).join('');
    const locHtml = LOCS.map(l => `<option value="${l.c}">${l.n}</option>`).join('');
    const locIdHtml = (DB_LOCS.length ? DB_LOCS : LOCS.map(l => ({ id: 0, name: l.n, code: l.c }))).map(l => `<option value="${l.id}">${l.name}</option>`).join('');
    const roleHtml = (DB_ROLES.length ? DB_ROLES : ROLES.map(r => ({ name: r.en }))).map(r => `<option value="${r.name}">${r.name}</option>`).join('');
    const lvlHtml = LEVELS.map(l => `<option>${l}</option>`).join('');

    ['qSp', 'cbSp', 'drSp', 'drAddSpec', 'esSpec'].forEach(id => setInner(id, specHtml));
    ['cbFr', 'drFr', 'schLoc', 'stLc'].forEach(id => setInner(id, locHtml));
    ['drAddDept', 'esDept'].forEach(id => setInner(id, locIdHtml));
    ['cbSR', 'stRl', 'schRole'].forEach(id => setInner(id, roleHtml));
    ['qLv', 'cbLv', 'drLv', 'drAddLevel', 'esLevel'].forEach(id => setInner(id, lvlHtml));

    // Doctor/pick selects for call-doctor tab
    const drHtml = DB_DOCTORS.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
    setInner('schDoctor', '<option value="">-- None --</option>' + drHtml);
    setInner('drPick', '<option value="">-- Select by name --</option>' + drHtml);

    // Dept for register
    const rDept = document.getElementById('rDept');
    if (rDept) rDept.innerHTML = LOCS.map(l => `<option value="${l.n}">${l.n}</option>`).join('');
}

// ===== RENDER CODE GRIDS =====
function renderCodeGrids() {
    const codes = DB_CODES.length ? DB_CODES : CODES.map(c => ({ code_key: c.id, name: c.n, name_ar: c.ar, description: c.d, description_ar: c.d_ar || '', color: c.bg, text_color: c.cl, icon: c.ic, priority: c.priority }));
    ['cgH', 'cgCB', 'cgEM'].forEach(gid => {
        const g = document.getElementById(gid); if (!g) return;
        g.innerHTML = codes.map(c => `
      <button class="code-btn priority-${c.priority}"
              style="--code-bg:${c.color};--code-glow:${c.color}88;color:${c.text_color};background:${c.color}"
              onclick="activateCode('${c.code_key}')" id="cbtn-${c.code_key}">
        <div class="code-btn-icon"><i class="fas ${c.icon}"></i></div>
        <div class="code-btn-label">${LANG === 'ar' && c.name_ar ? c.name_ar : c.name}</div>
        <div class="code-btn-desc">${LANG === 'ar' && c.description_ar ? c.description_ar : (c.description || '')}</div>
        ${c.priority === 'critical' ? '<div class="code-btn-critical-dot"></div>' : ''}
      </button>`).join('');
    });
}

function renderCodeRefTable() {
    const tb = document.getElementById('codeRef'); if (!tb) return;
    const codes = DB_CODES.length ? DB_CODES : CODES.map(c => ({ code_key: c.id, name: c.n, name_ar: c.ar, description: c.d, color: c.bg, text_color: c.cl, action_note: CODE_ACTIONS[c.id] }));
    tb.innerHTML = codes.map(c => `
    <tr>
      <td><span class="code-chip" style="background:${c.color};color:${c.text_color}">${LANG === 'ar' && c.name_ar ? c.name_ar : c.name}</span></td>
      <td>${c.description || ''}</td>
      <td class="text-muted small">${c.action_note || ''}</td>
    </tr>`).join('');
}

function renderTemplateButtons() {
    const el = document.getElementById('tmplBtns'); if (!el) return;
    const labels = ['Doctor Call', 'Staff Alert', 'Visitor', 'General', 'Urgent', 'Pharmacy'];
    el.innerHTML = labels.map((t, i) => `<button class="tmpl-btn" onclick="insertTemplate(${i})">${t}</button>`).join('');
}
function insertTemplate(i) { const el = document.getElementById('fcMsg'); if (el) el.value = TMPLS[i] || ''; }

// ===== LOCATION =====
function openLoc() {
    const ll = document.getElementById('loList'); if (!ll) return;
    ll.innerHTML = LOCS.map(l => `
    <div class="loc-item ${l.c === LOC.c ? 'selected' : ''}" onclick="selectLocation('${l.c}')">
      <div class="loc-item-code">${l.c}</div>
      <div class="loc-item-names">
        <div class="loc-item-en">${l.n}</div>
        <div class="loc-item-ar">${l.ar || ''}</div>
      </div>
      ${l.c === LOC.c ? '<i class="fas fa-check loc-item-check"></i>' : ''}
    </div>`).join('');
    document.getElementById('locOv')?.classList.add('show');
}
function closeLoc() { document.getElementById('locOv')?.classList.remove('show'); }
function selectLocation(c) {
    LOC = LOCS.find(l => l.c === c) || { c, n: c, ar: c };
    updateLocLabels(); closeLoc(); saveSession();
    toast(`Location: ${LOC.n}`, 'info', 'Location Set');
}
function updateLocLabels() { ['locLabel', 'cbLoc', 'emLoc', 'hLoc'].forEach(id => setElText(id, LOC.n)); }

// ===== VOICE TOGGLE =====
const VS_MAP = { q: ['vmQ', 'vfQ'], cb: ['vmCB', 'vfCB'], st: ['vmST', 'vfST'], st2: ['vmST2', 'vfST2'], ca: ['vmCA', 'vfCA'], dr: ['vmDR', 'vfDR'], fc: ['vmFC', 'vfFC'] };
function sv(panel, gender) {
    VS[panel] = gender;
    const [mid, fid] = VS_MAP[panel] || [];
    if (mid) document.getElementById(mid)?.classList.toggle('voice-active', gender === 'male');
    if (fid) document.getElementById(fid)?.classList.toggle('voice-active', gender === 'female');
}

// ===== EMERGENCY CODE =====
function activateCode(id) {
    const c = DB_CODES.find(x => x.code_key === id) || (() => {
        const fc = CODES.find(x => x.id === id);
        return fc ? { code_key: fc.id, name: fc.n, name_ar: fc.ar, color: fc.bg, text_color: fc.cl, icon: fc.ic, priority: fc.priority, msg_en: fc.msg_en, msg_ar: fc.msg_ar } : null;
    })();
    if (!c) return;

    const msgEn = (c.msg_en || `${c.name}... ${c.name}... ${LOC.n}. All staff respond immediately.`).replace('{loc}', LOC.n);
    const msgAr = (c.msg_ar || '').replace('{loc_ar}', LOC.ar || LOC.n);

    const displayName = LANG === 'ar' && c.name_ar ? c.name_ar : c.name;
    showAnnouncement({ title: displayName, type: t('ann.emergency_code'), en: msgEn, ar: msgAr, bg: c.color, cl: c.text_color, icon: c.icon, priority: c.priority });
    Audio.announce(msgEn, 'female', 'code', null, msgAr);
    addLog('emergency_code', c.name, msgEn, LOC.n, 'female', c.color);
    ST.t++; ST.e++; updateStats(); appendEmFeed(c, LOC.n);
    toast(`${displayName} — ${LOC.n}`, 'warning', '🚨');
    // Log to backend
    logToBackend('emergency_code', c.name, LOC.n, '', '', '', msgEn, 'female');
}

function appendEmFeed(c, locName) {
    const ef = document.getElementById('emFeed'); if (!ef) return;
    ef.querySelector('.feed-empty')?.remove();
    const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    const cName = LANG === 'ar' && c.name_ar ? c.name_ar : c.name;
    ef.insertAdjacentHTML('afterbegin', `<li class="feed-item">
    <span class="feed-time">${time}</span>
    <span class="feed-dot" style="background:${c.color}"></span>
    <span class="feed-text"><strong>${cName}</strong> — ${locName}</span>
  </li>`);
}

// ===== DOCTOR CALL =====
function callDoc(src) {
    let sp, lv, ex, fr, g, drName = '';
    if (src === 'q') { sp = val('qSp'); lv = val('qLv'); ex = val('qEx') || 'unavailable'; fr = LOC.n; g = VS.q; }
    else if (src === 'cb') { sp = val('cbSp'); lv = val('cbLv'); ex = val('cbEx') || 'unavailable'; fr = LOCS.find(l => l.c === val('cbFr'))?.n || LOC.n; g = VS.cb; }
    else { sp = val('drSp'); lv = val('drLv'); ex = val('drEx') || 'unavailable'; fr = LOCS.find(l => l.c === val('drFr'))?.n || LOC.n; g = VS.dr; }

    // Check if a specific doctor was selected
    const drSel = document.getElementById('drPick')?.value;
    if (drSel) { const dr = DB_DOCTORS.find(d => d.id == drSel); if (dr) { drName = dr.name; sp = dr.specialty_name || sp; ex = dr.extension || ex; fr = dr.dept_name || fr; g = dr.gender || g; } }

    const msgEn = drName
        ? `Doctor ${drName}... ${sp} ${lv}... please contact... ${fr}... extension ${ex}.`
        : `${sp}... ${lv}... on call... please contact... ${fr}... extension ${ex}.`;
    const spAr = DB_SPECS.find(s => s.name === sp)?.name_ar || sp;
    const frAr = LOCS.find(l => l.n === fr)?.ar || fr;
    const msgAr = drName
        ? `الدكتور ${drName}... ${spAr}... يرجى التواصل مع... ${frAr}... الداخلي ${ex}.`
        : `${spAr}... المناوب... يرجى التواصل مع... ${frAr}... الداخلي ${ex}.`;

    showAnnouncement({ title: drName || `${sp} — ${lv}`, type: t('ann.doctor_page'), en: msgEn, ar: msgAr, bg: '#1549c0', cl: '#fff', icon: 'fa-user-md', priority: 'high' });
    Audio.announce(fixPronunciation(msgEn), g, 'doctor', null, msgAr);
    showSpeaking(src === 'cb' ? 'spkD' : src === 'dr' ? 'spkDR' : null);
    addLog('call_doctor', 'Doctor Page', msgEn, fr, g, '#1549c0');
    ST.t++; ST.d++; updateStats(); appendDrLog(drName || sp, lv, ex, g);
    toast(`Paging ${drName || sp}`, 'info', '📟 Doctor Page');
    logToBackend('call_doctor', 'Doctor Page', fr, sp, '', ex, msgEn, g, drName);
}

function appendDrLog(sp, lv, ex, g) {
    const tb = document.getElementById('drLog'); if (!tb) return;
    tb.querySelector('.log-empty')?.closest('tr')?.remove();
    const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    tb.insertAdjacentHTML('afterbegin', `<tr>
    <td class="log-time">${time}</td>
    <td>${sp}</td><td>${lv}</td><td class="log-ext">${ex}</td>
    <td><i class="fas fa-${g === 'male' ? 'mars' : 'venus'} gender-icon ${g}"></i> ${g}</td>
  </tr>`);
}

// ===== STAFF CALL =====
function callSt() { _callStaff('cbSR', 'cbSE', 'cbSL', 'st', 'spkS'); }
function callStFull() { _callStaff('stRl', 'stEx', 'stLc', 'st2', 'spkST2'); }
function _callStaff(roleId, extId, locId, vsKey, spkId) {
    const rl = val(roleId); const ex = val(extId) || 'unavailable';
    const lc = LOCS.find(l => l.c === val(locId))?.n || LOC.n;
    const g = VS[vsKey];
    const rlAr = DB_ROLES.find(r => r.name === rl)?.name_ar || rl;
    const lcAr = LOCS.find(l => l.n === lc)?.ar || lc;
    const msgEn = `${rl}... ${rl}... please report to... ${lc}... extension ${ex}.`;
    const msgAr = `الرجاء من ${rlAr}... ${rlAr}... التوجه إلى ${lcAr}... الداخلي ${ex}.`;
    showAnnouncement({ title: rl, type: t('ann.staff_page'), en: msgEn, ar: msgAr, bg: '#059669', cl: '#fff', icon: 'fa-id-badge', priority: 'normal' });
    Audio.announce(fixPronunciation(msgEn), g, 'staff', () => hideSpeaking(spkId), msgAr);
    showSpeaking(spkId);
    addLog('call_staff', 'Staff Call', msgEn, lc, g, '#059669');
    ST.t++; ST.s++; updateStats(); appendStLog(rl, lc, ex, g);
    toast(`Paging ${rl}`, 'info', '📢 Staff Page');
    logToBackend('call_staff', 'Staff Call', lc, '', rl, ex, msgEn, g);
}
function appendStLog(rl, lc, ex, g) {
    const tb = document.getElementById('stLog'); if (!tb) return;
    tb.querySelector('.log-empty')?.closest('tr')?.remove();
    const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    tb.insertAdjacentHTML('afterbegin', `<tr>
    <td class="log-time">${time}</td>
    <td>${rl}</td><td>${lc}</td><td class="log-ext">${ex}</td>
    <td><i class="fas fa-${g === 'male' ? 'mars' : 'venus'} gender-icon ${g}"></i> ${g}</td>
  </tr>`);
}

// ===== CUSTOM / BROADCAST =====
function bcastCA() { const msg = document.getElementById('cbCA')?.value.trim(); if (!msg) { toast('Enter message', 'error', 'Broadcast'); return; } _broadcast(msg, VS.ca, 'spkC'); }
function bcastFull() { const msg = document.getElementById('fcMsg')?.value.trim(); if (!msg) { toast('Enter announcement', 'error', 'Broadcast'); return; } _broadcast(msg, VS.fc, 'spkFC'); appendCustomHist(msg); }
function _broadcast(msg, g, spkId) {
    const isAr = /[\u0600-\u06FF]/.test(msg);
    const enText = isAr ? '' : msg;
    const arText = isAr ? msg : '';
    showAnnouncement({ title: LANG === 'ar' ? 'إعلان مخصص' : 'Custom Announcement', type: t('ann.announcement'), en: enText, ar: arText, bg: '#7c3aed', cl: '#fff', icon: 'fa-bullhorn', priority: 'normal' });
    if (isAr) {
        Audio.announce('', g, 'custom', () => hideSpeaking(spkId), msg);
    } else {
        Audio.announce(fixPronunciation(msg), g, 'custom', () => hideSpeaking(spkId));
    }
    showSpeaking(spkId);
    addLog('custom', 'Custom', msg, LOC.n, g, '#7c3aed');
    ST.t++; ST.c++; updateStats();
    toast(LANG === 'ar' ? 'تم بث الإعلان' : 'Announcement broadcast', 'success', '📣');
    logToBackend('custom', 'Custom', LOC.n, '', '', '', msg, g);
}
function appendCustomHist(msg) {
    const ch = document.getElementById('custHist'); if (!ch) return;
    ch.querySelector('.feed-empty')?.remove();
    const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    ch.insertAdjacentHTML('afterbegin', `<li class="feed-item">
    <span class="feed-time">${time}</span>
    <span class="feed-dot" style="background:#7c3aed"></span>
    <span class="feed-text small">${msg.substring(0, 120)}</span>
  </li>`);
}

// ===== BACKEND LOG =====
async function logToBackend(type, code, loc, spec, role, ext, msg, gender, doctorName = '') {
    await apiFetch('api/logs.php', {
        method: 'POST',
        body: JSON.stringify({
            call_type: type, code, location_name: loc, specialty_name: spec, staff_role_name: role,
            doctor_name: doctorName, extension: ext, announced_text: msg, voice_gender: gender,
            operator_name: CU?.name, initiated_by: CU?.id
        })
    });
}

// ===== SCHEDULED ANNOUNCEMENTS =====
function checkScheduled() {
    if (!DB_SCHEDULED.length) return;
    const now = new Date();
    DB_SCHEDULED.forEach(s => {
        if (!s.scheduled_time) return;
        const schedTime = new Date(s.scheduled_time);
        const diff = Math.abs(now - schedTime) / 1000;
        if (diff < 65 && diff > 0) { // within 1 minute window
            var isArSch = /[\u0600-\u06FF]/.test(s.message_text);
            if (isArSch) {
                Audio.announce('', s.voice_gender || 'female', 'custom', null, s.message_text);
            } else {
                Audio.announce(fixPronunciation(s.message_text), s.voice_gender || 'female', 'custom');
            }
            toast(`Scheduled: ${s.title}`, 'info', '⏰ Scheduled');
            logToBackend('scheduled', s.title, '', '', s.target_role || '', '', s.message_text, s.voice_gender || 'female');
        }
    });
}

async function saveScheduled() {
    const title = val('schTitle'); const msg = val('schMsg'); const loc = val('schLoc');
    const role = val('schRole'); const doctorId = val('schDoctor');
    const gender = document.querySelector('.sch-voice.voice-active')?.dataset.gender || 'female';
    const schedTime = document.getElementById('schTime')?.value || null;
    const repeat = val('schRepeat') || 'once';
    if (!title || !msg) { toast('Enter title and message', 'error', 'Scheduled'); return; }

    const r = await apiFetch('api/scheduled.php', {
        method: 'POST',
        body: JSON.stringify({
            title, message_text: msg, target_role: role, target_doctor_id: doctorId || 0,
            target_location_id: DB_LOCS.find(l => l.code === loc)?.id || 0,
            voice_gender: gender, scheduled_time: schedTime, repeat_type: repeat
        })
    });
    if (r.success) {
        toast('Scheduled announcement saved', 'success', 'Scheduled');
        loadScheduled();
        ['schTitle', 'schMsg'].forEach(id => { const e = document.getElementById(id); if (e) e.value = ''; });
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

function renderScheduledCards() {
    const el = document.getElementById('scheduledList'); if (!el) return;
    if (!DB_SCHEDULED.length) { el.innerHTML = `<div class="empty-state"><i class="fas fa-clock"></i><p>No scheduled announcements</p></div>`; return; }
    el.innerHTML = DB_SCHEDULED.map(s => `
    <div class="sched-card">
      <div class="sched-card-head">
        <span class="sched-badge"><i class="fas fa-clock"></i></span>
        <div class="sched-info">
          <h4>${s.title}</h4>
          <p class="sched-time">${s.scheduled_time ? new Date(s.scheduled_time).toLocaleString() : 'No time set'}</p>
        </div>
        <button class="sched-del" onclick="deleteScheduled(${s.id})"><i class="fas fa-trash"></i></button>
      </div>
      <p class="sched-msg">${(s.message_text || '').substring(0, 100)}</p>
      <div class="sched-meta">
        ${s.doctor_name ? `<span><i class="fas fa-user-md"></i> ${s.doctor_name}</span>` : ''}
        ${s.location_name ? `<span><i class="fas fa-map-marker-alt"></i> ${s.location_name}</span>` : ''}
        <span><i class="fas fa-${s.voice_gender === 'male' ? 'mars' : 'venus'}"></i> ${s.voice_gender}</span>
        <span class="repeat-badge">${s.repeat_type || 'once'}</span>
      </div>
    </div>`).join('');
}

async function deleteScheduled(id) {
    await apiFetch(`api/scheduled.php?id=${id}`, { method: 'DELETE' });
    DB_SCHEDULED = DB_SCHEDULED.filter(s => s.id !== id);
    renderScheduledCards(); toast('Deleted', 'info', 'Scheduled');
}

// Preview scheduled announcement
function previewScheduled() {
    const msg = val('schMsg'); if (!msg) { toast(LANG === 'ar' ? 'أدخل رسالة أولاً' : 'Enter message first', 'error', 'Preview'); return; }
    const isArPr = /[\u0600-\u06FF]/.test(msg);
    if (isArPr) {
        Audio.announce('', 'female', 'custom', null, msg);
    } else {
        Audio.announce(fixPronunciation(msg), 'female', 'custom');
    }
    toast(LANG === 'ar' ? 'جاري التشغيل...' : 'Playing preview…', 'info', 'Preview');
}

// ===== MANAGE DOCTORS =====
function getStaffTypeCfg() {
    return {
        doctor: { label: t('staff.doctor'), icon: 'fa-user-md', grad: '135deg,#1549c0,#3b82f6', title: 'Dr.' },
        nurse: { label: t('staff.nurse'), icon: 'fa-user-nurse', grad: '135deg,#be185d,#f43f5e', title: '' },
        technician: { label: t('staff.technician'), icon: 'fa-microscope', grad: '135deg,#059669,#34d399', title: '' },
        admin: { label: t('staff.admin'), icon: 'fa-user-tie', grad: '135deg,#7c3aed,#a78bfa', title: '' },
        paramedic: { label: t('staff.paramedic'), icon: 'fa-truck-medical', grad: '135deg,#d97706,#fbbf24', title: '' },
    };
}

function renderDoctorCards() {
    const el = document.getElementById('doctorCards'); if (!el) return;
    const flt = document.getElementById('staffTypeFilter')?.value || '';
    const src = document.getElementById('staffSearch')?.value?.toLowerCase() || '';
    let list = DB_DOCTORS;
    if (flt) list = list.filter(d => (d.staff_type || 'doctor') === flt);
    if (src) list = list.filter(d => d.name.toLowerCase().includes(src) || (d.specialty_name || '').toLowerCase().includes(src) || (d.dept_name || '').toLowerCase().includes(src));
    if (!list.length) { el.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-users"></i><p>${t('staff.no_found')}</p></div>`; return; }
    const STC = getStaffTypeCfg();
    el.innerHTML = list.map(d => {
        const cfg = STC[d.staff_type || 'doctor'] || STC.doctor;
        const avatarGrad = d.gender === 'female' && d.staff_type !== 'doctor'
            ? '135deg,#c026d3,#e879f9' : cfg.grad;
        return `
    <div class="doctor-card" id="dc-${d.id}">
      <div class="doctor-card-head">
        <div class="doctor-avatar" style="background:linear-gradient(${avatarGrad})">
          <i class="fas ${cfg.icon}"></i>
        </div>
        <div class="doctor-info">
          <h4>${cfg.title ? cfg.title + ' ' : ''}${d.name}</h4>
          <p>${d.specialty_name || d.staff_type || t('staff.staff')}</p>
          <span class="staff-type-chip ${d.staff_type || 'doctor'}">${cfg.label}</span>
        </div>
        <div class="doctor-actions">
          <button class="icon-btn blue" onclick="viewStaff(${d.id})" title="View details"><i class="fas fa-eye"></i></button>
          <button class="icon-btn green" onclick="callDoctorByName(${d.id})" title="Page"><i class="fas fa-phone"></i></button>
          <button class="icon-btn purple" onclick="openEditStaff(${d.id})" title="Edit"><i class="fas fa-pen"></i></button>
          <button class="icon-btn red" onclick="deleteDoctor(${d.id})" title="Delete"><i class="fas fa-trash"></i></button>
        </div>
      </div>
      <div class="doctor-meta">
        <span><i class="fas fa-${d.gender === 'female' ? 'venus text-pink' : 'mars text-blue'}"></i> ${d.level || t('staff.staff')}</span>
        ${d.dept_name ? `<span><i class="fas fa-hospital"></i> ${d.dept_name}</span>` : ''}
        ${d.extension ? `<span><i class="fas fa-phone-alt"></i> Ext. ${d.extension}</span>` : ''}
        ${d.phone ? `<span><i class="fas fa-mobile-alt"></i> ${d.phone}</span>` : ''}
      </div>
    </div>`;
    }).join('');
}

function viewStaff(id) {
    const d = DB_DOCTORS.find(x => x.id == id); if (!d) return;
    const STC = getStaffTypeCfg();
    const cfg = STC[d.staff_type || 'doctor'] || STC.doctor;
    const info = [
        [t('view.staff_type'), cfg.label],
        [t('view.specialty_role'), d.specialty_name || '—'],
        [t('view.level_grade'), d.level || '—'],
        [t('view.department'), d.dept_name || '—'],
        [t('view.gender'), d.gender],
        [t('view.extension'), d.extension || '—'],
        [t('view.phone'), d.phone || '—'],
    ];
    const el = document.getElementById('vsBody');
    if (el) el.innerHTML = info.map(([k, v]) => `<div class="vs-row"><span class="vs-key">${k}</span><span class="vs-val">${v}</span></div>`).join('');
    const title = document.getElementById('vsTitle');
    if (title) title.textContent = `${cfg.title ? cfg.title + ' ' : ''}${d.name}`;
    openModal('viewStaffModal');
}

function openEditStaff(id) {
    const d = DB_DOCTORS.find(x => x.id == id); if (!d) return;
    const setV = (id, val) => { const e = document.getElementById(id); if (e) e.value = val || ''; };
    setV('esId', d.id);
    setV('esName', d.name);
    setV('esNameAr', d.name_ar);
    setV('esType', d.staff_type || 'doctor');
    setV('esGender', d.gender);
    setV('esPhone', d.phone);
    setV('esExt', d.extension);
    // Dropdowns — populated? Set after slight delay to ensure options exist
    setTimeout(() => {
        setV('esSpec', d.specialty_name || '');
        setV('esLevel', d.level || 'Consultant');
        // Set dept by id
        const deptEl = document.getElementById('esDept');
        if (deptEl && d.department_id) deptEl.value = d.department_id;
    }, 50);
    openModal('editStaffModal');
}

async function saveEditStaff() {
    const id = document.getElementById('esId')?.value; if (!id) return;
    const name = val('esName');
    if (!name) { toast('Name required', 'error', 'Edit'); return; }
    const specName = val('esSpec');
    const specObj = DB_SPECS.find(s => s.name === specName) || { id: null };
    const deptId = val('esDept') || null;
    const r = await apiFetch(`api/doctors.php?id=${id}`, {
        method: 'PUT',
        body: JSON.stringify({
            name, name_ar: val('esNameAr'), level: val('esLevel'),
            gender: val('esGender'), staff_type: val('esType'),
            phone: val('esPhone'), extension: val('esExt'),
            specialty_id: specObj.id, department_id: deptId
        })
    });
    if (r.success) {
        toast('Staff updated', 'success', 'Updated');
        closeModal('editStaffModal');
        loadDoctors();
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

function callDoctorByName(id) {
    const dr = DB_DOCTORS.find(d => d.id == id); if (!dr) return;
    const loc = dr.dept_name || LOC.n;
    const msgEn = `Doctor ${dr.name}... ${dr.specialty_name || ''}... ${dr.level || 'Consultant'}... please contact... ${loc}... extension ${dr.extension || 'unavailable'}.`;
    const msgAr = `الدكتور ${dr.name_ar || dr.name}... ${dr.specialty_name_ar || dr.specialty_name || ''}... يرجى التواصل مع... ${dr.dept_name_ar || loc}... الداخلي ${dr.extension || ''}.`;
    showAnnouncement({ title: `Dr. ${dr.name}`, type: 'DOCTOR PAGE', en: msgEn, ar: msgAr, bg: '#1549c0', cl: '#fff', icon: 'fa-user-md', priority: 'high' });
    Audio.announce(fixPronunciation(msgEn), dr.gender || 'male', 'doctor', null, msgAr);
    addLog('call_doctor', `Dr. ${dr.name}`, msgEn, loc, dr.gender || 'male', '#1549c0');
    ST.t++; ST.d++; updateStats();
    toast(`Paging Dr. ${dr.name}`, 'info', '📟 Doctor Page');
    logToBackend('call_doctor', `Dr. ${dr.name}`, loc, dr.specialty_name || '', '', dr.extension || '', msgEn, dr.gender || 'male', dr.name);
}

async function addDoctor() {
    const name = val('drAddName');
    const spec = val('drAddSpec');
    const level = val('drAddLevel');
    const gender = val('drAddGender');
    const type = val('drAddType') || 'doctor';
    const ext = val('drAddExt');
    const deptId = val('drAddDept');
    const phone = val('drAddPhone');
    const nameAr = val('drAddNameAr');
    if (!name) { toast('Enter staff name', 'error', 'Add Staff'); return; }
    const specObj = DB_SPECS.find(s => s.name === spec) || { id: null };
    const r = await apiFetch('api/doctors.php', {
        method: 'POST',
        body: JSON.stringify({ name, name_ar: nameAr, specialty_id: specObj.id, level, gender, staff_type: type, extension: ext, department_id: deptId || null, phone })
    });
    if (r.success) {
        toast('Staff added successfully', 'success', 'Staff');
        loadDoctors();
        ['drAddName', 'drAddNameAr', 'drAddExt', 'drAddPhone'].forEach(id => { const e = document.getElementById(id); if (e) e.value = ''; });
        closeModal('drAddModal');
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

async function deleteDoctor(id) {
    const confirmed = typeof Swal !== 'undefined'
        ? await Swal.fire({ title: t('confirm.remove_staff'), text: t('confirm.cannot_undo'), icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b', confirmButtonText: t('confirm.yes_remove'), cancelButtonText: t('confirm.cancel') }).then(r => r.isConfirmed)
        : confirm(t('confirm.remove_staff'));
    if (!confirmed) return;
    await apiFetch(`api/doctors.php?id=${id}`, { method: 'DELETE' });
    DB_DOCTORS = DB_DOCTORS.filter(d => d.id !== id); renderDoctorCards();
    toast('Staff member removed', 'info', 'Staff');
}

// ===== MANAGE DEPARTMENTS =====
function renderDeptCards() {
    const el = document.getElementById('deptCards'); if (!el) return;
    const search = (document.getElementById('deptSearch')?.value || '').toLowerCase();
    let locs = DB_LOCS.length ? DB_LOCS : LOCS.map(l => ({ id: 0, name: l.n, name_ar: l.ar, code: l.c, category: 'medical', floor: '' }));
    if (search) locs = locs.filter(l => l.name.toLowerCase().includes(search) || (l.code || '').toLowerCase().includes(search) || (l.name_ar || '').includes(search));
    if (!locs.length) { el.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-building"></i><p>${t('dept.no_found')}</p></div>`; return; }
    el.innerHTML = locs.map(l => `
    <div class="dept-card">
      <div class="dept-card-code">${l.code}</div>
      <div class="dept-card-info">
        <h4>${l.name}</h4>
        ${l.name_ar ? `<p class="ar" style="font-size:.78rem;color:var(--text-muted)">${l.name_ar}</p>` : ''}
        <div class="dept-meta">
          ${l.floor ? `<span><i class="fas fa-layer-group"></i> ${l.floor}</span>` : ''}
          <span class="dept-cat ${l.category}">${l.category || 'medical'}</span>
        </div>
      </div>
      <div class="dept-card-actions">
        ${l.id ? `<button class="icon-btn dept-btn-emp sml" onclick="showDeptEmployeesPage(${l.id})" title="${t('demp.btn_employees')}"><i class="fas fa-users"></i></button>` : ''}
        ${l.id ? `<button class="icon-btn dept-btn-edit sml" onclick="openEditDept(${l.id})" title="${t('misc.edit')}"><i class="fas fa-pen"></i></button>` : ''}
        ${l.id ? `<button class="icon-btn dept-btn-del sml" onclick="deleteDept(${l.id})" title="${t('misc.delete')}"><i class="fas fa-trash"></i></button>` : ''}
      </div>
    </div>`).join('');
}

async function addDept() {
    const name = val('deptAddName'); const nameAr = val('deptAddNameAr'); const code = val('deptAddCode').toUpperCase();
    const floor = val('deptAddFloor'); const cat = val('deptAddCat'); const ext = val('deptAddExt');
    if (!name || !code) { toast('Name and code required', 'error', 'Add Dept'); return; }
    const r = await apiFetch('api/locations.php', { method: 'POST', body: JSON.stringify({ name, name_ar: nameAr, code, category: cat, floor, extension: ext }) });
    if (r.success) {
        toast('Department added', 'success', 'Departments');
        LOCS.push({ c: code, n: name, ar: nameAr || name });
        loadLocations(); renderDeptCards();
        document.getElementById('deptAddModal')?.classList.remove('show');
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

async function deleteDept(id) {
    const confirmed = typeof Swal !== 'undefined'
        ? await Swal.fire({ title: t('confirm.remove_dept'), text: t('confirm.cannot_undo'), icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b', confirmButtonText: t('confirm.yes_remove'), cancelButtonText: t('confirm.cancel') }).then(r => r.isConfirmed)
        : confirm(t('confirm.remove_dept'));
    if (!confirmed) return;
    await apiFetch(`api/locations.php?id=${id}`, { method: 'DELETE' });
    loadLocations(); renderDeptCards(); toast('Removed', 'info', 'Departments');
}

// ===== MANAGE CUSTOM CODES =====
function renderCustomCodeCards() {
    const el = document.getElementById('customCodeCards'); if (!el) return;
    const customs = DB_CODES.filter(c => !c.is_builtin || c.is_builtin == 0);
    if (!customs.length) { el.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-plus-circle"></i><p>${t('dept.no_custom_codes')}</p></div>`; return; }
    el.innerHTML = customs.map(c => {
        const clr = c.color || '#1F2A6D';
        const txtClr = c.text_color || '#ffffff';
        return `<div class="ccode-card" style="--ccode-color:${clr}">
            <span style="position:absolute;top:0;left:0;width:4px;height:100%;background:${clr};border-radius:4px 0 0 4px;"></span>
            <span class="ccode-icon" style="background:${clr};color:${txtClr}"><i class="fas ${c.icon || 'fa-exclamation-triangle'}"></i></span>
            <div class="ccode-head">
                <h4>${esc(c.name)}</h4>
                ${c.description ? `<span class="ccode-msg">${esc(c.description)}</span>` : ''}
                <div class="ccode-meta">
                    <span class="priority-pill ${c.priority}">${c.priority}</span>
                    <button class="icon-btn blue sml" onclick="activateCode('${c.code_key}')" title="${t('misc.activate')}"><i class="fas fa-broadcast-tower"></i></button>
                    <button class="icon-btn red sml" onclick="deleteCode('${c.code_key}',${c.id || 0})" title="${t('misc.delete')}"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>`;
    }).join('');
}

async function addCustomCode() {
    const name = val('ccName'); const desc = val('ccDesc'); const color = document.getElementById('ccColor')?.value || '#1549c0';
    const textColor = document.getElementById('ccTextColor')?.value || '#ffffff';
    const icon = val('ccIcon') || 'fa-exclamation-triangle'; const priority = val('ccPriority') || 'high';
    const msgEn = val('ccMsgEn'); const msgAr = val('ccMsgAr'); const action = val('ccAction');
    if (!name) { toast('Enter code name', 'error', 'Add Code'); return; }
    const key = 'CUSTOM_' + name.toUpperCase().replace(/[^A-Z0-9]/g, '_');
    const r = await apiFetch('api/codes.php', {
        method: 'POST', body: JSON.stringify({
            code_key: key, name, description: desc, color, text_color: textColor,
            icon: 'fa-' + icon.replace('fa-', ''), priority, msg_en: msgEn || `${name}... ${name}... {loc}. All staff please respond.`,
            msg_ar: msgAr, action_note: action, sort_order: 99
        })
    });
    if (r.success) {
        toast('Custom code added', 'success', 'Codes');
        document.getElementById('ccAddModal')?.classList.remove('show');
        await loadCodes();
        renderCustomCodeCards();
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

async function deleteCode(key, id) {
    const confirmed = typeof Swal !== 'undefined'
        ? await Swal.fire({ title: t('confirm.delete_code'), text: t('confirm.code_permanent'), icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b', confirmButtonText: t('confirm.yes_delete'), cancelButtonText: t('confirm.cancel') }).then(r => r.isConfirmed)
        : confirm(t('confirm.delete_code'));
    if (!confirmed) return;
    await apiFetch(`api/codes.php?id=${id}`, { method: 'DELETE' });
    DB_CODES = DB_CODES.filter(c => c.code_key !== key);
    renderCodeGrids(); renderCustomCodeCards(); toast('Deleted', 'info', 'Codes');
}

// ===== ANNOUNCEMENT MODAL =====
function showAnnouncement({ title, type, en, ar, bg, cl, icon, priority }) {
    setElText('annTitle', title); setElText('annType', type || 'ANNOUNCEMENT');
    setElText('annTextEn', en); setElText('annTextAr', ar || '');
    const wrap = document.getElementById('annIconWrap');
    if (wrap) { wrap.style.background = bg; wrap.style.color = cl; wrap.style.boxShadow = `0 0 0 16px ${bg}33,0 0 0 32px ${bg}18`; }
    const icon_el = document.getElementById('annIcon'); if (icon_el) icon_el.className = `fas ${icon}`;
    const modal = document.getElementById('annModal'); if (modal) modal.className = `ann-modal priority-${priority || 'normal'}`;
    const prog = document.getElementById('annProgress');
    if (prog) { prog.style.animation = 'none'; void prog.offsetHeight; prog.style.animation = 'ann-prog 8s linear forwards'; prog.style.background = bg; }
    document.getElementById('annOv')?.classList.add('show');
    setTimeout(() => document.getElementById('annOv')?.classList.remove('show'), 9000);
}
function closeAnn() { document.getElementById('annOv')?.classList.remove('show'); Audio.cancelSpeech(); }

// ===== SPEAKING INDICATORS =====
function showSpeaking(id) { if (id) document.getElementById(id)?.classList.add('active'); }
function hideSpeaking(id) { if (id) document.getElementById(id)?.classList.remove('active'); else document.querySelectorAll('.speaking-indicator').forEach(e => e.classList.remove('active')); }

// ===== LOGS =====
function addLog(type, code, msg, loc, g, col) {
    const now = new Date();
    LOGS.unshift({ id: LOGS.length + 1, type, code, msg, loc, g, col, time: now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false }), ts: now });
    updateRecentCalls(); updateCallFeed(); updateAllLogs();
}

function updateRecentCalls() {
    const el = document.getElementById('rcList'); if (!el) return;
    if (!LOGS.length) { el.innerHTML = `<li class="feed-empty"><i class="fas fa-satellite-dish"></i><span>${t('misc.no_calls_yet')}</span></li>`; return; }
    el.innerHTML = LOGS.slice(0, 10).map(l => `<li class="feed-item">
    <span class="feed-time">${l.time}</span>
    <span class="feed-dot" style="background:${l.col}"></span>
    <span class="feed-text">${l.code} — ${l.loc}</span>
    <span class="feed-badge" style="background:${l.col}22;color:${l.col}">${t('log.' + l.type) || l.type.replace('_', ' ')}</span>
  </li>`).join('');
}

function updateCallFeed() {
    const el = document.getElementById('cbFeed'); if (!el) return;
    if (!LOGS.length) { el.innerHTML = `<li class="feed-empty"><span>${t('misc.waiting_ann')}</span></li>`; return; }
    el.innerHTML = LOGS.slice(0, 20).map(l => `<li class="feed-item">
    <span class="feed-time">${l.time}</span>
    <span class="feed-dot" style="background:${l.col}"></span>
    <span class="feed-text small">${l.msg.substring(0, 130)}</span>
    <span class="feed-badge" style="background:${l.col}22;color:${l.col}"><i class="fas fa-${l.g === 'male' ? 'mars' : 'venus'}"></i></span>
  </li>`).join('');
}

function updateAllLogs() {
    const tb = document.getElementById('allLogs'); if (!tb) return;
    if (!LOGS.length) { tb.innerHTML = `<tr><td colspan="7" class="log-empty">${t('misc.no_logs_yet')}</td></tr>`; return; }
    const TC = { 'emergency_code': '#e03131', 'call_doctor': '#1549c0', 'call_staff': '#059669', 'custom': '#7c3aed' };
    tb.innerHTML = LOGS.map(l => `<tr>
    <td data-label="#" class="log-id">#${l.id}</td><td data-label="Time" class="log-time">${l.time}</td>
    <td data-label="Type"><span class="log-badge" style="background:${(TC[l.type] || '#718096')}22;color:${TC[l.type] || '#718096'}">${l.code}</span></td>
    <td data-label="Announcement" class="log-msg">${l.msg.substring(0, 90)}</td><td data-label="Location">${l.loc}</td>
    <td data-label="Voice"><i class="fas fa-${l.g === 'male' ? 'mars' : 'venus'} gender-icon ${l.g}"></i> ${l.g}</td>
    <td data-label="Status"><span class="log-badge green-badge">${t('misc.sent')}</span></td>
  </tr>`).join('');
}

function clearLogs() {
    LOGS = []; ST = { t: 0, e: 0, d: 0, s: 0, c: 0 }; updateStats();
    updateRecentCalls(); updateCallFeed(); updateAllLogs();
    toast('Logs cleared', 'info', 'Logs');
}

// ===== STATS =====
function updateStats() { setElText('stT', ST.t); setElText('stE', ST.e); setElText('stD', ST.d); setElText('stS', ST.s); }

// ===== ANALYTICS =====
function updateAnalytics() {
    const sg = document.getElementById('aSg'); if (sg) sg.innerHTML = [
        { lbl: t('an.total_calls'), val: ST.t, icon: 'fa-broadcast-tower', cls: 'blue' },
        { lbl: t('an.emergency_codes'), val: ST.e, icon: 'fa-exclamation-circle', cls: 'red' },
        { lbl: t('an.doctor_pages'), val: ST.d, icon: 'fa-user-md', cls: 'green' },
        { lbl: t('an.staff_calls'), val: ST.s, icon: 'fa-users', cls: 'purple' },
    ].map(x => `<div class="stat-card"><div class="stat-icon ${x.cls}"><i class="fas ${x.icon}"></i></div>
    <div class="stat-info"><div class="stat-val">${x.val}</div><div class="stat-lbl">${x.lbl}</div></div></div>`).join('');
    const at = document.getElementById('aType');
    if (at) {
        const tot = ST.t || 1; at.innerHTML = [
            { n: t('an.emergency_codes'), v: ST.e, cl: '#e03131' }, { n: t('an.doctor_pages'), v: ST.d, cl: '#1549c0' },
            { n: t('an.staff_calls'), v: ST.s, cl: '#059669' }, { n: t('an.custom'), v: ST.c, cl: '#7c3aed' }
        ].map(x => `<div class="analytics-bar-row">
    <div class="analytics-bar-label"><span>${x.n}</span><span class="analytics-bar-count">${x.v}</span></div>
    <div class="analytics-bar-track"><div class="analytics-bar-fill" style="width:${Math.round(x.v / tot * 100)}%;background:${x.cl}"></div></div>
  </div>`).join('');
    }
    const al = document.getElementById('aLoc');
    if (al) {
        const lc = {}; LOGS.forEach(l => { lc[l.loc] = (lc[l.loc] || 0) + 1; });
        al.innerHTML = Object.keys(lc).length
            ? Object.entries(lc).sort((a, b) => b[1] - a[1]).map(([n, v]) => `<div class="analytics-loc-row"><span>${n}</span><span class="log-badge blue">${v}</span></div>`).join('')
            : `<p class="no-data">${t('an.no_data')}</p>`;
    }
}

// ===== VOICE TEST =====
function testVoice(g) {
    Audio.dingDong(() => {
        Audio.speak('Testing ' + g + ' voice. King Khalid Hospital, Hail. All departments, system operational. Extension two six seven five.', g, false, () => {
            Audio.dingDong(() => {
                Audio.speak('اختبار الصوت. مستشفى الملك خالد بحائل. جميع الأقسام، النظام يعمل بشكل طبيعي.', g, false, null, 'ar-SA');
            });
        }, 'en-US');
    });
    toast(LANG === 'ar' ? 'جاري اختبار الصوت...' : 'Playing ' + g + ' voice…', 'info', '🔊');
}
function testChime() { Audio.dingDong(() => { }); toast(LANG === 'ar' ? 'جاري تشغيل النغمة...' : 'Playing chimes…', 'info', 'Chime Test'); }
function testEmergency() { Audio.emergencyAlert(() => Audio.dingDong(() => { })); toast(LANG === 'ar' ? 'تنبيه طوارئ...' : 'Emergency alert…', 'warning', 'Alert Test'); }

// ===== SETTINGS SLIDERS (with Audio Control Panel sync) =====
function initSettingsSliders() {
    // Primary sliders (Settings page)  ↔  Secondary sliders (Audio Control Panel)
    const pairs = [
        { p: 'sRt',   pv: 'sRtV',   s: 'sRt2',  sv2: 'sRtV2'  },
        { p: 'sPM',   pv: 'sPMV',   s: 'sPM2',  sv2: 'sPMV2'  },
        { p: 'sPF',   pv: 'sPFV',   s: 'sPF2',  sv2: 'sPFV2'  },
    ];
    pairs.forEach(({ p, pv, s, sv2 }) => {
        const primary   = document.getElementById(p);
        const primaryV  = document.getElementById(pv);
        const secondary = document.getElementById(s);
        const secondaryV = document.getElementById(sv2);
        if (primary && primaryV) {
            primaryV.textContent = parseFloat(primary.value).toFixed(2);
            primary.addEventListener('input', () => {
                primaryV.textContent = parseFloat(primary.value).toFixed(2);
                if (secondary) { secondary.value = primary.value; if (secondaryV) secondaryV.textContent = parseFloat(primary.value).toFixed(2); }
            });
        }
        if (secondary && secondaryV) {
            // Sync secondary from primary value at startup
            if (primary) secondary.value = primary.value;
            secondaryV.textContent = parseFloat(secondary.value).toFixed(2);
            secondary.addEventListener('input', () => {
                secondaryV.textContent = parseFloat(secondary.value).toFixed(2);
                if (primary) { primary.value = secondary.value; if (primaryV) primaryV.textContent = parseFloat(secondary.value).toFixed(2); }
            });
        }
    });
    // Standalone slider (sPause lives only in Audio Control Panel)
    const sp = document.getElementById('sPause'); const spv = document.getElementById('sPauseV');
    if (sp && spv) { spv.textContent = sp.value; sp.addEventListener('input', () => spv.textContent = sp.value); }
}

// ===== AUDIO SETTINGS (Load / Save to DB) =====
async function loadAudioSettings() {
    const r = await apiFetch('api/settings.php');
    if (!r.success || !r.settings) return;
    const set = (id, val) => { const e = document.getElementById(id); if (e) { e.value = val; e.dispatchEvent(new Event('input')); } };
    if (r.settings.speech_rate)   set('sRt', r.settings.speech_rate.value);
    if (r.settings.pitch_male)    set('sPM', r.settings.pitch_male.value);
    if (r.settings.pitch_female)  set('sPF', r.settings.pitch_female.value);
    if (r.settings.pause_between) set('sPause', r.settings.pause_between.value);
    if (r.settings.tts_repeat)    { const e = document.getElementById('sRpt'); if (e) e.value = r.settings.tts_repeat.value; }
}

async function saveAudioSettings() {
    const g = id => parseFloat(document.getElementById(id)?.value || 0);
    const payload = {
        speech_rate:   g('sRt').toFixed(2),
        pitch_male:    g('sPM').toFixed(2),
        pitch_female:  g('sPF').toFixed(2),
        pause_between: (parseInt(document.getElementById('sPause')?.value || 600)).toString(),
        tts_repeat:    (document.getElementById('sRpt')?.value || '2')
    };
    const status = document.getElementById('audioSaveStatus');
    if (status) status.textContent = 'Saving…';
    const r = await apiFetch('api/settings.php', { method: 'POST', body: JSON.stringify(payload) });
    if (status) { status.textContent = r.success ? '✓ Saved' : '✗ Error'; setTimeout(() => { status.textContent = ''; }, 2500); }
    toast(r.success ? 'Audio settings saved' : (r.message || 'Failed'), r.success ? 'success' : 'error', 'Audio Settings');
}

// ===== CUSTOM ANNOUNCEMENT HISTORY =====
async function loadCustomHistory() {
    const r = await apiFetch('api/logs.php?type=custom&limit=20');
    if (r.success && r.data.length) renderCustomHistory(r.data);
}

function renderCustomHistory(rows) {
    const el = document.getElementById('custHist'); if (!el) return;
    el.innerHTML = rows.map(l => {
        const time = l.created_at ? new Date(l.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false }) : '--:--';
        return `<li class="feed-item">
            <span class="feed-time">${time}</span>
            <span class="feed-dot" style="background:#7c3aed"></span>
            <span class="feed-text small">${(l.announced_text || '').substring(0, 120)}</span>
        </li>`;
    }).join('');
}

// ===== EDIT DEPARTMENT =====
function openEditDept(id) {
    const loc = DB_LOCS.find(l => l.id == id); if (!loc) return;
    const setV = (eid, v) => { const e = document.getElementById(eid); if (e) e.value = v || ''; };
    setV('edId',     loc.id);
    setV('edName',   loc.name);
    setV('edNameAr', loc.name_ar);
    setV('edCat',    loc.category || 'medical');
    setV('edFloor',  loc.floor || '');
    setV('edExt',    loc.extension || '');
    openModal('editDeptModal');
}

async function saveEditDept() {
    const id = document.getElementById('edId')?.value; if (!id) return;
    const name = document.getElementById('edName')?.value.trim();
    if (!name) { toast('Department name required', 'error', 'Edit Dept'); return; }
    const r = await apiFetch(`api/locations.php?id=${id}`, {
        method: 'PUT',
        body: JSON.stringify({
            name, name_ar: document.getElementById('edNameAr')?.value || '',
            category: document.getElementById('edCat')?.value || 'medical',
            floor: document.getElementById('edFloor')?.value || '',
            extension: document.getElementById('edExt')?.value || ''
        })
    });
    if (r.success) {
        toast('Department updated', 'success', 'Departments');
        closeModal('editDeptModal');
        loadLocations();
    } else { toast(r.error || 'Failed', 'error', 'Error'); }
}

// ===== SYSTEM INIT (called by footer.php) =====
function initSystem() {
    // Called from footer after DOMContentLoaded — intentionally minimal
}

// ===== MODALS =====
function openModal(id) { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

// ===== TOAST =====
function toast(msg, type = 'info', title = '') {
    const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    const tc = document.getElementById('tc'); if (!tc) return;
    const id = 'toast-' + Date.now();
    const el = document.createElement('div'); el.id = id;
    el.className = `toast-item toast-${type}`;
    el.innerHTML = `<div class="toast-icon"><i class="fas ${icons[type] || 'fa-info-circle'}"></i></div>
    <div class="toast-body">${title ? `<div class="toast-title">${title}</div>` : ''}<div class="toast-msg">${msg}</div></div>
    <button onclick="document.getElementById('${id}')?.remove()" class="toast-close"><i class="fas fa-times"></i></button>`;
    tc.appendChild(el);
    requestAnimationFrame(() => el.classList.add('show'));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 400); }, 4200);
}

// ===== UTILS =====
function esc(s) { if (!s) return ''; var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
function setElText(id, text) { const el = document.getElementById(id); if (el) el.textContent = text; }
function setInner(id, html) { const el = document.getElementById(id); if (el) el.innerHTML = html; }
function val(id) { return document.getElementById(id)?.value || ''; }

// ============================================================
//   SMART FEATURES — SOS Wall, TV Board, Handover, Quiet Hours
// ============================================================

// ===== QUICK SOS WALL =====
var sosLogEntries = [];

function sendQuickSOS(type) {
    var colorMap = {
        blue:   { name: 'Code Blue',   name_ar: 'كود أزرق',   color: '#1549c0', icon: 'fa-heart-pulse', priority: 'critical', msg: 'Code Blue... Code Blue... {loc}. Cardiac arrest. Resuscitation team respond immediately.', msg_ar: 'كود أزرق... كود أزرق... {loc_ar}. توقف قلبي. فريق الإنعاش يستجيب فوراً.' },
        red:    { name: 'Code Red',    name_ar: 'كود أحمر',   color: '#dc2626', icon: 'fa-fire',        priority: 'critical', msg: 'Code Red... Code Red... {loc}. Fire emergency. Evacuate and call security.', msg_ar: 'كود أحمر... كود أحمر... {loc_ar}. حالة حريق طارئة. إخلاء المنطقة واستدعاء الأمن.' },
        pink:   { name: 'Code Pink',   name_ar: 'كود وردي',   color: '#be185d', icon: 'fa-baby',        priority: 'critical', msg: 'Code Pink... Code Pink... {loc}. Infant alert. Security lock down exits.', msg_ar: 'كود وردي... كود وردي... {loc_ar}. تنبيه اختطاف طفل. الأمن يغلق المخارج فوراً.' },
        black:  { name: 'Code Black',  name_ar: 'كود أسود',   color: '#1e293b', icon: 'fa-shield-halved', priority: 'critical', msg: 'Code Black... Code Black... {loc}. Bomb threat. Evacuate area immediately.', msg_ar: 'كود أسود... كود أسود... {loc_ar}. تهديد بوجود قنبلة. إخلاء المنطقة فوراً.' },
        orange: { name: 'Code Orange', name_ar: 'كود برتقالي', color: '#d97706', icon: 'fa-person-falling-burst', priority: 'high', msg: 'Code Orange... Code Orange... {loc}. Mass casualty. All available medical staff respond.', msg_ar: 'كود برتقالي... كود برتقالي... {loc_ar}. حادث جماعي. جميع الكوادر الطبية المتاحة تستجيب.' },
        yellow: { name: 'Code Yellow', name_ar: 'كود أصفر',   color: '#b45309', icon: 'fa-person-walking-arrow-loop-left', priority: 'high', msg: 'Code Yellow... Code Yellow... {loc}. Missing patient. All staff be on alert.', msg_ar: 'كود أصفر... كود أصفر... {loc_ar}. مريض مفقود. جميع الموظفين في حالة تأهب.' },
        silver: { name: 'Code Silver', name_ar: 'كود فضي',    color: '#475569', icon: 'fa-gun',         priority: 'critical', msg: 'Code Silver... Code Silver... {loc}. Armed person. Security respond immediately.', msg_ar: 'كود فضي... كود فضي... {loc_ar}. شخص مسلح. الأمن يستجيب فوراً.' },
        white:  { name: 'Code White',  name_ar: 'كود أبيض',   color: '#6b7280', icon: 'fa-snowflake',   priority: 'high',     msg: 'Code White... Code White... {loc}. Infrastructure emergency. Facilities respond.', msg_ar: 'كود أبيض... كود أبيض... {loc_ar}. حالة طوارئ في البنية التحتية. فريق الصيانة يستجيب.' }
    };
    var c = colorMap[type];
    if (!c) return;

    var msg = c.msg.replace('{loc}', LOC.n);
    var displayName = (LANG === 'ar' && c.name_ar) ? c.name_ar : c.name;

    var msgArQ = (c.msg_ar || '').replace('{loc_ar}', LOC.ar || LOC.n);
    showAnnouncement({ title: displayName, type: t('ann.emergency_code'), en: msg, ar: msgArQ, bg: c.color, cl: '#ffffff', icon: c.icon, priority: c.priority });
    Audio.announce(msg, 'female', 'code', null, msgArQ);
    addLog('emergency_code', c.name, msg, LOC.n, 'female', c.color);
    ST.t++; ST.e++; updateStats();
    toast(displayName + ' — ' + LOC.n, 'warning', '🚨');
    logToBackend('emergency_code', c.name, LOC.n, '', '', '', msg, 'female');

    var entry = { name: c.name, name_ar: c.name_ar, color: c.color, icon: c.icon, loc: LOC.n, time: new Date() };
    sosLogEntries.unshift(entry);
    renderSOSLog();
}

function renderSOSLog() {
    var el = document.getElementById('sosLog');
    if (!el) return;
    if (!sosLogEntries.length) {
        el.innerHTML = '<div class="empty-state" style="padding:2rem 0;"><i class="fas fa-shield-heart"></i><p>' + t('sos.no_events') + '</p></div>';
        return;
    }
    el.innerHTML = sosLogEntries.slice(0, 10).map(function(e) {
        var nm = (LANG === 'ar' && e.name_ar) ? e.name_ar : e.name;
        var time = e.time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
        return '<div class="feed-item" style="padding:10px 14px;background:var(--card-bg);border:1px solid var(--border-light);border-radius:var(--radius-md);display:flex;align-items:center;gap:10px;">' +
            '<div style="width:32px;height:32px;border-radius:8px;background:' + e.color + ';display:flex;align-items:center;justify-content:center;color:white;font-size:.85rem;flex-shrink:0;"><i class="fas ' + e.icon + '"></i></div>' +
            '<div style="flex:1;"><div style="font-weight:700;font-size:.88rem;">' + nm + '</div><div style="font-size:.75rem;color:var(--text-muted);">' + e.loc + '</div></div>' +
            '<div style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;">' + time + '</div>' +
        '</div>';
    }).join('');
}

// ===== TV BOARD =====
var tvBoardActive = false;
var tvBoardTimer = null;
var tvTodayCount = 0;

function sendTVBoard() {
    var msgEn = val('tvMsgEn');
    var msgAr = val('tvMsgAr');
    if (!msgEn.trim() && !msgAr.trim()) {
        toast(LANG === 'ar' ? 'أدخل رسالة أولاً' : 'Please enter a message', 'warning');
        return;
    }

    var duration = parseInt(val('tvDuration'));
    var priority = val('tvPriority');
    var display = (LANG === 'ar' && msgAr) ? msgAr : msgEn;

    var previewEl = document.getElementById('tvPreviewMsg');
    if (previewEl) {
        previewEl.textContent = display;
        previewEl.style.color = priority === 'urgent' ? '#ef4444' : priority === 'high' ? '#f59e0b' : '#ffffff';
    }

    tvTodayCount++;
    setElText('tvTodayCount', tvTodayCount);
    setElText('tvLastTime', new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false }));

    if (tvBoardTimer) clearTimeout(tvBoardTimer);
    if (duration > 0) {
        tvBoardTimer = setTimeout(function() {
            var p = document.getElementById('tvPreviewMsg');
            if (p) { p.textContent = t('tv.no_msg'); p.style.color = ''; }
        }, duration * 1000);
    }

    toast(LANG === 'ar' ? 'تم إرسال الرسالة للشاشة' : 'Message sent to TV board', 'success');
}

// ===== SHIFT HANDOVER =====
var handoverEntries = [];

function saveHandover() {
    saveHandoverToDB();
}

async function broadcastHandover() {
    var notes = val('hoNotes');
    if (!notes.trim()) {
        toast(LANG === 'ar' ? 'أدخل ملاحظات التسليم' : 'Please enter handover notes', 'warning');
        return;
    }
    await saveHandoverToDB();
    var deptId = val('hoDept');
    var deptName = 'All Departments';
    if (deptId) {
        var d = DB_DEPTS.find(function(x) { return x.id == deptId; });
        deptName = d ? d.name : 'Department';
    }
    var msg = 'Shift handover in progress... ' + deptName + '... Outgoing shift, please complete handover to incoming team.';
    var msgArHo = 'جاري تسليم الوردية... ' + deptName + '... فريق الوردية المنتهية يرجى إكمال التسليم للفريق القادم.';
    Audio.announce(msg, 'female', 'normal', null, msgArHo);
    showAnnouncement({ title: LANG === 'ar' ? 'تسليم الوردية' : 'Shift Handover', type: t('ann.announcement'), en: msg, ar: '', bg: '#4a0072', cl: '#ffffff', icon: 'fa-clipboard-list', priority: 'normal' });
    toast(LANG === 'ar' ? 'تم بث إعلان التسليم' : 'Handover broadcast sent', 'success');
}

function renderHandoverLog() {
    var el = document.getElementById('handoverLog');
    if (!el) return;
    if (!handoverEntries.length) {
        el.innerHTML = '<div class="empty-state"><i class="fas fa-clipboard"></i><p>' + t('ho.no_entries') + '</p></div>';
        return;
    }
    var priorityColors = { routine: 'var(--text-muted)', important: 'var(--warning)', critical: 'var(--danger)' };
    el.innerHTML = handoverEntries.slice(0, 20).map(function(e) {
        var time = e.time.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false });
        var badge = '<span style="padding:2px 8px;border-radius:50px;font-size:.68rem;font-weight:700;background:' + (e.priority === 'critical' ? '#fef2f2' : e.priority === 'important' ? '#fffbeb' : '#f5f0fc') + ';color:' + priorityColors[e.priority] + ';">' + e.priority.toUpperCase() + '</span>';
        return '<div class="handover-item ' + e.priority + '" style="padding-left:18px;">' +
            '<div class="handover-item-head">' +
                '<div style="font-weight:700;font-size:.88rem;">' + e.shiftFrom + ' → ' + e.shiftTo + ' · ' + e.dept + '</div>' +
                badge +
            '</div>' +
            '<div class="handover-notes">' + e.notes.replace(/\n/g,'<br>') + '</div>' +
            '<div class="handover-meta" style="margin-top:6px;">' + e.user + ' · ' + time + '</div>' +
        '</div>';
    }).join('');
}

// ===== QUIET HOURS =====
var quietEnabled = false;
var quietCheckInterval = null;

function toggleQuietHours(enabled) {
    quietEnabled = enabled;
    updateQuietStatus();
    toast(enabled ? (LANG === 'ar' ? 'ساعات الهدوء مفعّلة' : 'Quiet Hours enabled') : (LANG === 'ar' ? 'تم إلغاء تفعيل ساعات الهدوء' : 'Quiet Hours disabled'), enabled ? 'info' : 'success');
}

function saveQuietHours() {
    saveQuietHoursToDB();
}

function updateQuietStatus() {
    var iconEl = document.getElementById('quietStatusIcon');
    var labelEl = document.getElementById('quietStatusLabel');
    var descEl = document.getElementById('quietStatusDesc');
    if (!iconEl) return;

    var now = new Date();
    var startT = val('quietStart') || '22:00';
    var endT = val('quietEnd') || '06:00';
    var isActive = quietEnabled && isTimeInQuietRange(now, startT, endT);

    iconEl.style.color = isActive ? '#7b1a9a' : 'var(--text-muted)';
    if (labelEl) labelEl.textContent = t(isActive ? 'quiet.active' : 'quiet.inactive');
    if (descEl) descEl.textContent = isActive
        ? (LANG === 'ar' ? 'يُسمح فقط برموز الطوارئ الحرجة' : 'Only critical emergency codes are broadcasting')
        : (LANG === 'ar' ? 'جميع الإعلانات تعمل بشكل اعتيادي' : 'All announcements are broadcasting normally');
}

function isTimeInQuietRange(now, startStr, endStr) {
    var h = now.getHours(), m = now.getMinutes();
    var cur = h * 60 + m;
    var parts = startStr.split(':');
    var start = parseInt(parts[0]) * 60 + parseInt(parts[1] || 0);
    parts = endStr.split(':');
    var end = parseInt(parts[0]) * 60 + parseInt(parts[1] || 0);
    if (start < end) return cur >= start && cur < end;
    return cur >= start || cur < end;
}

// Load saved quiet hours settings
(function() {
    try {
        var saved = JSON.parse(localStorage.getItem('hcs_quiet') || '{}');
        if (saved.enabled) {
            quietEnabled = true;
            if (document.getElementById('quietEnabled')) document.getElementById('quietEnabled').checked = true;
            if (saved.start && document.getElementById('quietStart')) document.getElementById('quietStart').value = saved.start;
            if (saved.end && document.getElementById('quietEnd')) document.getElementById('quietEnd').value = saved.end;
        }
    } catch(e) {}
})();

// ===== DEPARTMENT MANAGEMENT API =====
let DB_DEPTS = [];

async function loadDepartments() {
    var r = await apiFetch('api/departments.php?action=list');
    if (r.success) {
        DB_DEPTS = r.data || [];
        populateDeptDropdowns();
    }
}

function populateDeptDropdowns() {
    var selectors = ['schedDeptSelect','timerDept','hoDept'];
    selectors.forEach(function(id) {
        var el = document.getElementById(id);
        if (!el) return;
        var firstOpt = el.querySelector('option:first-child');
        el.innerHTML = '';
        if (firstOpt) el.appendChild(firstOpt);
        DB_DEPTS.forEach(function(d) {
            var o = document.createElement('option');
            o.value = d.id;
            o.textContent = LANG === 'ar' && d.name_ar ? d.name_ar : d.name;
            el.appendChild(o);
        });
    });
}

// ===== GLOBAL SEARCH =====
var searchTimeout = null;
function handleGlobalSearch(q) {
    clearTimeout(searchTimeout);
    var el = document.getElementById('globalSearchResults');
    if (!el) return;
    if (!q || q.length < 2) { el.style.display = 'none'; return; }
    searchTimeout = setTimeout(async function() {
        var r = await apiFetch('api/departments.php?action=search&q=' + encodeURIComponent(q));
        if (r.success && r.data.length) {
            var colors = { doctor: '#2563eb', employee: '#7c3aed', department: '#059669' };
            var icons = { doctor: 'fa-user-md', employee: 'fa-id-badge', department: 'fa-hospital' };
            el.innerHTML = r.data.map(function(item) {
                return '<div class="search-result-item" onclick="handleSearchClick(\'' + item.type + '\',' + item.id + ')">' +
                    '<div class="search-result-icon" style="background:' + (colors[item.type]||'#666') + '"><i class="fas ' + (icons[item.type]||'fa-search') + '"></i></div>' +
                    '<div class="search-result-info">' +
                        '<div class="search-result-name">' + esc(item.name || '') + '</div>' +
                        '<div class="search-result-type">' + esc(item.type) + (item.role ? ' · ' + esc(item.role) : '') + '</div>' +
                    '</div></div>';
            }).join('');
            el.style.display = 'block';
        } else {
            el.innerHTML = '<div style="padding:14px;text-align:center;color:var(--text-muted);font-size:.82rem;">' + t('search.no_results') + '</div>';
            el.style.display = 'block';
        }
    }, 300);
}

function handleSearchClick(type, id) {
    document.getElementById('globalSearchResults').style.display = 'none';
    document.getElementById('globalSearch').value = '';
    if (type === 'doctor') showTab('manage-doctors');
    else if (type === 'department') showTab('manage-depts');
    else if (type === 'employee') showTab('dept-schedules');
}

document.addEventListener('click', function(e) {
    var wrap = document.getElementById('topbarSearchWrap');
    if (wrap && !wrap.contains(e.target)) {
        var el = document.getElementById('globalSearchResults');
        if (el) el.style.display = 'none';
    }
});

// ===== DEPARTMENT SCHEDULES =====
var currentScheduleData = {};
var currentScheduleEmps = [];

function initSchedDefaults() {
    var sel = document.getElementById('schedDeptSelect');
    if (sel && !sel.value && sel.options.length > 1) {
        sel.selectedIndex = 1;
        loadDeptSchedule();
    } else if (sel && sel.value) {
        loadDeptSchedule();
    }
}

async function loadDeptSchedule() {
    var deptId = val('schedDeptSelect');
    var month = val('schedMonth');
    var year = val('schedYear');
    if (!deptId) return;

    var dept = DB_DEPTS.find(function(d) { return d.id == deptId; });
    document.getElementById('schedInfoBar').style.display = 'block';
    document.getElementById('schedLegend').style.display = 'flex';
    document.getElementById('schedTimerSection').style.display = 'block';

    if (dept) {
        document.getElementById('schedHeadName').textContent = LANG === 'ar' && dept.head_name_ar ? dept.head_name_ar : (dept.head_name || '—');
        document.getElementById('schedEmpCount').textContent = dept.employee_count || '0';
    }

    var empR = await apiFetch('api/departments.php?action=employees&department_id=' + deptId);
    currentScheduleEmps = empR.success ? empR.data : [];
    document.getElementById('schedEmpCount').textContent = currentScheduleEmps.length;

    var schedR = await apiFetch('api/departments.php?action=get_schedule&department_id=' + deptId + '&month=' + month + '&year=' + year);
    if (schedR.success && schedR.schedule) {
        try { currentScheduleData = JSON.parse(schedR.schedule.schedule_data || '{}'); } catch(e) { currentScheduleData = {}; }
        document.getElementById('schedApprover').textContent = schedR.schedule.approved_by || '—';
    } else {
        currentScheduleData = {};
        document.getElementById('schedApprover').textContent = '—';
    }

    renderScheduleTable();
    loadActiveTimers();
}

function renderScheduleTable() {
    var wrap = document.getElementById('schedTableWrap');
    if (!wrap) return;
    var month = val('schedMonth');
    var year = parseInt(val('schedYear')) || new Date().getFullYear();
    var MONTH_NAMES_EN = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    var MONTH_NAMES_AR = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
    var DAY_NAMES_EN = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    var DAY_NAMES_AR = ['أحد','اثن','ثلا','أرب','خمي','جمع','سبت'];
    var dayNames = LANG === 'ar' ? DAY_NAMES_AR : DAY_NAMES_EN;
    var monthIdx = MONTH_NAMES_EN.indexOf(month);
    var daysInMonth = new Date(year, monthIdx + 1, 0).getDate();
    var firstDayOfWeek = new Date(year, monthIdx, 1).getDay();
    var dept = DB_DEPTS.find(function(d) { return d.id == val('schedDeptSelect'); });
    var deptName = dept ? (LANG === 'ar' && dept.name_ar ? dept.name_ar : dept.name) : '';
    var monthLabel = LANG === 'ar' ? MONTH_NAMES_AR[monthIdx] : month;

    var SHIFT_LABELS = { M: LANG==='ar'?'ص':'M', E: LANG==='ar'?'م':'E', N: LANG==='ar'?'ل':'N', O: LANG==='ar'?'إ':'O', C: LANG==='ar'?'ن':'C', V: LANG==='ar'?'ف':'V' };
    var SHIFT_FULL  = { M: LANG==='ar'?'صباحي':'Morning', E: LANG==='ar'?'مسائي':'Evening', N: LANG==='ar'?'ليلي':'Night', O: LANG==='ar'?'إجازة':'Off', C: LANG==='ar'?'نداء':'On-Call', V: LANG==='ar'?'عطلة':'Vacation' };

    if (!currentScheduleEmps.length) {
        wrap.innerHTML = '<div class="empty-state" style="padding:2rem;"><i class="fas fa-users" style="font-size:2rem;color:var(--text-muted);margin-bottom:.5rem;display:block;"></i><p>' + t('sched.no_employees') + '</p><button class="btn-add" style="margin-top:.5rem;" onclick="openDeptEmployees(' + val('schedDeptSelect') + ')"><i class="fas fa-plus"></i> ' + t('sched.manage_emp') + '</button></div>';
        return;
    }

    /* Build per-day map: day -> [{emp, shift}] */
    var dayMap = {};
    for (var d = 1; d <= daysInMonth; d++) { dayMap[d] = []; }
    currentScheduleEmps.forEach(function(emp) {
        for (var d = 1; d <= daysInMonth; d++) {
            var sh = currentScheduleData[emp.id + '_' + d] || '';
            if (sh) dayMap[d].push({ name: LANG === 'ar' && emp.name_ar ? emp.name_ar : emp.name, shift: sh, role: emp.role || '' });
        }
    });

    /* ---- Calendar HTML ---- */
    var html = '<div class="cal-wrap">';

    /* Decorative header */
    html += '<div class="cal-hero">' +
        '<div class="cal-hero-orb cal-hero-orb1"></div>' +
        '<div class="cal-hero-orb cal-hero-orb2"></div>' +
        '<div class="cal-hero-orb cal-hero-orb3"></div>' +
        '<div class="cal-hero-content">' +
          '<div class="cal-hero-year">' + year + '</div>' +
          '<div class="cal-hero-month">' + monthLabel + '</div>' +
          (deptName ? '<div class="cal-hero-dept"><i class="fas fa-hospital-alt"></i> ' + esc(deptName) + '</div>' : '') +
        '</div>' +
    '</div>';

    /* Day-of-week headers */
    html += '<div class="cal-grid">';
    dayNames.forEach(function(dn, i) {
        var isFri = i === 5;
        html += '<div class="cal-dow' + (isFri ? ' cal-dow-fri' : '') + '">' + dn + '</div>';
    });

    /* Empty leading cells */
    for (var b = 0; b < firstDayOfWeek; b++) {
        html += '<div class="cal-cell cal-cell-empty"></div>';
    }

    /* Day cells */
    for (var d = 1; d <= daysInMonth; d++) {
        var date = new Date(year, monthIdx, d);
        var dow = date.getDay();
        var isToday = (date.toDateString() === new Date().toDateString());
        var isFriday = dow === 5;
        var isSaturday = dow === 6;
        var entries = dayMap[d];
        var cellCls = 'cal-cell' + (isToday ? ' cal-cell-today' : '') + (isFriday ? ' cal-cell-fri' : '') + (isSaturday ? ' cal-cell-sat' : '');
        html += '<div class="' + cellCls + '">';
        html += '<div class="cal-date-num' + (isToday ? ' cal-date-today' : '') + '">' + d + '</div>';
        if (entries.length) {
            html += '<div class="cal-entries">';
            entries.forEach(function(e) {
                html += '<div class="cal-entry sched-cell-' + e.shift + '" title="' + esc(e.name) + ' — ' + (SHIFT_FULL[e.shift] || e.shift) + '">' +
                    '<span class="cal-entry-shift">' + (SHIFT_LABELS[e.shift] || e.shift) + '</span>' +
                    '<span class="cal-entry-name">' + esc(e.name.split(' ').slice(0,2).join(' ')) + '</span>' +
                '</div>';
            });
            html += '</div>';
        }
        html += '</div>';
    }

    html += '</div>'; /* end cal-grid */

    /* Mobile card view (week rows) */
    html += '<div class="cal-mobile-weeks">';
    var week = 1;
    for (var d = 1; d <= daysInMonth; d++) {
        var dow = new Date(year, monthIdx, d).getDay();
        if (dow === 0) { html += '<div class="cal-week-card"><div class="cal-week-label">' + (t('sched.week') || 'Week') + ' ' + week + '</div><div class="cal-week-days">'; week++; }
        var entries = dayMap[d];
        var isToday = new Date(year, monthIdx, d).toDateString() === new Date().toDateString();
        html += '<div class="cal-week-day' + (isToday ? ' cal-week-day-today' : '') + '">' +
            '<div class="cal-week-day-num">' + d + '<span>' + dayNames[new Date(year, monthIdx, d).getDay()] + '</span></div>' +
            '<div class="cal-week-day-entries">';
        entries.forEach(function(e) {
            html += '<span class="cal-entry sched-cell-' + e.shift + '">' + (SHIFT_LABELS[e.shift] || e.shift) + ' ' + esc(e.name.split(' ')[0]) + '</span>';
        });
        if (!entries.length) html += '<span class="cal-week-empty">—</span>';
        html += '</div></div>';
        if (dow === 6 || d === daysInMonth) { html += '</div></div>'; }
    }
    html += '</div>'; /* end cal-mobile-weeks */

    html += '</div>'; /* end cal-wrap */
    wrap.innerHTML = html;
}

function openScheduleEditor() {
    var deptId = val('schedDeptSelect');
    if (!deptId) { toast(t('timer.select_dept'), 'warning'); return; }

    var month = val('schedMonth');
    var year = parseInt(val('schedYear')) || new Date().getFullYear();
    var monthIdx = ['January','February','March','April','May','June','July','August','September','October','November','December'].indexOf(month);
    var daysInMonth = new Date(year, monthIdx + 1, 0).getDate();
    var dept = DB_DEPTS.find(function(d) { return d.id == deptId; });

    document.getElementById('schedTitleEn').value = (dept ? dept.name : '') + ' - ' + month + ' ' + year + ' Schedule';
    document.getElementById('schedTitleAr').value = (dept && dept.name_ar ? dept.name_ar : '') + ' - جدول ' + month + ' ' + year;

    if (!currentScheduleEmps.length) {
        toast(t('sched.no_employees_toast'), 'warning');
        return;
    }

    var html = '<table class="sched-table"><thead><tr><th>' + t('sched.employee') + '</th>';
    for (var d = 1; d <= daysInMonth; d++) {
        html += '<th>' + d + '</th>';
    }
    html += '</tr></thead><tbody>';

    currentScheduleEmps.forEach(function(emp) {
        var empName = LANG === 'ar' && emp.name_ar ? emp.name_ar : emp.name;
        html += '<tr><td style="font-size:.78rem;">' + empName + '</td>';
        for (var d = 1; d <= daysInMonth; d++) {
            var key = emp.id + '_' + d;
            var val2 = currentScheduleData[key] || '';
            var cellCls = val2 ? 'sched-cell sched-cell-' + val2 : '';
            html += '<td data-key="' + key + '" onclick="cycleShiftCell(this)" class="' + (val2 ? '' : '') + '">' +
                (val2 ? '<span class="' + cellCls + '">' + val2 + '</span>' : '') + '</td>';
        }
        html += '</tr>';
    });
    html += '</tbody></table>';

    document.getElementById('schedEditorTable').innerHTML = html;
    openModal('schedEditModal');
}

var SHIFT_CYCLE = ['M','E','N','O','C','V',''];
function cycleShiftCell(td) {
    var key = td.dataset.key;
    var cur = currentScheduleData[key] || '';
    var idx = SHIFT_CYCLE.indexOf(cur);
    var next = SHIFT_CYCLE[(idx + 1) % SHIFT_CYCLE.length];
    currentScheduleData[key] = next;
    if (next) {
        td.innerHTML = '<span class="sched-cell sched-cell-' + next + '">' + next + '</span>';
    } else {
        td.innerHTML = '';
    }
}

async function saveScheduleData() {
    var deptId = val('schedDeptSelect');
    var month = val('schedMonth');
    var year = parseInt(val('schedYear'));

    var payload = {
        department_id: parseInt(deptId),
        schedule_month: month,
        schedule_year: year,
        title: val('schedTitleEn'),
        title_ar: val('schedTitleAr'),
        shift_definitions: JSON.stringify({M:'Morning',E:'Evening',N:'Night',O:'Off',C:'On-Call',V:'Vacation'}),
        schedule_data: JSON.stringify(currentScheduleData),
        approved_by: val('schedApprovedBy'),
        approved_by_ar: '',
        approver_title: val('schedApproverTitle'),
        approver_title_ar: '',
        notes: ''
    };

    var r = await apiFetch('api/departments.php?action=save_schedule', { method: 'POST', body: JSON.stringify(payload) });
    if (r.success) {
        closeModal('schedEditModal');
        renderScheduleTable();
        toast(LANG === 'ar' ? 'تم حفظ الجدول' : 'Schedule saved successfully', 'success');
    } else {
        toast('Error saving schedule', 'error');
    }
}

function printSchedule() {
    var wrap = document.getElementById('schedTableWrap');
    if (!wrap) return;
    var dept = DB_DEPTS.find(function(d) { return d.id == val('schedDeptSelect'); });
    var deptName = dept ? dept.name : 'Department';
    var month = val('schedMonth');
    var year = val('schedYear');
    var win = window.open('', '_blank');
    win.document.write('<!DOCTYPE html><html><head><title>' + deptName + ' Schedule</title><style>body{font-family:Arial,sans-serif;padding:20px;}h1{font-size:18px;text-align:center;}table{width:100%;border-collapse:collapse;font-size:11px;}th,td{border:1px solid #ccc;padding:4px;text-align:center;}th{background:#f0f0f0;}td:first-child{text-align:left;font-weight:bold;}.sched-cell{padding:2px 4px;border-radius:3px;color:#fff;font-weight:bold;font-size:10px;}.sched-cell-M{background:#3b82f6;}.sched-cell-E{background:#f59e0b;}.sched-cell-N{background:#6366f1;}.sched-cell-O{background:#ef4444;}.sched-cell-C{background:#10b981;}.sched-cell-V{background:#ec4899;}</style></head><body>');
    win.document.write('<h1>King Khalid Hospital - ' + deptName + '</h1><h2 style="text-align:center;font-size:14px;">' + month + ' ' + year + ' Duty Schedule</h2>');
    win.document.write(wrap.innerHTML);
    win.document.write('<p style="margin-top:20px;font-size:11px;">Approved by: ' + (document.getElementById('schedApprover')?.textContent || '') + '</p>');
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}

// ===== DEPARTMENT EMPLOYEES CRUD =====
async function openDeptEmployees(deptId) {
    var dept = DB_DEPTS.find(function(d) { return d.id == deptId; });
    var titleEl = document.getElementById('deptEmpTitle');
    if (titleEl) titleEl.textContent = (dept ? dept.name : t('view.department')) + ' - ' + t('misc.employees');
    var deptIdEl = document.getElementById('deptEmpDeptId');
    if (deptIdEl) deptIdEl.value = deptId;
    var empForm = document.getElementById('deptEmpForm');
    if (empForm) empForm.style.display = 'none';
    await refreshDeptEmployees(deptId);
    openModal('deptEmpModal');
}

function toggleEmpForm() {
    var f = document.getElementById('deptEmpForm');
    if (!f) return;
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

async function refreshDeptEmployees(deptId) {
    var r = await apiFetch('api/departments.php?action=employees&department_id=' + deptId);
    var list = document.getElementById('deptEmpList');
    if (!r.success || !r.data.length) {
        list.innerHTML = '<div class="empty-state"><i class="fas fa-users"></i><p>' + t('sched.no_employees_short') + '</p></div>';
        return;
    }
    list.innerHTML = r.data.map(function(e) {
        var initials = (e.name || 'U').split(' ').map(function(w) { return w[0]; }).join('').substr(0,2).toUpperCase();
        return '<div class="dept-emp-row">' +
            '<div class="dept-emp-avatar">' + esc(initials) + '</div>' +
            '<div class="dept-emp-info">' +
                '<div class="dept-emp-name">' + esc(e.name) + (e.name_ar ? ' / ' + esc(e.name_ar) : '') + '</div>' +
                '<div class="dept-emp-role">' + esc(e.role || 'Staff') + ' · ' + esc(e.employee_id || '') + (e.phone ? ' · ' + esc(e.phone) : '') + '</div>' +
            '</div>' +
            '<button onclick="deleteEmployee(' + e.id + ',' + e.department_id + ')" class="btn-cancel" style="padding:4px 10px;font-size:.7rem;"><i class="fas fa-trash"></i></button>' +
        '</div>';
    }).join('');
}

async function saveNewEmployee() {
    var deptId = document.getElementById('deptEmpDeptId').value;
    var data = {
        department_id: parseInt(deptId),
        name: val('empName'),
        name_ar: val('empNameAr'),
        employee_id: val('empEmpId'),
        role: val('empRole') || 'Staff',
        role_ar: '',
        phone: val('empPhone'),
        extension: val('empExt'),
        email: '',
        gender: 'male'
    };
    if (!data.name) { toast('Employee name is required', 'warning'); return; }
    var r = await apiFetch('api/departments.php?action=add_employee', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        toast('Employee added', 'success');
        ['empName','empNameAr','empEmpId','empRole','empPhone','empExt'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('empAddForm').style.display = 'none';
        await refreshDeptEmployees(deptId);
    } else {
        toast('Error adding employee', 'error');
    }
}

async function deleteEmployee(empId, deptId) {
    if (!confirm('Remove this employee?')) return;
    await apiFetch('api/departments.php?action=delete_employee&id=' + empId);
    toast('Employee removed', 'success');
    refreshDeptEmployees(deptId);
}

// ===== DEPT EMPLOYEES PAGE =====
var CURRENT_DEPT_EMP_ID = 0;
var ALL_DEPT_EMPLOYEES = [];

function applyDeptEmpHeader(deptId) {
    var dept = DB_DEPTS.find(function(d) { return d.id == deptId; }) ||
               DB_LOCS.find(function(l) { return l.id == deptId; });
    var deptName = dept ? (LANG === 'ar' && dept.name_ar ? dept.name_ar : dept.name) : t('demp.title');
    var titleEl = document.getElementById('deptEmpPageTitle');
    if (titleEl) titleEl.innerHTML = '<i class="fas fa-users me-2"></i>' + esc(deptName);
    var subEl = document.getElementById('deptEmpPageSub');
    if (subEl) subEl.textContent = t('demp.sub');
    var codeEl = document.getElementById('deptEmpDeptCode');
    if (codeEl) codeEl.textContent = dept ? (dept.code || deptName) : '—';
    var headerInfo = document.getElementById('deptEmpHeaderInfo');
    if (headerInfo) headerInfo.style.display = '';
    var hidEl = document.getElementById('currentDeptEmpId');
    if (hidEl) hidEl.value = deptId;
}
function showDeptEmployeesPage(deptId) {
    CURRENT_DEPT_EMP_ID = deptId;
    try { sessionStorage.setItem('hcs_dept_emp_id', deptId); } catch(e) {}
    applyDeptEmpHeader(deptId);
    var addForm = document.getElementById('deptEmpAddFormWrap');
    if (addForm) addForm.style.display = 'none';
    showTab('dept-employees');
}

async function loadCurrentDeptEmployees() {
    if (!CURRENT_DEPT_EMP_ID) {
        var savedId = parseInt(sessionStorage.getItem('hcs_dept_emp_id') || '0');
        if (savedId) {
            CURRENT_DEPT_EMP_ID = savedId;
            if (!DB_DEPTS.length) await loadDepartments();
            applyDeptEmpHeader(savedId);
        } else { return; }
    }
    var grid = document.getElementById('deptEmpPageGrid');
    if (grid) grid.innerHTML = '<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-spinner fa-spin"></i><p>' + t('misc.loading') + '</p></div>';
    var r = await apiFetch('api/departments.php?action=employees&department_id=' + CURRENT_DEPT_EMP_ID);
    ALL_DEPT_EMPLOYEES = (r.success && r.data) ? r.data : [];
    var countEl = document.getElementById('deptEmpCountNum');
    if (countEl) countEl.textContent = ALL_DEPT_EMPLOYEES.length;
    renderDeptEmpPageGrid(ALL_DEPT_EMPLOYEES);
}

function filterDeptEmployees() {
    var search = (document.getElementById('deptEmpSearch')?.value || '').toLowerCase();
    var gender = (document.getElementById('deptEmpGenderFilter')?.value || '');
    var filtered = ALL_DEPT_EMPLOYEES.filter(function(e) {
        var matchName = !search || (e.name || '').toLowerCase().includes(search) || (e.name_ar || '').includes(search) || (e.role || '').toLowerCase().includes(search) || (e.employee_id || '').toLowerCase().includes(search);
        var matchGender = !gender || e.gender === gender;
        return matchName && matchGender;
    });
    renderDeptEmpPageGrid(filtered);
}

function renderDeptEmpPageGrid(employees) {
    var grid = document.getElementById('deptEmpPageGrid');
    if (!grid) return;
    if (!employees.length) {
        grid.innerHTML = '<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-users"></i><p>' + t('demp.no_employees') + '</p></div>';
        return;
    }
    grid.innerHTML = employees.map(function(e) {
        var initials = (e.name || 'U').split(' ').map(function(w) { return w[0] || ''; }).join('').substring(0, 2).toUpperCase();
        var genderClass = e.gender === 'female' ? 'female' : 'male';
        var genderIcon = e.gender === 'female' ? 'fa-venus' : 'fa-mars';
        return '<div class="demp-card ' + genderClass + '" id="demp-card-' + e.id + '">' +
            '<div class="demp-avatar ' + genderClass + '">' + esc(initials) + '<i class="fas ' + genderIcon + ' demp-gender-icon"></i></div>' +
            '<div class="demp-info">' +
                '<div class="demp-name">' + esc(e.name || '—') + '</div>' +
                (e.name_ar ? '<div class="demp-name-ar">' + esc(e.name_ar) + '</div>' : '') +
                '<div class="demp-role"><i class="fas fa-briefcase me-1"></i>' + esc(e.role || 'Staff') + '</div>' +
                '<div class="demp-meta">' +
                    (e.employee_id ? '<span class="demp-chip id"><i class="fas fa-id-badge"></i> ' + esc(e.employee_id) + '</span>' : '') +
                    (e.phone ? '<span class="demp-chip phone"><i class="fas fa-phone"></i> ' + esc(e.phone) + '</span>' : '') +
                    (e.extension ? '<span class="demp-chip ext"><i class="fas fa-hashtag"></i> ' + esc(e.extension) + '</span>' : '') +
                '</div>' +
                '<div id="demp-edit-' + e.id + '" style="display:none;"></div>' +
            '</div>' +
            '<div class="demp-actions">' +
                '<button class="icon-btn dept-btn-emp sml" onclick="toggleDempEdit(' + e.id + ')" title="' + t('misc.edit') + '"><i class="fas fa-pen"></i></button>' +
                '<button class="icon-btn dept-btn-del sml" onclick="deleteDeptEmp(' + e.id + ')" title="' + t('misc.delete') + '"><i class="fas fa-trash"></i></button>' +
            '</div>' +
        '</div>';
    }).join('');
}

function toggleDempEdit(empId) {
    var emp = ALL_DEPT_EMPLOYEES.find(function(e) { return e.id == empId; });
    if (!emp) return;
    var panel = document.getElementById('demp-edit-' + empId);
    if (!panel) return;
    if (panel.style.display !== 'none') { panel.style.display = 'none'; return; }
    panel.style.display = 'block';
    panel.innerHTML =
        '<div class="demp-edit-overlay">' +
            '<div class="row g-2" style="margin-top:8px;">' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.name_en') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-name-' + empId + '" value="' + esc(emp.name || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.name_ar') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" dir="rtl" id="demp-e-name-ar-' + empId + '" value="' + esc(emp.name_ar || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.role') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-role-' + empId + '" value="' + esc(emp.role || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.emp_id') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-empid-' + empId + '" value="' + esc(emp.employee_id || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.phone') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-phone-' + empId + '" value="' + esc(emp.phone || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.ext') + '</label>' +
                '<input class="panel-input" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-ext-' + empId + '" value="' + esc(emp.extension || '') + '"></div>' +
                '<div class="col-12 col-sm-6"><label class="panel-label" style="font-size:.72rem;">' + t('demp.gender') + '</label>' +
                '<select class="panel-select" style="font-size:.82rem;padding:.4rem .6rem;" id="demp-e-gender-' + empId + '">' +
                    '<option value="male"' + (emp.gender !== 'female' ? ' selected' : '') + '>' + t('demp.male') + '</option>' +
                    '<option value="female"' + (emp.gender === 'female' ? ' selected' : '') + '>' + t('demp.female') + '</option>' +
                '</select></div>' +
                '<div class="col-12 d-flex gap-2 flex-wrap">' +
                    '<button class="btn-call blue" style="font-size:.78rem;padding:.4rem .9rem;" onclick="saveDempEdit(' + empId + ')">' +
                        '<i class="fas fa-save"></i> ' + t('misc.save') + '</button>' +
                    '<button class="btn-cancel" style="font-size:.78rem;padding:.4rem .9rem;" onclick="toggleDempEdit(' + empId + ')">' + t('misc.cancel') + '</button>' +
                '</div>' +
            '</div>' +
        '</div>';
}

async function saveDempEdit(empId) {
    var data = {
        id: empId,
        name: document.getElementById('demp-e-name-' + empId)?.value || '',
        name_ar: document.getElementById('demp-e-name-ar-' + empId)?.value || '',
        role: document.getElementById('demp-e-role-' + empId)?.value || 'Staff',
        role_ar: '',
        employee_id: document.getElementById('demp-e-empid-' + empId)?.value || '',
        phone: document.getElementById('demp-e-phone-' + empId)?.value || '',
        extension: document.getElementById('demp-e-ext-' + empId)?.value || '',
        email: '',
        gender: document.getElementById('demp-e-gender-' + empId)?.value || 'male'
    };
    if (!data.name) { toast(t('demp.name_required'), 'warning'); return; }
    var r = await apiFetch('api/departments.php?action=update_employee', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        toast(t('misc.save') + ' ✓', 'success');
        var idx = ALL_DEPT_EMPLOYEES.findIndex(function(e) { return e.id == empId; });
        if (idx >= 0) Object.assign(ALL_DEPT_EMPLOYEES[idx], data);
        loadCurrentDeptEmployees();
    } else {
        toast(r.error || 'Failed to save', 'error');
    }
}

async function deleteDeptEmp(empId) {
    var confirmed = typeof Swal !== 'undefined'
        ? await Swal.fire({ title: t('confirm.remove_emp') || 'Remove employee?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b', confirmButtonText: t('confirm.yes_remove') || 'Remove', cancelButtonText: t('confirm.cancel') || 'Cancel' }).then(function(r) { return r.isConfirmed; })
        : confirm('Remove this employee?');
    if (!confirmed) return;
    await apiFetch('api/departments.php?action=delete_employee&id=' + empId);
    toast(t('demp.removed') || 'Employee removed', 'success');
    loadCurrentDeptEmployees();
}

function toggleDeptEmpAddForm() {
    var wrap = document.getElementById('deptEmpAddFormWrap');
    if (!wrap) return;
    var visible = wrap.style.display !== 'none';
    wrap.style.display = visible ? 'none' : 'block';
    if (!visible) {
        ['dempName','dempNameAr','dempEmpId','dempRole','dempPhone','dempExt'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.value = '';
        });
        wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

async function saveDeptEmpNew() {
    if (!CURRENT_DEPT_EMP_ID) { toast(t('demp.select_dept_first') || 'No department selected', 'error'); return; }
    var name = val('dempName');
    if (!name) { toast(t('demp.name_required') || 'Name is required', 'warning'); return; }
    var data = {
        department_id: parseInt(CURRENT_DEPT_EMP_ID),
        name: name,
        name_ar: val('dempNameAr'),
        employee_id: val('dempEmpId'),
        role: val('dempRole') || 'Staff',
        role_ar: '',
        phone: val('dempPhone'),
        extension: val('dempExt'),
        email: '',
        gender: val('dempGender') || 'male'
    };
    var r = await apiFetch('api/departments.php?action=add_employee', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        toast(t('demp.added') || 'Employee added', 'success');
        toggleDeptEmpAddForm();
        loadCurrentDeptEmployees();
    } else {
        toast(r.error || 'Failed to add employee', 'error');
    }
}

// ===== SHIFT TIMERS =====
var activeTimers = [];
var timerInterval = null;

async function loadActiveTimers() {
    var r = await apiFetch('api/departments.php?action=active_timers');
    if (r.success) {
        activeTimers = r.data || [];
        renderActiveTimers();
        updateTimerBadge();
    }
}

function renderActiveTimers() {
    var el = document.getElementById('activeTimersList');
    if (!el) return;
    if (!activeTimers.length) {
        el.innerHTML = '<div class="empty-state" style="padding:1.5rem 0;"><i class="fas fa-hourglass-half" style="font-size:1.5rem;color:var(--text-muted);margin-bottom:.5rem;display:block;"></i><p>' + t('sched.no_timers') + '</p></div>';
        return;
    }
    el.innerHTML = activeTimers.map(function(tm) {
        var remaining = getTimerRemaining(tm.end_time);
        var isUrgent = remaining.totalSec < 300;
        return '<div class="shift-timer-card" data-auto-announce="' + (tm.auto_announce ? '1' : '0') + '">' +
            '<div class="shift-timer-countdown' + (isUrgent ? ' urgent' : '') + '" data-end="' + tm.end_time + '">' + remaining.display + '</div>' +
            '<div class="shift-timer-info">' +
                '<div class="shift-timer-dept">' + esc(LANG === 'ar' && tm.dept_name_ar ? tm.dept_name_ar : (tm.dept_name || 'Department')) + '</div>' +
                '<div class="shift-timer-meta">' + esc(tm.employee_name || '') + ' · ' + esc(tm.shift_type || '') + (tm.operation_number ? ' · OP: ' + esc(tm.operation_number) : '') + '</div>' +
            '</div>' +
            (tm.auto_announce ? '<i class="fas fa-volume-up" style="color:var(--primary);font-size:1.1rem;" title="Auto-announce on end"></i>' : '') +
        '</div>';
    }).join('');
}

function getTimerRemaining(endTime) {
    var end = new Date(endTime.replace(' ', 'T'));
    var now = new Date();
    var diff = Math.max(0, Math.floor((end - now) / 1000));
    var hrs = Math.floor(diff / 3600);
    var mins = Math.floor((diff % 3600) / 60);
    var secs = diff % 60;
    return {
        totalSec: diff,
        display: (hrs > 0 ? hrs + ':' : '') + String(mins).padStart(2,'0') + ':' + String(secs).padStart(2,'0')
    };
}

function updateTimerBadge() {
    var btn = document.getElementById('shiftTimerBtn');
    var badge = document.getElementById('timerCountBadge');
    if (!btn || !badge) return;
    if (activeTimers.length > 0) {
        btn.style.display = '';
        badge.textContent = activeTimers.length;
    } else {
        btn.style.display = 'none';
    }
}

function updateTimerCountdowns() {
    document.querySelectorAll('.shift-timer-countdown[data-end]').forEach(function(el) {
        var r = getTimerRemaining(el.dataset.end);
        el.textContent = r.display;
        if (r.totalSec < 300) el.classList.add('urgent');
        if (r.totalSec <= 0) {
            el.textContent = t('timer.ended');
            el.style.color = '#dc2626';
            var card = el.closest('.shift-timer-card');
            if (card && !card.dataset.announced) {
                card.dataset.announced = '1';
                if (card.dataset.autoAnnounce === '1') {
                    announceShiftEnd(card);
                }
            }
        }
    });
}

function announceShiftEnd(card) {
    var dept = card.querySelector('.shift-timer-dept')?.textContent || 'Department';
    var meta = card.querySelector('.shift-timer-meta')?.textContent || '';
    var msg = 'Shift change notification... ' + dept + '... ' + meta.split('·')[0].trim() + '... Your shift has ended. Please proceed with handover.';
    var msgAr = 'إشعار تغيير الوردية... ' + dept + '... انتهت ورديتكم. يرجى البدء بعملية التسليم.';
    if (typeof Audio !== 'undefined' && Audio.announce) {
        Audio.announce(msg, 'female', 'normal', null, msgAr);
    }
    showAnnouncement({
        title: t('timer.shift_change'),
        type: t('timer.shift_timer'),
        en: msg,
        ar: '',
        bg: '#f59e0b',
        cl: '#000',
        icon: 'fa-hourglass-end',
        priority: 'normal'
    });
}

function openAddTimerModal() {
    populateDeptDropdowns();
    var now = new Date();
    now.setHours(now.getHours() + 8);
    var dtStr = now.toISOString().slice(0,16);
    document.getElementById('timerEndTime').value = dtStr;
    openModal('timerAddModal');
}

async function addShiftTimer() {
    var deptId = val('timerDept');
    if (!deptId) { toast(t('timer.select_dept'), 'warning'); return; }
    var now = new Date();
    var data = {
        department_id: parseInt(deptId),
        employee_name: val('timerEmpName'),
        employee_name_ar: '',
        shift_type: val('timerShiftType'),
        start_time: now.toISOString().slice(0,19).replace('T',' '),
        end_time: val('timerEndTime').replace('T',' '),
        auto_announce: document.getElementById('timerAutoAnn').checked ? 1 : 0,
        operation_number: val('timerOpNum')
    };
    var r = await apiFetch('api/departments.php?action=save_shift_timer', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        closeModal('timerAddModal');
        loadActiveTimers();
        toast(t('timer.added'), 'success');
    } else {
        toast(t('timer.error_adding'), 'error');
    }
}

// ===== ENHANCED HANDOVER (DB-backed) =====
async function saveHandoverToDB() {
    var deptId = val('hoDept');
    var deptName = '';
    if (deptId) {
        var d = DB_DEPTS.find(function(x) { return x.id == deptId; });
        deptName = d ? d.name : '';
    }
    var data = {
        department_id: deptId ? parseInt(deptId) : 0,
        department_name: deptName || val('hoDept'),
        shift_from: val('hoShiftFrom'),
        shift_to: val('hoShiftTo'),
        outgoing_staff: CU ? CU.name : 'System',
        incoming_staff: '',
        notes: val('hoNotes'),
        priority: val('hoPriority')
    };
    if (!data.notes.trim()) { toast(LANG === 'ar' ? 'أدخل ملاحظات التسليم' : 'Please enter handover notes', 'warning'); return false; }
    var r = await apiFetch('api/departments.php?action=save_handover', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        document.getElementById('hoNotes').value = '';
        loadHandoverLog();
        toast(LANG === 'ar' ? 'تم حفظ إدخال التسليم' : 'Handover entry saved', 'success');
        return true;
    }
    return false;
}

async function loadHandoverLog() {
    var r = await apiFetch('api/departments.php?action=handover_list');
    var el = document.getElementById('handoverLog');
    if (!el) return;
    if (!r.success || !r.data.length) {
        el.innerHTML = '<div class="empty-state"><i class="fas fa-clipboard"></i><p>' + t('ho.no_entries') + '</p></div>';
        return;
    }
    var priorityColors = { routine: 'var(--text-muted)', important: 'var(--warning)', critical: 'var(--danger)' };
    el.innerHTML = r.data.map(function(e) {
        var badge = '<span style="padding:2px 8px;border-radius:50px;font-size:.68rem;font-weight:700;background:' + (e.priority === 'critical' ? '#fef2f2' : e.priority === 'important' ? '#fffbeb' : '#f5f0fc') + ';color:' + (priorityColors[e.priority] || '#999') + ';">' + (e.priority || 'routine').toUpperCase() + '</span>';
        return '<div class="handover-item ' + (e.priority||'routine') + '" style="padding-left:18px;">' +
            '<div class="handover-item-head">' +
                '<div style="font-weight:700;font-size:.88rem;">' + esc(e.shift_from||'') + ' → ' + esc(e.shift_to||'') + ' · ' + esc(e.department_name||'') + '</div>' +
                badge +
            '</div>' +
            '<div class="handover-notes">' + esc(e.notes||'').replace(/\n/g,'<br>') + '</div>' +
            '<div class="handover-meta" style="margin-top:6px;">' + esc(e.outgoing_staff||'') + ' · ' + esc(e.created_at||'') + '</div>' +
        '</div>';
    }).join('');
}

// ===== TV BOARD (DB-backed) =====
async function sendTVBoard() {
    var msgEn = val('tvMsgEn');
    var msgAr = val('tvMsgAr');
    if (!msgEn && !msgAr) { toast('Enter a message', 'warning'); return; }
    var data = {
        message_en: msgEn,
        message_ar: msgAr,
        priority: val('tvPriority'),
        duration: parseInt(val('tvDuration')) || 60
    };
    var r = await apiFetch('api/departments.php?action=save_tv_message', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        document.getElementById('tvMsgEn').value = '';
        document.getElementById('tvMsgAr').value = '';
        loadTVMessages();
        if (msgEn || msgAr) {
            Audio.announce(msgEn, 'female', 'normal', null, msgAr);
            showAnnouncement({ title: 'TV Board', type: 'BOARD MESSAGE', en: msgEn, ar: msgAr || '', bg: '#4a0072', cl: '#ffffff', icon: 'fa-tv', priority: data.priority });
        }
        toast(LANG === 'ar' ? 'تم إرسال رسالة لوحة التلفزيون' : 'TV board message sent', 'success');
        ST.t++; updateStats();
    }
}

async function loadTVMessages() {
    var r = await apiFetch('api/departments.php?action=tv_messages');
    var previewEl = document.getElementById('tvPreviewMsg');
    var countEl = document.getElementById('tvTodayCount');
    if (r.success && r.data.length) {
        var latest = r.data[0];
        if (previewEl) previewEl.innerHTML = '<div style="margin-bottom:6px;">' + esc(latest.message_en || '') + '</div>' + (latest.message_ar ? '<div dir="rtl" style="color:var(--text-secondary);">' + esc(latest.message_ar) + '</div>' : '');
        if (countEl) countEl.textContent = r.data.length;
        var lastEl = document.getElementById('tvLastTime');
        if (lastEl) lastEl.textContent = latest.created_at || '—';
    }
}

// ===== QUIET HOURS (DB-backed) =====
async function saveQuietHoursToDB() {
    var days = [];
    document.querySelectorAll('.quietDay:checked').forEach(function(c) { days.push(c.value); });
    var data = {
        is_enabled: document.getElementById('quietEnabled')?.checked ? 1 : 0,
        start_time: val('quietStart'),
        end_time: val('quietEnd'),
        repeat_days: days.join(','),
        allowed_codes: 'blue,red,pink,black'
    };
    var r = await apiFetch('api/departments.php?action=save_quiet_hours', { method: 'POST', body: JSON.stringify(data) });
    if (r.success) {
        quietEnabled = !!data.is_enabled;
        updateQuietStatus();
        toast(LANG === 'ar' ? 'تم حفظ ساعات الهدوء' : 'Quiet hours configuration saved', 'success');
    }
}

async function loadQuietHoursConfig() {
    var r = await apiFetch('api/departments.php?action=get_quiet_hours');
    if (r.success && r.config) {
        var c = r.config;
        var el = document.getElementById('quietEnabled');
        if (el) el.checked = !!parseInt(c.is_enabled);
        var sEl = document.getElementById('quietStart');
        if (sEl) sEl.value = c.start_time || '22:00';
        var eEl = document.getElementById('quietEnd');
        if (eEl) eEl.value = c.end_time || '06:00';
        quietEnabled = !!parseInt(c.is_enabled);
        if (c.repeat_days) {
            var days = c.repeat_days.split(',');
            document.querySelectorAll('.quietDay').forEach(function(cb) {
                cb.checked = days.includes(cb.value);
            });
        }
        updateQuietStatus();
    }
}

// Timer tick for shift timers
setInterval(function() {
    updateTimerCountdowns();
}, 1000);

// Load departments on init
document.addEventListener('DOMContentLoaded', function() {
    loadDepartments();
    setTimeout(loadActiveTimers, 2000);
});

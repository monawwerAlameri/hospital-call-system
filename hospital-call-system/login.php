<?php
/**
 * Hospital Call System - Login Page
 * King Khalid Hospital, Hail
 */

define('HOSPITAL_CALL_SYSTEM', true);

$pageTitle = 'Sign In';
$pageName  = 'login';
$pageDescription = 'Sign in to the Hospital Call System — King Khalid Hospital Hail';
$bodyClass = 'auth-page';
$pageStyles = '
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body.auth-page {
  font-family: "Cairo", "Inter", sans-serif;
  background: #eaf2f8;
  margin: 0; padding: 0;
  min-height: 100vh;
  overflow-x: hidden;
}
body.auth-page main {
  padding: 0;
  min-height: 100vh;
  display: flex;
  align-items: stretch;
}

/* NAVBAR */
.auth-navbar{background:linear-gradient(90deg,var(--primary,#1F2A6D),var(--primary-light,#2E4A9E),var(--accent-emerald,#38C98A)) !important;border-bottom:1px solid rgba(255,255,255,0.1);padding:.75rem 0;position:fixed;top:0;width:100%;z-index:1000;transition:all .3s;border-radius:0 0 36px 36px;}
.auth-navbar.scrolled{background:linear-gradient(90deg,var(--primary-dark,#141c4a),var(--primary,#1F2A6D),var(--primary-light,#2E4A9E)) !important;box-shadow:0 4px 28px rgba(31,42,109,.45);}
.auth-navbar .navbar-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
.auth-navbar .navbar-brand img{width:40px;height:40px;border-radius:50%;border:2px solid rgba(255,255,255,.4);object-fit:cover;flex-shrink:0;box-shadow:0 0 12px rgba(31,169,113,.45);}
.auth-navbar .brand-text-wrap{display:flex;flex-direction:column;line-height:1.2;}
.auth-navbar .brand-title{font-weight:800;font-size:clamp(.88rem,2vw,1.05rem);color:white !important;white-space:nowrap;}
.auth-navbar .brand-sub{font-size:.68rem;color:rgba(255,255,255,.6);white-space:nowrap;}
.auth-navbar .nav-link{color:rgba(255,255,255,.85) !important;font-weight:600;font-size:.9rem;padding:.45rem 1rem !important;border-radius:50px;transition:all .22s;}
.auth-navbar .nav-link:hover{color:white !important;background:rgba(255,255,255,.14);}
.auth-navbar .btn-nav-login{background:transparent;border:2px solid rgba(255,255,255,.65);color:white !important;border-radius:50px;padding:.38rem 1.2rem !important;font-weight:700;}
.auth-navbar .btn-nav-login:hover{background:white;color:var(--primary,#1F2A6D) !important;}
.auth-navbar .btn-nav-signup{background:white;border:none;color:var(--primary,#1F2A6D) !important;border-radius:50px;padding:.38rem 1.2rem !important;font-weight:700;box-shadow:0 4px 14px rgba(0,0,0,.18);}
.auth-navbar .btn-nav-signup:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(0,0,0,.25);}
.auth-navbar .nav-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.auth-navbar .btn-nav-lang{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.3);color:white !important;border-radius:50px;padding:.35rem 1rem !important;font-weight:700;font-size:.82rem;cursor:pointer;transition:all .22s;}
.auth-navbar .btn-nav-lang:hover{background:rgba(255,255,255,.22);color:white !important;}
@media(max-width:991.98px){
  .auth-navbar{border-radius:0 0 22px 22px;}
  .auth-navbar .navbar-collapse{background:linear-gradient(135deg,var(--primary-dark,#141c4a),var(--primary,#1F2A6D));border-radius:16px;margin-top:10px;padding:1rem;border:1px solid rgba(255,255,255,.12);}
  .auth-navbar .navbar-nav{flex-direction:column;width:100%;gap:3px;}
  .auth-navbar .navbar-nav .nav-item{width:100%;}
  .auth-navbar .navbar-nav .nav-link{display:block;text-align:center;border-radius:12px;}
  .auth-navbar .nav-actions{justify-content:center;margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.12);width:100%;}
}

/* SVG BG */
.auth-svg-bg{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;}
.auth-svg-bg svg{position:absolute;}

/* PAGE WRAPPER */
.auth-page-wrap {
  min-height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 100px 1rem 100px;
  box-sizing: border-box;
  position: relative;
  z-index: 1;
}

/* SPLIT CARD */
.auth-split {
  display: flex;
  width: 100%;
  max-width: 960px;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 24px 80px rgba(31,42,109,0.18), 0 6px 24px rgba(0,0,0,0.08);
  min-height: 480px;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(16px);
}

/* LEFT PANEL */
.auth-left {
  flex: 0 0 42%;
  background: var(--primary-gradient, linear-gradient(135deg, #1F2A6D 0%, #2E4A9E 25%, #1F6F8B 50%, #1FA971 75%, #38C98A 100%));
  display: none;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 2.5rem;
  position: relative;
  overflow: hidden;
}
@media (min-width: 700px) {
  .auth-left { display: flex; }
  .auth-right { flex: 0 0 58%; }
}

.auth-bars { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
.auth-bar {
  position: absolute;
  width: 160%;
  background: rgba(255,255,255,0.055);
  transform: rotate(-32deg);
  left: -30%;
  border-radius: 4px;
}
.auth-bar:nth-child(1) { top: 12%;  height: 52px; }
.auth-bar:nth-child(2) { top: 22%;  height: 28px; background: rgba(255,255,255,0.03); }
.auth-bar:nth-child(3) { top: 48%;  height: 44px; }
.auth-bar:nth-child(4) { top: 60%;  height: 22px; background: rgba(255,255,255,0.03); }
.auth-bar:nth-child(5) { top: 75%;  height: 38px; }

.auth-logo-wrap {
  position: relative; z-index: 2; margin-bottom: 1.5rem;
  width: 120px; height: 120px; border-radius: 50%;
  background: rgba(255,255,255,0.15);
  display: flex; align-items: center; justify-content: center;
  padding: 10px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.auth-logo-wrap img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }

.auth-left-title {
  font-size: clamp(1.25rem, 2.5vw, 1.7rem);
  font-weight: 900; color: #fff; text-align: center;
  line-height: 1.25; margin-bottom: 0.35rem;
  position: relative; z-index: 2;
  letter-spacing: 1px; text-transform: uppercase;
}
.auth-left-sub {
  font-size: 0.78rem; color: rgba(255,255,255,0.7);
  text-align: center; margin-bottom: 2.5rem;
  position: relative; z-index: 2;
}

.auth-nav-btns {
  display: flex; flex-direction: column;
  gap: 0.875rem; width: 100%; max-width: 210px;
  position: relative; z-index: 2;
}
.auth-nav-btn {
  display: block; padding: 0.7rem 1.5rem;
  border: 2px solid rgba(255,255,255,0.55);
  border-radius: 99px; background: transparent;
  color: #fff; font-size: 0.88rem; font-weight: 900;
  font-family: inherit; letter-spacing: 2px;
  text-transform: uppercase; text-align: center;
  text-decoration: none; cursor: pointer;
  transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
}
.auth-nav-btn:hover { background: rgba(255,255,255,0.12); border-color: #fff; }
.auth-nav-btn.active { background: rgba(255,255,255,0.15); border-color: #fff; box-shadow: 0 4px 18px rgba(0,0,0,0.18); }

.auth-dots { display: flex; gap: 10px; margin-top: 2.75rem; position: relative; z-index: 2; }
.auth-dot { width: 13px; height: 13px; border-radius: 50%; }
.auth-dot.blue   { background: #3b82f6; }
.auth-dot.orange { background: #f59e0b; }
.auth-dot.white  { background: rgba(255,255,255,0.45); }

/* RIGHT PANEL */
.auth-right {
  flex: 1;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 2.5rem;
}
.auth-box {
  width: 100%;
  max-width: 100%;
  animation: box-enter 0.5s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes box-enter {
  from { opacity: 0; transform: translateY(20px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.auth-mobile-logo {
  display: flex; align-items: center; gap: 0.875rem; margin-bottom: 2rem;
}
@media (min-width: 700px) { .auth-mobile-logo { display: none; } }
.auth-mobile-logo-hex {
  width: 52px; height: 52px; border-radius: 50%;
  background: var(--primary-gradient, linear-gradient(135deg, #1F2A6D, #38C98A));
  display: flex; align-items: center; justify-content: center;
  overflow: hidden; padding: 4px;
}
.auth-mobile-logo-hex img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
.auth-mobile-logo-text strong { display: block; font-size: 0.95rem; font-weight: 900; color: var(--primary, #1F2A6D); }
.auth-mobile-logo-text span { font-size: 0.72rem; color: #94a3b8; }

/* Form card */
.auth-form-card {
  background: #fff;
  border-radius: 22px;
  padding: 2.25rem 2rem 2rem;
  box-shadow: 0 8px 40px rgba(31,42,109,0.1), 0 2px 8px rgba(0,0,0,0.05);
  border: 1px solid rgba(31,169,113,0.12);
}
.auth-form-title {
  font-size: 1.7rem; font-weight: 900;
  color: var(--primary, #1F2A6D); margin-bottom: 4px;
}
.auth-form-sub { font-size: 0.82rem; color: #94a3b8; margin-bottom: 1.75rem; }

.auth-field { margin-bottom: 1rem; }
.auth-label { display: block; font-size: 0; height: 0; overflow: hidden; }
.auth-input-wrap { position: relative; }
.auth-input-icon {
  position: absolute; left: 16px; top: 50%;
  transform: translateY(-50%); color: #b0a8cc;
  font-size: 0.9rem; z-index: 1; pointer-events: none;
}
html[dir="rtl"] .auth-input-icon { left: auto; right: 16px; }
.auth-input {
  width: 100%; padding: 0.875rem 1.1rem 0.875rem 2.85rem;
  border: 1.5px solid #e5e0f5; border-radius: 25px;
  font-size: 0.92rem; font-family: inherit; color: #1e1040;
  background: #f7f5fd; transition: all 0.2s; outline: none; box-sizing: border-box;
}
html[dir="rtl"] .auth-input { padding: 0.875rem 2.85rem 0.875rem 1.1rem; }
.auth-input:focus { border-color: var(--accent-cyan, #1F6F8B); background: #fff; box-shadow: 0 0 0 3px rgba(31,111,139,0.12); }
.auth-input::placeholder { color: #c4bce0; }

.auth-forgot { text-align: right; font-size: 0.78rem; margin-top: -0.5rem; margin-bottom: 0.75rem; }
.auth-forgot a { color: var(--accent-cyan, #1F6F8B); text-decoration: none; font-weight: 600; }
.auth-forgot a:hover { text-decoration: underline; }

.auth-btn {
  width: 100%; padding: 0.9rem;
  background: var(--primary-gradient, linear-gradient(135deg, #1F2A6D 0%, #2E4A9E 30%, #1F6F8B 65%, #38C98A 100%));
  border: none; border-radius: 99px; color: #fff;
  font-size: 0.95rem; font-weight: 900; font-family: inherit;
  letter-spacing: 1.5px; text-transform: uppercase;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all 0.2s; box-shadow: 0 6px 20px rgba(31,42,109,0.4); margin-top: 0.25rem;
}
.auth-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(31,169,113,0.45); }
.auth-btn:active { transform: translateY(0); }

.auth-bottom { text-align: center; font-size: 0.82rem; color: #94a3b8; margin-top: 1.25rem; }
.auth-bottom a { color: var(--accent-cyan, #1F6F8B); font-weight: 700; text-decoration: none; }
.auth-bottom a:hover { text-decoration: underline; }

.auth-demo {
  margin-top: 1.25rem; background: #f0f7f4; border: 1px solid #d0e6f0;
  border-radius: 14px; padding: 0.875rem 1rem;
}
.auth-demo-title { font-size: 0.68rem; font-weight: 900; color: #4a6080; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.5rem; }
.auth-demo-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 0.78rem; cursor: pointer; border-radius: 8px;
  padding: 5px 7px; border-bottom: 1px solid #ddeef8; transition: background 0.15s;
}
.auth-demo-row:last-child { border-bottom: none; }
.auth-demo-row:hover { background: #ddeef8; }
.auth-demo-email { font-weight: 700; color: var(--primary, #1F2A6D); }
.auth-demo-pass  { color: #94a3b8; font-family: monospace; font-size: 0.75rem; }

/* BOTTOM NAV */
:root { --bnav-h: 68px; }
.pub-bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;z-index:1100;}
@media(max-width:767.98px){
  .pub-bottom-nav{display:block;}
  body.auth-page{padding-bottom:calc(var(--bnav-h) + 10px);}
}
.pub-bnav-bar{height:var(--bnav-h);background:linear-gradient(135deg,var(--primary-dark,#141c4a) 0%,var(--accent-cyan,#1F6F8B) 50%,var(--primary-light,#2E4A9E) 100%);display:flex;align-items:center;justify-content:space-around;position:relative;box-shadow:0 -3px 24px rgba(31,42,109,.35), 0 -1px 0 rgba(255,255,255,.06);}
.pub-bnav-notch{position:absolute;top:-32px; left:50%; transform:translateX(-50%);width:100px; height:32px;pointer-events:none;display:block;}
.pub-bnav-fab{position:absolute;top:-30px; left:50%; transform:translateX(-50%);width:60px; height:60px;background:linear-gradient(135deg,var(--primary-light,#2E4A9E),var(--accent-emerald,#38C98A));border-radius:50%;display:flex; align-items:center; justify-content:center;font-size:1.3rem; color:white;text-decoration:none; border:none; outline:none;cursor:pointer;box-shadow:0 6px 22px rgba(31,169,113,.55), 0 0 0 5px rgba(31,169,113,.12);animation:pubFabGlow 2.5s ease-in-out infinite;transition:all .28s cubic-bezier(.34,1.56,.64,1);z-index:3;border:3px solid rgba(255,255,255,.2);}
@keyframes pubFabGlow{0%,100%{box-shadow:0 6px 22px rgba(31,169,113,.55),0 0 0 5px rgba(31,169,113,.12),0 0 0 10px rgba(31,169,113,.05);}50%{box-shadow:0 8px 32px rgba(31,169,113,.7),0 0 0 9px rgba(31,169,113,.1),0 0 0 18px rgba(31,169,113,.04);}}
.pub-bnav-fab:hover{transform:translateX(-50%) scale(1.1) translateY(-4px);}
.pub-bnav-fab-label{position:absolute;bottom:6px; left:50%; transform:translateX(-50%);font-size:.55rem; font-weight:800; color:rgba(255,255,255,.45);text-transform:uppercase; letter-spacing:.06em;white-space:nowrap;z-index:2;pointer-events:none;}
.pub-bnav-item{display:flex; flex-direction:column; align-items:center; justify-content:center;gap:3px; flex:1; padding:6px 2px;text-decoration:none; border:none; background:transparent;cursor:pointer; -webkit-tap-highlight-color:transparent;transition:all .24s cubic-bezier(.34,1.56,.64,1);position:relative;}
.pub-bnav-item.spacer{pointer-events:none;opacity:0;flex:1.2;}
.pub-bnav-icon{width:34px; height:34px; border-radius:10px;display:flex; align-items:center; justify-content:center;font-size:.95rem; color:rgba(255,255,255,.4);background:transparent;transition:all .24s;}
.pub-bnav-label{font-size:.57rem;font-weight:700;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.05em;transition:color .24s;}
.pub-bnav-item.active .pub-bnav-icon,.pub-bnav-item:hover .pub-bnav-icon{color:#f472b6;transform:translateY(-2px);background:rgba(244,114,182,.12);}
.pub-bnav-item.active .pub-bnav-label,.pub-bnav-item:hover .pub-bnav-label{color:#f472b6;}
.pub-bnav-item.active::after{content:"";position:absolute; bottom:3px; left:50%; transform:translateX(-50%);width:4px; height:4px; border-radius:50%;background:#f472b6;}
@media(max-width:380px){.pub-bnav-label{display:none;}.pub-bnav-icon{width:30px;height:30px;font-size:.88rem;}.pub-bnav-fab{width:54px;height:54px;top:-26px;font-size:1.15rem;}}
';
include_once 'includes/header.php';
?>
<script>
    if (sessionStorage.getItem('hcs_user')) {
        window.location.replace('dashboard.php');
    }
</script>

<!-- SVG HEALTH BACKGROUNDS -->
<div class="auth-svg-bg" aria-hidden="true">
  <svg width="400" height="400" viewBox="0 0 400 400" style="top:-60px;right:-80px;opacity:0.06;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M200 40C200 40 240 0 280 0C320 0 360 30 360 80C360 130 320 160 280 200C240 240 200 280 200 280C200 280 160 240 120 200C80 160 40 130 40 80C40 30 80 0 120 0C160 0 200 40 200 40Z" fill="#1F2A6D"/>
  </svg>
  <svg width="200" height="200" viewBox="0 0 200 200" style="bottom:120px;left:40px;opacity:0.05;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="60" y="10" width="80" height="180" rx="12" fill="#2E4A9E"/>
    <rect x="10" y="60" width="180" height="80" rx="12" fill="#2E4A9E"/>
  </svg>
  <svg width="300" height="300" viewBox="0 0 300 300" style="top:50%;left:-60px;opacity:0.04;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="150" cy="150" r="140" stroke="#38C98A" stroke-width="8" fill="none"/>
    <circle cx="150" cy="150" r="100" stroke="#1F6F8B" stroke-width="4" fill="none" stroke-dasharray="20 10"/>
    <path d="M150 60L150 240M60 150L240 150" stroke="#1FA971" stroke-width="6" stroke-linecap="round"/>
  </svg>
  <svg width="180" height="180" viewBox="0 0 180 180" style="top:20%;right:10%;opacity:0.04;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M90 10C90 10 130 50 130 90C130 130 100 170 90 170C80 170 50 130 50 90C50 50 90 10 90 10Z" fill="#1F6F8B"/>
    <circle cx="90" cy="90" r="20" fill="#38C98A"/>
  </svg>
  <svg width="250" height="250" viewBox="0 0 250 250" style="bottom:80px;right:20%;opacity:0.04;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M125 25 L155 95 L230 95 L168 140 L193 215 L125 170 L57 215 L82 140 L20 95 L95 95 Z" fill="none" stroke="#2E4A9E" stroke-width="4"/>
    <circle cx="125" cy="125" r="35" fill="none" stroke="#1FA971" stroke-width="3"/>
    <rect x="110" y="100" width="30" height="50" rx="4" fill="none" stroke="#1F6F8B" stroke-width="2"/>
    <rect x="100" y="115" width="50" height="20" rx="4" fill="none" stroke="#1F6F8B" stroke-width="2"/>
  </svg>
  <svg width="160" height="160" viewBox="0 0 160 160" style="top:65%;right:5%;opacity:0.03;" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M80 10 Q120 40 140 80 Q120 120 80 150 Q40 120 20 80 Q40 40 80 10Z" fill="#1F2A6D"/>
  </svg>
</div>

<!-- NAVBAR -->
<nav class="auth-navbar navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/logo-transparent.png" alt="King Khalid Hospital Logo">
      <div class="brand-text-wrap">
        <span class="brand-title">King Khalid Hospital</span>
        <span class="brand-sub">Hail — مستشفى الملك خالد</span>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#authNavMenu" style="color:white;">
      <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="authNavMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="index.php#features" data-i18n="lp.nav_features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#codes" data-i18n="lp.nav_codes">Emergency Codes</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#how" data-i18n="lp.nav_how">How It Works</a></li>
      </ul>
      <div class="nav-actions">
        <button class="btn-nav-lang" onclick="toggleAuthLang()" type="button" data-i18n="lp.lang_btn">العربية</button>
        <a href="register.php" class="btn-nav-login nav-link" data-i18n="lp.nav_register">Register</a>
        <a href="login.php" class="btn-nav-signup nav-link" data-i18n="lp.nav_signin">Sign In</a>
      </div>
    </div>
  </div>
</nav>

<main id="main-content">
<div class="auth-page-wrap">
<div class="auth-split">

    <!-- LEFT BRAND PANEL -->
    <div class="auth-left">
        <div class="auth-bars" aria-hidden="true">
            <div class="auth-bar"></div>
            <div class="auth-bar"></div>
            <div class="auth-bar"></div>
            <div class="auth-bar"></div>
            <div class="auth-bar"></div>
        </div>

        <div class="auth-logo-wrap">
            <img src="assets/images/logo-transparent.png" alt="Hail Health Cluster Logo">
        </div>

        <h1 class="auth-left-title">King Khalid<br>Hospital</h1>
        <p class="auth-left-sub">Hail Health Cluster — Paging System v3.0</p>

        <div class="auth-nav-btns">
            <a href="register.php" class="auth-nav-btn">Register</a>
            <a href="login.php" class="auth-nav-btn active">Login</a>
        </div>

        <div class="auth-dots" aria-hidden="true">
            <div class="auth-dot blue"></div>
            <div class="auth-dot orange"></div>
            <div class="auth-dot white"></div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right">
        <div class="auth-box">

            <!-- Mobile logo -->
            <div class="auth-mobile-logo">
                <div class="auth-mobile-logo-hex">
                    <img src="assets/images/logo-transparent.png" alt="HHC Logo">
                </div>
                <div class="auth-mobile-logo-text">
                    <strong>King Khalid Hospital</strong>
                    <span>Call System v3.0 — Hail</span>
                </div>
            </div>

            <div class="auth-form-card">
                <h2 class="auth-form-title" data-i18n="auth.signin_title">Sign In</h2>
                <p class="auth-form-sub" data-i18n="auth.signin_sub">Sign in to your account</p>

                <form onsubmit="event.preventDefault(); doLogin();">
                    <div class="auth-field">
                        <label class="auth-label" for="lEmail">Email Address</label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-user auth-input-icon"></i>
                            <input class="auth-input" type="email" id="lEmail"
                                   placeholder="Email" value="admin@hospital.sa"
                                   autocomplete="email" required aria-required="true">
                        </div>
                    </div>

                    <div class="auth-field">
                        <label class="auth-label" for="lPass">Password</label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-lock auth-input-icon"></i>
                            <input class="auth-input" type="password" id="lPass"
                                   placeholder="Password" value="Admin@1234"
                                   autocomplete="current-password" required aria-required="true">
                        </div>
                    </div>

                    <div class="auth-forgot">
                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-btn">
                        Submit
                    </button>
                </form>

                <div class="auth-bottom">
                    No account? <a href="register.php">Create one</a>
                    &nbsp;·&nbsp;
                    <a href="index.php">← Home</a>
                </div>

                <div class="auth-demo" aria-label="Demo accounts">
                    <div class="auth-demo-title">Demo Accounts — click to fill</div>
                    <div class="auth-demo-row" onclick="document.getElementById('lEmail').value='admin@hospital.sa';document.getElementById('lPass').value='Admin@1234'">
                        <span class="auth-demo-email">admin@hospital.sa</span>
                        <span class="auth-demo-pass">Admin@1234</span>
                    </div>
                    <div class="auth-demo-row" onclick="document.getElementById('lEmail').value='sara@hospital.sa';document.getElementById('lPass').value='Admin@1234'">
                        <span class="auth-demo-email">sara@hospital.sa</span>
                        <span class="auth-demo-pass">Admin@1234</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</main>

<!-- BOTTOM NAV -->
<nav class="pub-bottom-nav" aria-label="Mobile navigation">
  <div class="pub-bnav-bar">
    <svg class="pub-bnav-notch" viewBox="0 0 100 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M0 32 L0 0 Q10 0 15 4 Q28 32 50 32 Q72 32 85 4 Q90 0 100 0 L100 32 Z" fill="#141c4a"/>
    </svg>
    <a href="index.php" class="pub-bnav-item">
      <div class="pub-bnav-icon"><i class="fas fa-home"></i></div>
      <span class="pub-bnav-label" data-i18n="lp.bnav_home">Home</span>
    </a>
    <a href="register.php" class="pub-bnav-item">
      <div class="pub-bnav-icon"><i class="fas fa-user-plus"></i></div>
      <span class="pub-bnav-label" data-i18n="lp.bnav_register">Register</span>
    </a>
    <span class="pub-bnav-item spacer" aria-hidden="true"></span>
    <a href="index.php#features" class="pub-bnav-item">
      <div class="pub-bnav-icon"><i class="fas fa-star"></i></div>
      <span class="pub-bnav-label" data-i18n="lp.bnav_features">Features</span>
    </a>
    <a href="index.php#codes" class="pub-bnav-item">
      <div class="pub-bnav-icon"><i class="fas fa-bolt"></i></div>
      <span class="pub-bnav-label" data-i18n="lp.bnav_codes">Codes</span>
    </a>
    <a href="login.php" class="pub-bnav-fab" aria-label="Sign In">
      <i class="fas fa-sign-in-alt"></i>
    </a>
    <span class="pub-bnav-fab-label" data-i18n="lp.bnav_signin">Sign In</span>
  </div>
</nav>

<script>
function toggleAuthLang() {
    const cur = localStorage.getItem('hcs_lang') || 'en';
    const nxt = cur === 'ar' ? 'en' : 'ar';
    localStorage.setItem('hcs_lang', nxt);
    document.documentElement.setAttribute('dir', nxt === 'ar' ? 'rtl' : 'ltr');
    document.documentElement.setAttribute('lang', nxt);
    if (typeof applyTranslations === 'function') applyTranslations();
    if (typeof updateLangToggle === 'function') updateLangToggle();
}
(function(){
    const l = localStorage.getItem('hcs_lang') || 'en';
    if (l === 'ar') {
        document.documentElement.setAttribute('dir', 'rtl');
        document.documentElement.setAttribute('lang', 'ar');
    }
    var saved = localStorage.getItem('hcs_theme');
    if (saved && saved !== 'default' && typeof applyTheme === 'function') applyTheme(saved);
})();
window.addEventListener('scroll', function () {
    var nb = document.querySelector('.auth-navbar');
    if (nb) nb.classList.toggle('scrolled', window.scrollY > 50);
});
</script>
<?php include_once 'includes/footer.php'; ?>

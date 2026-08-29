<?php
/**
 * Hospital Call System - Landing Page
 * King Khalid Hospital, Hail
 * Version: 3.0
 */
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" id="lpHtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hospital Call System — King Khalid Hospital</title>
  <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico">
  <link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet"/>
  <link href="assets/vendor/fontawesome/css/all.min.css" rel="stylesheet"/>
  <link href="assets/vendor/fonts/fonts.css" rel="stylesheet"/>
<style>
:root {
  --primary: #1F2A6D;
  --secondary: #2E4A9E;
  --accent: #9c27b0;
  --accent2: #38C98A;
  --danger: #dc2626;
  --gradient-primary: linear-gradient(135deg, #1F2A6D 0%, #2E4A9E 25%, #1F6F8B 50%, #1FA971 75%, #38C98A 100%);
  --gradient-accent: linear-gradient(90deg, #1F6F8B, #38C98A);
  --light-blue: #f5f0fc;
  --bg: #edeaf8;
  --white: #FFFFFF;
  --text-dark: #1e0a35;
  --text-mid: #6b5a88;
  --border: rgba(31,42,109,0.1);
  --bnav-h: 68px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-dark);overflow-x:hidden;}
html[dir="rtl"] body{font-family:'Cairo',sans-serif;}
html[dir="rtl"] .brand-title,
html[dir="rtl"] .hero h1,
html[dir="rtl"] .section-h,
html[dir="rtl"] .feat-title,
html[dir="rtl"] .step-title,
html[dir="rtl"] .code-name,
html[dir="rtl"] .cta-h,
html[dir="rtl"] .footer-h,
html[dir="rtl"] .footer-brand{font-family:'Cairo',sans-serif;}
html[dir="rtl"] .navbar-nav{text-align:right;}
html[dir="rtl"] .footer-link{text-align:right;}

/* NAVBAR */
.navbar{background:linear-gradient(90deg,#1F2A6D,#2E4A9E,#38C98A) !important;border-bottom:1px solid rgba(255,255,255,0.1);padding:.75rem 0;position:fixed;top:0;width:100%;z-index:1000;transition:all .3s;border-radius:0 0 36px 36px;}
.navbar.scrolled{background:linear-gradient(90deg,#141c4a,#1F2A6D,#2E4A9E) !important;box-shadow:0 4px 28px rgba(31,42,109,.45);}
.navbar-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
.navbar-brand img.brand-logo{width:40px;height:40px;border-radius:50%;border:2px solid rgba(255,255,255,.4);object-fit:cover;flex-shrink:0;box-shadow:0 0 12px rgba(31,169,113,.45);}
.brand-text-wrap{display:flex;flex-direction:column;line-height:1.2;}
.brand-title{font-family:'Sora',sans-serif;font-weight:800;font-size:clamp(.88rem,2vw,1.05rem);color:white !important;white-space:nowrap;}
.brand-sub{font-size:.68rem;color:rgba(255,255,255,.6);white-space:nowrap;}
.nav-link{color:rgba(255,255,255,.85) !important;font-weight:600;font-size:.9rem;padding:.45rem 1rem !important;border-radius:50px;transition:all .22s;}
.nav-link:hover{color:white !important;background:rgba(255,255,255,.14);}
.btn-nav-login{background:transparent;border:2px solid rgba(255,255,255,.65);color:white !important;border-radius:50px;padding:.38rem 1.2rem !important;font-weight:700;}
.btn-nav-login:hover{background:white;color:var(--primary) !important;}
.btn-nav-signup{background:white;border:none;color:var(--primary) !important;border-radius:50px;padding:.38rem 1.2rem !important;font-weight:700;box-shadow:0 4px 14px rgba(0,0,0,.18);}
.btn-nav-signup:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(0,0,0,.25);}
.nav-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.btn-nav-lang{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.3);color:white !important;border-radius:50px;padding:.35rem 1rem !important;font-weight:700;font-size:.82rem;cursor:pointer;transition:all .22s;}
.btn-nav-lang:hover{background:rgba(255,255,255,.22);color:white !important;}
@media(max-width:991.98px){
  .navbar{border-radius:0 0 22px 22px;}
  .navbar-collapse{background:linear-gradient(135deg,#141c4a,#1F2A6D);border-radius:16px;margin-top:10px;padding:1rem;border:1px solid rgba(255,255,255,.12);}
  .navbar-nav{flex-direction:column;width:100%;gap:3px;}
  .navbar-nav .nav-item{width:100%;}
  .navbar-nav .nav-link{display:block;text-align:center;border-radius:12px;}
  .nav-actions{justify-content:center;margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.12);width:100%;}
}

/* HERO */
.hero{min-height:100vh;background:linear-gradient(145deg,#0d1535 0%,#1F2A6D 50%,#1F6F8B 100%);position:relative;display:flex;align-items:center;overflow:hidden;padding-top:96px;padding-bottom:60px;border-bottom-left-radius:50% 70px;border-bottom-right-radius:50% 70px;}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 15% 55%,rgba(46,74,158,.22) 0%,transparent 52%),radial-gradient(ellipse at 85% 15%,rgba(31,169,113,.14) 0%,transparent 40%),radial-gradient(ellipse at 55% 85%,rgba(31,42,109,.12) 0%,transparent 38%);pointer-events:none;}
.hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(31,169,113,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(31,169,113,.05) 1px,transparent 1px);background-size:55px 55px;pointer-events:none;}
.hero-float{position:absolute;border-radius:50%;filter:blur(70px);animation:floatBlob 9s ease-in-out infinite;pointer-events:none;}
.hero-float:nth-child(1){width:380px;height:380px;background:rgba(46,74,158,.18);top:-90px;left:-90px;}
.hero-float:nth-child(2){width:280px;height:280px;background:rgba(31,169,113,.12);bottom:-70px;right:-70px;animation-delay:-3s;}
.hero-float:nth-child(3){width:180px;height:180px;background:rgba(31,42,109,.1);top:38%;right:18%;animation-delay:-5s;}
@keyframes floatBlob{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(28px,-18px) scale(1.06)}}

.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.07);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.14);color:#fff;border-radius:50px;padding:.45rem 1.2rem;font-size:.85rem;font-weight:600;margin-bottom:1.4rem;}
.pulse-dot{width:8px;height:8px;border-radius:50%;background:#f472b6;flex-shrink:0;animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.5)}}

.hero h1{font-family:'Sora',sans-serif;font-size:clamp(2rem,5.5vw,4rem);font-weight:900;color:#fff;line-height:1.15;margin-bottom:1.2rem;letter-spacing:-.025em;}
.hero h1 .accent-text{background:linear-gradient(90deg,#a8dff9,#38C98A);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero h1 .sub-ar{font-size:clamp(1rem,2.5vw,1.7rem);font-weight:600;color:rgba(255,255,255,.45);display:block;margin-top:4px;}
.hero-sub{color:rgba(255,255,255,.75);font-size:clamp(.88rem,2vw,1.02rem);line-height:1.9;margin-bottom:2rem;max-width:510px;}
.hero-sub .sub-ar{display:block;color:rgba(255,255,255,.4);font-size:.82rem;margin-top:4px;}
.hero-cta-group{display:flex;gap:12px;flex-wrap:wrap;align-items:center;}
.btn-hero-primary{background:linear-gradient(90deg,#2E4A9E,#38C98A);border:none;color:#fff;border-radius:50px;padding:.85rem 2.1rem;font-family:'Sora',sans-serif;font-weight:700;font-size:clamp(.86rem,2vw,.98rem);box-shadow:0 8px 28px rgba(31,169,113,.42);transition:all .3s;text-decoration:none;display:inline-flex;align-items:center;gap:.55rem;white-space:nowrap;}
.btn-hero-primary:hover{transform:translateY(-3px);box-shadow:0 14px 38px rgba(31,169,113,.55);color:#fff;}
.btn-hero-secondary{background:rgba(255,255,255,.07);backdrop-filter:blur(12px);border:2px solid rgba(255,255,255,.22);color:#fff;border-radius:50px;padding:.85rem 2.1rem;font-family:'Sora',sans-serif;font-weight:600;font-size:clamp(.86rem,2vw,.98rem);transition:all .3s;text-decoration:none;display:inline-flex;align-items:center;gap:.55rem;white-space:nowrap;cursor:pointer;}
.btn-hero-secondary:hover{background:rgba(255,255,255,.16);transform:translateY(-3px);color:#fff;}
.hero-stats{display:flex;gap:clamp(1rem,3vw,2.2rem);margin-top:2.4rem;flex-wrap:wrap;}
.hero-stat .num{font-family:'Sora',sans-serif;font-size:clamp(1.4rem,3vw,2rem);font-weight:900;color:#a8dff9;}
.hero-stat .lbl{font-size:clamp(.7rem,1.5vw,.78rem);color:rgba(255,255,255,.55);font-weight:500;}
.hero-cards-col{display:flex;justify-content:center;}

/* Mock card */
.mock-card{background:rgba(255,255,255,.04);backdrop-filter:blur(22px);border:1px solid rgba(255,255,255,.1);border-radius:22px;overflow:hidden;width:100%;max-width:400px;box-shadow:0 24px 55px rgba(0,0,0,.38),0 0 0 1px rgba(46,74,158,.25);}
.mock-header{background:rgba(46,74,158,.22);border-bottom:1px solid rgba(255,255,255,.08);padding:13px 17px;display:flex;align-items:center;justify-content:space-between;}
.mock-dots{display:flex;gap:6px;}
.mock-dots span{width:10px;height:10px;border-radius:50%;}
.mock-dots span:nth-child(1){background:#ef4444;}
.mock-dots span:nth-child(2){background:#f59e0b;}
.mock-dots span:nth-child(3){background:#22c55e;}
.mock-title{font-family:'Sora',sans-serif;font-size:.8rem;font-weight:700;color:rgba(255,255,255,.78);}
.mock-live{display:flex;align-items:center;gap:5px;font-size:.7rem;font-weight:700;color:#f472b6;}
.mock-live span{width:6px;height:6px;border-radius:50%;background:#f472b6;animation:pulse 1.5s infinite;}
.mock-body{padding:18px;}
.mock-code-block{background:rgba(220,38,38,.14);border:1px solid rgba(220,38,38,.28);border-radius:14px;padding:14px;display:flex;align-items:center;gap:13px;margin-bottom:15px;}
.mock-code-icon{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#dc2626,#ef4444);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:white;flex-shrink:0;animation:heartbeat 1.6s ease-in-out infinite;}
@keyframes heartbeat{0%,100%{transform:scale(1)}50%{transform:scale(1.1)}}
.mock-code-name{font-family:'Sora',sans-serif;font-weight:800;font-size:.95rem;color:#fca5a5;}
.mock-code-loc{font-size:.75rem;color:rgba(255,255,255,.45);margin-top:2px;}
.mock-wave{display:flex;align-items:center;justify-content:center;gap:3px;height:38px;margin-bottom:15px;}
.mock-wave span{width:4px;border-radius:4px;background:linear-gradient(180deg,#a8dff9,#2E4A9E);animation:wave 1.2s ease-in-out infinite;}
.mock-wave span:nth-child(1){height:14px;animation-delay:0s}.mock-wave span:nth-child(2){height:26px;animation-delay:.1s}.mock-wave span:nth-child(3){height:34px;animation-delay:.2s}.mock-wave span:nth-child(4){height:20px;animation-delay:.3s}.mock-wave span:nth-child(5){height:30px;animation-delay:.4s}.mock-wave span:nth-child(6){height:16px;animation-delay:.5s}.mock-wave span:nth-child(7){height:24px;animation-delay:.6s}.mock-wave span:nth-child(8){height:32px;animation-delay:.7s}.mock-wave span:nth-child(9){height:18px;animation-delay:.8s}.mock-wave span:nth-child(10){height:12px;animation-delay:.9s}
@keyframes wave{0%,100%{transform:scaleY(.5);opacity:.5}50%{transform:scaleY(1.2);opacity:1}}
.mock-ann{background:rgba(255,255,255,.05);border-radius:11px;padding:11px 13px;display:flex;align-items:flex-start;gap:9px;margin-bottom:13px;font-size:.8rem;color:rgba(255,255,255,.65);font-style:italic;line-height:1.6;}
.mock-ann i{color:#f472b6;margin-top:2px;flex-shrink:0;}
.mock-chips{display:flex;gap:7px;flex-wrap:wrap;}
.mock-chip{padding:4px 11px;border-radius:50px;font-size:.7rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;}
.mock-chip.blue{background:rgba(46,74,158,.22);color:#e9d5ff;border:1px solid rgba(46,74,158,.35);}
.mock-chip.green{background:rgba(5,150,105,.18);color:#6ee7b7;border:1px solid rgba(5,150,105,.28);}

@media(max-width:991.98px){.hero{padding-top:88px;border-bottom-left-radius:50% 50px;border-bottom-right-radius:50% 50px;text-align:center;}.hero-sub{margin:0 auto 2rem;}.hero-cta-group{justify-content:center;}.hero-stats{justify-content:center;}}
@media(max-width:575.98px){.hero{border-bottom-left-radius:50% 28px;border-bottom-right-radius:50% 28px;padding-top:78px;}.hero-float:nth-child(1){width:180px;height:180px;}.hero-float:nth-child(2){width:140px;height:140px;}.hero-float:nth-child(3){display:none;}.mock-card{max-width:320px;}}

/* SECTIONS */
.section-pad{padding:clamp(3rem,8vw,5.5rem) 0;}
.section-title-wrap{text-align:center;margin-bottom:clamp(1.8rem,5vw,3.2rem);}
.section-eyebrow{display:inline-block;background:var(--light-blue);color:var(--secondary);border-radius:50px;padding:.32rem 1.1rem;font-size:.8rem;font-weight:700;margin-bottom:.9rem;border:1px solid var(--border);}
.section-h{font-family:'Sora',sans-serif;font-size:clamp(1.45rem,4vw,2.5rem);font-weight:900;color:var(--text-dark);margin-bottom:.75rem;letter-spacing:-.022em;}
.section-h span{background:var(--gradient-accent);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.section-sub{color:var(--text-mid);font-size:clamp(.86rem,2vw,.98rem);max-width:540px;margin:0 auto;line-height:1.85;}

/* FEATURE CARDS */
.feat-card{background:var(--white);border:1px solid var(--border);border-radius:20px;padding:clamp(1.2rem,3vw,1.9rem);height:100%;transition:all .3s;position:relative;overflow:hidden;}
.feat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--gradient-accent);opacity:0;transition:opacity .3s;}
.feat-card:hover{transform:translateY(-6px);box-shadow:0 18px 45px rgba(10,42,110,.1);border-color:var(--accent);}
.feat-card:hover::before{opacity:1;}
.feat-icon{width:52px;height:52px;border-radius:15px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;color:white;margin-bottom:1.1rem;box-shadow:0 7px 18px rgba(0,0,0,.14);}
.feat-title{font-family:'Sora',sans-serif;font-size:clamp(.88rem,2vw,1rem);font-weight:800;color:var(--text-dark);margin-bottom:.55rem;}
.feat-desc{font-size:clamp(.78rem,1.5vw,.86rem);color:var(--text-mid);line-height:1.8;}

/* HOW IT WORKS */
.how-section{background:linear-gradient(140deg,#0d1535 0%,#1F2A6D 100%);position:relative;overflow:hidden;}
.how-section::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(46,74,158,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(46,74,158,.07) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;}
.step-card{background:rgba(255,255,255,.055);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:clamp(1.2rem,3vw,2rem);text-align:center;height:100%;transition:all .3s;}
.step-card:hover{background:rgba(255,255,255,.1);transform:translateY(-5px);}
.step-num{width:54px;height:54px;border-radius:50%;font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:900;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;background:var(--gradient-accent);box-shadow:0 8px 20px rgba(31,169,113,.38);}
.step-title{color:#fff;font-family:'Sora',sans-serif;font-weight:800;font-size:clamp(.88rem,2vw,.98rem);margin-bottom:.45rem;}
.step-desc{color:rgba(255,255,255,.58);font-size:clamp(.78rem,1.5vw,.84rem);line-height:1.8;}

/* CODES */
.code-card{border-radius:18px;padding:1.3rem;display:flex;align-items:center;gap:13px;transition:all .3s;border:1px solid transparent;cursor:default;}
.code-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.09);}
.code-card.blue{background:#EEF4FF;border-color:#bfdbfe;}.code-card.red{background:#FEF2F2;border-color:#fecaca;}.code-card.pink{background:#FDF2F8;border-color:#a8dff9;}.code-card.black{background:#F1F5F9;border-color:#cbd5e1;}
.code-icon{width:48px;height:48px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:white;flex-shrink:0;}
.code-icon.blue-g{background:linear-gradient(135deg,#1549c0,#3b82f6);}.code-icon.red-g{background:linear-gradient(135deg,#dc2626,#ef4444);}.code-icon.pink-g{background:linear-gradient(135deg,#be185d,#ec4899);}.code-icon.black-g{background:linear-gradient(135deg,#1e293b,#475569);}
.code-name{font-family:'Sora',sans-serif;font-weight:800;font-size:.92rem;color:var(--text-dark);}
.code-desc{font-size:.75rem;color:var(--text-mid);margin-top:2px;}

/* CTA */
.cta-section{background:linear-gradient(155deg,#1F2A6D,#2E4A9E,#38C98A);background-size:200% 200%;animation:gradMove 6s ease infinite;position:relative;overflow:hidden;}
.cta-section::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:42px 42px;}
@keyframes gradMove{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.cta-h{font-family:'Sora',sans-serif;font-size:clamp(1.45rem,4vw,2.6rem);font-weight:900;color:#fff;margin-bottom:.9rem;letter-spacing:-.022em;}
.cta-sub{color:rgba(255,255,255,.82);font-size:clamp(.88rem,2vw,1.02rem);margin-bottom:2rem;}
.btn-cta{background:#fff;color:var(--primary);border:none;border-radius:50px;padding:.88rem 2.3rem;font-family:'Sora',sans-serif;font-weight:800;font-size:clamp(.86rem,2vw,.98rem);transition:all .3s;text-decoration:none;display:inline-flex;align-items:center;gap:.6rem;cursor:pointer;}
.btn-cta:hover{transform:translateY(-3px);box-shadow:0 12px 38px rgba(0,0,0,.24);color:var(--primary);}
.btn-cta-ghost{background:transparent;border:2px solid rgba(255,255,255,.46);color:white;border-radius:50px;padding:.88rem 2.3rem;font-family:'Sora',sans-serif;font-weight:700;font-size:clamp(.86rem,2vw,.98rem);transition:all .3s;text-decoration:none;display:inline-flex;align-items:center;gap:.6rem;cursor:pointer;}
.btn-cta-ghost:hover{background:rgba(255,255,255,.12);transform:translateY(-3px);color:white;}

/* FOOTER */
footer{background:linear-gradient(155deg,#1F2A6D,#2E4A9E,#38C98A);color:rgba(255,255,255,.9);padding:clamp(2.5rem,6vw,3.5rem) 0 1.5rem;border-top-left-radius:50% 70px;border-top-right-radius:50% 70px;margin-top:3rem;}
.footer-brand{display:flex;align-items:center;gap:10px;font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:800;margin-bottom:.75rem;}
.footer-brand img{width:34px;height:34px;border-radius:50%;border:2px solid rgba(255,255,255,.35);object-fit:cover;flex-shrink:0;}
.footer-desc{font-size:clamp(.78rem,1.8vw,.84rem);line-height:1.85;max-width:270px;margin-bottom:1.4rem;color:rgba(255,255,255,.65);}
.footer-h{font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:.92rem;margin-bottom:.9rem;}
.footer-link{display:block;color:rgba(255,255,255,.62);font-size:.84rem;text-decoration:none;padding:.22rem 0;transition:color .2s;}
.footer-link:hover{color:#a8dff9;}
.footer-bottom{border-top:1px solid rgba(255,255,255,.1);margin-top:2rem;padding-top:1.4rem;text-align:center;font-size:.77rem;color:rgba(255,255,255,.42);}
.dev-footer-wrap{display:flex;align-items:center;justify-content:center;gap:12px;margin-top:14px;flex-wrap:wrap;padding-top:12px;border-top:1px solid rgba(255,255,255,.12);}
.dev-footer-label{font-size:.75rem;color:rgba(255,255,255,.7);display:flex;align-items:center;gap:6px;font-weight:600;}
.dev-footer-label i{color:#38C98A;font-size:.82rem;}
.dev-footer-name{font-size:.82rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:7px;}
.dev-footer-name i{color:#a78bfa;font-size:.9rem;}
.dev-footer-icons{display:flex;align-items:center;gap:8px;}
.dev-icon-btn{width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;text-decoration:none;cursor:pointer;transition:all .25s;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);}
.dev-email-btn{color:#fff;}
.dev-email-btn:hover{background:#3b82f6;border-color:#3b82f6;color:#fff;transform:translateY(-2px);}
.dev-wa-btn{color:#fff;}
.dev-wa-btn:hover{background:#25D366;border-color:#25D366;color:#fff;transform:translateY(-2px);}
.social-btn{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:inline-flex;align-items:center;justify-content:center;color:rgba(255,255,255,.65);text-decoration:none;transition:all .24s;margin-right:.35rem;margin-bottom:.35rem;}
.social-btn:hover{background:white;color:var(--primary);}
@media(max-width:767.98px){footer{border-top-left-radius:50% 45px;border-top-right-radius:50% 45px;}}
@media(max-width:575.98px){footer{border-top-left-radius:50% 26px;border-top-right-radius:50% 26px;}}

/* BOTTOM NAV */
.bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;z-index:1100;}
@media(max-width:767.98px){
  .bottom-nav{display:block;}
  body{padding-bottom:calc(var(--bnav-h) + 10px);}
}
.bnav-bar{height:var(--bnav-h);background:linear-gradient(135deg,#141c4a 0%,#1F6F8B 50%,#2E4A9E 100%);display:flex;align-items:center;justify-content:space-around;position:relative;box-shadow:0 -3px 24px rgba(31,42,109,.35), 0 -1px 0 rgba(255,255,255,.06);}
.bnav-notch-svg{position:absolute;top:-32px; left:50%; transform:translateX(-50%);width:100px; height:32px;pointer-events:none;display:block;}
.bnav-fab{position:absolute;top:-30px; left:50%; transform:translateX(-50%);width:60px; height:60px;background:linear-gradient(135deg,#2E4A9E,#38C98A);border-radius:50%;display:flex; align-items:center; justify-content:center;font-size:1.3rem; color:white;text-decoration:none; border:none; outline:none;cursor:pointer;box-shadow:0 6px 22px rgba(31,169,113,.55), 0 0 0 5px rgba(31,169,113,.12), 0 0 0 10px rgba(31,169,113,.05);animation:fabGlow 2.5s ease-in-out infinite;transition:all .28s cubic-bezier(.34,1.56,.64,1);z-index:3;border:3px solid rgba(255,255,255,.2);}
@keyframes fabGlow{0%,100%{box-shadow:0 6px 22px rgba(31,169,113,.55),0 0 0 5px rgba(31,169,113,.12),0 0 0 10px rgba(31,169,113,.05);}50%{box-shadow:0 8px 32px rgba(31,169,113,.7),0 0 0 9px rgba(31,169,113,.1),0 0 0 18px rgba(31,169,113,.04);}}
.bnav-fab:hover{transform:translateX(-50%) scale(1.1) translateY(-4px);}
.bnav-fab:active{transform:translateX(-50%) scale(.95);}
.bnav-fab-label{position:absolute;bottom:6px; left:50%; transform:translateX(-50%);font-size:.55rem; font-weight:800; color:rgba(255,255,255,.45);text-transform:uppercase; letter-spacing:.06em;white-space:nowrap;z-index:2;pointer-events:none;}
.bnav-item{display:flex; flex-direction:column; align-items:center; justify-content:center;gap:3px; flex:1; padding:6px 2px;text-decoration:none; border:none; background:transparent;cursor:pointer; -webkit-tap-highlight-color:transparent;transition:all .24s cubic-bezier(.34,1.56,.64,1);position:relative;}
.bnav-item.bnav-spacer{pointer-events:none;opacity:0;flex:1.2;}
.bnav-icon{width:34px; height:34px; border-radius:10px;display:flex; align-items:center; justify-content:center;font-size:.95rem; color:rgba(255,255,255,.4);background:transparent;transition:all .24s;}
.bnav-label{font-size:.57rem;font-weight:700;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.05em;transition:color .24s;}
.bnav-item.active .bnav-icon,.bnav-item:hover .bnav-icon{color:#f472b6;transform:translateY(-2px);background:rgba(244,114,182,.12);}
.bnav-item.active .bnav-label,.bnav-item:hover .bnav-label{color:#f472b6;}
.bnav-item.active::after{content:'';position:absolute; bottom:3px; left:50%; transform:translateX(-50%);width:4px; height:4px; border-radius:50%;background:#f472b6;}
@media(max-width:380px){.bnav-label{display:none;}.bnav-icon{width:30px;height:30px;font-size:.88rem;}.bnav-fab{width:54px;height:54px;top:-26px;font-size:1.15rem;}}
@media(hover:none){.feat-card:hover,.code-card:hover{transform:none;}}
@media(max-width:575.98px){.section-pad{padding:2.4rem 0;}.section-title-wrap{margin-bottom:1.4rem;}}

/* ============================================================
   CHATBOT — Landing Page Inline Styles
============================================================ */
.chatbot-fab{position:fixed;bottom:90px;right:24px;z-index:9000;width:58px;height:58px;border-radius:50%;background:linear-gradient(135deg,#2E4A9E,#38C98A);color:white;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(31,42,109,.4),0 2px 8px rgba(0,0,0,.2);display:flex;align-items:center;justify-content:center;transition:transform .35s cubic-bezier(.34,1.56,.64,1),box-shadow .35s;overflow:hidden;}
.chatbot-fab:hover{transform:scale(1.1) translateY(-3px);box-shadow:0 8px 30px rgba(31,42,109,.5);}
.chatbot-fab-icon,.chatbot-fab-close{position:absolute;font-size:1.3rem;transition:all .3s;}
.chatbot-fab-close{opacity:0;transform:rotate(-90deg) scale(.5);}
.chatbot-fab.chat-open .chatbot-fab-icon{opacity:0;transform:rotate(90deg) scale(.5);}
.chatbot-fab.chat-open .chatbot-fab-close{opacity:1;transform:rotate(0) scale(1);}
.chatbot-badge{position:absolute;top:4px;right:4px;background:#e03131;color:white;width:18px;height:18px;border-radius:50%;font-size:.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid white;animation:badge-pulse 2s infinite;}
@keyframes badge-pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.15)}}
@media(max-width:768px){.chatbot-fab{bottom:80px;right:16px;width:50px;height:50px;}}
.chatbot-panel{position:fixed;bottom:160px;right:24px;z-index:8999;width:370px;max-height:520px;background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.2),0 0 0 1px rgba(31,42,109,.1);display:flex;flex-direction:column;overflow:hidden;transform:scale(.85) translateY(30px);opacity:0;pointer-events:none;transform-origin:bottom right;transition:all .3s cubic-bezier(.34,1.56,.64,1);}
.chatbot-panel.open{transform:scale(1) translateY(0);opacity:1;pointer-events:all;}
@media(max-width:480px){.chatbot-panel{right:10px;left:10px;width:auto;bottom:140px;max-height:60vh;}.chatbot-fab{right:16px;}}
.chatbot-header{background:linear-gradient(135deg,#2E4A9E,#38C98A);color:white;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.chatbot-header-info{display:flex;align-items:center;gap:10px;}
.chatbot-avatar{width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem;position:relative;flex-shrink:0;}
.chatbot-fab-logo{width:38px;height:38px;border-radius:50%;object-fit:contain;}
.chatbot-avatar-logo{width:32px;height:32px;border-radius:50%;object-fit:contain;}
.chatbot-online-dot{position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:#22c55e;border:2px solid white;animation:online-blink 2.5s infinite;}
@keyframes online-blink{0%,100%{opacity:1}50%{opacity:.5}}
.chatbot-name{font-weight:700;font-size:.95rem;}
.chatbot-status{font-size:.72rem;opacity:.85;}
.chatbot-close-btn{background:rgba(255,255,255,.15);border:none;color:white;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.85rem;transition:background .2s;flex-shrink:0;}
.chatbot-close-btn:hover{background:rgba(255,255,255,.25);}
.chatbot-messages{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;scroll-behavior:smooth;}
.chatbot-messages::-webkit-scrollbar{width:4px;}
.chatbot-messages::-webkit-scrollbar-track{background:transparent;}
.chatbot-messages::-webkit-scrollbar-thumb{background:#38C98A;border-radius:2px;}
.chat-msg{display:flex;align-items:flex-end;gap:8px;animation:chat-in .25s ease-out;}
@keyframes chat-in{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.chat-msg.user{flex-direction:row-reverse;}
.chat-avatar-bot{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#2E4A9E,#38C98A);color:white;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0;}
.chat-bubble{max-width:88%;padding:10px 13px;border-radius:16px;font-size:.83rem;line-height:1.5;word-break:break-word;}
.chat-bubble.user{background:linear-gradient(135deg,#2E4A9E,#38C98A);color:white;border-bottom-right-radius:4px;}
.chat-bubble.bot{background:#f5f0fc;color:#1e0a35;border:1px solid rgba(31,42,109,.1);border-bottom-left-radius:4px;}
.chat-bubble-title{font-weight:700;font-size:.88rem;margin-bottom:6px;color:#2E4A9E;}
.chat-bubble-text{line-height:1.6;}
.chat-bubble.typing{display:flex;gap:5px;align-items:center;padding:12px 16px;}
.chat-bubble.typing span{width:7px;height:7px;border-radius:50%;background:#9c27b0;animation:typing-bounce 1.2s infinite ease-in-out;}
.chat-bubble.typing span:nth-child(1){animation-delay:0s}.chat-bubble.typing span:nth-child(2){animation-delay:.2s}.chat-bubble.typing span:nth-child(3){animation-delay:.4s}
@keyframes typing-bounce{0%,60%,100%{transform:translateY(0);opacity:.4}30%{transform:translateY(-6px);opacity:1}}
.chat-quick-btns{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;}
.chat-qbtn{background:#f5f0fc;border:1px solid rgba(31,42,109,.2);color:#2E4A9E;font-size:.75rem;font-weight:600;padding:4px 10px;border-radius:50px;cursor:pointer;transition:all .2s;white-space:nowrap;}
.chat-qbtn:hover{background:#2E4A9E;color:white;border-color:#2E4A9E;transform:translateY(-1px);}
.chatbot-input-area{padding:10px 12px;border-top:1px solid rgba(31,42,109,.1);display:flex;gap:8px;align-items:center;background:#fff;flex-shrink:0;}
.chatbot-input{flex:1;background:#f5f0fc;border:1px solid rgba(31,42,109,.1);border-radius:50px;padding:9px 14px;font-size:.83rem;color:#1e0a35;outline:none;transition:border-color .2s;font-family:inherit;}
.chatbot-input:focus{border-color:#9c27b0;background:#fff;}
.chatbot-input::placeholder{color:#9c87b8;}
.chatbot-send-btn{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2E4A9E,#38C98A);color:white;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;transition:all .2s;}
.chatbot-send-btn:hover{transform:scale(1.1);box-shadow:0 4px 12px rgba(31,42,109,.3);}
html[dir="rtl"] .chatbot-fab{right:auto;left:24px;}
html[dir="rtl"] .chatbot-panel{right:auto;left:24px;transform-origin:bottom left;}
html[dir="rtl"] .chat-msg.user{flex-direction:row;}
html[dir="rtl"] .chat-msg.bot{flex-direction:row-reverse;}
html[dir="rtl"] .chat-bubble.user{border-bottom-right-radius:16px;border-bottom-left-radius:4px;}
html[dir="rtl"] .chat-bubble.bot{border-bottom-left-radius:16px;border-bottom-right-radius:4px;}
html[dir="rtl"] .chatbot-badge{right:auto;left:4px;}
@media(max-width:767.98px){.chatbot-fab{bottom:calc(var(--bnav-h) + 16px);}html[dir="rtl"] .chatbot-panel{left:10px;right:10px;}}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img class="brand-logo" src="https://pbs.twimg.com/profile_images/1727685869367144448/3hqKnJs9_400x400.jpg" alt="King Khalid Hospital Logo">
      <div class="brand-text-wrap">
        <span class="brand-title">King Khalid Hospital</span>
        <span class="brand-sub">Hail — مستشفى الملك خالد</span>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="color:white;">
      <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="#features" data-i18n="lp.nav_features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#codes" data-i18n="lp.nav_codes">Emergency Codes</a></li>
        <li class="nav-item"><a class="nav-link" href="#how" data-i18n="lp.nav_how">How It Works</a></li>
      </ul>
      <div class="nav-actions">
        <button class="btn-nav-lang" id="lpLangBtn" onclick="lpToggleLang()" type="button" data-i18n="lp.lang_btn">العربية</button>
        <a href="register.php" class="btn-nav-login nav-link" data-i18n="lp.nav_register">Register</a>
        <a href="login.php" class="btn-nav-signup nav-link" data-i18n="lp.nav_signin">Sign In</a>
      </div>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-float"></div>
  <div class="hero-float"></div>
  <div class="hero-float"></div>
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center g-4 g-lg-5">
      <div class="col-lg-6 col-12">
        <div class="hero-badge">
          <span class="pulse-dot"></span>
          <span data-i18n="lp.hero_status">System Online</span> — الاتصال الداخلي نشط
        </div>
        <h1 id="heroH1">
          Hospital<br>
          <span class="accent-text">Call System</span><br>
          <span class="sub-ar">نظام نداءات المستشفى</span>
        </h1>
        <p class="hero-sub">
          <span data-i18n="lp.hero_desc">Airport-style voice paging — emergency codes, doctor pages, and staff calls broadcast across all departments in under 1 second.</span>
          <span class="sub-ar" data-i18n="lp.hero_desc_ar">إعلانات صوتية فورية لجميع الأقسام بأسلوب نداءات المطارات</span>
        </p>
        <div class="hero-cta-group">
          <a href="login.php" class="btn-hero-primary"><i class="fas fa-rocket"></i> <span data-i18n="lp.hero_launch">Launch System</span></a>
          <a href="register.php" class="btn-hero-secondary"><i class="fas fa-user-plus"></i> <span data-i18n="lp.hero_create">Create Account</span></a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat"><div class="num">13+</div><div class="lbl" data-i18n="lp.stat_depts">Departments</div></div>
          <div class="hero-stat"><div class="num">20+</div><div class="lbl" data-i18n="lp.stat_specs">Specialties</div></div>
          <div class="hero-stat"><div class="num">&lt;1s</div><div class="lbl" data-i18n="lp.stat_response">Response Time</div></div>
          <div class="hero-stat"><div class="num">24/7</div><div class="lbl" data-i18n="lp.stat_uptime">Uptime</div></div>
        </div>
      </div>
      <div class="col-lg-6 col-12 hero-cards-col">
        <div class="mock-card">
          <div class="mock-header">
            <div class="mock-dots"><span></span><span></span><span></span></div>
            <div class="mock-title" data-i18n="lp.mock_title">Emergency Broadcast</div>
            <div class="mock-live"><span></span>LIVE</div>
          </div>
          <div class="mock-body">
            <div class="mock-code-block">
              <div class="mock-code-icon"><i class="fas fa-heart-pulse"></i></div>
              <div>
                <div class="mock-code-name">Code Blue</div>
                <div class="mock-code-loc">Emergency Room — غرفة الطوارئ</div>
              </div>
            </div>
            <div class="mock-wave">
              <span></span><span></span><span></span><span></span><span></span>
              <span></span><span></span><span></span><span></span><span></span>
            </div>
            <div class="mock-ann">
              <i class="fas fa-microphone-alt"></i>
              <p data-i18n="lp.mock_ann">"Attention please… Code Blue… Code Blue… Emergency Room…"</p>
            </div>
            <div class="mock-chips">
              <span class="mock-chip blue"><i class="fas fa-bell"></i> <span data-i18n="lp.mock_chimes">Chimes Active</span></span>
              <span class="mock-chip green"><i class="fas fa-check"></i> <span data-i18n="lp.mock_sent">Broadcast Sent</span></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="section-pad" id="features">
  <div class="container">
    <div class="section-title-wrap">
      <div class="section-eyebrow" data-i18n="lp.feat_eyebrow">Features</div>
      <h2 class="section-h" data-i18n="lp.feat_h">Powerful Tools for Every Department</h2>
      <p class="section-sub" data-i18n="lp.feat_sub">From instant emergency codes to scheduled announcements — everything your hospital needs in one system</p>
    </div>
    <div class="row g-3 g-md-4">
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444)"><i class="fas fa-bolt"></i></div><div class="feat-title" data-i18n="lp.feat1_t">Instant Emergency Codes</div><div class="feat-desc" data-i18n="lp.feat1_d">Code Blue, Red, Pink, Black — broadcast in under 1 second with airport-style chimes</div></div></div>
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#1549c0,#3b82f6)"><i class="fas fa-user-md"></i></div><div class="feat-title" data-i18n="lp.feat2_t">Doctor &amp; Staff Paging</div><div class="feat-desc" data-i18n="lp.feat2_d">Page any doctor by name, specialty, or role with gender-aware TTS voice</div></div></div>
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#059669,#34d399)"><i class="fas fa-microphone-alt"></i></div><div class="feat-title" data-i18n="lp.feat3_t">Airport-Style Voice</div><div class="feat-desc" data-i18n="lp.feat3_d">Metallic Ding-Dong chimes + slow deliberate pacing — exactly like international airports</div></div></div>
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)"><i class="fas fa-language"></i></div><div class="feat-title" data-i18n="lp.feat4_t">Bilingual Arabic / English</div><div class="feat-desc" data-i18n="lp.feat4_d">All announcements in both languages with full Arabic name support</div></div></div>
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#d97706,#fbbf24)"><i class="fas fa-clock"></i></div><div class="feat-title" data-i18n="lp.feat5_t">Scheduled Announcements</div><div class="feat-desc" data-i18n="lp.feat5_d">Set timed announcements for shift changes, reminders, or recurring messages</div></div></div>
      <div class="col-6 col-lg-4"><div class="feat-card"><div class="feat-icon" style="background:linear-gradient(135deg,#be185d,#f43f5e)"><i class="fas fa-shield-halved"></i></div><div class="feat-title" data-i18n="lp.feat6_t">Custom Emergency Codes</div><div class="feat-desc" data-i18n="lp.feat6_d">Create your own codes with custom colors, icons, and announcement messages</div></div></div>
    </div>
  </div>
</section>

<!-- EMERGENCY CODES -->
<section class="section-pad" id="codes" style="background:var(--white);">
  <div class="container">
    <div class="section-title-wrap">
      <div class="section-eyebrow" data-i18n="lp.codes_eyebrow">Emergency Codes</div>
      <h2 class="section-h" data-i18n="lp.codes_h">Recognized Hospital Codes</h2>
      <p class="section-sub" data-i18n="lp.codes_sub">Industry-standard emergency codes broadcast instantly across all departments</p>
    </div>
    <div class="row g-3">
      <div class="col-12 col-sm-6"><div class="code-card blue"><div class="code-icon blue-g"><i class="fas fa-heart-pulse"></i></div><div><div class="code-name" data-i18n="lp.code1_n">Code Blue — Cardiac Arrest</div><div class="code-desc" data-i18n="lp.code1_d">Immediate medical emergency requiring resuscitation team</div></div></div></div>
      <div class="col-12 col-sm-6"><div class="code-card red"><div class="code-icon red-g"><i class="fas fa-fire"></i></div><div><div class="code-name" data-i18n="lp.code2_n">Code Red — Fire Emergency</div><div class="code-desc" data-i18n="lp.code2_d">Fire or smoke detected in the facility</div></div></div></div>
      <div class="col-12 col-sm-6"><div class="code-card pink"><div class="code-icon pink-g"><i class="fas fa-baby"></i></div><div><div class="code-name" data-i18n="lp.code3_n">Code Pink — Infant Abduction</div><div class="code-desc" data-i18n="lp.code3_d">Missing or abducted infant / child alert</div></div></div></div>
      <div class="col-12 col-sm-6"><div class="code-card black"><div class="code-icon black-g"><i class="fas fa-shield-halved"></i></div><div><div class="code-name" data-i18n="lp.code4_n">Code Black — Bomb Threat</div><div class="code-desc" data-i18n="lp.code4_d">Bomb threat or suspicious package alert</div></div></div></div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section section-pad" id="how">
  <div class="container position-relative" style="z-index:1;">
    <div class="section-title-wrap">
      <div class="section-eyebrow" style="background:rgba(255,255,255,.08);color:#fff;border-color:rgba(255,255,255,.14);" data-i18n="lp.how_eyebrow">Simple Process</div>
      <h2 class="section-h" style="color:#fff;" data-i18n="lp.how_h">Get Started in 3 Easy Steps</h2>
    </div>
    <div class="row g-3 g-md-4">
      <div class="col-12 col-md-4"><div class="step-card"><div class="step-num">1</div><div class="step-title" data-i18n="lp.step1_t">Create Account</div><div class="step-desc" data-i18n="lp.step1_d">Register as operator or staff member in under 2 minutes</div></div></div>
      <div class="col-12 col-md-4"><div class="step-card"><div class="step-num">2</div><div class="step-title" data-i18n="lp.step2_t">Set Up Profile</div><div class="step-desc" data-i18n="lp.step2_d">Configure departments, staff list, and preferred announcement voices</div></div></div>
      <div class="col-12 col-md-4"><div class="step-card"><div class="step-num">3</div><div class="step-title" data-i18n="lp.step3_t">Broadcast Live</div><div class="step-desc" data-i18n="lp.step3_d">Send emergency codes and pages instantly across all hospital speakers</div></div></div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div class="footer-brand">
          <img src="https://pbs.twimg.com/profile_images/1727685869367144448/3hqKnJs9_400x400.jpg" alt="Logo">
          <span>King Khalid Hospital</span>
        </div>
        <p class="footer-desc" data-i18n="lp.footer_desc">Internal Announcement &amp; Paging System — Ministry of Health · Kingdom of Saudi Arabia</p>
        <div>
          <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-btn"><i class="fas fa-globe"></i></a>
          <a href="#" class="social-btn"><i class="fas fa-envelope"></i></a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="footer-h" data-i18n="lp.footer_system">System</div>
        <a href="#features" class="footer-link" data-i18n="lp.footer_features">Features</a>
        <a href="#how" class="footer-link" data-i18n="lp.footer_how">How It Works</a>
        <a href="#codes" class="footer-link" data-i18n="lp.footer_codes">Emergency Codes</a>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="footer-h" data-i18n="lp.footer_account">Account</div>
        <a href="login.php" class="footer-link" data-i18n="lp.footer_signin">Sign In</a>
        <a href="register.php" class="footer-link" data-i18n="lp.footer_register">Register</a>
      </div>
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="footer-h" data-i18n="lp.footer_support">Support</div>
        <a href="#" class="footer-link" data-i18n="lp.footer_privacy">Privacy Policy</a>
        <a href="#" class="footer-link" data-i18n="lp.footer_terms">Terms of Use</a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; <span class="current-year"></span> King Khalid Hospital — Hail &middot; Hospital Call System v3.0 &middot; All Rights Reserved
      <div class="dev-footer-wrap">
        <span class="dev-footer-label"><i class="fas fa-laptop-code"></i> Developed &amp; Implemented by</span>
        <span class="dev-footer-name"><i class="fas fa-user-astronaut"></i> MANAR AOAD ALSHAM MARI</span>
        <div class="dev-footer-icons">
          <a href="mailto:mnarty80@gmail.com" class="dev-icon-btn dev-email-btn" title="mnarty80@gmail.com">
            <i class="fas fa-envelope"></i>
          </a>
          <a href="https://wa.me/966582046494" target="_blank" rel="noopener" class="dev-icon-btn dev-wa-btn" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- BOTTOM NAV -->
<nav class="bottom-nav" aria-label="Mobile navigation">
  <div class="bnav-bar">
    <svg class="bnav-notch-svg" viewBox="0 0 100 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M0 32 L0 0 Q10 0 15 4 Q28 32 50 32 Q72 32 85 4 Q90 0 100 0 L100 32 Z" fill="#141c4a"/>
    </svg>
    <a href="#" class="bnav-item active" data-section="home">
      <div class="bnav-icon"><i class="fas fa-home"></i></div>
      <span class="bnav-label" data-i18n="lp.bnav_home">Home</span>
    </a>
    <a href="#features" class="bnav-item" data-section="features">
      <div class="bnav-icon"><i class="fas fa-star"></i></div>
      <span class="bnav-label" data-i18n="lp.bnav_features">Features</span>
    </a>
    <span class="bnav-item bnav-spacer" aria-hidden="true"></span>
    <a href="#codes" class="bnav-item" data-section="codes">
      <div class="bnav-icon"><i class="fas fa-bolt"></i></div>
      <span class="bnav-label" data-i18n="lp.bnav_codes">Codes</span>
    </a>
    <a href="register.php" class="bnav-item">
      <div class="bnav-icon"><i class="fas fa-user-plus"></i></div>
      <span class="bnav-label" data-i18n="lp.bnav_register">Register</span>
    </a>
    <a href="login.php" class="bnav-fab" aria-label="Launch System">
      <i class="fas fa-rocket"></i>
    </a>
    <span class="bnav-fab-label">Launch</span>
  </div>
</nav>

<!-- CHATBOT WIDGET -->
<button class="chatbot-fab" id="chatBotBtn" title="Help Assistant / مساعد الدعم" aria-label="Open Help Assistant">
    <img class="chatbot-fab-logo chatbot-fab-icon" src="assets/images/logo-transparent.png" alt="Khalid">
    <i class="fas fa-times chatbot-fab-close"></i>
    <span class="chatbot-badge" id="chatBadge">1</span>
</button>

<div class="chatbot-panel" id="chatBotPanel" role="dialog" aria-modal="true" aria-label="Help Assistant">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar">
                <img class="chatbot-avatar-logo" src="assets/images/logo-transparent.png" alt="Khalid">
                <span class="chatbot-online-dot"></span>
            </div>
            <div>
                <div class="chatbot-name">Khalid</div>
                <div class="chatbot-status">KKH Support Assistant • مساعد الدعم</div>
            </div>
        </div>
        <button class="chatbot-close-btn" id="chatCloseBtn" aria-label="Close chat">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="chatbot-messages" id="chatMessages"></div>
    <div class="chatbot-input-area">
        <input type="text" class="chatbot-input" id="chatInput"
               placeholder="Ask a question / اسأل سؤالاً…"
               aria-label="Type your message" maxlength="200" autocomplete="off">
        <button class="chatbot-send-btn" id="chatSendBtn" aria-label="Send">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/js/i18n.js"></script>
<script src="assets/js/themes.js"></script>
<script src="assets/js/chatbot.js"></script>
<script>
  // ── Landing page language toggle ──────────────────────────────
  function lpToggleLang() {
    if (typeof toggleLang === 'function') {
      toggleLang();
    } else {
      var cur = localStorage.getItem('hcs_lang') || 'en';
      var next = cur === 'en' ? 'ar' : 'en';
      localStorage.setItem('hcs_lang', next);
      location.reload();
    }
  }

  // Init on load
  document.addEventListener('DOMContentLoaded', function () {
    var yearEl = document.querySelector('.current-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    if (sessionStorage.getItem('hcs_user')) {
      window.location.href = 'dashboard.php';
      return;
    }

    // Apply saved language using shared i18n if available
    if (typeof setLang === 'function') {
      var savedLang = localStorage.getItem('hcs_lang') || 'en';
      setLang(savedLang);
    }
  });

  // Navbar scroll effect
  window.addEventListener('scroll', function () {
    document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
  });

  // Bottom nav active on scroll
  var sections = document.querySelectorAll('section[id]');
  var bnavItems = document.querySelectorAll('.bnav-item[data-section]');
  window.addEventListener('scroll', function () {
    var current = '';
    sections.forEach(function (s) {
      if (window.scrollY >= s.offsetTop - 180) current = s.id;
    });
    bnavItems.forEach(function (item) {
      item.classList.remove('active');
      if (item.dataset.section === current || (!current && item.dataset.section === 'home')) {
        item.classList.add('active');
      }
    });
  });

  // Close navbar on link click (mobile)
  document.querySelectorAll('.navbar-nav .nav-link, .nav-actions a').forEach(function (link) {
    link.addEventListener('click', function () {
      var collapse = document.getElementById('navMenu');
      if (collapse.classList.contains('show')) {
        var bsCollapse = bootstrap.Collapse.getInstance(collapse);
        if (bsCollapse) bsCollapse.hide();
      }
    });
  });
</script>

</body>
</html>

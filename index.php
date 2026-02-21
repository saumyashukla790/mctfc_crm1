<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = isset($_POST['username']) ? trim($_POST['username']) : '';
  $p = isset($_POST['password']) ? trim($_POST['password']) : '';
  if ($u !== '' && $p !== '') {
    $_SESSION['username'] = $u;
    header('Location: dashboard.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title id="mainTitle">Integrated Call Center MoHFW</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&family=Noto+Sans+Devanagari:wght@400;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --accent:       #034948;
  --accent2:      #165668;
  --text:         #061829;
  --muted:        #10374A;
  --card-bg:      rgba(255,255,255,0.9);
  --card-border:  rgba(3,73,72,0.15);
  --card-shadow:  0 0 0 1px rgba(3,73,72,0.08), 0 32px 80px rgba(6,24,41,0.13), inset 0 1px 0 rgba(255,255,255,0.98);
  --input-bg:     rgba(232,244,245,0.75);
  --input-border: rgba(3,73,72,0.18);
  --divider:      rgba(3,73,72,0.12);
  --badge-bg:     rgba(3,73,72,0.08);
  --logo-shadow:  rgba(3,73,72,0.28);
  --grid:         rgba(3,73,72,0.055);
  --orb1:         rgba(22,86,104,0.13);
  --orb2:         rgba(3,73,72,0.09);
}

body {
  font-family: 'Segoe UI', sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

/* ── BACKGROUND ── */
.bg {
  position: fixed; inset: 0; z-index: 0;
  background:
    radial-gradient(ellipse 75% 55% at 8% 12%, rgba(22,86,104,0.13) 0%, transparent 55%),
    radial-gradient(ellipse 55% 50% at 90% 85%, rgba(3,73,72,0.10) 0%, transparent 55%),
    linear-gradient(150deg, #d2eaec 0%, #e8f4f5 45%, #cde6e8 100%);
}

.grid-lines {
  position: fixed; inset: 0; z-index: 0;
  background-image:
    linear-gradient(var(--grid) 1px, transparent 1px),
    linear-gradient(90deg, var(--grid) 1px, transparent 1px);
  background-size: 50px 50px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
}

.orb {
  position: fixed; border-radius: 50%; filter: blur(90px); z-index: 0;
  animation: drift 16s ease-in-out infinite alternate;
}
.orb1 { width: 400px; height: 400px; background: var(--orb1); top: -110px; left: -110px; }
.orb2 { width: 320px; height: 320px; background: var(--orb2); bottom: -90px; right: -90px; animation-delay: -8s; }

@keyframes drift {
  from { transform: translate(0,0) scale(1); }
  to   { transform: translate(28px, 18px) scale(1.07); }
}

/* ── CARD ── */
.card {
  position: relative; z-index: 10;
  width: 500px;
  padding: 44px 40px 40px;
  border-radius: 26px;
  border: 1px solid var(--card-border);
  background: var(--card-bg);
  box-shadow: var(--card-shadow);
  backdrop-filter: blur(30px);
  -webkit-backdrop-filter: blur(30px);
  animation: rise .7s cubic-bezier(.22,1,.36,1) both;
}

@keyframes rise {
  from { opacity: 0; transform: translateY(30px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ── LOGO ── */
.logo-bar { display: flex; align-items: center; gap: 13px; margin-bottom: 28px; }
.logo-icon {
  width: 60px; height: 60px; border-radius: 13px; flex-shrink: 0;
  background: linear-gradient(135deg, #165668, #034948);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 0 22px var(--logo-shadow);
}
.logo-icon svg { width: 22px; height: 22px; fill: white; }
.logo-text h1 { font-size: 19.5px; font-weight: 700; color: var(--text); line-height: 1.35; }
.logo-text p  { font-size: 14px; font-weight: 300; color: var(--muted); letter-spacing: 0.04em; text-transform: uppercase; margin-top: 2px; }

/* ── DIVIDER ── */
.divider {
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--divider), transparent);
  margin-bottom: 26px;
}

/* ── LANG ROW ── */
.lang-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
.lang-label { font-size: 12px; color: var(--muted); letter-spacing: 0.07em; text-transform: uppercase; font-weight: 500; }

.lang-pills {
  display: flex;
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  border-radius: 50px; padding: 3px; gap: 2px;
}
.lang-pills input { display: none; }
.lang-pills label {
  padding: 5px 16px; border-radius: 50px;
  font-size: 12px; font-weight: 600; color: var(--muted);
  cursor: pointer; transition: all .2s; user-select: none;
}
#en:checked + label, #hi:checked + label {
  background: linear-gradient(135deg, #165668, #034948);
  color: white;
  box-shadow: 0 2px 10px var(--logo-shadow);
}

/* ── SECTION LABEL ── */
.section-label {
  font-size: 14px; color: var(--muted);
  text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;font-weight: 600;
}

/* ── FIELDS ── */
.field { position: relative; margin-bottom: 14px; }
.field-icon {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  color: var(--muted); pointer-events: none; transition: color .2s;
}
.field-icon svg { width: 15px; height: 15px; display: block; }

.field input {
  width: 100%; padding: 13px 14px 13px 41px;
  background: var(--input-bg); border: 1px solid var(--input-border);
  border-radius: 12px; color: var(--text);
  font-size: 13.5px; font-family: 'Sora', sans-serif; outline: none;
  transition: border-color .2s, box-shadow .2s, background .2s;
}
.field input::placeholder { color: #6a9ea8; font-size: 13px; }
.field input:focus {
  border-color: var(--accent);
  background: rgba(3,73,72,0.04);
  box-shadow: 0 0 0 3px rgba(3,73,72,0.1);
}
.field input:focus + .field-icon { color: var(--accent); }

/* ── EXTRAS ── */
.extras { display: flex; justify-content: flex-end; margin: -4px 0 20px; }
.extras a { font-size: 12px; color: var(--accent); text-decoration: none; opacity: .85; transition: opacity .2s; }
.extras a:hover { opacity: 1; }

/* ── BUTTON ── */
.btn {
  width: 100%; padding: 14px; border: none; border-radius: 13px;
  background: linear-gradient(135deg, #165668, #034948);
  color: white; font-size: 15px; font-weight: 700;
  font-family: 'Sora', sans-serif; letter-spacing: 0.02em;
  cursor: pointer; position: relative; overflow: hidden;
  box-shadow: 0 4px 22px var(--logo-shadow);
  transition: transform .18s, box-shadow .18s;
}
.btn:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(3,73,72,0.38); }
.btn:active { transform: translateY(0); }
.btn::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(255,255,255,0.12) 0%, transparent 100%);
  pointer-events: none;
}


/* ── RESPONSIVE ── */
@media(max-width: 460px) {
  .card { width: 92vw; padding: 32px 22px 28px; }
}

.hindi { font-family: 'Noto Sans Devanagari', sans-serif; }
</style>
</head>
<body>

<div class="bg"></div>
<div class="grid-lines"></div>
<div class="orb orb1"></div>
<div class="orb orb2"></div>

<div class="card">

  <!-- LOGO -->
  <div class="logo-bar">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M6.5 10h-2v9h2v-9zm5.5 0h-2v9h2v-9zm7 0h-2v9h2v-9zM2 19h20v2H2v-2zM12 1L2 6v2h20V6L12 1z"/></svg>
    </div>
    <div class="logo-text">
      <h1 id="mainTitle">Integrated Call Center MoHFW</h1>
      <p id="subtitle">Ministry of Health & Family Welfare</p>
    </div>
  </div>

  <div class="divider"></div>

  <!-- LANGUAGE TOGGLE -->
  <div class="lang-row">
    <span class="lang-label" id="langLabel">Language</span>
    <div class="lang-pills">
      <input type="radio" name="lang" id="en" checked>
      <label for="en">EN</label>
      <input type="radio" name="lang" id="hi">
      <label for="hi">हि</label>
    </div>
  </div>

  <p class="section-label" id="formLabel">Login to your account</p>

  <form action="login.php" method="POST">

    <div class="field">
      <span class="field-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
      </span>
      <input type="text" id="emailInput" name="username" placeholder="Username" required>
    </div>

    <div class="field">
      <span class="field-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </span>
      <input type="password" id="passInput" name="password" placeholder="Password" required>
    </div>

    <button class="btn" id="loginBtn">Sign In</button>

  </form>

</div>

<script>
const strings = {
  en: {
    mainTitle: "Integrated Call Center — MoHFW",
    subtitle:  "Ministry of Health & Family Welfare",
    langLabel: "Language",
    formLabel: "Login to your account",
    email:     "Username",
    password:  "Password",
    loginBtn:  "Sign In",
  },
  hi: {
    mainTitle: "इंटीग्रेटेड कॉल सेंटर - स्वास्थ्य मंत्रालय",
    subtitle:  "स्वास्थ्य और परिवार कल्याण मंत्रालय",
    langLabel: "भाषा",
    formLabel: "अपने खाते में लॉगिन करें",
    email:     "उपयोगकर्ता नाम",
    password:  "पासवर्ड",
    loginBtn:  "लॉगिन",
  }
};

function applyLang(lang) {

  const s = strings[lang];
  const isHi = lang === 'hi';

  document.getElementById('mainTitle').textContent  = s.mainTitle;
  document.getElementById('subtitle').textContent   = s.subtitle;
  document.getElementById('langLabel').textContent  = s.langLabel;
  document.getElementById('formLabel').textContent  = s.formLabel;
  document.getElementById('emailInput').placeholder = s.email;
  document.getElementById('passInput').placeholder  = s.password;
  document.getElementById('loginBtn').textContent   = s.loginBtn;

  /* APPLY FONT TO WHOLE CARD */
  document.querySelector('.card').classList.toggle('hindi', isHi);

}


document.getElementById('en').addEventListener('change', () => applyLang('en'));
document.getElementById('hi').addEventListener('change', () => applyLang('hi'));
</script>

</body>
</html>
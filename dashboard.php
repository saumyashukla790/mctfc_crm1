<!DOCTYPE html>
<html lang="en">
<head>
<title>Integrated Call Center MoHFW</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --rail-w:     64px;
  --panel-w:    260px;
  --nav-h:      100px;
  --t900: #061829;
  --t800: #10374A;
  --t700: #165668;
  --t500: #034948;
  --t400: #005B5C;
  --t100: #d0ecec;
  --t50:  #edf6f6;
  --bg:        #f0f5f6;
  --surface:   #ffffff;
  --border:    rgba(6,24,41,0.08);
  --text:      #061829;
  --muted:     #7a9faa;
  --sh1: 0 2px 12px rgba(6,24,41,0.07);
  --sh2: 0 8px 32px rgba(6,24,41,0.13);
}

html, body { height: 100%; overflow: hidden; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text); display: flex; flex-direction: column; }

/* ══ RAIL ══ */
.rail {
  width: var(--rail-w);
  height: 100vh;
  position: fixed;
  left: 0; top: 0;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 12px 0;
  z-index: 600;
  box-shadow: var(--sh1);
}
.rail-brand {
  width: 40px; height: 40px;
  background: linear-gradient(135deg, #005B5C, #165668);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 16px;
  box-shadow: 0 4px 14px rgba(0,91,92,0.4);
  cursor: pointer;
  flex-shrink: 0;
}
.rail-brand svg { width: 20px; height: 20px; fill: white; }
.rail-btn {
  width: 44px; height: 44px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--muted);
  margin: 2px 0;
  position: relative;
  transition: background 0.15s, color 0.15s;
  flex-shrink: 0;
}
.rail-btn:hover { background: var(--t50); color: var(--t500); }
.rail-btn.active { background: var(--t50); color: var(--t500); }
.rail-btn svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.rail-btn.active::before {
  content: '';
  position: absolute;
  left: -1px; top: 25%; bottom: 25%;
  width: 3px;
  background: var(--t400);
  border-radius: 0 3px 3px 0;
}
.rail-btn::after {
  content: attr(data-tip);
  position: absolute;
  left: calc(100% + 10px);
  background: var(--t900);
  color: white;
  font-size: 11.5px; font-weight: 600;
  padding: 5px 10px; border-radius: 8px;
  white-space: nowrap;
  opacity: 0; pointer-events: none;
  transform: translateX(-4px);
  transition: opacity 0.15s, transform 0.15s;
  z-index: 9999;
  box-shadow: 0 4px 14px rgba(6,24,41,0.2);
}
.rail-btn:hover::after { opacity: 1; transform: translateX(0); }
.rail-bottom { margin-top: auto; display: flex; flex-direction: column; align-items: center; gap: 2px; }
.rail-sep { width: 32px; height: 1px; background: var(--border); margin: 8px 0; }

/* ══ PANEL ══ */
.panel {
  width: var(--panel-w);
  height: 100vh;
  position: fixed;
  left: var(--rail-w);
  top: 0;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  z-index: 590;
  transform: translateX(calc(-100% - 4px));
  transition: transform 0.28s cubic-bezier(.4,0,.2,1);
  box-shadow: var(--sh2);
  overflow: hidden;
}
.panel.open { transform: translateX(0); }

/* Panel header — dark teal matching screenshot */
.panel-header {
  background: linear-gradient(160deg, #0d3547 0%, #10374A 60%, #165668 100%);
  padding: 20px 18px 18px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}
.panel-header::before {
  content: '';
  position: absolute;
  top: -30px; right: -30px;
  width: 120px; height: 120px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
}
.panel-header::after {
  content: '';
  position: absolute;
  bottom: -20px; left: 40px;
  width: 80px; height: 80px;
  border-radius: 50%;
  background: rgba(255,255,255,0.03);
}

.ph-top {
  display: flex;
  align-items: center;
  gap: 12px;
  position: relative;
  z-index: 1;
}
.ph-emblem {
  width: 46px; height: 46px;
  border-radius: 50%;
  background: white;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(0,0,0,0.25);
  overflow: hidden;
  font-size: 9px;
  font-weight: 800;
  color: #034948;
  letter-spacing: 0;
  padding: 2px;
}
.ph-emblem img { width: 100%; height: 100%;}
/* Fallback Ashoka wheel SVG */
.ph-emblem-fallback {
  width: 38px; height: 38px;
}

.ph-titles {
  display: flex;
  flex-direction: column;
  gap: 1px;
}
.ph-main { font-size: 17px; font-weight: 800; color: white; letter-spacing: -0.01em; line-height: 1.1; }
.ph-sub  { font-size: 11px; font-weight: 400; color: white; font-family: 'Outfit', sans-serif; }

.ph-divider {
  height: 1px;
     background: #cdc5c5;
  margin: 0 -18px;
  position: relative; z-index: 1;
}

.ph-bottom {
  position: relative; z-index: 1;
}
.ph-ministry { font-size: 10.5px; font-weight: 700; color: white; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 2px; }
.ph-dept     { font-size: 10px; font-weight: 400; color: white; }
.ph-dept-hi  { font-size: 10px; font-weight: 400; color: white; margin-top: 1px; }

/* Panel body */
.panel-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 14px 0 16px;
  scrollbar-width: thin;
  scrollbar-color: rgba(3,73,72,0.1) transparent;
}
.panel-body::-webkit-scrollbar { width: 3px; }
.panel-body::-webkit-scrollbar-thumb { background: rgba(3,73,72,0.12); border-radius: 4px; }

.pnl-section {
  font-size: 10px; font-weight: 700;
  letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--muted);
  padding: 10px 18px 6px;
}

.pnl-link {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 18px;
  font-size: 13px; font-weight: 500;
  color: #3a5a6a;
  text-decoration: none; cursor: pointer;
  transition: background 0.13s, color 0.13s;
  position: relative;
  border-radius: 0;
}
.pnl-link:hover { background: var(--t50); color: var(--t500); }
.pnl-link.active {
  background: var(--t50);
  color: var(--t500);
  font-weight: 600;
}
.pnl-link.active::after {
  content: '';
  position: absolute; right: 0; top: 18%; bottom: 18%;
  width: 3px; background: var(--t400);
  border-radius: 3px 0 0 3px;
}
.pnl-icon {
  width: 22px; height: 22px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; color: var(--muted);
}
.pnl-link.active .pnl-icon,
.pnl-link:hover .pnl-icon { color: var(--t500); }
.pnl-icon svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

/* Backdrop */
.backdrop {
  display: none;
  position: fixed; inset: 0;
  background: rgba(6,24,41,0.35);
  z-index: 580;
  backdrop-filter: blur(2px);
}
.backdrop.show { display: block; }

/* ══ NAVBAR ══ */
.navbar {
  position: fixed;
  top: 0;
  left: var(--rail-w);
  right: 0;
  height: var(--nav-h);
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center;
  padding: 0 20px;
  gap: 12px;
  z-index: 500;
  box-shadow: 0 1px 0 var(--border);
  transition: left 0.28s cubic-bezier(.4,0,.2,1);
}
.nav-collapse {
  width: 34px; height: 34px;
  border: 1px solid var(--border);
  border-radius: 9px; background: var(--surface);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; flex-shrink: 0;
  color: var(--muted);
  transition: background 0.15s, color 0.15s;
}
.nav-collapse:hover { background: var(--t50); color: var(--t500); }
.nav-collapse svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; }

.nav-right { display: flex; align-items: center; gap: 6px; margin-left: auto; }

.nav-icon-btn {
  width: 36px; height: 36px;
  border: none; background: transparent;
  border-radius: 9px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: var(--muted); position: relative;
  transition: background 0.15s, color 0.15s;
}
.nav-icon-btn:hover { background: var(--t50); color: var(--t500); }
.nav-icon-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

.nav-user {
  display: flex; align-items: center; gap: 10px;
  padding: 5px 5px 5px 12px;
  border-radius: 10px; cursor: pointer;
  border: none; background: transparent;
  transition: background 0.15s;
  margin-left: 4px; position: relative;
}
.nav-user:hover { background: var(--t50); }
.nav-user-info { text-align: right; }
.nav-user-name { font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.2; }
.nav-user-role { font-size: 11px; color: var(--muted); }
.nav-user-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #165668, #034948);
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 12px; font-weight: 800;
  border: 2px solid rgba(3,73,72,0.15); flex-shrink: 0;
}

/* Dropdowns */
.dp-wrap { position: relative; }
.dp-menu {
  position: absolute; top: calc(100% + 8px); right: 0;
  background: white; border: 1px solid var(--border);
  border-radius: 16px; padding: 8px;
  box-shadow: var(--sh2); z-index: 700;
  display: none; min-width: 200px;
  animation: dpIn 0.18s ease;
}
.dp-menu.show { display: block; }
@keyframes dpIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.dp-hd { display:flex;align-items:center;gap:9px;padding:8px 8px 12px;border-bottom:1px solid var(--border);margin-bottom:4px; }
.dp-hd-av { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#165668,#034948);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:800; }
.dp-hd-n { font-size:13px;font-weight:700;color:var(--text); }
.dp-hd-e { font-size:11px;color:var(--muted); }
.dp-item { display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:9px;font-size:13px;color:var(--text);text-decoration:none;cursor:pointer;transition:background 0.12s; }
.dp-item:hover { background:var(--t50); }
.dp-item svg { width:14px;height:14px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round; }
.dp-sep { height:1px;background:var(--border);margin:4px 0; }
.dp-item.danger { color:#ef4444; }
.dp-item.danger svg { stroke:#ef4444; }

/* ══ MAIN ══ */
.main-wrap {
  margin-left: var(--rail-w);
  margin-top: var(--nav-h);
  flex: 1;
  height: calc(100vh - var(--nav-h));
  overflow-y: auto;
  transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
}
.page { padding: 24px; }

.breadcrumb { display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--muted);margin-bottom:20px; }
.breadcrumb svg { width:12px;height:12px;stroke:var(--muted);fill:none;stroke-width:2; }
.breadcrumb a { color:var(--muted);text-decoration:none; }
.breadcrumb .cur { color:var(--t500);font-weight:700; }

/* Stats */
.stats-row { display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px; }
.stat-card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: 16px; padding: 20px;
  box-shadow: var(--sh1); animation: riseIn 0.4s ease both;
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--sh2); }
.stat-card:nth-child(1){animation-delay:.05s}
.stat-card:nth-child(2){animation-delay:.10s}
.stat-card:nth-child(3){animation-delay:.15s}
.stat-card:nth-child(4){animation-delay:.20s}
@keyframes riseIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
.sc-top { display:flex;align-items:center;justify-content:space-between;margin-bottom:14px; }
.sc-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center; }
.sc-icon svg { width:21px;height:21px;stroke:white;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round; }
.sci1{background:linear-gradient(135deg,#165668,#034948);box-shadow:0 4px 14px rgba(3,73,72,0.28)}
.sci2{background:linear-gradient(135deg,#0891b2,#0e7490);box-shadow:0 4px 14px rgba(8,145,178,0.28)}
.sci3{background:linear-gradient(135deg,#059669,#047857);box-shadow:0 4px 14px rgba(5,150,105,0.28)}
.sci4{background:linear-gradient(135deg,#d97706,#b45309);box-shadow:0 4px 14px rgba(217,119,6,0.28)}
.sc-val { font-size:30px;font-weight:900;color:var(--text);letter-spacing:-0.04em;line-height:1;font-family:'JetBrains Mono',monospace; }
.sc-label { font-size:11.5px;color:var(--muted);font-weight:500;text-transform:uppercase;letter-spacing:0.04em;margin-top:5px; }
.sc-bar { height:4px;border-radius:50px;background:var(--border);margin-top:14px;overflow:hidden; }
.sc-bar-f { height:100%;border-radius:50px;background:linear-gradient(90deg,var(--t400),var(--t700));transition:width 1.3s cubic-bezier(.4,0,.2,1); }

/* Content grid */
.grid-2 { display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px; }

/* Charts row */
.charts-row { display:grid;grid-template-columns:3fr 2fr;gap:16px;margin-bottom:20px; }
.chart-wrap { position:relative; width:100%; }
.chart-wrap canvas { width:100% !important; }
.card {
  background:var(--surface);border:1px solid var(--border);
  border-radius:16px;box-shadow:var(--sh1);overflow:hidden;
  animation:riseIn 0.4s ease both;animation-delay:0.25s;
}
.card-hd { display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border); }
.card-hd h3 { font-size:14px;font-weight:700;color:var(--text); }
.card-hd p  { font-size:11px;color:var(--muted);margin-top:2px; }
.card-body { padding:16px 20px; }
.c-act { font-size:11.5px;font-weight:700;color:var(--t500);padding:5px 12px;background:var(--t50);border-radius:8px;border:1px solid rgba(0,91,92,0.1);text-decoration:none;cursor:pointer;transition:background 0.15s; }
.c-act:hover { background:var(--t100); }

/* Table */
.tbl { width:100%;border-collapse:collapse;font-size:12.5px; }
.tbl th { padding:9px 14px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;border-bottom:1px solid var(--border);background:rgba(237,246,247,0.5);white-space:nowrap; }
.tbl td { padding:11px 14px;border-bottom:1px solid rgba(3,73,72,0.05);vertical-align:middle; }
.tbl tr:last-child td { border-bottom:none; }
.tbl tr:hover td { background:rgba(237,246,247,0.4); }
.tbl .fw7 { font-weight:700;color:var(--text); }
.pill { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:50px; }
.pill::before { content:'';width:5px;height:5px;border-radius:50%;background:currentColor; }
.pill-g { background:rgba(5,150,105,0.1);color:#059669; }
.pill-y { background:rgba(217,119,6,0.1);color:#d97706; }
.pill-r { background:rgba(239,68,68,0.1);color:#ef4444; }

/* Agent list */
.agent-row { display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid rgba(3,73,72,0.05); }
.agent-row:last-child { border-bottom:none; }
.agent-av { width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700;flex-shrink:0; }
.agent-info { flex:1; }
.agent-name { font-size:12.5px;font-weight:600;color:var(--text); }
.agent-calls { font-size:11px;color:var(--muted); }
.agent-status { font-size:10px;font-weight:700;padding:2px 8px;border-radius:50px; }
.as-on  { background:rgba(5,150,105,0.1);color:#059669; }
.as-off { background:rgba(239,68,68,0.1);color:#ef4444; }

.footer { text-align:center;font-size:11.5px;color:var(--muted);padding:14px 0 6px;border-top:1px solid var(--border);margin-top:4px; }

@media(max-width:900px) { .stats-row{grid-template-columns:1fr 1fr} .grid-2{grid-template-columns:1fr} }
@media(max-width:480px) { .stats-row{grid-template-columns:1fr} }
</style>
</head>
<body>

<!-- ══ ICON RAIL ══ -->
<aside class="rail" id="rail">
  <div class="rail-brand" id="railBrand" title="MoHFW Portal">
    <svg viewBox="0 0 24 24"><path d="M6.5 10h-2v9h2v-9zm5.5 0h-2v9h2v-9zm7 0h-2v9h2v-9zM2 19h20v2H2v-2zM12 1L2 6v2h20V6L12 1z"/></svg>
  </div>

  <div class="rail-btn active" data-panel="dashboard" data-tip="Dashboard" onclick="togglePanel('dashboard', this)">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
  </div>
  <div class="rail-btn" data-tip="Email Tracking" onclick="togglePanel('email-tracking',this)">
    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
  </div>
  <div class="rail-btn" data-tip="Masters" onclick="togglePanel('masters',this)">
    <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
  </div>
  <div class="rail-btn" data-tip="Reports" onclick="togglePanel('reports',this)">
    <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
  </div>
  <div class="rail-btn" data-tip="SMS" onclick="togglePanel('sms',this)">
    <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  </div>

  <div class="rail-sep"></div>

  <div class="rail-bottom">
    <div class="rail-btn" data-tip="Add New">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
    </div>
    <div class="rail-btn" data-tip="Log Out">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    </div>
  </div>
</aside>

<!-- ══ SLIDE-OUT PANEL ══ -->
<div class="backdrop" id="backdrop" onclick="closePanel()"></div>

<div class="panel" id="sidePanel">

  <!-- Header — dark teal matching screenshot -->
  <div class="panel-header">
    <div class="ph-top">
      <!-- Ashoka Chakra / National Emblem placeholder -->
      <div class="ph-emblem">
        <img src="image/NRHM-logo.jpg" alt="National Emblem">
      </div>
      <div class="ph-titles">
        <div class="ph-main">MoHFW</div>
        <div class="ph-sub" lang="hi">स्वास्थ्य एवं परिवार कल्याण</div>
      </div>
    </div>

    <div class="ph-divider"></div>

    <div class="ph-bottom">
      <div class="ph-ministry">Ministry of Health &amp; Family Welfare</div>
      <div class="ph-dept">Department of Health &amp; Family Welfare</div>
      <div class="ph-dept-hi" lang="hi">भारत सरकार · Government of India</div>
    </div>
  </div>

  <!-- Nav -->
  <div class="panel-body">
    <div class="pnl-section">Dashboard</div>
    <a class="pnl-link active" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
      Default Dashboard
    </a>
    <!-- <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
      Sales Dashboard
    </a>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
      Finance Dashboard
    </a> -->

    <div class="pnl-section" style="margin-top:8px;">Call Management</div>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.54a16 16 0 0 0 6.55 6.55l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
      Call Logs
    </a>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg></span>
      Agent Management
    </a>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
      SMS Tracking
    </a>

    <div class="pnl-section" style="margin-top:8px;">Reports &amp; Verification</div>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></span>
      Form Verification
    </a>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
      Login / Logout Summary
    </a>
    <a class="pnl-link" href="#" onclick="setPnlActive(this);return false;">
      <span class="pnl-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></span>
      Performance Reports
    </a>
  </div>
</div>

<!-- ══ NAVBAR ══ -->
<nav class="navbar" id="navbar">
  <button class="nav-collapse" id="collapseBtn" title="Toggle menu" onclick="togglePanelBtn()">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/><polyline points="11 18 5 12 11 6"/></svg>
  </button>

  <div style="display:flex; align-items:center; margin-inline-start:20px; gap:12px;">
    <!-- National Emblem Image -->
    <div style="width:75px;height:85px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
      <img src="image/download.png" alt="National Emblem of India" style="height:82px;width:auto;object-fit:contain;">
    </div>
    <div style="display:flex; flex-direction:column; line-height:1.28; font-family:'Outfit',sans-serif;">
      <span style="font-size:11.5px; font-weight:500; color:#555;" lang="hi">स्वास्थ्य एवं परिवार कल्याण मंत्रालय</span>
      <span style="font-size:15px; font-weight:800; color:#061829; letter-spacing:0.02em;" lang="en">MINISTRY OF HEALTH &amp; FAMILY WELFARE</span>
      <span style="font-size:10.5px; font-weight:400; color:#777;" lang="hi">स्वास्थ्य एवं परिवार कल्याण विभाग</span>
      <span style="font-size:12.5px; font-weight:600; color:#444; letter-spacing:0.02em;" lang="en">DEPARTMENT OF HEALTH &amp; FAMILY WELFARE</span>
    </div>
  </div>

  <div class="nav-right">
    <div class="dp-wrap">
      <button class="nav-user" id="userBtn">
        <div class="nav-user-info">
          <div class="nav-user-name">Admin User</div>
          <div class="nav-user-role">Super Admin</div>
        </div>
        <div class="nav-user-avatar">AU</div>
      </button>
      <div class="dp-menu" id="userMenu" style="min-width:240px">
        <div class="dp-hd">
          <div class="dp-hd-av">AU</div>
          <div>
            <div class="dp-hd-n">Admin User</div>
            <div class="dp-hd-e">Super Admin</div>
          </div>
        </div>
        <a class="dp-item" href="#">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          View Profile
        </a>
        <div class="dp-sep"></div>
        <a class="dp-item danger" href="#">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Log Out
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- ══ MAIN CONTENT ══ -->
<div class="main-wrap">
  <div class="page">

    <div class="breadcrumb">
      <a href="#">Home</a>
      <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
      <span class="cur">Dashboard</span>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
      <!-- Total Calls -->
      <div class="stat-card">
        <div class="sc-top">
          <div class="sc-icon sci1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.54a16 16 0 0 0 6.55 6.55l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
          </div>
       
        </div>
        <div class="sc-val">5,758</div>
        <div class="sc-label">Total Calls</div>
        <div class="sc-bar"><div class="sc-bar-f" style="width:68%"></div></div>
      </div>

      <!-- Answered Calls -->
      <div class="stat-card">
        <div class="sc-top">
          <div class="sc-icon sci2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="16 2 16 8 22 8"/><line x1="23" y1="1" x2="16" y2="8"/>
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.54a16 16 0 0 0 6.55 6.55l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
          </div>
          
        </div>
        <div class="sc-val">1,070</div>
        <div class="sc-label">Answered Calls</div>
        <div class="sc-bar"><div class="sc-bar-f" style="width:35%;background:linear-gradient(90deg,#0891b2,#0e7490)"></div></div>
      </div>

      <!-- Unanswered Calls -->
      <div class="stat-card">
        <div class="sc-top">
          <div class="sc-icon sci3">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="23" y1="1" x2="17" y2="7"/><line x1="17" y1="1" x2="23" y2="7"/>
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.54a16 16 0 0 0 6.55 6.55l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
          </div>
         
        </div>
        <div class="sc-val">80</div>
        <div class="sc-label">Unanswered Calls</div>
        <div class="sc-bar"><div class="sc-bar-f" style="width:14%;background:linear-gradient(90deg,#059669,#047857)"></div></div>
      </div>

      <!-- Agents Online -->
      <div class="stat-card">
        <div class="sc-top">
          <div class="sc-icon sci4">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
              <polyline points="16 11 18 13 22 9"/>
            </svg>
          </div>
        </div>
        <div class="sc-val">225</div>
        <div class="sc-label">Agents Online</div>
        <div class="sc-bar"><div class="sc-bar-f" style="width:80%;background:linear-gradient(90deg,#d97706,#b45309)"></div></div>
      </div>
    </div>


    <!-- Charts Row -->
    <div class="charts-row">

      <!-- Bar Chart: State-wise Call Records -->
      <div class="card" style="animation-delay:0.3s;">
        <div class="card-hd">
          <div>
            <h3>State Wise Call(s) Records</h3>
            <p>Monthly · Answered Calls by State</p>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#7a9faa;">
              <span style="width:10px;height:10px;border-radius:2px;background:#5bc4e0;display:inline-block;"></span>
              Answered Call(s)
            </span>
            <a class="c-act" href="#">Export</a>
          </div>
        </div>
        <div class="card-body" style="padding:16px 16px 10px;">
          <div class="chart-wrap" style="height:280px;">
            <canvas id="stateChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Line Chart: Agent Work Hours -->
      <div class="card" style="animation-delay:0.35s;">
        <div class="card-hd">
          <div>
            <h3>Agent Work Hour(s)</h3>
            <p>January · Daily Work Count</p>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#7a9faa;">
              <span style="width:16px;height:2px;background:#5bc4e0;display:inline-block;border-radius:2px;"></span>
              Work (s)
            </span>
            <a class="c-act" href="#">Export</a>
          </div>
        </div>
        <div class="card-body" style="padding:16px 16px 10px;">
          <div class="chart-wrap" style="height:280px;">
            <canvas id="agentWorkChart"></canvas>
          </div>
        </div>
      </div>

    </div>

    <div class="footer">Copyright © 2026 Vision Plus Security Control Limited</div>
  </div>
</div>

<script>
let panelOpen = false;

function togglePanel(name, btn) {
  const panel = document.getElementById('sidePanel');
  const backdrop = document.getElementById('backdrop');
  if (panelOpen && btn && btn.classList.contains('active')) { closePanel(); return; }
  document.querySelectorAll('.rail-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  panel.classList.add('open');
  backdrop.classList.add('show');
  panelOpen = true;
}

function closePanel() {
  document.getElementById('sidePanel').classList.remove('open');
  document.getElementById('backdrop').classList.remove('show');
  panelOpen = false;
}

function togglePanelBtn() {
  if (panelOpen) closePanel();
  else togglePanel('dashboard', document.querySelector('.rail-btn.active'));
}

document.getElementById('railBrand').addEventListener('click', () => {
  togglePanel('dashboard', document.querySelector('.rail-btn[data-panel="dashboard"]'));
});

function setPnlActive(el) {
  document.querySelectorAll('.pnl-link').forEach(l => l.classList.remove('active'));
  el.classList.add('active');
}

// Animate bars on load
setTimeout(() => {
  document.querySelectorAll('.sc-bar-f').forEach(el => {
    const w = el.style.width;
    el.style.width = '0%';
    requestAnimationFrame(() => requestAnimationFrame(() => { el.style.width = w; }));
  });
}, 300);

// User dropdown
const userBtn = document.getElementById("userBtn");
const userMenu = document.getElementById("userMenu");
userBtn.addEventListener("click", (e) => { e.stopPropagation(); userMenu.classList.toggle("show"); });
document.addEventListener("click", () => { userMenu.classList.remove("show"); });

// ── CHART DEFAULTS ──
Chart.defaults.font.family = "'Outfit', sans-serif";
Chart.defaults.color = '#7a9faa';

// ── STATE-WISE BAR CHART ──
const stateLabels = [
  'Andaman Nicobar','Arunachal Pradesh','Bihar','Chhattisgarh','Delhi',
  'Gujarat','Himachal Pradesh','Jharkhand','Kerala','Lakshadweep',
  'Maharashtra','Meghalaya','Nagaland','Puducherry','Rajasthan',
  'Tamil Nadu','Tripura','Uttarakhand'
];
const stateData = [
  0, 920, 860, 980, 490, 340, 1010, 620, 590, 80,
  1730, 0, 30, 720, 680, 310, 0, 12540, 720
];

new Chart(document.getElementById('stateChart'), {
  type: 'bar',
  data: {
    labels: stateLabels,
    datasets: [{
      label: 'Answered Call(s)',
      data: stateData,
      backgroundColor: 'rgba(91,196,224,0.75)',
      borderColor: 'rgba(91,196,224,1)',
      borderWidth: 1,
      borderRadius: 4,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#061829',
        titleColor: '#fff',
        bodyColor: 'rgba(255,255,255,0.75)',
        padding: 10,
        borderRadius: 8,
        callbacks: {
          label: ctx => ` ${ctx.parsed.y.toLocaleString()} calls`
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          font: { size: 9.5, family: "'Outfit', sans-serif" },
          color: '#7a9faa',
          maxRotation: 45,
          minRotation: 40
        },
        border: { color: 'rgba(6,24,41,0.08)' }
      },
      y: {
        grid: { color: 'rgba(6,24,41,0.05)', lineWidth: 1 },
        border: { dash: [4, 4], color: 'transparent' },
        ticks: {
          font: { size: 10, family: "'JetBrains Mono', monospace" },
          color: '#7a9faa',
          callback: v => v >= 1000 ? (v/1000).toFixed(1)+'k' : v
        }
      }
    }
  }
});

// ── AGENT WORK HOURS LINE CHART ──
const days = Array.from({length:31}, (_,i) => i+1);
const workData = [
  0,0,0,0,0,0,0,0,0,0,0,0,
  15.5, 5.3, 24.7, 23.8, 8.3, 0.1,
  0,0,0,0,0,0,0,0,0,0,0,0,0
];

new Chart(document.getElementById('agentWorkChart'), {
  type: 'line',
  data: {
    labels: days,
    datasets: [{
      label: 'Work (s)',
      data: workData,
      borderColor: '#5bc4e0',
      backgroundColor: 'rgba(91,196,224,0.08)',
      borderWidth: 2,
      pointRadius: 4,
      pointBackgroundColor: '#5bc4e0',
      pointBorderColor: '#fff',
      pointBorderWidth: 1.5,
      pointHoverRadius: 6,
      tension: 0.2,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#061829',
        titleColor: '#fff',
        bodyColor: 'rgba(255,255,255,0.75)',
        padding: 10,
        borderRadius: 8,
        callbacks: {
          title: ctx => `Day ${ctx[0].label}`,
          label: ctx => ` ${ctx.parsed.y} work count`
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          font: { size: 9.5, family: "'JetBrains Mono', monospace" },
          color: '#7a9faa',
          maxTicksLimit: 16
        },
        border: { color: 'rgba(6,24,41,0.08)' }
      },
      y: {
        min: 0,
        max: 30,
        grid: { color: 'rgba(6,24,41,0.05)', lineWidth: 1 },
        border: { dash: [4,4], color: 'transparent' },
        ticks: {
          stepSize: 5,
          font: { size: 10, family: "'JetBrains Mono', monospace" },
          color: '#7a9faa'
        }
      }
    }
  }
});
</script>
</body>
</html>
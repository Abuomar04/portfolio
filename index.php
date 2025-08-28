<?php 
$projects = json_decode(file_get_contents('projects.json'), true);

// Resume path + cache-buster
$cvPath = 'Documentation/Mostafa El Badawi El Najjar- CV 2025.pdf';
$cvVer  = file_exists($cvPath) ? filemtime($cvPath) : time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mostafa Najjar Portfolio - MEP Engineer</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Lora:400,700|Roboto+Slab:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

  <!-- Inter + Geist (Ahmad-style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Dark mode + layout tweaks -->
  <style>
    :root{
      --bg:#0b1220;
      --surface:#121826;
      --elev:#1f2937;
      --card:#0f172a;
      --text:#e5e7eb;
      --muted:#9ca3af;
      --primary:#60a5fa;
      --primary-2:#3b82f6;
      --accent:#8b5cf6;
      --border:#263045;
      --shadow:0 10px 25px rgba(0,0,0,.45);
    }

    html,body{ background:var(--bg) !important; color:var(--text) !important; }
    img{ filter:none !important; }

    /* Header / Nav (desktop look kept) */
    header{
      position: sticky; top: 0; z-index: 10000;
      padding: 10px 0; background: transparent !important;
      backdrop-filter: blur(8px);
    }
    .nav{
      display:flex; justify-content:center; align-items:center;
      pointer-events:auto; z-index:10001;
    }
    .nav__list{
      display:flex; align-items:center; gap:10px;
      padding:8px 12px; border-radius:999px;
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.12);
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
      list-style:none; margin:0;
      
    }
    .nav__link{
      color: var(--text) !important;
      font-weight: 600; text-decoration:none;
      padding: 9px 14px; border-radius: 999px;
      transition: background .2s ease, color .2s ease, transform .2s ease;
      line-height: 1;
    }
    .nav__link:hover, .nav__link.is-active{
      color:#061018 !important;
      background: linear-gradient(90deg, var(--accent), var(--primary));
    }

    /* Modern hamburger */
    .nav-toggle{
      position: absolute; right:16px; top:50%; transform:translateY(-50%);
      width:44px; height:44px; border-radius:12px;
      display:grid; place-items:center;
      background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12);
      cursor:pointer;
    }
    .nav-toggle .hamburger,
    .nav-toggle .hamburger::before,
    .nav-toggle .hamburger::after{
      content:""; display:block; width:20px; height:2px;
      background:var(--text); border-radius:2px; position:relative;
      transition: transform .25s ease, opacity .25s ease;
    }
    .nav-toggle .hamburger::before{ position:absolute; top:-6px; }
    .nav-toggle .hamburger::after{  position:absolute; top: 6px; }
    body.nav-open .nav-toggle .hamburger{ background:transparent; }
    body.nav-open .nav-toggle .hamburger::before{ transform:translateY(6px) rotate(45deg); }
    body.nav-open .nav-toggle .hamburger::after{  transform:translateY(-6px) rotate(-45deg); }

    /* Hide empty logo box if no image */
    .logo img[src="img/"], .logo img[src=""]{ display:none; }
    .logo{ position:absolute; left:16px; top:50%; transform:translateY(-50%); }

    /* ✅ Mobile: horizontal pill bar (not vertical) */
    @media (max-width: 991.98px){
      .nav{
        position: fixed; top: 58px; left: 0; right: 0;
        display: none; /* shown when nav-open */
        background: rgba(7,12,22,.98) !important;
        padding: 10px 12px;
        z-index: 10001;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
        border-top: 1px solid rgba(255,255,255,.12);
      }
      body.nav-open .nav{ display:block; }

      .nav__list{
        flex-direction: row;        /* horizontal */
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin: 0 auto;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.12);
        overflow-x: auto;           /* sideways scroll if needed */
        -webkit-overflow-scrolling: touch;
      }
      .nav__link{ white-space: nowrap; padding: 9px 12px; }
      body{ padding-top: 70px; }    /* ensure content not hidden behind fixed bar */
    }

    /* Solid overlay disabled (we use the bar instead) */
    body.nav-open{ overflow:hidden; }

    /* Sections + spacing */
    section{ padding:90px 0 !important; }
    .intro,.my-skills,.about-me,.my-work,.resume-section{
      background:var(--surface) !important; color:var(--text) !important;
    }
    .section__title{ color:var(--text) !important; margin-bottom:40px !important; text-align:center; }
    .section__subtitle{ color:var(--muted) !important; text-align:center; }

    /* Hero subtitle pill */
    .section__subtitle--intro{
      display:inline-block !important; padding:.6rem 1rem !important;
      border-radius:10px !important;
      background:linear-gradient(90deg,var(--accent),var(--primary)) !important;
      color:#fff !important;
    }
    .intro::before,.intro::after,
    .section__title--intro::before,.section__title--intro::after,
    .section__subtitle--intro::before,.section__subtitle--intro::after{ content:none !important; }

    /* HERO grid/layout (kept) */
    .intro__img, .section__title--intro, .section__subtitle--intro{
      float:none !important; width:auto !important; position:static !important; left:auto !important; transform:none !important;
    }
    .intro__name{ white-space:nowrap; }
    @media (min-width: 992px){
      .intro{
        display:grid !important;
        grid-template-columns:minmax(320px, 420px) minmax(700px, 1fr) !important;
        align-items:center !important; column-gap:40px !important;
      }
      .intro__img{ grid-column:1 !important; justify-self:center !important; }
      .section__title--intro{ grid-column:2 !important; margin:0 0 10px 0 !important; text-align:left !important; }
      .section__subtitle--intro{ grid-column:2 !important; justify-self:start !important; margin:12px 0 0 0 !important; text-align:left !important; }
    }

    /* Skills grid/cards (kept) */
    .skills{ display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:40px !important; }
    .skill{
      background:var(--card) !important; color:var(--text) !important;
      border:1px solid var(--border) !important; border-radius:14px !important;
      box-shadow:var(--shadow) !important; padding:24px !important;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease, background .25s ease;
    }
    .skill:hover{ transform: translateY(-6px); box-shadow: 0 16px 34px rgba(0,0,0,.5); border-color: rgba(34,211,238,.35); }
    .skill-icon{ color:var(--primary) !important; font-size:28px; margin-bottom:10px; }
    .skill-icon i{ font-size:34px !important; transition: transform .35s ease, color .25s ease, text-shadow .25s ease; }
    .skill:hover .skill-icon i{ transform: scale(1.1) translateY(-2px); text-shadow: 0 0 18px rgba(94,234,212,.45); }

    .my-skills{ padding:180px 0 !important; }
    .section__title--skills{ font-size:calc(2.2rem + 1vw) !important; }
    .skill h3{ font-size:1.6rem !important; }
    .skill p{ font-size:1.05rem !important; line-height:1.75 !important; }

    /* Badges */
    .badge-section{ margin-top:40px !important; }
    .badge-title{ margin:24px 0 16px !important; font-size:1.25rem !important; color:var(--muted) !important; text-align:center !important; }
    .badge-grid{ display:flex; flex-wrap:wrap; gap:12px; justify-content:center; list-style:none; padding:0; margin:0; }
    .badge{
      display:inline-flex; align-items:center; gap:10px;
      padding:10px 14px; border:1px solid var(--border); border-radius:12px;
      background:linear-gradient(90deg, rgba(99,102,241,.15), rgba(34,211,238,.12));
      box-shadow:var(--shadow);
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
    }
    .badge:hover{ transform: translateY(-2px) scale(1.04); box-shadow: 0 8px 20px rgba(0,0,0,.45); border-color: rgba(34,211,238,.35); background: linear-gradient(90deg, rgba(99,102,241,.22), rgba(34,211,238,.18)); }
    .badge__logo{ width:22px; height:22px; object-fit:contain; }
    .badge span{ color:var(--text); font-weight:600; }

    /* About offsets (your request to not hug left) */
    @media (min-width: 768px){
      .about-me__body{ padding-left: 64px !important; }
      .section__subtitle--about{ margin-left: 64px !important; }
    }
    @media (max-width: 767.98px){
      .about-me__body, .section__subtitle--about{ padding-left:20px !important; padding-right:20px !important; }
    }

    /* Projects grid/cards (kept) */
    .projects-grid{
      display:grid;
      grid-template-columns: repeat(3, minmax(0,1fr));
      gap: 20px; align-items: stretch;
      max-width: 1200px; margin: 0 auto;
    }
    @media (max-width: 1200px){ .projects-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 700px){  .projects-grid{ grid-template-columns: 1fr; } }

    .project-card{
      background: rgba(15,22,35,.92);
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(0,0,0,.45);
      display:flex; flex-direction:column;
      position: relative;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .project-card::after{
      content:""; position:absolute; inset:-1px; border-radius:inherit; pointer-events:none;
      background: linear-gradient(120deg, transparent 30%, rgba(34,211,238,.15), rgba(16,185,129,.15), transparent 70%);
      opacity:0; transition: opacity .25s ease;
    }
    .project-card:hover{ transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.55); border-color: rgba(34,211,238,.35); }
    .project-card:hover::after{ opacity:1; }

    .project-card__media{
      display:block; width:100%;
      aspect-ratio: 16 / 10; background:#0b1220;
      will-change: transform, filter;
      transition: transform .35s ease, filter .35s ease;
    }
    .project-card:hover .project-card__media{ transform: scale(1.04); filter: saturate(1.05) contrast(1.02); }

    .project-card__img{ width:100%; height:100%; object-fit: cover; }

    .project-card__body{ padding: 14px 16px; display:flex; flex-direction:column; flex: 1 1 auto; }
    .project-chips{ margin: 0 0 10px; }
    .project-chip{
      display:inline-block; padding:4px 8px; margin:0 8px 10px 0; border-radius:999px;
      font-size:.75rem; color:#d9f7ff; background: rgba(34,211,238,.12); border:1px solid rgba(34,211,238,.22);
    }
    .project-card h3{ margin: 6px 0 6px; font-size: 1.10rem; }
    .project-card p{ margin: 0 0 16px; color: var(--muted); font-size:.95rem; }

    .project-card__cta{
      align-self:flex-start; margin-top:auto;
      display:inline-flex; align-items:center; gap:8px;
      text-decoration:none; font-weight:700; color:#061018;
      background: linear-gradient(90deg, var(--accent), var(--primary));
      padding:10px 14px; border-radius:12px; border: 1px solid rgba(255,255,255,.08);
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .project-card:hover .project-card__cta{ box-shadow: 0 8px 20px rgba(34,211,238,.35); }

    /* Buttons */
    .btn{
      background:var(--primary) !important; color:#061018 !important;
      border:1px solid var(--primary-2) !important; border-radius:12px;
      box-shadow:var(--shadow) !important;
    }
    .btn:hover{ background:var(--primary-2) !important; }

    /* Resume card */
    .resume-container{ background:var(--card) !important; border:1px solid var(--border) !important; box-shadow:var(--shadow) !important; border-radius:14px !important; }

    /* Footer */
    .footer{ background:var(--surface) !important; color:var(--text) !important; border-top:1px solid var(--border) !important; }
    .footer__link{ color:var(--primary) !important; }
    .social-list__link{ color:var(--text) !important; }
    .social-list__link:hover{ color:var(--primary) !important; }

    .intro__img{ box-shadow:var(--shadow) !important; }
    .container{ color:inherit !important; }
  </style>

  <!-- Apply Ahmad-style type everywhere -->
  <style id="ahmad-font-override">
    :root{ --site-font: "Geist", "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    :where(html, body,
           h1, h2, h3, h4, h5, h6,
           p, a, li, button, input, textarea, label,
           .section__title, .section__subtitle,
           .nav__link, .btn,
           .project-card h3, .project-card p,
           .skill h3, .skill p,
           .badge span){
      font-family: var(--site-font) !important;
    }
    :where(h1, h2, h3, .section__title){ font-weight: 800 !important; letter-spacing: .2px; }
    :where(.section__subtitle, .nav__link, .btn){ font-weight: 600 !important; }
    .fa, .fas, .far { font-family: "Font Awesome 5 Free" !important; }
    .fab { font-family: "Font Awesome 5 Brands" !important; }
  </style>

  <!-- Resume box (scoped) -->
  <style>
    .resume-box{
      max-width: 780px; margin: 0 auto 24px; padding: 18px;
      background: var(--card); border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow);
      display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap: wrap;
    }
    .resume-info{ display:flex; align-items:center; gap:10px; color: var(--muted); font-weight: 600; }
    .resume-info i{ font-size: 20px; }
    .resume-actions{ display:flex; gap:12px; flex-wrap: wrap; }
    .resume-actions .btn{ white-space: nowrap; }
  </style>
  <style>
/* ===== Topbar: centered pill menu + actions (Ahmad-like) ===== */
.topbar{
  position: sticky !important;
  top: 0 !important;
  z-index: 10000 !important;
  background: rgba(10,14,22,.65) !important;     /* subtle glass */
  -webkit-backdrop-filter: blur(8px) !important;
  backdrop-filter: blur(8px) !important;
  border-bottom: 1px solid rgba(255,255,255,.08) !important;
}
.topbar__inner{
  max-width: 1200px !important;
  margin: 0 auto !important;
  padding: 10px 16px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  gap: 14px !important;
}

/* Brand (hide empty img gracefully) */
.logo{ display:flex; align-items:center; gap:10px; }
.logo img[src=""], .logo img[src="img/"]{ display:none !important; }
.logo .brand{ color: var(--text); font-weight: 800; letter-spacing:.2px; }

/* Center pill list */
.nav-center{ flex: 1 1 auto; display:flex; justify-content:center; }
.nav-pills{
  display:flex; align-items:center; gap:18px;
  margin:0; padding:8px 12px; list-style:none;
  background: rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.12);
  border-radius: 999px;
  box-shadow: 0 10px 30px rgba(0,0,0,.35);
}
.nav-link{
  color: var(--text) !important;
  text-decoration:none !important;
  font-weight:600 !important;
  padding: 9px 14px; border-radius:999px;
  line-height:1; white-space:nowrap;
  transition: background .18s ease, color .18s ease, transform .18s ease;
}
.nav-link:hover,
.nav-link.is-active{
  color:#061018 !important;
  background: linear-gradient(90deg, var(--accent), var(--primary));
}

/* Right actions */
.nav-actions{ display:flex; align-items:center; gap:10px; }
.btn-cta{
  display:inline-flex; align-items:center; gap:8px;
  padding:10px 14px; border-radius:999px;
  text-decoration:none; font-weight:700;
  color:#061018;
  background: linear-gradient(90deg, #10b981, #22d3ee); /* emerald → cyan */
  border:1px solid rgba(255,255,255,.12);
  box-shadow: 0 8px 22px rgba(34,211,238,.25);
}
.icon-btn{
  width:42px; height:42px; display:grid; place-items:center;
  border-radius:12px; color:var(--text); text-decoration:none;
  background: rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.12);
  transition: transform .18s ease, background .18s ease;
}
.icon-btn:hover{ transform: translateY(-2px); background: rgba(255,255,255,.12); }

/* Kill any previous hamburger/overlay behaviors */
.nav-toggle{ display:none !important; }
body.nav-open{ overflow:auto !important; }
body.nav-open .nav{ display:flex !important; position:static !important; background:transparent !important; }

/* Responsive: keep nav centered & scrollable */
@media (max-width: 860px){
  .topbar__inner{ gap:10px; }
  .nav-pills{ gap:14px; padding:6px 10px; }
  .nav-link{ padding:8px 12px; }
}
@media (max-width: 700px){
  .nav-center{ max-width: 60vw; }
  .nav-pills{
    overflow-x:auto; -webkit-overflow-scrolling:touch;
    scrollbar-width:none;
  }
  .nav-pills::-webkit-scrollbar{ display:none; }
  .btn-cta{ display:none; } /* keep only icons when really tight */
}

</style>
<style>
  /* Make the entire page share the exact same background */
  :root{
    --page-bg: #0b1220;              /* pick ONE color */
    /* Or swap for a page-wide gradient: */
    /* --page-bg: radial-gradient(1200px 800px at 12% 10%, #132040 0%, rgba(19,32,64,0) 60%),
                  radial-gradient(1000px 700px at 88% 80%, #0f2142 0%, rgba(15,33,66,0) 55%),
                  #0b1220; */
  }

  html, body{
    background: var(--page-bg) !important;
    background-attachment: fixed; /* nice subtle feel for gradients; harmless for solid */
  }

  /* Let sections inherit the same background (remove the lighter strip) */
  .intro,
  .my-skills,
  .about-me,
  .my-work,
  .resume-section,
  .footer {
    background: transparent !important;   /* inherits --page-bg from body */
    border-color: rgba(255,255,255,0.08); /* keep your borders if you like */
  }
  /* --- Hero visual polish --- */
.intro{
  position: relative;
  overflow: hidden;
  /* subtle vignette so the center pops */
  background:
    radial-gradient(900px 600px at 15% 10%, rgba(99,102,241,.12), transparent 60%),
    radial-gradient(800px 520px at 85% 85%, rgba(34,211,238,.10), transparent 55%);
}

/* Photo: gradient border + glow (no wrapper needed) */
.intro__img{
  border-radius: 22px;
  border: 3px solid transparent;
  /* gradient border on the image itself */
  border-image: linear-gradient(135deg, var(--accent), var(--primary), #22d3ee) 1;
  box-shadow:
    0 12px 36px rgba(0,0,0,.50),
    0 0 0 1px rgba(255,255,255,.06) inset,
    0 0 35px rgba(34,211,238,.18);
  transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}
.intro__img:hover{
  transform: translateY(-2px);
  box-shadow:
    0 18px 52px rgba(0,0,0,.55),
    0 0 0 1px rgba(255,255,255,.06) inset,
    0 0 45px rgba(96,165,250,.22);
}

/* Emoji pills under the subtitle */
.hero-pills{
  display:flex; flex-wrap:wrap; gap:10px;
  margin:14px 0 0; padding:0; list-style:none;
}
.hero-pill{
  display:inline-flex; align-items:center; gap:8px;
  padding:8px 12px; border-radius:999px;
  background: rgba(255,255,255,.06);
  border:1px solid var(--border);
  box-shadow: 0 6px 16px rgba(0,0,0,.35);
  font-weight:700; color:var(--text);
}
.hero-pill .emoji{ font-size:1.15rem; line-height:1; }

/* keep text column tidy on wide screens */
.section__title--intro{ max-width: 22ch; }
.section__subtitle--intro{ margin-top: 8px; }
/* ===== Education (card style) ===== */
.education{ background:var(--surface); color:var(--text); padding:90px 0; }
.edu-card{
  max-width: 980px; margin: 0 auto; padding: 22px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: var(--shadow);
  display: grid; grid-template-columns: 160px 1fr; gap: 22px; align-items: center;
}
.edu-logo{
  width:100%; height:120px; object-fit: cover;
  border-radius:12px; border:1px solid var(--border);
  box-shadow: 0 8px 18px rgba(0,0,0,.35);
}
.edu-body h3{ margin:0 0 6px; font-weight:800; }
.edu-degree{ margin:0 0 12px; color:var(--muted); font-weight:600; }

/* Chips */
.edu-chips{ display:flex; flex-wrap:wrap; gap:8px; list-style:none; padding:0; margin:0; }
.edu-chip{
  padding:6px 10px; border-radius:999px;
  font-size:.85rem; color:#d9f7ff;
  background: rgba(34,211,238,.12);
  border:1px solid rgba(34,211,238,.22);
}

/* Mobile */
@media (max-width:700px){
  .edu-card{ grid-template-columns:1fr; }
  .edu-logo{ height:140px; }
}
/* ===== About (modern) ===== */
.about-modern{
  background: var(--surface);
  padding: 120px 0;
}
.about-modern .about-grid{
  max-width: 1150px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.15fr 0.85fr;
  gap: 46px;
  align-items: center;
  padding: 0 16px;
}
@media (max-width: 980px){
  .about-modern .about-grid{ grid-template-columns: 1fr; }
}

/* Title + subtitle */
.about-modern .about-title{
  font-size: clamp(2.0rem, 3.2vw, 3rem);
  line-height: 1.1;
  margin: 0 0 14px;
  font-weight: 800;
}
.about-modern .about-sub{
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--accent), var(--primary));
  color: #061018;
  font-weight: 800;
  box-shadow: 0 10px 30px rgba(0,0,0,.35);
}

/* Body copy */
.about-modern .lead{
  color: var(--text);
  line-height: 1.8;
  font-size: 1.05rem;
  margin: 20px 0 22px;
}

/* Chips */
.about-tags{
  display:flex; flex-wrap:wrap; gap:10px 12px;
  list-style:none; margin:0 0 22px; padding:0;
}
.about-tags li{
  display:inline-flex; align-items:center; gap:8px;
  padding:8px 12px;
  border-radius: 12px;
  background: rgba(99,102,241,.14);
  border:1px solid rgba(255,255,255,.12);
  color: var(--text); font-weight: 600;
}

/* Stats */
.about-stats{
  display:grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 12px;
}
@media (max-width: 560px){ .about-stats{ grid-template-columns: repeat(2,1fr); } }
.stat{
  background: var(--card);
  border:1px solid var(--border);
  border-radius: 14px;
  padding: 14px 16px;
  box-shadow: var(--shadow);
  text-align: center;
}
.stat .num{
  font-weight: 900;
  font-size: 1.6rem;
  background: linear-gradient(90deg, var(--primary), #22d3ee);
  -webkit-background-clip: text; background-clip: text;
  color: transparent;
}
.stat .label{ color: var(--muted); font-weight: 600; font-size: .9rem; }

/* Photo stack (right) */
.photo-stack{
  position: relative;
  width: 100%;
  max-width: 520px;
  justify-self: center;
}
.photo-stack .orb{
  position:absolute; inset:-16%;
  background: radial-gradient(60% 60% at 60% 40%, rgba(99,102,241,.25), rgba(59,130,246,.18), rgba(0,0,0,0));
  filter: blur(28px);
  z-index:0;
}
.photo-card{
  position:relative; z-index:1;
  border-radius: 18px;
  overflow:hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.45);
  border: 1px solid rgba(255,255,255,.12);
  background:
    linear-gradient(var(--card), var(--card)) padding-box,
    linear-gradient(120deg, rgba(139,92,246,.6), rgba(96,165,250,.6)) border-box; /* gradient border */
}
.photo-card img{ display:block; width:100%; height:auto; }

/* Floating notes */
.glass-note{
  position:absolute; right:-10px; top:-14px; z-index:2;
  display:flex; align-items:center; gap:10px;
  padding:10px 12px; border-radius:12px;
  background: rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.18);
  backdrop-filter: blur(6px);
  color:#fff; font-weight:700;
}
.fab-badge{
  position:absolute; left:-12px; bottom:-12px; z-index:2;
  width:56px; height:56px; border-radius:50%;
  display:grid; place-items:center;
  background: linear-gradient(90deg, #10b981, #22d3ee);
  color:#061018; font-weight:900;
  border:1px solid rgba(255,255,255,.25);
  box-shadow: 0 10px 30px rgba(0,0,0,.45);
}

/* ===== About (modern, fixed layout + hovers) ===== */
.about-modern{
  background: var(--surface);
  padding: 120px 0;
}

.about-modern .about-grid{
  max-width: 1150px;
  margin: 0 auto;
  padding: 0 16px;
  display: grid;
  grid-template-columns: minmax(520px, 1.1fr) minmax(380px, 0.9fr);
  gap: 56px;
  align-items: start;              /* keep photo aligned to top */
}

@media (max-width: 1100px){
  .about-modern .about-grid{
    grid-template-columns: 1fr 0.9fr;
    gap: 36px;
  }
}
@media (max-width: 980px){
  .about-modern .about-grid{ grid-template-columns: 1fr; }
}

/* Title + subtitle */
.about-modern .about-title{
  font-size: clamp(2rem, 3.2vw, 3rem);
  line-height: 1.1;
  margin: 0 0 10px;
  font-weight: 800;
}
.about-modern .about-sub{
  display:inline-flex; align-items:center; gap:10px;
  padding: 12px 16px; border-radius: 14px;
  background: linear-gradient(90deg, var(--accent), var(--primary));
  color:#061018; font-weight: 800;
  box-shadow: 0 10px 30px rgba(0,0,0,.35);
}

/* Body copy */
.about-modern .lead{
  color: var(--text);
  line-height: 1.8;
  font-size: 1.05rem;
  margin: 20px 0 22px;
}

/* Chips with tooltips */
.about-tags{ display:flex; flex-wrap:wrap; gap:12px; list-style:none; margin:0 0 24px; padding:0; }
.about-tags li{
  position: relative;
  display:inline-flex; align-items:center; gap:10px;
  padding:10px 14px; border-radius: 12px;
  background: rgba(99,102,241,.14);
  border:1px solid rgba(255,255,255,.12);
  color: var(--text); font-weight: 700; cursor: help;
  transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease;
}
.about-tags li:hover{
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(0,0,0,.35);
  border-color: rgba(34,211,238,.35);
}

/* Tooltip */
.about-tags li[data-tip]::after{
  content: attr(data-tip);
  position: absolute; left: 50%; bottom: calc(100% + 10px);
  transform: translateX(-50%) translateY(4px) scale(.98);
  opacity: 0; pointer-events:none;
  padding:10px 12px; max-width: 260px;
  color:#fff; background: rgba(7,12,22,.92);
  border:1px solid rgba(255,255,255,.15); border-radius:10px;
  box-shadow: 0 10px 28px rgba(0,0,0,.5);
  text-align: center; line-height:1.5; font-weight:600; font-size:.9rem;
  transition: opacity .16s ease, transform .16s ease;
  z-index: 5;
}
.about-tags li[data-tip]::before{
  content:""; position:absolute; left:50%; bottom:100%;
  transform: translateX(-50%);
  border:6px solid transparent; border-top-color: rgba(7,12,22,.92);
  opacity:0; transition: opacity .16s ease;
}
.about-tags li:hover::after,
.about-tags li:focus-visible::after,
.about-tags li:hover::before,
.about-tags li:focus-visible::before{
  opacity:1; transform: translateX(-50%) translateY(0) scale(1);
}

/* Stats */
.about-stats{
  display:grid; grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 14px;
}
@media (max-width: 560px){ .about-stats{ grid-template-columns: repeat(2,1fr); } }
.stat{
  background: var(--card);
  border:1px solid var(--border);
  border-radius: 14px;
  padding: 16px;
  box-shadow: var(--shadow);
  text-align: center;
  transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease;
}
.stat:hover{ transform: translateY(-2px); border-color: rgba(34,211,238,.35); box-shadow: 0 16px 34px rgba(0,0,0,.45); }
.stat .num{
  font-weight: 900; font-size: 1.7rem;
  background: linear-gradient(90deg, var(--primary), #22d3ee);
  -webkit-background-clip: text; background-clip: text; color: transparent;
}
.stat .label{ color: var(--muted); font-weight: 700; font-size: .92rem; }

/* Photo (RIGHT column) */
.photo-stack{
  position: relative; width: 100%; max-width: 520px;
  justify-self: end;               /* keep at the right */
  align-self: start;
}
.photo-stack .orb{
  position:absolute; inset:-12%;
  background: radial-gradient(60% 60% at 60% 40%, rgba(99,102,241,.28), rgba(59,130,246,.20), rgba(0,0,0,0));
  filter: blur(28px); z-index:0; pointer-events:none;
}
.photo-card{
  position:relative; z-index:1;
  border-radius: 18px; overflow:hidden;
  background:
    linear-gradient(var(--card), var(--card)) padding-box,
    linear-gradient(120deg, rgba(139,92,246,.6), rgba(96,165,250,.6)) border-box;
  border: 1px solid transparent;
  box-shadow: 0 20px 60px rgba(0,0,0,.45);
  transition: transform .2s ease, box-shadow .25s ease;
}
.photo-card:hover{ transform: translateY(-4px) scale(1.01); box-shadow: 0 28px 70px rgba(0,0,0,.55); }
.photo-card img{ display:block; width:100%; height:auto; }

/* Floating labels (kept inside card bounds on all screens) */
.glass-note, .fab-badge{ pointer-events:none; } /* purely decorative */
.glass-note{
  position:absolute; right: 12px; top: 12px; z-index:2;
  display:flex; align-items:center; gap:10px;
  padding:10px 12px; border-radius:12px;
  background: rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.20);
  backdrop-filter: blur(6px);
  color:#fff; font-weight:800;
}
.fab-badge{
  position:absolute; left: 12px; bottom: 12px; z-index:2;
  width:56px; height:56px; border-radius:50%;
  display:grid; place-items:center;
  background: linear-gradient(90deg, #10b981, #22d3ee);
  color:#061018; font-weight:900;
  border:1px solid rgba(255,255,255,.25);
  box-shadow: 0 10px 30px rgba(0,0,0,.45);
}

</style>


</head>

<script>
(() => {
  const links = Array.from(document.querySelectorAll('.nav__link, .nav-link'));
  if (!links.length) return;

  // Map: section id -> all nav anchors that point to it
  const linkMap = new Map();
  links.forEach(a => {
    const id = (a.getAttribute('href') || '').split('#')[1];
    if (!id) return;
    if (!linkMap.has(id)) linkMap.set(id, []);
    linkMap.get(id).push(a);
  });

  function setActiveByKey(key){
    links.forEach(a => a.classList.remove('is-active'));
    (linkMap.get(key) || []).forEach(a => a.classList.add('is-active'));
  }

  // Project detail page: always highlight Projects
  const path = (location.pathname || '').toLowerCase();
  if (path.includes('project.php')) {
    setActiveByKey('work');
    return;
  }

  const headerH = document.querySelector('.topbar')?.offsetHeight || 70;

  // Sections that actually exist and have matching nav links
  const sections = Array.from(document.querySelectorAll('section[id]'))
    .filter(sec => linkMap.has(sec.id));

  // Initial highlight from hash (or Home)
  const initialId = (location.hash || '#home').slice(1).toLowerCase();
  setActiveByKey(linkMap.has(initialId) ? initialId : 'home');

  // Smooth-scroll with header offset on clicks + close any mobile menu
  const body = document.body;
  const toggle = document.querySelector('.nav-toggle');
  if (toggle) toggle.addEventListener('click', () => body.classList.toggle('nav-open'));
  links.forEach(a => {
    a.addEventListener('click', (e) => {
      const id = (a.getAttribute('href') || '').split('#')[1];
      if (!id) return;
      const target = document.getElementById(id);
      if (!target) return;
      e.preventDefault();
      const y = target.getBoundingClientRect().top + window.pageYOffset - headerH - 8;
      window.scrollTo({ top: y, behavior: 'smooth' });
      history.replaceState(null, '', `#${id}`);
      setActiveByKey(id);
      body.classList.remove('nav-open');
    });
  });

  // === Scroll-aware highlighting ===
  function chooseActiveByPosition(){
    // Section whose top is closest to the top after accounting for header
    let best = null;
    let bestDist = Infinity;
    for (const sec of sections) {
      const dist = Math.abs(sec.getBoundingClientRect().top - headerH);
      if (dist < bestDist) { best = sec; bestDist = dist; }
    }
    if (best && linkMap.has(best.id)) {
      setActiveByKey(best.id);
      history.replaceState(null, '', `#${best.id}`);
    }
  }

  if ('IntersectionObserver' in window) {
    let activeId = null;
    const io = new IntersectionObserver((entries) => {
      // Consider visible entries, prefer the one nearest to header
      const vis = entries
        .filter(e => e.isIntersecting)
        .sort((a,b) => Math.abs(a.boundingClientRect.top - headerH) - Math.abs(b.boundingClientRect.top - headerH));
      if (vis.length) {
        const id = vis[0].target.id;
        if (id && id !== activeId) {
          activeId = id;
          setActiveByKey(id);
          history.replaceState(null, '', `#${id}`);
        }
      } else {
        // fallback within IO callback when nothing intersects
        chooseActiveByPosition();
      }
    }, {
      root: null,
      rootMargin: `-${headerH + 1}px 0px -60% 0px`,
      threshold: [0, 0.25, 0.5, 0.75, 1]
    });
    sections.forEach(sec => io.observe(sec));
  } else {
    // Fallback: scroll listener (throttled)
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        ticking = true;
        requestAnimationFrame(() => {
          chooseActiveByPosition();
          ticking = false;
        });
      }
    }, { passive: true });
    window.addEventListener('load', chooseActiveByPosition);
  }

  // Ensure anchored load positions account for sticky header
  window.addEventListener('load', () => {
    const id = location.hash.slice(1);
    const target = id && document.getElementById(id);
    if (target) {
      const y = target.getBoundingClientRect().top + window.pageYOffset - headerH - 8;
      window.scrollTo({ top: y });
    }
  });
})();
</script>

<body>
 <header class="topbar">
  <div class="topbar__inner">
    <!-- Left: brand -->
    <div class="logo">
      <img src="img/" alt="">
      <span class="brand">Mostafa</span>
    </div>

    <!-- Center: pill navigation -->
    <nav class="nav-center" aria-label="Primary">
      <ul class="nav-pills">
        <li class="nav__item"><a href="#home"   class="nav__link">Home</a></li>
        <li class="nav__item"><a href="#education" class="nav__link">Education</a></li>

        <li class="nav__item"><a href="#skills" class="nav__link">Skills</a></li>
        <li class="nav__item"><a href="#about"  class="nav__link">About</a></li>
        <li class="nav__item"><a href="#work"   class="nav__link">Projects</a></li>
        <li class="nav__item"><a href="#resume" class="nav__link">Resume</a></li>
      </ul>
    </nav>

    <!-- Right: actions -->
    <div class="nav-actions">
      <a href="mailto:eng.mostafanajjar@outlook.com" class="btn-cta">
        Get in Touch <span aria-hidden="true">→</span>
      </a>
      <a href="mailto:eng.mostafanajjar@outlook.com" class="icon-btn" aria-label="Email">
        <i class="fas fa-envelope"></i>
      </a>
      <a href="https://linkedin.com/in/mostafa-najjar" target="_blank" rel="noopener" class="icon-btn" aria-label="LinkedIn">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>
  </div>
</header>


  <!-- Intro -->
  <section class="intro" id="home">
    <h1 class="section__title section__title--intro">
      Hello, I am <strong><span class="intro__name">Mostafa Najjar !</span></strong>
    </h1>
    <p class="section__subtitle section__subtitle--intro">MEP Designer &amp; BIM Modeler</p>
    
    <img src="img/Pfp-mos.jpg" alt="A Picture of ARZ" class="intro__img">
  </section>
  <!-- Education -->
<section class="education" id="education">
  <h2 class="section__title section__title--education">Education</h2>
  <p class="section__subtitle section__subtitle--education">B.E. in Mechanical Engineering</p>

  <div class="edu-card">
    <!-- Replace src with your actual LAU photo file -->
    <img src="img/lau.jpg" alt="Lebanese American University" class="edu-logo"
         onerror="this.style.display='none'">

    <div class="edu-body">
      <h3>Lebanese American University (LAU)</h3>
      <p class="edu-degree">Bachelor of Engineering • Mechanical Engineering (2022-2026)</p>

      <!-- optional highlight chips -->
      <ul class="edu-chips">
        <li class="edu-chip">HVAC</li>
        <li class="edu-chip">Thermodynamics</li>
        <li class="edu-chip">Fluid Mechanics</li>
         <li class="edu-chip">Heat Transfer</li>
             <li class="edu-chip">Mechanical Design</li>
        <li class="edu-chip">Thermal Systems</li>
      </ul>
    </div>
  </div>
</section>


  <!-- Skills -->
  <section class="my-skills" id="skills">
    <div class="container">
      <h2 class="section__title section__title--skills">My Skills</h2>

      <div class="skills">
        <div class="skill">
          <div class="skill-icon"><i class="fas fa-cogs"></i></div>
          <h3>Systems Design &amp; Standards</h3>
          <p>Experienced in designing and integrating mechanical, electrical, and plumbing systems for residential, commercial, and institutional codes (ASHRAE, IPC) and advanced simulation tools for accurate load calculations and system optimization.</p>
        </div>

        <div class="skill">
          <div class="skill-icon"><i class="fas fa-drafting-compass"></i></div>
          <h3>BIM &amp; Digital Engineering</h3>
          <p>Proficient in Building Information Modeling (BIM) using Revit to deliver precise, coordinated, and construction-ready models, enhancing collaboration and efficiency across multidisciplinary teams.</p>
        </div>

        <div class="skill">
          <div class="skill-icon"><i class="fas fa-users"></i></div>
          <h3>Leadership &amp; Team Collaboration</h3>
          <p>Proven leadership and organizational skills demonstrated through presidency of university clubs and successful coordination of group projects, fostering teamwork, innovation, and goal achievement.</p>
        </div>
      </div>

      <!-- Software badges -->
      <div class="badge-section">
        <h3 class="badge-title">Software</h3>
        <ul class="badge-grid">
          <li class="badge"><img src="img/revit.jpg"         alt="Revit"      class="badge__logo" onerror="this.style.display='none'"><span>Revit</span></li>
          <li class="badge"><img src="img/Autocad.jpg"       alt="AutoCAD"    class="badge__logo" onerror="this.style.display='none'"><span>AutoCAD</span></li>
          <li class="badge"><img src="img/HAP.jpg"           alt="HAP"        class="badge__logo" onerror="this.style.display='none'"><span>HAP</span></li>
          <li class="badge"><img src="img/LATS.png"          alt="LATS"       class="badge__logo" onerror="this.style.display='none'"><span>LATS</span></li>
          <li class="badge"><img src="img/ductsizer.png"     alt="Ductsizer"  class="badge__logo" onerror="this.style.display='none'"><span>Ductsizer</span></li>
          <li class="badge"><img src="img/Solidworks.png"    alt="SolidWorks" class="badge__logo" onerror="this.style.display='none'"><span>SolidWorks</span></li>
          <li class="badge"><img src="img/MS office.png"     alt="MS Office"  class="badge__logo" onerror="this.style.display='none'"><span>MS Office</span></li>
          <li class="badge"><img src="img/navisworks.jpg"    alt="Navisworks" class="badge__logo" onerror="this.style.display='none'"><span>Navisworks</span></li>
          <li class="badge"><img src="img/caps.jpg"          alt="CAPS"       class="badge__logo" onerror="this.style.display='none'"><span>CAPS</span></li>
        </ul>
      </div>

      <!-- Codes & Standards badges -->
      <div class="badge-section">
        <h3 class="badge-title">Codes &amp; Standards</h3>
        <ul class="badge-grid">
          <li class="badge"><img src="img/ipc.jpg"       alt="IPC"    class="badge__logo" onerror="this.style.display='none'"><span>IPC</span></li>
          <li class="badge"><img src="img/logos/npc.svg" alt="UPC"    class="badge__logo" onerror="this.style.display='none'"><span>UPC</span></li>
          <li class="badge"><img src="img/ASHRAE.jpg"    alt="ASHRAE" class="badge__logo" onerror="this.style.display='none'"><span>ASHRAE</span></li>
          <li class="badge"><img src="img/smacna.jpg"    alt="SMACNA" class="badge__logo" onerror="this.style.display='none'"><span>SMACNA</span></li>
        </ul>
      </div>
    </div>
  </section>

  <a href="#work" class="btn">My Work</a>

  <!-- About -->
<section class="about-me about-modern" id="about">
  <div class="about-grid">
    <!-- LEFT: text -->
    <div>
      <h2 class="about-title">Who’s Mostafa?</h2>
      <span class="about-sub">MEP Engineer • BIM Modeler • Team Leader</span>

      <p class="lead">
        I’m Mostafa El Badawi El Najjar, a Senior Mechanical Engineering student at the
        Lebanese American University. I’m focused on sustainable MEP design and using
        modern digital tools to deliver efficient, coordinated systems.
      </p>
      <p class="lead">
        Outside academics, I’ve led student clubs and scout teams and co-founded
        LawMate, an AI startup—experiences that sharpened my leadership, delivery,
        and collaboration.
      </p>

      <ul class="about-tags">
        <li>🛠️ MEP Design</li>
        <li>🧩 BIM / Revit</li>
        <li>🌱 Sustainable HVAC</li>
        
      </ul>

      <div class="about-stats">
        <div class="stat"><div class="num">10+</div><div class="label">Projects</div></div>
        <div class="stat"><div class="num">4+</div><div class="label">Leadership Roles</div></div>
        <div class="stat"><div class="num">2</div><div class="label">Years BIM</div></div>
      </div>
    </div>

    <!-- RIGHT: photo + accents -->
    <div class="photo-stack">
      <div class="orb"></div>
      <figure class="photo-card">
        <!-- Use your preferred picture -->
        <img src="img/imagemos.jpg" alt="Mostafa Najjar speaking at a podium">
      </figure>

      <div class="glass-note">
        <i class="fas fa-drafting-compass"></i> BIM • Revit
      </div>
      <div class="fab-badge">
        <i class="fas fa-cogs"></i>
      </div>
    </div>
  </div>
</section>

  <!-- My Work -->
  <section class="my-work" id="work">
    <h2 class="section__title section__title--work">My Projects</h2>
    <p class="section__subtitle section__subtitle--work">A selection of my Top work</p>

    <div class="projects-grid">
      <?php foreach ($projects as $p): ?>
        <article class="project-card">
          <a href="project.php?project=<?php echo htmlspecialchars($p['id']); ?>"
             class="project-card__media"
             aria-label="Open <?php echo htmlspecialchars($p['title']); ?>">
            <img src="<?php echo htmlspecialchars($p['images'][0]); ?>"
                 alt="<?php echo htmlspecialchars($p['title']); ?>"
                 class="project-card__img"
                 onerror="this.style.display='none'">
          </a>

          <div class="project-card__body">
            <?php if (!empty($p['tags']) && is_array($p['tags'])): ?>
              <div class="project-chips">
                <?php foreach ($p['tags'] as $tag): ?>
                  <span class="project-chip"><?php echo htmlspecialchars($tag); ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <h3><?php echo htmlspecialchars($p['title']); ?></h3>

            <?php if (!empty($p['short_description'])): ?>
              <p><?php echo htmlspecialchars($p['short_description']); ?></p>
            <?php endif; ?>

            <a class="project-card__cta" href="project.php?project=<?php echo htmlspecialchars($p['id']); ?>">
              View details <span aria-hidden="true">→</span>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Resume -->
  <section class="resume-section" id="resume">
    <h2 class="section__title section__title--resume">My Resume</h2>
    <p class="section__subtitle section__subtitle--resume">Check my resume!</p>

    <div class="resume-box">
      <div class="resume-info">
        <i class="far fa-file-pdf"></i>
        <span>Resume (PDF)</span>
      </div>
      <div class="resume-actions">
        <a href="<?php echo $cvPath . '?v=' . $cvVer; ?>" target="_blank" rel="noopener" class="btn">View Resume</a>
        <a href="<?php echo $cvPath; ?>" class="btn" download>Download</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <a href="mailto:eng.mostafanajjar@outlook.com" class="footer__link">eng.mostafanajjar@outlook.com</a>
    <ul class="social-list">
      <li class="social-list__item">
        <a class="social-list__link" href="http://linkedin.com/in/mostafa-najjar" aria-label="LinkedIn">
          <i class="fab fa-linkedin"></i>
        </a>
      </li>
      <li class="social-list__item">
        <a class="social-list__link" href="tel:+96176493457" aria-label="Phone">
          <i class="fas fa-phone fa-flip-horizontal"></i>
        </a>
      </li>
    </ul>
  </footer>

  <!-- Keep your main script once -->
  <script src="js/index.js"></script>
<!-- Scroll-aware active pill (replaces the old helper) -->
<script>
(() => {
  const links = [...document.querySelectorAll('.nav__link, .nav-link')];

  // Map link -> section id
  const linkTargets = links.map(a => ((a.getAttribute('href')||'').split('#').pop()||'').toLowerCase());
  const sections = Array.from(new Set(linkTargets))
    .map(id => document.getElementById(id))
    .filter(Boolean);

  const header = document.querySelector('.topbar') || document.querySelector('header');
  const headerH = header ? header.offsetHeight : 0;

  const setActiveId = (id) => {
    const hash = `#${id}`;
    links.forEach(a => {
      const isThis = (a.getAttribute('href')||'').toLowerCase() === hash;
      a.classList.toggle('is-active', isThis);
    });
  };

  // Initial state (honor hash if valid, else home)
  const initial = ((location.hash||'#home').slice(1)).toLowerCase();
  if (document.getElementById(initial)) setActiveId(initial);
  else setActiveId('home');

  // Update on click immediately (feels snappy)
  links.forEach(a => {
    a.addEventListener('click', () => {
      const id = (a.getAttribute('href')||'').split('#').pop().toLowerCase();
      if (id) setActiveId(id);
      document.body.classList.remove('nav-open'); // close mobile menu if open
    });
  });

  // Observe scrolling to switch active pill based on what's visible
  if ('IntersectionObserver' in window && sections.length) {
    let current = initial;
    const io = new IntersectionObserver((entries) => {
      // pick the entry with the largest visible area
      let best = null, bestRatio = 0;
      for (const e of entries) {
        if (e.isIntersecting && e.intersectionRatio >= bestRatio) {
          best = e; bestRatio = e.intersectionRatio;
        }
      }
      if (best) {
        const id = best.target.id.toLowerCase();
        if (id !== current) {
          current = id;
          setActiveId(id);
          // keep hash in sync without jumping
          if (location.hash.toLowerCase() !== `#${id}`) {
            history.replaceState(null, '', `#${id}`);
          }
        }
      }
    }, {
      root: null,
      threshold: [0.55, 0.6, 0.7],                 // "more than half on screen"
      rootMargin: `-${headerH + 10}px 0px -45% 0px` // compensate sticky header
    });
    sections.forEach(s => io.observe(s));
  } else {
    // Fallback if IO not supported
    const onScroll = () => {
      const y = window.scrollY + headerH + 20;
      let closest = null, best = Infinity;
      for (const s of sections) {
        const top = s.getBoundingClientRect().top + window.scrollY;
        const d = Math.abs(top - y);
        if (d < best) { best = d; closest = s; }
      }
      if (closest) setActiveId(closest.id.toLowerCase());
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();
</script>
<script>
(() => {
  // All nav links (works with .nav__link or .nav-link)
  const links = [...document.querySelectorAll('.nav__link, .nav-link')];
  if (!links.length) return;

  // IDs of sections you have on the page
  const SECTION_IDS = ['home','skills','about','education','work','resume'];

  // Utility: set the active pill
  function setActive(id){
    links.forEach(a => {
      const href = (a.getAttribute('href')||'').toLowerCase();
      a.classList.toggle('is-active', href.endsWith('#'+id));
    });
  }

  // Observe sections as you scroll
  const sections = SECTION_IDS
    .map(id => document.getElementById(id))
    .filter(Boolean);

  const headerH = (document.querySelector('.topbar') || document.querySelector('header'))?.offsetHeight || 64;

  // Prefer the section around the middle of the viewport
  const io = new IntersectionObserver((entries) => {
    // choose the entry with largest intersection ratio
    let best = null;
    for (const e of entries) {
      if (!e.isIntersecting) continue;
      if (!best || e.intersectionRatio > best.intersectionRatio) best = e;
    }
    if (best) setActive(best.target.id);
  }, {
    root: null,
    rootMargin: `-${headerH + 8}px 0px -55% 0px`,
    threshold: [0.15, 0.35, 0.55, 0.75]
  });

  sections.forEach(s => io.observe(s));

  // Also react to clicks/hash so it highlights immediately
  links.forEach(a => a.addEventListener('click', () => {
    const id = (a.getAttribute('href')||'').replace(/^.*#/, '');
    setTimeout(() => setActive(id), 0);
  }));
  window.addEventListener('hashchange', () => setActive(location.hash.replace('#','')));
  // Initial state
  setActive((location.hash || '#home').replace('#',''));
})();
</script>
<script>
(function () {
  const params = new URLSearchParams(location.search);
  const to = params.get('to'); // e.g. "skills"
  if (to) {
    if (location.hash.slice(1) !== to) {
      history.replaceState(null, '', location.pathname + '#' + to);
    }
    const el = document.getElementById(to);
    if (el) el.scrollIntoView();
  }
})();
</script>

</body>
</html>

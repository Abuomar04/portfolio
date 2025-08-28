<?php
// --------- PHP: compute home URL + load project ----------
$base    = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
$homeUrl = $base . 'index.php';

$projects  = json_decode(file_get_contents('projects.json'), true);
$projectId = $_GET['project'] ?? null;

if (!$projectId) { header('Location: ' . $homeUrl); exit(); }

$selectedProject = null;
foreach ($projects as $p) {
  if (isset($p['id']) && $p['id'] === $projectId) { $selectedProject = $p; break; }
}
if (!$selectedProject) { header('Location: ' . $homeUrl); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Project — <?php echo htmlspecialchars($selectedProject['title']); ?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" crossorigin="anonymous" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
  :root{
    --bg:#0b1220; --surface:#121826; --card:#0f172a;
    --text:#e5e7eb; --muted:#9ca3af;
    --primary:#60a5fa; --primary-2:#3b82f6; --accent:#8b5cf6;
    --border:#263045; --shadow:0 10px 25px rgba(0,0,0,.45);
  }
  html, body{ background:var(--bg); color:var(--text); font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif; }
  body{ overflow-x:hidden; }

  /* Header (links back to homepage sections) */
  header{ position:sticky; top:0; z-index:1000; background:rgba(18,24,38,.9); backdrop-filter: blur(6px); border-bottom:1px solid rgba(255,255,255,.08); }
  .nav{ max-width:1200px; margin:0 auto; padding:10px 16px; }
  .nav__list{ display:flex; gap:14px; list-style:none; margin:0; padding:0; justify-content:center; }
  .nav__link{ color:var(--text); text-decoration:none; font-weight:600; padding:8px 12px; border-radius:999px; }
  .nav__link:hover{ color:#061018; background:linear-gradient(90deg,var(--accent),var(--primary)); }

  /* Page wrap */
  .project-wrap{ max-width:1200px; margin:0 auto; padding:22px 16px 56px; }
  .section__title{ font-weight:800; font-size:clamp(1.6rem,2.4vw,2.3rem); margin:14px 0 6px; }
  .project-sub{ color:var(--muted); margin:0 0 16px; }

  /* === CAROUSEL (single image view, horizontal) === */
  .carousel{
    position:relative;
    width: min(1100px, 92vw);
    height: min(60vh, 620px);
    margin: 10px auto 18px;
    border-radius:16px;
    background:#0a0f1c;
    border:1px solid var(--border);
    box-shadow:var(--shadow);
    overflow:hidden;
  }
  .track{ display:flex; height:100%; transition: transform .4s ease; }
  .slide{ flex:0 0 100%; height:100%; display:grid; place-items:center; }
  .slide img{
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display:block;
    cursor: zoom-in;
  }

  /* Big, high-contrast arrows */
  .cbtn{
    position:absolute; top:50%; transform:translateY(-50%);
    z-index:3;
    width:60px; height:60px;
    border-radius:50%;
    display:grid; place-items:center;
    color:#fff;
    background:rgba(8,12,20,.65);
    border:1px solid rgba(255,255,255,.35);
    box-shadow: 0 8px 24px rgba(0,0,0,.55), 0 0 0 2px rgba(255,255,255,.15) inset;
    cursor:pointer; user-select:none;
  }
  .cbtn i{ font-size:22px; }
  .cbtn:hover{ background:rgba(8,12,20,.82); }
  .prev{ left:18px; } .next{ right:18px; }

  .cbtn::after{
    display:none;
    position:absolute; top:50%; transform:translateY(-50%);
    padding:6px 10px; border-radius:10px;
    background:rgba(0,0,0,.45);
    color:#fff; font-weight:700; letter-spacing:.02em; font-size:12.5px;
    box-shadow:0 6px 16px rgba(0,0,0,.35);
    pointer-events:none;
  }
  @media (min-width: 992px){
    .prev::after{ content:"Prev"; left:72px; display:block; }
    .next::after{ content:"Next"; right:72px; display:block; }
  }

  .edge{ position:absolute; top:0; bottom:0; width:120px; z-index:2; pointer-events:none; }
  .edge.left{  left:0;  background:linear-gradient(90deg, rgba(11,18,32,.9), rgba(11,18,32,0)); }
  .edge.right{ right:0; background:linear-gradient(270deg, rgba(11,18,32,.9), rgba(11,18,32,0)); }

  .dots{ position:absolute; left:0; right:0; bottom:10px; display:flex; gap:8px; justify-content:center; z-index:3; }
  .dot{ width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,.28); border:1px solid rgba(255,255,255,.35); cursor:pointer; }
  .dot.active{ background:#fff; }

  /* Description card */
  .project-details__description{
    line-height:1.7; color:var(--text);
    background:var(--card); border:1px solid var(--border);
    border-radius:14px; padding:18px; box-shadow:var(--shadow);
    max-width:min(1100px, 92vw); margin: 0 auto;
  }

  /* === LIGHTBOX (full image on click, no new page) === */
  .lightbox{
    position: fixed; inset: 0; z-index: 2000;
    display:none;                 /* .open => flex */
    align-items:center; justify-content:center;
    background: rgba(6,10,18,.92);
    -webkit-backdrop-filter: blur(2px);
    backdrop-filter: blur(2px);
  }
  .lightbox.open{ display:flex; }
  .lightbox__img{
    max-width: 92vw; max-height: 90vh;
    object-fit: contain; display:block;
    cursor: zoom-out;
    box-shadow: 0 20px 60px rgba(0,0,0,.6);
    border:1px solid rgba(255,255,255,.12);
    border-radius: 10px;
  }
  .lightbox .cbtn{ width:64px; height:64px; }
  .lightbox .prev{ left:24px; } .lightbox .next{ right:24px; }
  .lightbox__close{
    position: absolute; top:20px; right:22px;
    width:46px; height:46px; border-radius:10px;
    display:grid; place-items:center;
    color:#fff; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.25);
    cursor:pointer;
  }
  .lightbox__close:hover{ background:rgba(255,255,255,.18); }

  .footer{ border-top:1px solid var(--border); background:var(--surface); padding:24px 16px; margin-top:42px; }
  .footer__inner{ max-width:1200px; margin:0 auto; display:flex; align-items:center; gap:16px; }
  .footer__link{ color:var(--primary); text-decoration:none; font-weight:600; }
</style>
</head>
<body>

<header>
  <nav class="nav">
    <ul class="nav__list">
      <li class="nav__item"><a href="<?php echo $homeUrl; ?>#home"   class="nav__link">Home</a></li>
      <li class="nav__item"><a href="<?php echo $homeUrl; ?>#skills" class="nav__link">Skills</a></li>
      <li class="nav__item"><a href="<?php echo $homeUrl; ?>#about"  class="nav__link">About</a></li>
      <li class="nav__item"><a href="<?php echo $homeUrl; ?>#work"   class="nav__link">Projects</a></li>
      <li class="nav__item"><a href="<?php echo $homeUrl; ?>#resume" class="nav__link">Resume</a></li>
    </ul>
  </nav>
</header>

<main class="project-wrap">
  <h1 class="section__title"><?php echo htmlspecialchars($selectedProject['title']); ?></h1>
  <?php if (!empty($selectedProject['short_description'])): ?>
    <p class="project-sub"><?php echo htmlspecialchars($selectedProject['short_description']); ?></p>
  <?php endif; ?>

  <!-- HORIZONTAL CAROUSEL -->
  <div class="carousel" id="carousel" aria-label="Project images">
    <div class="track" id="track">
      <?php foreach ($selectedProject['images'] as $img): ?>
        <div class="slide">
          <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($selectedProject['title']); ?>">
        </div>
      <?php endforeach; ?>
    </div>

    <div class="edge left"></div>
    <div class="edge right"></div>

    <button class="cbtn prev" id="prev" aria-label="Previous"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
    <button class="cbtn next" id="next" aria-label="Next"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>

    <div class="dots" id="dots">
      <?php for ($i=0;$i<count($selectedProject['images']);$i++): ?>
        <span class="dot<?php echo $i===0?' active':''; ?>" data-i="<?php echo $i; ?>"></span>
      <?php endfor; ?>
    </div>
  </div>

  <?php if (!empty($selectedProject['description'])): ?>
    <div class="project-details__description">
      <?php echo nl2br(htmlspecialchars($selectedProject['description'])); ?>
    </div>
  <?php endif; ?>
</main>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" aria-modal="true" role="dialog">
  <img class="lightbox__img" id="lightboxImg" alt="">
  <button class="lightbox__close" id="lbClose" aria-label="Close"><i class="fas fa-times"></i></button>
  <button class="cbtn prev" id="lbPrev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
  <button class="cbtn next" id="lbNext" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
</div>

<footer class="footer">
  <div class="footer__inner">
    <a href="mailto:eng.mostafanajjar@outlook.com" class="footer__link">eng.mostafanajjar@outlook.com</a>
    <a class="footer__link" href="http://linkedin.com/in/mostafa-najjar" target="_blank" rel="noopener">
      <i class="fab fa-linkedin"></i> LinkedIn
    </a>
  </div>
</footer>

<script>
(() => {
  const carousel = document.getElementById('carousel');
  const track    = document.getElementById('track');
  const slides   = Array.from(track.children);
  const prev     = document.getElementById('prev');
  const next     = document.getElementById('next');
  const dotsWrap = document.getElementById('dots');
  const dots     = Array.from(dotsWrap.children);

  let i = 0;
  function go(idx){
    i = (idx + slides.length) % slides.length;
    track.style.transform = `translateX(${-i * 100}%)`;
    dots.forEach((d,k)=>d.classList.toggle('active', k===i));
  }
  prev.addEventListener('click', ()=>go(i-1));
  next.addEventListener('click', ()=>go(i+1));
  dots.forEach(d=>d.addEventListener('click', ()=>go(+d.dataset.i)));

  // Keyboard arrows for carousel
  window.addEventListener('keydown', e=>{
    if (lightbox.classList.contains('open')) return;
    if(e.key === 'ArrowLeft')  go(i-1);
    if(e.key === 'ArrowRight') go(i+1);
  });

  // Mouse wheel maps to horizontal navigation
  carousel.addEventListener('wheel', e=>{
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      e.preventDefault();
      if (e.deltaY > 0) go(i+1); else go(i-1);
    }
  }, { passive:false });

  // Touch swipe
  let x0=null;
  track.addEventListener('touchstart', e=>{ x0 = e.touches[0].clientX; }, {passive:true});
  track.addEventListener('touchmove', e=>{
    if(x0===null) return;
    const dx = e.touches[0].clientX - x0;
    if (Math.abs(dx) > 60){ go(i + (dx<0 ? 1 : -1)); x0=null; }
  }, {passive:true});
  track.addEventListener('touchend', ()=>{ x0=null; });

  // ---------- Lightbox ----------
  const lightbox   = document.getElementById('lightbox');
  const lbImg      = document.getElementById('lightboxImg');
  const lbClose    = document.getElementById('lbClose');
  const lbPrev     = document.getElementById('lbPrev');
  const lbNext     = document.getElementById('lbNext');

  function openLB(idx){
    i = (idx + slides.length) % slides.length;
    const img = slides[i].querySelector('img');
    lbImg.src = img.getAttribute('src');
    lbImg.alt = img.getAttribute('alt') || '';
    lightbox.classList.add('open');
  }
  function closeLB(){ lightbox.classList.remove('open'); }
  function lbGo(di){ openLB(i + di); }

  slides.forEach((s,idx) => s.querySelector('img').addEventListener('click', ()=> openLB(idx)));
  lbClose.addEventListener('click', closeLB);
  lbPrev.addEventListener('click', ()=> lbGo(-1));
  lbNext.addEventListener('click', ()=> lbGo( 1));
  lightbox.addEventListener('click', (e)=>{ if (e.target === lightbox) closeLB(); });
  window.addEventListener('keydown', (e)=>{
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'Escape') closeLB();
    if (e.key === 'ArrowLeft')  lbGo(-1);
    if (e.key === 'ArrowRight') lbGo( 1);
  });
})();
</script>

<!-- Optional: show "Projects" as active while on project.php -->
<script>
(function(){
  document.querySelectorAll('.nav__link').forEach(a=>{
    a.classList.toggle('is-active', /#work$/.test(a.getAttribute('href')));
  });
})();
</script>

</body>
</html>

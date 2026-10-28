const THEME_KEY='portalGuruTheme';
const esc=s=>String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
const safe=u=>u&&String(u).trim()?String(u).trim():'#';

async function getData(){
  const fallback=window.PORTAL_DEFAULT_DATA||{profile:{},links:{},materials:[],modules:[],portfolio:[]};
  const api=String(window.PORTAL_API_URL||'').replace(/\/$/,'');
  if(!api)return fallback;
  try{
    const r=await fetch(api+'/api.php?action=public',{headers:{Accept:'application/json'},cache:'no-store'});
    if(!r.ok)throw new Error('API '+r.status);
    const j=await r.json();
    return j.data||j;
  }catch(e){console.warn('Backend tidak dapat diakses, memakai data lokal.',e);return fallback}
}
function setTheme(){const t=localStorage.getItem(THEME_KEY);if(t)document.documentElement.dataset.theme=t}
function toggleTheme(){const t=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=t;localStorage.setItem(THEME_KEY,t)}
function applyProfile(d){
  const p=d.profile||{},l=d.links||{};
  document.querySelectorAll('[data-name]').forEach(x=>x.textContent=p.name||'Guru');
  document.querySelectorAll('[data-title]').forEach(x=>x.textContent=p.title||'');
  document.querySelectorAll('[data-school]').forEach(x=>x.textContent=p.school||'');
  document.querySelectorAll('[data-location]').forEach(x=>x.textContent=p.location||'');
  document.querySelectorAll('[data-bio]').forEach(x=>x.textContent=p.bio||'');
  document.querySelectorAll('[data-photo]').forEach(x=>x.src=safe(p.photo));
  document.querySelectorAll('[data-logo]').forEach(x=>x.src=safe(p.schoolLogo));
  document.querySelectorAll('[data-email]').forEach(x=>x.href='mailto:'+(p.email||''));
  document.querySelectorAll('[data-whatsapp]').forEach(x=>x.href=safe(p.whatsapp));
  document.querySelectorAll('[data-sibela]').forEach(x=>x.href=safe(l.sibela));
  document.querySelectorAll('[data-classroom]').forEach(x=>x.href=safe(l.classroom));
  document.querySelectorAll('[data-drive]').forEach(x=>x.href=safe(l.drive));
  document.querySelectorAll('[data-github]').forEach(x=>x.href=safe(l.github));
  document.querySelectorAll('[data-school-link]').forEach(x=>x.href=safe(l.schoolWebsite));
  const q=document.querySelector('[data-qr]');
  if(q&&l.sibela&&l.sibela!=='#')q.src='https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='+encodeURIComponent(l.sibela);
}
function cardsMaterial(d,limit){
  const root=document.querySelector('[data-material-list]');if(!root)return;
  const q=(document.querySelector('[data-search]')?.value||'').toLowerCase(),c=document.querySelector('[data-class-filter]')?.value||'';
  let items=(d.materials||[]).filter(m=>{const h=[m.title,m.summary,m.tp,m.className,m.mapel,...(m.tags||[])].join(' ').toLowerCase();return(!q||h.includes(q))&&(!c||m.className===c)});
  if(limit)items=items.slice(0,limit);
  root.innerHTML=items.length?items.map(m=>`<article class="card material-card"><span class="badge">${esc(m.className)} · ${esc(m.tp)}</span><h3>${esc(m.title)}</h3><p class="muted">${esc(m.summary)}</p><div class="tags">${(m.tags||[]).map(t=>`<span class="tag">${esc(t)}</span>`).join('')}</div><a class="btn btn-primary" href="tp.html?id=${encodeURIComponent(m.id)}">Lihat detail TP</a></article>`).join(''):'<div class="notice">Materi tidak ditemukan.</div>';
}
function cardsPortfolio(d,limit){
  const root=document.querySelector('[data-portfolio-list]');if(!root)return;
  let items=d.portfolio||[];if(limit)items=items.slice(0,limit);
  root.innerHTML=items.length?items.map(p=>`<article class="card portfolio-card"><img src="${esc(safe(p.image))}" alt="${esc(p.title)}"><div class="body"><span class="badge">${esc(p.className)}</span><h3>${esc(p.title)}</h3><p class="muted">${esc(p.description)}</p><p><strong>${esc(p.student)}</strong></p><div class="actions"><a class="btn btn-primary" href="${esc(safe(p.demoUrl))}" target="_blank" rel="noopener">Demo</a><a class="btn" href="${esc(safe(p.repoUrl))}" target="_blank" rel="noopener">Repositori</a></div></div></article>`).join(''):'<div class="notice">Belum ada portofolio.</div>';
}
function cardsModules(d){
  const root=document.querySelector('[data-module-list]');if(!root)return;
  const q=(document.querySelector('[data-module-search]')?.value||'').toLowerCase(),c=document.querySelector('[data-module-class]')?.value||'';
  const items=(d.modules||[]).filter(m=>{const h=[m.title,m.className,m.type,m.description].join(' ').toLowerCase();return(!q||h.includes(q))&&(!c||m.className===c)});
  root.innerHTML=items.length?items.map(m=>`<div class="card download-item"><div><span class="badge">${esc(m.className)} · ${esc(m.type)}</span><h3>${esc(m.title)}</h3><p class="muted">${esc(m.description)}</p></div><a class="btn btn-primary" href="${esc(safe(m.fileUrl))}" target="_blank" rel="noopener">Buka / Download</a></div>`).join(''):'<div class="notice">Modul atau LKPD tidak ditemukan.</div>';
}
function renderTp(d){
  const root=document.querySelector('[data-tp-detail]');if(!root)return;
  const id=new URLSearchParams(location.search).get('id'),m=(d.materials||[]).find(x=>x.id===id);
  if(!m){root.innerHTML='<div class="content-card"><h2>TP tidak ditemukan</h2><p class="muted">Periksa kembali tautan materi.</p></div>';return}
  document.title=m.tp+' - '+m.title;
  root.innerHTML=`<div class="content-card"><span class="badge">${esc(m.className)} · ${esc(m.mapel)}</span><h1>${esc(m.tp)} · ${esc(m.title)}</h1><p class="lead">${esc(m.summary)}</p><hr style="border:0;border-top:1px solid var(--border);margin:24px 0"><h2>Deskripsi Pembelajaran</h2><p>${esc(m.content)}</p><h2>Kata Kunci</h2><div class="tags">${(m.tags||[]).map(t=>`<span class="tag">${esc(t)}</span>`).join('')}</div></div><aside class="sidebar"><div class="card"><h3>Informasi TP</h3><p><strong>Kelas</strong><br>${esc(m.className)}</p><p><strong>Mata Pelajaran</strong><br>${esc(m.mapel)}</p><p><strong>Kode</strong><br>${esc(m.tp)}</p></div><div class="card"><h3>Materi Pendukung</h3><a class="btn btn-primary" style="width:100%" href="${esc(safe(m.resourceUrl))}" target="_blank" rel="noopener">Buka / Download</a></div><a class="btn" href="materi.html">← Kembali ke Materi</a></aside>`;
}
function nav(){
  document.querySelectorAll('[data-theme-toggle]').forEach(b=>b.onclick=toggleTheme);
  document.querySelectorAll('[data-menu]').forEach(b=>b.onclick=()=>document.querySelector('.mobile-nav')?.classList.toggle('open'));
  const p=location.pathname.split('/').pop()||'index.html';document.querySelectorAll('.nav-links a,.mobile-nav a').forEach(a=>{if(a.getAttribute('href')===p)a.classList.add('active')});
}
document.addEventListener('DOMContentLoaded',async()=>{
  setTheme();nav();const d=await getData();applyProfile(d);cardsMaterial(d,document.body.dataset.home==='1'?3:null);cardsPortfolio(d,document.body.dataset.home==='1'?3:null);cardsModules(d);renderTp(d);
  document.querySelector('[data-search]')?.addEventListener('input',()=>cardsMaterial(d));document.querySelector('[data-class-filter]')?.addEventListener('change',()=>cardsMaterial(d));document.querySelector('[data-module-search]')?.addEventListener('input',()=>cardsModules(d));document.querySelector('[data-module-class]')?.addEventListener('change',()=>cardsModules(d));document.querySelectorAll('[data-year]').forEach(x=>x.textContent=new Date().getFullYear());
});
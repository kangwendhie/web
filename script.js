const config = window.SITE_CONFIG || {};
const teacher = config.teacher || {};
const links = config.links || {};

const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => [...document.querySelectorAll(selector)];

function setText(selector, value) {
  const el = $(selector);
  if (el && value) el.textContent = value;
}

function setHref(selector, value) {
  const el = $(selector);
  if (el && value) el.href = value;
}

function hydrateProfile() {
  setText('[data-name]', teacher.name);
  setText('[data-title]', teacher.title);
  setText('[data-school]', teacher.school);
  setText('[data-location]', teacher.location);
  setText('[data-bio]', teacher.bio);
  const photo = $('[data-photo]');
  const logo = $('[data-logo]');
  if (photo && teacher.photo) photo.src = teacher.photo;
  if (logo && teacher.schoolLogo) logo.src = teacher.schoolLogo;
  if (teacher.email) {
    setText('[data-email-text]', teacher.email);
    setHref('[data-email-link]', `mailto:${teacher.email}`);
  }
  setHref('[data-whatsapp]', teacher.whatsapp);
  setHref('[data-sibela]', links.sibela);
  setHref('[data-classroom]', links.classroom);
  setHref('[data-drive]', links.drive);
  setHref('[data-github]', links.github);
  setHref('[data-school-link]', links.schoolWebsite);

  const stats = $('#stats');
  if (stats && Array.isArray(config.statistics)) {
    stats.innerHTML = config.statistics.map(item => `
      <div class="stat"><strong>${item.value}</strong><span>${item.label}</span></div>
    `).join('');
  }
}

function initMenu() {
  const menuBtn = $('#menuBtn');
  const navLinks = $('#navLinks');
  if (!menuBtn || !navLinks) return;
  menuBtn.addEventListener('click', () => {
    const open = navLinks.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', String(open));
  });
  navLinks.addEventListener('click', e => {
    if (e.target.tagName === 'A') navLinks.classList.remove('open');
  });
}

function initTheme() {
  const btn = $('#themeBtn');
  const saved = localStorage.getItem('guru-theme');
  const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  const theme = saved || preferred;
  document.documentElement.dataset.theme = theme;
  if (btn) btn.textContent = theme === 'dark' ? '☀' : '◐';
  btn?.addEventListener('click', () => {
    const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = next;
    localStorage.setItem('guru-theme', next);
    btn.textContent = next === 'dark' ? '☀' : '◐';
  });
}

function initTabs() {
  $$('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      $$('.tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const filter = tab.dataset.filter;
      $$('[data-resource]').forEach(card => {
        const category = card.dataset.resource;
        card.hidden = filter !== 'all' && category !== filter;
      });
    });
  });
}

hydrateProfile();
initMenu();
initTheme();
initTabs();
setText('#year', new Date().getFullYear());

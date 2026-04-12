/*!
 * animations.js — مخيّم
 * GSAP 3.12.5 + ScrollTrigger — تأثيرات مشتركة
 */
gsap.registerPlugin(ScrollTrigger);
gsap.config({ nullTargetWarn: false });
ScrollTrigger.config({ limitCallbacks: true });

// السكريبت في نهاية </body> — DOM جاهز دائماً
// rAF واحد يضمن أن المتصفح رسم الصفحة مرة واحدة قبل البدء
requestAnimationFrame(function () {

  // ── ١. شريط القراءة ────────────────────────────────────────
  var bar = document.getElementById('scrollBar');
  if (bar) {
    ScrollTrigger.create({
      start: 0, end: 'max',
      onUpdate: function (self) {
        gsap.set(bar, { height: (self.progress * 100) + '%' });
      }
    });
  }

  // ── ٢. تصغير الهيدر عند الـ scroll ─────────────────────────
  var hdr  = document.querySelector('.site-header');
  var lTxt = document.querySelector('.logo-arabic');
  var lImg = document.querySelector('.logo-img img');
  if (hdr) {
    ScrollTrigger.create({
      start: 'top -70', end: 99999,
      onEnter: function () {
        gsap.to(hdr, { paddingTop: '5px', paddingBottom: '5px', duration: 0.38, ease: 'power2.out' });
        if (lTxt) gsap.to(lTxt, { fontSize: '40px',  duration: 0.38, ease: 'power2.out' });
        if (lImg) gsap.to(lImg, { maxHeight: '44px', duration: 0.38, ease: 'power2.out' });
      },
      onLeaveBack: function () {
        gsap.to(hdr, { clearProps: 'paddingTop,paddingBottom', duration: 0.3 });
        if (lTxt) gsap.to(lTxt, { clearProps: 'fontSize',  duration: 0.3 });
        if (lImg) gsap.to(lImg, { clearProps: 'maxHeight', duration: 0.3 });
      }
    });
  }

  // ── ٣. Hover على الكاردات ────────────────────────────────────
  document.querySelectorAll('.art-card, .cat-feat-side-card, .hm-side-card, .cat-list-item').forEach(function (card) {
    card.addEventListener('mouseenter', function () {
      gsap.to(card, { duration: 0.22, ease: 'power2.out' });
    });
    card.addEventListener('mouseleave', function () {
      gsap.to(card, { duration: 0.18, ease: 'power2.in'  });
    });
  });

  // ── ٤. انتقالات الصفحات ──────────────────────────────────────
  var mainEl = document.querySelector('main');
  if (mainEl) {
    gsap.from(mainEl, { opacity: 0, y: 8, duration: 0.45, ease: 'power2.out' });

    document.querySelectorAll('a[href]').forEach(function (a) {
      if (!a.href || a.target === '_blank') return;
      try {
        var url = new URL(a.href, window.location.origin);
        if (url.origin !== window.location.origin) return;
        if (url.hash && url.pathname === window.location.pathname) return;
      } catch (e) { return; }

      a.addEventListener('click', function (e) {
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
        e.preventDefault();
        var dest = a.href;
        gsap.to(mainEl, {
          opacity: 0, y: -8, duration: 0.25, ease: 'power2.in',
          onComplete: function () { window.location.href = dest; }
        });
      });
    });
  }

  // ── ٥. مؤشر ذهبي — Desktop فقط ──────────────────────────────
  if (window.innerWidth > 1024 && !('ontouchstart' in window)) {
    var dot = document.createElement('div');
    Object.assign(dot.style, {
      position:      'fixed',
      width:         '8px',
      height:        '8px',
      borderRadius:  '50%',
      background:    'var(--gold,#b8902a)',
      pointerEvents: 'none',
      zIndex:        '9999',
      opacity:       '0',
      willChange:    'transform',
      transform:     'translate(-50%,-50%)'
    });
    document.body.appendChild(dot);

    var xTo = gsap.quickTo(dot, 'x', { duration: 0.14, ease: 'power3.out' });
    var yTo = gsap.quickTo(dot, 'y', { duration: 0.14, ease: 'power3.out' });

    window.addEventListener('mousemove', function (e) {
      gsap.to(dot, { opacity: 0.85, duration: 0.2 });
      xTo(e.clientX); yTo(e.clientY);
    });
    document.querySelectorAll('h1,h2,h3,a,.btn-gold,.btn-ink,.btn-read,.art-card,.hm-main,.value-card,.team-card').forEach(function (el) {
      el.addEventListener('mouseenter', function () { gsap.to(dot, { scale: 3.2, opacity: 0.4, duration: 0.2, ease: 'power2.out' }); });
      el.addEventListener('mouseleave', function () { gsap.to(dot, { scale: 1,   opacity: 0.85, duration: 0.18, ease: 'power2.in' }); });
    });
  }

}); // end rAF

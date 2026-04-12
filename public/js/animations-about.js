/*!
 * animations-about.js — صفحة عن مخيّم
 */
(function () {
  'use strict';

  // ── إخفاء فوري ──────────────────────────────────────────────
  gsap.set('.about-hero h1', { y: 40, opacity: 0 });
  gsap.set('.about-hero p, .about-hero .badge', { opacity: 0 });

  requestAnimationFrame(function () {

    // ① About Hero
    var heroTl = gsap.timeline({ delay: 0.15 });
    heroTl.to('.about-hero .badge', { opacity: 1, duration: 0.42, ease: 'power2.out' });
    heroTl.to('.about-hero h1',     { y: 0, opacity: 1, duration: 0.85, ease: 'power3.out' }, '-=0.22');
    heroTl.to('.about-hero p',      { opacity: 1, duration: 0.62, ease: 'power2.out' }, '-=0.38');

    // ② about-body
    document.querySelectorAll('.about-body > h2').forEach(function (h2) {
      gsap.from(h2, { y: 24, opacity: 0, duration: 0.62, ease: 'power3.out',
        scrollTrigger: { trigger: h2, start: 'top 88%' } });
    });
    document.querySelectorAll('.about-body > p').forEach(function (p) {
      gsap.from(p,  { y: 18, opacity: 0, duration: 0.58, ease: 'power2.out',
        scrollTrigger: { trigger: p, start: 'top 90%' } });
    });
    document.querySelectorAll('.about-divider').forEach(function (div) {
      gsap.from(div, { opacity: 0, scale: 0.65, duration: 0.62, ease: 'back.out(1.6)',
        scrollTrigger: { trigger: div, start: 'top 92%' } });
    });

    // ③ Values Grid
    document.querySelectorAll('.value-card').forEach(function (card, i) {
      gsap.set(card, { borderTopWidth: '0px' });
      var cardTl = gsap.timeline({
        scrollTrigger: { trigger: '.values-grid', start: 'top 85%' },
        delay: i * 0.12
      });
      cardTl.from(card, { y: 34, opacity: 0, duration: 0.65, ease: 'power3.out' });
      cardTl.to(card,   { borderTopWidth: '3px', duration: 0.32, ease: 'power2.out' }, '-=0.45');
    });

    // ④ Team Grid
    if (document.querySelectorAll('.team-card').length) {
      gsap.from('.team-card', {
        y: 30, opacity: 0, duration: 0.65, stagger: 0.1, ease: 'power3.out',
        scrollTrigger: { trigger: '.team-grid', start: 'top 86%' }
      });
      gsap.from('.team-avatar', {
        scale: 0.75, opacity: 0, duration: 0.58, stagger: 0.1, ease: 'power3.out',
        scrollTrigger: { trigger: '.team-grid', start: 'top 86%' }
      });
    }

    // ⑤ Submit CTA
    var cta = document.querySelector('.submit-cta');
    if (cta) {
      gsap.to(cta, {
        y: -14, ease: 'none',
        scrollTrigger: { trigger: cta, start: 'top bottom', end: 'bottom top', scrub: 1.8 }
      });
      var ctaEls = cta.querySelectorAll('h2, p, a');
      // إذا كان العنصر مرئياً عند التحميل، شغّل الأنيميشن فوراً
      var rect = cta.getBoundingClientRect();
      if (rect.top < window.innerHeight) {
        gsap.from(ctaEls, { y: 24, opacity: 0, duration: 0.62, stagger: 0.14, ease: 'power3.out' });
      } else {
        gsap.from(ctaEls, {
          y: 24, opacity: 0, duration: 0.62, stagger: 0.14, ease: 'power3.out',
          scrollTrigger: { trigger: cta, start: 'top 95%' }
        });
      }
    }

  });
}());

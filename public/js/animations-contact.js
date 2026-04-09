/*!
 * animations-contact.js — تواصل معنا
 */
(function () {
  'use strict';

  // ── إخفاء فوري ──────────────────────────────────────────────
  gsap.set('.cat-hero h1', { y: 32, opacity: 0 });
  gsap.set('.cat-hero p, .cat-hero .cat-hero-label', { opacity: 0 });

  requestAnimationFrame(function () {

    // ① Hero
    var heroTl = gsap.timeline({ delay: 0.15 });
    heroTl.to('.cat-hero .cat-hero-label', { opacity: 1, duration: 0.42, ease: 'power2.out' });
    heroTl.to('.cat-hero h1', { y: 0, opacity: 1, duration: 0.75, ease: 'power3.out' }, '-=0.2');
    heroTl.to('.cat-hero p',  { opacity: 1, duration: 0.55, ease: 'power2.out' }, '-=0.35');

    // ② Form Card
    gsap.from('.contact-form-card', {
      y: 40, opacity: 0, duration: 0.75, ease: 'power3.out',
      scrollTrigger: { trigger: '.contact-form-card', start: 'top 90%' }
    });

    // ③ Info Cards
    document.querySelectorAll('.contact-info-card').forEach(function (card, i) {
      gsap.from(card, {
        x: -28, opacity: 0, duration: 0.6, ease: 'power3.out',
        delay: i * 0.12,
        scrollTrigger: { trigger: '.contact-info', start: 'top 88%' }
      });
    });

  });
}());

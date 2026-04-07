/*!
 * animations-category.js — صفحة التصنيف
 */
(function () {
  'use strict';

  // ── إخفاء فوري ──────────────────────────────────────────────
  gsap.set('.cat-hero h1', { y: 32, opacity: 0 });
  gsap.set('.cat-hero p, .cat-hero .cat-hero-label', { opacity: 0 });

  requestAnimationFrame(function () {

    // ① Cat Hero
    var heroTl = gsap.timeline({ delay: 0.15 });
    heroTl.to('.cat-hero .cat-hero-label', { opacity: 1, duration: 0.42, ease: 'power2.out' });
    heroTl.to('.cat-hero h1', { y: 0, opacity: 1, duration: 0.75, ease: 'power3.out' }, '-=0.2');
    heroTl.to('.cat-hero p',  { opacity: 1, duration: 0.55, ease: 'power2.out' }, '-=0.35');

    // ② البطاقة المميزة
    if (document.querySelector('.cat-feat-main')) {
      var featTl = gsap.timeline({
        scrollTrigger: { trigger: '.cat-featured', start: 'top 88%' }
      });
      featTl.from('.cat-feat-main',        { scale: 1.04, opacity: 0, duration: 0.95, ease: 'power3.out' });
      featTl.from('.cat-feat-info .badge', { y: -12, opacity: 0, duration: 0.38, ease: 'power2.out' }, '-=0.52');
      featTl.from('.cat-feat-info h2',     { y: 26, opacity: 0, duration: 0.58, ease: 'power3.out' }, '-=0.32');
      featTl.from('.cat-feat-info p',      { y: 18, opacity: 0, duration: 0.48, ease: 'power2.out' }, '-=0.28');
      featTl.from('.cat-feat-side-card',   { x: 30, opacity: 0, duration: 0.62, stagger: 0.13, ease: 'power3.out' }, '-=0.55');
    }

    // ③ قائمة المقالات
    document.querySelectorAll('.cat-list-item').forEach(function (item) {
      var itemTl = gsap.timeline({ scrollTrigger: { trigger: item, start: 'top 89%' } });
      if (item.querySelector('.clbody'))   itemTl.from(item.querySelector('.clbody'),   { y: 24, opacity: 0, duration: 0.58, ease: 'power3.out' });
      if (item.querySelector('.cat-thumb'))itemTl.from(item.querySelector('.cat-thumb'), { x: -20, opacity: 0, duration: 0.52, ease: 'power3.out' }, '-=0.38');
    });

    // ④ Sidebar
    var widgets = document.querySelectorAll('.sidebar-aside .widget, .sidebar-aside .nl-box');
    if (widgets.length) {
      gsap.from(widgets, {
        x: -22, opacity: 0, duration: 0.62, stagger: 0.16, ease: 'power3.out',
        scrollTrigger: { trigger: '.sidebar-aside', start: 'top 88%' }
      });
    }

  });
}());

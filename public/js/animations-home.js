/*!
 * animations-home.js — الرئيسية
 * gsap.set خارج rAF لإخفاء العناصر قبل أول رسم
 * gsap.to داخل rAF لبدء الأنيميشن بعد رسم الـ layout
 */
(function () {
  'use strict';

  // ── إخفاء فوري قبل أول رسم ──────────────────────────────────
  // يُنفَّذ أثناء تحليل السكريبت (بعد تحميل GSAP مباشرة)
  if (document.querySelector('.hm-main')) {
    gsap.set('.hm-main',       { x: 50, opacity: 0 });
    gsap.set('.hm-side-card',  { x: 32, opacity: 0 });
    gsap.set('.hm-strip-card', { y: 22, opacity: 0 });
  }
  gsap.set('.logo-arabic, .logo-img', { y: -16, opacity: 0 });

  // ── rAF: بدء الأنيميشن ──────────────────────────────────────
  requestAnimationFrame(function () {

    // ════════════════════════════════════════════
    // ① اللوغو
    // ════════════════════════════════════════════
    gsap.to('.logo-arabic, .logo-img', {
      y: 0, opacity: 1, duration: 0.6, ease: 'power3.out', stagger: 0.05
    });

    // ════════════════════════════════════════════
    // ② Hero Mosaic
    // ════════════════════════════════════════════
    if (document.querySelector('.hm-main')) {
      var tl = gsap.timeline({ delay: 0.2 });

      tl.to('.hm-main', { x: 0, opacity: 1, duration: 0.85, ease: 'power3.out' });
      tl.from('.hm-main-info .badge',    { y: -14, opacity: 0, duration: 0.40, ease: 'power2.out' }, '-=0.52');
      tl.from('.hm-main-info h1',        { y: 22,  opacity: 0, duration: 0.55, ease: 'power3.out' }, '-=0.30');
      tl.from('.hm-main-info p',         { y: 16,  opacity: 0, duration: 0.45, ease: 'power2.out' }, '-=0.28');
      tl.from('.hm-main-info .btn-read', { y: 10,  opacity: 0, duration: 0.40, ease: 'power2.out' }, '-=0.22');
      tl.to('.hm-side-card',  { x: 0, opacity: 1, duration: 0.62, stagger: 0.13, ease: 'power3.out' }, '-=0.45');
      tl.to('.hm-strip-card', { y: 0, opacity: 1, duration: 0.55, stagger: 0.10, ease: 'power3.out' }, '-=0.35');
    }

    // ════════════════════════════════════════════
    // ③–⑦ عناصر تحت الـ fold — ScrollTrigger
    // ════════════════════════════════════════════

    // عناوين الأقسام
    gsap.utils.toArray('.sec-head').forEach(function (el) {
      gsap.from(el, {
        y: 22, opacity: 0, duration: 0.6, ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 88%' }
      });
    });

    // شبكة المقالات
    var artCards = document.querySelectorAll('.cards-grid .art-card');
    if (artCards.length) {
      gsap.from(artCards, {
        y: 42, opacity: 0, duration: 0.68, stagger: 0.1, ease: 'power3.out',
        scrollTrigger: { trigger: '.cards-grid', start: 'top 88%' }
      });
    }

    // Long Read Banner
    var lr = document.querySelector('.longread');
    if (lr) {
      var lrTl = gsap.timeline({
        scrollTrigger: { trigger: lr, start: 'top 85%' }
      });
      lrTl.from(lr, { clipPath: 'inset(0 0 0 100%)', duration: 0.9, ease: 'power3.out' });
      var lrLabel = lr.querySelector('.lr-label');
      var lrH2    = lr.querySelector('h2');
      var lrP     = lr.querySelector('p');
      var lrBtn   = lr.querySelector('.btn-gold');
      if (lrLabel) lrTl.from(lrLabel, { y: 18, opacity: 0, duration: 0.42, ease: 'power2.out' }, '-=0.45');
      if (lrH2)    lrTl.from(lrH2,    { y: 30, opacity: 0, duration: 0.58, ease: 'power3.out' }, '-=0.30');
      if (lrP)     lrTl.from(lrP,     { y: 20, opacity: 0, duration: 0.48, ease: 'power2.out' }, '-=0.28');
      if (lrBtn)   lrTl.from(lrBtn,   { scale: 0.86, opacity: 0, duration: 0.42, ease: 'back.out(1.3)' }, '-=0.22');
    }

    // قائمة المقالات
    if (document.querySelectorAll('.list-item-row').length) {
      gsap.from('.list-item-row', {
        x: 30, opacity: 0, duration: 0.62, stagger: 0.1, ease: 'power3.out',
        scrollTrigger: { trigger: '.list-feed', start: 'top 88%' }
      });
    }

    // Sidebar
    var widgets = document.querySelectorAll('.sidebar-aside .widget, .sidebar-aside .nl-box');
    if (widgets.length) {
      gsap.from(widgets, {
        x: -24, opacity: 0, duration: 0.65, stagger: 0.18, ease: 'power3.out',
        scrollTrigger: { trigger: '.sidebar-aside', start: 'top 88%' }
      });
    }

  }); // end rAF

}());

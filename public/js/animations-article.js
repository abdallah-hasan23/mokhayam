/*!
 * animations-article.js — صفحة المقال
 */
(function () {
  'use strict';

  // ── إخفاء فوري ──────────────────────────────────────────────
  gsap.set('.art-author-avatar, .art-author-info, .article-meta-top .badge, .art-share-top', { opacity: 0 });
  gsap.set('.article-title', { y: 30, opacity: 0 });

  requestAnimationFrame(function () {

    // ① Ken Burns
    var heroImg = document.querySelector('.article-hero img');
    if (heroImg) {
      gsap.set(heroImg, { willChange: 'transform' });
      gsap.fromTo(heroImg, { scale: 1.0 }, { scale: 1.07, duration: 9, ease: 'none' });
    }

    // ② Meta + عنوان
    var metaTl = gsap.timeline({ delay: 0.25 });
    metaTl.to('.art-author-avatar',       { opacity: 1, scale: 1, duration: 0.48, ease: 'power3.out' });
    gsap.set('.art-author-avatar',        { scale: 0.72 });
    metaTl.from('.art-author-info',        { x: 16,  duration: 0.44, ease: 'power3.out' }, '-=0.25');
    metaTl.to('.art-author-info',          { opacity: 1, duration: 0.44 }, '-=0.44');
    metaTl.from('.article-meta-top .badge',{ y: -12, duration: 0.40, ease: 'power2.out' }, '-=0.22');
    metaTl.to('.article-meta-top .badge',  { opacity: 1, duration: 0.40 }, '-=0.40');
    metaTl.from('.art-share-top',          { x: -18, duration: 0.42, ease: 'power3.out' }, '-=0.24');
    metaTl.to('.art-share-top',            { opacity: 1, duration: 0.42 }, '-=0.42');

    // عنوان المقال
    var titleTl = gsap.timeline({ delay: 0.45 });
    titleTl.to('.article-title', { y: 0, opacity: 1, duration: 0.78, ease: 'power3.out' });

    // ③ Article Deck
    var deck = document.querySelector('.article-deck');
    if (deck) {
      gsap.set(deck, { borderRightWidth: '0px' });
      gsap.from(deck, { y: 18, opacity: 0, duration: 0.65, ease: 'power2.out', delay: 0.7 });
      gsap.to(deck, { borderRightWidth: '4px', duration: 0.5, ease: 'power2.out', delay: 0.7 });
    }

    // ④ فقرات جسم المقال
    var body = document.querySelector('.article-body');
    if (body) {
      body.querySelectorAll('p, h2, h3, h4, ul, ol').forEach(function (el) {
        gsap.from(el, {
          y: 24, opacity: 0, duration: 0.65, ease: 'power3.out',
          scrollTrigger: { trigger: el, start: 'top 90%' }
        });
      });
      body.querySelectorAll('blockquote').forEach(function (bq) {
        gsap.set(bq, { borderRightWidth: '0px' });
        var bqTl = gsap.timeline({ scrollTrigger: { trigger: bq, start: 'top 88%' } });
        bqTl.from(bq, { x: 26, opacity: 0, duration: 0.6, ease: 'power3.out' });
        bqTl.to(bq, { borderRightWidth: '4px', duration: 0.35, ease: 'power2.out' }, 0);
      });
    }

    // ⑤ المقالات ذات الصلة
    if (document.querySelectorAll('.related-grid .art-card').length) {
      gsap.from('.related-grid .art-card', {
        y: 35, opacity: 0, duration: 0.68, stagger: 0.13, ease: 'power3.out',
        scrollTrigger: { trigger: '.related-grid', start: 'top 88%' }
      });
    }

    // ⑥ Author Box
    var authorBox = document.querySelector('.author-box');
    if (authorBox) {
      gsap.from(authorBox, {
        y: 26, opacity: 0, duration: 0.65, ease: 'power3.out',
        scrollTrigger: { trigger: authorBox, start: 'top 88%' }
      });
    }

  });
}());

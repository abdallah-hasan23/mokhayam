/* Mukhayyam Frontend JS  —  public/js/app.js */
document.addEventListener('DOMContentLoaded', function () {

    /* Scroll progress bar */
    const bar = document.getElementById('scrollBar');
    if (bar) {
        window.addEventListener('scroll', function () {
            const pct = window.scrollY / (document.body.scrollHeight - window.innerHeight);
            bar.style.height = Math.min(pct * 100, 100) + '%';
        }, { passive: true });
    }

    /* Active nav based on current URL */
    document.querySelectorAll('.main-nav ul li a').forEach(function (link) {
        if (link.getAttribute('href') === window.location.pathname) {
            link.classList.add('active');
        }
    });

    /* Subscribe form AJAX */
    document.querySelectorAll('.nl-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const btn = form.querySelector('button[type=submit]');
            if (!btn) return;
            btn.textContent = 'جاري الاشتراك...';
            btn.disabled = true;
        });
    });

    /* Copy link */
    window.copyLink = function (url) {
        navigator.clipboard.writeText(url || window.location.href).then(function () {
            const tip = document.createElement('div');
            tip.textContent = 'تم نسخ الرابط ✓';
            tip.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:var(--ink);color:var(--sand);padding:10px 20px;font-family:Cairo,sans-serif;font-size:13px;z-index:9999';
            document.body.appendChild(tip);
            setTimeout(() => tip.remove(), 2500);
        });
    };

    /* Lazy images */
    if ('IntersectionObserver' in window) {
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { img.src = img.dataset.src; obs.unobserve(img); }
                });
            }, { rootMargin: '200px' }).observe(img);
        });
    }
});

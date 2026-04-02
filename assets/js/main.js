/**
 * Mukhayyam — Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 1. SCROLL PROGRESS BAR
    // ============================================================
    const scrollBar = document.getElementById('scrollBar');
    if (scrollBar) {
        window.addEventListener('scroll', function () {
            const scrollTop    = window.scrollY;
            const docHeight    = document.body.scrollHeight - window.innerHeight;
            const scrollPct    = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            scrollBar.style.height = Math.min(scrollPct, 100) + '%';
        }, { passive: true });
    }

    // ============================================================
    // 2. ACTIVE NAV ITEM (يحدد الصفحة الحالية)
    // ============================================================
    const currentPath = window.location.pathname;
    document.querySelectorAll('.main-nav ul li a').forEach(function (link) {
        if (link.getAttribute('href') === currentPath ||
            (currentPath !== '/' && link.getAttribute('href') !== '/' && currentPath.includes(link.getAttribute('href')))) {
            link.classList.add('active');
        }
    });

    // ============================================================
    // 3. SMOOTH ARTICLE CARD HOVER
    // ============================================================
    document.querySelectorAll('.art-card, .hm-strip-card, .list-item-row, .cat-list-item').forEach(function (card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function (e) {
            // فقط لو ما كانت على رابط داخلي
            if (e.target.tagName !== 'A') {
                const link = card.querySelector('a[href]');
                if (link) window.location.href = link.href;
            }
        });
    });

    // ============================================================
    // 4. NEWSLETTER FORM (Fallback - بدون Plugin)
    // ============================================================
    document.querySelectorAll('.nl-form button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input && input.value && input.value.includes('@')) {
                // هنا ممكن تربط API نشرة بريدية
                btn.textContent = 'شكراً! ✓';
                btn.style.background = '#2a6a2a';
                input.value = '';
                setTimeout(() => {
                    btn.textContent = 'اشترك الآن';
                    btn.style.background = '';
                }, 3000);
            } else {
                input.style.borderColor = 'var(--rust)';
                setTimeout(() => input.style.borderColor = '', 2000);
            }
        });
    });

    // ============================================================
    // 5. LAZY LOAD IMAGES (بدون Library)
    // ============================================================
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        }, { rootMargin: '200px' });

        images.forEach(img => observer.observe(img));
    }

    // ============================================================
    // 6. READING PROGRESS IN ARTICLE (للمقالات)
    // ============================================================
    const articleBody = document.querySelector('.article-body');
    if (articleBody) {
        const updateProgress = function () {
            const rect   = articleBody.getBoundingClientRect();
            const total  = articleBody.offsetHeight;
            const read   = Math.max(0, -rect.top);
            const pct    = Math.min((read / total) * 100, 100);
            if (scrollBar) scrollBar.style.height = pct + '%';
        };
        window.addEventListener('scroll', updateProgress, { passive: true });
    }

    // ============================================================
    // 7. MOBILE MENU TOGGLE (للموبايل)
    // ============================================================
    const menuToggle = document.getElementById('menu-toggle');
    const mainNav    = document.querySelector('.main-nav');
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            mainNav.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded',
                mainNav.classList.contains('open') ? 'true' : 'false');
        });
    }

});

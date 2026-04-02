/* Mukhayyam Dashboard JS  —  public/js/dashboard.js */
document.addEventListener('DOMContentLoaded', function () {

    /* Sidebar toggle */
    window.toggleSidebar = function () {
        document.getElementById('sidebar').classList.toggle('collapsed');
        localStorage.setItem('sidebar_collapsed',
            document.getElementById('sidebar').classList.contains('collapsed'));
    };
    if (localStorage.getItem('sidebar_collapsed') === 'true') {
        document.getElementById('sidebar')?.classList.add('collapsed');
    }

    /* Notifications */
    window.toggleNotif = function () {
        document.getElementById('notifPanel').classList.toggle('open');
    };
    window.closeNotif = function () {
        document.getElementById('notifPanel').classList.remove('open');
    };
    document.addEventListener('click', function (e) {
        const panel = document.getElementById('notifPanel');
        if (panel && !panel.contains(e.target) && !e.target.closest('.tb-icon-btn')) {
            panel.classList.remove('open');
        }
    });

    /* Auto-dismiss alerts */
    document.querySelectorAll('.alert').forEach(function (el) {
        setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .5s'; setTimeout(() => el.remove(), 500); }, 4000);
    });

    /* Close modals on backdrop click */
    document.querySelectorAll('[id$="-modal"]').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    });

    /* Toggle feature switches */
    window.toggleSwitch = function (el) { el.classList.toggle('on'); };

});

import './bootstrap';

window.Pusher = Pusher;

document.addEventListener('DOMContentLoaded', function () {

    /* ── Admin sidebar toggle (mobile) ─────────────────────────────────── */
    const adminSidebar  = document.getElementById('sidebar');
    const adminOverlay  = document.getElementById('overlay');
    const adminToggle   = document.getElementById('sidebarToggle');

    function openAdminSidebar() {
        adminSidebar.classList.add('show');
        adminOverlay.classList.add('show');
    }
    function closeAdminSidebar() {
        adminSidebar.classList.remove('show');
        adminOverlay.classList.remove('show');
    }

    if (adminToggle && adminSidebar) {
        adminToggle.addEventListener('click', function () {
            adminSidebar.classList.contains('show') ? closeAdminSidebar() : openAdminSidebar();
        });
    }
    if (adminOverlay) {
        adminOverlay.addEventListener('click', closeAdminSidebar);
    }

    /* ── Student sidebar expand/collapse ───────────────────────────────── */
    const studentToggleBtn = document.getElementById('studentSidebarToggleBtn');
    const studentSidebar   = document.getElementById('studentSidebar');
    const studentOverlay   = document.getElementById('studentOverlay');
    const mainContent      = document.getElementById('mainContent');

    function openStudentSidebar() {
        studentSidebar.classList.add('expanded');
        if (studentOverlay) studentOverlay.classList.add('show');
        if (mainContent) mainContent.classList.add('sidebar-expanded');
    }
    function closeStudentSidebar() {
        studentSidebar.classList.remove('expanded');
        if (studentOverlay) studentOverlay.classList.remove('show');
        if (mainContent) mainContent.classList.remove('sidebar-expanded');
    }

    if (studentToggleBtn && studentSidebar) {
        studentToggleBtn.addEventListener('click', function () {
            studentSidebar.classList.contains('expanded') ? closeStudentSidebar() : openStudentSidebar();
        });
    }
    if (studentOverlay) {
        studentOverlay.addEventListener('click', closeStudentSidebar);
    }
});

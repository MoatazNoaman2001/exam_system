import './bootstrap';

window.Pusher = Pusher;
// import { createApp } from 'vue';

// import App from './App.vue';

// createApp(App).mount('#app');
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('studentSidebarToggleBtn');
    const sidebar = document.getElementById('studentSidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            // Toggle class لإخفاء نص اللغة عند الضغط على الزر
            sidebar.classList.toggle('language-collapsed');
        });
    }
});

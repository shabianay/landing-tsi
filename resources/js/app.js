import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();

// Behaviour that used to live in inline on*="" attributes and <script> blocks.
// Kept here so the Content-Security-Policy can forbid inline scripts.

// Non-blocking web fonts: <link media="print" data-async-style> → apply once loaded.
document.querySelectorAll('link[data-async-style]').forEach((link) => {
    const apply = () => { link.media = 'all'; };
    if (link.sheet) {
        apply();
    } else {
        link.addEventListener('load', apply, { once: true });
    }
});

// <form data-confirm="Message?"> asks before submitting (delete buttons).
document.addEventListener('submit', (event) => {
    const form = event.target;
    if (form instanceof HTMLFormElement && form.dataset.confirm && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
        event.stopImmediatePropagation();
    }
}, true);

document.addEventListener('click', (event) => {
    const target = event.target instanceof Element ? event.target : null;
    if (!target) return;

    if (target.closest('[data-toggle-admin-sidebar]')) {
        document.getElementById('admin-sidebar')?.classList.toggle('hidden');
        document.getElementById('admin-sidebar-overlay')?.classList.toggle('hidden');
        return;
    }

    const menuToggle = target.closest('[data-toggle-mobile-menu]');
    if (menuToggle) {
        const menu = document.getElementById('mobile-menu');
        if (!menu) return;
        const opening = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !opening);
        document.documentElement.classList.toggle('overflow-hidden', opening);
        menuToggle.setAttribute('aria-expanded', String(opening));
        menuToggle.querySelector('[data-menu-icon="open"]')?.classList.toggle('hidden', opening);
        menuToggle.querySelector('[data-menu-icon="close"]')?.classList.toggle('hidden', !opening);
        return;
    }

    const submitter = target.closest('[data-submit-parent-form]');
    if (submitter) {
        event.preventDefault();
        submitter.closest('form')?.requestSubmit();
    }
});

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

    // Links inside the mobile menu (e.g. "/#jasa" on the home page) may not reload the page: close it.
    if (target.closest('#mobile-menu a')) {
        const menu = document.getElementById('mobile-menu');
        const toggle = document.querySelector('[data-toggle-mobile-menu]');
        menu?.classList.add('hidden');
        document.documentElement.classList.remove('overflow-hidden');
        toggle?.setAttribute('aria-expanded', 'false');
        toggle?.querySelector('[data-menu-icon="open"]')?.classList.remove('hidden');
        toggle?.querySelector('[data-menu-icon="close"]')?.classList.add('hidden');
    }

    const submitter = target.closest('[data-submit-parent-form]');
    if (submitter) {
        event.preventDefault();
        submitter.closest('form')?.requestSubmit();
    }
});

// <select data-autosubmit> submits its form on change (no inline handlers under the CSP).
document.addEventListener('change', (event) => {
    const field = event.target;
    if (field instanceof HTMLSelectElement && field.dataset.autosubmit !== undefined) {
        field.form?.requestSubmit();
    }
});

// <input data-select-on-focus> selects its text on focus, so a link is easy to copy.
document.addEventListener('focusin', (event) => {
    const field = event.target;
    if (field instanceof HTMLInputElement && field.dataset.selectOnFocus !== undefined) {
        field.select();
    }
});

// Admin Kanban: drag a project card to another column to change its status.
const board = document.querySelector('[data-kanban]');
if (board) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let dragged = null;

    const updateCounts = () => {
        board.querySelectorAll('[data-kanban-column]').forEach((column) => {
            const count = column.querySelectorAll('[data-order-card]').length;
            const badge = column.querySelector('[data-kanban-count]');
            if (badge) badge.textContent = String(count);
            column.querySelector('[data-kanban-empty]')?.classList.toggle('hidden', count > 0);
        });
    };

    board.addEventListener('dragstart', (event) => {
        dragged = event.target instanceof Element ? event.target.closest('[data-order-card]') : null;
        if (!dragged) return;
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', dragged.dataset.orderId);
        requestAnimationFrame(() => dragged.classList.add('opacity-40'));
    });

    board.addEventListener('dragend', () => {
        dragged?.classList.remove('opacity-40');
        board.querySelectorAll('[data-kanban-drop]').forEach((zone) => zone.classList.remove('bg-primary-50'));
        dragged = null;
    });

    board.addEventListener('dragover', (event) => {
        const zone = event.target instanceof Element ? event.target.closest('[data-kanban-drop]') : null;
        if (!zone || !dragged) return;
        event.preventDefault();
        board.querySelectorAll('[data-kanban-drop]').forEach((z) => z.classList.toggle('bg-primary-50', z === zone));
    });

    board.addEventListener('drop', async (event) => {
        const zone = event.target instanceof Element ? event.target.closest('[data-kanban-drop]') : null;
        if (!zone || !dragged) return;
        event.preventDefault();
        const card = dragged;
        const from = card.parentElement;
        const status = zone.closest('[data-kanban-column]').dataset.status;
        if (card.dataset.status === status) return;

        zone.prepend(card);
        updateCounts();

        try {
            const response = await fetch(card.dataset.statusUrl, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ status }),
            });
            if (!response.ok) throw new Error(String(response.status));
            card.dataset.status = status;
            const select = card.querySelector('select[name="status"]');
            if (select) select.value = status;
        } catch (error) {
            from.prepend(card);
            updateCounts();
            window.alert('Gagal memindahkan proyek. Muat ulang halaman lalu coba lagi.');
        }
    });
}

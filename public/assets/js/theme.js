// ================================
// THEME TOGGLE (Dark / Light)
// Persisted via localStorage
// ================================

(function () {
    const STORAGE_KEY = 'wl-theme';
    const html = document.documentElement;

    // Apply saved theme on load (no transition on initial load)
    const saved = localStorage.getItem(STORAGE_KEY) || 'light';
    html.setAttribute('data-theme', saved);
    updateIcon(saved);

    // Enable transitions after load
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => html.classList.add('theme-ready'), 50);
    });

    // Bind toggle button
    const btn = document.getElementById('themeToggle');
    if (btn) {
        btn.addEventListener('click', function () {
            const current = html.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';

            // Animate the button
            btn.classList.add('theme-spin');
            setTimeout(() => btn.classList.remove('theme-spin'), 400);

            // Switch theme
            html.setAttribute('data-theme', next);
            localStorage.setItem(STORAGE_KEY, next);
            updateIcon(next);
        });
    }

    function updateIcon(theme) {
        const btn = document.getElementById('themeToggle');
        if (btn) {
            if (theme === 'dark') {
                btn.innerHTML = '<span style="color:#f5c542;">&#x2600;</span>';
            } else {
                btn.innerHTML = '<span style="color:#1a1a1a;">&#x1F319;</span>';
            }
        }
    }
})();

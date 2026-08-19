// ================================
// THEME TOGGLE (Dark / Light)
// Persisted via localStorage
// ================================

(function () {
    const STORAGE_KEY = 'wl-theme';
    const html = document.documentElement;

    // Apply saved theme on load
    const saved = localStorage.getItem(STORAGE_KEY) || 'light';
    html.setAttribute('data-theme', saved);
    updateIcon(saved);

    // Bind toggle button
    const btn = document.getElementById('themeToggle');
    if (btn) {
        btn.addEventListener('click', function () {
            const current = html.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
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

// ================================
// ORDER STATUS NOTIFICATIONS
// Polls server every 10s for status updates
// ================================

(function () {
    const POLL_INTERVAL = 10000; // 10 seconds
    const STORAGE_KEY = 'wl-notif-since';

    let since = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);

    function poll() {
        fetch('/notifications/poll?since=' + since)
            .then(res => res.json())
            .then(data => {
                if (data.server_time) {
                    since = data.server_time;
                    localStorage.setItem(STORAGE_KEY, since.toString());
                }

                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach(n => {
                        showNotification(n.message, n.status);
                    });
                    updateHistoryBadge(data.notifications.length);
                }
            })
            .catch(() => {});
    }

    function showNotification(message, status) {
        const container = getContainer();

        const notif = document.createElement('div');
        notif.className = 'notif-toast notif-' + (status || 'info');
        notif.innerHTML = `
            <span class="notif-icon">${getIcon(status)}</span>
            <span class="notif-text">${message}</span>
        `;

        container.appendChild(notif);

        // Animate in
        requestAnimationFrame(() => {
            notif.classList.add('notif-show');
        });

        // Auto dismiss after 5s
        setTimeout(() => {
            notif.classList.remove('notif-show');
            setTimeout(() => notif.remove(), 300);
        }, 5000);
    }

    function getIcon(status) {
        switch (status) {
            case 'processed': return '&#x1F373;';
            case 'done': return '&#x2705;';
            case 'pending': return '&#x23F3;';
            default: return '&#x1F514;';
        }
    }

    function getContainer() {
        let container = document.getElementById('notif-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notif-container';
            document.body.appendChild(container);
        }
        return container;
    }

    // Start polling
    setTimeout(poll, 2000); // first poll after 2s
    setInterval(poll, POLL_INTERVAL);

    function updateHistoryBadge(count) {
        const badge = document.getElementById('history-badge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = 'flex';
        }
    }
})();

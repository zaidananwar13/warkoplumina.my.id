// ================================
// CART JS - Shopee-style
// ================================

function updateFloatingCart() {
    fetch('/cart/count')
        .then(res => res.json())
        .then(data => {
            const fab = document.getElementById('floating-cart');
            const fabCount = document.getElementById('cart-count-fab');
            const headerBadge = document.getElementById('cart-count');

            // Floating action button
            if (fab && fabCount) {
                if (data.count > 0) {
                    fabCount.textContent = data.count;
                    fab.style.display = 'flex';
                } else {
                    fab.style.display = 'none';
                }
            }

            // Header badge
            if (headerBadge) {
                if (data.count > 0) {
                    headerBadge.textContent = data.count;
                    headerBadge.style.display = 'flex';
                } else {
                    headerBadge.style.display = 'none';
                }
            }
        })
        .catch(() => {});
}

function addToCart(id) {
    fetch('/cart/add?id=' + id)
        .then(() => {
            updateFloatingCart();
            showToast('Ditambahkan ke keranjang');
        });
}

// Simple toast notification
function showToast(message) {
    let toast = document.getElementById('toast-msg');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-msg';
        toast.style.cssText =
            'position:fixed;bottom:80px;left:50%;transform:translateX(-50%);' +
            'background:#1e1e1e;color:#fff;padding:10px 20px;border-radius:20px;' +
            'font-size:13px;z-index:10000;opacity:0;transition:opacity 0.3s;' +
            'border:1px solid #2a2a2a;pointer-events:none;';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.opacity = '1';
    setTimeout(() => { toast.style.opacity = '0'; }, 1800);
}

// Auto-load on page ready
document.addEventListener('DOMContentLoaded', function () {
    updateFloatingCart();
});

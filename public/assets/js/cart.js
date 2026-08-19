// ================================
// FLOATING CART CONTROLLER
// ================================

function updateFloatingCart() {
    fetch('/cart/count')
        .then(res => res.json())
        .then(data => {
            const cart  = document.getElementById('floating-cart');
            const count = document.getElementById('cart-count');

            if (!cart || !count) return;

            if (data.count > 0) {
                count.textContent = data.count;
                cart.style.display = 'flex';
            } else {
                cart.style.display = 'none';
            }
        })
        .catch(() => {
            // silent fail
        });
}

// ================================
// ADD TO CART
// ================================
function addToCart(id) {
    fetch('/cart/add?id=' + id)
        .then(() => updateFloatingCart());
}

// ================================
// AUTO LOAD
// ================================
document.addEventListener('DOMContentLoaded', function () {
    updateFloatingCart();
});

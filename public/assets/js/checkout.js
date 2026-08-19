document.addEventListener('DOMContentLoaded', () => {
    const paymentMethod = document.getElementById('paymentMethod');
    const cashFields = document.getElementById('cashFields');
    const paidAmount = document.getElementById('paidAmount');
    const changeBox = document.getElementById('changeBox');
    const changeText = document.getElementById('changeText');
    const totalValue = document.getElementById('totalValue');
    const namaInput = document.getElementById('nama');
    const kamarInput = document.getElementById('kamar');
    const form = document.getElementById('checkoutForm');

    if (!paymentMethod) return;

    // ================================
    // AUTO-FILL from localStorage
    // ================================
    const savedNama = localStorage.getItem('wl-nama');
    const savedKamar = localStorage.getItem('wl-kamar');

    if (savedNama && namaInput) namaInput.value = savedNama;
    if (savedKamar && kamarInput) kamarInput.value = savedKamar;

    // Save on form submit
    if (form) {
        form.addEventListener('submit', () => {
            if (namaInput && namaInput.value.trim()) {
                localStorage.setItem('wl-nama', namaInput.value.trim());
            }
            if (kamarInput && kamarInput.value.trim()) {
                localStorage.setItem('wl-kamar', kamarInput.value.trim());
            }
        });
    }

    // ================================
    // PAYMENT METHOD TOGGLE
    // ================================
    function formatRupiah(num) {
        return 'Rp' + num.toLocaleString('id-ID');
    }

    function updatePaymentUI() {
        if (paymentMethod.value === 'QRIS') {
            cashFields.style.display = 'none';
            paidAmount.value = '';
            paidAmount.disabled = true;
            changeBox.style.display = 'none';
        } else {
            cashFields.style.display = 'block';
            paidAmount.disabled = false;
        }
    }

    function updateChange() {
        const paid = parseInt(paidAmount.value || 0, 10);
        const total = parseInt(totalValue.value || 0, 10);

        if (paid > 0 && paid >= total) {
            changeText.textContent = formatRupiah(paid - total);
            changeBox.style.display = 'flex';
        } else {
            changeBox.style.display = 'none';
        }
    }

    updatePaymentUI();
    paymentMethod.addEventListener('change', updatePaymentUI);
    paidAmount.addEventListener('input', updateChange);
});

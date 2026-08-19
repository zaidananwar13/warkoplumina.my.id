document.addEventListener('DOMContentLoaded', () => {
  const paymentMethod = document.getElementById('paymentMethod');
  const cashFields    = document.getElementById('cashFields');
  const paidAmount    = document.getElementById('paidAmount');
  const changeBox     = document.getElementById('changeBox');
  const changeText    = document.getElementById('changeText');
  const totalValue    = document.getElementById('totalValue');

  if (!paymentMethod) return;

  function formatRupiah(num) {
    return 'Rp' + num.toLocaleString('id-ID');
  }

  function updatePaymentUI() {
    const method = paymentMethod.value;

    if (method === 'QRIS') {
      // sembunyikan & nonaktifkan field cash
      cashFields.style.display = 'none';
      paidAmount.value = '';
      paidAmount.disabled = true;
      changeBox.style.display = 'none';
    } else {
      // Cash
      cashFields.style.display = 'block';
      paidAmount.disabled = false;
    }
  }

  function updateChange() {
    const paid  = parseInt(paidAmount.value || 0, 10);
    const total = parseInt(totalValue.value || 0, 10);

    if (paid > 0 && paid >= total) {
      const change = paid - total;
      changeText.textContent = formatRupiah(change);
      changeBox.style.display = 'block';
    } else {
      changeBox.style.display = 'none';
    }
  }

  // init
  updatePaymentUI();

  // events
  paymentMethod.addEventListener('change', updatePaymentUI);
  paidAmount.addEventListener('input', updateChange);
});

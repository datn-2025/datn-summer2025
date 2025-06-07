document.addEventListener('DOMContentLoaded', function () {
    const formatSelect = document.getElementById('bookFormatSelect');
    const priceDisplay = document.getElementById('bookPrice');
    const originalPriceElement = document.getElementById('originalPrice');
    const stockDisplay = document.getElementById('bookStock');
    const quantityInput = document.getElementById('quantity');
    const productQuantityDisplay = document.getElementById('productQuantity');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const incrementBtn = document.getElementById('incrementBtn');
    const decrementBtn = document.getElementById('decrementBtn');
    const discountText = document.getElementById('discountText');
    const discountPercent = document.getElementById('discountPercent');

    const quantityGroup = quantityInput?.closest('.mt-4.flex');
    const attributeGroups = document.querySelectorAll('[id^="attribute_"]');

    function updatePriceAndStock() {
        const selectedOption = formatSelect?.selectedOptions?.[0];
        let basePrice = parseFloat(selectedOption?.getAttribute('data-price')) || 0;
        let discount = parseFloat(selectedOption?.getAttribute('data-discount')) || 0;
        let stock = parseInt(selectedOption?.getAttribute('data-stock')) || 0;
        let isEbook = selectedOption?.textContent?.toLowerCase().includes('ebook');

        let totalExtra = 0;
        document.querySelectorAll('select[id^="attribute_"]').forEach(select => {
            const extra = parseFloat(select.selectedOptions?.[0]?.getAttribute('data-price')) || 0;
            totalExtra += extra;
        });

        const totalBase = basePrice + totalExtra;

        let finalPrice = totalBase;
        if (discount > 0) {
            finalPrice = totalBase - (totalBase * (discount / 100));
        }

        priceDisplay.textContent = `${finalPrice.toLocaleString('vi-VN', { minimumFractionDigits: 0 })}₫`;
        priceDisplay.dataset.basePrice = totalBase;

        if (originalPriceElement) {
            if (discount > 0) {
                originalPriceElement.style.display = 'inline';
                originalPriceElement.textContent = `${totalBase.toLocaleString('vi-VN', { minimumFractionDigits: 0 })}₫`;
            } else {
                originalPriceElement.style.display = 'none';
            }
        }

        if (discountText && discountPercent) {
            if (discount > 0) {
                discountText.style.display = 'inline';
                discountPercent.textContent = discount;
            } else {
                discountText.style.display = 'none';
            }
        }

        if (isEbook) {
            if (quantityGroup) quantityGroup.style.display = 'none';
            quantityInput.value = 1;
            quantityInput.disabled = true;

            attributeGroups.forEach(select => {
                const label = document.querySelector(`label[for="${select.id}"]`);
                const isLanguage = label?.textContent.toLowerCase().includes('ngôn ngữ');
                select.closest('.col-span-1').style.display = isLanguage ? 'block' : 'none';
            });

            productQuantityDisplay.textContent = 'Không giới hạn';
            stockDisplay.textContent = 'Có thể mua';
            stockDisplay.className = 'font-bold px-3 py-1.5 rounded text-white bg-blue-500';
            addToCartBtn.disabled = false;
            addToCartBtn.classList.remove('bg-gray-300');
            addToCartBtn.classList.add('bg-black');
            incrementBtn.disabled = true;
            decrementBtn.disabled = true;
        } else {
            if (quantityGroup) quantityGroup.style.display = 'flex';
            quantityInput.disabled = false;

            attributeGroups.forEach(select => {
                select.closest('.col-span-1').style.display = 'block';
            });

            productQuantityDisplay.textContent = stock > 0 ? stock : 0;
            quantityInput.max = stock;
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? 1 : 0;
            }

            const outOfStock = stock <= 0;
            addToCartBtn.disabled = outOfStock;
            addToCartBtn.classList.toggle('bg-gray-300', outOfStock);
            addToCartBtn.classList.toggle('bg-black', !outOfStock);
            incrementBtn.disabled = outOfStock;
            decrementBtn.disabled = outOfStock;

            stockDisplay.textContent = outOfStock ? 'Hết hàng' : 'Còn hàng';
            stockDisplay.className = `font-bold px-3 py-1.5 rounded text-white ${outOfStock ? 'bg-gray-900' : 'bg-green-500'}`;
        }
    }

    incrementBtn?.addEventListener('click', () => {
        const max = parseInt(quantityInput.max);
        let val = parseInt(quantityInput.value) || 1;
        if (val < max) quantityInput.value = val + 1;
    });

    decrementBtn?.addEventListener('click', () => {
        let val = parseInt(quantityInput.value) || 1;
        if (val > 1) quantityInput.value = val - 1;
    });

    quantityInput?.addEventListener('input', () => {
        let val = parseInt(quantityInput.value) || 0;
        const max = parseInt(quantityInput.max);
        if (val < 1) val = 1;
        if (val > max) val = max;
        quantityInput.value = val;
    });

    quantityInput?.addEventListener('blur', () => {
        if (!quantityInput.value) {
            quantityInput.value = 1;
        }
    });

    formatSelect?.addEventListener('change', updatePriceAndStock);
    document.querySelectorAll('select[id^="attribute_"]').forEach(select => {
        select.addEventListener('change', updatePriceAndStock);
    });

    updatePriceAndStock();
});

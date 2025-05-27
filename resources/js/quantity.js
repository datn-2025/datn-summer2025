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

    function updatePriceAndStock() {
        let basePrice = parseFloat(priceDisplay.dataset.basePrice) || 0;
        let stock = parseInt(productQuantityDisplay.textContent) || 0;

        // Nếu có chọn định dạng
        const selectedOption = formatSelect?.selectedOptions?.[0];
        if (selectedOption && selectedOption.value) {
            basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        }

        // Tính thêm giá từ thuộc tính (nếu có)
        const attributeSelects = document.querySelectorAll('select[id^="attribute_"]');
        attributeSelects.forEach(select => {
            const option = select.selectedOptions?.[0];
            if (option) {
                const extra = parseFloat(option.getAttribute('data-price')) || 0;
                basePrice += extra;
            }
        });

        // Tính giảm giá
        let finalPrice = basePrice;
        let discount = 0;
        if (selectedOption) {
            discount = parseFloat(selectedOption.getAttribute('data-discount')) || 0;
        }
        
        if (discount > 0) {
            finalPrice = basePrice - (basePrice * (discount / 100));
        }
   
        // Cập nhật giao diện giá
        if (originalPriceElement) {
            if (discount > 0) {
                originalPriceElement.style.display = 'inline';
                originalPriceElement.textContent = `${basePrice.toLocaleString('vi-VN')}₫`;
            } else {
                originalPriceElement.style.display = 'none';
            }
        }

        priceDisplay.textContent = `${finalPrice.toLocaleString('vi-VN')}₫`;
        
        // Cập nhật % giảm giá
        if (discountText && discountPercent) {
            if (discount > 0) {
                discountText.style.display = 'inline';
                discountPercent.textContent = discount;
            } else {
                discountText.style.display = 'none';
            }
        }

        // Cập nhật tồn kho
        stockDisplay.textContent = stock > 0 ? 'Còn hàng' : 'Hết hàng';
        productQuantityDisplay.textContent = stock;

        // Cập nhật số lượng tối đa
        quantityInput.max = stock;
        if (parseInt(quantityInput.value) > stock) {
            quantityInput.value = stock > 0 ? 1 : 0;
        }

        // Bật/tắt nút mua
        const outOfStock = stock <= 0;
        addToCartBtn.disabled = outOfStock;
        addToCartBtn.classList.toggle('bg-gray-300', outOfStock);
        addToCartBtn.classList.toggle('bg-black', !outOfStock);
        incrementBtn.disabled = outOfStock;
        decrementBtn.disabled = outOfStock;
    }

    // Nút tăng giảm
    incrementBtn?.addEventListener('click', () => {
        const max = parseInt(quantityInput.max);
        let val = parseInt(quantityInput.value) || 1;
        if (val < max) quantityInput.value = val + 1;
    });

    decrementBtn?.addEventListener('click', () => {
        let val = parseInt(quantityInput.value) || 1;
        if (val > 1) quantityInput.value = val - 1;
    });

    // Xử lý khi người dùng nhập trực tiếp
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

    // Lắng nghe thay đổi
    formatSelect?.addEventListener('change', updatePriceAndStock);
    document.querySelectorAll('select[id^="attribute_"]').forEach(select => {
        select.addEventListener('change', updatePriceAndStock);
    });

    // Load lần đầu
    updatePriceAndStock();
});

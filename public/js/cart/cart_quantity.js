/**
 * Cart Quantity Module - Quantity management and controls
 * Handles quantity input, increase/decrease buttons, and quantity validation
 */

const CartQuantity = {
    // Initialize quantity controls
    init() {
        this.bindQuantityControls();
        this.initQuantityValidation();
    },

    // Bind all quantity control events
    bindQuantityControls() {
        const cartItems = CartBase.dom.getAll('.cart-item');
        
        cartItems.forEach(cartItem => {
            this.setupQuantityControls(cartItem);
        });
    },

    // Setup quantity controls for a single cart item
    setupQuantityControls(cartItem) {
        const quantityInput = cartItem.querySelector('.quantity-input');
        const increaseBtn = cartItem.querySelector('.increase-quantity');
        const decreaseBtn = cartItem.querySelector('.decrease-quantity');
        const itemData = CartBase.utils.getCartItemData(cartItem);

        if (!itemData) return;

        // Store initial value
        if (quantityInput) {
            quantityInput.dataset.lastValue = quantityInput.value;
            this.setupQuantityInput(quantityInput, cartItem);
        }

        // Setup increase button
        if (increaseBtn) {
            this.setupIncreaseButton(increaseBtn, quantityInput, cartItem);
        }

        // Setup decrease button
        if (decreaseBtn) {
            this.setupDecreaseButton(decreaseBtn, quantityInput, cartItem);
        }
    },

    // Setup quantity input events
    setupQuantityInput(input, cartItem) {
        const itemData = CartBase.utils.getCartItemData(cartItem);
        
        // Handle input validation
        CartBase.dom.on(input, 'input', (e) => {
            this.validateQuantityInput(e.target, itemData);
        });

        // Handle quantity change
        CartBase.dom.on(input, 'change', (e) => {
            const newValue = parseInt(e.target.value) || 1;
            if (newValue > 0) {
                this.updateQuantity(cartItem, newValue);
            }
        });
    },

    // Setup increase button
    setupIncreaseButton(button, input, cartItem) {
        CartBase.dom.on(button, 'click', () => {
            if (!input) return;
            
            const currentValue = parseInt(input.value) || 1;
            const max = parseInt(input.max) || parseInt(cartItem.dataset.stock) || 1;
            
            if (currentValue < max) {
                input.value = currentValue + 1;
                this.updateQuantity(cartItem, currentValue + 1);
            }
        });
    },

    // Setup decrease button
    setupDecreaseButton(button, input, cartItem) {
        CartBase.dom.on(button, 'click', () => {
            if (!input) return;
            
            const currentValue = parseInt(input.value) || 1;
            const min = parseInt(input.min) || 1;
            
            if (currentValue > min) {
                input.value = currentValue - 1;
                this.updateQuantity(cartItem, currentValue - 1);
            }
        });
    },

    // Validate quantity input
    validateQuantityInput(input, itemData) {
        const newValue = parseInt(input.value) || 0;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || itemData.stock;

        // Check stock limit
        if (newValue > itemData.stock) {
            CartBase.utils.showError(`Số lượng không được vượt quá ${itemData.stock} sản phẩm (số lượng tồn kho hiện tại)`);
            input.value = itemData.stock;
            return;
        }

        // Validate min/max
        if (newValue < min) {
            input.value = min;
            CartBase.utils.showWarning(`Số lượng tối thiểu là ${min}`);
        } else if (newValue > max) {
            input.value = max;
            CartBase.utils.showWarning(`Số lượng tối đa là ${max}`);
        }
    },

    // Initialize quantity validation
    initQuantityValidation() {
        // Set up initial button states
        const cartItems = CartBase.dom.getAll('.cart-item');
        
        cartItems.forEach(cartItem => {
            this.updateButtonStates(cartItem);
        });
    },

    // Update button states based on current quantity
    updateButtonStates(cartItem) {
        const quantityInput = cartItem.querySelector('.quantity-input');
        const increaseBtn = cartItem.querySelector('.increase-quantity');
        const decreaseBtn = cartItem.querySelector('.decrease-quantity');
        const itemData = CartBase.utils.getCartItemData(cartItem);

        if (!quantityInput || !itemData) return;

        const currentValue = parseInt(quantityInput.value) || 1;
        const min = parseInt(quantityInput.min) || 1;
        const max = parseInt(quantityInput.max) || itemData.stock;

        // Update increase button
        if (increaseBtn) {
            increaseBtn.disabled = currentValue >= max;
        }

        // Update decrease button
        if (decreaseBtn) {
            decreaseBtn.disabled = currentValue <= min;
        }
    },

    // Update quantity with debouncing
    updateQuantity: CartBase.utils.debounce(function(cartItem, newQuantity) {
        const itemData = CartBase.utils.getCartItemData(cartItem);
        const quantityInput = cartItem.querySelector('.quantity-input');
        const increaseBtn = cartItem.querySelector('.increase-quantity');
        const decreaseBtn = cartItem.querySelector('.decrease-quantity');
        const stockAmount = cartItem.querySelector('.stock-amount');
        const oldQuantity = parseInt(quantityInput?.dataset.lastValue) || 1;

        if (!itemData) return;

        // Validate quantity against stock
        if (newQuantity > itemData.stock) {
            CartBase.utils.showError(`Số lượng không được vượt quá ${itemData.stock} sản phẩm (số lượng tồn kho hiện tại)`);
            if (quantityInput) quantityInput.value = oldQuantity;
            return;
        }

        // Disable controls during update
        const controls = [quantityInput, increaseBtn, decreaseBtn].filter(el => el);
        controls.forEach(el => el.disabled = true);

        // Add loading state
        CartBase.dom.addClass(cartItem, 'loading');

        // Update UI immediately for better UX
        if (window.CartSummary && typeof window.CartSummary.updateItemPriceDisplay === 'function') {
            window.CartSummary.updateItemPriceDisplay(cartItem, newQuantity);
        }

        // Make AJAX request
        $.ajax({
            url: '/cart/update',
            method: 'POST',
            data: {
                book_id: itemData.bookId,
                quantity: newQuantity,
                _token: CartBase.utils.getCSRFToken()
            },
            success: (response) => {
                this.handleUpdateSuccess(response, cartItem, newQuantity, controls, stockAmount, quantityInput, increaseBtn, decreaseBtn);
            },
            error: (xhr) => {
                this.handleUpdateError(xhr, cartItem, oldQuantity, controls);
            },
            complete: () => {
                // Remove loading state
                CartBase.dom.removeClass(cartItem, 'loading');
                
                // Re-enable controls
                controls.forEach(el => el.disabled = false);
            }
        });
    }, CartBase.config.debounceDelay),

    // Handle successful quantity update
    handleUpdateSuccess(response, cartItem, newQuantity, controls, stockAmount, quantityInput, increaseBtn, decreaseBtn) {
        if (response.success) {
            CartBase.utils.showSuccess(response.success);
            
            // Update item data
            if (response.data) {
                cartItem.dataset.price = response.data.price;
                cartItem.dataset.stock = response.data.stock;
                
                // Update stock display
                if (stockAmount) {
                    stockAmount.textContent = response.data.stock;
                }

                // Update input attributes
                if (quantityInput) {
                    quantityInput.max = response.data.stock;
                    quantityInput.dataset.lastValue = newQuantity;
                }

                // Update button states
                this.updateButtonStates(cartItem);

                // Update price display
                if (window.CartSummary && typeof window.CartSummary.updateItemPriceDisplay === 'function') {
                    window.CartSummary.updateItemPriceDisplay(cartItem, newQuantity);
                }
            }
        } else if (response.error) {
            CartBase.utils.showError(response.error);
            this.resetToOldValue(cartItem);
        }
    },

    // Handle quantity update error
    handleUpdateError(xhr, cartItem, oldQuantity, controls) {
        const response = xhr.responseJSON;
        
        if (response?.error) {
            if (response.available_stock !== undefined) {
                const quantityInput = cartItem.querySelector('.quantity-input');
                const stockAmount = cartItem.querySelector('.stock-amount');
                
                CartBase.utils.showError(`${response.error} (Số lượng tồn kho hiện tại: ${response.available_stock} sản phẩm)`);
                
                if (quantityInput) {
                    quantityInput.value = Math.min(response.available_stock, oldQuantity);
                    quantityInput.max = response.available_stock;
                }
                
                if (stockAmount) {
                    stockAmount.textContent = response.available_stock;
                }
                
                cartItem.dataset.stock = response.available_stock;
                
                if (window.CartSummary && typeof window.CartSummary.updateItemPriceDisplay === 'function') {
                    window.CartSummary.updateItemPriceDisplay(cartItem, Math.min(response.available_stock, oldQuantity));
                }
            } else {
                CartBase.utils.showError(response.error);
                this.resetToOldValue(cartItem);
            }
        } else {
            CartBase.utils.showError('Có lỗi xảy ra. Vui lòng thử lại.');
            this.resetToOldValue(cartItem);
        }
    },

    // Reset quantity to previous value
    resetToOldValue(cartItem) {
        const quantityInput = cartItem.querySelector('.quantity-input');
        const oldQuantity = parseInt(quantityInput?.dataset.lastValue) || 1;
        
        if (quantityInput) {
            quantityInput.value = oldQuantity;
        }
        
        if (window.CartSummary && typeof window.CartSummary.updateItemPriceDisplay === 'function') {
            window.CartSummary.updateItemPriceDisplay(cartItem, oldQuantity);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (window.CartBase) {
        CartQuantity.init();
    }
});

// Export for use in other modules
window.CartQuantity = CartQuantity;

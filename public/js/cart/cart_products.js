/**
 * Cart Products Module - Product management (add/remove items)
 * Handles individual product removal and bulk cart operations
 */

const CartProducts = {
    // Initialize product management
    init() {
        this.bindRemoveButtons();
        this.bindBulkActions();
    },

    // Bind individual product remove buttons
    bindRemoveButtons() {
        const removeButtons = CartBase.dom.getAll('.remove-item');
        
        removeButtons.forEach(button => {
            this.setupRemoveButton(button);
        });
    },

    // Setup individual remove button
    setupRemoveButton(button) {
        if (!button) return;
        
        CartBase.dom.on(button, 'click', () => {
            this.removeItem(button);
        });
    },

    // Remove individual item from cart
    removeItem(button) {
        const cartItem = button.closest('.cart-item');
        const bookId = button.dataset.bookId;
        
        if (!cartItem || !bookId) return;

        // Show confirmation
        if (!CartBase.utils.showConfirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }

        // Disable item controls
        const controls = cartItem.querySelectorAll('button, input');
        controls.forEach(el => el.disabled = true);
        
        // Add loading state
        CartBase.dom.addClass(cartItem, 'loading');

        // Make AJAX request
        $.ajax({
            url: '/cart/remove',
            method: 'POST',
            data: {
                book_id: bookId,
                _token: CartBase.utils.getCSRFToken()
            },
            success: (response) => {
                this.handleRemoveSuccess(response, cartItem);
            },
            error: (xhr) => {
                this.handleRemoveError(xhr, cartItem, controls);
            }
        });
    },

    // Handle successful item removal
    handleRemoveSuccess(response, cartItem) {
        if (response.success) {
            CartBase.utils.showSuccess(response.success);
            
            // Remove item from DOM
            CartBase.dom.remove(cartItem);
            
            // Check if cart is empty
            const remainingItems = CartBase.dom.getAll('.cart-item');
            if (remainingItems.length === 0) {
                // Reload page if no items left
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Update cart totals
                if (window.CartSummary && typeof window.CartSummary.updateCartTotal === 'function') {
                    window.CartSummary.updateCartTotal();
                }
            }
        } else if (response.error) {
            CartBase.utils.showError(response.error);
            this.resetItemControls(cartItem);
        }
    },

    // Handle item removal error
    handleRemoveError(xhr, cartItem, controls) {
        const response = xhr.responseJSON;
        CartBase.utils.showError(response?.error || 'Có lỗi xảy ra. Vui lòng thử lại.');
        this.resetItemControls(cartItem);
    },

    // Reset item controls after error
    resetItemControls(cartItem) {
        const controls = cartItem.querySelectorAll('button, input');
        controls.forEach(el => el.disabled = false);
        CartBase.dom.removeClass(cartItem, 'loading');
    },

    // Bind bulk action buttons
    bindBulkActions() {
        this.setupClearCartButton();
        this.setupAddWishlistButton();
    },

    // Setup clear cart button
    setupClearCartButton() {
        const clearCartBtn = CartBase.dom.getById('clear-cart-btn');
        
        if (clearCartBtn) {
            CartBase.dom.on(clearCartBtn, 'click', () => {
                this.clearCart(clearCartBtn);
            });
        }
    },

    // Clear entire cart
    clearCart(button) {
        if (!CartBase.utils.showConfirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            return;
        }

        // Show loading state
        CartBase.utils.showLoading(button, 'Đang xóa...');

        // Make AJAX request
        $.ajax({
            url: '/cart/clear',
            method: 'POST',
            data: {
                _token: CartBase.utils.getCSRFToken()
            },
            success: (response) => {
                this.handleClearSuccess(response);
            },
            error: (xhr) => {
                this.handleClearError(xhr, button);
            }
        });
    },

    // Handle successful cart clear
    handleClearSuccess(response) {
        if (response.success) {
            CartBase.utils.showSuccess(response.success);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    },

    // Handle cart clear error
    handleClearError(xhr, button) {
        const response = xhr.responseJSON;
        CartBase.utils.showError(response?.error || 'Có lỗi xảy ra khi xóa giỏ hàng');
        
        // Reset button
        CartBase.utils.hideLoading(button, '<i class="fas fa-trash-alt me-2"></i>Xóa tất cả');
    },

    // Setup add wishlist button
    setupAddWishlistButton() {
        const addWishlistBtn = CartBase.dom.getById('add-wishlist-btn');
        
        if (addWishlistBtn) {
            CartBase.dom.on(addWishlistBtn, 'click', () => {
                this.addFromWishlist(addWishlistBtn);
            });
        }
    },

    // Add all items from wishlist to cart
    addFromWishlist(button) {
        // Show loading state
        CartBase.utils.showLoading(button, 'Đang thêm...');

        // Make AJAX request
        $.ajax({
            url: '/cart/add-wishlist',
            method: 'POST',
            data: {
                _token: CartBase.utils.getCSRFToken()
            },
            success: (response) => {
                this.handleWishlistSuccess(response);
            },
            error: (xhr) => {
                this.handleWishlistError(xhr, button);
            }
        });
    },

    // Handle successful wishlist addition
    handleWishlistSuccess(response) {
        if (response.success) {
            CartBase.utils.showSuccess(response.success);
            
            // Show information about skipped items
            if (response.skipped_items && response.skipped_items.length > 0) {
                let skippedMsg = 'Một số sản phẩm không thể thêm:\n';
                response.skipped_items.forEach(item => {
                    skippedMsg += `- ${item.title}: ${item.reason}\n`;
                });
                
                if (typeof toastr !== 'undefined') {
                    toastr.warning(skippedMsg, 'Thông báo', {
                        timeOut: 5000
                    });
                }
            }
            
            // Reload page after delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    },

    // Handle wishlist addition error
    handleWishlistError(xhr, button) {
        const response = xhr.responseJSON;
        CartBase.utils.showError(response?.error || 'Có lỗi xảy ra khi thêm từ danh sách yêu thích');
        
        // Reset button
        CartBase.utils.hideLoading(button, '<i class="fas fa-heart me-2"></i>Thêm từ yêu thích');
    },

    // Utility methods for product management
    utils: {
        // Get product information from cart item
        getProductInfo(cartItem) {
            if (!cartItem) return null;
            
            const titleElement = cartItem.querySelector('.product-title');
            const priceElement = cartItem.querySelector('.product-price');
            const quantityInput = cartItem.querySelector('.quantity-input');
            
            return {
                id: cartItem.dataset.bookId,
                title: titleElement ? titleElement.textContent.trim() : '',
                price: CartBase.utils.parseNumber(priceElement ? priceElement.textContent : '0'),
                quantity: quantityInput ? parseInt(quantityInput.value) || 1 : 1,
                stock: parseInt(cartItem.dataset.stock) || 0
            };
        },

        // Update product display information
        updateProductDisplay(cartItem, data) {
            if (!cartItem || !data) return;
            
            // Update data attributes
            if (data.price !== undefined) {
                cartItem.dataset.price = data.price;
            }
            if (data.stock !== undefined) {
                cartItem.dataset.stock = data.stock;
            }
            
            // Update stock display
            const stockElement = cartItem.querySelector('.stock-amount');
            if (stockElement && data.stock !== undefined) {
                stockElement.textContent = data.stock;
            }
            
            // Update quantity max
            const quantityInput = cartItem.querySelector('.quantity-input');
            if (quantityInput && data.stock !== undefined) {
                quantityInput.max = data.stock;
            }
        },

        // Check if cart has any items
        hasItems() {
            return CartBase.dom.getAll('.cart-item').length > 0;
        },

        // Get all cart items data
        getAllCartItems() {
            const cartItems = CartBase.dom.getAll('.cart-item');
            return Array.from(cartItems).map(item => this.getProductInfo(item)).filter(item => item);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (window.CartBase) {
        CartProducts.init();
    }
});

// Export for use in other modules
window.CartProducts = CartProducts;

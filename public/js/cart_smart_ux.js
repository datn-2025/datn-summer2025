/**
 * Cart Smart UX Features - Phase 2 Implementation
 * Enhanced user experience with smart features, bulk actions, and accessibility improvements
 */

class CartSmartUX {
    constructor() {
        this.initializeComponents();
        this.setupEventListeners();
        this.initializeKeyboardNavigation();
        this.setupAccessibilityFeatures();
        this.initializeBulkActions();
        this.setupSmartRecommendations();
    }

    initializeComponents() {
        this.cartContainer = document.querySelector('.cart-container');
        this.cartItems = document.querySelectorAll('.cart-item-card');
        this.selectAllCheckbox = this.createSelectAllCheckbox();
        this.bulkActionBar = this.createBulkActionBar();
        this.smartFilters = this.createSmartFilters();
        this.selectedItems = new Set();
        this.isSelectionMode = false;
    }

    setupEventListeners() {
        // Long press for selection mode on mobile
        this.cartItems.forEach(item => {
            this.setupLongPressSelection(item);
        });

        // Double tap to add to wishlist
        this.cartItems.forEach(item => {
            this.setupDoubleTapWishlist(item);
        });

        // Smart quantity suggestions
        this.setupQuantitySuggestions();

        // Auto-save cart state
        this.setupAutoSave();
    }

    createSelectAllCheckbox() {
        const container = document.createElement('div');
        container.className = 'select-all-container';
        container.innerHTML = `
            <div class="select-all-wrapper">
                <input type="checkbox" id="select-all-items" class="select-all-checkbox">
                <label for="select-all-items" class="select-all-label">
                    <span class="checkmark"></span>
                    <span class="label-text">Chọn tất cả sản phẩm</span>
                    <span class="selection-count">(0 đã chọn)</span>
                </label>
            </div>
        `;

        // Insert at the top of cart products
        const cartHeader = document.querySelector('.cart-header') || 
                          document.querySelector('.cart-products-grid');
        if (cartHeader) {
            cartHeader.insertAdjacentElement('beforebegin', container);
        }

        return container.querySelector('.select-all-checkbox');
    }

    createBulkActionBar() {
        const bulkBar = document.createElement('div');
        bulkBar.className = 'bulk-action-bar';
        bulkBar.innerHTML = `
            <div class="bulk-actions-content">
                <div class="selected-info">
                    <span class="selected-count">0</span> sản phẩm đã chọn
                </div>
                <div class="bulk-actions">
                    <button class="bulk-btn bulk-wishlist" data-action="wishlist">
                        <i class="fas fa-heart"></i>
                        Thêm vào yêu thích
                    </button>
                    <button class="bulk-btn bulk-remove" data-action="remove">
                        <i class="fas fa-trash"></i>
                        Xóa khỏi giỏ hàng
                    </button>
                    <button class="bulk-btn bulk-save" data-action="save">
                        <i class="fas fa-bookmark"></i>
                        Lưu để mua sau
                    </button>
                </div>
                <button class="bulk-close" data-action="close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(bulkBar);
        return bulkBar;
    }

    createSmartFilters() {
        const filterContainer = document.createElement('div');
        filterContainer.className = 'smart-filters-container';
        filterContainer.innerHTML = `
            <div class="smart-filters">
                <div class="filter-group">
                    <label class="filter-label">Sắp xếp theo:</label>
                    <select class="smart-filter-select" data-filter="sort">
                        <option value="default">Mặc định</option>
                        <option value="price-low">Giá thấp đến cao</option>
                        <option value="price-high">Giá cao đến thấp</option>
                        <option value="name">Tên A-Z</option>
                        <option value="author">Tác giả</option>
                        <option value="quantity">Số lượng</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Lọc theo:</label>
                    <div class="filter-chips">
                        <button class="filter-chip" data-filter="discounted">Giảm giá</button>
                        <button class="filter-chip" data-filter="high-quantity">Số lượng nhiều</button>
                        <button class="filter-chip" data-filter="favorites">Yêu thích</button>
                    </div>
                </div>
            </div>
        `;

        const cartContainer = document.querySelector('.cart-products-grid');
        if (cartContainer) {
            cartContainer.insertAdjacentElement('beforebegin', filterContainer);
        }

        return filterContainer;
    }

    setupLongPressSelection(item) {
        let pressTimer;
        let startX, startY;
        const longPressDuration = 800; // 800ms

        const startPress = (e) => {
            if (e.type === 'touchstart') {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }
            
            pressTimer = setTimeout(() => {
                this.enterSelectionMode();
                this.toggleItemSelection(item);
                this.showToast('Chế độ chọn nhiều đã được kích hoạt', 'info');
                
                // Haptic feedback on mobile
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }, longPressDuration);
        };

        const endPress = (e) => {
            clearTimeout(pressTimer);
            
            // Check if it was a drag gesture
            if (e.type === 'touchend' && e.changedTouches) {
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const deltaX = Math.abs(endX - startX);
                const deltaY = Math.abs(endY - startY);
                
                if (deltaX > 20 || deltaY > 20) {
                    return; // It was a drag, not a press
                }
            }
        };

        item.addEventListener('mousedown', startPress);
        item.addEventListener('touchstart', startPress, { passive: true });
        item.addEventListener('mouseup', endPress);
        item.addEventListener('mouseleave', endPress);
        item.addEventListener('touchend', endPress);
        item.addEventListener('touchcancel', endPress);
    }

    setupDoubleTapWishlist(item) {
        let lastTap = 0;
        const doubleTapDelay = 300;

        const handleTap = (e) => {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            
            if (tapLength < doubleTapDelay && tapLength > 0) {
                e.preventDefault();
                this.addToWishlist(item);
                this.showHeartAnimation(e.target);
                
                // Haptic feedback
                if (navigator.vibrate) {
                    navigator.vibrate([50, 50, 50]);
                }
            }
            
            lastTap = currentTime;
        };

        item.addEventListener('touchend', handleTap);
    }

    setupQuantitySuggestions() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        quantityInputs.forEach(input => {
            const suggestions = this.createQuantitySuggestions(input);
            
            input.addEventListener('focus', () => {
                this.showQuantitySuggestions(input, suggestions);
            });
            
            input.addEventListener('blur', () => {
                setTimeout(() => {
                    this.hideQuantitySuggestions(input);
                }, 200);
            });
        });
    }

    createQuantitySuggestions(input) {
        const currentValue = parseInt(input.value) || 1;
        const suggestions = [];
        
        // Smart suggestions based on common quantities
        const commonQuantities = [1, 2, 3, 5, 10];
        const bulkQuantities = [15, 20, 25, 50];
        
        commonQuantities.forEach(qty => {
            if (qty !== currentValue) {
                suggestions.push({
                    value: qty,
                    label: `${qty} cuốn`,
                    type: 'common'
                });
            }
        });
        
        if (currentValue < 10) {
            suggestions.push({
                value: currentValue * 2,
                label: `${currentValue * 2} cuốn (Gấp đôi)`,
                type: 'smart'
            });
        }
        
        bulkQuantities.forEach(qty => {
            if (qty > currentValue) {
                suggestions.push({
                    value: qty,
                    label: `${qty} cuốn (Số lượng lớn)`,
                    type: 'bulk'
                });
            }
        });
        
        return suggestions;
    }

    showQuantitySuggestions(input, suggestions) {
        const existing = input.parentNode.querySelector('.quantity-suggestions');
        if (existing) existing.remove();
        
        const suggestionContainer = document.createElement('div');
        suggestionContainer.className = 'quantity-suggestions';
        
        suggestions.forEach(suggestion => {
            const suggestionItem = document.createElement('div');
            suggestionItem.className = `suggestion-item suggestion-${suggestion.type}`;
            suggestionItem.textContent = suggestion.label;
            suggestionItem.dataset.value = suggestion.value;
            
            suggestionItem.addEventListener('click', () => {
                input.value = suggestion.value;
                input.dispatchEvent(new Event('change'));
                this.hideQuantitySuggestions(input);
                this.showToast(`Đã cập nhật số lượng: ${suggestion.label}`, 'success');
            });
            
            suggestionContainer.appendChild(suggestionItem);
        });
        
        input.parentNode.appendChild(suggestionContainer);
    }

    hideQuantitySuggestions(input) {
        const suggestions = input.parentNode.querySelector('.quantity-suggestions');
        if (suggestions) {
            suggestions.remove();
        }
    }

    setupAutoSave() {
        let saveTimeout;
        const autoSaveDelay = 2000; // 2 seconds
        
        const triggerAutoSave = () => {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                this.saveCartState();
                this.showAutoSaveIndicator();
            }, autoSaveDelay);
        };
        
        // Listen to cart changes
        document.addEventListener('cart:updated', triggerAutoSave);
        document.addEventListener('quantity:changed', triggerAutoSave);
        document.addEventListener('item:removed', triggerAutoSave);
    }

    enterSelectionMode() {
        if (this.isSelectionMode) return;
        
        this.isSelectionMode = true;
        document.body.classList.add('selection-mode');
        
        // Add selection checkboxes to all items
        this.cartItems.forEach(item => {
            this.addSelectionCheckbox(item);
        });
        
        // Show bulk action bar
        this.bulkActionBar.classList.add('visible');
        
        // Show select all option
        this.selectAllCheckbox.closest('.select-all-container').classList.add('visible');
    }

    exitSelectionMode() {
        this.isSelectionMode = false;
        document.body.classList.remove('selection-mode');
        
        // Remove selection checkboxes
        this.cartItems.forEach(item => {
            const checkbox = item.querySelector('.item-selection-checkbox');
            if (checkbox) {
                checkbox.closest('.selection-checkbox-container').remove();
            }
        });
        
        // Hide bulk action bar
        this.bulkActionBar.classList.remove('visible');
        
        // Hide select all option
        this.selectAllCheckbox.closest('.select-all-container').classList.remove('visible');
        
        // Clear selections
        this.selectedItems.clear();
        this.updateSelectionUI();
    }

    addSelectionCheckbox(item) {
        if (item.querySelector('.selection-checkbox-container')) return;
        
        const checkboxContainer = document.createElement('div');
        checkboxContainer.className = 'selection-checkbox-container';
        checkboxContainer.innerHTML = `
            <input type="checkbox" class="item-selection-checkbox" data-item-id="${this.getItemId(item)}">
            <span class="selection-checkmark"></span>
        `;
        
        item.insertAdjacentElement('afterbegin', checkboxContainer);
        
        const checkbox = checkboxContainer.querySelector('.item-selection-checkbox');
        checkbox.addEventListener('change', () => {
            this.toggleItemSelection(item);
        });
    }

    toggleItemSelection(item) {
        const itemId = this.getItemId(item);
        const checkbox = item.querySelector('.item-selection-checkbox');
        
        if (this.selectedItems.has(itemId)) {
            this.selectedItems.delete(itemId);
            item.classList.remove('selected');
            if (checkbox) checkbox.checked = false;
        } else {
            this.selectedItems.add(itemId);
            item.classList.add('selected');
            if (checkbox) checkbox.checked = true;
        }
        
        this.updateSelectionUI();
    }

    updateSelectionUI() {
        const selectedCount = this.selectedItems.size;
        const totalCount = this.cartItems.length;
        
        // Update bulk action bar
        const countElement = this.bulkActionBar.querySelector('.selected-count');
        if (countElement) {
            countElement.textContent = selectedCount;
        }
        
        // Update select all checkbox
        if (this.selectAllCheckbox) {
            this.selectAllCheckbox.checked = selectedCount === totalCount;
            this.selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
        }
        
        // Update selection count in label
        const selectionCount = document.querySelector('.selection-count');
        if (selectionCount) {
            selectionCount.textContent = `(${selectedCount} đã chọn)`;
        }
        
        // Show/hide bulk actions based on selection
        const bulkActions = this.bulkActionBar.querySelector('.bulk-actions');
        if (bulkActions) {
            bulkActions.style.opacity = selectedCount > 0 ? '1' : '0.5';
            bulkActions.style.pointerEvents = selectedCount > 0 ? 'auto' : 'none';
        }
    }

    getItemId(item) {
        return item.dataset.itemId || item.querySelector('[data-item-id]')?.dataset.itemId || 
               Array.from(this.cartItems).indexOf(item);
    }

    addToWishlist(item) {
        // Add wishlist functionality
        const itemId = this.getItemId(item);
        console.log('Adding to wishlist:', itemId);
        
        // Visual feedback
        item.classList.add('adding-to-wishlist');
        setTimeout(() => {
            item.classList.remove('adding-to-wishlist');
            item.classList.add('in-wishlist');
        }, 500);
        
        this.showToast('Đã thêm vào danh sách yêu thích', 'success');
    }

    showHeartAnimation(target) {
        const heart = document.createElement('div');
        heart.className = 'floating-heart';
        heart.innerHTML = '<i class="fas fa-heart"></i>';
        
        const rect = target.getBoundingClientRect();
        heart.style.left = rect.left + rect.width / 2 + 'px';
        heart.style.top = rect.top + rect.height / 2 + 'px';
        
        document.body.appendChild(heart);
        
        setTimeout(() => {
            heart.remove();
        }, 1000);
    }

    saveCartState() {
        const cartData = {
            items: Array.from(this.cartItems).map(item => ({
                id: this.getItemId(item),
                quantity: item.querySelector('.quantity-input')?.value || 1,
                selected: this.selectedItems.has(this.getItemId(item))
            })),
            timestamp: Date.now()
        };
        
        localStorage.setItem('cart_state', JSON.stringify(cartData));
    }

    showAutoSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'auto-save-indicator';
        indicator.innerHTML = '<i class="fas fa-check"></i> Đã lưu tự động';
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.classList.add('visible');
        }, 100);
        
        setTimeout(() => {
            indicator.classList.remove('visible');
            setTimeout(() => indicator.remove(), 300);
        }, 2000);
    }

    showToast(message, type = 'info') {
        // Use existing toast system or create one
        if (window.showToast) {
            window.showToast(message, type);
        } else {
            console.log(`Toast [${type}]: ${message}`);
        }
    }

    initializeKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Escape to exit selection mode
            if (e.key === 'Escape' && this.isSelectionMode) {
                this.exitSelectionMode();
            }
            
            // Ctrl+A to select all
            if (e.ctrlKey && e.key === 'a' && this.isSelectionMode) {
                e.preventDefault();
                this.selectAllItems();
            }
            
            // Delete to remove selected items
            if (e.key === 'Delete' && this.selectedItems.size > 0) {
                e.preventDefault();
                this.removeSelectedItems();
            }
        });
    }

    setupAccessibilityFeatures() {
        // Add ARIA labels and roles
        this.cartItems.forEach((item, index) => {
            item.setAttribute('role', 'listitem');
            item.setAttribute('aria-label', `Sản phẩm ${index + 1} trong giỏ hàng`);
            
            const quantityInput = item.querySelector('.quantity-input');
            if (quantityInput) {
                quantityInput.setAttribute('aria-label', 'Số lượng sản phẩm');
            }
            
            const removeBtn = item.querySelector('.remove-btn');
            if (removeBtn) {
                removeBtn.setAttribute('aria-label', 'Xóa sản phẩm khỏi giỏ hàng');
            }
        });
        
        // Add focus indicators
        this.addFocusIndicators();
    }

    addFocusIndicators() {
        const style = document.createElement('style');
        style.textContent = `
            .cart-item-card:focus-within {
                outline: 2px solid #667eea;
                outline-offset: 2px;
            }
            
            .quantity-input:focus {
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            }
            
            .remove-btn:focus,
            .wishlist-btn:focus,
            .bulk-btn:focus {
                outline: 2px solid #ffffff;
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);
    }

    initializeBulkActions() {
        this.bulkActionBar.addEventListener('click', (e) => {
            const action = e.target.closest('[data-action]')?.dataset.action;
            
            switch (action) {
                case 'wishlist':
                    this.bulkAddToWishlist();
                    break;
                case 'remove':
                    this.bulkRemoveItems();
                    break;
                case 'save':
                    this.bulkSaveForLater();
                    break;
                case 'close':
                    this.exitSelectionMode();
                    break;
            }
        });
        
        this.selectAllCheckbox.addEventListener('change', () => {
            if (this.selectAllCheckbox.checked) {
                this.selectAllItems();
            } else {
                this.deselectAllItems();
            }
        });
    }

    selectAllItems() {
        this.cartItems.forEach(item => {
            const itemId = this.getItemId(item);
            this.selectedItems.add(itemId);
            item.classList.add('selected');
            
            const checkbox = item.querySelector('.item-selection-checkbox');
            if (checkbox) checkbox.checked = true;
        });
        
        this.updateSelectionUI();
    }

    deselectAllItems() {
        this.selectedItems.clear();
        
        this.cartItems.forEach(item => {
            item.classList.remove('selected');
            
            const checkbox = item.querySelector('.item-selection-checkbox');
            if (checkbox) checkbox.checked = false;
        });
        
        this.updateSelectionUI();
    }

    bulkAddToWishlist() {
        if (this.selectedItems.size === 0) return;
        
        this.selectedItems.forEach(itemId => {
            const item = this.getItemById(itemId);
            if (item) {
                this.addToWishlist(item);
            }
        });
        
        this.showToast(`Đã thêm ${this.selectedItems.size} sản phẩm vào danh sách yêu thích`, 'success');
        this.exitSelectionMode();
    }

    bulkRemoveItems() {
        if (this.selectedItems.size === 0) return;
        
        const confirmMessage = `Bạn có chắc chắn muốn xóa ${this.selectedItems.size} sản phẩm khỏi giỏ hàng?`;
        
        if (confirm(confirmMessage)) {
            this.selectedItems.forEach(itemId => {
                const item = this.getItemById(itemId);
                if (item) {
                    this.removeItem(item);
                }
            });
            
            this.showToast(`Đã xóa ${this.selectedItems.size} sản phẩm khỏi giỏ hàng`, 'success');
            this.exitSelectionMode();
        }
    }

    bulkSaveForLater() {
        if (this.selectedItems.size === 0) return;
        
        // Implementation for saving items for later
        this.showToast(`Đã lưu ${this.selectedItems.size} sản phẩm để mua sau`, 'success');
        this.exitSelectionMode();
    }

    getItemById(itemId) {
        return Array.from(this.cartItems).find(item => this.getItemId(item) == itemId);
    }

    removeItem(item) {
        item.style.transition = 'all 0.4s ease';
        item.style.transform = 'translateX(-100%)';
        item.style.opacity = '0';
        
        setTimeout(() => {
            item.remove();
            this.updateCartItems();
        }, 400);
    }

    updateCartItems() {
        this.cartItems = document.querySelectorAll('.cart-item-card');
    }

    setupSmartRecommendations() {
        // Create recommendations section
        const recommendationsSection = document.createElement('div');
        recommendationsSection.className = 'smart-recommendations';
        recommendationsSection.innerHTML = `
            <div class="recommendations-header">
                <h3>Có thể bạn cũng thích</h3>
                <p>Dựa trên sản phẩm trong giỏ hàng của bạn</p>
            </div>
            <div class="recommendations-grid">
                <!-- Recommendations will be loaded here -->
            </div>
        `;
        
        const cartSummary = document.querySelector('.cart-summary');
        if (cartSummary) {
            cartSummary.insertAdjacentElement('afterend', recommendationsSection);
        }
        
        // Load recommendations
        this.loadSmartRecommendations();
    }

    loadSmartRecommendations() {
        // Simulate smart recommendations based on cart content
        setTimeout(() => {
            const recommendations = this.generateRecommendations();
            this.displayRecommendations(recommendations);
        }, 1000);
    }

    generateRecommendations() {
        // Mock recommendations - in real app, this would be from an API
        return [
            {
                id: 'rec1',
                title: 'Sách hay được đề xuất',
                author: 'Tác giả nổi tiếng',
                price: 150000,
                originalPrice: 200000,
                image: '/images/book1.jpg',
                reason: 'Vì bạn thích sách văn học'
            },
            {
                id: 'rec2',
                title: 'Combo sách bán chạy',
                author: 'Nhiều tác giả',
                price: 300000,
                originalPrice: 400000,
                image: '/images/book2.jpg',
                reason: 'Thường được mua cùng'
            }
        ];
    }

    displayRecommendations(recommendations) {
        const grid = document.querySelector('.recommendations-grid');
        if (!grid) return;
        
        grid.innerHTML = recommendations.map(item => `
            <div class="recommendation-card" data-item-id="${item.id}">
                <div class="rec-image">
                    <img src="${item.image}" alt="${item.title}" loading="lazy">
                    <div class="rec-reason">${item.reason}</div>
                </div>
                <div class="rec-content">
                    <h4 class="rec-title">${item.title}</h4>
                    <p class="rec-author">${item.author}</p>
                    <div class="rec-price">
                        <span class="current-price">${this.formatPrice(item.price)}</span>
                        ${item.originalPrice ? `<span class="original-price">${this.formatPrice(item.originalPrice)}</span>` : ''}
                    </div>
                    <button class="rec-add-btn" data-item-id="${item.id}">
                        <i class="fas fa-plus"></i>
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        `).join('');
        
        // Add event listeners
        grid.querySelectorAll('.rec-add-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemId = e.target.dataset.itemId;
                this.addRecommendationToCart(itemId);
            });
        });
    }

    addRecommendationToCart(itemId) {
        // Add recommendation to cart
        this.showToast('Đã thêm sản phẩm đề xuất vào giỏ hàng', 'success');
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.cart-container')) {
        window.cartSmartUX = new CartSmartUX();
    }
});

// CSS for Smart UX Features
const smartUXStyles = `
/* Selection Mode Styles */
.selection-mode .cart-item-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.selection-mode .cart-item-card:hover {
    background: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);
}

.selection-mode .cart-item-card.selected {
    background: linear-gradient(145deg, #e6fffa 0%, #b2f5ea 100%);
    border-color: #38b2ac;
    transform: translateY(-2px);
}

.selection-checkbox-container {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(5px);
}

.item-selection-checkbox {
    opacity: 0;
    position: absolute;
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.selection-checkmark {
    width: 16px;
    height: 16px;
    border: 2px solid #cbd5e0;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
    background: white;
}

.item-selection-checkbox:checked + .selection-checkmark {
    background: #38b2ac;
    border-color: #38b2ac;
}

.item-selection-checkbox:checked + .selection-checkmark::after {
    content: '';
    position: absolute;
    left: 4px;
    top: 1px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Select All Container */
.select-all-container {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.select-all-container.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.select-all-wrapper {
    display: flex;
    align-items: center;
}

.select-all-checkbox {
    margin-right: 12px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.select-all-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #2d3748;
    cursor: pointer;
    margin: 0;
}

.selection-count {
    color: #718096;
    font-weight: 400;
    margin-left: 8px;
}

/* Bulk Action Bar */
.bulk-action-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

.bulk-action-bar.visible {
    transform: translateY(0);
}

.bulk-actions-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}

.selected-info {
    font-weight: 600;
    font-size: 1rem;
}

.selected-count {
    color: #ffd700;
    font-size: 1.2rem;
}

.bulk-actions {
    display: flex;
    gap: 12px;
}

.bulk-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.bulk-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

.bulk-close {
    background: transparent;
    color: white;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.bulk-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

/* Smart Filters */
.smart-filters-container {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.smart-filters {
    display: flex;
    gap: 24px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-label {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    margin: 0;
}

.smart-filter-select {
    background: linear-gradient(145deg, #f7fafc 0%, #edf2f7 100%);
    border: 1px solid #cbd5e0;
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: 500;
    color: #2d3748;
    cursor: pointer;
    transition: all 0.3s ease;
}

.smart-filter-select:hover {
    border-color: #667eea;
    background: linear-gradient(145deg, #edf2f7 0%, #e2e8f0 100%);
}

.smart-filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-chip {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
    color: #2d3748;
    border: none;
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-chip:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-1px);
}

.filter-chip.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Quantity Suggestions */
.quantity-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    z-index: 100;
    overflow: hidden;
    margin-top: 4px;
}

.suggestion-item {
    padding: 10px 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    border-bottom: 1px solid #f7fafc;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    color: #667eea;
}

.suggestion-common {
    font-weight: 500;
}

.suggestion-smart {
    color: #667eea;
    font-weight: 600;
}

.suggestion-bulk {
    color: #e53e3e;
    font-weight: 600;
}

/* Floating Heart Animation */
.floating-heart {
    position: fixed;
    z-index: 9999;
    pointer-events: none;
    color: #e53e3e;
    font-size: 2rem;
    animation: floatHeart 1s ease-out forwards;
}

@keyframes floatHeart {
    0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 1;
    }
    50% {
        transform: translate(-50%, -80px) scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: translate(-50%, -120px) scale(1);
        opacity: 0;
    }
}

/* Auto Save Indicator */
.auto-save-indicator {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.auto-save-indicator.visible {
    transform: translateX(0);
}

.auto-save-indicator i {
    margin-right: 8px;
}

/* Smart Recommendations */
.smart-recommendations {
    background: white;
    border-radius: 20px;
    padding: 28px;
    margin-top: 32px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.recommendations-header {
    text-align: center;
    margin-bottom: 24px;
}

.recommendations-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #2d3748 0%, #667eea 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.recommendations-header p {
    color: #718096;
    margin: 0;
    font-weight: 500;
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.recommendation-card {
    background: linear-gradient(145deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.recommendation-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    border-color: #667eea;
}

.rec-image {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
}

.rec-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.recommendation-card:hover .rec-image img {
    transform: scale(1.05);
}

.rec-reason {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(102, 126, 234, 0.9);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(5px);
}

.rec-content {
    padding: 20px;
}

.rec-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.rec-author {
    color: #718096;
    font-size: 0.9rem;
    margin: 0 0 12px 0;
    font-weight: 500;
}

.rec-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}

.rec-price .current-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #e53e3e;
}

.rec-price .original-price {
    font-size: 0.9rem;
    color: #a0aec0;
    text-decoration: line-through;
}

.rec-add-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.rec-add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

/* Responsive Design */
@media (max-width: 768px) {
    .smart-filters {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .bulk-actions-content {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .bulk-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .recommendations-grid {
        grid-template-columns: 1fr;
    }
    
    .select-all-container {
        padding: 12px 16px;
    }
    
    .selection-checkbox-container {
        top: 12px;
        left: 12px;
    }
}
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = smartUXStyles;
document.head.appendChild(styleSheet);

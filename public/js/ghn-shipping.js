// GHN Shipping Integration
class GHNShipping {
    constructor() {
        this.init();
    }

    init() {
        this.loadProvinces();
        this.bindEvents();
    }    bindEvents() {
        // Bind province change
        $('#tinh').on('change', (e) => {
            const provinceId = e.target.value;
            const provinceName = e.target.options[e.target.selectedIndex].text;
            
            // Lưu thông tin tỉnh vào các hidden field
            $('#ten_tinh').val(provinceName);
            $('#province_id').val(provinceId);
            
            if (provinceId) {
                this.loadDistricts(provinceId);
                this.clearWards();
                this.clearShippingInfo();
            }
        });

        // Bind district change
        $('#quan').on('change', (e) => {
            const districtId = e.target.value;
            const districtName = e.target.options[e.target.selectedIndex].text;
            
            // Lưu thông tin quận vào các hidden field
            $('#ten_quan').val(districtName);
            $('#ghn_district_id').val(districtId);
            
            if (districtId) {
                this.loadWards(districtId);
                this.clearShippingInfo();
            }
        });

        // Bind ward change
        $('#phuong').on('change', (e) => {
            const wardCode = e.target.value;
            const wardName = e.target.options[e.target.selectedIndex].text;
            const districtId = $('#quan').val();
            
            // Lưu thông tin phường vào các hidden field
            $('#ten_phuong').val(wardName);
            $('#ward_code').val(wardCode);
            
            if (wardCode && districtId) {
                this.calculateShipping(districtId, wardCode);
            }
        });

        // Bind existing address selection
        $('select[name="address_id"]').on('change', (e) => {
            const addressId = e.target.value;
            if (addressId) {
                this.loadShippingForAddress(addressId);
            }
        });
    }

    async loadProvinces() {
        try {
            const response = await fetch('/ghn/provinces', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            });            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#tinh', data.data, 'ProvinceID', 'ProvinceName');
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }    async loadDistricts(provinceId) {
        try {
            this.showLoading('#quan');
            
            // Đảm bảo provinceId là số nguyên
            const provinceIdInt = parseInt(provinceId);
            if (isNaN(provinceIdInt)) {
                throw new Error('Invalid province ID');
            }
            
            const response = await fetch('/ghn/districts', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ province_id: provinceIdInt })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#quan', data.data, 'DistrictID', 'DistrictName');
            } else {
                console.error('Failed to load districts:', data.message);
                this.showError('Không thể tải danh sách quận/huyện');
            }
            this.hideLoading('#quan');
        } catch (error) {
            console.error('Error loading districts:', error);
            this.showError('Lỗi khi tải danh sách quận/huyện');
            this.hideLoading('#quan');
        }
    }    async loadWards(districtId) {
        try {
            this.showLoading('#phuong');
            
            // Đảm bảo districtId là số nguyên
            const districtIdInt = parseInt(districtId);
            if (isNaN(districtIdInt)) {
                throw new Error('Invalid district ID');
            }
            
            const response = await fetch('/ghn/wards', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ district_id: districtIdInt })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#phuong', data.data, 'WardCode', 'WardName');
            } else {
                console.error('Failed to load wards:', data.message);
                this.showError('Không thể tải danh sách phường/xã');
            }
            this.hideLoading('#phuong');
        } catch (error) {
            console.error('Error loading wards:', error);
            this.showError('Lỗi khi tải danh sách phường/xã');
            this.hideLoading('#phuong');
        }
    }

    async calculateShipping(districtId, wardCode) {
        try {
            this.showShippingLoading();

            const response = await fetch('/ghn/full-shipping-info', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },                body: JSON.stringify({
                    to_district_id: parseInt(districtId),
                    to_ward_code: wardCode
                    // weight sẽ được tính tự động từ giỏ hàng ở backend
                })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.displayShippingInfo(data.data);
                this.updateOrderSummary(data.data.shipping_fee);
            } else {
                this.showShippingError(data.message || 'Không thể tính phí vận chuyển');
            }
        } catch (error) {
            console.error('Error calculating shipping:', error);
            this.showShippingError('Có lỗi xảy ra khi tính phí vận chuyển');
        } finally {
            this.hideShippingLoading();
        }
    }

    async loadShippingForAddress(addressId) {
        try {
            this.showShippingLoading();

            // Load shipping info cho địa chỉ đã có
            const address = this.getAddressData(addressId);
            if (address && address.district_id && address.ward_code) {
                await this.calculateShipping(address.district_id, address.ward_code);
            }
        } catch (error) {
            console.error('Error loading shipping for address:', error);
            this.showShippingError('Không thể tải thông tin vận chuyển');
        }
    }

    populateSelect(selector, data, valueKey, textKey) {
        const $select = $(selector);
        $select.empty().append('<option value="">-- Chọn --</option>');
        
        data.forEach(item => {
            $select.append(`<option value="${item[valueKey]}">${item[textKey]}</option>`);
        });
    }

    clearWards() {
        $('#ward').empty().append('<option value="">-- Chọn phường/xã --</option>');
    }

    clearShippingInfo() {
        $('#shipping-info').html('');
        this.updateOrderSummary(0);
    }    displayShippingInfo(shippingData) {
        const html = `
            <div class="shipping-info bg-blue-50 p-4 rounded-lg">
                <h4 class="font-semibold text-blue-800 mb-2">Thông tin vận chuyển</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Phí vận chuyển:</span>
                        <span class="font-semibold text-red-600">${this.formatCurrency(shippingData.shipping_fee)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Dịch vụ:</span>
                        <span>${shippingData.service_name}</span>
                    </div>
                    ${shippingData.expected_delivery_date ? `
                    <div class="flex justify-between">
                        <span>Ngày giao dự kiến:</span>
                        <span class="font-semibold text-green-600">${shippingData.expected_delivery_date}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        $('#shipping-info').html(html);
        
        // Update hidden fields
        $('#form_hidden_shipping_fee').val(shippingData.shipping_fee);
        $('#form_hidden_ghn_service_id').val(shippingData.service_id);
        
        // Update shipping fee display in order summary
        $('#shipping-fee').text(new Intl.NumberFormat('vi-VN').format(shippingData.shipping_fee) + 'đ');
        
        // Call updateTotal if available (from checkout.blade.php)
        if (typeof updateTotal === 'function') {
            updateTotal();
        }
        
        console.log('Updated shipping fee:', shippingData.shipping_fee);
        console.log('Updated service ID:', shippingData.service_id);
        console.log('Updated shipping-fee display element');
    }

    updateOrderSummary(shippingFee) {
        const subtotal = parseFloat($('#subtotal').text().replace(/[^\d.-]/g, '')) || 0;
        const discount = parseFloat($('#discount-amount').text().replace(/[^\d.-]/g, '')) || 0;
        const total = subtotal + shippingFee - discount;
        
        $('#shipping-fee').text(this.formatCurrency(shippingFee));
        $('#total-amount').text(this.formatCurrency(total));
          // Update final total input
        $('input[name="final_total"]').val(total);
    }

    getAddressData(addressId) {
        // Get address data from hidden inputs or data attributes
        const addressElement = $(`input[value="${addressId}"]`).closest('.address-item');
        return {
            district_id: addressElement.data('district-id'),
            ward_code: addressElement.data('ward-code')
        };
    }

    showLoading(selector) {
        $(selector).prop('disabled', true).html('<option>Đang tải...</option>');
    }

    hideLoading(selector) {
        $(selector).prop('disabled', false);
    }

    showShippingLoading() {
        $('#shipping-info').html(`
            <div class="flex items-center justify-center p-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="ml-2">Đang tính phí vận chuyển...</span>
            </div>
        `);
    }

    hideShippingLoading() {
        // Loading will be replaced by shipping info or error
    }

    showShippingError(message) {
        $('#shipping-info').html(`
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <span>${message}</span>
            </div>
        `);
    }

    showError(message) {
        // Hiển thị thông báo lỗi chung
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
}

// Initialize when document is ready
$(document).ready(function() {
    if ($('#checkout-form').length) {
        new GHNShipping();
    }
});

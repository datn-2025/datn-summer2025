// Address management with GHN integration
class AddressManager {
    constructor() {
        this.init();
    }

    init() {
        this.loadProvinces();
        this.bindEvents();
    }

    bindEvents() {
        // Province change
        $('#new_address_province').on('change', (e) => {
            const provinceId = e.target.value;
            const provinceName = e.target.options[e.target.selectedIndex].text;
            
            $('#new_address_province_id').val(provinceId);
            $('#new_address_city_name').val(provinceName);
            
            if (provinceId) {
                this.loadDistricts(provinceId);
                this.clearDistricts();
                this.clearWards();
            }
        });

        // District change
        $('#new_address_district').on('change', (e) => {
            const districtId = e.target.value;
            const districtName = e.target.options[e.target.selectedIndex].text;
            
            $('#new_address_ghn_district_id').val(districtId);
            $('#new_address_district_name').val(districtName);
            
            if (districtId) {
                this.loadWards(districtId);
                this.clearWards();
            }
        });

        // Ward change
        $('#new_address_ward').on('change', (e) => {
            const wardCode = e.target.value;
            const wardName = e.target.options[e.target.selectedIndex].text;
            
            $('#new_address_ward_code').val(wardCode);
            $('#new_address_ward_name').val(wardName);
            
            // Trigger shipping calculation if we're on checkout page
            if ($('#checkout-form').length) {
                const districtId = $('#new_address_ghn_district_id').val();
                if (districtId && wardCode) {
                    this.calculateShipping(districtId, wardCode);
                }
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
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#new_address_province', data.data, 'ProvinceID', 'ProvinceName');
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }

    async loadDistricts(provinceId) {
        try {
            this.showLoading('#new_address_district');
            
            const response = await fetch('/ghn/districts', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ province_id: provinceId })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#new_address_district', data.data, 'DistrictID', 'DistrictName');
            }
            this.hideLoading('#new_address_district');
        } catch (error) {
            console.error('Error loading districts:', error);
            this.hideLoading('#new_address_district');
        }
    }

    async loadWards(districtId) {
        try {
            this.showLoading('#new_address_ward');
            
            const response = await fetch('/ghn/wards', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ district_id: districtId })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.populateSelect('#new_address_ward', data.data, 'WardCode', 'WardName');
            }
            this.hideLoading('#new_address_ward');
        } catch (error) {
            console.error('Error loading wards:', error);
            this.hideLoading('#new_address_ward');
        }
    }

    async calculateShipping(districtId, wardCode) {
        try {
            const response = await fetch('/ghn/full-shipping-info', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    to_district_id: parseInt(districtId),
                    to_ward_code: wardCode,
                    weight: this.calculateCartWeight()
                })
            });

            const data = await response.json();
            if (data.success && data.data) {
                this.displayShippingInfo(data.data);
                this.updateOrderSummary(data.data.shipping_fee);
            }
        } catch (error) {
            console.error('Error calculating shipping:', error);
        }
    }

    populateSelect(selector, data, valueKey, textKey) {
        const $select = $(selector);
        $select.empty().append('<option value="">-- Chọn --</option>');
        
        data.forEach(item => {
            $select.append(`<option value="${item[valueKey]}">${item[textKey]}</option>`);
        });
    }

    clearDistricts() {
        $('#new_address_district').empty().append('<option value="">-- Chọn quận/huyện --</option>');
        $('#new_address_ghn_district_id').val('');
        $('#new_address_district_name').val('');
    }

    clearWards() {
        $('#new_address_ward').empty().append('<option value="">-- Chọn phường/xã --</option>');
        $('#new_address_ward_code').val('');
        $('#new_address_ward_name').val('');
    }

    displayShippingInfo(shippingData) {
        const html = `
            <div class="shipping-info bg-blue-50 p-4 rounded-lg mt-4">
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
        
        $('#new-address-shipping-info').html(html);
        
        // Update hidden fields
        $('input[name="shipping_fee_applied"]').val(shippingData.shipping_fee);
    }

    updateOrderSummary(shippingFee) {
        const subtotal = parseFloat($('#subtotal').text().replace(/[^\d.-]/g, '')) || 0;
        const discount = parseFloat($('#discount-amount').text().replace(/[^\d.-]/g, '')) || 0;
        const total = subtotal + shippingFee - discount;
        
        $('#shipping-fee').text(this.formatCurrency(shippingFee));
        $('#total-amount').text(this.formatCurrency(total));
    }

    calculateCartWeight() {
        const itemCount = parseInt($('#cart-item-count').text()) || 1;
        return itemCount * 200; // 200g per book default
    }

    showLoading(selector) {
        $(selector).prop('disabled', true).html('<option>Đang tải...</option>');
    }

    hideLoading(selector) {
        $(selector).prop('disabled', false);
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
    new AddressManager();
});

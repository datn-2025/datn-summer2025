
document.addEventListener('DOMContentLoaded', function () {
    const physicalCheckbox = document.getElementById('has_physical');
    const physicalForm = document.getElementById('physical_format');
    const ebookCheckbox = document.getElementById('has_ebook');
    const ebookForm = document.getElementById('ebook_format');
    const allowSampleCheckbox = document.getElementById('allow_sample_read');
    const sampleFileContainer = document.getElementById('sample_file_container');

    function toggleDisplay(checkbox, element) {
        if (checkbox.checked) {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }

    // Khởi tạo trạng thái ban đầu
    toggleDisplay(physicalCheckbox, physicalForm);
    toggleDisplay(ebookCheckbox, ebookForm);
    toggleDisplay(allowSampleCheckbox, sampleFileContainer);

    // Lắng nghe sự kiện thay đổi checkbox
    physicalCheckbox.addEventListener('change', function () {
        toggleDisplay(this, physicalForm);
    });

    ebookCheckbox.addEventListener('change', function () {
        toggleDisplay(this, ebookForm);
    });

    allowSampleCheckbox.addEventListener('change', function () {
        toggleDisplay(this, sampleFileContainer);
    });
});

document.addEventListener('DOMContentLoaded', function() {
  const attrCheckboxes = document.querySelectorAll('.physical-attr');
  const priceSection = document.getElementById('physical_price_section');

  function togglePriceSection() {
    // Kiểm tra xem có checkbox thuộc tính nào được chọn không
    const anyChecked = Array.from(attrCheckboxes).some(cb => cb.checked);
    priceSection.style.display = anyChecked ? 'block' : 'none';
  }

  // Gán sự kiện change cho tất cả checkbox thuộc tính
  attrCheckboxes.forEach(cb => {
    cb.addEventListener('change', togglePriceSection);
  });

  // Khởi tạo trạng thái khi load trang
  togglePriceSection();
});

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thuộc tính sản phẩm
    const attributeSelects = document.querySelectorAll('.attribute-select');
    
    attributeSelects.forEach(select => {
        // Xử lý sự kiện khi chọn thuộc tính
        select.addEventListener('change', function() {
            // Hiển thị giá trị của thuộc tính đã chọn
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const valueName = selectedOption.getAttribute('data-value-name');
                const extraPriceInput = this.closest('.input-group').querySelector('.attribute-extra-price');
                
                // Hiển thị giá trị đã chọn và giá thêm trong input
                extraPriceInput.placeholder = `Giá thêm cho ${valueName}`;
            }
        });
    });

    // Xử lý nút thêm thuộc tính
    document.querySelectorAll('.add-attribute-value').forEach(button => {
        button.addEventListener('click', function() {
            const attributeGroup = this.closest('.attribute-group');
            const select = attributeGroup.querySelector('.attribute-select');
            const extraPriceInput = attributeGroup.querySelector('.attribute-extra-price');
            const selectedValuesContainer = attributeGroup.querySelector('.selected-values');
            
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption.value) {
                alert('Vui lòng chọn một giá trị thuộc tính');
                return;
            }
            
            const attributeId = select.getAttribute('data-attribute-id');
            const attributeName = select.getAttribute('data-attribute-name');
            const valueId = selectedOption.value;
            const valueName = selectedOption.getAttribute('data-value-name');
            const extraPrice = extraPriceInput.value || 0;
            
            // Kiểm tra xem thuộc tính này đã được thêm chưa
            const existingValue = attributeGroup.querySelector(`input[name="attribute_values[${valueId}][id]"]`);
            if (existingValue) {
                alert(`Thuộc tính ${valueName} đã được thêm`);
                return;
            }
            
            // Tạo badge hiển thị thuộc tính đã chọn
            const badge = document.createElement('div');
            badge.className = 'badge bg-primary me-2 mb-2 p-2';
            badge.innerHTML = `
                ${valueName} (+${new Intl.NumberFormat('vi-VN').format(extraPrice)}đ)
                <button type="button" class="btn-close btn-close-white ms-2" aria-label="Close"></button>
            `;
            
            // Tạo input ẩn để lưu dữ liệu
            const hiddenInputId = document.createElement('input');
            hiddenInputId.type = 'hidden';
            hiddenInputId.name = `attribute_values[${valueId}][id]`;
            hiddenInputId.value = valueId;
            
            const hiddenInputExtraPrice = document.createElement('input');
            hiddenInputExtraPrice.type = 'hidden';
            hiddenInputExtraPrice.name = `attribute_values[${valueId}][extra_price]`;
            hiddenInputExtraPrice.value = extraPrice;
            
            // Thêm vào container
            selectedValuesContainer.appendChild(badge);
            selectedValuesContainer.appendChild(hiddenInputId);
            selectedValuesContainer.appendChild(hiddenInputExtraPrice);
            
            // Xử lý sự kiện xóa
            badge.querySelector('.btn-close').addEventListener('click', function() {
                badge.remove();
                hiddenInputId.remove();
                hiddenInputExtraPrice.remove();
            });
            
            // Reset form
            select.value = '';
            extraPriceInput.value = '0';
            extraPriceInput.placeholder = 'Giá thêm';
        });
    });
});

// chọn ảnh
document.getElementById('cover_image').addEventListener('change', function (e) {
    const preview = document.querySelector('#cover_preview .preview-container');
    preview.innerHTML = ''; // clear
    const file = e.target.files[0];
    if (file) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'img-fluid';
        preview.appendChild(img);
    }
});

document.getElementById('images').addEventListener('change', function (e) {
    const preview = document.getElementById('images_preview');
    preview.innerHTML = ''; // clear
    Array.from(e.target.files).forEach(file => {
        const col = document.createElement('div');
        col.className = 'col-4';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'img-fluid rounded';
        col.appendChild(img);
        preview.appendChild(col);
    });
});

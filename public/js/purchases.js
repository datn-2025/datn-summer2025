document.addEventListener('DOMContentLoaded', function() {
    // Xử lý khi submit form đánh giá
    document.querySelectorAll('.review-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Thêm loading state khi submit
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang gửi...';
            }
        });
    });
});

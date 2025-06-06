<footer id="footer" class="padding-large bg-white text-dark">
  <div class="container">
    <div class="row">
      <div class="footer-top-area">
        <div class="row d-flex flex-wrap justify-content-between">
          {{-- Cột 1: Logo + mô tả --}}
          <div class="col-lg-3 col-sm-6 pb-3">
            <div class="footer-menu">
              <img src="{{ asset('storage/images/main-logo.png') }}" alt="logo" class="img-fluid mb-2" style="height: 40px;">
              <p>Mang đến trải nghiệm đọc sách hiện đại, tiện lợi và nhanh chóng cho mọi độc giả.</p>
              <div class="social-links">
                <ul class="d-flex list-unstyled gap-2">
                  <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                  <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                  <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                  <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                </ul>
              </div>
            </div>
          </div>

          {{-- Cột 2: Liên kết nhanh --}}
          <div class="col-lg-2 col-sm-6 pb-3">
            <div class="footer-menu text-capitalize">
              <h5 class="widget-title pb-2">Liên kết nhanh</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item mb-1"><a href="/">Trang chủ</a></li>
                <li class="menu-item mb-1"><a href="#">Giới thiệu</a></li>
                <li class="menu-item mb-1"><a href="#">Cửa hàng</a></li>
                <li class="menu-item mb-1"><a href="/news">Tin tức</a></li>
                <li class="menu-item mb-1"><a href="/contact">Liên hệ</a></li>
              </ul>
            </div>
          </div>

          {{-- Cột 3: Hỗ trợ khách hàng --}}
          <div class="col-lg-3 col-sm-6 pb-3">
            <div class="footer-menu text-capitalize">
              <h5 class="widget-title pb-2">Hỗ trợ khách hàng</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item mb-1"><a href="#">Theo dõi đơn hàng</a></li>
                <li class="menu-item mb-1"><a href="#">Chính sách đổi trả</a></li>
                <li class="menu-item mb-1"><a href="#">Giao hàng & thanh toán</a></li>
                <li class="menu-item mb-1"><a href="#">Liên hệ với chúng tôi</a></li>
                <li class="menu-item mb-1"><a href="#">Câu hỏi thường gặp</a></li>
              </ul>
            </div>
          </div>

          {{-- Cột 4: Liên hệ --}}
          <div class="col-lg-3 col-sm-6 pb-3">
            <div class="footer-menu contact-item">
              <h5 class="widget-title text-capitalize pb-2">Liên hệ</h5>
              <p>Bạn có thắc mắc hoặc góp ý gì không? <a href="mailto:yourinfo@gmail.com" class="text-decoration-underline">yourinfo@gmail.com</a></p>
              <p class="mt-2">Nếu bạn cần hỗ trợ? Hãy gọi cho chúng tôi. <a href="tel:123456789" class="text-decoration-underline">123456789</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<hr class="m-0">

<div id="footer-bottom" class="py-3 bg-light">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      {{-- Đối tác vận chuyển + thanh toán --}}
      <div class="ship-and-payment d-flex gap-md-5 flex-wrap">
        <div class="shipping d-flex align-items-center gap-2">
          <span>Đối tác vận chuyển:</span>
          <div class="card-wrap ps-2 d-flex gap-2 align-items-center">
            <img src="{{ asset('storage/images/dhl.png') }}" alt="DHL" style="height: 32px;">
            <img src="{{ asset('storage/images/shippingcard.png') }}" alt="Shipping Card" style="height: 32px;">
          </div>
        </div>
        <div class="payment-method d-flex align-items-center gap-2">
          <span>Hình thức thanh toán:</span>
          <div class="card-wrap ps-2 d-flex gap-2 align-items-center">
            <img src="{{ asset('storage/images/visa.jpg') }}" alt="Visa" style="height: 32px;">
            <img src="{{ asset('storage/images/mastercard.jpg') }}" alt="MasterCard" style="height: 32px;">
            <img src="{{ asset('storage/images/paypal.jpg') }}" alt="PayPal" style="height: 32px;">
          </div>
        </div>
      </div>

      {{-- Bản quyền --}}
      <div class="copyright text-muted text-center text-md-end small">
        © 2025 Bản quyền thuộc về BookBee. Thiết kế bởi BookBee Team
      </div>
    </div>
  </div>
</div>

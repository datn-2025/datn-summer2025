@extends('layouts.app') 

@section('content')

<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hồ Sơ Cá Nhân - BookBee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    :root {
      --primary: #6366f1;
      --primary-hover: #4f46e5;
      --secondary: #a855f7;
      --background: #f0f2f5;
      --surface: #ffffff;
      --text: #374151;
      --text-light: #6b7280;
      --border: #e5e7eb;
      --shadow: rgba(0,0,0,0.05);
    }

    [data-theme="dark"] {
      --primary: #818cf8;
      --primary-hover: #6366f1;
      --secondary: #c084fc;
      --background: #1f2937;
      --surface: #293548;
      --text: #f3f4f6;
      --text-light: #d1d5db;
      --border: #374151;
      --shadow: rgba(0,0,0,0.2);
    }

    /* Add glass morphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    [data-theme="dark"] .glass {
      background: rgba(0, 0, 0, 0.2);
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--background);
      min-height: 100vh;
      color: var(--text);
      transition: all 0.3s ease;
    }

    /* Modern 3D Card Effect */
    .card-3d {
      transform-style: preserve-3d;
      transform: perspective(1000px);
    }

    .card-3d:hover {
      transform: perspective(1000px) rotateX(5deg) rotateY(5deg);
    }

    /* Floating Animation */
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    .float {
      animation: float 3s ease-in-out infinite;
    }

    /* Theme Switcher */
    .theme-switch {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 20px var(--shadow);
      transition: all 0.3s ease;
    }

    .theme-switch:hover {
      transform: scale(1.1);
    }

    /* Enhanced Navigation */
    .navbar-custom {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      padding: 1rem 0;
      box-shadow: 0 2px 15px var(--shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(10px);
    }

    /* Enhanced Sidebar */
    .sidebar {
      background: var(--surface);
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 4px 20px var(--shadow);
      position: sticky;
      top: 100px;
      transition: all 0.3s ease;
    }

    .sidebar a {
      color: var(--text);
      background: linear-gradient(to right, transparent 50%, var(--primary) 50%);
      background-size: 200% 100%;
      background-position: left bottom;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background-position: right bottom;
      color: white;
      transform: translateX(10px);
    }

    /* Enhanced Form Controls */
    .form-control, .form-select {
      background: var(--surface);
      border: 1.5px solid var(--border);
      color: var(--text);
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
      transform: translateY(-2px);
    }

    /* Enhanced Avatar Section */
    .avatar {
      background: linear-gradient(135deg, 
        rgba(var(--primary-rgb), 0.1) 0%, 
        rgba(var(--secondary-rgb), 0.1) 100%);
      border-radius: 20px;
      padding: 30px;
      transition: all 0.3s ease;
    }

    .avatar:hover {
      transform: translateY(-5px);
    }

    .avatar img {
      border: none;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      padding: 5px;
      transition: all 0.5s ease;
    }

    .avatar img:hover {
      transform: scale(1.1) rotate(10deg);
    }

    /* Animated Button */
    .btn-save {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border: none;
      padding: 15px 40px;
      color: white;
      border-radius: 12px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-save::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        to bottom right,
        rgba(255,255,255,0.2) 0%,
        rgba(255,255,255,0.2) 40%,
        rgba(255,255,255,0.6) 50%,
        rgba(255,255,255,0.2) 60%,
        rgba(255,255,255,0.2) 100%
      );
      transform: rotate(45deg);
      transition: 0.5s;
      opacity: 0;
    }

    .btn-save:hover::after {
      opacity: 1;
      transform: rotate(45deg) translate(50%, 50%);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background: var(--background);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-hover);
    }

    /* Loading Animation */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(var(--background-rgb), 0.8);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 3px solid var(--border);
      border-top: 3px solid var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    /* Ripple Effect */
    .ripple {
      position: absolute;
      border-radius: 50%;
      transform: scale(0);
      animation: ripple 0.6s linear;
      background-color: rgba(255, 255, 255, 0.7);
    }

    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Toast Notifications */
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 30px;
      background: var(--surface);
      border-left: 4px solid var(--primary);
      border-radius: 4px;
      box-shadow: 0 4px 20px var(--shadow);
      transform: translateX(120%);
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .toast.show {
      transform: translateX(0);
    }

    /* Progress Indicator */
    .progress-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      transition: width 0.3s ease;
    }
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
      min-height: 100vh;
    }
    .navbar-custom {
      background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
      padding: 1rem 0;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(10px);
    }
    .navbar-custom .navbar-brand {
      color: #fff;
      font-weight: 700;
      font-size: 1.5rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
    }
    .navbar-custom .navbar-brand::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: #fff;
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.3s ease;
    }
    .navbar-custom .navbar-brand:hover::after {
      transform: scaleX(1);
      transform-origin: left;
    }
    .navbar-custom .nav-link {
      color: #fff;
      font-weight: 500;
      padding: 0.5rem 1rem;
      position: relative;
      transition: all 0.3s ease;
    }
    .navbar-custom .nav-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: #fff;
      transition: all 0.3s ease;
    }
    .navbar-custom .nav-link:hover::before {
      width: 80%;
      left: 10%;
    }
    .navbar-custom .nav-link:hover {
      transform: translateY(-2px);
    }
    .sidebar {
      min-width: 250px;
      background: white;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      position: sticky;
      top: 100px;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      margin-bottom: 8px;
      color: #4b5563;
      text-decoration: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .sidebar a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    .sidebar a:hover::before {
      transform: scaleY(1);
    }
    .sidebar a i {
      margin-right: 12px;
      font-size: 1.1rem;
      transition: transform 0.3s ease;
    }
    .sidebar a:hover {
      background: #f3f4f6;
      color: #6366f1;
      transform: translateX(5px);
    }
    .sidebar a:hover i {
      transform: scale(1.2);
    }
    .main-content {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .form-control, .form-select {
      border-radius: 10px;
      border: 1.5px solid #e5e7eb;
      padding: 12px;
      transition: all 0.3s ease;
      background: #fff;
    }
    .form-control:focus, .form-select:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
      transform: translateY(-1px);
    }
    .form-control::placeholder {
      color: #9ca3af;
    }
    .form-label {
      font-weight: 500;
      color: #374151;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .form-label i {
      color: #6366f1;
      font-size: 0.9em;
    }
    .avatar {
      position: relative;
      padding: 20px;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
      border-radius: 16px;
    }
    .avatar img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #6366f1;
      padding: 3px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(99, 102, 241, 0.2);
    }
    .avatar img:hover {
      transform: scale(1.05) rotate(5deg);
    }
    .avatar .upload-btn {
      margin-top: 15px;
      width: 100%;
      max-width: 200px;
    }
    .btn-save {
      background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      color: white;
      border-radius: 10px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .btn-save::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        to right,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
      );
      transition: 0.5s;
    }
    .btn-save:hover::before {
      left: 100%;
    }
    .btn-save:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
    }
    .disabled-input {
      background-color: #f3f4f6;
      cursor: not-allowed;
      opacity: 0.8;
    }
    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }
      .sidebar {
        margin-bottom: 20px;
        position: static;
      }
      .main-content {
        padding: 25px;
      }
      .avatar {
        margin-top: 20px;
      }
      .navbar-custom {
        padding: 0.5rem 0;
      }
      .navbar-custom .navbar-brand {
        font-size: 1.2rem;
      }
    }
    // Add loading animation
    .loading {
      position: relative;
    }
    .loading::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(5px);
    }
    .loading::before {
      content: '';
      position: absolute;
      width: 40px;
      height: 40px;
      top: 50%;
      left: 50%;
      margin: -20px 0 0 -20px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #6366f1;
      border-radius: 50%;
      z-index: 1;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <div class="progress-bar" style="width: 0%"></div>
  
  <nav class="navbar navbar-expand-lg navbar-custom mb-4">
    <div class="container-fluid px-5">
      <a class="navbar-brand" href="#">BookBee</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#">Tài Khoản</a></li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container">
    <div class="row g-4">
      <div class="col-md-3">
        <div class="sidebar">
          <a href="#"><i class="fas fa-user"></i>Tài Khoản Của Tôi</a>
          <a href="#"><i class="fas fa-address-card"></i>Hồ Sơ</a>
          <a href="#"><i class="fas fa-map-marker-alt"></i>Địa Chỉ</a>
          <a href="{{ url('/account/password/change') }}"><i class="fas fa-key"></i>Đổi Mật Khẩu</a>
        </div>
      </div>
      <div class="col-md-9">
        <div class="main-content">
            <h2 class="mb-3">Hồ Sơ Của Tôi</h2>
            <p class="text-muted mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

            <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Đặt phương thức PUT để update -->

            <div class="row">
                <div class="col-md-8">
                <!-- Tên đăng nhập -->
                <div class="mb-4">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                </div>


                <!-- Số điện thoại -->
                <div class="mb-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                </div>

                <div class="mb-4 text-center">
                    <label class="form-label">Ảnh đại diện (avatar)</label>
                   <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png">
                    <p class="text-muted mt-2 small">Tối đa 1MB. Định dạng: .JPEG, .PNG</p>
                </div>


                <!-- Nút lưu -->
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                </button>
                </div>

                <!-- Avatar hiển thị -->
                <div class="col-md-4 text-center avatar">
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="mb-3 shadow"
                             style="width: 150px; height: 150px; object-fit: cover;">
                </div>

            </div>
            </form>
        </div>
        </div>
    </div>
  </div>

  <div class="theme-switch">
    <i class="fas fa-moon"></i>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Theme Switching
    const themeSwitch = document.querySelector('.theme-switch');
    themeSwitch.addEventListener('click', () => {
      const html = document.documentElement;
      const currentTheme = html.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      
      html.setAttribute('data-theme', newTheme);
      themeSwitch.innerHTML = `<i class="fas fa-${newTheme === 'light' ? 'moon' : 'sun'}"></i>`;
      
      localStorage.setItem('theme', newTheme);
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    themeSwitch.innerHTML = `<i class="fas fa-${savedTheme === 'light' ? 'moon' : 'sun'}"></i>`;

    // Progress bar
    const progressBar = document.querySelector('.progress-bar');
    window.addEventListener('scroll', () => {
      const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = (winScroll / height) * 100;
      progressBar.style.width = scrolled + '%';
    });

    // Enhanced form validation and submission
    function luuHoSo() {
      const form = document.querySelector('.main-content');
      const ten = document.getElementById('ten').value;
      const required = ['ten', 'gioiTinh', 'ngay', 'thang', 'nam'];
      let isValid = true;
      let errors = [];

      required.forEach(field => {
        const input = document.getElementById(field);
        if (!input?.value) {
          isValid = false;
          errors.push(`Vui lòng nhập ${field.replace(/([A-Z])/g, ' $1').toLowerCase()}`);
          input?.classList.add('is-invalid');
        } else {
          input?.classList.remove('is-invalid');
        }
      });

      if (!isValid) {
        Swal.fire({
          icon: 'error',
          title: 'Vui lòng kiểm tra lại',
          html: errors.join('<br>'),
          confirmButtonColor: getComputedStyle(document.documentElement)
            .getPropertyValue('--primary')
        });
        return;
      }

      // Show loading
      form.classList.add('loading');
      
      // Simulate API call
      setTimeout(() => {
        form.classList.remove('loading');
        
        Swal.fire({
          icon: 'success',
          title: 'Thành công!',
          text: 'Thông tin hồ sơ đã được cập nhật',
          showConfirmButton: false,
          timer: 1500,
          backdrop: `
            rgba(var(--primary-rgb), 0.1)
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0C13.432 0 0 13.432 0 30c0 16.568 13.432 30 30 30 16.568 0 30-13.432 30-30C60 13.432 46.568 0 30 0zm0 54C16.745 54 6 43.255 6 30S16.745 6 30 6s24 10.745 24 24-10.745 24-24 24zm12.844-33.755l-15.879 15.88-9.808-9.808 4.242-4.243 5.566 5.566 11.637-11.637 4.242 4.242z' fill='%236366f1'/%3E%3C/svg%3E")
            center center no-repeat
          `
        });

        // Show toast notification
        showToast('Thông tin hồ sơ đã được cập nhật!');
      }, 1500);
    }

    // Toast notification
    function showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
      `;
      document.body.appendChild(toast);
      
      requestAnimationFrame(() => {
        toast.classList.add('show');
      });

      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // Enhanced ripple effect
    document.querySelectorAll('.btn-save').forEach(button => {
      button.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('div');
        ripple.className = 'ripple';
        ripple.style.width = ripple.style.height = `${Math.max(rect.width, rect.height)}px`;
        ripple.style.left = `${e.clientX - rect.left - ripple.offsetWidth/2}px`;
        ripple.style.top = `${e.clientY - rect.top - ripple.offsetHeight/2}px`;
        
        this.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
      });
    });

    // Image preview
    const imageInput = document.querySelector('input[type="file"]');
    const previewImage = document.querySelector('.avatar img');
    
    imageInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewImage.classList.add('animate__animated', 'animate__bounceIn');
        }
        reader.readAsDataURL(file);
      }
    });

    // Smooth scroll with enhanced behavior
    document.querySelectorAll('.sidebar a').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        
        if (target) {
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
          const startPosition = window.pageYOffset;
          const distance = targetPosition - startPosition;
          const duration = 1000;
          let start = null;
          
          window.requestAnimationFrame(step);

          function step(timestamp) {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const percentage = Math.min(progress / duration, 1);
            
            window.scrollTo(0, startPosition + distance * easeInOutCubic(percentage));
            
            if (progress < duration) {
              window.requestAnimationFrame(step);
            }
          }
        }
      });
    });

    // Easing function
    function easeInOutCubic(t) {
      return t < 0.5 
        ? 4 * t * t * t 
        : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  

</body>
</html>
@endsection

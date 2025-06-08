@extends('layouts.app') 

@section('content')

<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đổi Mật Khẩu - BookBee</title>
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
      background: var(--surface);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--border);
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    /* Enhanced Sidebar */
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
    .sidebar a:hover::before,
    .sidebar a.active::before {
        transform: scaleY(1);
    }
    .sidebar a i {
        margin-right: 12px;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    .sidebar a:hover,
    .sidebar a.active {
        background: #f3f4f6;
        color: #6366f1;
        transform: translateX(5px);
    }
    .sidebar a:hover i,
    .sidebar a.active i {
        transform: scale(1.2);
    }
    .main-content {
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        animation: fadeIn 0.5s ease;
    }
    .form-control {
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        transform: translateY(-1px);
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
    .input-group-text {
        background: transparent;
        border-right: none;
        color: #6366f1;
    }
    .input-group .form-control {
        border-left: none;
    }
    .input-group .btn-outline-secondary {
        border-color: #e5e7eb;
        color: #6366f1;
    }
    .input-group .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        color: #4f46e5;
    }

    /* Additional Enhanced Styles */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-label {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 8px;
        color: var(--primary);
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

    /* Progress Indicator */
    .progress-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      z-index: 9999;
      transition: width 0.3s ease;
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

    /* Ripple Effect */
    .ripple {
      position: absolute;
      background: rgba(255,255,255,0.3);
      border-radius: 50%;
      pointer-events: none;
      animation: ripple 0.6s linear;
    }

    @keyframes ripple {
      0% {
        transform: scale(0);
        opacity: 1;
      }
      100% {
        transform: scale(4);
        opacity: 0;
      }
    }

    @media (max-width: 768px) {
        .sidebar {
            min-width: 100%;
            margin-bottom: 20px;
            position: static;
        }
        
        .main-content {
            padding: 20px;
        }
    }
  </style>
</head>

<body>
  <div class="progress-bar" style="width: 0%"></div>

  <!-- Thêm CSS cho Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

  <!-- Thêm JS cho Toastr và jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script>
      @if(Session::has('success'))
          toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "positionClass": "toast-top-right",
              "timeOut": "3000"
          }
          toastr.success("{{ Session::get('success') }}", "Thành công!");
      @endif
  </script>

  <div class="container">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <a href="{{ route('account.showUser') }}">
                    <i class="fas fa-user"></i>Hồ sơ của tôi
                </a>
                <a href="{{ route('account.password.change') }}" class="active">
                    <i class="fas fa-key"></i>Đổi mật khẩu
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="main-content">
                <h2 class="mb-3">Đổi mật khẩu</h2>
                <p class="text-muted mb-4">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
                    <form method="POST" action="{{ route('account.password.change') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="current_password" type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                <input id="password_confirmation" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('account.showUser') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-key me-2"></i>Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
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
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      
      document.documentElement.setAttribute('data-theme', newTheme);
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
      const scrollTop = window.pageYOffset;
      const docHeight = document.body.offsetHeight;
      const winHeight = window.innerHeight;
      const scrollPercent = scrollTop / (docHeight - winHeight);
      const scrolled = scrollPercent * 100;
      progressBar.style.width = scrolled + '%';
    });

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

    // Password toggle function
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const icon = event.currentTarget.querySelector('i');
      
      if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
      } else {
          input.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.currentTarget.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection

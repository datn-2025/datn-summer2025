<!-- Adidas-Style Top Navigation -->
<nav class="bg-black text-white py-2 px-4 text-xs">
  <div class="max-w-7xl mx-auto flex justify-between items-center">
    <div class="flex space-x-4">
      <a href="#" class="hover:text-gray-300 transition-colors">Miễn phí vận chuyển</a>
      <a href="#" class="hover:text-gray-300 transition-colors">Đổi trả & Hoàn tiền</a>
      <a href="#" class="hover:text-gray-300 transition-colors">Hỗ trợ</a>
    </div>
    <div class="flex space-x-4">
      @auth
        <a href="{{ route('account.showUser') }}" class="hover:text-gray-300 transition-colors">{{ Auth::user()->name }}</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="hover:text-gray-300 transition-colors">Đăng xuất</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="hover:text-gray-300 transition-colors">Đăng nhập</a>
        <a href="{{ route('account.register') }}" class="hover:text-gray-300 transition-colors">Đăng ký</a>
      @endauth
    </div>
  </div>
</nav>

<!-- Main Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50 adidas-nav">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="/" class="flex items-center">
          <h1 class="text-2xl font-black text-black">
            BOOK<span class="adidas-gradient-text">BEE</span>
          </h1>
        </a>
      </div>
      
      <!-- Navigation Links -->
      <div class="hidden md:flex space-x-8">
        <a href="/" class="text-black hover:text-blue-800 font-medium transition-colors duration-200 uppercase tracking-wide {{ request()->routeIs('home') ? 'text-blue-800 font-bold border-b-2 border-blue-800' : '' }}">Trang chủ</a>
        <a href="/books" class="text-black hover:text-blue-800 font-medium transition-colors duration-200 uppercase tracking-wide {{ request()->routeIs('books.*') ? 'text-blue-800 font-bold border-b-2 border-blue-800' : '' }}">Cửa hàng</a>
        <a href="#" class="text-black hover:text-blue-800 font-medium transition-colors duration-200 uppercase tracking-wide">Danh mục</a>
        <a href="#" class="text-black hover:text-blue-800 font-medium transition-colors duration-200 uppercase tracking-wide">Giới thiệu</a>
        <a href="#" class="text-black hover:text-blue-800 font-medium transition-colors duration-200 uppercase tracking-wide">Liên hệ</a>
      </div>
      
      <!-- Action Icons -->
      <div class="flex items-center space-x-4">
        <!-- Search -->
        <div class="relative hidden lg:block">
          <form method="GET" action="{{ route('books.index') }}" class="flex">
            <input type="search" name="search" placeholder="Tìm kiếm sách..." 
                   value="{{ request('search') }}"
                   class="w-64 px-4 py-2 border-2 border-gray-200 rounded-l-xl focus:border-black focus:outline-none transition-all duration-200 text-sm">
            <button type="submit" class="bg-black text-white px-4 py-2 rounded-r-xl hover:bg-blue-800 transition-colors duration-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </button>
          </form>
        </div>
        
        <!-- User Account -->
        <div class="relative group">
          <button class="adidas-btn p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </button>
          
          <!-- Dropdown menu -->
          <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-200">
            @auth
              <a href="{{ route('account.showUser') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                <i class="fas fa-user mr-2"></i>Tài khoản của tôi
              </a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-shopping-bag mr-2"></i>Đơn hàng
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                  <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                </button>
              </form>
            @else
              <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
              </a>
              <a href="{{ route('account.register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                <i class="fas fa-user-plus mr-2"></i>Đăng ký
              </a>
            @endauth
          </div>
        </div>
        
        <!-- Wishlist -->
        <button class="adidas-btn p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 relative">
          <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
          </svg>
          <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
        </button>
        
        <!-- Shopping Cart -->
        <button class="adidas-btn p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 relative">
          <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
          <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
        </button>
      </div>
      
      <!-- Mobile menu button -->
      <button class="md:hidden p-2" id="mobile-menu-btn">
        <svg class="w-6 h-6 text-black">
          <path fill="none" stroke="currentColor" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
  </div>
  
  <!-- Mobile Navigation -->
  <div class="md:hidden hidden" id="mobile-menu">
    <div class="px-4 py-4 space-y-2 bg-gray-50 border-t">
      <!-- Mobile Search -->
      <div class="mb-4">
        <form method="GET" action="{{ route('books.index') }}" class="flex">
          <input type="search" name="search" placeholder="Tìm kiếm sách..." 
                 value="{{ request('search') }}"
                 class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-l-xl focus:border-black focus:outline-none">
          <button type="submit" class="bg-black text-white px-4 py-2 rounded-r-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
        </form>
      </div>
      
      <!-- Mobile Menu Links -->
      <a href="/" class="block py-3 text-black hover:text-blue-800 font-medium uppercase tracking-wide {{ request()->routeIs('home') ? 'text-blue-800 font-bold' : '' }}">Trang chủ</a>
      <a href="/books" class="block py-3 text-black hover:text-blue-800 font-medium uppercase tracking-wide {{ request()->routeIs('books.*') ? 'text-blue-800 font-bold' : '' }}">Cửa hàng</a>
      <a href="#" class="block py-3 text-black hover:text-blue-800 font-medium uppercase tracking-wide">Danh mục</a>
      <a href="#" class="block py-3 text-black hover:text-blue-800 font-medium uppercase tracking-wide">Giới thiệu</a>
      <a href="#" class="block py-3 text-black hover:text-blue-800 font-medium uppercase tracking-wide">Liên hệ</a>
    </div>
  </div>
</nav>

<style>
  .adidas-nav {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  }
  
  .adidas-btn {
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }
  
  .adidas-btn:hover {
    transform: scale(1.05);
  }
  
  .adidas-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
  }
  
  .adidas-btn:hover::before {
    left: 100%;
  }
  
  .adidas-gradient-text {
    background: linear-gradient(45deg, #000000, #767677, #000000);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
</style>

<script>
  // Mobile menu toggle
  document.getElementById('mobile-menu-btn').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
  });
  
  // Navbar scroll effect
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.adidas-nav');
    if (window.scrollY > 100) {
      navbar.classList.add('shadow-xl');
      navbar.style.background = 'rgba(255, 255, 255, 0.95)';
      navbar.style.backdropFilter = 'blur(10px)';
    } else {
      navbar.classList.remove('shadow-xl');
      navbar.style.background = 'white';
      navbar.style.backdropFilter = 'none';
    }
  });
</script>
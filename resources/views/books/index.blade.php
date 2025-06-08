<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <title>BookBee - Premium Bookstore</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'adidas-black': '#000000',
            'adidas-white': '#ffffff',
            'adidas-gray': '#767677',
            'adidas-light-gray': '#f4f4f4',
            'adidas-blue': '#1e3a8a',
            'adidas-dark-gray': '#2d2d2d',
            'adidas-silver': '#c4c4c4',
            'adidas-red': '#d71921',
            'adidas-green': '#69be28',
          },
          fontFamily: {
            'adidas': ['AdihausDIN', 'Arial', 'Helvetica', 'sans-serif'],
          },
          animation: {
            'slide-in': 'slideIn 0.5s ease-out',
            'fade-in': 'fadeIn 0.3s ease-in',
            'bounce-soft': 'bounceSoft 2s infinite',
          }
        }
      }
    }
  </script>
  
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  
  <!-- Custom CSS -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
    
    * {
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    .adidas-hover:hover {
      transform: translateY(-2px);
      transition: all 0.3s ease;
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
    
    .book-card {
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }
    
    .book-card:hover {
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      transform: translateY(-4px);
    }
    
    .book-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      transition: left 0.6s;
    }
    
    .book-card:hover::after {
      left: 100%;
    }
    
    .filter-section {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      backdrop-filter: blur(10px);
    }
    
    .line-clamp-2 {
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
    }
    
    /* Adidas-style loading animation */
    @keyframes adidasPulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    
    @keyframes slideIn {
      from { transform: translateX(-100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes bounceSoft {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-4px); }
      60% { transform: translateY(-2px); }
    }
    
    .adidas-loading {
      animation: adidasPulse 2s infinite;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f4f4f4;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #000000;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #767677;
    }
    
    /* Adidas three stripes pattern */
    .adidas-stripes::before {
      content: '';
      position: absolute;
      top: 0;
      right: -20px;
      width: 60px;
      height: 100%;
      background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 5px,
        rgba(255,255,255,0.1) 5px,
        rgba(255,255,255,0.1) 10px
      );
    }
    
    /* Adidas gradient text */
    .adidas-gradient-text {
      background: linear-gradient(45deg, #000000, #767677, #000000);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Floating animation */
    .floating {
      animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
      0% { transform: translate(0, 0px); }
      50% { transform: translate(0, -10px); }
      100% { transform: translate(0, 0px); }
    }
    
    /* Ripple effect */
    .ripple {
      position: relative;
      overflow: hidden;
    }
    
    .ripple::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      transition: width 0.6s, height 0.6s;
      transform: translate(-50%, -50%);
    }
    
    .ripple:active::before {
      width: 300px;
      height: 300px;
    }
    
    /* Glass morphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Neon glow effect */
    .neon-glow {
      box-shadow: 0 0 5px #000, 0 0 10px #000, 0 0 15px #000;
    }
    
    .neon-glow:hover {
      box-shadow: 0 0 5px #000, 0 0 10px #000, 0 0 15px #000, 0 0 20px #000;
    }

    /* Enhanced Adidas-style effects */
    .adidas-card-3d {
      perspective: 1000px;
    }

    .adidas-card-inner {
      transition: transform 0.6s;
      transform-style: preserve-3d;
    }

    .adidas-card-3d:hover .adidas-card-inner {
      transform: rotateY(5deg) rotateX(5deg);
    }

    /* Premium loading skeleton */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }

    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Enhanced three stripes animation */
    .adidas-three-stripes {
      position: relative;
      overflow: hidden;
    }

    .adidas-three-stripes::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 8px,
        rgba(255,255,255,0.1) 8px,
        rgba(255,255,255,0.1) 16px
      );
      animation: stripes-move 3s linear infinite;
    }

    @keyframes stripes-move {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    /* Advanced button morphing */
    .morph-btn {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .morph-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 0;
      height: 100%;
      background: linear-gradient(45deg, #000, #1e3a8a);
      transition: width 0.4s cubic-bezier(0.23, 1, 0.320, 1);
      z-index: -1;
    }

    .morph-btn:hover::before {
      width: 100%;
    }

    /* Pulse effect for new arrivals */
    .pulse-new {
      animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
      0% { box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.7); }
      70% { box-shadow: 0 0 0 10px rgba(30, 58, 138, 0); }
      100% { box-shadow: 0 0 0 0 rgba(30, 58, 138, 0); }
    }

    /* Enhanced scroll indicator */
    .scroll-indicator {
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 4px;
      background: linear-gradient(90deg, #000, #1e3a8a, #000);
      z-index: 9999;
      transition: width 0.25s ease-out;
    }

    /* Advanced hover card effect */
    .hover-lift {
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .hover-lift:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    }

    /* Sophisticated badge effects */
    .premium-badge {
      background: linear-gradient(135deg, #000 0%, #333 50%, #000 100%);
      position: relative;
      overflow: hidden;
    }

    .premium-badge::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.3), transparent);
      animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="bg-adidas-white font-adidas antialiased">
    {{-- Thay thế navbar cũ bằng navbar layout chung --}}
    @include('layouts.partials.navbar')
    
    <!-- Enhanced Adidas-Style Hero Section -->
    <section class="bg-adidas-black text-adidas-white py-20 relative overflow-hidden adidas-stripes">
      <!-- Animated Background -->
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-adidas-black via-adidas-dark-gray to-adidas-black opacity-90"></div>
        <div class="absolute top-10 left-10 w-20 h-20 bg-adidas-white opacity-5 rounded-full floating"></div>
        <div class="absolute bottom-20 right-20 w-16 h-16 bg-adidas-white opacity-5 rounded-full floating" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-adidas-white opacity-5 rounded-full floating" style="animation-delay: 2s;"></div>
      </div>
      
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in">
          <div class="mb-6">
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-black tracking-tighter mb-4 transform hover:scale-105 transition-transform duration-500">
              <span class="inline-block animate-slide-in">BOOK</span><span class="text-adidas-white inline-block animate-slide-in" style="animation-delay: 0.2s;">BEE</span>
            </h1>
            <div class="h-1 w-24 bg-adidas-white mx-auto mb-6 animate-slide-in" style="animation-delay: 0.4s;"></div>
          </div>
          
          <p class="text-xl md:text-3xl lg:text-4xl font-light text-adidas-gray mb-8 animate-slide-in tracking-widest" style="animation-delay: 0.6s;">
            IMPOSSIBLE IS NOTHING
          </p>
          
          <p class="text-lg md:text-xl text-adidas-silver mb-10 max-w-2xl mx-auto animate-slide-in" style="animation-delay: 0.8s;">
            Discover the world's best books. Premium collection for passionate readers.
          </p>
          
          <!-- Call to Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12 animate-slide-in" style="animation-delay: 1s;">
            <button class="adidas-btn ripple bg-adidas-white text-adidas-black px-8 py-4 rounded-lg font-bold uppercase tracking-wide hover:bg-adidas-gray hover:text-adidas-white transition-all duration-300 neon-glow">
              Shop Now
            </button>
            <button class="adidas-btn ripple border-2 border-adidas-white text-adidas-white px-8 py-4 rounded-lg font-bold uppercase tracking-wide hover:bg-adidas-white hover:text-adidas-black transition-all duration-300">
              Explore Categories
            </button>
          </div>
          
          <!-- Breadcrumb -->
          <div class="flex justify-center items-center space-x-3 text-sm uppercase tracking-wider animate-slide-in" style="animation-delay: 1.2s;">
            <a href="/" class="text-adidas-gray hover:text-adidas-white transition-colors duration-200 hover:underline">Home</a>
            <svg class="w-4 h-4 text-adidas-gray">
              <use xlink:href="#alt-arrow-right-outline"></use>
            </svg>
            <span class="text-adidas-white font-semibold">Shop</span>
          </div>
        </div>
      </div>
      
      <!-- Enhanced Three Stripes Design Element -->
      <div class="absolute top-0 right-0 w-40 h-full opacity-10 flex space-x-3">
        <div class="w-8 h-full bg-gradient-to-b from-adidas-white to-transparent transform skew-x-12 animate-slide-in" style="animation-delay: 1.4s;"></div>
        <div class="w-8 h-full bg-gradient-to-b from-adidas-white to-transparent transform skew-x-12 animate-slide-in" style="animation-delay: 1.6s;"></div>
        <div class="w-8 h-full bg-gradient-to-b from-adidas-white to-transparent transform skew-x-12 animate-slide-in" style="animation-delay: 1.8s;"></div>
      </div>
      
      <!-- Adidas-style geometric patterns -->
      <div class="absolute bottom-0 left-0 w-full h-2 bg-gradient-to-r from-adidas-black via-adidas-white to-adidas-black opacity-20"></div>
    </section>
 
    <!-- Adidas-Style Stats Section -->
    <section class="bg-adidas-light-gray py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center group">
            <div class="bg-adidas-white p-6 rounded-lg shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-2">
              <div class="text-3xl font-black text-adidas-black mb-2">10K+</div>
              <div class="text-sm text-adidas-gray uppercase tracking-wide">Books Available</div>
            </div>
          </div>
          <div class="text-center group">
            <div class="bg-adidas-white p-6 rounded-lg shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-2">
              <div class="text-3xl font-black text-adidas-black mb-2">5K+</div>
              <div class="text-sm text-adidas-gray uppercase tracking-wide">Happy Customers</div>
            </div>
          </div>
          <div class="text-center group">
            <div class="bg-adidas-white p-6 rounded-lg shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-2">
              <div class="text-3xl font-black text-adidas-black mb-2">500+</div>
              <div class="text-sm text-adidas-gray uppercase tracking-wide">Authors</div>
            </div>
          </div>
          <div class="text-center group">
            <div class="bg-adidas-white p-6 rounded-lg shadow-sm group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-2">
              <div class="text-3xl font-black text-adidas-black mb-2">24/7</div>
              <div class="text-sm text-adidas-gray uppercase tracking-wide">Support</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced Adidas-Style Main Container -->
    <div class="bg-gradient-to-b from-adidas-light-gray to-adidas-white min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Enhanced Adidas-Style Product Listing -->
            <main class="flex-1 lg:order-2">
              <!-- Header Controls -->
              <div class="glass bg-adidas-white rounded-2xl shadow-lg p-8 mb-8 backdrop-blur-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                  <div class="flex items-center space-x-6">
                    <h2 class="text-3xl font-black text-adidas-black tracking-tight">
                      BOOKS <span class="adidas-gradient-text">COLLECTION</span>
                    </h2>
                    <div class="h-8 w-px bg-gradient-to-b from-adidas-black to-adidas-gray"></div>
                    <div class="bg-adidas-black text-adidas-white px-4 py-2 rounded-full text-sm font-bold">
                      {{ $books->total() }} ITEMS
                    </div>
                  </div>
                  <div class="flex items-center space-x-6">
                    <span class="text-sm font-bold text-adidas-gray uppercase tracking-wider">Sort by:</span>
                    <select onchange="location = this.value;" 
                            class="bg-adidas-white border-2 border-adidas-light-gray rounded-xl px-6 py-3 text-adidas-black font-semibold focus:border-adidas-black focus:outline-none transition-all duration-300 shadow-sm hover:shadow-lg">
                      <option value="">Featured</option>
                      <option value="name_asc">Name A-Z</option>
                      <option value="name_desc">Name Z-A</option>
                      <option value="price_asc">Price Low to High</option>
                      <option value="price_desc">Price High to Low</option>
                      <option value="rating_desc">Best Rating</option>
                      <option value="rating_asc">Lowest Rating</option>
                    </select>
                  </div>
                </div>
                
                <!-- Results info -->
                <div class="mt-4 flex items-center justify-between text-sm text-adidas-gray">
                  <span>Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }} results</span>
                  <div class="flex items-center space-x-2">
                    <span>View:</span>
                    <button class="p-2 bg-adidas-black text-adidas-white rounded-lg">
                      <svg class="w-4 h-4"><path fill="currentColor" d="M3 3h6v6H3V3zm8 0h6v6h-6V3zM3 11h6v6H3v-6zm8 0h6v6h-6v-6z"/></svg>
                    </button>
                    <button class="p-2 bg-adidas-light-gray text-adidas-gray rounded-lg hover:bg-adidas-gray hover:text-adidas-white transition-colors">
                      <svg class="w-4 h-4"><path fill="currentColor" d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Enhanced Adidas-Style Product Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($books as $book)
                <div class="book-card bg-adidas-white rounded-2xl overflow-hidden group cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                  <div class="relative overflow-hidden">
                    <!-- Premium Badge -->
                    <div class="absolute top-4 left-4 z-20">
                      <span class="bg-gradient-to-r from-adidas-black to-adidas-dark-gray text-adidas-white px-3 py-1 text-xs font-black uppercase rounded-full shadow-lg">
                        Premium
                      </span>
                    </div>
                    
                    <!-- Discount Badge -->
                    @if(!empty($book->discount))
                    <div class="absolute top-4 right-4 z-20">
                      <span class="bg-adidas-red text-adidas-white px-3 py-1 text-xs font-black uppercase rounded-full shadow-lg animate-bounce-soft">
                        -{{ $book->discount }}%
                      </span>
                    </div>
                    @endif

                    <!-- Book Cover with Enhanced Effects -->
                    <div class="aspect-[3/4] overflow-hidden bg-gradient-to-br from-adidas-light-gray to-adidas-silver relative">
                      @php
                        $imagePath = public_path('images/' . $book->cover_image);
                      @endphp
                      <img src="{{ file_exists($imagePath) ? asset('images/' . $book->cover_image) : asset('images/product-item1.png') }}" 
                           alt="{{ $book->title }}"
                           class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 filter group-hover:brightness-110">
                      
                      <!-- Overlay gradient -->
                      <div class="absolute inset-0 bg-gradient-to-t from-adidas-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <!-- Enhanced Hover Actions -->
                    <div class="absolute inset-0 bg-adidas-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100">
                      <div class="flex space-x-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <a href="{{ route('books.show', $book->slug) }}" 
                           class="adidas-btn ripple bg-adidas-white text-adidas-black p-4 rounded-full hover:bg-adidas-black hover:text-adidas-white transition-all duration-300 shadow-lg neon-glow">
                          <svg class="w-6 h-6">
                            <use xlink:href="#cart"></use>
                          </svg>
                        </a>
                        <button class="btn-wishlist adidas-btn ripple bg-adidas-white text-adidas-black p-4 rounded-full hover:bg-adidas-red hover:text-adidas-white transition-all duration-300 shadow-lg" 
                                data-book-id="{{ $book->id }}">
                          <svg class="w-6 h-6">
                            <use xlink:href="#heart"></use>
                          </svg>
                        </button>
                        <button class="adidas-btn ripple bg-adidas-white text-adidas-black p-4 rounded-full hover:bg-adidas-blue hover:text-adidas-white transition-all duration-300 shadow-lg">
                          <svg class="w-6 h-6">
                            <use xlink:href="#search"></use>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Enhanced Product Info -->
                  <div class="p-6">
                    <!-- Category Tag -->
                    <div class="mb-3">
                      <span class="inline-block bg-adidas-light-gray text-adidas-black px-3 py-1 text-xs font-bold uppercase rounded-full">
                        Fiction
                      </span>
                    </div>
                    
                    <!-- Book Title -->
                    <h3 class="font-black text-xl text-adidas-black mb-3 group-hover:text-adidas-blue transition-colors duration-300 leading-tight">
                      <a href="{{ route('books.show', $book->slug) }}" class="line-clamp-2 hover:underline">
                        {{ $book->title }}
                      </a>
                    </h3>

                    <!-- Author -->
                    <p class="text-adidas-gray text-sm font-semibold uppercase tracking-wider mb-4">
                      BY {{ strtoupper($book->author_name ?? 'Unknown Author') }}
                    </p>

                    <!-- Rating with Enhanced Design -->
                    <div class="flex items-center justify-between mb-6">
                      <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                          @php
                            $ratingRounded = round($book->avg_rating ?? 0);
                          @endphp
                          @for ($i = 1; $i <= 5; $i++)
                          <svg class="w-5 h-5 {{ $i <= $ratingRounded ? 'text-yellow-400' : 'text-adidas-light-gray' }} transition-colors duration-200">
                            @if($i <= $ratingRounded)
                              <use xlink:href="#star-fill"></use>
                            @else
                              <use xlink:href="#star-empty"></use>
                            @endif
                          </svg>
                          @endfor
                        </div>
                        <span class="text-sm font-bold text-adidas-black">({{ number_format($book->avg_rating ?? 0, 1) }})</span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-adidas-green rounded-full"></span>
                        <span class="text-xs text-adidas-gray font-medium">In Stock</span>
                      </div>
                    </div>

                    <!-- Price and Actions -->
                    <div class="flex items-center justify-between">
                      <div>
                        <span class="text-2xl font-black text-adidas-black">
                          {{ number_format($book->min_price ?? 0, 0, ',', '.') }}₫
                        </span>
                        @if(!empty($book->discount))
                        <span class="text-sm text-adidas-gray line-through ml-2">
                          {{ number_format(($book->min_price ?? 0) * 1.2, 0, ',', '.') }}₫
                        </span>
                        @endif
                      </div>
                      <a href="{{ route('books.show', $book->slug) }}" 
                         class="adidas-btn ripple bg-adidas-black text-adidas-white px-6 py-3 rounded-xl text-sm font-black uppercase tracking-wider hover:bg-adidas-blue transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Add to Cart
                      </a>
                    </div>
                  </div>
                  
                  <!-- Bottom accent line -->
                  <div class="h-1 bg-gradient-to-r from-adidas-black via-adidas-blue to-adidas-black transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                </div>
                @endforeach
              </div>

              <!-- Enhanced Adidas-Style Pagination -->
              <nav class="mt-16 flex justify-center">
                <div class="bg-adidas-white rounded-2xl shadow-lg p-6">
                  <div class="flex items-center space-x-3">
                    <!-- Prev Button -->
                    @if ($books->onFirstPage())
                      <span class="px-6 py-3 text-adidas-gray cursor-not-allowed bg-adidas-light-gray rounded-xl">
                        <svg class="w-5 h-5">
                          <use xlink:href="#alt-arrow-left-outline"></use>
                        </svg>
                      </span>
                    @else
                      <a href="{{ $books->previousPageUrl() }}" 
                         class="adidas-btn ripple px-6 py-3 text-adidas-black hover:bg-adidas-black hover:text-adidas-white rounded-xl transition-all duration-300 font-semibold shadow-sm hover:shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5">
                          <use xlink:href="#alt-arrow-left-outline"></use>
                        </svg>
                      </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                      @if ($page == $books->currentPage())
                        <span class="px-6 py-3 bg-gradient-to-r from-adidas-black to-adidas-dark-gray text-adidas-white rounded-xl font-black shadow-lg">
                          {{ $page }}
                        </span>
                      @else
                        <a href="{{ $url }}" 
                           class="adidas-btn ripple px-6 py-3 text-adidas-black hover:bg-adidas-black hover:text-adidas-white rounded-xl transition-all duration-300 font-semibold shadow-sm hover:shadow-lg transform hover:scale-105">
                          {{ $page }}
                        </a>
                      @endif
                    @endforeach

                    <!-- Next Button -->
                    @if ($books->hasMorePages())
                      <a href="{{ $books->nextPageUrl() }}" 
                         class="adidas-btn ripple px-6 py-3 text-adidas-black hover:bg-adidas-black hover:text-adidas-white rounded-xl transition-all duration-300 font-semibold shadow-sm hover:shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5">
                          <use xlink:href="#alt-arrow-right-outline"></use>
                        </svg>
                      </a>
                    @else
                      <span class="px-6 py-3 text-adidas-gray cursor-not-allowed bg-adidas-light-gray rounded-xl">
                        <svg class="w-5 h-5">
                          <use xlink:href="#alt-arrow-right-outline"></use>
                        </svg>
                      </span>
                    @endif
                  </div>
                  
                  <!-- Page info -->
                  <div class="text-center mt-4 text-sm text-adidas-gray">
                    Page {{ $books->currentPage() }} of {{ $books->lastPage() }}
                  </div>
                </div>
              </nav>
            </main>

          <!-- Adidas-Style Sidebar Filters -->
          <aside class="w-full lg:w-80 lg:order-1">
            <div class="filter-section bg-adidas-white rounded-lg shadow-sm p-6 sticky top-8">
              
              <!-- Search Section -->
              <div class="mb-8">
                <h3 class="text-lg font-bold text-adidas-black mb-4 uppercase tracking-wide border-b-2 border-adidas-light-gray pb-2">
                  Search Books
                </h3>
                <form method="GET" action="{{ url()->current() }}" role="search" class="relative">
                  <input 
                    name="search" 
                    type="search" 
                    placeholder="Search by title or author..." 
                    aria-label="Search"
                    value="{{ request('search') ?? '' }}"
                    class="w-full px-4 py-3 pr-12 border-2 border-adidas-light-gray rounded-lg focus:border-adidas-black focus:outline-none transition-colors duration-200">
                  <button type="submit" 
                          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-adidas-gray hover:text-adidas-black transition-colors duration-200">
                    <svg class="w-5 h-5">
                      <use xlink:href="#search"></use>
                    </svg>
                  </button>
                </form>
              </div>

              <!-- Categories Filter -->
              <div class="mb-8">
                <h3 class="text-lg font-bold text-adidas-black mb-4 uppercase tracking-wide border-b-2 border-adidas-light-gray pb-2">
                  Categories
                </h3>
                <select
                  aria-label="Select category"
                  onchange="location = this.value;"
                  class="w-full px-4 py-3 border-2 border-adidas-light-gray rounded-lg focus:border-adidas-black focus:outline-none transition-colors duration-200 bg-adidas-white">
                  <option value="{{ url('/books') . '?' . http_build_query(request()->except('category')) }}"
                    {{ request()->segment(2) === null ? 'selected' : '' }}>
                    All Categories
                  </option>
                  @foreach($categories as $cat)
                    <option value="{{ url('/books/' . $cat->slug) . '?' . http_build_query(request()->except('authors', 'brands')) }}"
                      {{ request()->segment(2) == $cat->slug ? 'selected' : '' }}>
                      {{ $cat->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Authors Filter -->
              <div class="mb-8">
                <h3 class="text-lg font-bold text-adidas-black mb-4 uppercase tracking-wide border-b-2 border-adidas-light-gray pb-2">
                  Authors
                </h3>
                <select
                  aria-label="Select author"
                  onchange="location = this.value;"
                  class="w-full px-4 py-3 border-2 border-adidas-light-gray rounded-lg focus:border-adidas-black focus:outline-none transition-colors duration-200 bg-adidas-white">
                  <option value="{{ url()->current() . '?' . http_build_query(request()->except('authors')) }}">
                    All Authors
                  </option>
                  @foreach ($authors as $author)
                    <option value="{{ url()->current() }}?authors={{ $author->id }}"
                      {{ in_array($author->id, (array) request('authors', [])) ? 'selected' : '' }}>
                      {{ $author->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Publishers Filter -->
              <div class="mb-8">
                <h3 class="text-lg font-bold text-adidas-black mb-4 uppercase tracking-wide border-b-2 border-adidas-light-gray pb-2">
                  Publishers
                </h3>
                <select
                  aria-label="Select publisher"
                  onchange="location = this.value;"
                  class="w-full px-4 py-3 border-2 border-adidas-light-gray rounded-lg focus:border-adidas-black focus:outline-none transition-colors duration-200 bg-adidas-white">
                  <option value="{{ url()->current() . '?' . http_build_query(request()->except('brands')) }}">
                    All Publishers
                  </option>
                  @foreach ($brands as $brand)
                    <option value="{{ url()->current() }}?brands={{ $brand->id }}"
                      {{ in_array($brand->id, (array) request('brands', [])) ? 'selected' : '' }}>
                      {{ $brand->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Price Filter -->
              <div class="mb-8">
                <h3 class="text-lg font-bold text-adidas-black mb-4 uppercase tracking-wide border-b-2 border-adidas-light-gray pb-2">
                  Price Range
                </h3>
                <form method="GET" action="{{ url()->current() }}">
                  <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <input type="radio" name="price_range" value="1-10" 
                             {{ request('price_range') == '1-10' ? 'checked' : '' }}
                             class="w-4 h-4 text-adidas-black focus:ring-adidas-black">
                      <span class="text-adidas-gray group-hover:text-adidas-black transition-colors duration-200">0 - 10,000 ₫</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <input type="radio" name="price_range" value="10-50" 
                             {{ request('price_range') == '10-50' ? 'checked' : '' }}
                             class="w-4 h-4 text-adidas-black focus:ring-adidas-black">
                      <span class="text-adidas-gray group-hover:text-adidas-black transition-colors duration-200">10,000 - 50,000 ₫</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <input type="radio" name="price_range" value="50-100" 
                             {{ request('price_range') == '50-100' ? 'checked' : '' }}
                             class="w-4 h-4 text-adidas-black focus:ring-adidas-black">
                      <span class="text-adidas-gray group-hover:text-adidas-black transition-colors duration-200">50,000 - 100,000 ₫</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                      <input type="radio" name="price_range" value="100+" 
                             {{ request('price_range') == '100+' ? 'checked' : '' }}
                             class="w-4 h-4 text-adidas-black focus:ring-adidas-black">
                      <span class="text-adidas-gray group-hover:text-adidas-black transition-colors duration-200">Over 100,000 ₫</span>
                    </label>
                  </div>
                  <button type="submit" 
                          class="adidas-btn w-full mt-4 bg-adidas-black text-adidas-white py-3 rounded-lg font-semibold uppercase tracking-wide hover:bg-adidas-blue transition-colors duration-200">
                    Apply Filter
                  </button>
                </form>
              </div>

              <!-- Reset Filter -->
              <div class="pt-6 border-t border-adidas-light-gray">
                <a href="{{ url('/books') }}" 
                   class="adidas-btn w-full block text-center bg-adidas-light-gray text-adidas-black py-3 rounded-lg font-semibold uppercase tracking-wide hover:bg-adidas-gray hover:text-adidas-white transition-colors duration-200">
                  Reset All Filters
                </a>
              </div>

            </div>
          </aside>

        </div>
      </div>
    </div>

    <!-- Enhanced JavaScript for Premium Interactions -->
    <script>
      // Mobile menu toggle
      document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
      });
      
      // Enhanced Wishlist functionality with visual feedback
      document.querySelectorAll('.btn-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          if (this.disabled) return;

          const button = this;
          const bookId = button.dataset.bookId;
          const originalHTML = button.innerHTML;

          // Visual feedback
          button.disabled = true;
          button.innerHTML = '<svg class="w-6 h-6 animate-spin"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path fill="currentColor" d="m4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

          fetch('/wishlist/add', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ book_id: bookId })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              // Success animation
              button.classList.add('bg-adidas-red', 'text-adidas-white');
              button.innerHTML = '<svg class="w-6 h-6"><use xlink:href="#heart"></use></svg>';
              
              // Show success toast
              showToast('Added to wishlist successfully!', 'success');
              
              // Animate button
              button.style.transform = 'scale(1.2)';
              setTimeout(() => {
                button.style.transform = 'scale(1)';
              }, 200);
              
            } else {
              button.innerHTML = originalHTML;
              button.disabled = false;
              showToast(data.message || 'Error adding to wishlist!', 'error');
            }
          })
          .catch(() => {
            button.innerHTML = originalHTML;
            button.disabled = false;
            showToast('Connection error!', 'error');
          });
        });
      });

      // Enhanced hover effects for book cards
      document.querySelectorAll('.book-card').forEach(card => {
        let tiltTimeout;
        
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-8px) rotateX(5deg)';
          this.style.boxShadow = '0 25px 50px rgba(0,0,0,0.25)';
          this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0) rotateX(0deg)';
          this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
          this.style.zIndex = '1';
        });
        
        // Add subtle tilt effect based on mouse position
        card.addEventListener('mousemove', function(e) {
          clearTimeout(tiltTimeout);
          tiltTimeout = setTimeout(() => {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            this.style.transform = `translateY(-8px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
          }, 10);
        });
      });

      // Parallax effect for hero section
      window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.adidas-stripes');
        if (heroSection) {
          heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
      });

      // Custom toast notification system
      function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full ${
          type === 'success' ? 'bg-adidas-green text-white' : 
          type === 'error' ? 'bg-adidas-red text-white' : 
          'bg-adidas-black text-white'
        }`;
        toast.innerHTML = `
          <div class="flex items-center space-x-3">
            <svg class="w-5 h-5">
              ${type === 'success' ? '<path fill="currentColor" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : 
                type === 'error' ? '<path fill="currentColor" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : 
                '<path fill="currentColor" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
            </svg>
            <span class="font-semibold">${message}</span>
          </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
          toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Animate out
        setTimeout(() => {
          toast.style.transform = 'translateX(100%)';
          setTimeout(() => {
            document.body.removeChild(toast);
          }, 300);
        }, 3000);
      }

      // Smooth scroll for internal links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });

      // Add loading state to buttons
      document.querySelectorAll('.adidas-btn').forEach(btn => {
        if (btn.tagName === 'A' && btn.href && !btn.href.includes('#')) {
          btn.addEventListener('click', function() {
            if (!this.disabled) {
              this.classList.add('adidas-loading');
            }
          });
        }
      });

      // Intersection Observer for animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      // Observe book cards for scroll animations
      document.querySelectorAll('.book-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        card.style.transitionDelay = `${index * 100}ms`;
        observer.observe(card);
      });

      // Advanced search functionality
      const searchInput = document.querySelector('input[name="search"]');
      if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
          clearTimeout(searchTimeout);
          const query = this.value.trim();
          
          if (query.length > 2) {
            searchTimeout = setTimeout(() => {
              // Add visual feedback for search
              this.style.borderColor = '#1e3a8a';
              this.style.boxShadow = '0 0 0 3px rgba(30, 58, 138, 0.1)';
            }, 300);
          } else {
            this.style.borderColor = '';
            this.style.boxShadow = '';
          }
        });
      }
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  </body>
</html>
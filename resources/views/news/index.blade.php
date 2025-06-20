@extends('layouts.app')
@section('title', 'Tin tức & Sự kiện')

@push('styles')
<style>
.news-page .adidas-font {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.news-page .adidas-bg {
    background: linear-gradient(135deg, #000000 0%, #2c2c2c 100%);
}

.news-page .adidas-stripes {
    background-image: 
        linear-gradient(45deg, transparent 25%, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.1) 50%, transparent 50%, transparent 75%, rgba(255,255,255,0.1) 75%);
    background-size: 4px 4px;
}

.news-page .adidas-card {
    background: #ffffff;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.news-page .adidas-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
}

.news-page .adidas-btn {
    background: #000000;
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-decoration: none;
}

.news-page .adidas-btn:hover {
    background: #333333;
    transform: scale(1.05);
    color: #ffffff;
}

.news-page .adidas-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.news-page .adidas-btn:hover::before {
    left: 100%;
}

.news-page .sport-badge {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.news-page .hero-overlay {
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
}
</style>
@endpush

@section('content')
<div class="news-page">

<!-- Hero Section - Adidas Style -->
<div class="relative adidas-bg h-[600px] mb-16 overflow-hidden">
    @if($featuredNews = $news->firstWhere('is_featured', true))
        <div class="absolute inset-0">
            <img src="{{ $featuredNews->thumbnail ?? '/images/news-default.jpg' }}" 
                 alt="{{ $featuredNews->title }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 hero-overlay"></div>
            <div class="absolute inset-0 adidas-stripes opacity-20"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
            <div class="max-w-3xl text-white z-10">
                <div class="sport-badge mb-6 inline-block">Featured Story</div>
                <h1 class="text-6xl font-black mb-6 adidas-font leading-tight">{{ $featuredNews->title }}</h1>
                <p class="text-xl text-gray-200 mb-8 leading-relaxed">{{ $featuredNews->summary }}</p>
                <a href="{{ route('news.show', $featuredNews->id) }}" 
                   class="adidas-btn inline-block text-center font-bold">
                    Read Full Story
                </a>
            </div>
        </div>
        
        <!-- Three stripes decoration -->
        <div class="absolute right-8 top-1/2 transform -translate-y-1/2 opacity-30">
            <div class="w-2 h-16 bg-white mb-2 transform rotate-12"></div>
            <div class="w-2 h-20 bg-white mb-2 transform rotate-12"></div>
            <div class="w-2 h-16 bg-white transform rotate-12"></div>
        </div>
    @endif
</div>

<!-- Main Content Section -->
<div class="max-w-7xl mx-auto px-4 mb-16">
    <!-- Section Header -->
    <div class="text-center mb-16">
        <h2 class="text-5xl font-black text-black adidas-font mb-4">LATEST NEWS</h2>
        <div class="w-24 h-1 bg-black mx-auto mb-6"></div>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Stay updated with the latest stories, events, and announcements from our community</p>
    </div>

    <div class="flex flex-col xl:flex-row gap-12">
        <!-- News Grid -->
        <div class="xl:w-2/3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($news->where('is_featured', false) as $item)
                <article class="adidas-card rounded-none overflow-hidden group">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ $item->thumbnail ?? '/images/news-default.jpg' }}"
                             alt="{{ $item->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="sport-badge">{{ $item->category ?? 'News' }}</span>
                        </div>
                        
                        <!-- Date Badge -->
                        <div class="absolute top-4 right-4 bg-white text-black px-3 py-1 font-bold text-sm">
                            {{ $item->created_at->format('d M') }}
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-black text-black adidas-font mb-3 line-clamp-2 group-hover:text-gray-700 transition-colors">
                            <a href="{{ route('news.show', $item->id) }}">{{ $item->title }}</a>
                        </h3>
                        <p class="text-gray-600 line-clamp-3 mb-6 leading-relaxed">{{ $item->summary }}</p>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 font-medium">{{ $item->created_at->format('d M Y') }}</span>
                            <a href="{{ route('news.show', $item->id) }}" 
                               class="text-black font-bold uppercase text-sm tracking-wide hover:text-gray-600 transition-colors group">
                                Read More
                                <span class="inline-block ml-2 transform group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Adidas Style Pagination -->
            <div class="mt-16 flex justify-center">
                <nav class="bg-black text-white p-6 inline-block">
                    <ul class="flex items-center space-x-1">
                        <!-- Previous Button -->
                        @if ($news->onFirstPage())
                            <li>
                                <span class="px-4 py-2 text-gray-500 font-bold uppercase tracking-wider">Prev</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $news->previousPageUrl() }}" 
                                   class="px-4 py-2 text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-all duration-300">
                                    Prev
                                </a>
                            </li>
                        @endif

                        <!-- Page Numbers -->
                        @foreach ($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                            @if ($page == $news->currentPage())
                                <li>
                                    <span class="px-4 py-2 bg-white text-black font-black">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" 
                                       class="px-4 py-2 text-white font-bold hover:bg-white hover:text-black transition-all duration-300">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        <!-- Next Button -->
                        @if ($news->hasMorePages())
                            <li>
                                <a href="{{ $news->nextPageUrl() }}" 
                                   class="px-4 py-2 text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-all duration-300">
                                    Next
                                </a>
                            </li>
                        @else
                            <li>
                                <span class="px-4 py-2 text-gray-500 font-bold uppercase tracking-wider">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Sidebar - Adidas Style -->
        <div class="xl:w-1/3">
            <!-- Featured News Module -->
            <div class="bg-black text-white p-8 mb-8">
                <div class="flex items-center mb-6">
                    <h3 class="text-2xl font-black adidas-font">FEATURED</h3>
                    <div class="ml-4 flex space-x-1">
                        <div class="w-1 h-6 bg-white"></div>
                        <div class="w-1 h-8 bg-white"></div>
                        <div class="w-1 h-6 bg-white"></div>
                    </div>
                </div>
                
                @foreach($news->where('is_featured', true)->take(3) as $featured)
                <article class="mb-8 last:mb-0 group">
                    <a href="{{ route('news.show', $featured->id) }}" class="block">
                        <div class="relative overflow-hidden mb-4 h-48">
                            <img src="{{ $featured->thumbnail ?? '/images/news-default.jpg' }}"
                                 alt="{{ $featured->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-white text-black px-2 py-1 text-xs font-bold">
                                    {{ $featured->created_at->format('d M') }}
                                </span>
                            </div>
                        </div>
                        <h4 class="font-black text-lg leading-tight group-hover:text-gray-300 transition-colors line-clamp-2">
                            {{ $featured->title }}
                        </h4>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Latest Updates Module -->
            <div class="bg-white border-l-4 border-black p-8">
                <h3 class="text-2xl font-black text-black adidas-font mb-6">LATEST UPDATES</h3>
                
                @foreach($news->sortByDesc('created_at')->take(5) as $latest)
                <article class="flex items-start space-x-4 mb-6 last:mb-0 group">
                    <div class="flex-shrink-0 w-20 h-20 bg-gray-100 overflow-hidden">
                        <img src="{{ $latest->thumbnail ?? '/images/news-default.jpg' }}"
                             alt="{{ $latest->title }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('news.show', $latest->id) }}" 
                           class="font-bold text-black hover:text-gray-600 transition-colors line-clamp-2 leading-tight">
                            {{ $latest->title }}
                        </a>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="text-xs font-bold text-gray-500">{{ $latest->created_at->format('d M Y') }}</span>
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-500 uppercase">{{ $latest->category ?? 'News' }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
                
                <!-- View All Button -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="#" class="w-full adidas-btn block text-center">
                        View All Updates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

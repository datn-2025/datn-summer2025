@extends('layouts.app')
@section('title', 'Tin tức & Sự kiện')
@section('content')

<!-- Banner Section -->
<div class="relative bg-gray-900 h-[400px] mb-16">
    @if($featuredNews = $news->firstWhere('is_featured', true))
        <div class="absolute inset-0">
            <img src="{{ $featuredNews->thumbnail ?? '/images/news-default.jpg' }}" 
                 alt="{{ $featuredNews->title }}" 
                 class="w-full h-full object-cover opacity-50">
        </div>
        <div class="relative max-w-screen-xl mx-auto px-4 h-full flex items-center">
            <div class="max-w-2xl text-white">
                <h1 class="text-5xl font-bold mb-4">{{ $featuredNews->title }}</h1>
                <p class="text-lg text-gray-300 mb-6">{{ $featuredNews->summary }}</p>
                <a href="{{ route('news.show', $featuredNews->id) }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out">
                    Read More
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    @endif
</div>

<div class="max-w-screen-xl mx-auto px-4 mb-16">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="lg:w-2/3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-3xl font-bold text-gray-900">Latest News</h2>
                    <p class="text-gray-600 mt-2">Discover our latest updates and stories</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($news->where('is_featured', false) as $item)
                <div class="group bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    <a href="{{ route('news.show', $item->id) }}" class="block relative overflow-hidden aspect-w-16 aspect-h-9">
                        <img src="{{ $item->thumbnail ?? '/images/news-default.jpg' }}"
                             alt="{{ $item->title }}"
                             class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">
                    </a>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-3">
                            <span class="text-sm text-blue-600">{{ $item->category }}</span>
                            <span class="text-sm text-gray-500">{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                        <a href="{{ route('news.show', $item->id) }}"
                           class="block text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200 line-clamp-2 mb-3">
                            {{ $item->title }}
                        </a>
                        <p class="text-gray-600 line-clamp-3 mb-4">{{ $item->summary }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">5 min read</span>
                            <span class="text-blue-600 group-hover:translate-x-2 transition-transform duration-200">→</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            {{-- Phân trang custom --}}
<nav class="pagination-container" style="display:flex; justify-content:center; padding:1rem 0; margin-top:20px;">
  <ul class="pagination" style="display:flex !important; justify-content:center; list-style:none; padding-left:0; border-radius:0.375rem; margin:0 auto;">
    {{-- Nút Prev --}}
    @if ($news->onFirstPage())
      <li class="page-item disabled" style="margin:0 4px;">
        <span class="page-link" style="color:#ccc; pointer-events:none; background:#f8f9fa; border:1px solid #ddd; padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center;">Prev</span>
      </li>
    @else
      <li class="page-item" style="margin:0 4px;">
        <a href="{{ $news->previousPageUrl() }}" class="page-link"
           style="color:#4a4a4a; border:1px solid #ddd; padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center; text-decoration:none;"
           onmouseover="this.style.backgroundColor='#0d6efd'; this.style.color='#fff'; this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0 8px rgba(13,110,253,0.5)';"
           onmouseout="this.style.backgroundColor=''; this.style.color='#4a4a4a'; this.style.borderColor='#ddd'; this.style.boxShadow='none';">
          Prev
        </a>
      </li>
    @endif

    {{-- Các số trang --}}
    @foreach ($news->getUrlRange(1, $news->lastPage()) as $page => $url)
      @if ($page == $news->currentPage())
        <li class="page-item active" aria-current="page" style="margin:0 4px;">
          <span class="page-link"
                style="background:#0d6efd; border:1px solid #0d6efd; color:#fff; font-weight:600; box-shadow:0 0 8px rgba(13,110,253,0.7); padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center;">
            {{ $page }}
          </span>
        </li>
      @else
        <li class="page-item" style="margin:0 4px;">
          <a href="{{ $url }}" class="page-link"
             style="color:#4a4a4a; border:1px solid #ddd; padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center; text-decoration:none;"
             onmouseover="this.style.backgroundColor='#0d6efd'; this.style.color='#fff'; this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0 8px rgba(13,110,253,0.5)';"
             onmouseout="this.style.backgroundColor=''; this.style.color='#4a4a4a'; this.style.borderColor='#ddd'; this.style.boxShadow='none';">
            {{ $page }}
          </a>
        </li>
      @endif
    @endforeach

    {{-- Nút Next --}}
    @if ($news->hasMorePages())
      <li class="page-item" style="margin:0 4px;">
        <a href="{{ $news->nextPageUrl() }}" class="page-link"
           style="color:#4a4a4a; border:1px solid #ddd; padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center; text-decoration:none;"
           onmouseover="this.style.backgroundColor='#0d6efd'; this.style.color='#fff'; this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0 8px rgba(13,110,253,0.5)';"
           onmouseout="this.style.backgroundColor=''; this.style.color='#4a4a4a'; this.style.borderColor='#ddd'; this.style.boxShadow='none';">
          Next
        </a>
      </li>
    @else
      <li class="page-item disabled" style="margin:0 4px;">
        <span class="page-link"
              style="color:#ccc; pointer-events:none; background:#f8f9fa; border:1px solid #ddd; padding:8px 14px; font-size:1rem; border-radius:0.375rem; min-width:42px; text-align:center;">
          Next
        </span>
      </li>
    @endif
  </ul>
</nav>
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3">
            <!-- Featured News Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Featured News</h3>
                @foreach($news->where('is_featured', true)->take(3) as $featured)
                <div class="mb-6 last:mb-0">
                    <a href="{{ route('news.show', $featured->id) }}" class="group block">
                        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden mb-3">
                            <img src="{{ $featured->thumbnail ?? '/images/news-default.jpg' }}"
                                 alt="{{ $featured->title }}"
                                 class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">
                        </div>
                        <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                            {{ $featured->title }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-2">{{ $featured->created_at->format('d M Y') }}</p>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Latest News Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Latest Updates</h3>
                @foreach($news->sortByDesc('created_at')->take(5) as $latest)
                <div class="flex items-center space-x-4 mb-6 last:mb-0">
                    <div class="flex-shrink-0 w-20 h-20">
                        <img src="{{ $latest->thumbnail ?? '/images/news-default.jpg' }}"
                             alt="{{ $latest->title }}"
                             class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div>
                        <a href="{{ route('news.show', $latest->id) }}" 
                           class="font-semibold text-gray-900 hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                            {{ $latest->title }}
                        </a>
                        <p class="text-sm text-gray-500 mt-1">{{ $latest->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

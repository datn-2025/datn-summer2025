@extends('layouts.app')

@section('title', 'Tìm kiếm sách')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Search Results -->
    @if($query)
        <div class="bg-white rounded-lg shadow-md p-6">            <!-- Results Header -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Kết quả tìm kiếm cho: <span class="text-blue-600">"{{ $query }}"</span>
                </h2>
                <p class="text-gray-600 mt-1">
                    Tìm thấy {{ $totalResults }} kết quả
                    @if($searchType != 'all')
                        trong mục 
                        @switch($searchType)
                            @case('title') Tên sách @break
                            @case('author') Tác giả @break
                            @case('publisher') Nhà xuất bản @break
                        @endswitch
                    @endif
                </p>
            </div>            <!-- Search Type Filter Buttons -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'title']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ $searchType == 'title' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">
                        Tên sách
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'author']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ $searchType == 'author' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">
                        Tác giả
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'publisher']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ $searchType == 'publisher' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700' }}">
                        Nhà xuất bản
                    </a>
                </div>
            </div>

            @if($books->count() > 0)
                <!-- Books Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($books as $book)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition duration-200">
                            <!-- Book Cover -->
                            <div class="aspect-[3/4] overflow-hidden rounded-t-lg">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Book Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h3>
                                
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Tác giả:</span> {{ $book->author->name ?? 'N/A' }}
                                </p>
                                
                                <p class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">NXB:</span> {{ $book->brand->name ?? 'N/A' }}
                                </p>
                                
                                <!-- Price and Formats -->
                                @if($book->formats->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($book->formats as $format)
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600">{{ $format->type }}</span>
                                                <span class="font-semibold text-blue-600">
                                                    {{ number_format($format->price, 0, ',', '.') }}đ
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Action Button -->
                                <button class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-sm">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $books->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy kết quả</h3>
                    <p class="text-gray-600 mb-4">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc tìm kiếm.</p>
                    <button onclick="clearSearch()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                        Xóa bộ lọc
                    </button>
                </div>
            @endif
        </div>    @else        <!-- Search Guide -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tìm kiếm sách yêu thích</h2>
            <p class="text-gray-600 mb-6">Nhập từ khóa để tìm kiếm sách theo tên, tác giả hoặc nhà xuất bản</p>
            
            <!-- Popular searches -->
            <div class="max-w-md mx-auto">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Tìm kiếm phổ biến:</h3>
                <div class="flex flex-wrap gap-2 justify-center">
                    <a href="{{ route('search.index', ['q' => 'Nguyễn Nhật Ánh', 'type' => 'author']) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
                        Nguyễn Nhật Ánh
                    </a>
                    <a href="{{ route('search.index', ['q' => 'NXB Trẻ', 'type' => 'publisher']) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
                        NXB Trẻ
                    </a>
                    <a href="{{ route('search.index', ['q' => 'tiểu thuyết']) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
                        Tiểu thuyết
                    </a>
                    <a href="{{ route('search.index', ['q' => 'self-help']) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
                        Sách kỹ năng
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function clearSearch() {
    window.location.href = '{{ route("search.index") }}';
}
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@php
    $searchTypes = [
        'all' => 'Tất cả',
        'title' => 'Tên sách',
        'author' => 'Tác giả', 
        'publisher' => 'Nhà xuất bản'
    ];
@endphp

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Tìm kiếm nâng cao</h2>
        <button onclick="toggleAdvancedSearch()" class="text-blue-600 hover:text-blue-800 text-sm">
            <span id="toggle-text">Mở rộng</span>
            <svg id="toggle-icon" class="w-4 h-4 inline-block ml-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>
    
    <form action="{{ route('search.index') }}" method="GET" id="advanced-search-form">        <!-- Basic Search -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-6 relative">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Nhập từ khóa tìm kiếm..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    id="advanced-search-input"
                    autocomplete="off"
                >
                <!-- Suggestions dropdown -->
                <div id="suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 hidden shadow-lg max-h-60 overflow-y-auto"></div>
            </div>
            
            <div class="md:col-span-3">
                <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @foreach($searchTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('type', 'all') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-3">
                <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tìm kiếm
                </button>
            </div>
        </div>

        <!-- Advanced Filters (Initially Hidden) -->
        <div id="advanced-filters" class="hidden">
            <div class="border-t border-gray-200 pt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">                    <!-- Author Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tác giả cụ thể</label>
                        <select name="author_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">Chọn tác giả</option>
                            @foreach(\App\Models\Author::orderBy('name')->get() as $author)
                                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Publisher Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhà xuất bản</label>
                        <select name="publisher_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">Chọn nhà xuất bản</option>
                            @foreach(\App\Models\Brand::orderBy('name')->get() as $publisher)
                                <option value="{{ $publisher->id }}" {{ request('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                      <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">Chọn danh mục</option>
                            @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Price Range and Publication Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá</label>
                        <div class="flex gap-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}"
                                placeholder="Từ"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >
                            <span class="flex items-center text-gray-500">-</span>
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}"
                                placeholder="Đến"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Năm xuất bản</label>
                        <div class="flex gap-2">
                            <input 
                                type="number" 
                                name="from_year" 
                                value="{{ request('from_year') }}"
                                placeholder="Từ năm"
                                min="1900"
                                max="{{ date('Y') }}"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >
                            <span class="flex items-center text-gray-500">-</span>
                            <input 
                                type="number" 
                                name="to_year" 
                                value="{{ request('to_year') }}"
                                placeholder="Đến năm"
                                min="1900"
                                max="{{ date('Y') }}"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Áp dụng bộ lọc
                    </button>
                    <button type="button" onclick="clearFilters()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                        Xóa bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function toggleAdvancedSearch() {
    const filters = document.getElementById('advanced-filters');
    const toggleText = document.getElementById('toggle-text');
    const toggleIcon = document.getElementById('toggle-icon');
    
    if (filters.classList.contains('hidden')) {
        filters.classList.remove('hidden');
        toggleText.textContent = 'Thu gọn';
        toggleIcon.classList.add('rotate-180');
    } else {
        filters.classList.add('hidden');
        toggleText.textContent = 'Mở rộng';
        toggleIcon.classList.remove('rotate-180');
    }
}

function clearFilters() {
    // Clear all form fields except the main search query
    const form = document.getElementById('advanced-search-form');
    const inputs = form.querySelectorAll('select, input[type="number"]');
    
    inputs.forEach(input => {
        if (input.name !== 'q' && input.name !== 'type') {
            input.value = '';
        }
    });
    
    // Reset search type to 'all'
    const typeSelect = form.querySelector('select[name="type"]');
    if (typeSelect) {
        typeSelect.value = 'all';
    }
}

// Auto-expand if advanced filters are active
document.addEventListener('DOMContentLoaded', function() {
    const hasAdvancedFilters = {{ 
        request('author_id') || request('publisher_id') || request('category_id') || 
        request('min_price') || request('max_price') || request('from_year') || request('to_year') 
        ? 'true' : 'false' 
    }};
    
    if (hasAdvancedFilters) {
        toggleAdvancedSearch();
    }
});
</script>

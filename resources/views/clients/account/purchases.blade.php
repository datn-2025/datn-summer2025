@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h1 class="text-2xl font-bold text-white">Đơn hàng của tôi</h1>
            </div>
            
            <div class="p-8">
                <!-- Navigation Tabs -->
                <div class="bg-slate-100 rounded-xl p-2 mb-8">
                    <div class="flex space-x-1">
                        <a href="{{ route('account.purchase', ['type' => 1]) }}" 
                           class="flex-1 px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-center
                                  {{ request('type', '1') == '1' 
                                     ? 'bg-white text-blue-600 shadow-sm' 
                                     : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Tất cả đơn hàng
                        </a>
                        <a href="{{ route('account.purchase', ['type' => 2]) }}" 
                           class="flex-1 px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-center
                                  {{ request('type') == '2' 
                                     ? 'bg-white text-blue-600 shadow-sm' 
                                     : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Chưa đánh giá
                        </a>
                        <a href="{{ route('account.purchase', ['type' => 3]) }}" 
                           class="flex-1 px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 text-center
                                  {{ request('type') == '3' 
                                     ? 'bg-white text-blue-600 shadow-sm' 
                                     : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Đã đánh giá
                        </a>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-6">
                    @forelse($orders as $order)
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-slate-50 border-b border-slate-200 rounded-t-xl">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Đơn hàng #{{ $order->id }}</h3>
                                    <p class="text-sm text-slate-500">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2 sm:mt-0">
                                    Đã hoàn thành
                                </span>
                            </div>
                            
                            <!-- Order Items -->
                            <div class="p-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->book->image_url ?? 'https://via.placeholder.com/120x160' }}" 
                                                 alt="{{ $item->book->name }}" 
                                                 class="w-24 h-32 object-cover rounded-lg shadow-sm">
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-slate-900 mb-3">{{ $item->book->name }}</h4>
                                            <div class="space-y-1">
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                                </p>
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Giá:</span> 
                                                    <span class="text-red-600 font-semibold">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Review Section -->
                                        <div class="lg:w-96">
                                            @php
                                                $review = $order->reviews()->withTrashed()->where('book_id', $item->book_id)->first();
                                            @endphp
                                            
                                            @if($review && $review->trashed())
                                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                                    <p class="text-center text-red-500">Đánh giá đã bị xóa</p>
                                                </div>   
                                            @elseif($review)
                                                <!-- Display Review -->

                                                <!-- Review hiện tại -->
                                                @php
                                                    $formId = 'review-form-' . $order->id . '-' . $item->book_id;
                                                    $fieldsetId = 'rating-fieldset-' . $order->id . '-' . $item->book_id;
                                                @endphp
                                                <fieldset id="{{ $fieldsetId }}" disabled>
                                                <form id="{{ $formId }}" action="{{ route('account.reviews.update', $review->id) }}" method="POST" class="space-y-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <input type="hidden" name="book_id" value="{{ $item->book_id }}">

                                                    <!-- Updated Date -->
                                                    <div class="mb-2">
                                                        <span class="text-xs text-slate-500">Cập nhật lần cuối: {{ $review->updated_at->format('d/m/Y') }}</span>
                                                    </div>

                                                    <!-- Rating Input -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-slate-700 mb-2">Đánh giá của bạn:</label>
                                                        <div class="flex items-center justify-center space-x-1">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <label for="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}" class="cursor-pointer text-2xl {{ old('rating', $review->rating) >= $i ? 'text-yellow-400' : 'text-slate-300' }} hover:text-yellow-400 transition-colors duration-150">
                                                                    <input type="radio"
                                                                        id="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}"
                                                                        name="rating"
                                                                        value="{{ $i }}"
                                                                        class="sr-only"
                                                                        {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                                                                    ★
                                                                </label>
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    <!-- Comment Input -->
                                                    <div>
                                                        <textarea name="comment"
                                                                rows="3"
                                                                placeholder="Nhận xét về sản phẩm..."
                                                                required
                                                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm resize-none">{{ old('comment', $review->comment) }}</textarea>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <!-- <button type="submit"
                                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                        </svg>
                                                        Cập nhật đánh giá
                                                    </button> -->
                                                </form>
                                                </fieldset>



                                                <!-- Review hiện tại -->
                                                <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <div class="flex items-center space-x-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" 
                                                                     fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-xs text-slate-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <p class="review-comment text-sm text-slate-700 leading-relaxed">{{ $review->comment }}</p>
                                                </div> -->
                                                
                                                <!-- <div class="review-item">
                                                    <div class="review-content">
                                                        <div class="review-rating">{{ $review->rating }}</div>
                                                        <div class="review-comment">{{ $review->comment }}</div> -->
                                                        <div class="review-action">
                                                        @if($review->user_id === auth()->id())
                                                            @php
                                                                $canEdit = $review->created_at->addHours(24)->isFuture();
                                                                $canDelete = $review->created_at->addDays(7)->isFuture();
                                                            @endphp
                                                                
                                                            @if($canEdit)
                                                                <!-- <button class="btn btn-sm btn-outline-primary btn-edit-review" data-id="{{ $review->id }}">Sửa</button> -->
                                                                <button class="btn btn-sm btn-outline-primary btn-edit-review"
                                                                    data-id="{{ $review->id }}"
                                                                    data-form-id="{{ $formId }}"
                                                                    data-fieldset-id="{{ $fieldsetId }}">
                                                                    Sửa
                                                                </button>
                                                            @endif
                                                                
                                                            @if($canDelete)
                                                                <!-- <button class="btn btn-sm btn-outline-danger btn-delete-review" data-id="{{ $review->id }}">Xóa</button> -->
                                                                <form action="{{ route('account.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                        </div>
                                                    <!-- </div>
                                                </div> -->
                                                 
                                            @else
                                                <!-- Review Form -->
                                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                                    <form action="{{ route('account.review.store') }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <input type="hidden" name="book_id" value="{{ $item->book_id }}">
                                                        
                                                        <!-- Rating Input -->
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-2">Đánh giá của bạn:</label>
                                                            <div class="flex items-center justify-center space-x-1">
                                                                @for($i = 5; $i >= 1; $i--)
                                                                    <input type="radio" 
                                                                           id="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}" 
                                                                           name="rating" 
                                                                           value="{{ $i }}" 
                                                                           class="sr-only"
                                                                           {{ old('rating') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }}>
                                                                    <label for="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}" 
                                                                           class="cursor-pointer text-2xl text-slate-300 hover:text-yellow-400 transition-colors duration-150"
                                                                           title="{{ $i }} sao">★</label>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Comment Input -->
                                                        <div>
                                                            <textarea name="comment" 
                                                                      rows="3"
                                                                      placeholder="Nhận xét về sản phẩm..."
                                                                      required
                                                                      class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm resize-none">{{ old('comment') }}</textarea>
                                                        </div>
                                                        
                                                        <!-- Submit Button -->
                                                        <button type="submit" 
                                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                            </svg>
                                                            Gửi đánh giá
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900 mb-1">Không có đơn hàng nào</h3>
                            <p class="text-slate-500">Bạn chưa có đơn hàng nào để hiển thị.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for Star Rating -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating clicks
    const starContainers = document.querySelectorAll('.space-x-1');
    
    starContainers.forEach(container => {
        const stars = container.querySelectorAll('label');
        const radioInputs = container.querySelectorAll('input[type="radio"]');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                updateStarDisplay(stars, stars.length - index);
            });
            
            star.addEventListener('click', function() {
                const radioIndex = stars.length - index - 1;
                if (radioInputs[radioIndex]) {
                    radioInputs[radioIndex].checked = true;
                }
                updateStarDisplay(stars, stars.length - index, true);
            });
        });
        
        container.addEventListener('mouseleave', function() {
            const checkedRadio = container.querySelector('input[type="radio"]:checked');
            if (checkedRadio) {
                const checkedValue = parseInt(checkedRadio.value);
                updateStarDisplay(stars, checkedValue, true);
            } else {
                updateStarDisplay(stars, 0, true);
            }
        });
    });
    
    function updateStarDisplay(stars, rating, isPermanent = false) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-slate-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-slate-300');
            }
        });
    }

    // document.querySelectorAll('.btn-edit-review').forEach(button => {
    //     button.addEventListener('click', function() {
    //         const reviewId = this.dataset.id;
    //         const reviewItem = document.getElementById('review-item');
    //         const reviewAction = document.querySelector('.review-action');
    //         reviewAction.style.display = 'none';
    //         document.getElementById('ratingFieldset').disabled = false;

    //         const btnUpdate = `
    //         <div id="update-review-btn" class="d-flex gap-2">
    //             <button type="submit"
    //                     class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    //                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    //                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
    //                 </svg>
    //                 Cập nhật đánh giá
    //             </button>
    //             <button type="button" class="btn btn-secondary btn-sm btn-cancel-edit">Hủy</button>
    //         </div>
    //         `;

    //         reviewItem.insertAdjacentHTML('beforeend', btnUpdate);
    //         reviewItem.querySelector('.btn-cancel-edit').addEventListener('click', function() {
    //             reviewAction.style.display = 'block';
    //             // reviewItem.querySelector('.btn-edit-review').remove();
    //             document.getElementById('ratingFieldset').disabled = true;
    //             document.getElementById('update-review-btn').remove();
    //             reviewItem.reset();
    //         });
    //     });
    // });

    document.querySelectorAll('.btn-edit-review').forEach(button => {
        button.addEventListener('click', function () {
            const formId = this.dataset.formId;
            const fieldsetId = this.dataset.fieldsetId;

            const reviewForm = document.getElementById(formId);
            const ratingFieldset = document.getElementById(fieldsetId);
            const reviewAction = this.closest('.review-action');

            // Disable lại các fieldset khác nếu muốn (optional)
            document.querySelectorAll('fieldset').forEach(fs => fs.disabled = true);
            document.querySelectorAll('#update-review-btn').forEach(el => el.remove());
            document.querySelectorAll('.review-action').forEach(el => el.style.display = 'block');

            reviewAction.style.display = 'none';
            ratingFieldset.disabled = false;

            const btnUpdate = `
                <div id="update-review-btn" class="d-flex gap-2 mt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Cập nhật đánh giá
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm btn-cancel-edit">Hủy</button>
                </div>
            `;

            reviewForm.insertAdjacentHTML('beforeend', btnUpdate);

            reviewForm.querySelector('.btn-cancel-edit').addEventListener('click', function () {
                ratingFieldset.disabled = true;
                reviewAction.style.display = 'block';
                document.getElementById('update-review-btn')?.remove();
                reviewForm.reset();
            });
        });
    });


    // document.querySelectorAll('.btn-delete-review').forEach(button => {
    //     button.addEventListener('click', function() {
    //         if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
    //             return;
    //         }
            
    //         const reviewId = this.dataset.id;
            
    //         fetch(`/reviews/${reviewId}`, {
    //             method: 'DELETE',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //                 'Content-Type': 'application/json',
    //                 'Accept': 'application/json'
    //             }
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 // Cập nhật giao diện
    //                 this.closest('.review-item').remove();
    //                 alert('Đã xóa đánh giá');
    //             } else {
    //                 alert(data.message || 'Có lỗi xảy ra');
    //             }
    //         });
    //     });
    // });

    
});
</script>

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện click nút sửa
    document.querySelectorAll('.btn-edit-review').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.id;
            const reviewItem = this.closest('.review-item');
            const reviewContent = reviewItem.querySelector('.review-content');
            const reviewRating = reviewItem.querySelector('.review-rating').textContent;
            const reviewComment = reviewItem.querySelector('.review-comment').textContent;

            // Tạo form sửa
            const editForm = `
                <form class="edit-review-form" data-id="${reviewId}">
                    <div class="mb-3">
                        <label class="form-label">Đánh giá</label>
                        <div class="rating-stars">
                            ${[5,4,3,2,1].map(star => `
                                <input type="radio" id="star${star}-edit" name="rating" value="${star}" 
                                    ${parseInt(reviewRating) === star ? 'checked' : ''}>
                                <label for="star${star}-edit" title="${star} sao">&#9733;</label>
                            `).join('')}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhận xét</label>
                        <textarea name="comment" class="form-control" rows="3" required>${reviewComment.trim()}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
                        <button type="button" class="btn btn-secondary btn-sm btn-cancel-edit">Hủy</button>
                    </div>
                </form>
            `;

            // Ẩn nội dung cũ và hiển thị form
            reviewContent.style.display = 'none';
            reviewItem.insertAdjacentHTML('beforeend', editForm);

            // Xử lý sự kiện hủy
            reviewItem.querySelector('.btn-cancel-edit').addEventListener('click', function() {
                reviewContent.style.display = 'block';
                reviewItem.querySelector('.edit-review-form').remove();
            });
        });
    });

    // Xử lý submit form sửa
    document.addEventListener('submit', async function(e) {
        if (e.target.matches('.edit-review-form')) {
            e.preventDefault();
            
            const form = e.target;
            const reviewId = form.dataset.id;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(`/account/reviews/${reviewId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: formData.get('rating'),
                        comment: formData.get('comment')
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Tải lại trang để cập nhật đánh giá
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi cập nhật đánh giá');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối đến máy chủ');
            }
        }
    });

    // Xử lý xóa đánh giá
    document.querySelectorAll('.btn-delete-review').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
                return;
            }

            const reviewId = this.dataset.id;
            
            try {
                const response = await fetch(`/account/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    // Tải lại trang để cập nhật danh sách
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa đánh giá');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối đến máy chủ');
            }
        });
    });
});
</script> -->
<!-- <style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    margin-bottom: 10px;
}
.rating-stars input[type="radio"] {
    display: none;
}
.rating-stars label {
    font-size: 24px;
    color: #ddd;
    cursor: pointer;
}
.rating-stars input[type="radio"]:checked ~ label {
    color: #ffc107;
}
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}
</style> -->
@endsection
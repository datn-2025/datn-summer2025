<div class="card-body">
    <h5 class="card-title mb-3">Books with the Lowest Inventory</h5>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Book</th>
                    <th>In Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="" class="avatar-xs me-2" />
                                <span class="fw-medium">{{ $book->title }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                // Tính số lượng sách vật lý (loại trừ ebook)
                                $physicalStock = $book->formats
                                    ->filter(fn($format) => stripos($format->format_name, 'ebook') === false)
                                    ->sum('stock');
                                
                                // Kiểm tra có ebook hay không
                                $hasEbook = $book->formats->contains(fn($format) => stripos($format->format_name, 'ebook') !== false);
                            @endphp
                            
                            @if($physicalStock > 0)
                                {{ $physicalStock }} cuốn
                            @elseif($hasEbook)
                                <span class="text-info">Ebook có sẵn</span>
                            @else
                                <span class="text-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                    @if($book->status == 'Còn Hàng') bg-success-subtle text-success
                                    @elseif($book->status == 'Sắp Ra Mắt') bg-warning-subtle text-warning
                                    @elseif($book->status == 'Hết Hàng Tồn Kho') bg-danger-subtle text-danger
                                    @else bg-secondary-subtle text-secondary @endif">
                                {{ $book->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted text-center">No data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

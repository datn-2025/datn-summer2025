@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-end">

        {{-- Trang 1 --}}
        <li class="page-item {{ $paginator->currentPage() == 1 ? 'active' : '' }}">
            @if ($paginator->currentPage() == 1)
                <span class="page-link">1</span>
            @else
                <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
            @endif
        </li>

        {{-- Trang 2 --}}
        @if ($paginator->lastPage() >= 2)
            <li class="page-item {{ $paginator->currentPage() == 2 ? 'active' : '' }}">
                @if ($paginator->currentPage() == 2)
                    <span class="page-link">2</span>
                @else
                    <a class="page-link" href="{{ $paginator->url(2) }}">2</a>
                @endif
            </li>
        @endif

        {{-- Dấu ... nếu trang hiện tại > 4 --}}
        @if ($paginator->currentPage() > 4)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        {{-- Các trang giữa (từ trang currentPage -1 đến currentPage +1) --}}
        @php
            $start = max(3, $paginator->currentPage() - 1);
            $end = min($paginator->lastPage() - 1, $paginator->currentPage() + 1);
        @endphp

        @for ($page = $start; $page <= $end; $page++)
            {{-- Bỏ qua trang 1 và 2 vì đã hiển thị --}}
            @if ($page == 1 || $page == 2)
                @continue
            @endif

            <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                @if ($paginator->currentPage() == $page)
                    <span class="page-link">{{ $page }}</span>
                @else
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                @endif
            </li>
        @endfor

        {{-- Dấu ... nếu khoảng cách giữa trang cuối và trang hiện tại lớn --}}
        @if ($paginator->lastPage() - $paginator->currentPage() > 2)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        {{-- Trang cuối --}}
        @if ($paginator->lastPage() > 2)
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'active' : '' }}">
                @if ($paginator->currentPage() == $paginator->lastPage())
                    <span class="page-link">{{ $paginator->lastPage() }}</span>
                @else
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                @endif
            </li>
        @endif

    </ul>
</nav>
@endif

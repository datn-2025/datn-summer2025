{{-- layouts/account/nav.blade.php --}}
<div class="sidebar-nav-outer">
    <div class="sidebar-profile">
        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=fff&color=111' }}" alt="User Avatar" class="sidebar-profile-avatar">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-profile-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
    @php
        $currentRoute = request()->route()->getName();
        $menuItems = [
            [ 'route' => 'account.profile', 'icon' => 'fas fa-user', 'title' => 'Thông tin cá nhân' ],
            [ 'route' => 'account.orders.index', 'icon' => 'fas fa-shopping-bag', 'title' => 'Đơn hàng' ],
            [ 'route' => 'account.purchase', 'icon' => 'fas fa-star', 'title' => 'Đánh giá' ],
            [ 'route' => 'account.addresses', 'icon' => 'fas fa-map-marker-alt', 'title' => 'Địa chỉ' ],
            [ 'route' => 'account.changePassword', 'icon' => 'fas fa-lock', 'title' => 'Đổi mật khẩu' ],
            [ 'route' => 'home', 'icon' => 'fas fa-home', 'title' => 'Trang chủ' ],
        ];
    @endphp
    <nav class="sidebar-nav-list">
        @foreach ($menuItems as $item)
            <a href="{{ route($item['route']) }}" class="sidebar-link{{ $currentRoute === $item['route'] ? ' active' : '' }}">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['title'] }}</span>
            </a>
        @endforeach
    </nav>
    <form method="POST" action="{{ route('logout') }}" style="margin-top: 24px;">
        @csrf
        <button type="submit" class="sidebar-logout-btn">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </button>
    </form>
</div>
<style>
.sidebar-nav-outer {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    height: 100%;
}
.sidebar-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 12px 20px 12px;
    border-bottom: 2px solid #111;
    background: #fafbfc;
    border-radius: 0 !important;
}
.sidebar-profile-avatar {
    width: 92px;
    height: 92px;
    border-radius: 0;
    object-fit: cover;
    border: 5px solid #111;
    margin-bottom: 16px;
    background: #fff;
    box-shadow: 0 6px 24px rgba(0,0,0,0.18);
    display: block;
    transition: box-shadow 0.2s;
}
.sidebar-profile-avatar:hover {
    box-shadow: 0 12px 36px rgba(0,0,0,0.22);
}
.sidebar-profile-info {
    text-align: center;
}
.sidebar-profile-name {
    font-weight: 900;
    color: #111;
    font-size: 1.13rem;
    margin-bottom: 2px;
    letter-spacing: 0.5px;
}
.sidebar-profile-email {
    color: #222;
    font-size: 1.01rem;
    font-weight: 600;
    word-break: break-all;
    opacity: 0.92;
}
.sidebar-nav-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin-top: 18px;
    flex: 1 1 auto;
}
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 24px;
    color: #111;
    text-decoration: none;
    border-radius: 0 !important;
    font-weight: 700;
    font-size: 1.06rem;
    border-left: 4px solid transparent;
    border-bottom: 1.5px solid #eee;
    transition: background 0.18s, color 0.18s, border-color 0.18s, box-shadow 0.18s;
    background: #fff;
    letter-spacing: 0.5px;
    position: relative;
}
.sidebar-link.active, .sidebar-link:hover {
    background: #111;
    color: #fff;
    border-left: 4px solid #111;
    box-shadow: 0 2px 12px 0 rgba(0,0,0,0.10);
}
.sidebar-link.active i, .sidebar-link:hover i {
    color: #fff;
}
.sidebar-link i {
    font-size: 1.13rem;
    min-width: 24px;
    color: #111;
    transition: color 0.18s;
}
.sidebar-logout-btn {
    width: 100%;
    text-align: left;
    color: #ef4444 !important;
    background: none;
    border: none;
    padding: 16px 24px;
    font-weight: 700;
    font-size: 1.06rem;
    display: flex;
    align-items: center;
    gap: 14px;
    border-radius: 0 !important;
    cursor: pointer;
    border-top: 2px solid #111;
    transition: background 0.15s;
    margin-top: auto;
}
.sidebar-logout-btn:hover {
    background: #fbe9e9;
    color: #b91c1c !important;
}
@media (max-width: 1100px) {
    .sidebar-profile {
        padding: 24px 4px 12px 4px;
    }
    .sidebar-profile-avatar {
        width: 72px;
        height: 72px;
        border-width: 4px;
    }
    .sidebar-link, .sidebar-logout-btn {
        padding: 12px 10px;
        font-size: 0.98rem;
    }
}
@media (max-width: 700px) {
    .sidebar-nav-list {
        flex-direction: row;
        gap: 0;
        overflow-x: auto;
        border-bottom: 2px solid #111;
        margin-top: 0;
    }
    .sidebar-link {
        border-left: none;
        border-bottom: 2px solid transparent;
        border-radius: 0 !important;
        padding: 12px 18px;
        font-size: 0.98rem;
        white-space: nowrap;
    }
    .sidebar-link.active, .sidebar-link:hover {
        background: #111;
        color: #fff;
        border-bottom: 2px solid #111;
        border-left: none;
    }
    .sidebar-profile {
        padding: 18px 0 8px 0;
    }
}
</style>

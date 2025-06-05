<nav style="background: linear-gradient(90deg, #fff 60%, #f8fafc 100%); box-shadow: 0 6px 24px rgba(0,0,0,0.10); border-bottom: 2.5px solid #e5e7eb; padding: 26px 0; font-family: 'Segoe UI', Arial, sans-serif; position: sticky; top: 0; z-index: 100; transition: box-shadow 0.3s;">
    <div style="max-width: 1320px; margin: 0 auto; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 34px; min-height: 96px;">
        <!-- Logo -->
        <div style="flex: 0 0 auto; display: flex; align-items: center; gap: 16px;">
            <a href="#" style="display: flex; align-items: center;">
                <img src="{{ asset('storage/images/main-logo.png') }}" alt="Logo" style="height: 56px; width: auto; max-width: 150px; filter: drop-shadow(0 2px 10px rgba(0,0,0,0.13)); transition: transform 0.2s, filter 0.2s; border-radius: 8px; background: #fff; padding: 2px 8px;" onmouseover="this.style.transform='scale(1.08)';this.style.filter='drop-shadow(0 4px 16px #e11d48)'" onmouseout="this.style.transform='scale(1)';this.style.filter='drop-shadow(0 2px 10px rgba(0,0,0,0.13))'">
            </a>
        </div>
        <!-- Menu -->
        <ul style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 44px; font-size: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px; margin: 0; padding: 0; list-style: none;">
            <li><a href="/" style="color: #e11d48; text-decoration: none; transition: color 0.2s, border-bottom 0.2s; border-bottom: 3px solid #e11d48; padding-bottom: 4px; border-radius: 2px; background: linear-gradient(90deg, #fbb6ce 0%, #fff 100%);">Trang chủ</a></li>
            <li><a href="#" style="color: #222; text-decoration: none; transition: color 0.2s, border-bottom 0.2s, background 0.2s; border-bottom: 3px solid transparent; padding-bottom: 4px; border-radius: 2px;" onmouseover="this.style.color='#e11d48';this.style.borderBottom='3px solid #e11d48';this.style.background='#fdf2f8'" onmouseout="this.style.color='#222';this.style.borderBottom='3px solid transparent';this.style.background='none'">Giới thiệu</a></li>
            <li><a href="#" style="color: #222; text-decoration: none; transition: color 0.2s, border-bottom 0.2s, background 0.2s; border-bottom: 3px solid transparent; padding-bottom: 4px; border-radius: 2px;" onmouseover="this.style.color='#e11d48';this.style.borderBottom='3px solid #e11d48';this.style.background='#fdf2f8'" onmouseout="this.style.color='#222';this.style.borderBottom='3px solid transparent';this.style.background='none'">Cửa hàng</a></li>
            <li><a href="#" style="color: #222; text-decoration: none; transition: color 0.2s, border-bottom 0.2s, background 0.2s; border-bottom: 3px solid transparent; padding-bottom: 4px; border-radius: 2px;" onmouseover="this.style.color='#e11d48';this.style.borderBottom='3px solid #e11d48';this.style.background='#fdf2f8'" onmouseout="this.style.color='#222';this.style.borderBottom='3px solid transparent';this.style.background='none'">Tin tức</a></li>
            <li><a href="#" style="color: #222; text-decoration: none; transition: color 0.2s, border-bottom 0.2s, background 0.2s; border-bottom: 3px solid transparent; padding-bottom: 4px; border-radius: 2px;" onmouseover="this.style.color='#e11d48';this.style.borderBottom='3px solid #e11d48';this.style.background='#fdf2f8'" onmouseout="this.style.color='#222';this.style.borderBottom='3px solid transparent';this.style.background='none'">Liên hệ</a></li>
        </ul>
        <!-- Phải: Tìm kiếm + icon + dòng phụ -->
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 16px; min-width: 250px;">
            <!-- Dòng icon + tìm kiếm -->
            <div style="display: flex; align-items: center; gap: 28px; font-size: 22px; color: #222;">
                <!-- Thanh tìm kiếm -->
                <div style="position: relative;">
                    <input type="text" placeholder="Tìm kiếm" style="padding: 10px 44px 10px 18px; font-size: 18px; border-radius: 10px; border: 2.5px solid #e5e7eb; outline: none; width: 200px; transition: border 0.2s, box-shadow 0.2s; box-shadow: 0 2px 12px rgba(0,0,0,0.08); background: #f9fafb;" onfocus="this.style.borderColor='#e11d48';this.style.boxShadow='0 2px 16px #fbb6ce'" onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)'">
                    <i class="fas fa-search" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #888; font-size: 20px;"></i>
                </div>
                <!-- User dropdown -->
                <div style="position: relative; display: inline-block;">
                    <a href="{{ auth()->check() ? '#' : route('login') }}"
                       style="text-decoration: none; color: #222; display: flex; align-items: center; gap: 8px; font-size: 22px; font-weight: 500;"
                       onmouseover="this.nextElementSibling.style.display='block'"
                       onmouseout="this.nextElementSibling.style.display='none'">
                        <i class="far fa-user" style="transition: color 0.2s;"></i>
                        @auth
                            <span style="font-size: 18px;">{{ Auth::user()->name }}</span>
                        @endauth
                    </a>
                    <div style="position: absolute; right: 0; top: 120%; background: #fff; border: 2.5px solid #e5e7eb; border-radius: 12px; display: none; min-width: 210px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); z-index: 10;">
                        @auth
                            <a href="{{ route('account.showUser') }}"
                               style="display: block; padding: 16px 28px; color: #333; text-decoration: none; border-bottom: 1px solid #f3f4f6; white-space: nowrap; font-size: 18px; transition: background 0.2s;" onmouseover="this.style.background='#fdf2f8'" onmouseout="this.style.background='none'">
                                <i class="fas fa-user-circle" style="margin-right: 12px;"></i> Tài khoản
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit"
                                        style="width: 100%; text-align: left; padding: 16px 28px; background: none; border: none; color: #333; cursor: pointer; font-size: 18px; transition: background 0.2s;" onmouseover="this.style.background='#fdf2f8'" onmouseout="this.style.background='none'">
                                    <i class="fas fa-sign-out-alt" style="margin-right: 12px;"></i> Đăng xuất
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               style="display: block; padding: 16px 28px; color: #333; text-decoration: none; border-bottom: 1px solid #f3f4f6; white-space: nowrap; font-size: 18px; transition: background 0.2s;" onmouseover="this.style.background='#fdf2f8'" onmouseout="this.style.background='none'">
                                <i class="fas fa-sign-in-alt" style="margin-right: 12px;"></i> Đăng nhập
                            </a>
                            <a href="{{ route('account.register') }}"
                               style="display: block; padding: 16px 28px; color: #333; text-decoration: none; font-size: 18px; transition: background 0.2s;" onmouseover="this.style.background='#fdf2f8'" onmouseout="this.style.background='none'">
                                <i class="fas fa-user-plus" style="margin-right: 12px;"></i> Đăng ký
                            </a>
                        @endauth
                    </div>
                </div>
                <i class="far fa-heart" style="cursor: pointer; transition: color 0.2s; font-size: 23px;" onmouseover="this.style.color='#e11d48'" onmouseout="this.style.color='#222'"></i>
                <div style="position: relative;">
                    <i class="fas fa-shopping-bag" style="cursor: pointer; transition: color 0.2s; font-size: 23px;" onmouseover="this.style.color='#eab308'" onmouseout="this.style.color='#222'"></i>
                    <span style="position: absolute; top: -10px; right: -14px; font-size: 14px; color: #222; font-weight: bold; border-radius: 50%; padding: 2px 10px; min-width: 22px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.13); background: #eab308;"></span>
                </div>
            </div>
        </div>
    </div>
</nav>
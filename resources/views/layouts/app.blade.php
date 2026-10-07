<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('tieude', 'Flower Shop - Cửa hàng hoa tươi sang trọng')</title>
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Sonner Toast -->
    <script src="https://cdn.jsdelivr.net/npm/sonner@1.5.0/dist/index.umd.min.js"></script>
    <script>
        // Make sonner available globally
        window.sonner = window.Sonner || {
            success: (msg) => alert(msg),
            error: (msg) => alert(msg)
        };
    </script>
    <!-- Marked.js (Markdown parser) -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <!-- Font Awesome (cho icon mạng xã hội) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-white-pure text-gray-dark flex flex-col min-h-screen">
    <!-- Header sang trọng -->
    <header class="sticky top-0 z-50 w-full bg-white-pure/95 backdrop-blur supports-[backdrop-filter]:bg-white-pure/60 shadow-sm">
        <div class="container mx-auto px-4 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                @if($logo = \App\Models\Setting::get('site_logo'))
                <img src="{{ asset($logo) }}" alt="Flower Shop" class="h-12 object-contain">
                @endif
                <div>
                    <span class="font-serif text-2xl font-bold text-gray-dark group-hover:text-rose-main transition-colors">Flower Shop</span>
                    <p class="text-xs text-gray-medium">Tinh hoa hoa tươi</p>
                </div>
            </a>
            
            <!-- Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-sm font-medium {{ request()->is('/') ? 'text-rose-main border-b-2 border-rose-main' : 'text-gray-dark hover:text-rose-main' }} transition-colors pb-1">Trang chủ</a>
                <a href="/san-pham" class="text-sm font-medium {{ request()->is('san-pham*') ? 'text-rose-main border-b-2 border-rose-main' : 'text-gray-dark hover:text-rose-main' }} transition-colors pb-1">Sản phẩm</a>
                <a href="/bai-viet" class="text-sm font-medium {{ request()->is('bai-viet*') ? 'text-rose-main border-b-2 border-rose-main' : 'text-gray-dark hover:text-rose-main' }} transition-colors pb-1">Blog</a>
                @auth
                <a href="/tai-khoan" class="text-sm font-medium {{ request()->is('tai-khoan*') ? 'text-rose-main border-b-2 border-rose-main' : 'text-gray-dark hover:text-rose-main' }} transition-colors pb-1">Tài khoản</a>
                @endauth
            </nav>
            
            <!-- Actions -->
            <div class="flex items-center gap-4">
                <!-- Search -->
                <button onclick="toggleSearch()" class="w-10 h-10 rounded-full bg-rose-pastel text-rose-main hover:bg-rose-light transition-colors flex items-center justify-center shadow-md hover:shadow-lg">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                
                <!-- Cart -->
                <a href="/gio-hang" class="relative w-10 h-10 rounded-full bg-rose-pastel text-rose-main hover:bg-rose-light transition-colors flex items-center justify-center shadow-md hover:shadow-lg">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 bg-rose-main text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" id="cartBadge">{{ count(session('cart') ?? []) }}</span>
                </a>
                
                <!-- User Menu -->
                @if(auth()->check())
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="w-10 h-10 rounded-full overflow-hidden hover:scale-105 transition-transform flex items-center justify-center border-2 border-rose-pastel">
                            @if(auth()->user()->anh_dai_dien)
                                <img src="{{ asset(auth()->user()->anh_dai_dien) }}" alt="{{ auth()->user()->ho_ten }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-rose-pastel to-pink-pastel flex items-center justify-center">
                                    <span class="text-rose-main font-bold">{{ substr(auth()->user()->ho_ten, 0, 1) }}</span>
                                </div>
                            @endif
                        </button>
                        
                        <div id="userMenu" class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-xl border border-rose-pastel hidden z-50 overflow-hidden">
                            <div class="bg-gradient-to-r from-rose-pastel to-pink-pastel p-4">
                                <p class="font-semibold text-gray-dark">{{ auth()->user()->ho_ten }}</p>
                                <p class="text-sm text-gray-medium">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="/tai-khoan" class="w-full text-left px-4 py-3 text-sm text-gray-dark hover:bg-rose-pastel rounded-lg transition-colors flex items-center gap-3">
                                    <i data-lucide="user" class="w-4 h-4 text-rose-main"></i>
                                    Hồ sơ cá nhân
                                </a>
                                <a href="/don-hang" class="w-full text-left px-4 py-3 text-sm text-gray-dark hover:bg-rose-pastel rounded-lg transition-colors flex items-center gap-3">
                                    <i data-lucide="package" class="w-4 h-4 text-rose-main"></i>
                                    Đơn hàng của tôi
                                </a>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nhan_vien')
                                    <a href="/admin" class="w-full text-left px-4 py-3 text-sm text-gray-dark hover:bg-rose-pastel rounded-lg transition-colors flex items-center gap-3">
                                        <i data-lucide="settings" class="w-4 h-4 text-rose-main"></i>
                                        Trang quản trị
                                    </a>
                                @endif
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-rose-dark hover:bg-rose-pastel rounded-lg transition-colors flex items-center gap-3">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="hidden md:inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-full hover:shadow-lg hover:scale-105 transition-all">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Đăng nhập</span>
                    </a>
                @endif
                
                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="lg:hidden w-10 h-10 rounded-full bg-rose-pastel text-rose-main flex items-center justify-center">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="lg:hidden hidden bg-white border-t border-rose-pastel">
            <nav class="container mx-auto px-4 py-4 flex flex-col gap-4">
                <a href="/" class="text-gray-dark hover:text-rose-main transition-colors">Trang chủ</a>
                <a href="/san-pham" class="text-gray-dark hover:text-rose-main transition-colors">Sản phẩm</a>
                <a href="/bai-viet" class="text-gray-dark hover:text-rose-main transition-colors">Blog</a>
                <a href="/ve-chung-toi" class="text-gray-dark hover:text-rose-main transition-colors">Về chúng tôi</a>
                <a href="/lien-he" class="text-gray-dark hover:text-rose-main transition-colors">Liên hệ</a>
                @if(!auth()->check())
                    <a href="/login" class="text-rose-main font-medium">Đăng nhập</a>
                @endif
            </nav>
        </div>
        
        <!-- Search Overlay -->
        <div id="searchOverlay" class="fixed inset-0 bg-black/50 z-50 hidden flex items-start justify-center pt-20">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 p-6">
                <form action="/tim-kiem" method="GET">
                    <div class="flex items-center gap-4">
                        <i data-lucide="search" class="w-5 h-5 text-rose-main"></i>
                        <input type="text" 
                               name="tu_khoa"
                               placeholder="Tìm kiếm sản phẩm, bài viết..." 
                               class="flex-1 text-lg outline-none"
                               autofocus>
                        <button type="submit" class="text-rose-main hover:text-rose-dark font-medium">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>
                        <button type="button" onclick="toggleSearch()" class="text-gray-medium hover:text-gray-dark">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('noi-dung')
    </main>

    <!-- Toast Notification -->
    <div id="toast-container" class="fixed top-24 right-4 z-50 flex flex-col gap-2"></div>

    <!-- Flash Messages -->
    @if(session('thong_bao'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('thong_bao') }}', 'success');
            });
        </script>
    @endif
    
    @if(session('thong_bao_loi'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('thong_bao_loi') }}', 'error');
            });
        </script>
    @endif

    <!-- Footer sang trọng -->
    <footer class="bg-gradient-to-br from-gray-dark to-gray-medium text-white py-16 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-rose-pastel rounded-full flex items-center justify-center">
                            <i data-lucide="flower-2" class="w-5 h-5 text-rose-main"></i>
                        </div>
                        <span class="font-serif text-xl font-bold">Flower Shop</span>
                    </div>
                    <p class="text-gray-light text-sm leading-relaxed">
                        Cửa hàng hoa tươi cao cấp với đa dạng các loại hoa đẹp, được tuyển chọn từ những vườn hoa chất lượng nhất.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="font-serif text-lg font-semibold mb-6">Liên kết nhanh</h3>
                    <ul class="space-y-3">
                        <li><a href="/san-pham" class="text-gray-light hover:text-white transition-colors text-sm">Sản phẩm</a></li>
                        <li><a href="/bai-viet" class="text-gray-light hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="/ve-chung-toi" class="text-gray-light hover:text-white transition-colors text-sm">Về chúng tôi</a></li>
                        <li><a href="/lien-he" class="text-gray-light hover:text-white transition-colors text-sm">Liên hệ</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div>
                    <h3 class="font-serif text-lg font-semibold mb-6">Danh mục hoa</h3>
                    <ul class="space-y-3">
                        @php
                            $danhMucsFooter = \App\Models\DanhMuc::LayDanhSachHoatDong()->take(4);
                        @endphp
                        @foreach($danhMucsFooter as $dm)
                        <li><a href="/san-pham?danh_muc={{ $dm->slug }}" class="text-gray-light hover:text-white transition-colors text-sm">{{ $dm->ten_danh_muc }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="font-serif text-lg font-semibold mb-6">Liên hệ</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-rose-main mt-1 flex-shrink-0"></i>
                            <span class="text-gray-light text-sm">{{ \App\Models\Setting::get('contact_address', '123 Đường Hoa, Quận 1, TP. Hồ Chí Minh') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-5 h-5 text-rose-main flex-shrink-0"></i>
                            <span class="text-gray-light text-sm">{{ \App\Models\Setting::get('contact_phone', '090 123 4567') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-5 h-5 text-rose-main flex-shrink-0"></i>
                            <span class="text-gray-light text-sm">{{ \App\Models\Setting::get('contact_email', 'info@flowershop.com') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i data-lucide="clock" class="w-5 h-5 text-rose-main flex-shrink-0"></i>
                            <span class="text-gray-light text-sm">{{ \App\Models\Setting::get('contact_hours', '8:00 - 20:00 hàng ngày') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-12 pt-8 text-center">
                <p class="text-gray-light text-sm">&copy; {{ date('Y') }} Flower Shop. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        function toggleSearch() {
            const overlay = document.getElementById('searchOverlay');
            overlay.classList.toggle('hidden');
            if (!overlay.classList.contains('hidden')) {
                overlay.querySelector('input').focus();
            }
        }
        
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const searchOverlay = document.getElementById('searchOverlay');
            
            if (!userMenu.contains(event.target) && !event.target.closest('button[onclick="toggleUserMenu()"]')) {
                userMenu.classList.add('hidden');
            }
            
            if (event.target === searchOverlay) {
                searchOverlay.classList.add('hidden');
            }
        });
        
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const iconName = type === 'success' ? 'check-circle' : 'alert-circle';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <i data-lucide="${iconName}" class="w-5 h-5"></i>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:opacity-80">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
            
            container.appendChild(toast);
            lucide.createIcons();
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>
@extends('layouts.admin')

@section('tieude_page')
Trang tổng quan
@endsection

@section('content')
@php
    $tongSanPham = \App\Models\SanPham::count();
    $tongDanhMuc = \App\Models\DanhMuc::count();
    $tongNguoiDung = \App\Models\User::count();
    $tongDonHang = \App\Models\DonHang::count();
    $donHangChoXacNhan = \App\Models\DonHang::where('trang_thai', 'cho_xac_nhan')->count();
    $donHangDaXacNhan = \App\Models\DonHang::where('trang_thai', 'da_xac_nhan')->count();
    $donHangDangChuanBi = \App\Models\DonHang::where('trang_thai', 'dang_chuan_bi')->count();
    $donHangDangGiao = \App\Models\DonHang::where('trang_thai', 'dang_giao')->count();
    $donHangDaGiao = \App\Models\DonHang::where('trang_thai', 'da_giao')->count();
    $donHangDaHuy = \App\Models\DonHang::where('trang_thai', 'da_huy')->count();
    $tongDoanhThu = \App\Models\DonHang::where('trang_thai', 'da_giao')->sum('tong_tien') ?? 0;
    $sanPhamHetHang = \App\Models\SanPham::where('so_luong', '<=', 0)->count();
    $baiVietDang = \App\Models\BaiViet::where('trang_thai', true)->count();
    $nguoiDungMoi = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
    
    // Lấy doanh thu theo tháng (6 tháng gần nhất)
    $doanhThuTheoThang = [];
    for($i = 5; $i >= 0; $i--) {
        $thang = date('Y-m', strtotime("-$i months"));
        $tong = \App\Models\DonHang::where('trang_thai', 'da_giao')
            ->whereYear('created_at', date('Y', strtotime($thang)))
            ->whereMonth('created_at', date('m', strtotime($thang)))
            ->sum('tong_tien') ?? 0;
        $doanhThuTheoThang[] = [
            'thang' => date('m/Y', strtotime($thang)),
            'tong' => $tong
        ];
    }
    
    // Lấy đơn hàng gần nhất
    $donHangGanNhat = \App\Models\DonHang::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
    
    // Lấy sản phẩm bán chạy
    $sanPhamBanChay = \App\Models\SanPham::withCount('chiTietDonHangs')
        ->orderBy('chi_tiet_don_hangs_count', 'desc')
        ->limit(5)
        ->get();
@endphp

<!-- Welcome Section -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-rose-pastel to-pink-pastel rounded-2xl p-8 shadow-lg">
        <div class="flex items-center gap-4">
            @if(auth()->user()->anh_dai_dien)
                <img src="{{ asset(auth()->user()->anh_dai_dien) }}" alt="{{ auth()->user()->ho_ten }}" class="w-16 h-16 rounded-2xl object-cover shadow-md">
            @else
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-md">
                    <i data-lucide="flower-2" class="w-8 h-8 text-rose-main"></i>
                </div>
            @endif
            <div>
                <h2 class="text-2xl font-serif font-bold text-gray-dark">Chào mừng trở lại, {{ auth()->user()->ho_ten }}! 👋</h2>
                <p class="text-gray-medium">Đây là tổng quan hoạt động của cửa hàng hôm nay</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Products -->
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-lg flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6 text-rose-main"></i>
            </div>
            <span class="text-xs text-gray-medium">{{ $sanPhamHetHang }} hết hàng</span>
        </div>
        <p class="text-gray-medium text-xs mb-1">Tổng sản phẩm</p>
        <p class="text-2xl font-bold text-gray-dark">{{ $tongSanPham }}</p>
    </div>
    
    <!-- Total Categories -->
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-green-pastel to-green-light rounded-lg flex items-center justify-center">
                <i data-lucide="tag" class="w-6 h-6 text-green-main"></i>
            </div>
            <span class="text-xs text-gray-medium">Danh mục</span>
        </div>
        <p class="text-gray-medium text-xs mb-1">Danh mục</p>
        <p class="text-2xl font-bold text-gray-dark">{{ $tongDanhMuc }}</p>
    </div>
    
    <!-- Total Users -->
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-pastel to-blue-light rounded-lg flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-blue-main"></i>
            </div>
            <span class="text-xs text-green-main">+{{ $nguoiDungMoi }} tuần này</span>
        </div>
        <p class="text-gray-medium text-xs mb-1">Người dùng</p>
        <p class="text-2xl font-bold text-gray-dark">{{ $tongNguoiDung }}</p>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-pastel to-purple-light rounded-lg flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-purple-main"></i>
            </div>
            <span class="text-xs text-gray-medium">{{ $donHangChoXacNhan }} chờ xác nhận</span>
        </div>
        <p class="text-gray-medium text-xs mb-1">Đơn hàng</p>
        <p class="text-2xl font-bold text-gray-dark">{{ $tongDonHang }}</p>
    </div>
</div>

<!-- Revenue & Orders Status -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <!-- Revenue Card -->
    <div class="bg-gradient-to-r from-rose-main to-pink-main rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <i data-lucide="trending-up" class="w-6 h-6"></i>
            <span class="text-xs opacity-80">Tổng doanh thu</span>
        </div>
        <p class="text-3xl font-bold">{{ number_format($tongDoanhThu, 0, ',', '.') }} đ</p>
        <p class="text-xs opacity-80 mt-1">{{ $donHangDaGiao }} đơn đã giao</p>
    </div>
    
    <!-- Orders Status -->
    <div class="bg-white rounded-xl p-5 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-semibold text-gray-dark mb-4">Trạng thái đơn hàng</h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            <div class="text-center p-3 bg-yellow-pastel rounded-lg">
                <p class="text-lg font-bold text-yellow-main">{{ $donHangChoXacNhan }}</p>
                <p class="text-xs text-gray-medium">Chờ xác nhận</p>
            </div>
            <div class="text-center p-3 bg-blue-pastel rounded-lg">
                <p class="text-lg font-bold text-blue-main">{{ $donHangDaXacNhan }}</p>
                <p class="text-xs text-gray-medium">Đã xác nhận</p>
            </div>
            <div class="text-center p-3 bg-purple-pastel rounded-lg">
                <p class="text-lg font-bold text-purple-main">{{ $donHangDangChuanBi }}</p>
                <p class="text-xs text-gray-medium">Chuẩn bị</p>
            </div>
            <div class="text-center p-3 bg-indigo-pastel rounded-lg">
                <p class="text-lg font-bold text-indigo-main">{{ $donHangDangGiao }}</p>
                <p class="text-xs text-gray-medium">Đang giao</p>
            </div>
            <div class="text-center p-3 bg-green-pastel rounded-lg">
                <p class="text-lg font-bold text-green-main">{{ $donHangDaGiao }}</p>
                <p class="text-xs text-gray-medium">Đã giao</p>
            </div>
            <div class="text-center p-3 bg-red-pastel rounded-lg">
                <p class="text-lg font-bold text-red-main">{{ $donHangDaHuy }}</p>
                <p class="text-xs text-gray-medium">Đã hủy</p>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="bg-white rounded-xl p-5 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-semibold text-gray-dark">Biểu đồ doanh thu</h3>
            <p class="text-xs text-gray-medium">6 tháng gần nhất</p>
        </div>
    </div>
    <div class="space-y-3">
        @foreach($doanhThuTheoThang as $item)
        <div class="flex items-center gap-3">
            <p class="w-16 text-xs text-gray-medium">{{ $item['thang'] }}</p>
            <div class="flex-1 h-8 bg-gray-100 rounded-lg overflow-hidden">
                @php
                    $doanhThuArray = collect($doanhThuTheoThang)->pluck('tong')->toArray();
                    $maxValue = max($doanhThuArray);
                    $width = $maxValue > 0 ? ($item['tong'] / $maxValue * 100) : 0;
                    $displayWidth = $item['tong'] > 0 ? max($width, 5) : 0;
                @endphp
                @if($item['tong'] > 0)
                <div class="h-full bg-gradient-to-r from-rose-main to-pink-main rounded-lg" style="width: {{ $displayWidth }}%;"></div>
                @endif
            </div>
            <p class="w-20 text-right text-xs font-bold text-gray-dark">{{ number_format($item['tong'], 0, ',', '.') }} đ</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Recent Orders & Best Selling -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-dark">Đơn hàng gần đây</h3>
            <a href="/admin/don-hang" class="text-rose-main text-xs font-medium hover:text-rose-dark">Xem tất cả →</a>
        </div>
        <div class="space-y-3">
            @forelse($donHangGanNhat as $donHang)
            <div class="flex items-center gap-3 p-3 bg-gray-light rounded-lg">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                    @if($donHang->trang_thai == 'cho_xac_nhan')
                        <i data-lucide="clock" class="w-4 h-4 text-yellow-main"></i>
                    @elseif($donHang->trang_thai == 'da_giao')
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-main"></i>
                    @elseif($donHang->trang_thai == 'dang_giao')
                        <i data-lucide="truck" class="w-4 h-4 text-blue-main"></i>
                    @else
                        <i data-lucide="package" class="w-4 h-4 text-gray-medium"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-dark truncate">{{ $donHang->ma_don_hang }}</p>
                    <p class="text-xs text-gray-medium truncate">{{ $donHang->user->ho_ten ?? 'Khách' }}</p>
                </div>
                <p class="text-xs font-bold text-gray-dark">{{ number_format($donHang->tong_tien, 0, ',', '.') }} đ</p>
            </div>
            @empty
            <div class="text-center py-6">
                <i data-lucide="inbox" class="w-8 h-8 text-gray-light mx-auto mb-2"></i>
                <p class="text-xs text-gray-medium">Chưa có đơn hàng</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Best Selling Products -->
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-dark">Sản phẩm bán chạy</h3>
            <a href="/admin/san-pham" class="text-rose-main text-xs font-medium hover:text-rose-dark">Xem tất cả →</a>
        </div>
        <div class="space-y-3">
            @forelse($sanPhamBanChay as $sanPham)
            <div class="flex items-center gap-3 p-3 bg-gray-light rounded-lg">
                @if($sanPham->hinhAnhs->isNotEmpty())
                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                @else
                    <div class="w-8 h-8 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="image" class="w-4 h-4 text-rose-main"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-dark truncate">{{ $sanPham->ten_san_pham }}</p>
                    <p class="text-xs text-gray-medium">{{ $sanPham->chi_tiet_don_hangs_count }} đã bán</p>
                </div>
                <p class="text-xs font-bold text-gray-dark">{{ number_format($sanPham->gia_ban, 0, ',', '.') }} đ</p>
            </div>
            @empty
            <div class="text-center py-6">
                <i data-lucide="package" class="w-8 h-8 text-gray-light mx-auto mb-2"></i>
                <p class="text-xs text-gray-medium">Chưa có dữ liệu</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
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
    $donHangMoi = \App\Models\DonHang::where('trang_thai', 'cho_xac_nhan')->count();
    $donHangDangGiao = \App\Models\DonHang::where('trang_thai', 'dang_giao')->count();
    $tongDoanhThu = \App\Models\DonHang::where('trang_thai', 'da_giao')->sum('thanh_tien') ?? 0;
    
    // Lấy doanh thu theo tháng (6 tháng gần nhất)
    $doanhThuTheoThang = [];
    for($i = 5; $i >= 0; $i--) {
        $thang = date('Y-m', strtotime("-$i months"));
        $tong = \App\Models\DonHang::where('trang_thai', 'da_giao')
            ->whereYear('created_at', date('Y', strtotime($thang)))
            ->whereMonth('created_at', date('m', strtotime($thang)))
            ->sum('thanh_tien') ?? 0;
        $doanhThuTheoThang[] = [
            'thang' => date('m/Y', strtotime($thang)),
            'tong' => $tong
        ];
    }
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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Products -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="package" class="w-7 h-7 text-rose-main"></i>
            </div>
            <span class="bg-green-pastel text-green-main text-xs font-bold px-3 py-1 rounded-full">+12%</span>
        </div>
        <p class="text-gray-medium text-sm mb-1">Tổng sản phẩm</p>
        <p class="text-3xl font-bold text-gray-dark">{{ $tongSanPham }}</p>
        <p class="text-gray-light text-xs mt-2">Cập nhật 5 phút trước</p>
    </div>
    
    <!-- Total Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-pastel to-green-light rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="tag" class="w-7 h-7 text-green-main"></i>
            </div>
            <span class="bg-rose-pastel text-rose-main text-xs font-bold px-3 py-1 rounded-full">+5%</span>
        </div>
        <p class="text-gray-medium text-sm mb-1">Danh mục</p>
        <p class="text-3xl font-bold text-gray-dark">{{ $tongDanhMuc }}</p>
        <p class="text-gray-light text-xs mt-2">Cập nhật 1 giờ trước</p>
    </div>
    
    <!-- Total Users -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-pastel to-blue-light rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="users" class="w-7 h-7 text-blue-main"></i>
            </div>
            <span class="bg-green-pastel text-green-main text-xs font-bold px-3 py-1 rounded-full">+8%</span>
        </div>
        <p class="text-gray-medium text-sm mb-1">Người dùng</p>
        <p class="text-3xl font-bold text-gray-dark">{{ $tongNguoiDung }}</p>
        <p class="text-gray-light text-xs mt-2">Cập nhật 2 giờ trước</p>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-pastel to-purple-light rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="shopping-cart" class="w-7 h-7 text-purple-main"></i>
            </div>
            <span class="bg-rose-pastel text-rose-main text-xs font-bold px-3 py-1 rounded-full">+23%</span>
        </div>
        <p class="text-gray-medium text-sm mb-1">Đơn hàng</p>
        <p class="text-3xl font-bold text-gray-dark">{{ $tongDonHang }}</p>
        <p class="text-gray-light text-xs mt-2">Cập nhật 30 phút trước</p>
    </div>
</div>

<!-- Revenue Chart -->
<div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-dark mb-1">Biểu đồ doanh thu</h3>
            <p class="text-gray-medium text-sm">Doanh thu 6 tháng gần nhất</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold text-gray-dark">{{ number_format($tongDoanhThu, 0, ',', '.') }}₫</p>
            <p class="text-green-main text-sm font-medium">Tổng doanh thu</p>
        </div>
    </div>
    <div class="space-y-4">
        @foreach($doanhThuTheoThang as $item)
        <div class="flex items-center gap-4">
            <p class="w-20 text-sm text-gray-medium">{{ $item['thang'] }}</p>
            <div class="flex-1 h-10 bg-gray-200 rounded-lg overflow-hidden">
                @php
                    $doanhThuArray = collect($doanhThuTheoThang)->pluck('tong')->toArray();
                    $maxValue = max($doanhThuArray);
                    $width = $maxValue > 0 ? ($item['tong'] / $maxValue * 100) : 0;
                    $displayWidth = $item['tong'] > 0 ? max($width, 5) : 0;
                @endphp
                @if($item['tong'] > 0)
                <div class="h-full bg-rose-500 rounded-lg" style="width: {{ $displayWidth }}%;"></div>
                @endif
            </div>
            <p class="w-24 text-right font-bold text-gray-dark">{{ number_format($item['tong'], 0, ',', '.') }}₫</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Recent Orders and Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-dark">Đơn hàng mới</h3>
            <a href="/admin/don-hang" class="text-rose-main text-sm font-medium hover:text-rose-dark transition-colors">Xem tất cả →</a>
        </div>
        <div class="space-y-4">
            @if($donHangMoi > 0)
            <div class="flex items-center gap-4 p-4 bg-rose-pastel rounded-xl">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-rose-main"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-dark">Đơn hàng chờ xác nhận</p>
                    <p class="text-sm text-gray-medium">{{ $donHangMoi }} đơn hàng</p>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-light mx-auto mb-2"></i>
                <p class="text-gray-medium">Không có đơn hàng mới</p>
            </div>
            @endif
            
            @if($donHangDangGiao > 0)
            <div class="flex items-center gap-4 p-4 bg-blue-pastel rounded-xl">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i data-lucide="truck" class="w-5 h-5 text-blue-main"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-dark">Đang giao hàng</p>
                    <p class="text-sm text-gray-medium">{{ $donHangDangGiao }} đơn hàng</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-dark mb-6">Thao tác nhanh</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="/admin/san-pham/create" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-rose-pastel to-pink-pastel rounded-xl hover:shadow-lg transition-all">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="plus" class="w-6 h-6 text-rose-main"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-dark group-hover:text-rose-main transition-colors">Thêm sản phẩm</p>
                    <p class="text-sm text-gray-medium">Tạo sản phẩm mới</p>
                </div>
            </a>
            
            <a href="/admin/don-hang" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-green-pastel to-green-light rounded-xl hover:shadow-lg transition-all">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-green-main"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-dark group-hover:text-green-main transition-colors">Xem đơn hàng</p>
                    <p class="text-sm text-gray-medium">Quản lý đơn hàng</p>
                </div>
            </a>
            
            <a href="/admin/nguoi-dung" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-blue-pastel to-blue-light rounded-xl hover:shadow-lg transition-all">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-6 h-6 text-blue-main"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-dark group-hover:text-blue-main transition-colors">Quản lý users</p>
                    <p class="text-sm text-gray-medium">Xem danh sách users</p>
                </div>
            </a>
            
            <a href="/admin/bai-viet" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-purple-pastel to-purple-light rounded-xl hover:shadow-lg transition-all">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="file-text" class="w-6 h-6 text-purple-main"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-dark group-hover:text-purple-main transition-colors">Viết bài</p>
                    <p class="text-sm text-gray-medium">Đăng bài viết mới</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl p-6 shadow-lg">
    <h3 class="text-lg font-semibold text-gray-dark mb-6">Hoạt động gần đây</h3>
    <div class="space-y-4">
        <div class="flex items-center gap-4 p-4 border border-gray-light rounded-xl">
            <div class="w-10 h-10 bg-rose-pastel rounded-full flex items-center justify-center">
                <i data-lucide="package" class="w-5 h-5 text-rose-main"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-gray-dark">Đã thêm sản phẩm mới</p>
                <p class="text-sm text-gray-medium">2 giờ trước</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 p-4 border border-gray-light rounded-xl">
            <div class="w-10 h-10 bg-green-pastel rounded-full flex items-center justify-center">
                <i data-lucide="user-plus" class="w-5 h-5 text-green-main"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-gray-dark">Người dùng mới đăng ký</p>
                <p class="text-sm text-gray-medium">5 giờ trước</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 p-4 border border-gray-light rounded-xl">
            <div class="w-10 h-10 bg-blue-pastel rounded-full flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-main"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-gray-dark">Đơn hàng mới #DH123</p>
                <p class="text-sm text-gray-medium">1 ngày trước</p>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const doanhThuData = @json($doanhThuTheoThang);
        console.log('Doanh thu data:', doanhThuData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: doanhThuData.map(item => item.thang),
                datasets: [{
                    label: 'Doanh thu (₫)',
                    data: doanhThuData.map(item => item.tong),
                    backgroundColor: 'rgba(233, 30, 99, 0.8)',
                    borderColor: '#E91E63',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: '#E91E63'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1F2937',
                        bodyColor: '#6B7280',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + '₫';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M₫';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'K₫';
                                }
                                return value + '₫';
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } else {
        console.error('Chart canvas not found');
    }
</script>
@endsection
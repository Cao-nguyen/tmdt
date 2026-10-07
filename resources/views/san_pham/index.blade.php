@extends('layouts.app')

@section('tieude', 'Sản Phẩm - Flower Shop')

@section('noi-dung')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-rose-pastel to-pink-pastel py-12">
    <div class="container mx-auto px-4">
        <h1 class="font-serif text-4xl font-bold text-gray-dark text-center mb-2">Sản Phẩm</h1>
        <p class="text-gray-medium text-center">Khám phá bộ sưu tập hoa tươi tuyệt đẹp</p>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-white border-b border-rose-pastel">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="/san-pham" class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->has('danh_muc') ? 'bg-rose-main text-white' : 'bg-rose-pastel text-rose-main hover:bg-rose-light' }} transition-colors">
                    Tất cả
                </a>
                @if($danhMucs->count() > 0)
                    @foreach($danhMucs as $danhMuc)
                    <a href="/san-pham?danh_muc={{ $danhMuc->slug }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request()->danh_muc == $danhMuc->slug ? 'bg-rose-main text-white' : 'bg-rose-pastel text-rose-main hover:bg-rose-light' }} transition-colors">
                        {{ $danhMuc->ten_danh_muc }}
                    </a>
                    @endforeach
                @else
                    <span class="text-gray-medium text-sm">Chưa có danh mục</span>
                @endif
            </div>

            <!-- Sort Filter -->
            <div class="flex items-center gap-4">
                <select onchange="window.location.href=this.value" class="px-4 py-2 rounded-lg border border-rose-pastel text-gray-dark outline-none focus:ring-2 focus:ring-rose-main">
                    <option value="/san-pham?{{ request()->fullUrlWithQuery(['sap_xep' => 'moi-nhat']) }}" {{ request()->sap_xep == 'moi-nhat' || !request()->has('sap_xep') ? 'selected' : '' }}>Mới nhất</option>
                    <option value="/san-pham?{{ request()->fullUrlWithQuery(['sap_xep' => 'gia-tang']) }}" {{ request()->sap_xep == 'gia-tang' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="/san-pham?{{ request()->fullUrlWithQuery(['sap_xep' => 'gia-giam']) }}" {{ request()->sap_xep == 'gia-giam' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="/san-pham?{{ request()->fullUrlWithQuery(['sap_xep' => 'ban-chay']) }}" {{ request()->sap_xep == 'ban-chay' ? 'selected' : '' }}>Bán chạy</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-12">
    <div class="container mx-auto px-4">
        @if($sanPhams->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($sanPhams as $sanPham)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all">
                <div class="relative">
                    @if($sanPham->hinhAnhs && $sanPham->hinhAnhs->count() > 0)
                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-64 object-cover">
                    @else
                    <div class="h-64 bg-gradient-to-br from-rose-pastel to-pink-pastel flex items-center justify-center">
                        <i class="fas fa-spa text-6xl text-rose-main opacity-60"></i>
                    </div>
                    @endif
                    @if($sanPham->san_pham_moi)
                    <span class="absolute top-4 left-4 bg-green-main text-white text-xs font-bold px-3 py-1 rounded-full">Mới</span>
                    @endif
                    @if($sanPham->gia_khuyen_mai)
                    <span class="absolute top-4 right-4 bg-rose-main text-white text-xs font-bold px-3 py-1 rounded-full">-{{ round(($sanPham->gia_ban - $sanPham->gia_khuyen_mai) / $sanPham->gia_ban * 100) }}%</span>
                    @endif
                </div>
                <div class="p-6">
                    <p class="text-xs text-gray-medium mb-1">{{ $sanPham->danhMuc->ten_danh_muc }}</p>
                    <h3 class="font-semibold text-gray-dark mb-2 line-clamp-2 group-hover:text-rose-main transition-colors">
                        <a href="/san-pham/{{ $sanPham->slug }}">{{ $sanPham->ten_san_pham }}</a>
                    </h3>
                    <div class="flex items-center gap-2 mb-4">
                        @if($sanPham->gia_khuyen_mai)
                        <span class="text-rose-main font-bold text-lg">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}₫</span>
                        <span class="text-gray-medium line-through text-sm">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @else
                        <span class="text-rose-main font-bold text-lg">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="/san-pham/{{ $sanPham->slug }}" class="flex-1 inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-rose-pastel to-pink-pastel text-rose-main rounded-xl hover:from-rose-main hover:to-pink-main hover:text-white transition-all font-semibold text-sm">
                            <i class="fas fa-eye"></i>
                            <span>Xem</span>
                        </a>
                        <button onclick="addToCart({{ $sanPham->id }})" class="w-12 h-12 bg-rose-pastel text-rose-main rounded-xl hover:bg-rose-main hover:text-white transition-colors flex items-center justify-center">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $sanPhams->appends(request()->except('page'))->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-spa text-6xl text-rose-pastel mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Không tìm thấy sản phẩm</h3>
            <p class="text-gray-medium mb-6">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
            <a href="/san-pham" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-full font-semibold">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại danh sách</span>
            </a>
        </div>
        @endif
    </div>
</section>

<script>
function addToCart(sanPhamId) {
    // Gọi API thêm vào giỏ hàng
    fetch('/gio-hang/them', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            san_pham_id: sanPhamId,
            so_luong: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã thêm vào giỏ hàng!', 'success');
            // Cập nhật số lượng trên icon giỏ hàng
            document.getElementById('cartBadge').textContent = data.cart_count;
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        showToast('Có lỗi xảy ra', 'error');
    });
}
</script>
@endsection
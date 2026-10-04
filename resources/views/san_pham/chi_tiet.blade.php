@extends('layouts.app')

@section('tieude', 'Chi tiết sản phẩm')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Ảnh sản phẩm -->
        <div>
            @if($sanPham->hinhAnhs && $sanPham->hinhAnhs->count() > 0)
                <div class="aspect-square bg-gray-light rounded-2xl overflow-hidden">
                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="aspect-square bg-gray-light rounded-2xl flex items-center justify-center">
                    <i data-lucide="image" class="w-20 h-20 text-gray-medium"></i>
                </div>
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div>
            @if($sanPham->danhMuc)
            <p class="text-rose-main font-medium mb-2">{{ $sanPham->danhMuc->ten_danh_muc }}</p>
            @endif

            <h1 class="text-3xl font-bold text-gray-dark mb-4">{{ $sanPham->ten_san_pham }}</h1>

            <div class="flex items-center gap-3 mb-6">
                @if($sanPham->gia_khuyen_mai)
                <span class="text-3xl font-bold text-rose-main">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}₫</span>
                <span class="text-xl text-gray-medium line-through">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                @else
                <span class="text-3xl font-bold text-rose-main">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="font-semibold text-gray-dark mb-2">Mô tả</h3>
                <p class="text-gray-medium leading-relaxed">{{ $sanPham->mo_ta_chi_tiet ?? $sanPham->mo_ta_ngan ?? 'Không có mô tả' }}</p>
            </div>

            <div class="space-y-2 mb-6">
                <p class="text-gray-medium"><strong>Số lượng:</strong> {{ $sanPham->so_luong }}</p>
                <p class="text-gray-medium">
                    <strong>Trạng thái:</strong>
                    @if($sanPham->trang_thai && $sanPham->so_luong > 0)
                        <span class="text-green-main">Còn hàng</span>
                    @else
                        <span class="text-red-main">Hết hàng</span>
                    @endif
                </p>
            </div>

            <button class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span>Thêm vào giỏ</span>
            </button>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

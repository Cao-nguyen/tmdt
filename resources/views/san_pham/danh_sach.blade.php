@extends('layouts.app')

@section('tieude', 'Danh sách sản phẩm')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-dark mb-2">Danh sách sản phẩm</h2>
        <p class="text-gray-medium">Khám phá bộ sưu tập hoa tươi tuyệt đẹp</p>
    </div>

    @if($danhSachSanPham->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($danhSachSanPham as $sanPham)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all">
                <!-- Ảnh -->
                <div class="relative h-56 bg-gray-light">
                    @if($sanPham->hinhAnhs->first())
                        <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-gray-medium"></i>
                        </div>
                    @endif
                    @if($sanPham->san_pham_moi)
                    <span class="absolute top-4 left-4 bg-green-main text-white text-xs font-bold px-3 py-1 rounded-full">Mới</span>
                    @endif
                </div>
                <!-- Thông tin -->
                <div class="p-5">
                    <h3 class="font-semibold text-gray-dark mb-3 line-clamp-2 group-hover:text-rose-main transition-colors">{{ $sanPham->ten_san_pham }}</h3>
                    <div class="flex items-center gap-2 mb-4">
                        @if($sanPham->gia_khuyen_mai)
                        <span class="text-rose-main font-bold text-xl">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}₫</span>
                        <span class="text-gray-medium line-through text-sm">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @else
                        <span class="text-rose-main font-bold text-xl">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                    <a href="/san-pham/{{ $sanPham->slug }}" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        <span>Xem chi tiết</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Chưa có sản phẩm nào</h3>
            <p class="text-gray-medium mb-6">Hãy thêm sản phẩm đầu tiên của bạn!</p>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
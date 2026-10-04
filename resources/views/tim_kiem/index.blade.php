@extends('layouts.app')

@section('tieude', 'Tìm kiếm: ' . $tuKhoa)

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Kết quả tìm kiếm: "{{ $tuKhoa }}"</h1>

    <!-- Sản phẩm -->
    @if($sanPhams->count() > 0)
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-dark mb-6">Sản phẩm ({{ $sanPhams->count() }})</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($sanPhams as $sanPham)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all">
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
    </div>
    @endif

    <!-- Blog -->
    @if($baiViets->count() > 0)
    <div>
        <h2 class="text-2xl font-bold text-gray-dark mb-6">Blog ({{ $baiViets->count() }})</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($baiViets as $baiViet)
            <a href="/bai-viet/{{ $baiViet->slug }}" class="bg-white rounded-2xl overflow-hidden group hover:shadow-xl transition-all flex">
                <div class="relative w-48 h-48 bg-gray-200 flex-shrink-0">
                    @if($baiViet->hinh_anh)
                        <img src="{{ $baiViet->hinh_anh }}" alt="{{ $baiViet->tieu_de }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="file-text" class="w-12 h-12 text-gray-medium"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6 flex-1 flex flex-col justify-center">
                    <h3 class="font-semibold text-gray-dark mb-4 line-clamp-2 group-hover:text-rose-main transition-colors text-lg">{{ $baiViet->tieu_de }}</h3>
                    <div class="inline-flex items-center gap-2 text-rose-main font-semibold hover:text-rose-dark transition-colors">
                        <span>Xem chi tiết</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($sanPhams->count() == 0 && $baiViets->count() == 0)
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
            <i data-lucide="search" class="w-10 h-10 text-gray-medium"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-dark mb-2">Không tìm thấy kết quả</h3>
        <p class="text-gray-medium mb-6">Thử từ khóa khác</p>
        <a href="/san-pham" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            <span>Xem sản phẩm</span>
        </a>
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection

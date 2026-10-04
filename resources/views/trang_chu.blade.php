@extends('layouts.app')

@section('tieude', 'Flower Shop - Cửa hàng hoa tươi sang trọng')

@section('noi-dung')
<!-- Hero Section đơn giản đẹp -->
<section class="relative bg-gradient-to-br from-rose-pastel via-pink-pastel to-cream py-24 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-pink-main/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-main/10 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-10 text-center relative z-10">
        <div class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white/80 backdrop-blur rounded-full">
            <i data-lucide="flower-2" class="w-5 h-5 text-rose-main"></i>
            <span class="text-rose-main font-medium text-sm">Cửa hàng hoa tươi</span>
        </div>
        <h1 class="font-serif text-5xl lg:text-7xl font-bold text-gray-dark mb-3">Chọn hoa đẹp</h1>
        <p class="text-2xl text-gray-medium mb-10 max-w-2xl mx-auto">Gửi gắm yêu thương</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/san-pham" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-full hover:shadow-2xl hover:scale-105 transition-all font-semibold text-lg">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span>Khám phá sản phẩm</span>
            </a>
            <a href="/bai-viet" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-white text-rose-main rounded-full hover:shadow-2xl hover:scale-105 transition-all font-semibold text-lg border-2 border-rose-pastel">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span>Xem blog</span>
            </a>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-10">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-rose-pastel text-rose-main rounded-full text-sm font-medium mb-4">Danh mục</span>
            <h2 class="font-serif text-3xl lg:text-4xl font-bold text-gray-dark mb-4">Khám Phá Theo Dịp</h2>
            <p class="text-gray-medium max-w-2xl mx-auto">Tìm kiếm hoa phù hợp cho mọi dịp đặc biệt trong cuộc sống của bạn</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($danhMucs as $danhMuc)
            <a href="/san-pham?danh_muc={{ $danhMuc->slug }}" class="group">
                <div class="bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-2xl p-6 text-center hover:shadow-xl hover:scale-105 transition-all cursor-pointer border-2 border-transparent hover:border-rose-main">
                    <h3 class="font-semibold text-gray-dark group-hover:text-rose-main transition-colors text-lg">{{ $danhMuc->ten_danh_muc }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 bg-cream">
    <div class="container mx-auto px-10">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-gray-dark mb-2">Sản Phẩm Nổi Bật</h2>
                <p class="text-gray-medium">Những bó hoa được yêu thích nhất</p>
            </div>
            <a href="/san-pham" class="hidden md:inline-flex items-center gap-2 text-rose-main font-semibold hover:text-rose-dark transition-colors">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($sanPhamsNoiBat->count() > 0)
                @foreach($sanPhamsNoiBat as $sanPham)
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
            @else
            <div class="col-span-full text-center py-12">
                <p class="text-gray-medium">Chưa có sản phẩm nào</p>
            </div>
            @endif
        </div>
        
        <div class="text-center mt-8 md:hidden">
            <a href="/san-pham" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-full font-semibold">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="font-serif text-3xl lg:text-4xl font-bold text-gray-dark mb-4">Tại Sao Chọn Chúng Tôi?</h2>
            <p class="text-gray-medium">Cam kết chất lượng và dịch vụ tốt nhất</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="truck" class="w-8 h-8 text-rose-main"></i>
                </div>
                <h3 class="font-semibold text-gray-dark mb-2">Giao Hàng Nhanh</h3>
                <p class="text-gray-medium text-sm">Giao hàng trong ngày tại nội thành</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-pastel to-green-light rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="leaf" class="w-8 h-8 text-green-main"></i>
                </div>
                <h3 class="font-semibold text-gray-dark mb-2">Hoa Tươi Mới</h3>
                <p class="text-gray-medium text-sm">Được thu hoạch và giao trong ngày</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-pink-pastel to-pink-light rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="headphones" class="w-8 h-8 text-pink-main"></i>
                </div>
                <h3 class="font-semibold text-gray-dark mb-2">Hỗ Trợ 24/7</h3>
                <p class="text-gray-medium text-sm">Luôn sẵn sàng giúp đỡ mọi lúc</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-cream to-cream-light rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-check" class="w-8 h-8 text-green-dark"></i>
                </div>
                <h3 class="font-semibold text-gray-dark mb-2">Đảm Bảo Chất Lượng</h3>
                <p class="text-gray-medium text-sm">Hoàn tiền nếu không hài lòng</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-10">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-gray-dark mb-2">Blog & Kiến Thức</h2>
                <p class="text-gray-medium">Mẹo cắm hoa và chăm sóc hoa</p>
            </div>
            <a href="/bai-viet" class="hidden md:inline-flex items-center gap-2 text-rose-main font-semibold hover:text-rose-dark transition-colors">
                Xem tất cả <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($baiViets->count() > 0)
                @foreach($baiViets as $baiViet)
                <a href="/bai-viet/{{ $baiViet->slug }}" class="bg-gray-light rounded-2xl overflow-hidden group hover:shadow-xl transition-all flex">
                    <!-- Ảnh -->
                    <div class="relative w-48 h-48 bg-gray-200 flex-shrink-0">
                        @if($baiViet->hinh_anh)
                            <img src="{{ $baiViet->hinh_anh }}" alt="{{ $baiViet->tieu_de }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-12 h-12 text-gray-medium"></i>
                            </div>
                        @endif
                    </div>
                    <!-- Thông tin -->
                    <div class="p-6 flex-1 flex flex-col justify-center">
                        <h3 class="font-semibold text-gray-dark mb-4 line-clamp-2 group-hover:text-rose-main transition-colors text-lg">{{ $baiViet->tieu_de }}</h3>
                        <div class="inline-flex items-center gap-2 text-rose-main font-semibold hover:text-rose-dark transition-colors">
                            <span>Xem chi tiết</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            @else
            <div class="col-span-full text-center py-12">
                <p class="text-gray-medium">Chưa có bài viết nào</p>
            </div>
            @endif
        </div>

        <div class="text-center mt-8 md:hidden">
            <a href="/bai-viet" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-full font-semibold">
                Xem tất cả <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</section>
@endsection

<script>
    lucide.createIcons();
</script>
@extends('layouts.app')

@section('tieude', 'Blog & Kiến Thức')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Blog & Kiến Thức</h1>

    @if($baiViets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($baiViets as $baiViet)
            <a href="/bai-viet/{{ $baiViet->slug }}" class="bg-white rounded-2xl overflow-hidden group hover:shadow-xl transition-all flex">
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
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="file-text" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Chưa có bài viết nào</h3>
            <p class="text-gray-medium">Hãy quay lại sau</p>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection

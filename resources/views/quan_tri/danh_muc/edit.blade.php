@extends('layouts.admin')

@section('tieude', 'Chỉnh sửa danh mục - Flower Shop Admin')
@section('tieude_page', 'Chỉnh sửa danh mục')

@section('noi-dung')
<div class="max-w-2xl">
    <!-- Back Button -->
    <a href="/quan-tri/danh-muc" class="inline-flex items-center gap-2 text-gray-medium hover:text-gray-dark transition-colors mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Quay lại danh sách</span>
    </a>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-semibold text-gray-dark mb-6">Chỉnh sửa danh mục</h2>
        
        <form action="/quan-tri/danh-muc/{{ $danhMuc->id }}/update" method="POST">
            @csrf
            
            <!-- Tên danh mục -->
            <div class="mb-6">
                <label for="ten_danh_muc" class="block text-sm font-medium text-gray-dark mb-2">
                    Tên danh mục <span class="text-red-main">*</span>
                </label>
                <input type="text" 
                       id="ten_danh_muc" 
                       name="ten_danh_muc" 
                       value="{{ old('ten_danh_muc', $danhMuc->ten_danh_muc) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all"
                       placeholder="Nhập tên danh mục">
                @error('ten_danh_muc')
                    <p class="mt-2 text-sm text-red-main">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="mb-6">
                <label for="mo_ta" class="block text-sm font-medium text-gray-dark mb-2">
                    Mô tả
                </label>
                <textarea id="mo_ta" 
                          name="mo_ta" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all resize-none"
                          placeholder="Nhập mô tả cho danh mục">{{ old('mo_ta', $danhMuc->mo_ta) }}</textarea>
                @error('mo_ta')
                    <p class="mt-2 text-sm text-red-main">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thứ tự -->
            <div class="mb-6">
                <label for="sap_xep" class="block text-sm font-medium text-gray-dark mb-2">
                    Thứ tự hiển thị
                </label>
                <input type="number" 
                       id="sap_xep" 
                       name="sap_xep" 
                       value="{{ old('sap_xep', $danhMuc->sap_xep) }}"
                       min="0"
                       class="w-full px-4 py-3 border border-gray-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all"
                       placeholder="0">
                <p class="mt-2 text-xs text-gray-medium">Số càng nhỏ thì hiển thị càng trước</p>
                @error('sap_xep')
                    <p class="mt-2 text-sm text-red-main">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           id="trang_thai" 
                           name="trang_thai" 
                           value="1"
                           {{ old('trang_thai', $danhMuc->trang_thai) ? 'checked' : '' }}
                           class="w-5 h-5 text-rose-main border-gray-medium rounded focus:ring-rose-main transition-colors">
                    <span class="text-sm font-medium text-gray-dark">Hoạt động</span>
                </label>
                <p class="mt-2 text-xs text-gray-medium">Danh mục sẽ hiển thị trên website khi được kích hoạt</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-lg transition-all font-medium">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Cập nhật danh mục</span>
                </button>
                <a href="/quan-tri/danh-muc" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-light text-gray-dark rounded-xl hover:bg-gray-medium transition-colors font-medium">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span>Hủy</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
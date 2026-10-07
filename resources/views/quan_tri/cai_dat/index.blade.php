@extends('layouts.admin')

@section('tieude_page')
Cài đặt hệ thống
@endsection

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Cài đặt hệ thống</h1>
            <p class="text-gray-medium">Quản lý thông tin liên hệ và cấu hình website</p>
        </div>
    </div>

    @if(isset($tableNotExists) && $tableNotExists)
    <div class="bg-yellow-pastel border border-yellow-main rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-main flex-shrink-0"></i>
            <div>
                <h3 class="font-semibold text-gray-dark mb-2">Bảng settings chưa được tạo</h3>
                <p class="text-gray-medium mb-4">Bạn cần chạy migration để tạo bảng settings trước khi sử dụng tính năng này.</p>
                <code class="bg-gray-light px-4 py-2 rounded-lg text-sm text-gray-dark">php artisan migrate</code>
            </div>
        </div>
    </div>
    @else
    <!-- Settings Form -->
    <form action="/admin/cai-dat" method="POST" id="settingsForm" enctype="multipart/form-data">
        @csrf

        <div class="space-y-8">
            <!-- Logo -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-dark mb-6 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5"></i>
                    Logo website
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo hiện tại -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Logo hiện tại</label>
                        <div class="border-2 border-dashed border-gray-light rounded-xl p-6 flex items-center justify-center bg-gray-pure min-h-[120px]">
                            @if($logo = \App\Models\Setting::get('site_logo'))
                            <div class="relative">
                                <img src="{{ asset($logo) }}" alt="Logo hiện tại" class="max-h-24 object-contain">
                                <button type="button" onclick="deleteLogo()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                            </div>
                            @else
                            <div class="text-center">
                                <i data-lucide="image-off" class="w-8 h-8 text-gray-medium mx-auto mb-2"></i>
                                <p class="text-sm text-gray-medium">Chưa có logo</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload logo mới -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Upload logo mới</label>
                        <div class="border-2 border-dashed border-gray-light rounded-xl p-6 hover:border-rose-main transition-colors cursor-pointer">
                            <input
                                type="file"
                                name="site_logo"
                                accept="image/*"
                                id="logoInput"
                                class="hidden"
                                onchange="previewLogo(this)"
                            >
                            <label for="logoInput" class="block text-center cursor-pointer">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-rose-main mx-auto mb-2"></i>
                                <p class="text-sm text-gray-dark font-medium">Click để upload</p>
                                <p class="text-xs text-gray-medium mt-1">PNG, JPG, JPEG (Max 2MB)</p>
                            </label>
                            <div id="logoPreview" class="hidden mt-4 flex justify-center">
                                <img id="previewImage" src="" alt="Preview" class="max-h-20 object-contain rounded-lg border border-gray-light">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-dark mb-6 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    Thông tin liên hệ
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Địa chỉ</label>
                        <input
                            type="text"
                            name="contact_address"
                            value="{{ \App\Models\Setting::get('contact_address', '') }}"
                            placeholder="Nhập địa chỉ"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                        <input
                            type="text"
                            name="contact_phone"
                            value="{{ \App\Models\Setting::get('contact_phone', '') }}"
                            placeholder="Nhập số điện thoại"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Email</label>
                        <input
                            type="email"
                            name="contact_email"
                            value="{{ \App\Models\Setting::get('contact_email', '') }}"
                            placeholder="Nhập email"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Giờ làm việc</label>
                        <input
                            type="text"
                            name="contact_hours"
                            value="{{ \App\Models\Setting::get('contact_hours', '') }}"
                            placeholder="Nhập giờ làm việc"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Lưu cài đặt</span>
            </button>
        </div>
    </form>
    @endif
</div>

<script>
    lucide.createIcons();

    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const previewImage = document.getElementById('previewImage');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }

    function deleteLogo() {
        if (confirm('Bạn có chắc muốn xóa logo?')) {
            fetch('/admin/cai-dat/delete-logo', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Đã xóa logo', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                showToast('Có lỗi xảy ra', 'error');
            });
        }
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-24 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(toastContainer);
        }

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
        }, 3000);
    }

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Đang lưu...</span>';
        btn.disabled = true;
        lucide.createIcons();

        const formData = new FormData(this);

        fetch('/admin/cai-dat', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
                btn.innerHTML = originalContent;
                btn.disabled = false;
                lucide.createIcons();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
            btn.innerHTML = originalContent;
            btn.disabled = false;
            lucide.createIcons();
        });
    });
</script>
@endsection

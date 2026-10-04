@extends('layouts.app')

@section('tieude', 'Đăng ký')

@section('noi-dung')
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gray-light">
    <div class="max-w-md w-full">
        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-full flex items-center justify-center">
                    <i data-lucide="flower-2" class="w-8 h-8 text-rose-main"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-dark mb-2">Đăng ký</h2>
                <p class="text-gray-medium">Tạo tài khoản mới</p>
            </div>

            <!-- Register Form -->
            <form action="/dang-ky" method="POST" class="space-y-5">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Họ và tên -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Họ và tên</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-medium"></i>
                        <input type="text"
                               name="ho_ten"
                               placeholder="Nhập họ và tên của bạn"
                               class="w-full pl-12 pr-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all"
                               required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-medium"></i>
                        <input type="email"
                               name="email"
                               placeholder="Nhập email của bạn"
                               class="w-full pl-12 pr-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all"
                               required>
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                    <div class="relative">
                        <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-medium"></i>
                        <input type="tel"
                               name="so_dien_thoai"
                               placeholder="Nhập số điện thoại"
                               class="w-full pl-12 pr-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all"
                               required>
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Mật khẩu</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-medium"></i>
                        <input type="password"
                               id="mat_khau"
                               name="mat_khau"
                               placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                               class="w-full pl-12 pr-12 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all"
                               required>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-medium hover:text-rose-main transition-colors">
                            <i data-lucide="eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    <span>Đăng ký</span>
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-medium">Đã có tài khoản? <a href="/login" class="text-rose-main font-semibold hover:text-rose-dark transition-colors">Đăng nhập ngay</a></p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="/" class="inline-flex items-center gap-2 text-gray-medium hover:text-rose-main transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Quay lại trang chủ</span>
            </a>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function togglePassword() {
        const input = document.getElementById('mat_khau');
        const icon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>
@endsection

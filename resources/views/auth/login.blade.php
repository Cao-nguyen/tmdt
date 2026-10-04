@extends('layouts.app')

@section('tieude', 'Đăng nhập')

@section('noi-dung')
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gray-light">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-full flex items-center justify-center">
                    <i data-lucide="flower-2" class="w-8 h-8 text-rose-main"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-dark mb-2">Đăng nhập</h2>
                <p class="text-gray-medium">Chào mừng bạn quay lại</p>
            </div>

            <!-- Login Form -->
            <form action="/login" method="POST" class="space-y-6">
                @csrf

                @if(session('thong_bao_loi'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('thong_bao_loi') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Tên đăng nhập/Số điện thoại -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Tên đăng nhập / Số điện thoại</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-medium"></i>
                        <input type="text"
                               name="thong_tin_dang_nhap"
                               placeholder="Nhập tên đăng nhập hoặc số điện thoại"
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
                               placeholder="Nhập mật khẩu"
                               class="w-full pl-12 pr-12 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all"
                               required>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-medium hover:text-rose-main transition-colors">
                            <i data-lucide="eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span>Đăng nhập</span>
                </button>
            </form>

            <!-- Đăng ký -->
            <div class="text-center mt-6">
                <p class="text-gray-medium">Chưa có tài khoản? <a href="/dang-ky" class="text-rose-main font-semibold hover:text-rose-dark transition-colors">Đăng ký ngay</a></p>
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

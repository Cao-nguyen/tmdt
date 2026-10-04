@extends('layouts.app')

@section('tieude', 'Tài khoản')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Hồ sơ cá nhân</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ảnh đại diện -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Ảnh đại diện</h2>

            <div class="flex flex-col items-center">
                @if($user->anh_dai_dien)
                    <img src="{{ asset($user->anh_dai_dien) }}" alt="{{ $user->ho_ten }}" class="w-32 h-32 rounded-full object-cover mb-4 border-4 border-rose-pastel">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-light flex items-center justify-center mb-4 border-4 border-rose-pastel">
                        <i data-lucide="user" class="w-16 h-16 text-gray-medium"></i>
                    </div>
                @endif

                <form action="/tai-khoan/cap-nhat-avatar" method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    <input type="file" name="anh_dai_dien" accept="image/*" class="w-full mb-4 text-sm text-gray-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rose-pastel file:text-rose-main hover:file:bg-rose-light">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        <span>Cập nhật ảnh</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Thông tin cá nhân -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Thông tin cá nhân</h2>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
            @endif

            <form action="/tai-khoan/cap-nhat" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Họ tên</label>
                        <input type="text" name="ho_ten" value="{{ $user->ho_ten }}" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" value="{{ $user->so_dien_thoai }}" class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Địa chỉ</label>
                        <input type="text" name="dia_chi" value="{{ $user->dia_chi }}" class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Lưu thông tin</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

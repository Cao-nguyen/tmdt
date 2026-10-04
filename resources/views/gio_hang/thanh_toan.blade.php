@extends('layouts.app')

@section('tieude', 'Thanh toán')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Thanh toán</h1>

    @if(session('thong_bao_loi'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
        {{ session('thong_bao_loi') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Danh sách sản phẩm -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Sản phẩm trong giỏ</h2>

            @foreach($cart as $item)
            <div class="flex items-center gap-4 py-4 border-b border-gray-light last:border-0">
                @if($item['hinh_anh'])
                    <img src="{{ $item['hinh_anh'] }}" alt="{{ $item['ten_san_pham'] }}" class="w-20 h-20 rounded-lg object-cover">
                @else
                    <div class="w-20 h-20 bg-gray-light rounded-lg flex items-center justify-center">
                        <i data-lucide="image" class="w-8 h-8 text-gray-medium"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-dark">{{ $item['ten_san_pham'] }}</h3>
                    <p class="text-gray-medium text-sm">Số lượng: {{ $item['so_luong'] }}</p>
                </div>
                <div class="text-right">
                    @if($item['gia_khuyen_mai'])
                        <p class="text-rose-main font-bold">{{ number_format($item['gia_khuyen_mai'] * $item['so_luong'], 0, ',', '.') }}₫</p>
                        <p class="text-gray-medium text-sm line-through">{{ number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.') }}₫</p>
                    @else
                        <p class="text-rose-main font-bold">{{ number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.') }}₫</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Thông tin thanh toán -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Thông tin thanh toán</h2>

            <form action="/dat-hang" method="POST" class="space-y-5">
                @csrf

                <!-- Họ tên -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Họ tên</label>
                    <input type="text" name="ho_ten" value="{{ $user->ho_ten ?? '' }}" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                    <input type="tel" name="so_dien_thoai" value="{{ $user->so_dien_thoai ?? '' }}" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                </div>

                <!-- Địa chỉ -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Địa chỉ giao hàng</label>
                    <textarea name="dia_chi" rows="3" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all resize-none">{{ $user->dia_chi ?? '' }}</textarea>
                </div>

                <!-- Phương thức thanh toán -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-3">Phương thức thanh toán</label>

                    <div class="space-y-3">
                        <!-- Tiền mặt -->
                        <label class="flex items-center gap-3 p-4 border border-gray-light rounded-xl cursor-pointer hover:border-rose-main transition-colors">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="cash" checked class="w-5 h-5 text-rose-main">
                            <div class="flex items-center gap-3">
                                <i data-lucide="banknote" class="w-5 h-5 text-gray-medium"></i>
                                <span class="text-gray-dark">Tiền mặt (COD)</span>
                            </div>
                        </label>

                        <!-- Ngân hàng -->
                        <label class="flex items-center gap-3 p-4 border border-gray-light rounded-xl cursor-pointer hover:border-rose-main transition-colors">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="momo" class="w-5 h-5 text-rose-main">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                                    <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
                                </div>
                                <span class="text-gray-dark">Ngân hàng (PayOS)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Ngân hàng Info -->
                <div id="momoInfo" class="hidden bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i data-lucide="building-2" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-medium mb-1">Thanh toán qua cổng PayOS</p>
                            <p class="text-sm text-gray-medium mt-1">Số tiền: <span class="font-bold text-blue-600">{{ number_format($tongTien, 0, ',', '.') }}₫</span></p>
                        </div>
                    </div>
                </div>

                <!-- Tổng tiền -->
                <div class="border-t border-gray-light pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-medium">Tổng thanh toán:</span>
                        <span class="text-2xl font-bold text-rose-main">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                    </div>
                </div>

                <!-- Nút đặt hàng -->
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Đặt hàng ngay</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Show/hide Momo Info
    document.querySelectorAll('input[name="phuong_thuc_thanh_toan"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const momoInfo = document.getElementById('momoInfo');
            if (this.value === 'momo') {
                momoInfo.classList.remove('hidden');
            } else {
                momoInfo.classList.add('hidden');
            }
        });
    });
</script>
@endsection

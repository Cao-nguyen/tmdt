@extends('layouts.app')

@section('tieude', 'Mua ngay')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Mua ngay</h1>

    @if(session('thong_bao_loi'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
        {{ session('thong_bao_loi') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Thông tin sản phẩm -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex gap-6">
                <!-- Ảnh sản phẩm -->
                @if($sanPham->hinhAnhs->first())
                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-48 h-48 rounded-xl object-cover">
                @else
                    <div class="w-48 h-48 bg-gray-light rounded-xl flex items-center justify-center">
                        <i data-lucide="image" class="w-16 h-16 text-gray-medium"></i>
                    </div>
                @endif

                <!-- Thông tin -->
                <div class="flex-1">
                    @if($sanPham->danhMuc)
                    <p class="text-rose-main font-medium mb-2">{{ $sanPham->danhMuc->ten_danh_muc }}</p>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-dark mb-3">{{ $sanPham->ten_san_pham }}</h2>
                    <div class="flex items-center gap-3 mb-4">
                        @if($sanPham->gia_khuyen_mai)
                        <span class="text-2xl font-bold text-rose-main">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}₫</span>
                        <span class="text-gray-medium line-through">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @else
                        <span class="text-2xl font-bold text-rose-main">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                    <p class="text-gray-medium text-sm">Số lượng còn lại: {{ $sanPham->so_luong }}</p>
                </div>
            </div>

            <!-- Chọn số lượng -->
            <div class="mt-6 pt-6 border-t border-gray-light">
                <label class="block text-sm font-medium text-gray-dark mb-3">Số lượng</label>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-gray-light rounded-xl">
                        <button type="button" onclick="updateQuantity(-1)" class="w-12 h-12 flex items-center justify-center text-gray-dark hover:bg-gray-light transition-colors">
                            <i data-lucide="minus" class="w-5 h-5"></i>
                        </button>
                        <input type="number" id="so_luong" name="so_luong" value="1" min="1" max="{{ $sanPham->so_luong }}" class="w-20 h-12 text-center border-x border-gray-light outline-none text-gray-dark font-medium">
                        <button type="button" onclick="updateQuantity(1)" class="w-12 h-12 flex items-center justify-center text-gray-dark hover:bg-gray-light transition-colors">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="text-gray-medium">
                        Tổng tiền: <span id="tong_tien" class="text-2xl font-bold text-rose-main">{{ number_format($sanPham->gia_khuyen_mai ?? $sanPham->gia_ban, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Thông tin giao hàng</h2>

            <form action="/dat-hang-ngay" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="san_pham_id" value="{{ $sanPham->id }}">

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
                            <p class="text-sm text-gray-medium mt-1">Số tiền: <span id="tong_tien_momo" class="font-bold text-blue-600">{{ number_format($sanPham->gia_khuyen_mai ?? $sanPham->gia_ban, 0, ',', '.') }}₫</span></p>
                        </div>
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

    const gia = {{ $sanPham->gia_khuyen_mai ?? $sanPham->gia_ban }};
    const maxQuantity = {{ $sanPham->so_luong }};

    function updateQuantity(change) {
        const input = document.getElementById('so_luong');
        const newValue = parseInt(input.value) + change;

        if (newValue >= 1 && newValue <= maxQuantity) {
            input.value = newValue;
            updateTotal();
        }
    }

    function updateTotal() {
        const quantity = parseInt(document.getElementById('so_luong').value);
        const total = gia * quantity;
        document.getElementById('tong_tien').textContent = total.toLocaleString('vi-VN') + '₫';
        document.getElementById('tong_tien_momo').textContent = total.toLocaleString('vi-VN') + '₫';
    }

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

        new QRCode(qrContainer, {
            text: qrData,
            width: 180,
            height: 180,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        document.getElementById('noi_dung_qr').textContent = content;
    }

    // Update total when quantity changes
    document.getElementById('so_luong').addEventListener('input', updateTotal);
</script>
@endsection

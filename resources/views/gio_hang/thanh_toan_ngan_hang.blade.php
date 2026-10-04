@extends('layouts.app')

@section('tieude')
Thanh toán ngân hàng
@endsection

@section('noi-dung')
<div class="max-w-4xl mx-auto px-10 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Thanh toán ngân hàng</h1>
            <p class="text-gray-medium">Quét mã QR để thanh toán đơn hàng</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- QR Code -->
            <div class="flex-1 flex flex-col items-center">
                <div class="bg-gray-light rounded-2xl p-8 w-full max-w-md">
                    <div class="w-64 h-64 mx-auto bg-white rounded-xl flex items-center justify-center border-2 border-gray-light p-4">
                        <div id="qrcode" class="w-full h-full flex items-center justify-center"></div>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-medium mb-2">Số tài khoản:</p>
                        <p class="text-2xl font-bold text-blue-600">1234567890</p>
                        <p class="text-sm text-gray-medium mt-2">Ngân hàng BIDV</p>
                        <p class="text-sm text-gray-medium">Chủ TK: FLOWER SHOP</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="flex-1">
                <div class="bg-rose-pastel rounded-2xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-dark mb-4">Thông tin đơn hàng</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-medium">Mã đơn hàng:</span>
                            <span class="font-bold text-gray-dark">{{ $pendingOrder['ma_don_hang'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-medium">Người nhận:</span>
                            <span class="font-bold text-gray-dark">{{ $pendingOrder['ho_ten'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-medium">Số điện thoại:</span>
                            <span class="font-bold text-gray-dark">{{ $pendingOrder['so_dien_thoai'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-medium">Địa chỉ:</span>
                            <span class="font-bold text-gray-dark text-right max-w-xs">{{ $pendingOrder['dia_chi'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-light rounded-2xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-dark mb-4">Sản phẩm</h2>
                    <div class="space-y-4">
                        @if($sanPham)
                        <div class="flex items-center gap-4">
                            @if($sanPham->hinhAnhs && $sanPham->hinhAnhs->first())
                                <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-16 h-16 rounded-xl object-cover">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-gray-medium"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-dark">{{ $sanPham->ten_san_pham }}</p>
                                <p class="text-sm text-gray-medium">Số lượng: {{ $pendingOrder['so_luong'] }}</p>
                            </div>
                            <p class="font-bold text-gray-dark">{{ number_format($pendingOrder['tong_tien'], 0, ',', '.') }}₫</p>
                        </div>
                        @else
                        <p class="text-gray-medium">Không có sản phẩm</p>
                        @endif
                    </div>
                </div>

                <div class="bg-rose-pastel rounded-2xl p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-dark">Tổng thanh toán:</span>
                        <span class="text-2xl font-bold text-rose-main">{{ number_format($pendingOrder['tong_tien'], 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nút xác nhận -->
        <div class="mt-8 flex gap-4">
            <form action="/xac-nhan-thanh-toan-ngan-hang" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>Đã thanh toán thành công</span>
                </button>
            </form>
            <a href="/" class="inline-flex items-center justify-center gap-2 py-4 px-8 border border-gray-light rounded-xl hover:bg-gray-light transition-all font-semibold text-lg text-gray-dark">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Hủy</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    lucide.createIcons();

    // Generate QR Code
    const qrContainer = document.getElementById('qrcode');
    if (qrContainer) {
        const phone = '1234567890';
        const amount = {{ $pendingOrder['tong_tien'] }};
        const content = '{{ $pendingOrder['ma_don_hang'] }}';

        // QR code format: phone|amount|content
        const qrData = `${phone}|${amount}|${content}`;

        new QRCode(qrContainer, {
            text: qrData,
            width: 240,
            height: 240,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
</script>
@endsection

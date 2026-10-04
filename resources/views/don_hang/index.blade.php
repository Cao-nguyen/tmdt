@extends('layouts.app')

@section('tieude', 'Đơn hàng của tôi')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Đơn hàng của tôi</h1>

    @if(session('thong_bao'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
        {{ session('thong_bao') }}
    </div>
    @endif

    <!-- Tabs Filter -->
    <div class="flex gap-4 mb-8 border-b border-gray-light">
        <button onclick="filterOrders('all')" class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-rose-main text-rose-main" data-filter="all">
            Tất cả
        </button>
        <button onclick="filterOrders('cho_xac_nhan')" class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-medium hover:text-rose-main transition-colors" data-filter="cho_xac_nhan">
            Chờ xử lý
        </button>
        <button onclick="filterOrders('dang_giao')" class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-medium hover:text-rose-main transition-colors" data-filter="dang_giao">
            Đang giao
        </button>
        <button onclick="filterOrders('da_giao')" class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-medium hover:text-rose-main transition-colors" data-filter="da_giao">
            Thành công
        </button>
    </div>

    @if($donHangs->count() > 0)
        <div class="space-y-6" id="ordersContainer">
            @foreach($donHangs as $donHang)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden order-card" data-status="{{ $donHang->trang_thai }}">
                <!-- Header đơn hàng -->
                <div class="bg-gray-light px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="font-semibold text-gray-dark">Mã đơn: {{ $donHang->ma_don_hang }}</span>
                        <span class="text-gray-medium">|</span>
                        <span class="text-gray-medium">{{ $donHang->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($donHang->trang_thai == 'cho_xac_nhan') bg-yellow-100 text-yellow-700
                        @elseif($donHang->trang_thai == 'da_xac_nhan') bg-blue-100 text-blue-700
                        @elseif($donHang->trang_thai == 'dang_chuan_bi') bg-purple-100 text-purple-700
                        @elseif($donHang->trang_thai == 'dang_giao') bg-orange-100 text-orange-700
                        @elseif($donHang->trang_thai == 'da_giao') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700
                        @endif">
                        @if($donHang->trang_thai == 'cho_xac_nhan') Chờ xác nhận
                        @elseif($donHang->trang_thai == 'da_xac_nhan') Đã xác nhận
                        @elseif($donHang->trang_thai == 'dang_chuan_bi') Đang chuẩn bị
                        @elseif($donHang->trang_thai == 'dang_giao') Đang giao
                        @elseif($donHang->trang_thai == 'da_giao') Đã giao
                        @else Đã hủy
                        @endif
                    </span>
                </div>

                <!-- Chi tiết đơn hàng -->
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-medium"><strong>Người nhận:</strong> {{ $donHang->ho_ten_nguoi_nhan }}</p>
                        <p class="text-gray-medium"><strong>SĐT:</strong> {{ $donHang->so_dien_thoai_nguoi_nhan }}</p>
                        <p class="text-gray-medium"><strong>Địa chỉ:</strong> {{ $donHang->dia_chi_giao }}</p>
                        <p class="text-gray-medium"><strong>Thanh toán:</strong>
                            @if($donHang->phuong_thuc_thanh_toan == 'cod') Tiền mặt (COD)
                            @else Chuyển khoản
                            @endif
                        </p>
                    </div>

                    <!-- Sản phẩm -->
                    <div class="border-t border-gray-light pt-4">
                        @foreach($donHang->chiTietDonHangs as $chiTiet)
                        <div class="flex items-center gap-4 py-3">
                            @if($chiTiet->sanPham && $chiTiet->sanPham->hinhAnhs->first())
                                <img src="{{ $chiTiet->sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $chiTiet->sanPham->ten_san_pham }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 bg-gray-light rounded-lg flex items-center justify-center">
                                    <i data-lucide="image" class="w-8 h-8 text-gray-medium"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-dark">{{ $chiTiet->sanPham->ten_san_pham ?? 'Sản phẩm' }}</p>
                                <p class="text-sm text-gray-medium">Số lượng: {{ $chiTiet->so_luong }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-rose-main font-bold">{{ number_format($chiTiet->gia_ban * $chiTiet->so_luong, 0, ',', '.') }}₫</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tổng tiền -->
                    <div class="border-t border-gray-light mt-4 pt-4 flex justify-between items-center">
                        <span class="text-gray-medium">Tổng thanh toán:</span>
                        <span class="text-2xl font-bold text-rose-main">{{ number_format($donHang->thanh_tien, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Chưa có đơn hàng nào</h3>
            <p class="text-gray-medium mb-6">Hãy mua sắm để tạo đơn hàng đầu tiên</p>
            <a href="/san-pham" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span>Xem sản phẩm</span>
            </a>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();

    function filterOrders(status) {
        // Update tab styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.dataset.filter === status) {
                btn.classList.remove('border-transparent', 'text-gray-medium');
                btn.classList.add('border-rose-main', 'text-rose-main');
            } else {
                btn.classList.remove('border-rose-main', 'text-rose-main');
                btn.classList.add('border-transparent', 'text-gray-medium');
            }
        });

        // Filter orders
        const cards = document.querySelectorAll('.order-card');
        cards.forEach(card => {
            const cardStatus = card.dataset.status;
            let show = false;

            if (status === 'all') {
                show = true;
            } else if (status === 'cho_xac_nhan') {
                show = cardStatus === 'cho_xac_nhan';
            } else if (status === 'dang_giao') {
                // Đang giao bao gồm: đang chuẩn bị, đã xác nhận, đang giao
                show = ['dang_chuan_bi', 'da_xac_nhan', 'dang_giao'].includes(cardStatus);
            } else if (status === 'da_giao') {
                show = cardStatus === 'da_giao';
            }

            card.style.display = show ? 'block' : 'none';
        });
    }
</script>
@endsection

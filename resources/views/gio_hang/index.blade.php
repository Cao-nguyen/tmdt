@extends('layouts.app')

@section('tieude', 'Giỏ hàng')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Giỏ hàng</h1>

    @if(count($cart) > 0)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-light">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-dark">Sản phẩm</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-dark">Giá</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-dark">Số lượng</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-dark">Thành tiền</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-dark">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr class="border-b border-gray-light">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($item['hinh_anh'])
                                    <img src="{{ $item['hinh_anh'] }}" alt="{{ $item['ten_san_pham'] }}" class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 bg-gray-light rounded-lg flex items-center justify-center">
                                        <i data-lucide="image" class="w-6 h-6 text-gray-medium"></i>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-dark">{{ $item['ten_san_pham'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item['gia_khuyen_mai'])
                                <span class="text-rose-main font-bold">{{ number_format($item['gia_khuyen_mai'], 0, ',', '.') }}₫</span>
                            @else
                                <span class="text-rose-main font-bold">{{ number_format($item['gia_ban'], 0, ',', '.') }}₫</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="number" value="{{ $item['so_luong'] }}" min="1" onchange="updateQuantity({{ $item['san_pham_id'] }}, this.value)" class="w-20 px-3 py-2 border border-gray-light rounded-lg text-center">
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-dark">
                                @if($item['gia_khuyen_mai'])
                                    {{ number_format($item['gia_khuyen_mai'] * $item['so_luong'], 0, ',', '.') }}₫
                                @else
                                    {{ number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.') }}₫
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="removeItem({{ $item['san_pham_id'] }})" class="text-red-main hover:text-red-dark transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-between items-center">
            <form action="/gio-hang/xoa-tat-ca" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 border border-red-main text-red-main rounded-xl hover:bg-red-pastel transition-colors">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    <span>Xóa tất cả</span>
                </button>
            </form>

            <div class="text-right">
                <p class="text-xl text-gray-medium mb-2">Tổng cộng:</p>
                <p class="text-3xl font-bold text-rose-main">
                    {{ number_format(collect($cart)->sum(function($item) {
                        return ($item['gia_khuyen_mai'] ?? $item['gia_ban']) * $item['so_luong'];
                    }), 0, ',', '.') }}₫
                </p>
                <a href="/thanh-toan" class="mt-4 inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span>Thanh toán</span>
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Giỏ hàng trống</h3>
            <p class="text-gray-medium mb-6">Hãy thêm sản phẩm vào giỏ hàng</p>
            <a href="/san-pham" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span>Xem sản phẩm</span>
            </a>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();

    function updateQuantity(sanPhamId, quantity) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('san_pham_id', sanPhamId);
        formData.append('so_luong', quantity);

        fetch('/gio-hang/cap-nhat', {
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
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function removeItem(sanPhamId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('san_pham_id', sanPhamId);

        fetch('/gio-hang/xoa', {
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
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endsection

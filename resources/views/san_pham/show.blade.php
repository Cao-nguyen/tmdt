@extends('layouts.app')

@section('tieude', $sanPham->ten_san_pham . ' - Flower Shop')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Ảnh sản phẩm -->
        <div>
            @if($sanPham->hinhAnhs && $sanPham->hinhAnhs->count() > 0)
                <div class="aspect-square bg-gray-light rounded-2xl overflow-hidden">
                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="aspect-square bg-gray-light rounded-2xl flex items-center justify-center">
                    <i data-lucide="image" class="w-20 h-20 text-gray-medium"></i>
                </div>
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div>
            @if($sanPham->danhMuc)
            <p class="text-rose-main font-medium mb-2">{{ $sanPham->danhMuc->ten_danh_muc }}</p>
            @endif

            <h1 class="text-3xl font-bold text-gray-dark mb-4">{{ $sanPham->ten_san_pham }}</h1>

            <div class="flex items-center gap-3 mb-6">
                @if($sanPham->gia_khuyen_mai)
                <span class="text-3xl font-bold text-rose-main">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}₫</span>
                <span class="text-xl text-gray-medium line-through">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                @else
                <span class="text-3xl font-bold text-rose-main">{{ number_format($sanPham->gia_ban, 0, ',', '.') }}₫</span>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="font-semibold text-gray-dark mb-2">Mô tả</h3>
                <p class="text-gray-medium leading-relaxed">{{ $sanPham->mo_ta_chi_tiet ?? $sanPham->mo_ta_ngan ?? 'Không có mô tả' }}</p>
            </div>

            <div class="space-y-2 mb-6">
                <p class="text-gray-medium"><strong>Số lượng:</strong> {{ $sanPham->so_luong }}</p>
                <p class="text-gray-medium">
                    <strong>Trạng thái:</strong>
                    @if($sanPham->trang_thai && $sanPham->so_luong > 0)
                        <span class="text-green-main">Còn hàng</span>
                    @else
                        <span class="text-red-main">Hết hàng</span>
                    @endif
                </p>
            </div>

            <div class="flex gap-4">
                <button onclick="addToCart({{ $sanPham->id }})" class="flex-1 inline-flex items-center justify-center gap-2 py-4 bg-white border-2 border-rose-main text-rose-main rounded-xl hover:bg-rose-pastel transition-all font-semibold text-lg">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Thêm vào giỏ</span>
                </button>
                <button onclick="buyNow({{ $sanPham->id }})" class="flex-1 inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span>Mua ngay</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function addToCart(sanPhamId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('san_pham_id', sanPhamId);
        formData.append('so_luong', 1);

        fetch('/gio-hang/them', {
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
                updateCartBadge(data.cart_count);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
        });
    }

    function buyNow(sanPhamId) {
        window.location.href = '/mua-ngay/' + sanPhamId;
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = count;
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
</script>
@endsection
@extends('layouts.app')

@section('tieude', $sanPham->ten_san_pham . ' - Flower Shop')

@section('noi-dung')
<div class="container mx-auto px-10 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Ảnh sản phẩm -->
        <div>
            @if($sanPham->hinhAnhs && $sanPham->hinhAnhs->count() > 0)
                <div class="aspect-square bg-gray-light rounded-2xl overflow-hidden mb-4">
                    <img id="mainImage" src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-full object-cover">
                </div>
                <!-- Gallery ảnh nhỏ -->
                <div class="grid grid-cols-3 gap-2">
                    @foreach($sanPham->hinhAnhs as $index => $hinhAnh)
                        <div class="aspect-square bg-gray-light rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-rose-main transition-all {{ $loop->first ? 'ring-2 ring-rose-main' : '' }}" onclick="changeMainImage('{{ $hinhAnh->duong_dan }}', this)">
                            <img src="{{ $hinhAnh->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
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

            <div class="flex items-center gap-2 mb-6">
                @php
                    $avgRating = $sanPham->trungBinhDanhGia() ?: 0;
                @endphp
                <div class="flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="text-lg {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                </div>
                <span class="text-gray-medium text-sm">({{ number_format($avgRating, 1) }}/5 - {{ $reviews->count() }} đánh giá)</span>
            </div>

            <div class="mb-6">
                <h3 class="font-semibold text-gray-dark mb-2">Mô tả</h3>
                <div class="text-gray-medium leading-relaxed">{{ $sanPham->mo_ta_chi_tiet ?? 'Không có mô tả' }}</div>
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

<!-- Phần đánh giá -->
<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-gray-dark mb-6">Đánh giá sản phẩm</h2>
    
    @if(auth()->check() && $daMua && !$daDanhGia)
    <!-- Form đánh giá -->
    <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm">
        <h3 class="font-semibold text-gray-dark mb-4">Viết đánh giá của bạn</h3>
        <form id="reviewForm" action="/san-pham/{{ $sanPham->id }}/danh-gia" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-medium mb-2">Đánh giá của bạn</label>
                <div class="flex gap-2" id="starRating">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}" onclick="setRating({{ $i }})">★</button>
                    @endfor
                </div>
                <input type="hidden" name="danh_gia" id="danhGiaInput" value="5">
            </div>
            <div class="mb-4">
                <label class="block text-gray-medium mb-2">Nội dung đánh giá</label>
                <textarea name="noi_dung" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
            </div>
            <button type="submit" class="px-6 py-3 bg-rose-main text-white rounded-xl hover:bg-rose-dark transition-colors font-semibold">Gửi đánh giá</button>
        </form>
    </div>
    @elseif(auth()->check() && $daDanhGia)
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
        <p class="text-green-main font-medium">Bạn đã đánh giá sản phẩm này rồi!</p>
    </div>
    @elseif(!auth()->check())
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-8">
        <p class="text-gray-medium">Vui lòng <a href="{{ route('login') }}" class="text-rose-main font-semibold hover:underline">đăng nhập</a> để đánh giá sản phẩm.</p>
    </div>
    @elseif(!$daMua)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-8">
        <p class="text-yellow-700 font-medium">Bạn cần mua sản phẩm này để có thể đánh giá!</p>
    </div>
    @endif

    <!-- Danh sách đánh giá -->
    @if($reviews->count() > 0)
    <div class="space-y-4">
        @foreach($reviews as $review)
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-4 mb-3">
                @if($review->user->anh_dai_dien)
                <img src="{{ $review->user->anh_dai_dien }}" class="w-12 h-12 rounded-full object-cover">
                @else
                <div class="w-12 h-12 rounded-full bg-rose-pastel flex items-center justify-center">
                    <i data-lucide="user" class="w-6 h-6 text-rose-main"></i>
                </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-dark">{{ $review->user->ho_va_ten ?? $review->user->email }}</p>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <span class="text-sm {{ $i <= $review->danh_gia ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                </div>
            </div>
            @if($review->noi_dung)
            <p class="text-gray-medium">{{ $review->noi_dung }}</p>
            @endif
            <p class="text-sm text-gray-400 mt-2">{{ $review->created_at->format('d/m/Y H:i') }}</p>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-50 rounded-xl p-8 text-center">
        <i data-lucide="message-circle" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
        <p class="text-gray-medium">Chưa có đánh giá nào cho sản phẩm này.</p>
    </div>
    @endif
</div>

<script>
    lucide.createIcons();

    function setRating(rating) {
        document.getElementById('danhGiaInput').value = rating;
        const stars = document.querySelectorAll('#starRating button');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.action, {
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
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    });

    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;
        
        // Xóa ring của tất cả
        document.querySelectorAll('.aspect-square.rounded-lg').forEach(el => {
            el.classList.remove('ring-2', 'ring-rose-main');
        });
        
        // Thêm ring cho ảnh được chọn
        element.classList.add('ring-2', 'ring-rose-main');
    }

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
@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<!-- Header Actions -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-dark">Quản lý sản phẩm</h1>
        <p class="text-gray-medium mt-1">Quản lý tất cả sản phẩm trong cửa hàng</p>
    </div>
    <button onclick="showCreateModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Thêm sản phẩm</span>
    </button>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-light">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Sản phẩm</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Danh mục</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Giá</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Số lượng</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-dark uppercase tracking-wide">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanPhams as $sanPham)
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors">
                        <td class="px-4 py-4 text-xs text-gray-dark font-semibold">{{ $sanPham->id }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                @if($sanPham->hinhAnhs->isNotEmpty())
                                    <img src="{{ $sanPham->hinhAnhs->first()->duong_dan }}" alt="{{ $sanPham->ten_san_pham }}" class="w-10 h-10 object-cover rounded-lg flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="image" class="w-5 h-5 text-rose-main"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-dark truncate">{{ $sanPham->ten_san_pham }}</p>
                                    <p class="text-xs text-gray-medium truncate">{{ $sanPham->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($sanPham->danhMuc)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-pastel text-purple-main rounded text-xs font-semibold">
                                    <i data-lucide="tag" class="w-3 h-3"></i>
                                    {{ $sanPham->danhMuc->ten_danh_muc }}
                                </span>
                            @else
                                <span class="text-gray-medium text-xs">Chưa phân loại</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                @if($sanPham->gia_khuyen_mai && $sanPham->gia_khuyen_mai < $sanPham->gia_ban)
                                    <span class="text-xs text-gray-medium line-through">{{ number_format($sanPham->gia_ban, 0, ',', '.') }} đ</span>
                                    <span class="text-xs font-semibold text-red-main">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }} đ</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-dark">{{ number_format($sanPham->gia_ban, 0, ',', '.') }} đ</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 @if($sanPham->so_luong > 0) bg-green-pastel text-green-main @else bg-red-pastel text-red-main @endif rounded text-xs font-semibold">
                                <i data-lucide="package" class="w-3 h-3"></i>
                                {{ $sanPham->so_luong }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($sanPham->trang_thai)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-pastel text-green-main rounded text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-pastel text-red-main rounded text-xs font-semibold">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    Ẩn
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="showEditModal({{ $sanPham->id }})" class="w-8 h-8 bg-blue-pastel text-blue-main rounded-lg flex items-center justify-center hover:bg-blue-light transition-colors" title="Sửa">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="showDeleteModal({{ $sanPham->id }}, '{{ $sanPham->ten_san_pham }}')" class="w-8 h-8 bg-red-pastel text-red-main rounded-lg flex items-center justify-center hover:bg-red-light transition-colors" title="Xóa">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-8 h-8 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium">Chưa có sản phẩm nào</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="productModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-light flex items-center justify-between bg-white">
            <h2 class="text-xl font-bold text-gray-dark" id="modalTitle">Thêm sản phẩm mới</h2>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-light transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-medium"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <form id="productForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="san_pham_id" id="sanPhamId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tên sản phẩm -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Tên sản phẩm <span class="text-red-main">*</span></label>
                        <input type="text" name="ten_san_pham" id="tenSanPham" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="Nhập tên sản phẩm">
                    </div>

                    <!-- Danh mục -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Danh mục <span class="text-red-main">*</span></label>
                        <select name="danh_muc_id" id="danhMucId" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                            <option value="">-- Chọn danh mục --</option>
                            @php
                                $danhMucs = \App\Models\DanhMuc::where('trang_thai', true)->orderBy('sap_xep')->get();
                            @endphp
                            @foreach($danhMucs as $danhMuc)
                                <option value="{{ $danhMuc->id }}">{{ $danhMuc->ten_danh_muc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Số lượng tồn kho <span class="text-red-main">*</span></label>
                        <input type="number" name="so_luong" id="soLuong" required min="0" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                    </div>

                    <!-- Giá bán -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Giá bán (đ) <span class="text-red-main">*</span></label>
                        <input type="text" name="gia_ban" id="giaBan" required min="0" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                    </div>

                    <!-- Giảm giá -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Giảm giá</label>
                        <div class="flex gap-2">
                            <select name="loai_giam_gia" id="loaiGiamGia" class="w-20 px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                                <option value="phan_tram">%</option>
                                <option value="vnđ">VNĐ</option>
                            </select>
                            <input type="number" name="gia_khuyen_mai" id="giaKhuyenMai" min="0" class="flex-1 px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                        </div>
                        <p class="text-xs text-gray-medium mt-1">Nhập 0 nếu không giảm giá</p>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Ảnh sản phẩm</label>
                        <div class="border-2 border-dashed border-gray-light rounded-lg p-4 text-center hover:border-rose-main transition-colors cursor-pointer" id="imageDropzone">
                            <input type="file" name="hinh_anhs[]" multiple accept="image/*" class="hidden" id="imageInput" onchange="previewImages(event)">
                            <div id="imagePlaceholder">
                                <i data-lucide="upload" class="w-8 h-8 text-gray-medium mx-auto mb-2"></i>
                                <p class="text-sm text-gray-medium">Click để upload ảnh</p>
                            </div>
                            <div id="imagePreview" class="grid grid-cols-4 gap-2 mt-3 hidden"></div>
                        </div>
                    </div>

                    <!-- Mô tả chi tiết -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Mô tả chi tiết</label>
                        <div class="border border-gray-light rounded-lg overflow-hidden">
                            <!-- Toolbar -->
                            <div class="bg-gray-light px-3 py-2 border-b border-gray-light flex items-center gap-1 flex-wrap">
                                <button type="button" onclick="formatText('bold')" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="In đậm">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" onclick="formatText('italic')" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="In nghiêng">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" onclick="formatText('underline')" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="Gạch chân">
                                    <i class="fas fa-underline"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-medium mx-1"></div>
                                <button type="button" onclick="formatText('insertUnorderedList')" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="Danh sách">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                                <button type="button" onclick="formatText('insertOrderedList')" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="Danh sách số">
                                    <i class="fas fa-list-ol"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-medium mx-1"></div>
                                <button type="button" onclick="insertLink()" class="p-2 hover:bg-gray-medium rounded text-gray-dark" title="Thêm link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                            <!-- Editor -->
                            <div id="editor" contenteditable="true" class="min-h-[300px] p-4 focus:outline-none" style="background: white;"></div>
                            <textarea name="mo_ta_chi_tiet" id="moTaChiTiet" class="hidden"></textarea>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="trang_thai" id="trangThai" checked class="w-4 h-4 text-rose-main border-gray-medium focus:ring-rose-main rounded">
                            <span class="text-sm text-gray-dark">Hoạt động</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-light bg-white flex items-center justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-dark hover:bg-gray-light rounded-lg transition-colors">
                Hủy
            </button>
            <button onclick="submitForm()" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-rose-main hover:bg-rose-dark rounded-lg transition-colors flex items-center gap-2">
                <svg id="submitSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="submitBtnText">Tạo sản phẩm</span>
            </button>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-pastel rounded-full flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-main"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-dark">Xác nhận xóa</h3>
                    <p class="text-sm text-gray-medium">Bạn có chắc muốn xóa sản phẩm này?</p>
                </div>
            </div>
            <p class="text-sm text-gray-dark mb-6">
                Sản phẩm <span id="deleteProductName" class="font-bold text-rose-main"></span> sẽ bị xóa vĩnh viễn.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-dark hover:bg-gray-light rounded-lg transition-colors">
                    Hủy
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-main hover:bg-rose-dark rounded-lg transition-colors">
                    Xóa sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentProductId = null;
let deleteProductId = null;

// Modal functions
function showCreateModal() {
    currentProductId = null;
    document.getElementById('modalTitle').textContent = 'Thêm sản phẩm mới';
    document.getElementById('submitBtnText').textContent = 'Tạo sản phẩm';
    document.getElementById('productForm').action = '/admin/san-pham';
    document.getElementById('productForm').method = 'POST';
    document.getElementById('sanPhamId').value = '';
    document.getElementById('productForm').reset();
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imagePlaceholder').classList.remove('hidden');
    
    // Reset giá bán input
    const giaBanInput = document.getElementById('giaBan');
    giaBanInput.value = '';
    giaBanInput.dataset.rawValue = '';
    
    document.getElementById('productModal').classList.remove('hidden');
}

function showEditModal(id) {
    currentProductId = id;
    document.getElementById('modalTitle').textContent = 'Chỉnh sửa sản phẩm';
    document.getElementById('submitBtnText').textContent = 'Cập nhật sản phẩm';
    document.getElementById('productForm').action = '/admin/san-pham/' + id + '/update';
    document.getElementById('productForm').method = 'POST';
    document.getElementById('sanPhamId').value = id;

    // Load product data via AJAX
    fetch('/admin/san-pham/' + id + '/data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.data;
                document.getElementById('tenSanPham').value = product.ten_san_pham;
                document.getElementById('danhMucId').value = product.danh_muc_id;
                document.getElementById('soLuong').value = product.so_luong;
                
                // Load giá bán đúng cách (ép kiểu, làm tròn, format)
                loadGiaBanInput(product.gia_ban);
                
                // Tính giảm giá
                if (product.gia_khuyen_mai && product.gia_khuyen_mai < product.gia_ban) {
                    const giamGia = product.gia_ban - product.gia_khuyen_mai;
                    const phanTram = (giamGia / product.gia_ban * 100).toFixed(2);
                    document.getElementById('loaiGiamGia').value = 'phan_tram';
                    document.getElementById('giaKhuyenMai').value = phanTram;
                    document.getElementById('giaKhuyenMai').dataset.rawValue = phanTram;
                } else {
                    document.getElementById('loaiGiamGia').value = 'phan_tram';
                    document.getElementById('giaKhuyenMai').value = '';
                    document.getElementById('giaKhuyenMai').dataset.rawValue = '';
                }
                
                document.getElementById('moTaChiTiet').value = product.mo_ta_chi_tiet || '';
                document.getElementById('editor').innerHTML = product.mo_ta_chi_tiet || '';
                document.getElementById('trangThai').checked = product.trang_thai;

                // Load ảnh hiện tại
                if (product.hinhAnhs && product.hinhAnhs.length > 0) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('imagePlaceholder');
                    preview.innerHTML = '';
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');

                    product.hinhAnhs.forEach((img, index) => {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${img.duong_dan}" class="w-full h-20 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium">Ảnh ${index + 1}</span>
                            </div>
                        `;
                        preview.appendChild(div);
                    });
                }

                document.getElementById('productModal').classList.remove('hidden');
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading product:', error);
            showToast('Có lỗi xảy ra khi tải sản phẩm', 'error');
        });
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
    document.getElementById('productForm').reset();
    currentProductId = null;
}

// Delete modal functions
function showDeleteModal(id, name) {
    deleteProductId = id;
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    deleteProductId = null;
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    if (!deleteProductId) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/san-pham/' + deleteProductId + '/delete';
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
    document.body.appendChild(form);
    form.submit();
}

// Form submission
function submitForm() {
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitBtnText = document.getElementById('submitBtnText');
    const originalText = submitBtnText.textContent;

    // Convert giá bán từ format về số gốc bằng parseVND
    const giaBanInput = document.getElementById('giaBan');
    if (giaBanInput.dataset.rawValue) {
        formData.set('gia_ban', giaBanInput.dataset.rawValue);
    }

    // Sync content từ editor → textarea trước khi submit
    updateContent();

    submitBtn.disabled = true;
    submitSpinner.classList.remove('hidden');
    submitBtnText.textContent = 'Đang xử lý...';

    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast(data.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitSpinner.classList.add('hidden');
        submitBtnText.textContent = originalText;
    });
}

// Image preview
document.getElementById('imageDropzone').addEventListener('click', function() {
    document.getElementById('imageInput').click();
});

document.getElementById('imageDropzone').addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-rose-main', 'bg-rose-pastel/30');
});

document.getElementById('imageDropzone').addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-rose-main', 'bg-rose-pastel/30');
});

document.getElementById('imageDropzone').addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-rose-main', 'bg-rose-pastel/30');
    const files = e.dataTransfer.files;
    document.getElementById('imageInput').files = files;
    previewImages({ target: { files: files } });
});

function previewImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');

    if (files.length > 0) {
        placeholder.classList.add('hidden');
        preview.classList.remove('hidden');
        preview.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                        <span class="text-white text-xs font-medium">Ảnh ${index + 1}</span>
                    </div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }
}

// Editor functions
function formatText(command) {
    document.execCommand(command, false, null);
    updateContent();
}

function insertLink() {
    const url = prompt('Nhập URL:');
    if (url) {
        document.execCommand('createLink', false, url);
        updateContent();
    }
}

function updateContent() {
    document.getElementById('moTaChiTiet').value = document.getElementById('editor').innerHTML;
}

// Rich text editor (cũ - cho textarea)
function formatTextarea(command) {
    const editor = document.getElementById('moTaChiTiet');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const text = editor.value;
    const selectedText = text.substring(start, end);

    let formattedText = '';
    switch(command) {
        case 'bold':
            formattedText = `**${selectedText}**`;
            break;
        case 'italic':
            formattedText = `*${selectedText}*`;
            break;
        case 'underline':
            formattedText = `__${selectedText}__`;
            break;
        case 'insertUnorderedList':
            formattedText = `- ${selectedText}`;
            break;
        case 'insertOrderedList':
            formattedText = `1. ${selectedText}`;
            break;
    }

    editor.value = text.substring(0, start) + formattedText + text.substring(end);
    editor.focus();
    editor.setSelectionRange(start, start + formattedText.length);
}

// Update discount label
function updateGiamGiaLabel() {
    const select = document.getElementById('loaiGiamGia');
    const label = document.getElementById('giamGiaLabel');
    if (label) {
        label.textContent = select.value === 'vnđ' ? 'VNĐ' : '%';
    }
}

// Hàm load giá trị vào input giá bán
function loadGiaBanInput(rawValue) {
    const input = document.getElementById('giaBan');
    loadCurrencyInput(input, rawValue, true);
}

// Áp dụng setupCurrencyInput cho input giá bán
const giaBanInput = document.getElementById('giaBan');
if (giaBanInput) {
    setupCurrencyInput(giaBanInput, true);
}

// Khi focus vào input giảm giá
document.getElementById('giaKhuyenMai').addEventListener('focus', function() {
    this.value = this.dataset.rawValue || '';
});

document.getElementById('giaKhuyenMai').addEventListener('blur', function() {
    this.dataset.rawValue = this.value;
});

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-main text-white' : 'bg-rose-main text-white'
    }`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    lucide.createIcons();

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
    }
});

lucide.createIcons();
</script>
@endsection

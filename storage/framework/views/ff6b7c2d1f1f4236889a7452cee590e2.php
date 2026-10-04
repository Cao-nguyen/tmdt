<?php $__env->startSection('title', 'Quản lý sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
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
<div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-light">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-rose-pastel to-pink-pastel border-b-2 border-rose-pastel">
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">ID</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Sản phẩm</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Danh mục</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Giá</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Số lượng</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Trạng thái</th>
                    <th class="px-6 py-5 text-center text-sm font-bold text-gray-dark tracking-wide uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sanPhams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sanPham): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-light hover:bg-gradient-to-r hover:from-rose-pastel/50 hover:to-pink-pastel/50 transition-all duration-200 group">
                        <td class="px-6 py-5 text-sm text-gray-dark font-semibold"><?php echo e($sanPham->id); ?></td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <?php if($sanPham->hinhAnhs->isNotEmpty()): ?>
                                    <img src="<?php echo e($sanPham->hinhAnhs->first()->duong_dan); ?>" alt="<?php echo e($sanPham->ten_san_pham); ?>" class="w-14 h-14 object-cover rounded-xl shadow-sm group-hover:scale-110 transition-transform">
                                <?php else: ?>
                                    <div class="w-14 h-14 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-xl flex items-center justify-center shadow-sm">
                                        <i data-lucide="image" class="w-6 h-6 text-rose-main"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm font-semibold text-gray-dark group-hover:text-rose-main transition-colors"><?php echo e($sanPham->ten_san_pham); ?></p>
                                    <p class="text-xs text-gray-medium"><?php echo e($sanPham->slug); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <?php if($sanPham->danhMuc): ?>
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-pastel text-purple-main rounded-xl text-sm font-semibold shadow-sm">
                                    <i data-lucide="tag" class="w-4 h-4"></i>
                                    <?php echo e($sanPham->danhMuc->ten_danh_muc); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-gray-medium text-sm">Chưa phân loại</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <?php if($sanPham->gia_khuyen_mai && $sanPham->gia_khuyen_mai < $sanPham->gia_ban): ?>
                                    <span class="text-xs text-gray-medium line-through"><?php echo e(number_format($sanPham->gia_ban, 0, ',', '.')); ?> VNĐ</span>
                                    <span class="text-sm font-semibold text-red-main"><?php echo e(number_format($sanPham->gia_khuyen_mai, 0, ',', '.')); ?> VNĐ</span>
                                <?php else: ?>
                                    <span class="text-sm font-semibold text-gray-dark"><?php echo e(number_format($sanPham->gia_ban, 0, ',', '.')); ?> VNĐ</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-2 px-4 py-2 <?php if($sanPham->so_luong > 0): ?> bg-green-pastel text-green-main <?php else: ?> bg-red-pastel text-red-main <?php endif; ?> rounded-xl text-sm font-semibold shadow-sm">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                <?php echo e($sanPham->so_luong); ?>

                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <?php if($sanPham->trang_thai): ?>
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-pastel text-green-main rounded-xl text-sm font-semibold shadow-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Hoạt động
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-pastel text-red-main rounded-xl text-sm font-semibold shadow-sm">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    Ẩn
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showEditModal(<?php echo e($sanPham->id); ?>)" class="w-10 h-10 bg-blue-pastel text-blue-main rounded-xl flex items-center justify-center hover:bg-blue-light hover:scale-110 transition-all shadow-sm" title="Sửa">
                                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                                </button>
                                <button onclick="showDeleteModal(<?php echo e($sanPham->id); ?>, '<?php echo e($sanPham->ten_san_pham); ?>')" class="w-10 h-10 bg-red-pastel text-red-main rounded-xl flex items-center justify-center hover:bg-red-light hover:scale-110 transition-all shadow-sm" title="Xóa">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium text-lg">Chưa có sản phẩm nào</p>
                                <button onclick="showCreateModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                    <span>Thêm sản phẩm mới</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
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
                <?php echo csrf_field(); ?>
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
                            <?php
                                $danhMucs = \App\Models\DanhMuc::where('trang_thai', true)->orderBy('sap_xep')->get();
                            ?>
                            <?php $__currentLoopData = $danhMucs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $danhMuc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($danhMuc->id); ?>"><?php echo e($danhMuc->ten_danh_muc); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Số lượng tồn kho <span class="text-red-main">*</span></label>
                        <input type="number" name="so_luong" id="soLuong" required min="0" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                    </div>

                    <!-- Giá bán -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Giá bán <span class="text-red-main">*</span></label>
                        <input type="number" name="gia_ban" id="giaBan" required min="0" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                    </div>

                    <!-- Giảm giá -->
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-1.5">Giảm giá</label>
                        <div class="flex gap-2">
                            <select name="loai_giam_gia" id="loaiGiamGia" class="w-24 px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                                <option value="vnđ">VNĐ</option>
                                <option value="phan_tram">%</option>
                            </select>
                            <input type="number" name="gia_khuyen_mai" id="giaKhuyenMai" min="0" class="flex-1 px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="0">
                        </div>
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
                            <div class="flex gap-1 p-2 bg-gray-light border-b border-gray-light">
                                <button type="button" onclick="formatText('bold')" class="p-1.5 rounded hover:bg-white transition-colors" title="In đậm">
                                    <i data-lucide="bold" class="w-4 h-4 text-gray-dark"></i>
                                </button>
                                <button type="button" onclick="formatText('italic')" class="p-1.5 rounded hover:bg-white transition-colors" title="In nghiêng">
                                    <i data-lucide="italic" class="w-4 h-4 text-gray-dark"></i>
                                </button>
                                <button type="button" onclick="formatText('underline')" class="p-1.5 rounded hover:bg-white transition-colors" title="Gạch chân">
                                    <i data-lucide="underline" class="w-4 h-4 text-gray-dark"></i>
                                </button>
                            </div>
                            <textarea name="mo_ta_chi_tiet" id="moTaChiTiet" rows="4" class="w-full px-3 py-2 text-sm focus:outline-none resize-none" placeholder="Nhập mô tả chi tiết..."></textarea>
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
                document.getElementById('giaBan').value = product.gia_ban;
                document.getElementById('giaKhuyenMai').value = product.gia_khuyen_mai || '';
                document.getElementById('moTaChiTiet').value = product.mo_ta_chi_tiet || '';
                document.getElementById('trangThai').checked = product.trang_thai;

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
    form.innerHTML = '<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">';
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

    submitBtn.disabled = true;
    submitSpinner.classList.remove('hidden');
    submitBtnText.textContent = 'Đang xử lý...';

    // Debug: log form data
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

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

// Rich text editor
function formatText(command) {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/quan_tri/san_pham/index.blade.php ENDPATH**/ ?>
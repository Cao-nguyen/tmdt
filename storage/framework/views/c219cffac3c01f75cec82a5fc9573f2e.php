<?php use \Illuminate\Support\Str; ?>


<?php $__env->startSection('tieude_page'); ?>
Quản lý bài viết
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <!-- Flash Messages -->
    <?php if(session('thong_bao')): ?>
        <div class="mb-6 p-4 bg-green-pastel border border-green-light rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-main"></i>
            <p class="text-sm text-green-main font-medium"><?php echo e(session('thong_bao')); ?></p>
        </div>
    <?php endif; ?>

    <?php if(session('thong_bao_loi')): ?>
        <div class="mb-6 p-4 bg-red-pastel border border-red-light rounded-xl flex items-center gap-3">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-main"></i>
            <p class="text-sm text-red-main font-medium"><?php echo e(session('thong_bao_loi')); ?></p>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-6 p-4 bg-red-pastel border border-red-light rounded-xl">
            <ul class="text-sm text-red-main">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Quản lý bài viết</h1>
            <p class="text-gray-medium">Quản lý tất cả bài viết blog/kiến thức</p>
        </div>
        <button onclick="showCreateModal()" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium" style="background: linear-gradient(90deg, #f43f5e 0%, #ec4899 100%); color: white;">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Thêm bài viết</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-light">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Tiêu đề</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Trạng thái</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Ngày tạo</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-dark uppercase tracking-wide">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $baiViets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $baiViet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <?php if($baiViet->hinh_anh): ?>
                                    <img src="<?php echo e($baiViet->hinh_anh); ?>" alt="<?php echo e($baiViet->tieu_de); ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-light rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="file-text" class="w-5 h-5 text-gray-medium"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-dark text-sm truncate"><?php echo e($baiViet->tieu_de); ?></p>
                                    <p class="text-xs text-gray-medium truncate"><?php echo e(Str::limit(strip_tags($baiViet->noi_dung), 40)); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <?php if($baiViet->trang_thai): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-pastel text-green-main rounded-lg text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    Đã xuất bản
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-pastel text-yellow-main rounded-lg text-xs font-semibold">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    Nháp
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-medium">
                            <?php echo e($baiViet->created_at->format('d/m/Y')); ?>

                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showEditModal(<?php echo e($baiViet->id); ?>)" class="w-8 h-8 bg-blue-pastel text-blue-main rounded-lg flex items-center justify-center hover:bg-blue-light transition-colors" title="Sửa">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="showDeleteModal(<?php echo e($baiViet->id); ?>, '<?php echo e($baiViet->tieu_de); ?>')" class="w-8 h-8 bg-red-pastel text-red-main rounded-lg flex items-center justify-center hover:bg-red-light transition-colors" title="Xóa">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-8 h-8 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium">Chưa có bài viết nào</p>
                                <button onclick="showCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    <span>Thêm bài viết mới</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl p-4 flex items-center gap-3 min-w-[300px] border-l-4" id="toastContent">
        <div id="toastIcon"></div>
        <div class="flex-1">
            <p id="toastMessage" class="text-sm font-medium"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-medium hover:text-gray-dark">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="baiVietModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-light flex items-center justify-between bg-white">
            <h2 class="text-xl font-bold text-gray-dark" id="modalTitle">Thêm bài viết mới</h2>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-light transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-medium"></i>
            </button>
        </div>

        <!-- Form -->
        <div class="p-6 overflow-y-auto flex-1">
            <form id="baiVietForm" action="/admin/bai-viet" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="bai_viet_id" id="baiVietId" value="">

                <!-- Tiêu đề -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Tiêu đề <span class="text-red-main">*</span></label>
                    <input type="text" name="tieu_de" id="tieuDe" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="Nhập tiêu đề bài viết">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Slug <span class="text-red-main">*</span></label>
                    <input type="text" name="slug" id="slug" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="Tự động tạo từ tiêu đề" readonly>
                    <p class="text-xs text-gray-medium mt-1">Slug sẽ tự động tạo từ tiêu đề + ngày tháng</p>
                </div>

                <!-- Nội dung -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Nội dung <span class="text-red-main">*</span></label>
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
                            <label class="p-2 hover:bg-gray-medium rounded cursor-pointer text-gray-dark" title="Upload ảnh" id="uploadImageBtn">
                                <span id="uploadIcon"><i class="fas fa-image"></i></span>
                                <span id="uploadSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                                <input type="file" id="imageUpload" accept="image/*" class="hidden" onchange="uploadImage(this)">
                            </label>
                        </div>
                        <!-- Editor -->
                        <div id="editor" contenteditable="true" class="min-h-[300px] p-4 focus:outline-none" style="background: white;"></div>
                        <textarea name="noi_dung" id="noiDung" class="hidden"></textarea>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Trạng thái <span class="text-red-main">*</span></label>
                    <select name="trang_thai" id="trangThai" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                        <option value="0">Nháp</option>
                        <option value="1">Đã xuất bản</option>
                    </select>
                </div>

                <!-- Footer buttons inside form -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-light">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg text-sm font-medium" style="border-color: #e5e7eb; color: #1f2937;">
                        Hủy
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2" style="background: linear-gradient(90deg, #f43f5e 0%, #ec4899 100%); color: white;">
                        <svg id="submitSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="submitBtnText">Tạo bài viết</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-light flex items-center justify-between bg-white">
            <h3 class="text-lg font-bold text-gray-dark">Xác nhận xóa</h3>
            <button onclick="closeDeleteModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-light transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-medium"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="mb-2 text-gray-dark">Bạn có chắc chắn muốn xóa bài viết này?</p>
            <div class="rounded-xl p-4 mb-4 bg-gray-light border border-gray-medium">
                <p class="font-semibold text-gray-dark" id="deleteBaiVietTitle"></p>
            </div>
            <div class="rounded-xl p-4 bg-red-pastel border border-red-light">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-main"></i>
                    <p class="text-sm text-gray-dark">Hành động này không thể hoàn tác. Bài viết sẽ bị xóa vĩnh viễn.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-light bg-white flex items-center justify-end gap-3">
            <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 border rounded-xl text-sm font-medium" style="border-color: #e5e7eb; color: #1f2937;">
                Hủy
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="bai_viet_id" id="deleteBaiVietId" value="">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2" style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); color: white;">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Xóa bài viết</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

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

    async function uploadImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Show loading state
            document.getElementById('uploadIcon').classList.add('hidden');
            document.getElementById('uploadSpinner').classList.remove('hidden');
            document.getElementById('uploadImageBtn').classList.add('cursor-not-allowed', 'opacity-50');

            try {
                const response = await fetch('/admin/bai-viet/upload-image', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    const editor = document.getElementById('editor');
                    const img = document.createElement('img');
                    img.src = data.url;
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                    editor.appendChild(img);
                    updateContent();
                    showToast('Upload ảnh thành công!', 'success');
                } else {
                    alert('Upload ảnh thất bại: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi upload ảnh');
            } finally {
                // Hide loading state
                document.getElementById('uploadIcon').classList.remove('hidden');
                document.getElementById('uploadSpinner').classList.add('hidden');
                document.getElementById('uploadImageBtn').classList.remove('cursor-not-allowed', 'opacity-50');
                input.value = '';
            }
        }
    }

    function updateContent() {
        document.getElementById('noiDung').value = document.getElementById('editor').innerHTML;
    }

    // Auto-generate slug from title
    document.getElementById('tieuDe').addEventListener('input', function() {
        const title = this.value;
        const now = new Date();
        const dateStr = now.getDate().toString().padStart(2, '0') +
                        (now.getMonth() + 1).toString().padStart(2, '0') +
                        now.getFullYear();
        const slug = title.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            + '-' + dateStr;
        document.getElementById('slug').value = slug;
    });

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastContent = document.getElementById('toastContent');
        const toastIcon = document.getElementById('toastIcon');
        const toastMessage = document.getElementById('toastMessage');

        toastMessage.textContent = message;

        if (type === 'success') {
            toastContent.style.borderColor = '#22c55e';
            toastIcon.innerHTML = '<i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>';
        } else if (type === 'error') {
            toastContent.style.borderColor = '#ef4444';
            toastIcon.innerHTML = '<i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>';
        } else {
            toastContent.style.borderColor = '#3b82f6';
            toastIcon.innerHTML = '<i data-lucide="info" class="w-5 h-5 text-blue-500"></i>';
        }

        toast.classList.remove('hidden');
        lucide.createIcons();

        setTimeout(() => {
            hideToast();
        }, 3000);
    }

    function hideToast() {
        document.getElementById('toast').classList.add('hidden');
    }

    function showCreateModal() {
        document.getElementById('modalTitle').textContent = 'Thêm bài viết mới';
        document.getElementById('submitBtnText').textContent = 'Tạo bài viết';
        document.getElementById('baiVietForm').action = '/admin/bai-viet';
        document.getElementById('baiVietId').value = '';
        document.getElementById('tieuDe').value = '';
        document.getElementById('slug').value = '';
        document.getElementById('noiDung').value = '';
        document.getElementById('editor').innerHTML = '';
        document.getElementById('trangThai').value = '0';
        document.getElementById('baiVietModal').classList.remove('hidden');
    }

    function showEditModal(id) {
        fetch(`/admin/bai-viet/${id}/data`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Sửa bài viết';
                document.getElementById('submitBtnText').textContent = 'Cập nhật';
                document.getElementById('baiVietForm').action = `/admin/bai-viet/${id}/update`;
                document.getElementById('baiVietId').value = data.data.id;
                document.getElementById('tieuDe').value = data.data.tieu_de;
                document.getElementById('slug').value = data.data.slug;
                document.getElementById('noiDung').value = data.data.noi_dung;
                document.getElementById('editor').innerHTML = data.data.noi_dung;
                document.getElementById('trangThai').value = data.data.trang_thai ? '1' : '0';
                document.getElementById('baiVietModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi tải dữ liệu', 'error');
            });
    }

    function closeModal() {
        document.getElementById('baiVietModal').classList.add('hidden');
    }

    function showDeleteModal(id, title) {
        document.getElementById('deleteBaiVietId').value = id;
        document.getElementById('deleteBaiVietTitle').textContent = title;
        document.getElementById('deleteForm').action = `/admin/bai-viet/${id}/delete`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Form submission - show loading state
    document.getElementById('baiVietForm').addEventListener('submit', function(e) {
        // Sync editor content to textarea before submit
        updateContent();

        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const btnText = document.getElementById('submitBtnText');

        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        btnText.textContent = 'Đang lưu...';

        // Form sẽ submit thông thường, trang sẽ reload
    });

    // Delete form submission
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xóa...';

        const formData = new FormData(form);

        fetch(form.action, {
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
                closeDeleteModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/quan_tri/bai_viet/index.blade.php ENDPATH**/ ?>
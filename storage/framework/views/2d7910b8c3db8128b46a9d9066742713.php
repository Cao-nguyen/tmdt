<?php $__env->startSection('tieude_page'); ?>
Quản lý đơn hàng
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Quản lý đơn hàng</h1>
            <p class="text-gray-medium">Quản lý tất cả đơn hàng của khách hàng</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="selectAll()" class="px-4 py-2 border border-gray-light rounded-xl hover:bg-gray-light transition-all text-sm font-medium text-gray-dark">
                Chọn tất cả
            </button>
            <button onclick="deleteSelected()" class="px-4 py-2 bg-red-pastel text-red-main rounded-xl hover:bg-red-light transition-all text-sm font-medium">
                Xóa đã chọn
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-light">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="w-4 h-4 rounded border-gray-light">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Mã đơn</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Khách hàng</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Tổng tiền</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Trạng thái</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Ngày đặt</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-dark uppercase tracking-wide">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $donHangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donHang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="order-checkbox w-4 h-4 rounded border-gray-light" value="<?php echo e($donHang->id); ?>">
                        </td>
                        <td class="px-4 py-4">
                            <code class="px-2 py-1 bg-gray-light rounded text-xs text-gray-dark"><?php echo e($donHang->ma_don_hang); ?></code>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <?php if($donHang->user->anh_dai_dien): ?>
                                    <img src="<?php echo e(asset($donHang->user->anh_dai_dien)); ?>" alt="<?php echo e($donHang->user->name); ?>" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-lg flex items-center justify-center text-rose-main font-bold text-sm flex-shrink-0">
                                        <?php echo e(substr($donHang->user->ho_ten, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-dark text-sm truncate"><?php echo e($donHang->user->ho_ten); ?></p>
                                    <p class="text-xs text-gray-medium truncate"><?php echo e($donHang->user->email); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-semibold text-gray-dark text-sm"><?php echo e(number_format($donHang->tong_tien, 0, ',', '.')); ?> đ</p>
                        </td>
                        <td class="px-4 py-4">
                            <?php if($donHang->trang_thai == 'cho_xac_nhan'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-pastel text-yellow-main rounded text-xs font-semibold">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    Chờ xác nhận
                                </span>
                            <?php elseif($donHang->trang_thai == 'da_xac_nhan'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-pastel text-blue-main rounded text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    Đã xác nhận
                                </span>
                            <?php elseif($donHang->trang_thai == 'dang_chuan_bi'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-pastel text-purple-main rounded text-xs font-semibold">
                                    <i data-lucide="package" class="w-3 h-3"></i>
                                    Chuẩn bị
                                </span>
                            <?php elseif($donHang->trang_thai == 'dang_giao'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-pastel text-indigo-main rounded text-xs font-semibold">
                                    <i data-lucide="truck" class="w-3 h-3"></i>
                                    Đang giao
                                </span>
                            <?php elseif($donHang->trang_thai == 'da_giao'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-pastel text-green-main rounded text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    Đã giao
                                </span>
                            <?php elseif($donHang->trang_thai == 'da_huy'): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-pastel text-red-main rounded text-xs font-semibold">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    Đã hủy
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-xs text-gray-medium">
                            <?php echo e($donHang->created_at->format('d/m/Y')); ?>

                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="/admin/don-hang/<?php echo e($donHang->id); ?>" class="w-8 h-8 bg-blue-pastel text-blue-main rounded-lg flex items-center justify-center hover:bg-blue-light transition-colors" title="Xem chi tiết">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <button onclick="deleteOrder(<?php echo e($donHang->id); ?>)" class="w-8 h-8 bg-red-pastel text-red-main rounded-lg flex items-center justify-center hover:bg-red-light transition-colors" title="Xóa đơn hàng">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="shopping-bag" class="w-8 h-8 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium">Chưa có đơn hàng nào</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
    }

    function selectAll() {
        const selectAll = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
        selectAll.checked = true;
    }

    function deleteOrder(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/don-hang/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
        });
    }

    function deleteSelected() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value);

        if (selectedIds.length === 0) {
            showToast('Vui lòng chọn đơn hàng để xóa', 'error');
            return;
        }

        if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} đơn hàng này?`)) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Xóa từng đơn hàng
        Promise.all(selectedIds.map(id => 
            fetch(`/admin/don-hang/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
        ))
        .then(results => {
            const successCount = results.filter(r => r.success).length;
            if (successCount === selectedIds.length) {
                showToast(`Đã xóa ${successCount} đơn hàng thành công!`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(`Đã xóa ${successCount}/${selectedIds.length} đơn hàng`, 'warning');
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
        });
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
        const bgColor = type === 'success' ? 'bg-green-500' : (type === 'error' ? 'bg-red-500' : 'bg-yellow-500');
        const iconName = type === 'success' ? 'check-circle' : (type === 'error' ? 'alert-circle' : 'alert-triangle');

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/quan_tri/don_hang/index.blade.php ENDPATH**/ ?>
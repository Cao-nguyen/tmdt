<?php $__env->startSection('tieude_page'); ?>
Chi tiết đơn hàng
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Chi tiết đơn hàng</h1>
            <p class="text-gray-medium">Mã đơn: <code class="px-3 py-1 bg-gray-light rounded-lg text-sm"><?php echo e($donHang->ma_don_hang); ?></code></p>
        </div>
        <a href="/admin/don-hang" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-light rounded-2xl hover:bg-gray-light transition-all font-medium text-gray-dark">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Quay lại</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Thông tin khách hàng -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-dark mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5"></i>
                Thông tin khách hàng
            </h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-medium mb-1">Họ tên</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->ho_ten_nguoi_nhan); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Email</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->email_nguoi_nhan ?? $donHang->user->email); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Số điện thoại</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->so_dien_thoai_nguoi_nhan); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Địa chỉ</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->dia_chi_giao); ?></p>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-dark mb-4 flex items-center gap-2">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Thông tin đơn hàng
            </h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-medium mb-1">Ngày đặt</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->created_at->format('d/m/Y H:i')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Trạng thái</p>
                    <select id="trangThai" onchange="updateStatus(<?php echo e($donHang->id); ?>, this.value)" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                        <option value="cho_xac_nhan" <?php echo e($donHang->trang_thai == 'cho_xac_nhan' ? 'selected' : ''); ?>>Chờ xác nhận</option>
                        <option value="da_xac_nhan" <?php echo e($donHang->trang_thai == 'da_xac_nhan' ? 'selected' : ''); ?>>Đã xác nhận</option>
                        <option value="dang_chuan_bi" <?php echo e($donHang->trang_thai == 'dang_chuan_bi' ? 'selected' : ''); ?>>Đang chuẩn bị</option>
                        <option value="dang_giao" <?php echo e($donHang->trang_thai == 'dang_giao' ? 'selected' : ''); ?>>Đang giao</option>
                        <option value="da_giao" <?php echo e($donHang->trang_thai == 'da_giao' ? 'selected' : ''); ?>>Đã giao</option>
                        <option value="da_huy" <?php echo e($donHang->trang_thai == 'da_huy' ? 'selected' : ''); ?>>Đã hủy</option>
                    </select>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Phương thức thanh toán</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->phuong_thuc_thanh_toan ?? 'Tiền mặt'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-medium mb-1">Ghi chú</p>
                    <p class="font-medium text-gray-dark"><?php echo e($donHang->ghi_chu ?? 'Không có'); ?></p>
                </div>
            </div>
        </div>

        <!-- Tổng tiền -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-dark mb-4 flex items-center gap-2">
                <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                Thanh toán
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <p class="text-sm text-gray-medium">Tạm tính</p>
                    <p class="font-medium text-gray-dark"><?php echo e(number_format($donHang->tong_tien, 0, ',', '.')); ?> đ</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm text-gray-medium">Phí vận chuyển</p>
                    <p class="font-medium text-gray-dark">Miễn phí</p>
                </div>
                <div class="flex justify-between border-t border-gray-light pt-3">
                    <p class="text-sm font-semibold text-gray-dark">Tổng cộng</p>
                    <p class="text-lg font-bold text-rose-main"><?php echo e(number_format($donHang->tong_tien, 0, ',', '.')); ?> đ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết sản phẩm -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mt-6">
        <h2 class="text-lg font-bold text-gray-dark mb-4 flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5"></i>
            Sản phẩm trong đơn
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-light">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-dark">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-dark">Giá</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-dark">Số lượng</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-dark">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $donHang->chiTietDonHangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chiTiet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-light">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if($chiTiet->sanPham && $chiTiet->sanPham->hinhAnhs->first()): ?>
                                        <img src="<?php echo e($chiTiet->sanPham->hinhAnhs->first()->duong_dan); ?>" alt="<?php echo e($chiTiet->sanPham->ten_san_pham); ?>" class="w-12 h-12 rounded-xl object-cover">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-light rounded-xl flex items-center justify-center">
                                            <i data-lucide="image" class="w-6 h-6 text-gray-medium"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-gray-dark"><?php echo e($chiTiet->sanPham->ten_san_pham ?? 'Sản phẩm đã xóa'); ?></p>
                                        <p class="text-sm text-gray-medium"><?php echo e($chiTiet->sanPham->danhMuc->ten_danh_muc ?? ''); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-dark"><?php echo e(number_format($chiTiet->gia_ban, 0, ',', '.')); ?> đ</p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <p class="font-medium text-gray-dark"><?php echo e($chiTiet->so_luong); ?></p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <p class="font-bold text-rose-main"><?php echo e(number_format($chiTiet->thanh_tien, 0, ',', '.')); ?> đ</p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function updateStatus(id, status) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('trang_thai', status);

        fetch(`/admin/don-hang/${id}/update-status`, {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/quan_tri/don_hang/show.blade.php ENDPATH**/ ?>
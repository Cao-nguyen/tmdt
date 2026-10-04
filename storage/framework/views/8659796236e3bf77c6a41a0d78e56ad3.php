<?php $__env->startSection('tieude', 'Thanh toán'); ?>

<?php $__env->startSection('noi-dung'); ?>
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Thanh toán</h1>

    <?php if(session('thong_bao_loi')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
        <?php echo e(session('thong_bao_loi')); ?>

    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Danh sách sản phẩm -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Sản phẩm trong giỏ</h2>

            <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 py-4 border-b border-gray-light last:border-0">
                <?php if($item['hinh_anh']): ?>
                    <img src="<?php echo e($item['hinh_anh']); ?>" alt="<?php echo e($item['ten_san_pham']); ?>" class="w-20 h-20 rounded-lg object-cover">
                <?php else: ?>
                    <div class="w-20 h-20 bg-gray-light rounded-lg flex items-center justify-center">
                        <i data-lucide="image" class="w-8 h-8 text-gray-medium"></i>
                    </div>
                <?php endif; ?>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-dark"><?php echo e($item['ten_san_pham']); ?></h3>
                    <p class="text-gray-medium text-sm">Số lượng: <?php echo e($item['so_luong']); ?></p>
                </div>
                <div class="text-right">
                    <?php if($item['gia_khuyen_mai']): ?>
                        <p class="text-rose-main font-bold"><?php echo e(number_format($item['gia_khuyen_mai'] * $item['so_luong'], 0, ',', '.')); ?>₫</p>
                        <p class="text-gray-medium text-sm line-through"><?php echo e(number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.')); ?>₫</p>
                    <?php else: ?>
                        <p class="text-rose-main font-bold"><?php echo e(number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.')); ?>₫</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-dark mb-6">Thông tin thanh toán</h2>

            <form action="/dat-hang" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>

                <!-- Họ tên -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Họ tên</label>
                    <input type="text" name="ho_ten" value="<?php echo e($user->ho_ten ?? ''); ?>" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                    <input type="tel" name="so_dien_thoai" value="<?php echo e($user->so_dien_thoai ?? ''); ?>" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all">
                </div>

                <!-- Địa chỉ -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">Địa chỉ giao hàng</label>
                    <textarea name="dia_chi" rows="3" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-main transition-all resize-none"><?php echo e($user->dia_chi ?? ''); ?></textarea>
                </div>

                <!-- Phương thức thanh toán -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-3">Phương thức thanh toán</label>

                    <div class="space-y-3">
                        <!-- Tiền mặt -->
                        <label class="flex items-center gap-3 p-4 border border-gray-light rounded-xl cursor-pointer hover:border-rose-main transition-colors">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="cash" checked class="w-5 h-5 text-rose-main">
                            <div class="flex items-center gap-3">
                                <i data-lucide="banknote" class="w-5 h-5 text-gray-medium"></i>
                                <span class="text-gray-dark">Tiền mặt (COD)</span>
                            </div>
                        </label>

                        <!-- Momo QR -->
                        <label class="flex items-center gap-3 p-4 border border-gray-light rounded-xl cursor-pointer hover:border-rose-main transition-colors">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="momo" class="w-5 h-5 text-rose-main">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-pink-500 rounded flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">Mo</span>
                                </div>
                                <span class="text-gray-dark">Ví MoMo (Quét mã QR)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Momo QR Code Preview -->
                <div id="momoQr" class="hidden bg-gray-light rounded-xl p-6 text-center">
                    <p class="text-sm text-gray-medium mb-4">Quét mã QR để thanh toán</p>
                    <div class="w-48 h-48 mx-auto bg-white rounded-lg flex items-center justify-center border-2 border-gray-light">
                        <div class="text-center">
                            <i data-lucide="qr-code" class="w-20 h-20 text-gray-medium mx-auto mb-2"></i>
                            <p class="text-xs text-gray-medium">QR Code MoMo</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-medium mt-4">Số tiền: <span class="font-bold text-rose-main"><?php echo e(number_format($tongTien, 0, ',', '.')); ?>₫</span></p>
                </div>

                <!-- Tổng tiền -->
                <div class="border-t border-gray-light pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-medium">Tổng thanh toán:</span>
                        <span class="text-2xl font-bold text-rose-main"><?php echo e(number_format($tongTien, 0, ',', '.')); ?>₫</span>
                    </div>
                </div>

                <!-- Nút đặt hàng -->
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold text-lg">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Đặt hàng ngay</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Show/hide Momo QR
    document.querySelectorAll('input[name="phuong_thuc_thanh_toan"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const momoQr = document.getElementById('momoQr');
            if (this.value === 'momo') {
                momoQr.classList.remove('hidden');
            } else {
                momoQr.classList.add('hidden');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/gio_hang/thanh_toan.blade.php ENDPATH**/ ?>
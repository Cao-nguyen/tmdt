<?php $__env->startSection('tieude', 'Danh sách sản phẩm'); ?>

<?php $__env->startSection('noi-dung'); ?>
<div class="container mx-auto px-10 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-dark mb-2">Danh sách sản phẩm</h2>
        <p class="text-gray-medium">Khám phá bộ sưu tập hoa tươi tuyệt đẹp</p>
    </div>

    <?php if($danhSachSanPham->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $danhSachSanPham; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sanPham): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all">
                <!-- Ảnh -->
                <div class="relative h-56 bg-gray-light">
                    <?php if($sanPham->hinhAnhs->first()): ?>
                        <img src="<?php echo e($sanPham->hinhAnhs->first()->duong_dan); ?>" alt="<?php echo e($sanPham->ten_san_pham); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-gray-medium"></i>
                        </div>
                    <?php endif; ?>
                    <?php if($sanPham->san_pham_moi): ?>
                    <span class="absolute top-4 left-4 bg-green-main text-white text-xs font-bold px-3 py-1 rounded-full">Mới</span>
                    <?php endif; ?>
                </div>
                <!-- Thông tin -->
                <div class="p-5">
                    <h3 class="font-semibold text-gray-dark mb-3 line-clamp-2 group-hover:text-rose-main transition-colors"><?php echo e($sanPham->ten_san_pham); ?></h3>
                    <div class="flex items-center gap-2 mb-4">
                        <?php if($sanPham->gia_khuyen_mai): ?>
                        <span class="text-rose-main font-bold text-xl"><?php echo e(number_format($sanPham->gia_khuyen_mai, 0, ',', '.')); ?>₫</span>
                        <span class="text-gray-medium line-through text-sm"><?php echo e(number_format($sanPham->gia_ban, 0, ',', '.')); ?>₫</span>
                        <?php else: ?>
                        <span class="text-rose-main font-bold text-xl"><?php echo e(number_format($sanPham->gia_ban, 0, ',', '.')); ?>₫</span>
                        <?php endif; ?>
                    </div>
                    <a href="/san-pham/<?php echo e($sanPham->slug); ?>" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        <span>Xem chi tiết</span>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Chưa có sản phẩm nào</h3>
            <p class="text-gray-medium mb-6">Hãy thêm sản phẩm đầu tiên của bạn!</p>
        </div>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/san_pham/danh_sach.blade.php ENDPATH**/ ?>
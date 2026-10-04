<?php $__env->startSection('tieude', 'Tìm kiếm: ' . $tuKhoa); ?>

<?php $__env->startSection('noi-dung'); ?>
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Kết quả tìm kiếm: "<?php echo e($tuKhoa); ?>"</h1>

    <!-- Sản phẩm -->
    <?php if($sanPhams->count() > 0): ?>
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-dark mb-6">Sản phẩm (<?php echo e($sanPhams->count()); ?>)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $sanPhams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sanPham): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all">
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
    </div>
    <?php endif; ?>

    <!-- Blog -->
    <?php if($baiViets->count() > 0): ?>
    <div>
        <h2 class="text-2xl font-bold text-gray-dark mb-6">Blog (<?php echo e($baiViets->count()); ?>)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php $__currentLoopData = $baiViets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $baiViet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="/bai-viet/<?php echo e($baiViet->slug); ?>" class="bg-white rounded-2xl overflow-hidden group hover:shadow-xl transition-all flex">
                <div class="relative w-48 h-48 bg-gray-200 flex-shrink-0">
                    <?php if($baiViet->hinh_anh): ?>
                        <img src="<?php echo e($baiViet->hinh_anh); ?>" alt="<?php echo e($baiViet->tieu_de); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="file-text" class="w-12 h-12 text-gray-medium"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-center">
                    <h3 class="font-semibold text-gray-dark mb-4 line-clamp-2 group-hover:text-rose-main transition-colors text-lg"><?php echo e($baiViet->tieu_de); ?></h3>
                    <div class="inline-flex items-center gap-2 text-rose-main font-semibold hover:text-rose-dark transition-colors">
                        <span>Xem chi tiết</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($sanPhams->count() == 0 && $baiViets->count() == 0): ?>
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
            <i data-lucide="search" class="w-10 h-10 text-gray-medium"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-dark mb-2">Không tìm thấy kết quả</h3>
        <p class="text-gray-medium mb-6">Thử từ khóa khác</p>
        <a href="/san-pham" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl transition-all font-semibold">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            <span>Xem sản phẩm</span>
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/tim_kiem/index.blade.php ENDPATH**/ ?>
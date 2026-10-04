<?php $__env->startSection('tieude', 'Blog & Kiến Thức'); ?>

<?php $__env->startSection('noi-dung'); ?>
<div class="container mx-auto px-10 py-8">
    <h1 class="text-3xl font-bold text-gray-dark mb-8">Blog & Kiến Thức</h1>

    <?php if($baiViets->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php $__currentLoopData = $baiViets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $baiViet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="/bai-viet/<?php echo e($baiViet->slug); ?>" class="bg-white rounded-2xl overflow-hidden group hover:shadow-xl transition-all flex">
                <!-- Ảnh -->
                <div class="relative w-48 h-48 bg-gray-200 flex-shrink-0">
                    <?php if($baiViet->hinh_anh): ?>
                        <img src="<?php echo e($baiViet->hinh_anh); ?>" alt="<?php echo e($baiViet->tieu_de); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="file-text" class="w-12 h-12 text-gray-medium"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Thông tin -->
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
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-light rounded-full flex items-center justify-center">
                <i data-lucide="file-text" class="w-10 h-10 text-gray-medium"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-dark mb-2">Chưa có bài viết nào</h3>
            <p class="text-gray-medium">Hãy quay lại sau</p>
        </div>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/bai_viet/index.blade.php ENDPATH**/ ?>
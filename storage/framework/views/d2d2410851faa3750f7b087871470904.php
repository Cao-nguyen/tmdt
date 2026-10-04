<?php $__env->startSection('tieude', $baiViet->tieu_de . ' - Blog'); ?>

<?php $__env->startSection('noi-dung'); ?>
<div class="container mx-auto px-10 py-12">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-medium mb-8">
        <a href="/" class="hover:text-rose-main transition-colors">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <a href="/bai-viet" class="hover:text-rose-main transition-colors">Blog</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-dark"><?php echo e($baiViet->tieu_de); ?></span>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="font-serif text-4xl lg:text-5xl font-bold text-gray-dark mb-4"><?php echo e($baiViet->tieu_de); ?></h1>
            <div class="flex items-center justify-center gap-6 text-gray-medium">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-rose-main"></i>
                    <span><?php echo e($baiViet->created_at->format('d/m/Y')); ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-rose-main"></i>
                    <span><?php echo e($baiViet->user->name ?? 'Admin'); ?></span>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t-2 border-rose-pastel mb-12"></div>

        <!-- Content -->
        <div class="text-gray-dark leading-loose text-xl">
            <style>
                .content-text img {
                    max-width: 300px;
                    display: block;
                    margin: 0.5rem auto;
                    border-radius: 1rem;
                }
            </style>
            <div class="content-text">
                <?php echo nl2br($baiViet->noi_dung); ?>

            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/bai_viet/show.blade.php ENDPATH**/ ?>
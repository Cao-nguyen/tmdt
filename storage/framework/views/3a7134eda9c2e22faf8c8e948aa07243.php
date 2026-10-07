<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('tieude', 'Trang quản trị - Flower Shop Admin'); ?></title>
    
    <!-- Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Currency Format Utilities -->
    <script src="<?php echo e(asset('js/currency-format.js')); ?>"></script>
    <!-- Font Awesome (cho icon mạng xã hội) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-gray-light text-gray-dark h-screen overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar - Đứng giữa, full height, không scroll -->
        <aside class="w-72 bg-gradient-to-b from-gray-dark to-gray-medium text-white flex flex-col items-center justify-center py-8 shadow-2xl flex-shrink-0">
            <!-- Logo -->
            <div class="mb-8">
                <a href="/admin" class="group">
                    <span class="font-serif text-2xl font-bold text-white group-hover:text-rose-pastel transition-colors">Flower Shop</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 w-full px-4 space-y-2 overflow-y-auto">
                <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin') || request()->is('admin/') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Trang tổng quan</span>
                </a>

                <a href="/admin/san-pham" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/san-pham*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Sản phẩm</span>
                </a>

                <a href="/admin/danh-muc" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/danh-muc*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Danh mục</span>
                </a>

                <a href="/admin/nguoi-dung" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/nguoi-dung*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Người dùng</span>
                </a>

                <a href="/admin/bai-viet" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/bai-viet*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Bài viết</span>
                </a>

                <a href="/admin/don-hang" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/don-hang*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Đơn hàng</span>
                </a>

                <a href="/admin/cai-dat" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all <?php echo e(request()->is('admin/cai-dat*') ? 'bg-gradient-to-r from-rose-main to-pink-main text-white shadow-lg' : 'text-gray-light hover:bg-white/10 hover:text-white'); ?>">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Cài đặt</span>
                </a>
            </nav>
            
            <!-- User Info -->
            <div class="mt-8 px-4 w-full">
                <div class="flex items-center gap-3 mb-4">
                    <?php if(auth()->user()->anh_dai_dien): ?>
                        <img src="<?php echo e(asset(auth()->user()->anh_dai_dien)); ?>" alt="<?php echo e(auth()->user()->ho_ten); ?>" class="w-12 h-12 rounded-xl object-cover shadow-lg flex-shrink-0">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-xl flex items-center justify-center text-rose-main font-bold text-lg shadow-lg flex-shrink-0">
                            <?php echo e(substr(auth()->user()->ho_ten, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->ho_ten); ?></p>
                        <p class="text-xs text-gray-light"><?php echo e(auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên'); ?></p>
                    </div>
                </div>
                <form action="/logout" method="POST" class="w-full">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-medium transition-all text-white">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col bg-gray-light h-screen overflow-hidden">
            <!-- Header - Fixed, không scroll -->
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 flex-shrink-0">
                <div>
                    <h1 class="text-2xl font-serif font-bold text-gray-dark"><?php echo $__env->yieldContent('tieude_page', 'Trang tổng quan'); ?></h1>
                    <p class="text-sm text-gray-medium">Quản lý hệ thống Flower Shop</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/" class="flex items-center gap-2 px-4 py-2 bg-rose-pastel text-rose-main rounded-xl hover:bg-rose-light transition-colors text-sm font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Về trang chủ</span>
                    </a>
                </div>
            </header>

            <!-- Content - Có scrollbar -->
            <div class="flex-1 overflow-y-auto p-8">
                <!-- Page Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Toast Notification Container
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) {
                const toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-24 right-4 z-[100] flex flex-col gap-3';
                document.body.appendChild(toastContainer);
            }
            
            const toast = document.createElement('div');
            
            const bgGradient = type === 'success' 
                ? 'bg-gradient-to-r from-green-main to-green-dark' 
                : 'bg-gradient-to-r from-rose-main to-rose-dark';
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            
            toast.className = `${bgGradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 transform transition-all duration-500 translate-x-full opacity-0 min-w-[300px]`;
            toast.innerHTML = `
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="${icon}" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
            
            document.getElementById('toast-container').appendChild(toast);
            lucide.createIcons();
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
        
        // Show toast from flash messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('thong_bao')): ?>
                showToast('<?php echo e(session('thong_bao')); ?>', 'success');
            <?php endif; ?>
            
            <?php if(session('thong_bao_loi')): ?>
                showToast('<?php echo e(session('thong_bao_loi')); ?>', 'error');
            <?php endif; ?>
        });
    </script>
</body>
</html><?php /**PATH D:\tmdt\resources\views/layouts/admin.blade.php ENDPATH**/ ?>
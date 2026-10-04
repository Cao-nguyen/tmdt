<?php $__env->startSection('tieude_page'); ?>
Cài đặt hệ thống
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark mb-2">Cài đặt hệ thống</h1>
            <p class="text-gray-medium">Quản lý thông tin liên hệ và cấu hình website</p>
        </div>
    </div>

    <?php if(isset($tableNotExists) && $tableNotExists): ?>
    <div class="bg-yellow-pastel border border-yellow-main rounded-2xl p-6 mb-8">
        <div class="flex items-start gap-4">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-main flex-shrink-0"></i>
            <div>
                <h3 class="font-semibold text-gray-dark mb-2">Bảng settings chưa được tạo</h3>
                <p class="text-gray-medium mb-4">Bạn cần chạy migration để tạo bảng settings trước khi sử dụng tính năng này.</p>
                <code class="bg-gray-light px-4 py-2 rounded-lg text-sm text-gray-dark">php artisan migrate</code>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Settings Form -->
    <form action="/admin/cai-dat" method="POST" id="settingsForm">
        <?php echo csrf_field(); ?>

        <div class="space-y-8">
            <!-- Contact Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-dark mb-6 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    Thông tin liên hệ
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Địa chỉ</label>
                        <input
                            type="text"
                            name="contact_address"
                            value="<?php echo e(\App\Models\Setting::get('contact_address', '')); ?>"
                            placeholder="Nhập địa chỉ"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Số điện thoại</label>
                        <input
                            type="text"
                            name="contact_phone"
                            value="<?php echo e(\App\Models\Setting::get('contact_phone', '')); ?>"
                            placeholder="Nhập số điện thoại"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Email</label>
                        <input
                            type="email"
                            name="contact_email"
                            value="<?php echo e(\App\Models\Setting::get('contact_email', '')); ?>"
                            placeholder="Nhập email"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">Giờ làm việc</label>
                        <input
                            type="text"
                            name="contact_hours"
                            value="<?php echo e(\App\Models\Setting::get('contact_hours', '')); ?>"
                            placeholder="Nhập giờ làm việc"
                            class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-xl hover:scale-105 transition-all font-semibold">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Lưu cài đặt</span>
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();

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

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Đang lưu...</span>';
        btn.disabled = true;
        lucide.createIcons();

        const formData = new FormData(this);

        fetch('/admin/cai-dat', {
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
                btn.innerHTML = originalContent;
                btn.disabled = false;
                lucide.createIcons();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
            btn.innerHTML = originalContent;
            btn.disabled = false;
            lucide.createIcons();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tmdt\resources\views/quan_tri/cai_dat/index.blade.php ENDPATH**/ ?>
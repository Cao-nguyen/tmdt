@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<!-- Header Actions -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-dark">Danh sách danh mục</h2>
        <p class="text-sm text-gray-medium">Quản lý tất cả danh mục sản phẩm</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="showBulkDeleteModal()" id="bulkDeleteBtn" class="hidden flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-rose-dark text-white rounded-xl hover:shadow-lg transition-all font-medium">
            <i data-lucide="trash-2" class="w-5 h-5"></i>
            <span>Xóa đã chọn (<span id="selectedCount">0</span>)</span>
        </button>
        <button onclick="showCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-xl hover:shadow-lg transition-all font-medium">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Thêm danh mục</span>
        </button>
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-light">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-rose-pastel to-pink-pastel border-b-2 border-rose-pastel">
                    <th class="px-6 py-5 text-center w-12">
                        <input type="checkbox" id="selectAll" class="w-5 h-5 text-rose-main border-rose-light rounded focus:ring-rose-main focus:ring-offset-2 cursor-pointer">
                    </th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">ID</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Tên danh mục</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Mô tả</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Số sản phẩm</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Thứ tự</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Trạng thái</th>
                    <th class="px-6 py-5 text-left text-sm font-bold text-gray-dark tracking-wide uppercase">Ngày tạo</th>
                    <th class="px-6 py-5 text-center text-sm font-bold text-gray-dark tracking-wide uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhMucs as $danhMuc)
                    <tr class="border-b border-gray-light hover:bg-gradient-to-r hover:from-rose-pastel/50 hover:to-pink-pastel/50 transition-all duration-200 group">
                        <td class="px-6 py-5 text-center">
                            <input type="checkbox" class="danh-muc-checkbox w-5 h-5 text-rose-main border-gray-medium rounded focus:ring-rose-main focus:ring-offset-2 cursor-pointer transition-colors" value="{{ $danhMuc->id }}">
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-dark font-semibold">{{ $danhMuc->id }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i data-lucide="tag" class="w-6 h-6 text-rose-main"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-dark group-hover:text-rose-main transition-colors">{{ $danhMuc->ten_danh_muc }}</p>
                                    <p class="text-xs text-gray-medium">{{ $danhMuc->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-medium max-w-xs truncate">{{ $danhMuc->mo_ta ?? '-' }}</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-pastel text-green-main rounded-xl text-sm font-semibold shadow-sm">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                {{ $danhMuc->sanPhams->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-dark font-medium">{{ $danhMuc->sap_xep }}</td>
                        <td class="px-6 py-5">
                            @if($danhMuc->trang_thai)
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-pastel text-green-main rounded-xl text-sm font-semibold shadow-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-pastel text-red-main rounded-xl text-sm font-semibold shadow-sm">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    Ẩn
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-medium">{{ $danhMuc->created_at ? $danhMuc->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/danh-muc/{{ $danhMuc->id }}/edit" class="w-10 h-10 bg-blue-pastel text-blue-main rounded-xl flex items-center justify-center hover:bg-blue-light hover:scale-110 transition-all shadow-sm" title="Sửa">
                                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                                </a>
                                <button onclick="showDeleteModal({{ $danhMuc->id }}, '{{ $danhMuc->ten_danh_muc }}', {{ $danhMuc->sanPhams->count() }})" class="w-10 h-10 bg-red-pastel text-red-main rounded-xl flex items-center justify-center hover:bg-red-light hover:scale-110 transition-all shadow-sm" title="Xóa">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium text-lg">Chưa có danh mục nào</p>
                                <a href="/admin/danh-muc/create" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                    <span>Tạo danh mục mới</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="createModalContent">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-rose-pastel to-pink-pastel p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="folder-plus" class="w-7 h-7 text-rose-main"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-serif font-bold text-gray-dark">Thêm danh mục mới</h3>
                        <p class="text-sm text-gray-medium">Tạo danh mục sản phẩm mới</p>
                    </div>
                </div>
                <button onclick="closeCreateModal()" class="w-10 h-10 bg-white/50 hover:bg-white rounded-xl flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-dark"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <form id="createForm" action="/admin/danh-muc" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-dark mb-2">Tên danh mục <span class="text-red-main">*</span></label>
                    <input type="text" name="ten_danh_muc" required class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all" placeholder="Nhập tên danh mục">
                    @error('ten_danh_muc')
                        <p class="text-red-main text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-dark mb-2">Mô tả</label>
                    <textarea name="mo_ta" rows="3" class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all resize-none" placeholder="Nhập mô tả danh mục"></textarea>
                    @error('mo_ta')
                        <p class="text-red-main text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-dark mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="sap_xep" min="0" class="w-full px-4 py-3 border border-gray-light rounded-xl focus:ring-2 focus:ring-rose-main focus:border-transparent transition-all" placeholder="0">
                        @error('sap_xep')
                            <p class="text-red-main text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-dark mb-2">Trạng thái</label>
                        <label class="flex items-center gap-2 cursor-pointer mt-3">
                            <input type="checkbox" name="trang_thai" checked class="w-4 h-4 text-rose-main border-gray-medium focus:ring-rose-main rounded">
                            <span class="text-sm text-gray-dark">Hoạt động</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="px-6 pb-6 flex items-center gap-4">
            <button onclick="closeCreateModal()" class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-light text-gray-dark rounded-2xl hover:bg-gray-medium transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Hủy bỏ</span>
            </button>
            <button type="submit" form="createForm" class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Tạo danh mục</span>
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-rose-pastel to-pink-pastel p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-main"></i>
                </div>
                <div>
                    <h3 class="text-xl font-serif font-bold text-gray-dark">Xác nhận xóa</h3>
                    <p class="text-sm text-gray-medium">Bạn có chắc muốn xóa danh mục này?</p>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="bg-gradient-to-br from-rose-pastel/50 to-pink-pastel/50 rounded-2xl p-5 mb-6 border border-rose-pastel">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <i data-lucide="tag" class="w-5 h-5 text-rose-main"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-medium mb-1">Danh mục</p>
                        <p class="text-lg font-semibold text-gray-dark" id="modalDanhMucName"></p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-rose-pastel/50">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-pastel to-rose-light rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <i data-lucide="package" class="w-5 h-5 text-rose-main"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-medium mb-1">Số sản phẩm sẽ bị xóa</p>
                            <p class="text-2xl font-bold text-rose-main" id="modalSanPhamCount"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-start gap-3 bg-cream rounded-xl p-4 border border-cream-light">
                <i data-lucide="info" class="w-5 h-5 text-rose-main flex-shrink-0 mt-0.5"></i>
                <p class="text-sm text-gray-dark leading-relaxed">
                    <span class="font-semibold">Lưu ý:</span> Hành động này không thể hoàn tác. Tất cả sản phẩm thuộc danh mục sẽ bị xóa vĩnh viễn và không thể khôi phục.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 pb-6 flex items-center gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-light text-gray-dark rounded-2xl hover:bg-gray-medium transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Hủy bỏ</span>
            </button>
            <form id="deleteForm" action="" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-rose-main to-rose-dark text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    <span>Xóa danh mục</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="bulkModalContent">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-rose-pastel to-pink-pastel p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="trash-2" class="w-7 h-7 text-rose-main"></i>
                </div>
                <div>
                    <h3 class="text-xl font-serif font-bold text-gray-dark">Xóa nhiều danh mục</h3>
                    <p class="text-sm text-gray-medium">Bạn có chắc muốn xóa các danh mục đã chọn?</p>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="bg-gradient-to-br from-rose-pastel/50 to-pink-pastel/50 rounded-2xl p-5 mb-6 border border-rose-pastel">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-rose-pastel to-rose-light rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <i data-lucide="list" class="w-5 h-5 text-rose-main"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-medium mb-1">Số danh mục sẽ bị xóa</p>
                        <p class="text-2xl font-bold text-rose-main" id="bulkDeleteCount">0</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-start gap-3 bg-cream rounded-xl p-4 border border-cream-light">
                <i data-lucide="info" class="w-5 h-5 text-rose-main flex-shrink-0 mt-0.5"></i>
                <p class="text-sm text-gray-dark leading-relaxed">
                    <span class="font-semibold">Lưu ý:</span> Hành động này không thể hoàn tác. Tất cả danh mục và sản phẩm thuộc các danh mục sẽ bị xóa vĩnh viễn và không thể khôi phục.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 pb-6 flex items-center gap-4">
            <button onclick="closeBulkDeleteModal()" class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-light text-gray-dark rounded-2xl hover:bg-gray-medium transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Hủy bỏ</span>
            </button>
            <form id="bulkDeleteForm" action="/admin/danh-muc/bulk-delete" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-rose-main to-rose-dark text-white rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    <span>Xóa danh mục</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    let deleteId = null;
    let selectedIds = [];

    // Handle create modal
    function showCreateModal() {
        const modal = document.getElementById('createModal');
        const content = document.getElementById('createModalContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCreateModal() {
        const modal = document.getElementById('createModal');
        const content = document.getElementById('createModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('createForm').reset();
        }, 300);
    }

    // Handle individual delete
    function showDeleteModal(id, name, sanPhamCount) {
        deleteId = id;
        document.getElementById('modalDanhMucName').textContent = name;
        document.getElementById('modalSanPhamCount').textContent = sanPhamCount;
        document.getElementById('deleteForm').action = '/admin/danh-muc/' + id + '/delete';
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        deleteId = null;
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Handle bulk delete
    function showBulkDeleteModal() {
        const checkboxes = document.querySelectorAll('.danh-muc-checkbox:checked');
        selectedIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            showToast('Vui lòng chọn ít nhất một danh mục để xóa', 'error');
            return;
        }
        
        document.getElementById('bulkDeleteCount').textContent = selectedIds.length;
        document.getElementById('bulkDeleteIds').value = selectedIds.join(',');
        
        const modal = document.getElementById('bulkDeleteModal');
        const content = document.getElementById('bulkModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeBulkDeleteModal() {
        selectedIds = [];
        document.getElementById('bulkDeleteModal').classList.add('hidden');
        document.getElementById('bulkModalContent').classList.remove('scale-100', 'opacity-100');
        document.getElementById('bulkModalContent').classList.add('scale-95', 'opacity-0');
    }
    
    // Handle checkbox selection
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.danh-muc-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateSelectedCount();
            });
        });
        
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.danh-muc-checkbox:checked');
            selectedCount.textContent = checked.length;
            
            if (checked.length > 0) {
                bulkDeleteBtn.classList.remove('hidden');
            } else {
                bulkDeleteBtn.classList.add('hidden');
            }
        }
    });
    
    // Close modals when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBulkDeleteModal();
        }
    });

    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeBulkDeleteModal();
            closeCreateModal();
        }
    });
</script>
@endsection
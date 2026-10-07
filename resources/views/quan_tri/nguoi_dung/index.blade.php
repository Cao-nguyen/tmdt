@extends('layouts.admin')

@section('tieude_page')
Quản lý người dùng
@endsection

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-dark">Quản lý người dùng</h1>
            <p class="text-gray-medium mt-1">Danh sách người dùng hệ thống</p>
        </div>
        <div class="flex gap-3">
            <button onclick="showBulkDeleteModal()" id="bulkDeleteBtn" class="hidden inline-flex items-center gap-2 px-6 py-3 rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium" style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); color: white;">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
                <span>Xóa đã chọn (<span id="selectedCount">0</span>)</span>
            </button>
            <button onclick="showCreateModal()" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl hover:shadow-lg hover:scale-105 transition-all font-medium" style="background: linear-gradient(90deg, #f43f5e 0%, #ec4899 100%); color: white;">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                <span>Thêm người dùng</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-light">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-light cursor-pointer">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Thông tin</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Vai trò</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-dark uppercase tracking-wide">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-dark uppercase tracking-wide">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="user-checkbox w-4 h-4 rounded border-gray-light cursor-pointer" value="{{ $user->id }}" onchange="updateSelectedCount()">
                        </td>
                        <td class="px-4 py-4 text-xs text-gray-dark font-semibold">{{ $user->id }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                @if($user->anh_dai_dien)
                                    <img src="{{ asset($user->anh_dai_dien) }}" alt="{{ $user->ho_ten }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-br from-rose-pastel to-pink-pastel rounded-lg flex items-center justify-center text-rose-main font-bold text-sm flex-shrink-0">
                                        {{ substr($user->ho_ten, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-dark truncate">{{ $user->ho_ten }}</p>
                                    <p class="text-xs text-gray-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-xs text-gray-dark truncate">{{ $user->email }}</td>
                        <td class="px-4 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-pastel text-purple-main rounded text-xs font-semibold">
                                    <i data-lucide="shield" class="w-3 h-3"></i>
                                    Admin
                                </span>
                            @elseif($user->role === 'nhan_vien')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-pastel text-blue-main rounded text-xs font-semibold">
                                    <i data-lucide="briefcase" class="w-3 h-3"></i>
                                    Nhân viên
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-pastel text-green-main rounded text-xs font-semibold">
                                    <i data-lucide="user" class="w-3 h-3"></i>
                                    Khách hàng
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-pastel text-green-main rounded text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    Đã xác minh
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-pastel text-yellow-main rounded text-xs font-semibold">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    Chưa xác minh
                                </span>
                            @endif
                            <button onclick="toggleEmailVerification({{ $user->id }}, {{ $user->email_verified_at ? 'false' : 'true' }})" class="ml-1 w-6 h-6 bg-blue-pastel text-blue-main rounded flex items-center justify-center hover:bg-blue-light transition-colors" title="Đổi trạng thái">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                            </button>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="showEditModal({{ $user->id }})" class="w-8 h-8 bg-blue-pastel text-blue-main rounded-lg flex items-center justify-center hover:bg-blue-light transition-colors" title="Sửa">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="showDeleteModal({{ $user->id }}, '{{ $user->ho_ten }}')" class="w-8 h-8 bg-red-pastel text-red-main rounded-lg flex items-center justify-center hover:bg-red-light transition-colors" title="Xóa">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-light rounded-full flex items-center justify-center">
                                    <i data-lucide="users" class="w-8 h-8 text-gray-medium"></i>
                                </div>
                                <p class="text-gray-medium">Chưa có người dùng nào</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="userModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-light flex items-center justify-between bg-white">
            <h2 class="text-xl font-bold text-gray-dark" id="modalTitle">Thêm người dùng mới</h2>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-light transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-medium"></i>
            </button>
        </div>

        <!-- Form -->
        <div class="p-6 overflow-y-auto flex-1">
            <form id="userForm" action="/admin/nguoi-dung" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="user_id" id="userId" value="">

                <!-- Tên -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Họ tên <span class="text-red-main">*</span></label>
                    <input type="text" name="name" id="userName" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="Nhập họ tên">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Email <span class="text-red-main">*</span></label>
                    <input type="email" name="email" id="userEmail" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="email@example.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Mật khẩu <span class="text-red-main">*</span></label>
                    <input type="password" name="password" id="userPassword" autocomplete="new-password" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm" placeholder="Nhập mật khẩu">
                    <p class="text-xs text-gray-medium mt-1" id="passwordHint">Chỉ cần nhập khi tạo mới hoặc muốn đổi mật khẩu</p>
                </div>

                <!-- Vai trò -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Vai trò <span class="text-red-main">*</span></label>
                    <select name="role" id="userRole" required class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                        <option value="khach_hang">Khách hàng</option>
                        <option value="nhan_vien">Nhân viên</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Trạng thái xác minh email -->
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-1.5">Xác minh email</label>
                    <select name="email_verified" id="emailVerified" class="w-full px-3 py-2.5 border border-gray-light rounded-lg focus:ring-2 focus:ring-rose-main focus:border-transparent text-sm">
                        <option value="">Chưa xác minh</option>
                        <option value="1">Đã xác minh</option>
                    </select>
                </div>

                <!-- Footer buttons inside form -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-light">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-light rounded-lg text-gray-dark hover:bg-gray-light transition-colors text-sm font-medium">
                        Hủy
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-gradient-to-r from-rose-main to-pink-main text-white rounded-lg hover:shadow-lg transition-all text-sm font-medium flex items-center gap-2">
                        <svg id="submitSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="submitBtnText">Tạo người dùng</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe4e4 100%);">
        <!-- Header -->
        <div class="px-6 py-4 border-b" style="background: linear-gradient(90deg, #fecaca 0%, #fca5a5 100%); border-color: #fca5a5;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5" style="color: #dc2626;"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: #1f2937;">Xác nhận xóa</h3>
                </div>
                <button onclick="closeDeleteModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/50 transition-colors">
                    <i data-lucide="x" class="w-5 h-5" style="color: #1f2937;"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="mb-2" style="color: #1f2937;">Bạn có chắc chắn muốn xóa người dùng này?</p>
            <div class="rounded-xl p-4 mb-4" style="background: #f3f4f6; border: 1px solid #e5e7eb;">
                <p class="font-semibold" id="deleteUserName" style="color: #1f2937;"></p>
            </div>
            <div class="rounded-xl p-4" style="background: #fecaca; border: 1px solid #fca5a5;">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: #dc2626;"></i>
                    <p class="text-sm" style="color: #1f2937;">Hành động này không thể hoàn tác. Tất cả dữ liệu của người dùng sẽ bị xóa vĩnh viễn.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex items-center justify-end gap-3" style="background: #f3f4f6; border-color: #e5e7eb;">
            <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 border rounded-xl text-sm font-medium" style="border-color: #e5e7eb; color: #1f2937;">
                Hủy
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="user_id" id="deleteUserId" value="">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2" style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); color: white;">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Xóa người dùng</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="bulkDeleteModalContent" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe4e4 100%);">
        <!-- Header -->
        <div class="px-6 py-4 border-b" style="background: linear-gradient(90deg, #fecaca 0%, #fca5a5 100%); border-color: #fca5a5;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5" style="color: #dc2626;"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: #1f2937;">Xác nhận xóa hàng loạt</h3>
                </div>
                <button onclick="closeBulkDeleteModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/50 transition-colors">
                    <i data-lucide="x" class="w-5 h-5" style="color: #1f2937;"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="mb-4" style="color: #1f2937;">Bạn có chắc chắn muốn xóa <span id="bulkDeleteCount" class="font-bold text-lg" style="color: #dc2626;">0</span> người dùng?</p>
            <div class="rounded-xl p-4 mb-4" style="background: #fecaca; border: 1px solid #fca5a5;">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: #dc2626;"></i>
                    <p class="text-sm" style="color: #1f2937;">Hành động này không thể hoàn tác. Tất cả dữ liệu của người dùng sẽ bị xóa vĩnh viễn.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex items-center justify-end gap-3" style="background: #f3f4f6; border-color: #e5e7eb;">
            <button type="button" onclick="closeBulkDeleteModal()" class="px-6 py-2.5 border rounded-xl text-sm font-medium" style="border-color: #e5e7eb; color: #1f2937;">
                Hủy
            </button>
            <form id="bulkDeleteForm" action="/admin/nguoi-dung/bulk-delete" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds" value="">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2" style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); color: white;">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Xóa tất cả</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="flex items-center gap-3 px-6 py-4 rounded-xl shadow-lg" id="toastContent">
        <i data-lucide="check-circle" class="w-5 h-5" id="toastIcon"></i>
        <span class="text-sm font-medium" id="toastMessage"></span>
    </div>
</div>

<script>
// Modal functions
let selectedIds = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const count = checkboxes.length;
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');

    selectedCount.textContent = count;

    if (count > 0) {
        bulkDeleteBtn.classList.remove('hidden');
    } else {
        bulkDeleteBtn.classList.add('hidden');
    }
}

function showBulkDeleteModal() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    selectedIds = Array.from(checkboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        showToast('Vui lòng chọn ít nhất một người dùng để xóa', 'error');
        return;
    }

    document.getElementById('bulkDeleteCount').textContent = selectedIds.length;
    document.getElementById('bulkDeleteIds').value = selectedIds.join(',');

    const modal = document.getElementById('bulkDeleteModal');
    const content = document.getElementById('bulkDeleteModalContent');

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeBulkDeleteModal() {
    selectedIds = [];
    document.getElementById('bulkDeleteModal').classList.add('hidden');
    const content = document.getElementById('bulkDeleteModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
}

// Handle checkbox selection
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
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
        const checked = document.querySelectorAll('.user-checkbox:checked');
        selectedCount.textContent = checked.length;

        if (checked.length > 0) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
        }
    }
});

function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'Thêm người dùng mới';
    document.getElementById('submitBtnText').textContent = 'Tạo người dùng';
    document.getElementById('userForm').action = '/admin/nguoi-dung';
    document.getElementById('userForm').method = 'POST';
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('passwordHint').textContent = 'Chỉ cần nhập khi tạo mới hoặc muốn đổi mật khẩu';
    document.getElementById('userRole').value = 'user';
    document.getElementById('userModal').classList.remove('hidden');
}

function showEditModal(id) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang tải...';

    fetch(`/admin/nguoi-dung/${id}/data`)
        .then(response => response.json())
        .then(data => {
            console.log('User data:', data);

            document.getElementById('modalTitle').textContent = 'Sửa thông tin người dùng';
            document.getElementById('submitBtnText').textContent = 'Cập nhật';
            document.getElementById('userForm').action = `/admin/nguoi-dung/${id}/update`;
            document.getElementById('userForm').method = 'POST';
            document.getElementById('userId').value = data.data.id;
            document.getElementById('userName').value = data.data.name;
            document.getElementById('userEmail').value = data.data.email;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('passwordHint').textContent = 'Để trống nếu không muốn đổi mật khẩu';
            document.getElementById('userRole').value = data.data.role;
            document.getElementById('emailVerified').value = data.data.email_verified_at ? '1' : '';
            document.getElementById('userModal').classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = originalText;
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = originalText;
            showToast('Có lỗi xảy ra', 'error');
        });
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.getElementById('userForm').reset();
}

function showDeleteModal(id, name) {
    document.getElementById('deleteUserId').value = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/nguoi-dung/${id}/delete`;
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Form submission
function submitForm() {
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitBtnText = document.getElementById('submitBtnText');
    const originalText = submitBtnText.textContent;

    submitBtn.disabled = true;
    submitSpinner.classList.remove('hidden');
    submitBtnText.textContent = 'Đang xử lý...';

    fetch(form.action, {
        method: form.method,
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
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitSpinner.classList.add('hidden');
        submitBtnText.textContent = originalText;
    });
}

// Delete form submission
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xóa...';

    const formData = new FormData(form);

    fetch(form.action, {
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
            closeDeleteModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Bulk delete form submission
document.getElementById('bulkDeleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xóa...';

    const formData = new FormData(form);

    fetch('/admin/nguoi-dung/bulk-delete', {
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
            closeBulkDeleteModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastContent = document.getElementById('toastContent');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');

    toastMessage.textContent = message;

    if (type === 'success') {
        toastContent.className = 'flex items-center gap-3 px-6 py-4 rounded-xl shadow-lg bg-green-main text-white';
        toastIcon.setAttribute('data-lucide', 'check-circle');
    } else {
        toastContent.className = 'flex items-center gap-3 px-6 py-4 rounded-xl shadow-lg bg-red-main text-white';
        toastIcon.setAttribute('data-lucide', 'alert-circle');
    }

    lucide.createIcons();
    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Toggle email verification
function toggleEmailVerification(id, verified) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('verified', verified);

    fetch(`/admin/nguoi-dung/${id}/toggle-verification`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
    });
}

// Form submission - show loading state
document.getElementById('userForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('submitSpinner');
    const btnText = document.getElementById('submitBtnText');

    submitBtn.disabled = true;
    spinner.classList.remove('hidden');
    btnText.textContent = 'Đang lưu...';
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
    }
});

// Close modal on click outside
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Initialize icons
lucide.createIcons();
</script>
@endsection

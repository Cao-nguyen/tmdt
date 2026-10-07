<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\UserController;

// Route trang chủ
Route::get('/', [TrangChuController::class, 'index'])->name('trang-chu');

// Routes Frontend - Người dùng

// Routes sản phẩm
Route::get('/san-pham', [SanPhamController::class, 'index'])->name('san-pham.index');
Route::get('/san-pham/{slug}', [SanPhamController::class, 'show'])->name('san-pham.show');
Route::post('/san-pham/{id}/danh-gia', [SanPhamController::class, 'danhGia'])->name('san-pham.danh-gia')->middleware('auth');
Route::post('/san-pham/{id}/cap-nhat-danh-gia', [SanPhamController::class, 'capNhatDanhGia'])->name('san-pham.cap-nhat-danh-gia')->middleware('auth');
Route::post('/san-pham/{id}/xoa-danh-gia', [SanPhamController::class, 'xoaDanhGia'])->name('san-pham.xoa-danh-gia')->middleware('auth');

// Routes giỏ hàng
Route::get('/gio-hang', [GioHangController::class, 'index'])->name('gio-hang.index');
Route::post('/gio-hang/them', [GioHangController::class, 'them'])->name('gio-hang.them');
Route::post('/gio-hang/cap-nhat', [GioHangController::class, 'capNhat'])->name('gio-hang.cap-nhat');
Route::post('/gio-hang/xoa', [GioHangController::class, 'xoa'])->name('gio-hang.xoa');
Route::post('/gio-hang/xoa-tat-ca', [GioHangController::class, 'xoaTatCa'])->name('gio-hang.xoa-tat-ca');
Route::get('/tim-kiem', [GioHangController::class, 'timKiem'])->name('tim-kiem');
Route::get('/thanh-toan', [GioHangController::class, 'thanhToan'])->name('thanh-toan')->middleware('auth');
Route::post('/dat-hang', [GioHangController::class, 'datHang'])->name('dat-hang')->middleware('auth');
Route::get('/mua-ngay/{id}', [GioHangController::class, 'muaNgay'])->name('mua-ngay')->middleware('auth');
Route::post('/dat-hang-ngay', [GioHangController::class, 'datHangNgay'])->name('dat-hang-ngay')->middleware('auth');
Route::get('/payos/success', [GioHangController::class, 'payOSSuccess'])->name('payos-success')->middleware('auth');
Route::get('/payos/cancel', [GioHangController::class, 'payOSCancel'])->name('payos-cancel')->middleware('auth');
Route::get('/thanh-toan-ngan-hang', [GioHangController::class, 'thanhToanNganHang'])->name('thanh-toan-ngan-hang')->middleware('auth');
Route::post('/xac-nhan-thanh-toan-ngan-hang', [GioHangController::class, 'xacNhanThanhToanNganHang'])->name('xac-nhan-thanh-toan-ngan-hang')->middleware('auth');
Route::get('/don-hang', [GioHangController::class, 'donHang'])->name('don-hang')->middleware('auth');
Route::post('/dat-hang-tien-mat', [GioHangController::class, 'datHangTienMat'])->name('dat-hang-tien-mat')->middleware('auth');

// Routes tài khoản
Route::get('/tai-khoan', [UserController::class, 'index'])->name('tai-khoan.index')->middleware('auth');
Route::post('/tai-khoan/cap-nhat', [UserController::class, 'update'])->name('tai-khoan.update')->middleware('auth');
Route::post('/tai-khoan/cap-nhat-avatar', [UserController::class, 'updateAvatar'])->name('tai-khoan.update-avatar')->middleware('auth');

// Routes danh mục (Frontend sẽ dùng qua san-pham filter)

// Routes bài viết (Blog)
Route::get('/bai-viet', [BaiVietController::class, 'index'])->name('bai-viet.index');
Route::get('/bai-viet/{slug}', [BaiVietController::class, 'show'])->name('bai-viet.show');
Route::post('/thanh-toan', [GioHangController::class, 'processCheckout'])->name('thanh-toan.process');

// Routes tài khoản người dùng
Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [UserController::class, 'index'])->name('tai-khoan.index');
    Route::post('/tai-khoan/cap-nhat', [UserController::class, 'update'])->name('tai-khoan.update');
});

// Routes Authentication
Route::get('/login', [AuthController::class, 'hienThiFormDangNhap'])->name('login');
Route::post('/login', [AuthController::class, 'xuLyDangNhap'])->name('login.post');
Route::get('/dang-ky', [AuthController::class, 'hienThiFormDangKy'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'xuLyDangKy'])->name('register.post');
Route::post('/logout', [AuthController::class, 'xuLyDangXuat'])->name('logout');

// Routes trang tĩnh
Route::get('/ve-chung-toi', function () {
    return view('pages.about');
})->name('ve-chung-toi');

Route::get('/lien-he', function () {
    return view('pages.contact');
})->name('lien-he');

Route::get('/chinh-sach', function () {
    return view('pages.policies');
})->name('chinh-sach');

// Route test kết nối với Supabase
Route::get('/test-database', function () {
    return response()->json([
        'trang_thai' => 'success',
        'thong_bao' => 'Routes loaded successfully!',
        'thoi_gian' => now()
    ]);
})->name('test-database');

// Route trang quản trị (chỉ cho admin)
Route::get('/admin', [AdminController::class, 'dashboard'])
     ->name('quan-tri.dashboard');

// Routes quản lý người dùng
Route::get('/admin/nguoi-dung', [AdminController::class, 'nguoiDungIndex'])
     ->name('quan-tri.nguoi-dung.index');
Route::get('/admin/nguoi-dung/{id}/data', [AdminController::class, 'nguoiDungData'])
     ->name('quan-tri.nguoi-dung.data');
Route::post('/admin/nguoi-dung', [AdminController::class, 'nguoiDungStore'])
     ->name('quan-tri.nguoi-dung.store');
Route::post('/admin/nguoi-dung/{id}/update', [AdminController::class, 'nguoiDungUpdate'])
     ->name('quan-tri.nguoi-dung.update');
Route::post('/admin/nguoi-dung/{id}/delete', [AdminController::class, 'nguDungDestroy'])
     ->name('quan-tri.nguoi-dung.destroy');
Route::post('/admin/nguoi-dung/{id}/toggle-verification', [AdminController::class, 'nguoiDungToggleVerification'])
     ->name('quan-tri.nguoi-dung.toggle-verification');
Route::post('/admin/nguoi-dung/bulk-delete', [AdminController::class, 'nguoiDungBulkDelete'])
     ->name('quan-tri.ngu-dung.bulk-delete');

// Routes quản lý sản phẩm
Route::get('/admin/san-pham', [AdminController::class, 'sanPhamIndex'])
     ->name('quan-tri.san-pham.index');
Route::get('/admin/san-pham/{id}/data', [AdminController::class, 'sanPhamData'])
     ->name('quan-tri.san-pham.data');
Route::post('/admin/san-pham', [AdminController::class, 'sanPhamStore'])
     ->name('quan-tri.san-pham.store');
Route::post('/admin/san-pham/{id}/update', [AdminController::class, 'sanPhamUpdate'])
     ->name('quan-tri.san-pham.update');
Route::post('/admin/san-pham/{id}/delete', [AdminController::class, 'sanPhamDestroy'])
     ->name('quan-tri.san-pham.destroy');

// Routes quản lý danh mục
Route::get('/admin/danh-muc', [AdminController::class, 'danhMucIndex'])
     ->name('quan-tri.danh-muc.index');
Route::get('/admin/danh-muc/{id}/data', [AdminController::class, 'danhMucData'])
     ->name('quan-tri.danh-muc.data');
Route::get('/admin/danh-muc/create', [AdminController::class, 'danhMucCreate'])
     ->name('quan-tri.danh-muc.create');
Route::post('/admin/danh-muc', [AdminController::class, 'danhMucStore'])
     ->name('quan-tri.danh-muc.store');
Route::get('/admin/danh-muc/{id}/edit', [AdminController::class, 'danhMucEdit'])
     ->name('quan-tri.danh-muc.edit');
Route::post('/admin/danh-muc/{id}/update', [AdminController::class, 'danhMucUpdate'])
     ->name('quan-tri.danh-muc.update');
Route::post('/admin/danh-muc/{id}/delete', [AdminController::class, 'danhMucDestroy'])
     ->name('quan-tri.danh-muc.destroy');
Route::post('/admin/danh-muc/bulk-delete', [AdminController::class, 'danhMucBulkDelete'])
     ->name('quan-tri.danh-muc.bulk-delete');

// Routes quản lý đơn hàng
Route::get('/admin/don-hang', [AdminController::class, 'donHangIndex'])
     ->name('admin.don-hang.index');
Route::get('/admin/don-hang/{id}', [AdminController::class, 'donHangShow'])
     ->name('admin.don-hang.show');
Route::post('/admin/don-hang/{id}/update-status', [AdminController::class, 'donHangUpdateStatus'])
     ->name('admin.don-hang.update-status');

Route::delete('/admin/don-hang/{id}', [AdminController::class, 'donHangDelete'])
     ->name('admin.don-hang.delete');

// Routes quản lý cài đặt
Route::get('/admin/cai-dat', [AdminController::class, 'caiDatIndex'])
     ->name('admin.cai-dat.index');
Route::post('/admin/cai-dat', [AdminController::class, 'caiDatUpdate'])
     ->name('admin.cai-dat.update');
Route::post('/admin/cai-dat/delete-logo', [AdminController::class, 'deleteLogo'])
     ->name('admin.cai-dat.delete-logo');

// Routes quản lý bài viết
Route::get('/admin/bai-viet', [AdminController::class, 'baiVietIndex'])
     ->name('admin.bai-viet.index');
Route::get('/admin/bai-viet/{id}/data', [AdminController::class, 'baiVietData'])
     ->name('admin.bai-viet.data');
Route::post('/admin/bai-viet', [AdminController::class, 'baiVietStore'])
     ->name('admin.bai-viet.store');
Route::post('/admin/bai-viet/{id}/update', [AdminController::class, 'baiVietUpdate'])
     ->name('admin.bai-viet.update');
Route::post('/admin/bai-viet/{id}/delete', [AdminController::class, 'baiVietDestroy'])
     ->name('admin.bai-viet.destroy');
Route::post('/admin/bai-viet/upload-image', [AdminController::class, 'baiVietUploadImage'])
     ->name('admin.bai-viet.upload-image');

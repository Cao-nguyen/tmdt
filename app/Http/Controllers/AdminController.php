<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\HinhAnh;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập trang này!');
        }
        
        return view('quan_tri.dashboard');
    }
    
    /**
     * Hàm hiển thị trang quản lý người dùng
     */
    public function nguoiDungIndex()
    {
        if (!auth()->check()) {
            Log::error('nguoiDungIndex - User not authenticated');
            return redirect('/login')->with('thong_bao_loi', 'Bạn cần đăng nhập!');
        }

        if (auth()->user()->role !== 'admin') {
            Log::error('nguoiDungIndex - User role: ' . auth()->user()->role);
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        Log::info('nguoiDungIndex - Loading users');
        $users = User::orderBy('created_at', 'desc')->get();
        Log::info('nguoiDungIndex - Users count: ' . $users->count());
        Log::info('nguoiDungIndex - Users: ' . json_encode($users->pluck('ho_ten')));
        return view('quan_tri.nguoi_dung.index', compact('users'));
    }

    /**
     * Hàm trả về dữ liệu người dùng dưới dạng JSON
     */
    public function nguoiDungData($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->ho_ten,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
            ]
        ]);
    }

    /**
     * Hàm tạo người dùng mới
     */
    public function nguoiDungStore(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->back()->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,nhan_vien,khach_hang',
            ]);

            $user = User::create([
                'ho_ten' => $validated['name'],
                'email' => $validated['email'],
                'ten_dang_nhap' => 'user_' . time() . '_' . rand(1000, 9999),
                'mat_khau' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'trang_thai' => true,
            ]);

            return redirect()->back()->with('thong_bao', 'Đã tạo người dùng thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra khi tạo người dùng: ' . $e->getMessage());
        }
    }

    /**
     * Hàm cập nhật người dùng
     */
    public function nguoiDungUpdate(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
            }
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $user = User::find($id);
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
            }
            return redirect()->back()->with('thong_bao_loi', 'Không tìm thấy người dùng');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,nhan_vien,khach_hang',
            'email_verified' => 'nullable|boolean',
        ]);

        try {
            $user->ho_ten = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if (!empty($validated['password'])) {
                $user->mat_khau = bcrypt($validated['password']);
            }

            // Cập nhật trạng thái xác minh email
            if (isset($validated['email_verified'])) {
                if ($validated['email_verified']) {
                    $user->email_verified_at = now();
                } else {
                    $user->email_verified_at = null;
                }
            }

            $user->save();

            return redirect()->back()->with('thong_bao', 'Đã cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra khi cập nhật người dùng: ' . $e->getMessage());
        }
    }

    /**
     * Hàm xóa người dùng
     */
    public function nguoiDungDestroy(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
            }
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $user = User::find($id);
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
            }
            return redirect()->back()->with('thong_bao_loi', 'Không tìm thấy người dùng');
        }

        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Không thể xóa chính mình'], 400);
            }
            return redirect()->back()->with('thong_bao_loi', 'Không thể xóa chính mình');
        }

        try {
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa người dùng thành công!']);
        } catch (\Exception $e) {
            Log::error('Lỗi xóa người dùng: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa người dùng'], 500);
        }
    }

    /**
     * Hàm toggle trạng thái xác minh email
     */
    public function nguoiDungToggleVerification(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
        }

        try {
            $verified = $request->input('verified');
            if ($verified) {
                $user->email_verified_at = now();
            } else {
                $user->email_verified_at = null;
            }
            $user->save();

            return response()->json(['success' => true, 'message' => 'Đã cập nhật trạng thái xác minh email!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm xóa nhiều người dùng cùng lúc
     */
    public function nguoiDungBulkDelete(Request $request)
    {
        Log::info('nguoiDungBulkDelete - Request data:', $request->all());

        if (!auth()->check() || auth()->user()->role !== 'admin') {
            Log::error('nguoiDungBulkDelete - Unauthorized');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
            }
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $userIds = $request->input('user_ids') ?? $request->input('ids');
        Log::info('nguoiDungBulkDelete - User IDs: ' . $userIds);

        if (!$userIds) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Không có người dùng nào được chọn'], 400);
            }
            return redirect()->back()->with('thong_bao_loi', 'Không có người dùng nào được chọn');
        }

        $ids = explode(',', $userIds);
        $deletedCount = 0;
        $skippedCount = 0;

        try {
            foreach ($ids as $id) {
                $user = User::find($id);
                if (!$user) {
                    $skippedCount++;
                    continue;
                }

                // Không cho phép xóa chính mình
                if ($user->id === auth()->id()) {
                    $skippedCount++;
                    continue;
                }

                $user->delete();
                $deletedCount++;
            }

            $message = "Đã xóa {$deletedCount} người dùng thành công!";
            if ($skippedCount > 0) {
                $message .= " Đã bỏ qua {$skippedCount} người dùng.";
            }

            Log::info('nguoiDungBulkDelete - Success: ' . $message);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Lỗi xóa hàng loạt người dùng: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa người dùng'], 500);
        }
    }
    
    /**
     * Hàm hiển thị trang quản lý sản phẩm
     */
    public function sanPhamIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        // Eager loading để tránh N+1 query
        $sanPhams = SanPham::with('danhMuc', 'hinhAnhs')->orderBy('created_at', 'desc')->get();
        return view('quan_tri.san_pham.index', compact('sanPhams'));
    }

    /**
     * Hàm trả về dữ liệu sản phẩm dưới dạng JSON
     */
    public function sanPhamData($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        try {
            $sanPham = SanPham::with('danhMuc', 'hinhAnhs')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $sanPham
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
        }
    }

    /**
     * Hàm lưu sản phẩm mới
     */
    public function sanPhamStore(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
            }
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        try {
            $rules = [
                'ten_san_pham' => 'required|string|max:255',
                'danh_muc_id' => 'required|exists:danh_mucs,id',
                'mo_ta_ngan' => 'nullable|string',
                'mo_ta_chi_tiet' => 'nullable|string',
                'gia_ban' => 'required|numeric|min:0',
                'loai_giam_gia' => 'nullable|in:vnđ,phan_tram',
                'gia_khuyen_mai' => 'nullable|numeric|min:0',
                'so_luong' => 'required|integer|min:0',
            ];

            // Chỉ validate ảnh khi có upload mới
            if ($request->hasFile('hinh_anhs')) {
                $rules['hinh_anhs'] = 'nullable|array';
                $rules['hinh_anhs.*'] = 'image|mimes:jpeg,png,jpg,gif|max:5120';
            }

            $request->validate($rules);

            $sanPham = new SanPham();
            $sanPham->ten_san_pham = $request->ten_san_pham;
            $sanPham->slug = Str::slug($request->ten_san_pham);
            $sanPham->danh_muc_id = $request->danh_muc_id;
            $sanPham->mo_ta_ngan = $request->mo_ta_ngan;
            $sanPham->mo_ta_chi_tiet = $request->mo_ta_chi_tiet;
            $sanPham->gia_ban = $request->gia_ban;

            // Xử lý giảm giá theo loại
            if ($request->has('gia_khuyen_mai') && $request->gia_khuyen_mai > 0) {
                if ($request->loai_giam_gia === 'phan_tram') {
                    // Tính giá giảm theo phần trăm
                    $giamGia = ($request->gia_ban * $request->gia_khuyen_mai) / 100;
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $giamGia;
                } else {
                    // Giảm theo số tiền VNĐ
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $request->gia_khuyen_mai;
                }
            } else {
                $sanPham->gia_khuyen_mai = null;
            }

            $sanPham->so_luong = $request->so_luong;
            $sanPham->trang_thai = $request->has('trang_thai') ? true : false;
            $sanPham->save();

            // Upload ảnh lên Cloudinary (nếu có) - Optional
            if ($request->hasFile('hinh_anhs')) {
                try {
                    foreach ($request->file('hinh_anhs') as $index => $image) {
                        $uploadedFile = Cloudinary::upload($image->getRealPath(), [
                            'folder' => 'flower-shop/san-pham',
                            'transformation' => [
                                'quality' => 90,
                                'fetch_format' => null,
                            ]
                        ]);

                        $hinhAnh = new HinhAnh();
                        $hinhAnh->san_pham_id = $sanPham->id;
                        $hinhAnh->duong_dan = $uploadedFile->getSecurePath();
                        $hinhAnh->public_id = $uploadedFile->getPublicId();
                        $hinhAnh->anh_chinh = $index === 0;
                        $hinhAnh->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Cloudinary upload failed: ' . $e->getMessage());
                    // Không throw lỗi để vẫn tạo được sản phẩm nếu ảnh upload lỗi
                    // Sản phẩm vẫn được tạo, chỉ là không có ảnh
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã tạo sản phẩm mới thành công!'
                ]);
            }

            return redirect('/admin/san-pham')->with('thong_bao', 'Đã tạo sản phẩm mới thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: ' . implode(', ', Arr::flatten($e->errors()))
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hàm xóa sản phẩm
     */
    public function sanPhamDestroy($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        try {
            $sanPham = SanPham::findOrFail($id);

            // Xóa ảnh trên Cloudinary
            foreach ($sanPham->hinhAnhs as $hinhAnh) {
                try {
                    if ($hinhAnh->public_id) {
                        Cloudinary::destroy($hinhAnh->public_id);
                    }
                } catch (\Exception $e) {
                    Log::error('Cloudinary delete failed: ' . $e->getMessage());
                }
            }

            // Xóa sản phẩm (cascade sẽ xóa ảnh trong DB)
            $sanPham->delete();

            return redirect('/admin/san-pham')->with('thong_bao', 'Đã xóa sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect('/admin/san-pham')->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hàm cập nhật sản phẩm
     */
    public function sanPhamUpdate(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
            }
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        Log::info('SanPhamUpdate - Request data:', $request->all());

        try {
            $rules = [
                'ten_san_pham' => 'required|string|max:255',
                'danh_muc_id' => 'required|exists:danh_mucs,id',
                'mo_ta_ngan' => 'nullable|string',
                'mo_ta_chi_tiet' => 'nullable|string',
                'gia_ban' => 'required|numeric|min:0',
                'loai_giam_gia' => 'nullable|in:vnđ,phan_tram',
                'gia_khuyen_mai' => 'nullable|numeric|min:0',
                'so_luong' => 'required|integer|min:0',
            ];

            // Chỉ validate ảnh khi có upload mới
            if ($request->hasFile('hinh_anhs')) {
                $rules['hinh_anhs'] = 'nullable|array';
                $rules['hinh_anhs.*'] = 'image|mimes:jpeg,png,jpg,gif|max:5120';
            }

            $request->validate($rules);

            $sanPham = SanPham::findOrFail($id);
            $sanPham->ten_san_pham = $request->ten_san_pham;
            $sanPham->slug = Str::slug($request->ten_san_pham);
            $sanPham->danh_muc_id = $request->danh_muc_id;
            $sanPham->mo_ta_ngan = $request->mo_ta_ngan;
            $sanPham->mo_ta_chi_tiet = $request->mo_ta_chi_tiet;
            $sanPham->gia_ban = $request->gia_ban;

            // Xử lý giảm giá theo loại
            if ($request->has('gia_khuyen_mai') && $request->gia_khuyen_mai > 0) {
                if ($request->loai_giam_gia === 'phan_tram') {
                    // Tính giá giảm theo phần trăm
                    $giamGia = ($request->gia_ban * $request->gia_khuyen_mai) / 100;
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $giamGia;
                } else {
                    // Giảm theo số tiền VNĐ
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $request->gia_khuyen_mai;
                }
            } else {
                $sanPham->gia_khuyen_mai = null;
            }

            $sanPham->so_luong = $request->so_luong;
            $sanPham->trang_thai = $request->has('trang_thai') ? true : false;
            $sanPham->save();

            // Upload ảnh mới lên Cloudinary
            if ($request->hasFile('hinh_anhs')) {
                // Xóa ảnh cũ trên Cloudinary
                foreach ($sanPham->hinhAnhs as $hinhAnh) {
                    try {
                        if ($hinhAnh->public_id) {
                            Cloudinary::destroy($hinhAnh->public_id);
                        }
                    } catch (\Exception $e) {
                        Log::error('Cloudinary delete failed: ' . $e->getMessage());
                    }
                    $hinhAnh->delete();
                }

                // Upload ảnh mới
                foreach ($request->file('hinh_anhs') as $index => $image) {
                    $uploadedFile = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'flower-shop/san-pham',
                        'transformation' => [
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ]
                    ]);

                    $hinhAnh = new HinhAnh();
                    $hinhAnh->san_pham_id = $sanPham->id;
                    $hinhAnh->duong_dan = $uploadedFile->getSecurePath();
                    $hinhAnh->public_id = $uploadedFile->getPublicId();
                    $hinhAnh->anh_chinh = $index === 0;
                    $hinhAnh->save();
                }
            }

            $sanPham = SanPham::findOrFail($id);
            $sanPham->ten_san_pham = $request->ten_san_pham;
            $sanPham->slug = Str::slug($request->ten_san_pham);
            $sanPham->danh_muc_id = $request->danh_muc_id;
            $sanPham->mo_ta_ngan = $request->mo_ta_ngan;
            $sanPham->mo_ta_chi_tiet = $request->mo_ta_chi_tiet;
            $sanPham->gia_ban = $request->gia_ban;

            // Xử lý giảm giá theo loại
            if ($request->has('gia_khuyen_mai') && $request->gia_khuyen_mai > 0) {
                if ($request->loai_giam_gia === 'phan_tram') {
                    // Tính giá giảm theo phần trăm
                    $giamGia = ($request->gia_ban * $request->gia_khuyen_mai) / 100;
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $giamGia;
                } else {
                    // Giảm theo số tiền VNĐ
                    $sanPham->gia_khuyen_mai = $request->gia_ban - $request->gia_khuyen_mai;
                }
            } else {
                $sanPham->gia_khuyen_mai = null;
            }

            $sanPham->so_luong = $request->so_luong;
            $sanPham->trang_thai = $request->has('trang_thai') ? true : false;
            $sanPham->save();

            // Upload ảnh mới lên Cloudinary
            if ($request->hasFile('hinh_anhs')) {
                // Xóa ảnh cũ trên Cloudinary
                foreach ($sanPham->hinhAnhs as $hinhAnh) {
                    try {
                        if ($hinhAnh->public_id) {
                            Cloudinary::destroy($hinhAnh->public_id);
                        }
                    } catch (\Exception $e) {
                        Log::error('Cloudinary delete failed: ' . $e->getMessage());
                    }
                    $hinhAnh->delete();
                }

                // Upload ảnh mới
                foreach ($request->file('hinh_anhs') as $index => $image) {
                    $uploadedFile = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'flower-shop/san-pham',
                        'transformation' => [
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ]
                    ]);

                    $hinhAnh = new HinhAnh();
                    $hinhAnh->san_pham_id = $sanPham->id;
                    $hinhAnh->duong_dan = $uploadedFile->getSecurePath();
                    $hinhAnh->public_id = $uploadedFile->getPublicId();
                    $hinhAnh->anh_chinh = $index === 0;
                    $hinhAnh->save();
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật sản phẩm thành công!'
                ]);
            }

            return redirect('/admin/san-pham')->with('thong_bao', 'Đã cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Hàm hiển thị trang quản lý danh mục
     */
    public function danhMucIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        // Eager loading để tránh N+1 query
        $danhMucs = DanhMuc::with('sanPhams')->orderBy('sap_xep')->orderBy('created_at', 'desc')->get();
        return view('quan_tri.danh_muc.index', compact('danhMucs'));
    }

    /**
     * Hàm trả về dữ liệu danh mục dưới dạng JSON
     */
    public function danhMucData($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        try {
            $danhMuc = DanhMuc::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $danhMuc
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục'], 404);
        }
    }

    /**
     * Hàm hiển thị form tạo danh mục mới
     */
    public function danhMucCreate()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        return view('quan_tri.danh_muc.create');
    }
    
    /**
     * Hàm lưu danh mục mới
     */
    public function danhMucStore(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'sap_xep' => 'nullable|integer|min:0',
        ]);
        
        $danhMuc = new DanhMuc();
        $danhMuc->ten_danh_muc = $request->ten_danh_muc;
        $danhMuc->slug = Str::slug($request->ten_danh_muc);
        $danhMuc->mo_ta = $request->mo_ta;
        $danhMuc->sap_xep = $request->sap_xep ?? 0;
        $danhMuc->trang_thai = $request->has('trang_thai');
        $danhMuc->save();
        
        return redirect('/admin/danh-muc')->with('thong_bao', 'Đã tạo danh mục mới thành công!');
    }
    
    /**
     * Hàm hiển thị form chỉnh sửa danh mục
     */
    public function danhMucEdit($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        $danhMuc = DanhMuc::findOrFail($id);
        return view('quan_tri.danh_muc.edit', compact('danhMuc'));
    }
    
    /**
     * Hàm cập nhật danh mục
     */
    public function danhMucUpdate(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'sap_xep' => 'nullable|integer|min:0',
        ]);
        
        $danhMuc = DanhMuc::findOrFail($id);
        $danhMuc->ten_danh_muc = $request->ten_danh_muc;
        $danhMuc->slug = Str::slug($request->ten_danh_muc);
        $danhMuc->mo_ta = $request->mo_ta;
        $danhMuc->sap_xep = $request->sap_xep ?? 0;
        $danhMuc->trang_thai = $request->has('trang_thai');
        $danhMuc->save();
        
        return redirect('/admin/danh-muc')->with('thong_bao', 'Đã cập nhật danh mục thành công!');
    }
    
    /**
     * Hàm xóa danh mục
     */
    public function danhMucDestroy($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        $danhMuc = DanhMuc::findOrFail($id);
        
        // Xóa tất cả sản phẩm thuộc danh mục
        $danhMuc->sanPhams()->delete();
        
        // Xóa danh mục
        $danhMuc->delete();
        
        return redirect('/admin/danh-muc')->with('thong_bao', 'Đã xóa danh mục và tất cả sản phẩm thuộc danh mục thành công!');
    }
    
    /**
     * Hàm xóa nhiều danh mục cùng lúc
     */
    public function danhMucBulkDelete(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }
        
        $ids = explode(',', $request->ids);
        
        if (empty($ids) || empty($request->ids)) {
            return redirect('/admin/danh-muc')->with('thong_bao_loi', 'Vui lòng chọn danh mục để xóa!');
        }
        
        $deletedCount = 0;
        $deletedProductsCount = 0;
        
        foreach ($ids as $id) {
            $danhMuc = DanhMuc::find($id);
            if ($danhMuc) {
                $productCount = $danhMuc->sanPhams()->count();
                $danhMuc->sanPhams()->delete();
                $danhMuc->delete();
                $deletedCount++;
                $deletedProductsCount += $productCount;
            }
        }
        
        return redirect('/admin/danh-muc')->with('thong_bao', "Đã xóa {$deletedCount} danh mục và {$deletedProductsCount} sản phẩm thành công!");
    }
    
    /**
     * Hàm hiển thị trang quản lý đơn hàng
     */
    public function donHangIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $donHangs = \App\Models\DonHang::with('user')->orderBy('created_at', 'desc')->get();
        return view('quan_tri.don_hang.index', compact('donHangs'));
    }

    /**
     * Hàm hiển thị chi tiết đơn hàng
     */
    public function donHangShow($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $donHang = \App\Models\DonHang::with(['user', 'chiTietDonHangs.sanPham'])->find($id);
        if (!$donHang) {
            return redirect('/admin/don-hang')->with('thong_bao_loi', 'Không tìm thấy đơn hàng');
        }

        return view('quan_tri.don_hang.show', compact('donHang'));
    }

    /**
     * Hàm cập nhật trạng thái đơn hàng
     */
    public function donHangUpdateStatus(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $donHang = \App\Models\DonHang::with('chiTietDonHangs.sanPham')->find($id);
        if (!$donHang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        try {
            $validated = $request->validate([
                'trang_thai' => 'required|in:cho_xac_nhan,da_xac_nhan,dang_chuan_bi,dang_giao,da_giao,da_huy'
            ]);

            $trangThaiCu = $donHang->trang_thai;
            $trangThaiMoi = $validated['trang_thai'];

            $donHang->trang_thai = $trangThaiMoi;
            $donHang->save();

            // Khi đơn hàng đã giao, trừ tồn kho
            if ($trangThaiMoi === 'da_giao' && $trangThaiCu !== 'da_giao') {
                foreach ($donHang->chiTietDonHangs as $chiTiet) {
                    if ($chiTiet->sanPham) {
                        $sanPham = $chiTiet->sanPham;
                        $sanPham->so_luong -= $chiTiet->so_luong;
                        if ($sanPham->so_luong < 0) {
                            $sanPham->so_luong = 0;
                        }
                        $sanPham->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Đã cập nhật trạng thái đơn hàng!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Xóa đơn hàng
     */
    public function donHangDelete($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $donHang = \App\Models\DonHang::find($id);
        if (!$donHang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        try {
            // Xóa chi tiết đơn hàng trước
            \App\Models\ChiTietDonHang::where('don_hang_id', $id)->delete();
            
            // Xóa đơn hàng
            $donHang->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa đơn hàng!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm hiển thị trang cài đặt
     */
    public function caiDatIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        return view('quan_tri.cai_dat.index');
    }

    /**
     * Hàm cập nhật cài đặt
     */
    public function caiDatUpdate(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        try {
            $data = $request->all();
            unset($data['_token']);

            // Xử lý upload logo
            if ($request->hasFile('site_logo')) {
                $file = $request->file('site_logo');
                $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                \App\Models\Setting::set('site_logo', 'uploads/' . $fileName);
                unset($data['site_logo']);
            }

            foreach ($data as $key => $value) {
                \App\Models\Setting::set($key, $value);
            }

            return response()->json(['success' => true, 'message' => 'Đã cập nhật cài đặt!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm xóa logo
     */
    public function deleteLogo()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        try {
            $logoPath = \App\Models\Setting::get('site_logo');
            if ($logoPath && file_exists(public_path($logoPath))) {
                unlink(public_path($logoPath));
            }
            \App\Models\Setting::set('site_logo', '');

            return response()->json(['success' => true, 'message' => 'Đã xóa logo!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm hiển thị trang quản lý bài viết
     */
    public function baiVietIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/')->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $baiViets = \App\Models\BaiViet::orderBy('created_at', 'desc')->get();
        return view('quan_tri.bai_viet.index', compact('baiViets'));
    }

    /**
     * Hàm trả về dữ liệu bài viết dưới dạng JSON
     */
    public function baiVietData($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $baiViet = \App\Models\BaiViet::find($id);
        if (!$baiViet) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bài viết'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $baiViet->id,
                'tieu_de' => $baiViet->tieu_de,
                'slug' => $baiViet->slug,
                'mo_ta' => $baiViet->mo_ta,
                'noi_dung' => $baiViet->noi_dung,
                'hinh_anh' => $baiViet->hinh_anh,
                'trang_thai' => $baiViet->trang_thai,
            ]
        ]);
    }

    /**
     * Hàm tạo bài viết mới
     */
    public function baiVietStore(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->back()->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        try {
            $validated = $request->validate([
                'tieu_de' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:bai_viets',
                'noi_dung' => 'required|string',
                'trang_thai' => 'required|boolean',
            ]);

            // Lấy ảnh đầu tiên từ nội dung làm thumbnail
            $hinhAnh = '';
            if (preg_match('/<img[^>]+src="([^"]+)"/i', $validated['noi_dung'], $matches)) {
                $hinhAnh = $matches[1];
            }

            \App\Models\BaiViet::create([
                'user_id' => auth()->id(),
                'tieu_de' => $validated['tieu_de'],
                'slug' => $validated['slug'],
                'mo_ta' => '',
                'noi_dung' => $validated['noi_dung'],
                'hinh_anh' => $hinhAnh,
                'trang_thai' => $validated['trang_thai'],
            ]);

            return redirect()->back()->with('thong_bao', 'Đã tạo bài viết thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra khi tạo bài viết: ' . $e->getMessage());
        }
    }

    /**
     * Hàm cập nhật bài viết
     */
    public function baiVietUpdate(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->back()->with('thong_bao_loi', 'Bạn không có quyền truy cập!');
        }

        $baiViet = \App\Models\BaiViet::find($id);
        if (!$baiViet) {
            return redirect()->back()->with('thong_bao_loi', 'Không tìm thấy bài viết');
        }

        try {
            $validated = $request->validate([
                'tieu_de' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:bai_viets,slug,' . $id,
                'noi_dung' => 'required|string',
                'trang_thai' => 'required|boolean',
            ]);

            // Lấy ảnh đầu tiên từ nội dung làm thumbnail
            $hinhAnh = '';
            if (preg_match('/<img[^>]+src="([^"]+)"/i', $validated['noi_dung'], $matches)) {
                $hinhAnh = $matches[1];
            }

            $baiViet->tieu_de = $validated['tieu_de'];
            $baiViet->slug = $validated['slug'];
            $baiViet->mo_ta_ngan = '';
            $baiViet->noi_dung = $validated['noi_dung'];
            $baiViet->hinh_anh = $hinhAnh;
            $baiViet->trang_thai = $validated['trang_thai'];
            $baiViet->save();

            return redirect()->back()->with('thong_bao', 'Đã cập nhật bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('thong_bao_loi', 'Có lỗi xảy ra khi cập nhật bài viết: ' . $e->getMessage());
        }
    }

    /**
     * Hàm upload ảnh lên Cloudinary
     */
    public function baiVietUploadImage(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'message' => 'Không có file được upload'], 400);
        }

        try {
            $image = $request->file('image');
            $uploadedFile = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($image->getRealPath(), [
                'folder' => 'flower-shop/bai-viet',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            ]);

            return response()->json([
                'success' => true,
                'url' => $uploadedFile->getSecurePath()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Upload ảnh thất bại: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm xóa bài viết
     */
    public function baiVietDestroy(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập!'], 403);
        }

        $baiViet = \App\Models\BaiViet::find($id);
        if (!$baiViet) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bài viết'], 404);
        }

        try {
            $baiViet->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa bài viết thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa bài viết'], 500);
        }
    }
}
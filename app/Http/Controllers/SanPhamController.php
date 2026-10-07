<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\DanhMuc;
use Illuminate\Http\Request;

/**
 * Controller SanPhamController - Quản lý các chức năng liên quan đến sản phẩm
 * Xử lý các request từ người dùng liên quan đến sản phẩm
 */
class SanPhamController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm với bộ lọc
     */
    public function index(Request $request)
    {
        $query = SanPham::with('danhMuc', 'hinhAnhs')->where('trang_thai', true);

        // Lọc theo danh mục
        if ($request->has('danh_muc')) {
            $danhMuc = DanhMuc::where('slug', $request->danh_muc)->first();
            if ($danhMuc) {
                $query->where('danh_muc_id', $danhMuc->id);
            }
        }

        // Lọc theo giá
        if ($request->has('gia_min')) {
            $query->where('gia_ban', '>=', $request->gia_min);
        }
        if ($request->has('gia_max')) {
            $query->where('gia_ban', '<=', $request->gia_max);
        }

        // Lọc theo từ khóa tìm kiếm
        if ($request->has('tu_khoa')) {
            $query->where('ten_san_pham', 'like', '%' . $request->tu_khoa . '%');
        }

        // Sắp xếp
        $sapXep = $request->get('sap_xep', 'moi-nhat');
        switch ($sapXep) {
            case 'gia-tang':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'gia-giam':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'ban-chay':
                $query->orderBy('luot_mua', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Phân trang
        $sanPhams = $query->paginate(12);
        $danhMucs = DanhMuc::where('trang_thai', true)->orderBy('sap_xep')->get();

        return view('san_pham.index', compact('sanPhams', 'danhMucs'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($slug)
    {
        $sanPham = SanPham::with('danhMuc', 'hinhAnhs')
            ->where('slug', $slug)
            ->where('trang_thai', true)
            ->firstOrFail();

        // Tăng lượt xem
        $sanPham->increment('luot_xem');

        // Lấy sản phẩm liên quan
        $sanPhamLienQuan = SanPham::where('danh_muc_id', $sanPham->danh_muc_id)
            ->where('id', '!=', $sanPham->id)
            ->where('trang_thai', true)
            ->take(4)
            ->get();

        // Lấy đánh giá của sản phẩm
        $reviews = \App\Models\Review::with('user')
            ->where('san_pham_id', $sanPham->id)
            ->where('trang_thai', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kiểm tra user đã mua sản phẩm chưa
        $daMua = false;
        $daDanhGia = false;
        if (auth()->check()) {
            $donHang = \App\Models\DonHang::where('user_id', auth()->id())
                ->whereHas('chiTietDonHangs', function($query) use ($sanPham) {
                    $query->where('san_pham_id', $sanPham->id);
                })
                ->where('trang_thai', 'da_giao')
                ->first();
            
            $daMua = $donHang !== null;
            
            $review = \App\Models\Review::where('user_id', auth()->id())
                ->where('san_pham_id', $sanPham->id)
                ->first();
            $daDanhGia = $review !== null;
        }

        return view('san_pham.show', compact('sanPham', 'sanPhamLienQuan', 'reviews', 'daMua', 'daDanhGia'));
    }

    /**
     * Xử lý đánh giá sản phẩm
     */
    public function danhGia(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá'], 401);
        }

        $request->validate([
            'danh_gia' => 'required|integer|min:1|max:5',
            'noi_dung' => 'nullable|string|max:1000',
        ]);

        $sanPham = SanPham::findOrFail($id);

        // Kiểm tra user đã mua sản phẩm chưa
        $donHang = \App\Models\DonHang::where('user_id', auth()->id())
            ->whereHas('chiTietDonHangs', function($query) use ($sanPham) {
                $query->where('san_pham_id', $sanPham->id);
            })
            ->where('trang_thai', 'da_giao')
            ->first();

        if (!$donHang) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa mua sản phẩm này nên không thể đánh giá'], 403);
        }

        // Kiểm tra user đã đánh giá chưa
        $existingReview = \App\Models\Review::where('user_id', auth()->id())
            ->where('san_pham_id', $sanPham->id)
            ->first();

        if ($existingReview) {
            return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi'], 400);
        }

        // Tạo đánh giá
        $review = \App\Models\Review::create([
            'user_id' => auth()->id(),
            'san_pham_id' => $sanPham->id,
            'don_hang_id' => $donHang->id,
            'danh_gia' => $request->danh_gia,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Đánh giá thành công!']);
    }

    /**
     * Hàm hiển thị danh sách tất cả sản phẩm (Admin - giữ lại)
     * @return \Illuminate\View\View - Trang danh sách sản phẩm
     */
    public function HienThiDanhSach()
    {
        // Lấy tất cả sản phẩm từ database với eager load
        $danhSachSanPham = SanPham::with('danhMuc', 'hinhAnhs')->get();

        // Trả về view với dữ liệu sản phẩm
        return view('san_pham.danh_sach', compact('danhSachSanPham'));
    }

    /**
     * Hàm hiển thị chi tiết một sản phẩm
     * @param int $id - ID của sản phẩm cần xem
     * @return \Illuminate\View\View - Trang chi tiết sản phẩm
     */
    public function HienThiChiTiet($id)
    {
        // Tìm sản phẩm theo ID với eager load
        $sanPham = SanPham::with('danhMuc', 'hinhAnhs')->find($id);

        // Nếu không tìm thấy sản phẩm, trả về lỗi 404
        if (!$sanPham) {
            abort(404, 'Không tìm thấy sản phẩm');
        }

        // Trả về view chi tiết sản phẩm
        return view('san_pham.chi_tiet', compact('sanPham'));
    }

    /**
     * Hàm hiển thị form tạo sản phẩm mới
     * @return \Illuminate\View\View - Form tạo sản phẩm
     */
    public function HienThiFormTao()
    {
        // Lấy danh sách danh mục để chọn
        $danhSachDanhMuc = DanhMuc::all();

        // Trả về view form tạo sản phẩm
        return view('san_pham.form_tao', compact('danhSachDanhMuc'));
    }

    /**
     * Hàm lưu sản phẩm mới vào database
     * @param Request $request - Dữ liệu từ form
     * @return \Illuminate\Http\RedirectResponse - Chuyển hướng về danh sách
     */
    public function LuuSanPhamMoi(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'required|integer|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
        ]);

        // Tạo sản phẩm mới với dữ liệu từ form
        $sanPhamMoi = SanPham::create([
            'ten_san_pham' => $request->ten_san_pham,
            'mo_ta' => $request->mo_ta,
            'gia' => $request->gia,
            'so_luong' => $request->so_luong,
            'trang_thai' => 'co_hang',
            'danh_muc_id' => $request->danh_muc_id,
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('san-pham.danh-sach')
                        ->with('thong_bao', 'Đã tạo sản phẩm mới thành công!');
    }

    /**
     * Hàm hiển thị form chỉnh sửa sản phẩm
     * @param int $id - ID của sản phẩm cần sửa
     * @return \Illuminate\View\View - Form chỉnh sửa sản phẩm
     */
    public function HienThiFormSua($id)
    {
        // Tìm sản phẩm theo ID
        $sanPham = SanPham::find($id);

        // Nếu không tìm thấy sản phẩm
        if (!$sanPham) {
            abort(404, 'Không tìm thấy sản phẩm');
        }

        // Lấy danh sách danh mục
        $danhSachDanhMuc = DanhMuc::all();

        // Trả về view form chỉnh sửa
        return view('san_pham.form_sua', compact('sanPham', 'danhSachDanhMuc'));
    }

    /**
     * Hàm cập nhật thông tin sản phẩm
     * @param Request $request - Dữ liệu từ form
     * @param int $id - ID của sản phẩm cần cập nhật
     * @return \Illuminate\Http\RedirectResponse - Chuyển hướng về danh sách
     */
    public function CapNhatSanPham(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'required|integer|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
        ]);

        // Tìm sản phẩm cần cập nhật
        $sanPham = SanPham::find($id);

        // Nếu không tìm thấy sản phẩm
        if (!$sanPham) {
            abort(404, 'Không tìm thấy sản phẩm');
        }

        // Cập nhật thông tin sản phẩm
        $sanPham->update([
            'ten_san_pham' => $request->ten_san_pham,
            'mo_ta' => $request->mo_ta,
            'gia' => $request->gia,
            'so_luong' => $request->so_luong,
            'danh_muc_id' => $request->danh_muc_id,
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('san-pham.danh-sach')
                        ->with('thong_bao', 'Đã cập nhật sản phẩm thành công!');
    }

    /**
     * Hàm xóa sản phẩm
     * @param int $id - ID của sản phẩm cần xóa
     * @return \Illuminate\Http\RedirectResponse - Chuyển hướng về danh sách
     */
    public function XoaSanPham($id)
    {
        // Tìm sản phẩm cần xóa
        $sanPham = SanPham::find($id);

        // Nếu không tìm thấy sản phẩm
        if (!$sanPham) {
            abort(404, 'Không tìm thấy sản phẩm');
        }

        // Xóa sản phẩm
        $sanPham->delete();

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('san-pham.danh-sach')
                        ->with('thong_bao', 'Đã xóa sản phẩm thành công!');
    }

    /**
     * Hàm tìm kiếm sản phẩm theo từ khóa
     * @param Request $request - Request chứa từ khóa tìm kiếm
     * @return \Illuminate\View\View - Trang kết quả tìm kiếm
     */
    public function TimKiemSanPham(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request
        $tuKhoa = $request->tu_khoa;

        // Tìm sản phẩm theo tên
        $ketQuaTimKiem = SanPham::where('ten_san_pham', 'like', '%' . $tuKhoa . '%')
                               ->get();

        // Trả về view kết quả tìm kiếm
        return view('san_pham.tim_kiem', compact('ketQuaTimKiem', 'tuKhoa'));
    }
}

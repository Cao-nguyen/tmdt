<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function them(Request $request)
    {
        $sanPhamId = $request->san_pham_id;
        $soLuong = $request->so_luong ?? 1;

        $sanPham = SanPham::with('hinhAnhs')->find($sanPhamId);

        if (!$sanPham) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        }

        if (!$sanPham->trang_thai || $sanPham->so_luong < $soLuong) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không có sẵn']);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$sanPhamId])) {
            $cart[$sanPhamId]['so_luong'] += $soLuong;
        } else {
            $cart[$sanPhamId] = [
                'san_pham_id' => $sanPhamId,
                'ten_san_pham' => $sanPham->ten_san_pham,
                'gia_ban' => $sanPham->gia_ban,
                'gia_khuyen_mai' => $sanPham->gia_khuyen_mai,
                'so_luong' => $soLuong,
                'hinh_anh' => $sanPham->hinhAnhs->first()->duong_dan ?? null,
            ];
        }

        Session::put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'so_luong'));

        return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng', 'cart_count' => $cartCount]);
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        return view('gio_hang.index', compact('cart'));
    }

    /**
     * Cập nhật số lượng
     */
    public function capNhat(Request $request)
    {
        $sanPhamId = $request->san_pham_id;
        $soLuong = $request->so_luong;

        $cart = Session::get('cart', []);

        if (isset($cart[$sanPhamId])) {
            if ($soLuong <= 0) {
                unset($cart[$sanPhamId]);
            } else {
                $cart[$sanPhamId]['so_luong'] = $soLuong;
            }
            Session::put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function xoa(Request $request)
    {
        $sanPhamId = $request->san_pham_id;

        $cart = Session::get('cart', []);

        if (isset($cart[$sanPhamId])) {
            unset($cart[$sanPhamId]);
            Session::put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Xóa tất cả giỏ hàng
     */
    public function xoaTatCa()
    {
        Session::forget('cart');
        return redirect('/gio-hang')->with('thong_bao', 'Đã xóa giỏ hàng');
    }

    /**
     * Tìm kiếm sản phẩm và blog
     */
    public function timKiem(Request $request)
    {
        $tuKhoa = $request->tu_khoa;

        if (!$tuKhoa) {
            return redirect('/');
        }

        // Tìm sản phẩm - dùng ilike cho case-insensitive
        $sanPhams = SanPham::with('danhMuc', 'hinhAnhs')
            ->where('ten_san_pham', 'ilike', '%' . $tuKhoa . '%')
            ->get();

        // Tìm blog - dùng ilike cho case-insensitive
        $baiViets = \App\Models\BaiViet::where(function($query) use ($tuKhoa) {
                $query->where('tieu_de', 'ilike', '%' . $tuKhoa . '%')
                      ->orWhere('noi_dung', 'ilike', '%' . $tuKhoa . '%');
            })
            ->get();

        return view('tim_kiem.index', compact('sanPhams', 'baiViets', 'tuKhoa'));
    }

    /**
     * Hiển thị trang thanh toán
     */
    public function thanhToan()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect('/gio-hang')->with('thong_bao_loi', 'Giỏ hàng trống');
        }

        $user = Auth::user();
        $tongTien = collect($cart)->sum(function($item) {
            return ($item['gia_khuyen_mai'] ?? $item['gia_ban']) * $item['so_luong'];
        });

        return view('gio_hang.thanh_toan', compact('cart', 'user', 'tongTien'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function datHang(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('thong_bao_loi', 'Giỏ hàng trống');
        }

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'dia_chi' => 'required|string|max:500',
            'phuong_thuc_thanh_toan' => 'required|in:cash,momo',
        ]);

        $tongTien = collect($cart)->sum(function($item) {
            return ($item['gia_khuyen_mai'] ?? $item['gia_ban']) * $item['so_luong'];
        });

        // Map payment method
        $phuongThuc = $request->phuong_thuc_thanh_toan === 'momo' ? 'chuyen_khoan' : 'cod';

        // Tạo đơn hàng
        $donHang = \App\Models\DonHang::create([
            'user_id' => Auth::id(),
            'ma_don_hang' => 'DH' . time() . rand(1000, 9999),
            'ho_ten_nguoi_nhan' => $request->ho_ten,
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai,
            'dia_chi_giao' => $request->dia_chi,
            'tong_tien' => $tongTien,
            'thanh_tien' => $tongTien,
            'trang_thai' => 'cho_xac_nhan',
            'phuong_thuc_thanh_toan' => $phuongThuc,
        ]);

        // Tạo chi tiết đơn hàng
        foreach ($cart as $sanPhamId => $item) {
            \App\Models\ChiTietDonHang::create([
                'don_hang_id' => $donHang->id,
                'san_pham_id' => $sanPhamId,
                'so_luong' => $item['so_luong'],
                'gia_ban' => $item['gia_ban'],
                'gia_khuyen_mai' => $item['gia_khuyen_mai'],
            ]);
        }

        // Xóa giỏ hàng
        Session::forget('cart');

        return redirect('/tai-khoan')->with('thong_bao', 'Đặt hàng thành công! Mã đơn: ' . $donHang->ma_don_hang);
    }

    /**
     * Mua ngay - trang checkout nhanh cho 1 sản phẩm
     */
    public function muaNgay($id)
    {
        $sanPham = SanPham::with('danhMuc', 'hinhAnhs')->find($id);

        if (!$sanPham) {
            return back()->with('thong_bao_loi', 'Không tìm thấy sản phẩm');
        }

        if (!$sanPham->trang_thai || $sanPham->so_luong < 1) {
            return back()->with('thong_bao_loi', 'Sản phẩm không có sẵn');
        }

        $user = Auth::user();

        return view('gio_hang.mua_ngay', compact('sanPham', 'user'));
    }

    /**
     * Đặt hàng ngay (cho 1 sản phẩm)
     */
    public function datHangNgay(Request $request)
    {
        try {
            $sanPhamId = $request->san_pham_id;
            $soLuong = $request->so_luong ?? 1;
            $hoTen = $request->ho_ten;
            $soDienThoai = $request->so_dien_thoai;
            $diaChi = $request->dia_chi;
            $phuongThucThanhToan = $request->phuong_thuc_thanh_toan;

            $sanPham = SanPham::find($sanPhamId);

            if (!$sanPham || $sanPham->so_luong < $soLuong) {
                return back()->with('thong_bao_loi', 'Sản phẩm không đủ số lượng');
            }

            $gia = $sanPham->gia_khuyen_mai ?? $sanPham->gia_ban;
            $tongTien = $gia * $soLuong;

            // Nếu là Ngân hàng/PayOS, tích hợp PayOS PHP thuần
            if ($phuongThucThanhToan === 'momo') {
                $maDonHang = time(); // Dùng số nguyên dương cho PayOS

                // Lưu thông tin vào session
                Session::put('pending_order', [
                    'ma_don_hang' => $maDonHang,
                    'san_pham_id' => $sanPhamId,
                    'so_luong' => $soLuong,
                    'gia' => $gia,
                    'tong_tien' => $tongTien,
                    'ho_ten' => $hoTen,
                    'so_dien_thoai' => $soDienThoai,
                    'dia_chi' => $diaChi,
                ]);

                // Tạo PayOS payment link
                require_once base_path('payos/PayOS.php');
                $config = require base_path('payos/config.php');
                $payos = new \PayOS($config);

                try {
                    $result = $payos->createPaymentLink([
                        'orderCode'   => (int)$maDonHang,
                        'amount'      => (int)$tongTien,
                        'description' => 'Thanh toan ' . $maDonHang,
                        'returnUrl'   => $config['return_url'],
                        'cancelUrl'   => $config['cancel_url'],
                        'buyerName'   => $hoTen,
                        'buyerPhone'  => $soDienThoai,
                        'buyerEmail'  => Auth::user()->email,
                        // Không gửi items để tránh lỗi format giá
                    ]);

                    if (isset($result['checkoutUrl'])) {
                        return redirect($result['checkoutUrl']);
                    }
                } catch (\Exception $e) {
                    // Nếu PayOS lỗi, redirect đến trang chuyển khoản thủ công
                    return redirect('/thanh-toan-ngan-hang')->with('thong_bao_loi', 'PayOS lỗi: ' . $e->getMessage());
                }
            }

            // Nếu là COD, tạo đơn hàng và redirect về trang đơn hàng
            $tongTien = $gia * $soLuong;

            // Tạo đơn hàng
            $donHang = \App\Models\DonHang::create([
                'user_id' => Auth::id(),
                'ma_don_hang' => 'DH' . time() . rand(1000, 9999),
                'ho_ten_nguoi_nhan' => $hoTen,
                'so_dien_thoai_nguoi_nhan' => $soDienThoai,
                'email_nguoi_nhan' => Auth::user()->email,
                'dia_chi_giao' => $diaChi,
                'tong_tien' => $tongTien,
                'thanh_tien' => $tongTien,
                'trang_thai' => 'cho_xac_nhan',
                'phuong_thuc_thanh_toan' => 'cod',
            ]);

            // Tạo chi tiết đơn hàng
            \App\Models\ChiTietDonHang::create([
                'don_hang_id' => $donHang->id,
                'san_pham_id' => $sanPham->id,
                'so_luong' => $soLuong,
                'gia_ban' => $sanPham->gia_ban,
                'gia_khuyen_mai' => $sanPham->gia_khuyen_mai,
                'thanh_tien' => $tongTien,
            ]);

            return redirect('/don-hang')->with('thong_bao', 'Đặt hàng thành công! Mã đơn: ' . $donHang->ma_don_hang);
        } catch (\Exception $e) {
            return back()->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách đơn hàng của user
     */
    public function donHang()
    {
        $donHangs = \App\Models\DonHang::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('don_hang.index', compact('donHangs'));
    }

    /**
     * PayOS success callback - tạo đơn hàng khi thanh toán thành công
     */
    public function payOSSuccess(Request $request)
    {
        try {
            $pendingOrder = Session::get('pending_order');

            if (!$pendingOrder) {
                return redirect('/')->with('thong_bao_loi', 'Không có đơn hàng chờ thanh toán');
            }

            // Kiểm tra trạng thái thanh toán từ PayOS
            require_once base_path('payos/PayOS.php');
            $config = require base_path('payos/config.php');
            $payos = new \PayOS($config);

            $paymentInfo = $payos->getPaymentInfo($pendingOrder['ma_don_hang']);

            if (isset($paymentInfo['status']) && $paymentInfo['status'] === 'PAID') {
                // Tạo đơn hàng
                $donHang = DonHang::create([
                    'user_id' => Auth::id(),
                    'ma_don_hang' => $pendingOrder['ma_don_hang'],
                    'ho_ten_nguoi_nhan' => $pendingOrder['ho_ten'],
                    'so_dien_thoai_nguoi_nhan' => $pendingOrder['so_dien_thoai'],
                    'email_nguoi_nhan' => Auth::user()->email,
                    'dia_chi_giao' => $pendingOrder['dia_chi'],
                    'tong_tien' => $pendingOrder['tong_tien'],
                    'tong_giam_gia' => 0,
                    'thanh_tien' => $pendingOrder['tong_tien'],
                    'phuong_thuc_thanh_toan' => 'chuyen_khoan',
                    'trang_thai' => 'da_xac_nhan',
                    'ghi_chu' => 'Thanh toán qua PayOS',
                ]);

                // Tạo chi tiết đơn hàng
                ChiTietDonHang::create([
                    'don_hang_id' => $donHang->id,
                    'san_pham_id' => $pendingOrder['san_pham_id'],
                    'gia_ban' => $pendingOrder['gia'],
                    'so_luong' => $pendingOrder['so_luong'],
                    'thanh_tien' => $pendingOrder['tong_tien'],
                ]);

                // Xóa session
                Session::forget('pending_order');

                return redirect('/don-hang')->with('thong_bao', 'Thanh toán PayOS thành công! Đơn hàng đang được xử lý.');
            }

            return redirect('/')->with('thong_bao_loi', 'Thanh toán chưa hoàn tất');
        } catch (\Exception $e) {
            return redirect('/')->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * PayOS cancel callback
     */
    public function payOSCancel()
    {
        Session::forget('pending_order');
        return redirect('/')->with('thong_bao', 'Đã huỷ thanh toán');
    }

    /**
     * Trang thanh toán ngân hàng với QR code (fallback)
     */
    public function thanhToanNganHang()
    {
        try {
            $pendingOrder = Session::get('pending_order');

            if (!$pendingOrder) {
                return redirect('/')->with('thong_bao_loi', 'Không có đơn hàng chờ thanh toán');
            }

            $sanPham = SanPham::with('hinhAnhs')->find($pendingOrder['san_pham_id']);

            return view('gio_hang.thanh_toan_ngan_hang', compact('pendingOrder', 'sanPham'));
        } catch (\Exception $e) {
            return redirect('/')->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xác nhận thanh toán thủ công - tạo đơn hàng
     */
    public function xacNhanThanhToanNganHang()
    {
        try {
            $pendingOrder = Session::get('pending_order');

            if (!$pendingOrder) {
                return redirect('/')->with('thong_bao_loi', 'Không có đơn hàng chờ thanh toán');
            }

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'user_id' => Auth::id(),
                'ma_don_hang' => $pendingOrder['ma_don_hang'],
                'ho_ten_nguoi_nhan' => $pendingOrder['ho_ten'],
                'so_dien_thoai_nguoi_nhan' => $pendingOrder['so_dien_thoai'],
                'email_nguoi_nhan' => Auth::user()->email,
                'dia_chi_giao' => $pendingOrder['dia_chi'],
                'tong_tien' => $pendingOrder['tong_tien'],
                'tong_giam_gia' => 0,
                'thanh_tien' => $pendingOrder['tong_tien'],
                'phuong_thuc_thanh_toan' => 'chuyen_khoan',
                'trang_thai' => 'da_xac_nhan',
                'ghi_chu' => 'Thanh toán thủ công',
            ]);

            // Tạo chi tiết đơn hàng
            ChiTietDonHang::create([
                'don_hang_id' => $donHang->id,
                'san_pham_id' => $pendingOrder['san_pham_id'],
                'gia_ban' => $pendingOrder['gia'],
                'so_luong' => $pendingOrder['so_luong'],
                'thanh_tien' => $pendingOrder['tong_tien'],
            ]);

            // Xóa session
            Session::forget('pending_order');

            return redirect('/don-hang')->with('thong_bao', 'Thanh toán thành công! Đơn hàng đang được xử lý.');
        } catch (\Exception $e) {
            return redirect('/')->with('thong_bao_loi', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Đặt hàng nhanh tiền mặt (cho 1 sản phẩm)
     */
    public function datHangTienMat(Request $request)
    {
        $request->validate([
            'san_pham_id' => 'required|exists:san_phams,id',
            'so_luong' => 'required|integer|min:1',
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'dia_chi' => 'required|string|max:500',
        ]);

        $sanPham = SanPham::find($request->san_pham_id);

        if (!$sanPham || $sanPham->so_luong < $request->so_luong) {
            return back()->with('thong_bao_loi', 'Sản phẩm không đủ số lượng');
        }

        $gia = $sanPham->gia_khuyen_mai ?? $sanPham->gia_ban;
        $tongTien = $gia * $request->so_luong;

        // Tạo đơn hàng (luôn COD)
        $donHang = \App\Models\DonHang::create([
            'user_id' => Auth::id(),
            'ma_don_hang' => 'DH' . time() . rand(1000, 9999),
            'ho_ten_nguoi_nhan' => $request->ho_ten,
            'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai,
            'dia_chi_giao' => $request->dia_chi,
            'tong_tien' => $tongTien,
            'thanh_tien' => $tongTien,
            'trang_thai' => 'cho_xac_nhan',
            'phuong_thuc_thanh_toan' => 'cod',
        ]);

        // Tạo chi tiết đơn hàng
        \App\Models\ChiTietDonHang::create([
            'don_hang_id' => $donHang->id,
            'san_pham_id' => $sanPham->id,
            'so_luong' => $request->so_luong,
            'gia_ban' => $sanPham->gia_ban,
            'gia_khuyen_mai' => $sanPham->gia_khuyen_mai,
        ]);

        return redirect('/don-hang')->with('thong_bao', 'Đặt hàng thành công! Mã đơn: ' . $donHang->ma_don_hang);
    }
}

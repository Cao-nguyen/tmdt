<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function hienThiFormDangKy()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    /**
     * Hàm xử lý đăng ký người dùng mới
     * @param Request $request - Request chứa thông tin đăng ký
     * @return \Illuminate\Http\RedirectResponse
     */
    public function xuLyDangKy(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ten_dang_nhap' => 'nullable|string|max:255|unique:users,ten_dang_nhap',
            'so_dien_thoai' => 'required|string|max:20|unique:users,so_dien_thoai',
            'mat_khau' => 'required|string|min:6',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ và tên',
            'ho_ten.max' => 'Họ và tên không được quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'so_dien_thoai.unique' => 'Số điện thoại đã được sử dụng',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        // Nếu validate thất bại, quay lại form với lỗi
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('thong_bao_loi', 'Vui lòng kiểm tra lại thông tin!')
                ->withInput();
        }

        // Tạo user mới
        try {
            $user = User::create([
                'ho_ten' => $request->get('ho_ten'),
                'email' => $request->get('email'),
                'ten_dang_nhap' => $request->get('ten_dang_nhap'),
                'so_dien_thoai' => $request->get('so_dien_thoai'),
                'mat_khau' => Hash::make($request->get('mat_khau')), // Hash mật khẩu trước khi lưu
                'trang_thai' => true, // Active account by default
                'role' => 'khach_hang', // Mặc định role là khách hàng
                'email_verified_at' => now(),
            ]);

            // Đăng nhập ngay sau khi đăng ký thành công
            Auth::login($user);

            // Redirect về trang chủ với thông báo thành công
            return redirect('/')
                ->with('thong_bao', 'Đăng ký thành công! Chào mừng ' . $user->ho_ten);

        } catch (\Exception $e) {
            // Nếu có lỗi, quay lại với thông báo lỗi
            return redirect()->back()
                ->with('thong_bao', 'Đăng ký thất bại: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hàm hiển thị trang đăng nhập
     * @return \Illuminate\View\View
     */
    public function hienThiFormDangNhap()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Hàm xử lý đăng nhập
     * @param Request $request - Request chứa thông tin đăng nhập
     * @return \Illuminate\Http\RedirectResponse
     */
    public function xuLyDangNhap(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'thong_tin_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ], [
            'thong_tin_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập hoặc số điện thoại',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // Nếu validate thất bại, quay lại form với lỗi
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('thong_bao_loi', 'Vui lòng kiểm tra lại thông tin!')
                ->withInput();
        }

        // Tìm user theo email, tên đăng nhập hoặc số điện thoại
        $user = User::where('email', $request->get('thong_tin_dang_nhap'))
            ->orWhere('ten_dang_nhap', $request->get('thong_tin_dang_nhap'))
            ->orWhere('so_dien_thoai', $request->get('thong_tin_dang_nhap'))
            ->where('trang_thai', true)
            ->first(['id', 'ho_ten', 'email', 'ten_dang_nhap', 'so_dien_thoai', 'mat_khau', 'trang_thai', 'role']);

        // Nếu không tìm thấy user
        if (!$user) {
            return redirect()->back()
                ->with('thong_bao_loi', 'Tên đăng nhập hoặc số điện thoại không đúng')
                ->withInput();
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->get('mat_khau'), $user->mat_khau)) {
            return redirect()->back()
                ->with('thong_bao_loi', 'Mật khẩu không đúng')
                ->withInput();
        }

        // Kiểm tra trạng thái tài khoản
        if (!$user->isActive()) {
            return redirect()->back()
                ->with('thong_bao_loi', 'Tài khoản của bạn đã bị khóa')
                ->withInput();
        }

        // Đăng nhập user
        Auth::login($user, $request->has('ghi_nho')); // Có remember me nếu checkbox được check

        // Redirect về trang chủ với thông báo thành công
        return redirect('/')
            ->with('thong_bao', 'Đăng nhập thành công! Chào mừng ' . $user->ho_ten);
    }

    /**
     * Hàm xử lý đăng xuất
     * @return \Illuminate\Http\RedirectResponse
     */
    public function xuLyDangXuat()
    {
        // Đăng xuất user hiện tại
        Auth::logout();

        // Xóa session
        session()->invalidate();
        session()->regenerateToken();

        // Redirect về trang chủ với thông báo
        return redirect('/')
            ->with('thong_bao', 'Đăng xuất thành công!');
    }
}
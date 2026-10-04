<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\BaiViet;
use App\Models\Banner;

class TrangChuController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy dữ liệu cho trang chủ với eager loading
        $banners = Banner::hoatDong()->get();
        $danhMucs = DanhMuc::LayDanhSachHoatDong()->take(5);

        // Eager loading để tránh N+1 query - tạm thời bỏ filter để debug
        $sanPhamsNoiBat = SanPham::with('danhMuc')->with('hinhAnhs')->take(8)->get();
        $sanPhamsMoi = SanPham::with('danhMuc')->with('hinhAnhs')->take(4)->get();
        $baiViets = BaiViet::take(3)->get();

        return view('trang_chu', compact(
            'banners',
            'danhMucs',
            'sanPhamsNoiBat',
            'sanPhamsMoi',
            'baiViets'
        ));
    }
}

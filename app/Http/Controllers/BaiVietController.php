<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaiViet;

class BaiVietController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index()
    {
        $baiViets = BaiViet::orderBy('created_at', 'desc')->get();
        return view('bai_viet.index', compact('baiViets'));
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function show($slug)
    {
        $baiViet = BaiViet::with('user')->where('slug', $slug)->firstOrFail();
        $baiViet->increment('luot_xem');

        return view('bai_viet.show', compact('baiViet'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function index()
    {
        $user = Auth::user();
        return view('tai_khoan.index', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:500',
        ]);

        $user->update([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
        ]);

        return back()->with('success', 'Đã cập nhật thông tin thành công');
    }

    /**
     * Cập nhật ảnh đại diện
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'anh_dai_dien' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('anh_dai_dien')) {
            $file = $request->file('anh_dai_dien');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $fileName);
            $user->anh_dai_dien = 'uploads/avatars/' . $fileName;
            $user->save();
        }

        return back()->with('success', 'Đã cập nhật ảnh đại diện thành công');
    }
}

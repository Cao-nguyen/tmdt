<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhMuc;
use Illuminate\Support\Str;

class DanhMucController extends Controller
{
    /**
     * Hiển thị danh sách danh mục
     */
    public function index()
    {
        $danhMucs = DanhMuc::orderBy('sap_xep')->orderBy('created_at', 'desc')->get();
        return view('quan_tri.danh_muc.index', compact('danhMucs'));
    }

    /**
     * Hiển thị form tạo danh mục mới
     */
    public function create()
    {
        return view('quan_tri.danh_muc.create');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'sap_xep' => 'nullable|integer|min:0',
        ]);

        $danhMuc = new DanhMuc();
        $danhMuc->ten_danh_muc = $validated['ten_danh_muc'];
        $danhMuc->slug = Str::slug($validated['ten_danh_muc']);
        $danhMuc->mo_ta = $validated['mo_ta'] ?? null;
        $danhMuc->sap_xep = $validated['sap_xep'] ?? 0;
        $danhMuc->trang_thai = $request->has('trang_thai');
        $danhMuc->save();

        return redirect()->route('quan-tri.danh-muc.index')
            ->with('thong_bao', 'Đã tạo danh mục mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit($id)
    {
        $danhMuc = DanhMuc::findOrFail($id);
        return view('quan_tri.danh_muc.edit', compact('danhMuc'));
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'sap_xep' => 'nullable|integer|min:0',
        ]);

        $danhMuc = DanhMuc::findOrFail($id);
        $danhMuc->ten_danh_muc = $validated['ten_danh_muc'];
        $danhMuc->slug = Str::slug($validated['ten_danh_muc']);
        $danhMuc->mo_ta = $validated['mo_ta'] ?? null;
        $danhMuc->sap_xep = $validated['sap_xep'] ?? 0;
        $danhMuc->trang_thai = $request->has('trang_thai');
        $danhMuc->save();

        return redirect()->route('quan-tri.danh-muc.index')
            ->with('thong_bao', 'Đã cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $danhMuc = DanhMuc::findOrFail($id);
        
        // Kiểm tra xem danh mục có sản phẩm không
        if ($danhMuc->sanPhams()->count() > 0) {
            return redirect()->route('quan-tri.danh-muc.index')
                ->with('thong_bao_loi', 'Không thể xóa danh mục này vì còn sản phẩm thuộc danh mục!');
        }

        $danhMuc->delete();

        return redirect()->route('quan-tri.danh-muc.index')
            ->with('thong_bao', 'Đã xóa danh mục thành công!');
    }
}

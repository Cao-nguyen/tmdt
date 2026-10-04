<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danh_mucs';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'ten_danh_muc',
        'slug',
        'mo_ta',
        'hinh_anh',
        'sap_xep',
        'trang_thai',
    ];

    public static function LayDanhSachHoatDong()
    {
        return self::where('trang_thai', true)
                    ->orderBy('sap_xep')
                    ->get();
    }

    public function DemSoLuongSanPham()
    {
        return $this->sanPhams()->where('trang_thai', true)->count();
    }

    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, 'danh_muc_id');
    }

    public function scopeNoiBat($query)
    {
        return $query->where('trang_thai', true)
                    ->orderBy('sap_xep')
                    ->limit(6);
    }
}

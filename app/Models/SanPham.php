<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'san_phams';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'danh_muc_id',
        'ten_san_pham',
        'slug',
        'mo_ta_ngan',
        'mo_ta_chi_tiet',
        'gia_nhap',
        'gia_ban',
        'gia_khuyen_mai',
        'so_luong',
        'mau_sac',
        'kich_thuoc',
        'nguon_goc',
        'luot_xem',
        'luot_mua',
        'noi_bat',
        'san_pham_moi',
        'trang_thai',
    ];

    protected $casts = [
        'gia_nhap' => 'decimal:2',
        'gia_ban' => 'decimal:2',
        'gia_khuyen_mai' => 'decimal:2',
        'so_luong' => 'integer',
        'luot_xem' => 'integer',
        'luot_mua' => 'integer',
        'noi_bat' => 'boolean',
        'san_pham_moi' => 'boolean',
        'trang_thai' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function LayDanhSachCoHang()
    {
        return $this->where('trang_thai', true)
                    ->where('so_luong', '>', 0)
                    ->get();
    }

    public function TimTheoTen($tuKhoa)
    {
        return $this->where('ten_san_pham', 'like', '%' . $tuKhoa . '%')
                    ->get();
    }

    public function TongGiaTri()
    {
        return $this->gia_nhap * $this->so_luong;
    }

    public function CoTheBan()
    {
        return $this->trang_thai === true && $this->so_luong > 0;
    }

    public function getGiaBanThucTeAttribute()
    {
        return $this->gia_khuyen_mai ? $this->gia_khuyen_mai : $this->gia_ban;
    }

    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    public function hinhAnhs()
    {
        return $this->hasMany(HinhAnh::class, 'san_pham_id');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'san_pham_id');
    }

    public function anhChinh()
    {
        return $this->hinhAnhs()->where('anh_chinh', true)->first();
    }

    public function scopeNoiBat($query)
    {
        return $query->where('noi_bat', true)->where('trang_thai', true);
    }

    public function scopeSanPhamMoi($query)
    {
        return $query->where('san_pham_moi', true)->where('trang_thai', true);
    }

    public function scopeCoSan($query)
    {
        return $query->where('trang_thai', true)->where('so_luong', '>', 0);
    }
}

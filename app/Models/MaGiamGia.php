<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gias';

    protected $fillable = [
        'ma_giam_gia',
        'ten_ma',
        'loai_giam_gia',
        'gia_tri_giam',
        'don_toi_thieu',
        'giam_toi_da',
        'so_luong',
        'so_luong_da_dung',
        'user_id',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
    ];

    protected $casts = [
        'gia_tri_giam' => 'decimal:2',
        'don_toi_thieu' => 'decimal:2',
        'giam_toi_da' => 'decimal:2',
        'so_luong' => 'integer',
        'so_luong_da_dung' => 'integer',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
        'trang_thai' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_giam_gia_id');
    }

    public function conHieuLuc()
    {
        $now = now();
        return $this->trang_thai &&
               $this->ngay_bat_dau <= $now &&
               $this->ngay_ket_thuc >= $now &&
               $this->so_luong_da_dung < $this->so_luong;
    }

    public function coTheApDung($tongTien)
    {
        return $this->conHieuLuc() && $tongTien >= $this->don_toi_thieu;
    }

    public function tinhTienGiam($tongTien)
    {
        if ($this->loai_giam_gia === 'phan_tram') {
            $tienGiam = $tongTien * ($this->gia_tri_giam / 100);
            return $this->giam_toi_da ? min($tienGiam, $this->giam_toi_da) : $tienGiam;
        } else {
            return min($this->gia_tri_giam, $tongTien);
        }
    }

    public function tangSoLuongDaDung()
    {
        $this->increment('so_luong_da_dung');
    }
}

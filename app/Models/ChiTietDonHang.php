<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_hangs';

    protected $fillable = [
        'don_hang_id',
        'san_pham_id',
        'so_luong',
        'gia_ban',
        'giam_gia',
        'thanh_tien',
    ];

    protected $casts = [
        'gia_ban' => 'decimal:2',
        'giam_gia' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
        'so_luong' => 'integer',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'don_hang_id');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($chiTiet) {
            $chiTiet->thanh_tien = ($chiTiet->gia_ban - $chiTiet->giam_gia) * $chiTiet->so_luong;
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'tieu_de',
        'hinh_anh',
        'lien_ket',
        'mo_ta',
        'sap_xep',
        'trang_thai',
        'ngay_bat_dau',
        'ngay_ket_thuc',
    ];

    protected $casts = [
        'sap_xep' => 'integer',
        'trang_thai' => 'boolean',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
    ];

    public function scopeHoatDong($query)
    {
        $now = now();
        return $query->where('trang_thai', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('ngay_bat_dau')
                          ->orWhere('ngay_bat_dau', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('ngay_ket_thuc')
                          ->orWhere('ngay_ket_thuc', '>=', $now);
                    })
                    ->orderBy('sap_xep');
    }

    public function dangHienThi()
    {
        $now = now();
        return $this->trang_thai &&
               (!$this->ngay_bat_dau || $this->ngay_bat_dau <= $now) &&
               (!$this->ngay_ket_thuc || $this->ngay_ket_thuc >= $now);
    }
}

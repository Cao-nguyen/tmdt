<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'bai_viets';

    protected $fillable = [
        'user_id',
        'tieu_de',
        'slug',
        'mo_ta_ngan',
        'noi_dung',
        'hinh_anh',
        'noi_bat',
        'trang_thai',
        'luot_xem',
    ];

    protected $casts = [
        'noi_bat' => 'boolean',
        'trang_thai' => 'boolean',
        'luot_xem' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function binhLuans()
    {
        return $this->hasMany(BinhLuan::class, 'bai_viet_id');
    }

    public function scopeNoiBat($query)
    {
        return $query->where('noi_bat', true)->where('trang_thai', true);
    }

    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', true);
    }

    public function tangLuotXem()
    {
        $this->increment('luot_xem');
    }
}

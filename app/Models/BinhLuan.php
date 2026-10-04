<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luans';

    protected $fillable = [
        'bai_viet_id',
        'user_id',
        'ten_nguoi_binh_luan',
        'email',
        'noi_dung',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    public function baiViet()
    {
        return $this->belongsTo(BaiViet::class, 'bai_viet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'san_pham_id',
        'don_hang_id',
        'danh_gia',
        'noi_dung',
        'trang_thai',
    ];

    protected $casts = [
        'danh_gia' => 'integer',
        'trang_thai' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class);
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class);
    }

    // Scope
    public function scopeDuyet($query)
    {
        return $query->where('trang_thai', true);
    }

    public function scopeBySanPham($query, $sanPhamId)
    {
        return $query->where('san_pham_id', $sanPhamId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hangs';

    protected $fillable = [
        'user_id',
        'ma_don_hang',
        'ho_ten_nguoi_nhan',
        'so_dien_thoai_nguoi_nhan',
        'email_nguoi_nhan',
        'dia_chi_giao',
        'thanh_pho',
        'quan_huyen',
        'tong_tien',
        'tong_giam_gia',
        'thanh_tien',
        'ma_giam_gia_id',
        'phuong_thuc_thanh_toan',
        'trang_thai',
        'ghi_chu',
        'ngay_giao_du_kien',
        'ngay_giao_thuc_te',
    ];

    protected $casts = [
        'tong_tien' => 'decimal:2',
        'tong_giam_gia' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
        'ngay_giao_du_kien' => 'datetime',
        'ngay_giao_thuc_te' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'ma_giam_gia_id');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'don_hang_id');
    }

    public function scopeChoXacNhan($query)
    {
        return $query->where('trang_thai', 'cho_xac_nhan');
    }

    public function scopeDangGiao($query)
    {
        return $query->where('trang_thai', 'dang_giao');
    }

    public function scopeDaHoanThanh($query)
    {
        return $query->where('trang_thai', 'da_giao');
    }

    public static function taoMaDonHang()
    {
        $prefix = 'DH';
        $timestamp = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . $timestamp . $random;
    }
}

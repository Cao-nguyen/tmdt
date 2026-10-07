<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'ho_ten',
        'email',
        'so_dien_thoai',
        'ten_dang_nhap',
        'mat_khau',
        'anh_dai_dien',
        'dia_chi',
        'role',
        'trang_thai',
        'email_verified_at',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
        'trang_thai' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function isActive(): bool
    {
        return $this->trang_thai === true;
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class);
    }

    public function baiViets()
    {
        return $this->hasMany(BaiViet::class);
    }

    public function binhLuans()
    {
        return $this->hasMany(BinhLuan::class);
    }

    public function maGiamGias()
    {
        return $this->hasMany(MaGiamGia::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isNhanVien(): bool
    {
        return $this->role === 'nhan_vien';
    }
}
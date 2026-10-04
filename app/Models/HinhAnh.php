<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnh extends Model
{
    use HasFactory;

    protected $table = 'hinh_anhs';

    protected $fillable = [
        'san_pham_id',
        'duong_dan',
        'public_id',
        'anh_chinh',
        'sap_xep',
    ];

    protected $casts = [
        'anh_chinh' => 'boolean',
        'sap_xep' => 'integer',
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id');
    }

    public function scopeAnhChinh($query)
    {
        return $query->where('anh_chinh', true);
    }
}

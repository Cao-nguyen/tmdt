<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách banner mẫu
        $bannerMau = [
            [
                'tieu_de' => 'Khuyến Mãi 20% Cho Đơn Hàng Đầu Tiên',
                'hinh_anh' => 'banner-khuyen-mai-1.jpg',
                'lien_ket' => '/san-pham?khuyen_mai=1',
                'mo_ta' => 'Giảm ngay 20% cho đơn hàng đầu tiên tại Flower Shop',
                'sap_xep' => 1,
                'trang_thai' => true,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addDays(30),
            ],
            [
                'tieu_de' => 'Bộ Sưu Tập Hoa Valentine 2024',
                'hinh_anh' => 'banner-valentine.jpg',
                'lien_ket' => '/san-pham?danh_muc=hoa-le-tinh-yeu',
                'mo_ta' => 'Khám phá bộ sưu tập hoa Valentine mới nhất',
                'sap_xep' => 2,
                'trang_thai' => true,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addDays(60),
            ],
            [
                'tieu_de' => 'Giao Hoa Miễn Phí Trong Nội Thành',
                'hinh_anh' => 'banner-giao-hoa.jpg',
                'lien_ket' => '/chinh-sach-giao-hang',
                'mo_ta' => 'Miễn phí giao hàng cho đơn hàng trên 500k',
                'sap_xep' => 3,
                'trang_thai' => true,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => null,
            ],
            [
                'tieu_de' => 'Hoa Tươi Đà Lạt - Chất Lượng Đỉnh Cao',
                'hinh_anh' => 'banner-da-lat.jpg',
                'lien_ket' => '/san-pham?nguon_goc=Da-Lat',
                'mo_ta' => 'Hoa tươi Đà Lạt được thu hoạch hàng ngày',
                'sap_xep' => 4,
                'trang_thai' => true,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => null,
            ],
        ];

        // Thêm từng banner vào database
        foreach ($bannerMau as $banner) {
            Banner::create($banner);
        }

        $this->command->info('✅ Đã thêm dữ liệu mẫu cho bảng banners');
    }
}

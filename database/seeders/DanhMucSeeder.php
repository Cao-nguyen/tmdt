<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DanhMuc;

/**
 * Seeder DanhMucSeeder - Thêm dữ liệu mẫu cho bảng danh_mucs
 * Dùng để tạo dữ liệu ban đầu cho testing
 */
class DanhMucSeeder extends Seeder
{
    /**
     * Hàm chạy seeder - Thêm dữ liệu mẫu
     */
    public function run()
    {
        // Danh sách danh mục hoa
        $danhMucMau = [
            [
                'ten_danh_muc' => 'Hoa Sinh Nhật',
                'slug' => 'hoa-sinh-nhat',
                'mo_ta' => 'Các bó hoa sinh nhật đẹp và ý nghĩa',
                'hinh_anh' => null,
                'sap_xep' => 1,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Chia Buồn',
                'slug' => 'hoa-chia-buon',
                'mo_ta' => 'Hoa tang lễ, chia buồn trang trọng',
                'hinh_anh' => null,
                'sap_xep' => 2,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Cưới Hỏi',
                'slug' => 'hoa-cuoi-hoi',
                'mo_ta' => 'Hoa cưới, hoa engagement lãng mạn',
                'hinh_anh' => null,
                'sap_xep' => 3,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Khai Trương',
                'slug' => 'hoa-khai-truong',
                'mo_ta' => 'Hoa khai trương, chúc mừng thành công',
                'hinh_anh' => null,
                'sap_xep' => 4,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Lễ Tình Yêu',
                'slug' => 'hoa-le-tinh-yeu',
                'mo_ta' => 'Hoa Valentine, lễ tình yêu lãng mạn',
                'hinh_anh' => null,
                'sap_xep' => 5,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa 8/3',
                'slug' => 'hoa-8-3',
                'mo_ta' => 'Hoa ngày Phụ nữ Việt Nam',
                'hinh_anh' => null,
                'sap_xep' => 6,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Bó',
                'slug' => 'hoa-bo',
                'mo_ta' => 'Các bó hoa nhỏ xinh, dễ thương',
                'hinh_anh' => null,
                'sap_xep' => 7,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Giỏ',
                'slug' => 'hoa-gio',
                'mo_ta' => 'Hoa giỏ sang trọng, trang trọng',
                'hinh_anh' => null,
                'sap_xep' => 8,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Chậu',
                'slug' => 'hoa-chau',
                'mo_ta' => 'Hoa chậu để bàn, trang trí nhà cửa',
                'hinh_anh' => null,
                'sap_xep' => 9,
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Hoa Lan',
                'slug' => 'hoa-lan',
                'mo_ta' => 'Các loại hoa lan cao cấp',
                'hinh_anh' => null,
                'sap_xep' => 10,
                'trang_thai' => true,
            ],
        ];

        // Thêm từng danh mục vào database
        foreach ($danhMucMau as $danhMuc) {
            DanhMuc::create($danhMuc);
        }

        $this->command->info('✅ Đã thêm dữ liệu mẫu cho bảng danh_mucs');
    }
}

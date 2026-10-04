<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder - Seeder chính để chạy tất cả các seeder khác
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Hàm chạy seeder chính
     * Gọi lần lượt các seeder con để thêm dữ liệu mẫu
     */
    public function run()
    {
        // Gọi seeder theo thứ tự đúng
        $this->call([
            UserSeeder::class,          // Tạo user trước vì các bảng khác có thể cần
            DanhMucSeeder::class,       // Tạo danh mục trước vì sản phẩm cần danh mục
            SanPhamSeeder::class,       // Tạo sản phẩm
            BaiVietSeeder::class,       // Tạo bài viết
            BannerSeeder::class,        // Tạo banner
            SettingSeeder::class,       // Tạo cài đặt
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BaiViet;
use App\Models\User;

class BaiVietSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy user admin để làm tác giả
        $admin = User::where('role', 'admin')->first();

        // Danh sách bài viết mẫu về hoa
        $baiVietMau = [
            [
                'user_id' => $admin->id ?? 1,
                'tieu_de' => 'Cách Chăm Sóc Hoa Tươi Lâu Tại Nhà',
                'slug' => 'cach-cham-soc-hoa-tuoi-lau-tai-nha',
                'mo_ta_ngan' => 'Hướng dẫn cách chăm sóc hoa tươi để kéo dài thời gian sử dụng',
                'noi_dung' => 'Hoa tươi là món quà tuyệt vời, nhưng để giữ hoa lâu đẹp cần biết cách chăm sóc đúng cách. Trong bài viết này, chúng tôi sẽ chia sẻ những bí quyết giúp hoa tươi lâu hơn...

1. Cắt gốc hoa mỗi ngày: Cắt một đoạn nhỏ ở gốc hoa mỗi ngày để giúp hoa hút nước tốt hơn.

2. Thay nước thường xuyên: Thay nước trong bình mỗi 2-3 ngày để tránh vi khuẩn phát triển.

3. Sử dụng chất bảo quản hoa: Thêm một chút đường hoặc chất bảo quản hoa chuyên dụng vào nước.

4. Tránh ánh nắng trực tiếp: Đặt hoa ở nơi thoáng mát, tránh ánh nắng gắt trực tiếp.

5. Loại bỏ lá bị hỏng: Thường xuyên kiểm tra và loại bỏ những lá hoặc bông hoa bị hỏng.',
                'hinh_anh' => null,
                'noi_bat' => true,
                'trang_thai' => true,
                'luot_xem' => 0,
            ],
            [
                'user_id' => $admin->id ?? 1,
                'tieu_de' => 'Ý Nghĩa Của Các Loài Hoa Phổ Biến',
                'slug' => 'y-nghia-cua-cac-loai-hoa-pho-bien',
                'mo_ta_ngan' => 'Khám phá ý nghĩa của các loại hoa phổ biến khi tặng quà',
                'noi_dung' => 'Mỗi loài hoa đều mang một ý nghĩa riêng biệt. Hiểu rõ ý nghĩa sẽ giúp bạn chọn được món quà phù hợp nhất:

- Hoa Hồng Đỏ: Tình yêu đam mê, tình yêu nồng nàn
- Hoa Hồng Trắng: Sự tinh khiết, thủy chung
- Hoa Hồng Hồng: Cảm giác biết ơn, lòng ngưỡng mộ
- Hoa Ly: Sự thanh cao, sang trọng
- Hoa Hướng Dương: Niềm vui, sự lạc quan
- Hoa Baby Breath: Sự dịu dàng, tinh khiết
- Hoa Lan: Sự sang trọng, đẳng cấp
- Hoa Cúc: Sự tươi vui, năng lượng tích cực',
                'hinh_anh' => null,
                'noi_bat' => true,
                'trang_thai' => true,
                'luot_xem' => 0,
            ],
            [
                'user_id' => $admin->id ?? 1,
                'tieu_de' => 'Mẹo Cắm Hoa Đẹp Cho Người Mới Bắt Đầu',
                'slug' => 'meo-cam-hoa-dep-cho-nguoi-moi-bat-dau',
                'mo_ta_ngan' => 'Hướng dẫn cắm hoa đẹp đơn giản cho người mới',
                'noi_dung' => 'Cắm hoa là nghệ thuật giúp không gian trở nên đẹp hơn. Dưới đây là một số mẹo cắm hoa đẹp cho người mới bắt đầu:

1. Chọn bình phù hợp: Bình hoa không nên quá lớn so với số lượng hoa.

2. Sử dụng bọt cắm hoa: Giúp hoa đứng vững và dễ dàng sắp xếp.

3. Tạo điểm nhấn: Chọn một bông hoa to nhất làm điểm nhấn chính.

4. Xen kẽ chiều cao: Sắp xếp hoa theo chiều cao khác nhau để tạo độ sâu.

5. Thêm lá cây: Lá cây giúp làm nổi bật hoa và tạo sự cân bằng.',
                'hinh_anh' => null,
                'noi_bat' => false,
                'trang_thai' => true,
                'luot_xem' => 0,
            ],
            [
                'user_id' => $admin->id ?? 1,
                'tieu_de' => '5 Loại Hoa Phù Hợp Tặng Ngày 8/3',
                'slug' => '5-loai-hoa-phu-hop-tang-ngay-8-3',
                'mo_ta_ngan' => 'Gợi ý các loại hoa tặng ngày Phụ nữ Việt Nam',
                'noi_dung' => 'Ngày 8/3 là dịp để tri ân những người phụ nữ yêu thương. Đây là 5 loại hoa phù hợp nhất:

1. Hoa Hồng: Lựa chọn kinh điển và an toàn nhất
2. Hoa Hướng Dương: Tượng trưng cho niềm vui và năng lượng
3. Hoa Ly: Sang trọng và thanh lịch
4. Hoa Tulip: Lãng mạn và tinh tế
5. Hoa Baby Breath: Dịu dàng và nữ tính

Mỗi loại hoa đều mang ý nghĩa riêng, hãy chọn loại phù hợp nhất với người nhận!',
                'hinh_anh' => null,
                'noi_bat' => true,
                'trang_thai' => true,
                'luot_xem' => 0,
            ],
        ];

        // Thêm từng bài viết vào database
        foreach ($baiVietMau as $baiViet) {
            BaiViet::create($baiViet);
        }

        $this->command->info('✅ Đã thêm dữ liệu mẫu cho bảng bai_viets');
    }
}

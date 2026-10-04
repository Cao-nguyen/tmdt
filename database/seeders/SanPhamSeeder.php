<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\DanhMuc;

/**
 * Seeder SanPhamSeeder - Thêm dữ liệu mẫu cho bảng san_phams
 * Dùng để tạo dữ liệu ban đầu cho testing
 */
class SanPhamSeeder extends Seeder
{
    /**
     * Hàm chạy seeder - Thêm dữ liệu mẫu
     */
    public function run()
    {
        // Lấy danh sách danh mục
        $danhMucs = DanhMuc::all();

        // Danh sách sản phẩm hoa mẫu
        $sanPhamMau = [
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Sinh Nhật')->first()->id ?? 1,
                'ten_san_pham' => 'Bó Hoa Hồng Đỏ Sinh Nhật',
                'slug' => 'bo-hoa-hong-do-sinh-nhat',
                'mo_ta_ngan' => 'Bó hoa hồng đỏ tươi thắm, ý nghĩa tình yêu đam mê',
                'mo_ta_chi_tiet' => 'Bó hoa gồm 12 bông hồng đỏ tươi, được gói đẹp với giấy hồng trắng. Thích hợp làm quà sinh nhật người yêu, vợ hoặc người thân.',
                'gia_nhap' => 250000,
                'gia_ban' => 350000,
                'gia_khuyen_mai' => null,
                'so_luong' => 15,
                'mau_sac' => 'Đỏ',
                'kich_thuoc' => 'Trung bình',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => true,
                'san_pham_moi' => true,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Sinh Nhật')->first()->id ?? 1,
                'ten_san_pham' => 'Bó Hoa Baby Breath Pastel',
                'slug' => 'bo-hoa-baby-breath-pastel',
                'mo_ta_ngan' => 'Hoa baby breath màu pastel dịu dàng',
                'mo_ta_chi_tiet' => 'Bó hoa baby breath với nhiều màu pastel nhẹ nhàng, tượng trưng cho sự tinh khiết và dịu dàng.',
                'gia_nhap' => 200000,
                'gia_ban' => 280000,
                'gia_khuyen_mai' => null,
                'so_luong' => 20,
                'mau_sac' => 'Pastel',
                'kich_thuoc' => 'Lớn',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => true,
                'san_pham_moi' => true,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Cưới Hỏi')->first()->id ?? 3,
                'ten_san_pham' => 'Giỏ Hoa Cưới Trắng Sang Trọng',
                'slug' => 'gio-hoa-cuoi-trang-sang-trong',
                'mo_ta_ngan' => 'Giỏ hoa cưới màu trắng sang trọng',
                'mo_ta_chi_tiet' => 'Giỏ hoa cưới với hoa hồng trắng, hoa ly trắng và lá cành, biểu tượng cho sự tinh khiết và thủy chung.',
                'gia_nhap' => 800000,
                'gia_ban' => 1200000,
                'gia_khuyen_mai' => null,
                'so_luong' => 5,
                'mau_sac' => 'Trắng',
                'kich_thuoc' => 'Lớn',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => true,
                'san_pham_moi' => false,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Khai Trương')->first()->id ?? 4,
                'ten_san_pham' => 'Bảng Hoa Khai Trương Vàng Đỏ',
                'slug' => 'bang-hoa-khai-truong-vang-do',
                'mo_ta_ngan' => 'Bảng hoa khai trương màu vàng đỏ thịnh vượng',
                'mo_ta_chi_tiet' => 'Bảng hoa khai trương với hoa hồng đỏ và hoa cúc vàng, mang ý nghĩa chúc mừng thành công và thịnh vượng.',
                'gia_nhap' => 500000,
                'gia_ban' => 750000,
                'gia_khuyen_mai' => null,
                'so_luong' => 10,
                'mau_sac' => 'Vàng - Đỏ',
                'kich_thuoc' => 'Lớn',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => false,
                'san_pham_moi' => false,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Lễ Tình Yêu')->first()->id ?? 5,
                'ten_san_pham' => 'Bó 99 Hoa Hồng Đỏ Valentine',
                'slug' => 'bo-99-hoa-hong-do-valentine',
                'mo_ta_ngan' => 'Bó 99 bông hồng đỏ cho ngày Valentine',
                'mo_ta_chi_tiet' => 'Bó 99 bông hồng đỏ rực rỡ, biểu tượng cho tình yêu vĩnh cửu. Quà tặng hoàn hảo cho ngày Valentine.',
                'gia_nhap' => 1500000,
                'gia_ban' => 2200000,
                'gia_khuyen_mai' => 1800000,
                'so_luong' => 3,
                'mau_sac' => 'Đỏ',
                'kich_thuoc' => 'Cực lớn',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => true,
                'san_pham_moi' => false,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa 8/3')->first()->id ?? 6,
                'ten_san_pham' => 'Giỏ Hoa 8/3 Đa Sắc Màu',
                'slug' => 'gio-hoa-8-3-da-sac-mau',
                'mo_ta_ngan' => 'Giỏ hoa ngày Phụ nữ 8/3',
                'mo_ta_chi_tiet' => 'Giỏ hoa với nhiều loại hoa đa sắc màu, tặng mẹ hoặc người phụ nữ yêu thương vào ngày 8/3.',
                'gia_nhap' => 400000,
                'gia_ban' => 600000,
                'gia_khuyen_mai' => null,
                'so_luong' => 12,
                'mau_sac' => 'Đa sắc',
                'kich_thuoc' => 'Trung bình',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => false,
                'san_pham_moi' => false,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Lan')->first()->id ?? 10,
                'ten_san_pham' => 'Chậu Lan Hồ Điệp Trắng',
                'slug' => 'chau-lan-ho-diep-trang',
                'mo_ta_ngan' => 'Chậu lan hồ điệp trắng sang trọng',
                'mo_ta_chi_tiet' => 'Chậu lan hồ điệp trắng với nhiều bông hoa, tượng trưng cho sự sang trọng và đẳng cấp.',
                'gia_nhap' => 350000,
                'gia_ban' => 500000,
                'gia_khuyen_mai' => null,
                'so_luong' => 8,
                'mau_sac' => 'Trắng',
                'kich_thuoc' => 'Trung bình',
                'nguon_goc' => 'Đà Lạt',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => true,
                'san_pham_moi' => false,
                'trang_thai' => true,
            ],
            [
                'danh_muc_id' => $danhMucs->where('ten_danh_muc', 'Hoa Chậu')->first()->id ?? 9,
                'ten_san_pham' => 'Chậu Hoa Mini Để Bàn',
                'slug' => 'chau-hoa-mini-de-ban',
                'mo_ta_ngan' => 'Chậu hoa nhỏ xinh để bàn làm việc',
                'mo_ta_chi_tiet' => 'Chậu hoa mini với hoa sen đá và xương rồng, dễ chăm sóc, thích hợp để bàn làm việc.',
                'gia_nhap' => 80000,
                'gia_ban' => 120000,
                'gia_khuyen_mai' => null,
                'so_luong' => 30,
                'mau_sac' => 'Xanh lá',
                'kich_thuoc' => 'Nhỏ',
                'nguon_goc' => 'Hà Nội',
                'luot_xem' => 0,
                'luot_mua' => 0,
                'noi_bat' => false,
                'san_pham_moi' => true,
                'trang_thai' => true,
            ],
        ];

        // Thêm từng sản phẩm vào database
        foreach ($sanPhamMau as $sanPham) {
            SanPham::create($sanPham);
        }

        $this->command->info('✅ Đã thêm dữ liệu mẫu cho bảng san_phams');
    }
}

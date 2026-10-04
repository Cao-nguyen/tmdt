<?php

/**
 * Script để xóa comment code trong dự án
 * - PHP: // và /* */
 * - Blade: {{-- --}}
 */

function removePhpComments($content) {
    // Xóa comment nhiều dòng /* */
    $content = preg_replace('/\/\*[\s\S]*?\*\//', '', $content);

    // Xóa comment một dòng //
    // Cẩn thận: không xóa nếu // nằm trong string
    $lines = explode("\n", $content);
    $result = [];

    foreach ($lines as $line) {
        // Kiểm tra xem dòng có // không
        if (strpos($line, '//') !== false) {
            // Đếm số dấu ngoặc đơn và kép để xác định // có trong string không
            $singleQuoteCount = substr_count($line, "'");
            $doubleQuoteCount = substr_count($line, '"');

            // Tìm vị trí của //
            $commentPos = strpos($line, '//');

            // Kiểm tra xem // có trong string không
            $inString = false;
            $stringChar = '';
            for ($i = 0; $i < $commentPos; $i++) {
                if ($line[$i] === "'" || $line[$i] === '"') {
                    if (!$inString) {
                        $inString = true;
                        $stringChar = $line[$i];
                    } elseif ($line[$i] === $stringChar) {
                        $inString = false;
                    }
                }
            }

            if (!$inString) {
                // Xóa comment từ vị trí // đến hết dòng
                $line = substr($line, 0, $commentPos);
            }
        }

        // Trim whitespace ở cuối dòng
        $line = rtrim($line);

        // Chỉ thêm dòng nếu không rỗng hoặc dòng trước đó có code
        if (trim($line) !== '' || !empty($result)) {
            $result[] = $line;
        }
    }

    return implode("\n", $result);
}

function removeBladeComments($content) {
    // Xóa comment Blade {{-- --}}
    $content = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $content);
    return $content;
}

function processFile($filePath) {
    echo "Processing: $filePath\n";

    $content = file_get_contents($filePath);

    if ($content === false) {
        echo "  ERROR: Cannot read file\n";
        return false;
    }

    $originalContent = $content;

    // Xóa comment PHP
    $content = removePhpComments($content);

    // Xóa comment Blade (nếu là file blade)
    if (strpos($filePath, '.blade.php') !== false) {
        $content = removeBladeComments($content);
    }

    // Nếu có thay đổi thì ghi lại file
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "  ✓ Comments removed\n";
        return true;
    } else {
        echo "  - No comments found\n";
        return false;
    }
}

// Danh sách file cần xử lý
$files = [
    // app/
    'D:\\tmdt\\app\\Console\\Kernel.php',
    'D:\\tmdt\\app\\Exceptions\\Handler.php',
    'D:\\tmdt\\app\\Http\\Controllers\\AdminController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\AuthController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\BaiVietController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\Controller.php',
    'D:\\tmdt\\app\\Http\\Controllers\\DanhMucController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\DonHangController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\GioHangController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\SanPhamController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\TrangChuController.php',
    'D:\\tmdt\\app\\Http\\Controllers\\UserController.php',
    'D:\\tmdt\\app\\Http\\Kernel.php',
    'D:\\tmdt\\app\\Http\\Middleware\\EncryptCookies.php',
    'D:\\tmdt\\app\\Http\\Middleware\\PreventRequestsDuringMaintenance.php',
    'D:\\tmdt\\app\\Http\\Middleware\\TrimStrings.php',
    'D:\\tmdt\\app\\Http\\Middleware\\TrustProxies.php',
    'D:\\tmdt\\app\\Http\\Middleware\\VerifyCsrfToken.php',
    'D:\\tmdt\\app\\Models\\BaiViet.php',
    'D:\\tmdt\\app\\Models\\Banner.php',
    'D:\\tmdt\\app\\Models\\BinhLuan.php',
    'D:\\tmdt\\app\\Models\\ChiTietDonHang.php',
    'D:\\tmdt\\app\\Models\\DanhMuc.php',
    'D:\\tmdt\\app\\Models\\DonHang.php',
    'D:\\tmdt\\app\\Models\\HinhAnh.php',
    'D:\\tmdt\\app\\Models\\MaGiamGia.php',
    'D:\\tmdt\\app\\Models\\SanPham.php',
    'D:\\tmdt\\app\\Models\\Setting.php',
    'D:\\tmdt\\app\\Models\\User.php',
    'D:\\tmdt\\app\\Providers\\RouteServiceProvider.php',
    'D:\\tmdt\\app\\Services\\PayOSService.php',

    // resources/views/
    'D:\\tmdt\\resources\\views\\auth\\login.blade.php',
    'D:\\tmdt\\resources\\views\\auth\\register.blade.php',
    'D:\\tmdt\\resources\\views\\bai_viet\\index.blade.php',
    'D:\\tmdt\\resources\\views\\bai_viet\\show.blade.php',
    'D:\\tmdt\\resources\\views\\don_hang\\index.blade.php',
    'D:\\tmdt\\resources\\views\\gio_hang\\index.blade.php',
    'D:\\tmdt\\resources\\views\\gio_hang\\mua_ngay.blade.php',
    'D:\\tmdt\\resources\\views\\gio_hang\\thanh_toan.blade.php',
    'D:\\tmdt\\resources\\views\\gio_hang\\thanh_toan_ngan_hang.blade.php',
    'D:\\tmdt\\resources\\views\\layouts\\admin.blade.php',
    'D:\\tmdt\\resources\\views\\layouts\\app.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\bai_viet\\index.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\cai_dat\\index.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\danh_muc\\edit.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\danh_muc\\index.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\dashboard.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\don_hang\\index.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\don_hang\\show.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\nguoi_dung\\index.blade.php',
    'D:\\tmdt\\resources\\views\\quan_tri\\san_pham\\index.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\chi_tiet.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\danh_sach.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\form_sua.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\form_tao.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\index.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\show.blade.php',
    'D:\\tmdt\\resources\\views\\san_pham\\tim_kiem.blade.php',
    'D:\\tmdt\\resources\\views\\tai_khoan\\index.blade.php',
    'D:\\tmdt\\resources\\views\\tim_kiem\\index.blade.php',
    'D:\\tmdt\\resources\\views\\trang_chu.blade.php',

    // routes/
    'D:\\tmdt\\routes\\console.php',
    'D:\\tmdt\\routes\\web.php',
];

$processedCount = 0;
$modifiedCount = 0;

echo "=== Bắt đầu xử lý ===\n\n";

foreach ($files as $file) {
    if (file_exists($file)) {
        $processedCount++;
        if (processFile($file)) {
            $modifiedCount++;
        }
    } else {
        echo "SKIP: $file (not found)\n";
    }
}

echo "\n=== Hoàn thành ===\n";
echo "Tổng số file đã xử lý: $processedCount\n";
echo "Tổng số file đã thay đổi: $modifiedCount\n";

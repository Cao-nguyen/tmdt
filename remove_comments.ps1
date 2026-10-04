# Script PowerShell để xóa comment code
# Xóa PHP: // và /* */
# Xóa Blade: {{-- --}}

function Remove-PhpComments {
    param([string]$content)

    # Xóa comment nhiều dòng /* */
    $content = [regex]::Replace($content, '/\*[\s\S]*?\*/', '')

    # Xóa comment một dòng // (không xóa nếu trong string)
    $lines = $content -split "`n"
    $result = @()

    foreach ($line in $lines) {
        if ($line -match '//') {
            # Kiểm tra xem // có trong string không
            $beforeComment = $line.Substring(0, $line.IndexOf('//'))
            $singleQuoteCount = ($beforeComment.ToCharArray() | Where-Object { $_ -eq "'" } | Measure-Object).Count
            $doubleQuoteCount = ($beforeComment.ToCharArray() | Where-Object { $_ -eq '"' } | Measure-Object).Count

            # Nếu số lượng ngoặc là chẵn, // không nằm trong string
            if (($singleQuoteCount % 2 -eq 0) -and ($doubleQuoteCount % 2 -eq 0)) {
                $line = $beforeComment.TrimEnd()
            }
        }
        $result += $line
    }

    return $result -join "`n"
}

function Remove-BladeComments {
    param([string]$content)

    # Xóa comment Blade {{-- --}}
    $content = [regex]::Replace($content, '\{\{--[\s\S]*?--\}\}', '')
    return $content
}

function Process-File {
    param([string]$filePath)

    Write-Host "Processing: $filePath"

    try {
        $content = Get-Content $filePath -Raw -Encoding UTF8
        $originalContent = $content

        # Xóa comment PHP
        $content = Remove-PhpComments $content

        # Xóa comment Blade nếu là file blade
        if ($filePath -match '\.blade\.php$') {
            $content = Remove-BladeComments $content
        }

        # Nếu có thay đổi thì ghi lại
        if ($content -ne $originalContent) {
            Set-Content $filePath $content -Encoding UTF8 -NoNewline
            Write-Host "  ✓ Comments removed" -ForegroundColor Green
            return $true
        } else {
            Write-Host "  - No comments found" -ForegroundColor Gray
            return $false
        }
    } catch {
        Write-Host "  ERROR: $_" -ForegroundColor Red
        return $false
    }
}

# Danh sách file cần xử lý
$files = @(
    # app/
    'D:\tmdt\app\Console\Kernel.php',
    'D:\tmdt\app\Exceptions\Handler.php',
    'D:\tmdt\app\Http\Controllers\AdminController.php',
    'D:\tmdt\app\Http\Controllers\AuthController.php',
    'D:\tmdt\app\Http\Controllers\BaiVietController.php',
    'D:\tmdt\app\Http\Controllers\Controller.php',
    'D:\tmdt\app\Http\Controllers\DanhMucController.php',
    'D:\tmdt\app\Http\Controllers\DonHangController.php',
    'D:\tmdt\app\Http\Controllers\GioHangController.php',
    'D:\tmdt\app\Http\Controllers\SanPhamController.php',
    'D:\tmdt\app\Http\Controllers\TrangChuController.php',
    'D:\tmdt\app\Http\Controllers\UserController.php',
    'D:\tmdt\app\Http\Kernel.php',
    'D:\tmdt\app\Http\Middleware\EncryptCookies.php',
    'D:\tmdt\app\Http\Middleware\PreventRequestsDuringMaintenance.php',
    'D:\tmdt\app\Http\Middleware\TrimStrings.php',
    'D:\tmdt\app\Http\Middleware\TrustProxies.php',
    'D:\tmdt\app\Http\Middleware\VerifyCsrfToken.php',
    'D:\tmdt\app\Models\BaiViet.php',
    'D:\tmdt\app\Models\Banner.php',
    'D:\tmdt\app\Models\BinhLuan.php',
    'D:\tmdt\app\Models\ChiTietDonHang.php',
    'D:\tmdt\app\Models\DanhMuc.php',
    'D:\tmdt\app\Models\DonHang.php',
    'D:\tmdt\app\Models\HinhAnh.php',
    'D:\tmdt\app\Models\MaGiamGia.php',
    'D:\tmdt\app\Models\SanPham.php',
    'D:\tmdt\app\Models\Setting.php',
    'D:\tmdt\app\Models\User.php',
    'D:\tmdt\app\Providers\RouteServiceProvider.php',
    'D:\tmdt\app\Services\PayOSService.php',

    # resources/views/
    'D:\tmdt\resources\views\auth\login.blade.php',
    'D:\tmdt\resources\views\auth\register.blade.php',
    'D:\tmdt\resources\views\bai_viet\index.blade.php',
    'D:\tmdt\resources\views\bai_viet\show.blade.php',
    'D:\tmdt\resources\views\don_hang\index.blade.php',
    'D:\tmdt\resources\views\gio_hang\index.blade.php',
    'D:\tmdt\resources\views\gio_hang\mua_ngay.blade.php',
    'D:\tmdt\resources\views\gio_hang\thanh_toan.blade.php',
    'D:\tmdt\resources\views\gio_hang\thanh_toan_ngan_hang.blade.php',
    'D:\tmdt\resources\views\layouts\admin.blade.php',
    'D:\tmdt\resources\views\layouts\app.blade.php',
    'D:\tmdt\resources\views\quan_tri\bai_viet\index.blade.php',
    'D:\tmdt\resources\views\quan_tri\cai_dat\index.blade.php',
    'D:\tmdt\resources\views\quan_tri\danh_muc\edit.blade.php',
    'D:\tmdt\resources\views\quan_tri\danh_muc\index.blade.php',
    'D:\tmdt\resources\views\quan_tri\dashboard.blade.php',
    'D:\tmdt\resources\views\quan_tri\don_hang\index.blade.php',
    'D:\tmdt\resources\views\quan_tri\don_hang\show.blade.php',
    'D:\tmdt\resources\views\quan_tri\nguoi_dung\index.blade.php',
    'D:\tmdt\resources\views\quan_tri\san_pham\index.blade.php',
    'D:\tmdt\resources\views\san_pham\chi_tiet.blade.php',
    'D:\tmdt\resources\views\san_pham\danh_sach.blade.php',
    'D:\tmdt\resources\views\san_pham\form_sua.blade.php',
    'D:\tmdt\resources\views\san_pham\form_tao.blade.php',
    'D:\tmdt\resources\views\san_pham\index.blade.php',
    'D:\tmdt\resources\views\san_pham\show.blade.php',
    'D:\tmdt\resources\views\san_pham\tim_kiem.blade.php',
    'D:\tmdt\resources\views\tai_khoan\index.blade.php',
    'D:\tmdt\resources\views\tim_kiem\index.blade.php',
    'D:\tmdt\resources\views\trang_chu.blade.php',

    # routes/
    'D:\tmdt\routes\console.php',
    'D:\tmdt\routes\web.php'
)

Write-Host "=== Bắt đầu xử lý ===" -ForegroundColor Cyan
Write-Host ""

$processedCount = 0
$modifiedCount = 0

foreach ($file in $files) {
    if (Test-Path $file) {
        $processedCount++
        if (Process-File $file) {
            $modifiedCount++
        }
    } else {
        Write-Host "SKIP: $file (not found)" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "=== Hoàn thành ===" -ForegroundColor Cyan
Write-Host "Tổng số file đã xử lý: $processedCount"
Write-Host "Tổng số file đã thay đổi: $modifiedCount"

# 🚀 Laravel + Supabase - Hướng dẫn chi tiết

Dự án mẫu kết hợp Laravel framework với Supabase database, được viết với code dễ hiểu, ghi chú tiếng Việt và tên biến theo tiếng Việt/tiếng Anh cơ bản.

## 📋 Nội dung

- [Cấu trúc dự án](#cấu-trúc-dự-án)
- [Cài đặt môi trường](#cài-đặt-môi-trường)
- [Cấu hình Supabase](#cấu-hình-supabase)
- [Cấu hình Laragon](#cấu-hình-laragon)
- [Chạy dự án](#chạy-dự-án)
- [Hướng dẫn sử dụng](#hướng-dẫn-sử dụng)
- [Giải thích code](#giải-thích-code)

## 📁 Cấu trúc dự án

```
tmdt/
├── app/
│   ├── Models/                    # Các Model (dữ liệu)
│   │   ├── SanPham.php           # Model sản phẩm
│   │   └── DanhMuc.php           # Model danh mục
│   └── Http/
│       └── Controllers/          # Các Controller (xử lý logic)
│           └── SanPhamController.php
├── config/
│   ├── app.php                   # Cấu hình ứng dụng
│   └── database.php              # Cấu hình database
├── database/
│   ├── migrations/               # Các file migration (tạo bảng)
│   │   ├── 2024_01_01_000001_create_danh_mucs_table.php
│   │   └── 2024_01_01_000002_create_san_phams_table.php
│   └── seeders/                 # Dữ liệu mẫu
│       ├── DanhMucSeeder.php
│       └── SanPhamSeeder.php
├── resources/
│   └── views/                   # Các file giao diện (Blade)
│       ├── layouts/
│       │   └── app.blade.php   # Layout chính
│       ├── trang_chu.blade.php  # Trang chủ
│       └── san_pham/           # Views cho sản phẩm
│           ├── danh_sach.blade.php
│           ├── chi_tiet.blade.php
│           ├── form_tao.blade.php
│           ├── form_sua.blade.php
│           └── tim_kiem.blade.php
├── routes/
│   └── web.php                  # Định nghĩa các route
├── .env                         # File cấu hình môi trường
└── README.md                    # File hướng dẫn này
```

## 🔧 Cài đặt môi trường

### 1. Cài đặt Laragon

Nếu bạn chưa cài đặt Laragon:
1. Tải Laragon từ [laragon.org](https://laragon.org/download/)
2. Cài đặt Laragon (Full version recommended)
3. Khởi động Laragon
4. Bật Apache và MySQL

### 2. Kiểm tra PHP và Composer

Mở Terminal/CMD trong Laragon và kiểm tra:

```bash
php --version
composer --version
```

Nếu hiển thị phiên bản tức là đã cài đặt thành công.

## 🔑 Cấu hình Supabase

### 1. Tạo tài khoản Supabase

1. Truy cập [supabase.com](https://supabase.com)
2. Đăng ký tài khoản miễn phí
3. Tạo project mới (chọn region gần Việt Nam nhất)

### 2. Lấy thông tin kết nối

Sau khi tạo project, vào **Settings > Database**:

```
Connection String:
postgresql://postgres:[YOUR-PASSWORD]@db.[PROJECT-ID].supabase.co:5432/postgres
```

### 3. Cập nhật file .env

Mở file `.env` trong dự án và cập nhật thông tin:

```env
# Cấu hình Database Supabase
DB_CONNECTION=pgsql
DB_HOST=db.xxx.supabase.co              # Thay bằng host của bạn
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password_here          # Thay bằng password của bạn

# Cấu hình Supabase API
SUPABASE_URL=https://xxx.supabase.co    # Thay bằng URL của bạn
SUPABASE_KEY=your_anon_key              # Thay bằng Anon Key
SUPABASE_SERVICE_ROLE_KEY=your_service_role_key  # Thay bằng Service Role Key
```

**Lấy API Keys:**
- Vào **Settings > API**
- Copy `anon public` key vào `SUPABASE_KEY`
- Copy `service_role` key vào `SUPABASE_SERVICE_ROLE_KEY`

## ⚙️ Cấu hình Laragon

### 1. Tạo Virtual Host

1. Mở Laragon
2. Click chuột phải vào icon Laragon > **Apache > www**
3. Tạo thư mục `tmdt` trong `www`
4. Copy toàn bộ code dự án vào thư mục `tmdt`

### 2. Cấu hình Virtual Host

1. Mở Laragon
2. Click chuột phải > **Apache > name-based virtual host**
3. Thêm dòng sau:

```
tmdt.test
```

4. Restart Apache

Bây giờ bạn có thể truy cập: `http://tmdt.test`

## 🚀 Chạy dự án

### 1. Cài đặt dependencies (nếu cần)

Nếu dự án có file `composer.json`:

```bash
composer install
```

### 2. Tạo APP_KEY

```bash
php artisan key:generate
```

### 3. Chạy Migration (tạo bảng trong database)

```bash
php artisan migrate
```

### 4. Thêm dữ liệu mẫu

```bash
php artisan db:seed
```

Hoặc chạy seeder cụ thể:

```bash
php artisan db:seed --class=DanhMucSeeder
php artisan db:seed --class=SanPhamSeeder
```

### 5. Test kết nối database

Truy cập: `http://tmdt.test/test-database`

Nếu hiển thị JSON với `trang_thai: "thanh_cong"` tức là kết nối thành công!

## 📖 Hướng dẫn sử dụng

### Các trang chính

1. **Trang chủ**: `http://tmdt.test`
   - Hiển thị chào mừng và hướng dẫn nhanh

2. **Danh sách sản phẩm**: `http://tmdt.test/san-pham`
   - Xem tất cả sản phẩm
   - Tìm kiếm sản phẩm
   - Thêm/sửa/xóa sản phẩm

3. **Chi tiết sản phẩm**: `http://tmdt.test/san-pham/{id}`
   - Xem chi tiết sản phẩm
   - Xem thông tin tính toán

4. **Test Database**: `http://tmdt.test/test-database`
   - Kiểm tra kết nối Supabase

### Tạo sản phẩm mới

1. Vào trang danh sách sản phẩm
2. Click "➕ Thêm sản phẩm mới"
3. Điền thông tin vào form
4. Click "💾 Lưu sản phẩm"

### Sửa sản phẩm

1. Vào trang danh sách sản phẩm
2. Click nút "✏️" ở sản phẩm muốn sửa
3. Sửa thông tin
4. Click "💾 Cập nhật sản phẩm"

### Xóa sản phẩm

1. Vào trang danh sách sản phẩm
2. Click nút "🗑️" ở sản phẩm muốn xóa
3. Xác nhận xóa

## 💡 Giải thích code

### 1. Model (SanPham.php)

Model đại diện cho bảng trong database:

```php
class SanPham extends Model
{
    protected $table = 'san_phams';        // Tên bảng
    protected $fillable = [               // Các trường có thể gán
        'ten_san_pham', 'mo_ta', 'gia', ...
    ];
    
    // Hàm tính tổng giá trị
    public function TongGiaTri()
    {
        return $this->gia * $this->so_luong;
    }
}
```

### 2. Controller (SanPhamController.php)

Controller xử lý logic và giao tiếp với Model:

```php
public function HienThiDanhSach()
{
    $danhSachSanPham = SanPham::all();  // Lấy tất cả sản phẩm
    return view('san_pham.danh_sach', compact('danhSachSanPham'));
}

public function LuuSanPhamMoi(Request $request)
{
    // Validate dữ liệu
    $request->validate([
        'ten_san_pham' => 'required',
        'gia' => 'required|numeric',
    ]);
    
    // Tạo sản phẩm mới
    SanPham::create([
        'ten_san_pham' => $request->ten_san_pham,
        'gia' => $request->gia,
        // ...
    ]);
}
```

### 3. Route (web.php)

Route định nghĩa URL và gán vào Controller:

```php
// Route hiển thị danh sách
Route::get('/san-pham', [SanPhamController::class, 'HienThiDanhSach'])
     ->name('san-pham.danh-sach');

// Route lưu sản phẩm mới
Route::post('/san-pham/luu-moi', [SanPhamController::class, 'LuuSanPhamMoi'])
     ->name('san-pham.luu-moi');
```

### 4. View (Blade Template)

View hiển thị giao diện HTML:

```php
@extends('layouts.app')              // Kế thừa layout chính

@section('tieude', 'Danh sách sản phẩm')  // Tiêu đề trang

@section('noi-dung')
    <!-- Nội dung chính -->
    @foreach($danhSachSanPham as $sanPham)
        <p>{{ $sanPham->ten_san_pham }}</p>
    @endforeach
@endsection
```

### 5. Migration

Migration tạo bảng trong database:

```php
public function up()
{
    Schema::create('san_phams', function (Blueprint $table) {
        $table->id();                    // ID tự động tăng
        $table->string('ten_san_pham');  // Tên sản phẩm
        $table->decimal('gia', 15, 2);  // Giá (15 chữ số, 2 thập phân)
        $table->timestamps();            // created_at, updated_at
    });
}
```

## 🐛 Xử lý lỗi thường gặp

### Lỗi kết nối database

**Kiểm tra:**
1. File `.env` đã cập nhật đúng thông tin Supabase chưa?
2. Password trong Supabase có đúng không?
3. Database trong Supabase đã được kích hoạt chưa?

**Giải pháp:**
- Vào Supabase > Settings > Database > Reset Database Password
- Cập nhật lại password trong file `.env`

### Lỗi 500 Internal Server Error

**Kiểm tra:**
1. Xem file log: `storage/logs/laravel.log`
2. Kiểm tra quyền thư mục: `storage` và `bootstrap/cache`

**Giải pháp:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Lỗi Migration

**Kiểm tra:**
1. Bảng đã tồn tại chưa?
2. Có lỗi trong file migration không?

**Giải pháp:**
```bash
# Rollback migration cuối cùng
php artisan migrate:rollback

# Reset toàn bộ migration
php artisan migrate:fresh
```

## 📚 Tài liệu tham khảo

- [Laravel Documentation](https://laravel.com/docs)
- [Supabase Documentation](https://supabase.com/docs)
- [Laragon Documentation](https://laragon.org/docs)

## 🎯 Các bước tiếp theo

1. **Thêm chức năng đăng nhập/đăng ký**
2. **Tải lên hình ảnh sản phẩm**
3. **Tạo giỏ hàng**
4. **Tích hợp thanh toán**
5. **Tạo API cho mobile app**

## 📝 Lưu ý quan trọng

- Mọi biến và hàm đều có ghi chú tiếng Việt
- Tên biến sử dụng tiếng Việt hoặc tiếng Anh cơ bản
- Code được viết dễ hiểu nhất có thể
- Luôn test kỹ trước khi đưa vào production

---

**Chúc bạn thành công với Laravel + Supabase! 🎉**

Nếu có vấn đề, hãy kiểm tra:
1. File `.env` đã cấu hình đúng chưa
2. Kết nối database có ổn không
3. Các file có lỗi syntax không

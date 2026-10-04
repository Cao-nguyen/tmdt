# HƯỚNG DẪN CHẠY DỰ ÁN FLOWER SHOP

Dự án: Laravel 10.50.3, PHP 8.3.33, PostgreSQL (Supabase)

---

## 1. CÀI ĐẶT LARAGON + CÔNG CỤ

### Laragon
- Tải: https://laragon.org/download
- Cài **Laragon Full** (bao gồm Apache, PHP, Node.js)
- Mở Laragon, Start Apache

### Composer
- Tải: https://getcomposer.org/Composer-Setup.exe
- Cài đặt mặc định

---

## 2. GIẢI NÉN TỆP DỰ ÁN

- Giải nén file `.zip` nhận được
- Copy thư mục vào `C:\laragon\www\tmdt`

---

## 3. LẤY CẤU HÌNH CẦN THIẾT

### Supabase (Database)
- Truy cập: https://supabase.com/
- Đăng ký → New Project
- Lấy thông tin:
  - Host
  - Port (5432)
  - Database (postgres)
  - Username
  - Password
  - Supabase URL, Key, Project ID

### Cloudinary (Upload ảnh)
- Truy cập: https://cloudinary.com/
- Đăng ký → Lấy:
  - Cloud Name
  - API Key
  - API Secret
  - Cloudinary URL

### PayOS (Thanh toán - tùy chọn)
- Truy cập: https://payos.vn/
- Đăng ký → Lấy:
  - Client ID
  - API Key
  - Checksum Key

---

## 4. MỞ DỰ ÁN TRONG VS CODE

Mở VS Code → File → Open Folder → Chọn `C:\laragon\www\tmdt`

### Chạy npm run dev
Mở Terminal trong VS Code:
```bash
npm run dev
```
(Giữ Terminal này mở để compile assets)

---

## 5. CẤU HÌNH FILE .ENV

Mở file `.env` trong VS Code, chỉnh sửa:

```env
APP_URL=http://tmdt.test

DB_CONNECTION=pgsql
DB_HOST=your-supabase-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=your-username
DB_PASSWORD=your-password

SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_KEY=your-anon-key
SUPABASE_PROJECT_ID=your-project-id

CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-secret
CLOUDINARY_URL=cloudinary://your-url

PAYOS_CLIENT_ID=your-client-id
PAYOS_API_KEY=your-api-key
PAYOS_CHECKSUM_KEY=your-checksum-key
```

---

## 6. MỞ LARAGON → START → TERMINAL

Mở Laragon → Click chuột vào thư mục `tmdt` → Open in Terminal

### Cài đặt thư viện
```bash
composer install
```

### Tạo app key
```bash
php artisan key:generate
```

### Chạy migrations và seeders
```bash
php artisan migrate
php artisan db:seed
```

### Chạy server
```bash
php artisan serve
```

---

## 7. CẤU HÌNH VIRTUAL HOST (NẾU MUỐN)

Nếu muốn dùng domain `tmdt.test` thay vì `localhost:8000`:

### Tạo file tmdt.conf
- Laragon → Menu → Apache → sites-enabled
- Tạo file `tmdt.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/laragon/www/tmdt/public"
    ServerName tmdt.test
    <Directory "C:/laragon/www/tmdt/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Chỉnh sửa file hosts
- Mở `C:\Windows\System32\drivers\etc\hosts` (quyền Admin)
- Thêm: `127.0.0.1 tmdt.test`

### Restart Apache
- Laragon → Stop All → Start All

---

## 8. TRUY CẬP WEBSITE

**Dùng php artisan serve:**
```
http://localhost:8000
```

**Dùng Virtual Host:**
```
http://tmdt.test
```

**Admin Dashboard:**
- URL: `/admin`
- Email: `admin123@gmail.com`
- Mật khẩu: `admin123`

---

## 9. CÁC LỆNH THƯỜNG DÙNG

```bash
# Xóa cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Tạo lại database (cẩn thận)
php artisan migrate:fresh --seed

# Storage link
php artisan storage:link
```

---

## 10. KHẮC PHỤC SỰ CỐ

| Lỗi | Giải pháp |
|-----|-----------|
| No encryption key | `php artisan key:generate` |
| Database failed | Kiểm tra `.env` thông tin Supabase |
| Không upload ảnh | Chạy `php artisan storage:link` |
| Composer not found | Cài Composer, thêm vào PATH |
| npm not found | Cài Node.js |

---

Chúc chạy thành công! 🌸

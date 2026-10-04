<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * AdminSeeder - Tạo tài khoản admin mặc định
 * Tạo tài khoản admin với username highlands_2009
 */
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem admin đã tồn tại chưa
        $existingAdmin = User::where('ten_dang_nhap', 'highlands_2009')->first();
        
        if ($existingAdmin) {
            $this->command->warn('Admin user "highlands_2009" đã tồn tại!');
            return;
        }

        // Tạo tài khoản admin
        User::create([
            'ho_ten' => 'Admin User',
            'ten_dang_nhap' => 'highlands_2009',
            'so_dien_thoai' => '0901234567',
            'mat_khau' => Hash::make('admin123'), // Mật khẩu mặc định: admin123
            'trang_thai' => true,
        ]);

        $this->command->info('Đã tạo tài khoản admin thành công!');
        $this->command->info('Username: highlands_2009');
        $this->command->info('Mật khẩu: admin123');
        $this->command->warn('⚠️  Vui lòng đổi mật khẩu sau khi đăng nhập!');
    }
}
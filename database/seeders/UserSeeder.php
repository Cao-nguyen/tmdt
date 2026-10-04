<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin
        $admin = User::where('email', 'admin@flowershop.com')->first();
        if (!$admin) {
            User::create([
                'ho_ten' => 'Admin System',
                'email' => 'admin@flowershop.com',
                'ten_dang_nhap' => 'admin_system',
                'mat_khau' => Hash::make('admin123'),
                'role' => 'admin',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Tạo tài khoản User mẫu
        $user = User::where('email', 'user@flowershop.com')->first();
        if (!$user) {
            User::create([
                'ho_ten' => 'Nguyễn Văn A',
                'email' => 'user@flowershop.com',
                'ten_dang_nhap' => 'user_flower',
                'mat_khau' => Hash::make('user123'),
                'role' => 'khach_hang',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Tạo thêm 5 người dùng random
        for ($i = 1; $i <= 5; $i++) {
            $existingUser = User::where('email', 'user' . $i . '@example.com')->first();
            if (!$existingUser) {
                User::create([
                    'ho_ten' => 'Người dùng ' . $i,
                    'email' => 'user' . $i . '@example.com',
                    'ten_dang_nhap' => 'user_' . $i . '_' . time(),
                    'mat_khau' => Hash::make('password123'),
                    'role' => 'khach_hang',
                    'trang_thai' => true,
                    'email_verified_at' => now(),
                ]);
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}

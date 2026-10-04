<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * UpdateAdminRoleSeeder - Cập nhật role admin cho user hiện có
 * Cập nhật role của user highlands_2009 thành admin
 */
class UpdateAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tìm user có username highlands_2009
        $user = User::where('ten_dang_nhap', 'highlands_2009')->first();
        
        if (!$user) {
            $this->command->error('Không tìm thấy user có username "highlands_2009"!');
            return;
        }

        // Cập nhật role thành admin
        $user->role = 'admin';
        $user->save();

        $this->command->info('Đã cập nhật role thành admin cho user: ' . $user->ten_dang_nhap);
        $this->command->info('Họ tên: ' . $user->ho_ten);
    }
}
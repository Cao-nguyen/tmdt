<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Contact info
            ['key' => 'contact_address', 'value' => '123 Đường Hoa, Quận 1, TP. Hồ Chí Minh', 'type' => 'text', 'group' => 'contact', 'label' => 'Địa chỉ'],
            ['key' => 'contact_phone', 'value' => '090 123 4567', 'type' => 'phone', 'group' => 'contact', 'label' => 'Số điện thoại'],
            ['key' => 'contact_email', 'value' => 'info@flowershop.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Email'],
            ['key' => 'contact_hours', 'value' => '8:00 - 20:00 hàng ngày', 'type' => 'text', 'group' => 'contact', 'label' => 'Giờ làm việc'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('email')->unique();
            $table->string('so_dien_thoai')->unique()->nullable();
            $table->string('ten_dang_nhap')->unique()->nullable();
            $table->string('mat_khau');
            $table->string('anh_dai_dien')->nullable();
            $table->string('dia_chi')->nullable();
            $table->enum('role', ['admin', 'nhan_vien', 'khach_hang'])->default('khach_hang');
            $table->boolean('trang_thai')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

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
        Schema::create('ma_giam_gias', function (Blueprint $table) {
            $table->id();
            $table->string('ma_giam_gia')->unique();
            $table->string('ten_ma');
            $table->enum('loai_giam_gia', ['phan_tram', 'tien_mat'])->default('phan_tram');
            $table->decimal('gia_tri_giam', 15, 2);
            $table->decimal('don_toi_thieu', 15, 2)->default(0);
            $table->decimal('giam_toi_da', 15, 2)->nullable();
            $table->integer('so_luong')->default(1);
            $table->integer('so_luong_da_dung')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('ngay_bat_dau');
            $table->timestamp('ngay_ket_thuc');
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ma_giam_gias');
    }
};

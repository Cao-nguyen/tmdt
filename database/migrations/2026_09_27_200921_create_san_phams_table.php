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
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danh_muc_id')->constrained('danh_mucs')->onDelete('cascade');
            $table->string('ten_san_pham');
            $table->string('slug')->unique();
            $table->text('mo_ta_ngan')->nullable();
            $table->text('mo_ta_chi_tiet')->nullable();
            $table->decimal('gia_nhap', 15, 2)->default(0);
            $table->decimal('gia_ban', 15, 2);
            $table->decimal('gia_khuyen_mai', 15, 2)->nullable();
            $table->integer('so_luong')->default(0);
            $table->string('mau_sac')->nullable();
            $table->string('kich_thuoc')->nullable();
            $table->string('nguon_goc')->nullable();
            $table->integer('luot_xem')->default(0);
            $table->integer('luot_mua')->default(0);
            $table->boolean('noi_bat')->default(false);
            $table->boolean('san_pham_moi')->default(false);
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};

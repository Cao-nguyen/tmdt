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
        Schema::create('bai_viets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->text('mo_ta_ngan')->nullable();
            $table->text('noi_dung');
            $table->string('hinh_anh')->nullable();
            $table->boolean('noi_bat')->default(false);
            $table->boolean('trang_thai')->default(true);
            $table->integer('luot_xem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_viets');
    }
};

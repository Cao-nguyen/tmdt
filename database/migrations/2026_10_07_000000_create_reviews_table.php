<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->foreignId('don_hang_id')->nullable()->constrained('don_hangs')->onDelete('cascade');
            $table->integer('danh_gia')->default(5); // 1-5 sao
            $table->text('noi_dung')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'san_pham_id'])->unique(); // Mỗi user chỉ đánh giá 1 sản phẩm 1 lần
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};

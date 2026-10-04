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
        Schema::create('don_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ma_don_hang')->unique();
            $table->string('ho_ten_nguoi_nhan');
            $table->string('so_dien_thoai_nguoi_nhan');
            $table->string('email_nguoi_nhan')->nullable();
            $table->string('dia_chi_giao');
            $table->string('thanh_pho')->nullable();
            $table->string('quan_huyen')->nullable();
            $table->decimal('tong_tien', 15, 2);
            $table->decimal('tong_giam_gia', 15, 2)->default(0);
            $table->decimal('thanh_tien', 15, 2);
            $table->foreignId('ma_giam_gia_id')->nullable();
            // Foreign key sẽ được thêm sau khi bảng ma_giam_gias được tạo
            $table->enum('phuong_thuc_thanh_toan', ['cod', 'chuyen_khoan', 'the_tin_dung'])->default('cod');
            $table->enum('trang_thai', ['cho_xac_nhan', 'da_xac_nhan', 'dang_chuan_bi', 'dang_giao', 'da_giao', 'da_huy'])->default('cho_xac_nhan');
            $table->text('ghi_chu')->nullable();
            $table->timestamp('ngay_giao_du_kien')->nullable();
            $table->timestamp('ngay_giao_thuc_te')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hangs');
    }
};

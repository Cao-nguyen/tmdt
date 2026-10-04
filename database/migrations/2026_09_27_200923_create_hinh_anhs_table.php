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
        Schema::create('hinh_anhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->string('duong_dan');
            $table->boolean('anh_chinh')->default(false);
            $table->integer('sap_xep')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinh_anhs');
    }
};

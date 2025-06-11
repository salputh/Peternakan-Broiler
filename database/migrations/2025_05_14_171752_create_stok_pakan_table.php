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
        Schema::create('stok_pakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->index('periode_id');
            $table->foreignId('kandang_id')->constrained('kandangs')->onDelete('cascade');
            $table->index('kandang_id');
            $table->foreignId('pakan_jenis_id')->constrained('pakan_jenis')->onDelete('cascade');
            $table->index('pakan_jenis_id');
            $table->integer('jumlah_stok')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_pakans');
    }
};

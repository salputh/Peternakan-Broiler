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
        Schema::create('stok_obat_masuk', function (Blueprint $table) {
            $table->id();
            $table->text('nama_obat_input')->nullable(false);
            $table->text('kategori_input')->nullable(false);
            $table->foreignId('stok_obat_id')->constrained('stok_obat')->onDelete('cascade');
            $table->index('stok_obat_id');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->index('periode_id');
            $table->foreignId('kandang_id')->constrained('kandangs')->onDelete('cascade');
            $table->index('kandang_id');
            $table->date('tanggal');
            $table->integer('jumlah_masuk');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_obat_masuk');
    }
};

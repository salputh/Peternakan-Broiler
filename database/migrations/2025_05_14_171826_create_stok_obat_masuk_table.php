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
            $table->bigIncrements('id');
            $table->text('nama_obat_input');
            $table->text('kategori_input');
            $table->bigInteger('stok_obat_id')->unsigned();
            $table->foreign('stok_obat_id')
                ->references('id')
                ->on('stok_obat')
                ->onDelete('cascade');
            $table->bigInteger('periode_id')->unsigned();
            $table->foreign('periode_id')
                ->references('id')
                ->on('periodes')
                ->onDelete('cascade');
            $table->bigInteger('kandang_id')->unsigned();
            $table->foreign('kandang_id')
                ->references('id')
                ->on('kandangs')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah_masuk');

            // Gunakan Laravel timestamps (lebih sederhana dan reliable)
            $table->timestamps();
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

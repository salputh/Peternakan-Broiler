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
        Schema::create('stok_obat_keluar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stok_obat_id')->unsigned();
            $table->foreign('stok_obat_id')
                ->references('id')
                ->on('stok_obat')
                ->onDelete('cascade');
            $table->bigInteger('data_periode_id')->unsigned();
            $table->foreign('data_periode_id')
                ->references('id')
                ->on('data_periodes')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah_keluar');
            $table->timestamps();

            $table->index('stok_obat_id');
            $table->index('data_periode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_obat_keluar');
    }
};

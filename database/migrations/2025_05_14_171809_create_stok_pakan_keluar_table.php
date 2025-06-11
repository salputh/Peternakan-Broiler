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
        Schema::create('stok_pakan_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_pakan_id')->constrained('stok_pakans')->onDelete('cascade');
            $table->index('stok_pakan_id');
            $table->foreignId('data_periode_id')->constrained('data_periodes')->onDelete('cascade');
            $table->index('data_periode_id');
            $table->date('tanggal');
            $table->integer('jumlah_keluar');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_pakan_keluar');
    }
};

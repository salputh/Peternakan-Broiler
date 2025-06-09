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
        Schema::create('deplesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_periode_id')->constrained('data_periodes')->onDelete('cascade');
            $table->index('data_periode_id');
            $table->integer('jumlah_mati')->default(0);
            $table->integer('jumlah_afkir')->default(0);
            $table->string('foto')->nullable();
            $table->string('keterangan')->nullable();
            $table->date('tanggal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deplesis');
    }
};

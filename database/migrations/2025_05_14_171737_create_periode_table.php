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
        Schema::create('periodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('kandang_id')->unsigned();
            $table->string('nama_periode');
            $table->string('slug')->unique();
            $table->date('tanggal_mulai');
            $table->boolean('aktif')->default(true);
            $table->integer('jumlah_ayam');
            $table->timestamps();

            $table->foreign('kandang_id')
                ->references('id')
                ->on('kandangs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};

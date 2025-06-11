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
            $table->bigIncrements('id');
            $table->bigInteger('data_periode_id');
            $table->foreign('data_periode_id')
                ->references('id')
                ->on('data_periodes')
                ->onDelete('cascade');
            $table->integer('jumlah_mati')->default(0);
            $table->integer('jumlah_afkir')->default(0);
            $table->text('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->index('data_periode_id');
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

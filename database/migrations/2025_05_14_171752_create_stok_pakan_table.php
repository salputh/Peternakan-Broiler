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
            $table->bigIncrements('id');
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
            $table->bigInteger('pakan_jenis_id')->unsigned();
            $table->foreign('pakan_jenis_id')
                ->references('id')
                ->on('pakan_jenis')
                ->onDelete('cascade');
            $table->integer('jumlah_stok')->default(0);
            $table->timestamps();

            $table->index('periode_id');
            $table->index('kandang_id');
            $table->index('pakan_jenis_id');
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

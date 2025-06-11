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
        Schema::create('stok_pakan_masuk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stok_pakan_id')->unsigned();
            $table->foreign('stok_pakan_id')
                ->references('id')
                ->on('stok_pakans')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah_masuk');
            $table->timestamps();
        });

        // Create index separately for PostgreSQL
        Schema::table('stok_pakan_masuk', function (Blueprint $table) {
            $table->index('stok_pakan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_pakan_masuk');
    }
};

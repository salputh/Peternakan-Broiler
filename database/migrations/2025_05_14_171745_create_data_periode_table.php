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
        Schema::create('data_periodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('periode_id')->unsigned();
            $table->foreign('periode_id')
                ->references('id')
                ->on('periodes')
                ->onDelete('cascade');
            $table->integer('usia');
            $table->decimal('suhu_min', 8, 2);
            $table->decimal('suhu_max', 8, 2);
            $table->decimal('kelembapan', 8, 2);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_periodes');
    }
};

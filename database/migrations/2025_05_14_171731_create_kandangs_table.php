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
        Schema::create('kandangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_kandang', 100);
            $table->string('slug');
            $table->bigInteger('peternakan_id')->unsigned();
            $table->integer('kapasitas');
            $table->string('alamat', 255);
            $table->timestamps();

            $table->unique('slug');
            $table->foreign('peternakan_id')
                ->references('id')
                ->on('peternakans')
                ->onDelete('cascade');
            $table->index('peternakan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandangs');
    }
};

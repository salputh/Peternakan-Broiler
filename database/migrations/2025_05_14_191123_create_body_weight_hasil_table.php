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
        Schema::create('body_weight', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('data_periode_id');
            $table->foreign('data_periode_id')
                ->references('id')
                ->on('data_periodes')
                ->onDelete('cascade');
            $table->integer('body_weight_hasil');
            $table->date('tanggal');
            $table->timestamps();
        });

        // Add index separately for PostgreSQL
        Schema::table('body_weight', function (Blueprint $table) {
            $table->index('data_periode_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_weight');
    }
};

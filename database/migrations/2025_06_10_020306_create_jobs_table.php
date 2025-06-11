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
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue');
            $table->text('payload');
            $table->smallInteger('attempts')->unsigned();
            $table->integer('reserved_at')->unsigned()->nullable();
            $table->integer('available_at')->unsigned();
            $table->integer('created_at')->unsigned();

            // Create index for PostgreSQL
            $table->index('queue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};

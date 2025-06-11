<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pakan_jenis', function (Blueprint $table) {
            $table->id()->comment('Primary key');
            $table->string('kode', 10)->comment('Feed type code');
            $table->text('keterangan')->comment('Feed type description');
            $table->string('satuan', 10)->default('zak')->comment('Unit of measurement');
            $table->timestamps();
        });

        // Set the table engine to InnoDB for PostgreSQL compatibility
        DB::statement('ALTER TABLE pakan_jenis SET (autovacuum_enabled = true)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakan_jenis');
    }
};

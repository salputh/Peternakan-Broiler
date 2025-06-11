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
        Schema::create('stok_obat', function (Blueprint $table) {
            $table->bigIncrements('id'); // Using 'obat_id' as primary key
            $table->string('nama_obat'); // Nama obat harus unik
            $table->string('kategori')->nullable(); // Kategori bisa kosong (NULL)
            $table->string('satuan')->nullable(); // Satuan bisa kosong (NULL)
            $table->timestampsTz(); // created_at and updated_at with timezone

            // Add unique constraint separately
            $table->unique('nama_obat');

            // Set the table engine for PostgreSQL
            $table->engine = 'InnoDB';
            // Use UUID if needed
            // $table->uuid('id')->primary();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_obat');
    }
};

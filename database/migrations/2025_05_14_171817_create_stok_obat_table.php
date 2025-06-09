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
            $table->id(); // Menggunakan 'obat_id' sebagai primary key
            $table->string('nama_obat')->unique(); // Nama obat harus unik
            $table->string('kategori')->nullable(); // Kategori bisa kosong (NULL)
            $table->string('satuan')->nullable(); // Satuan bisa kosong (NULL)
            $table->timestamps(); // created_at dan updated_at
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

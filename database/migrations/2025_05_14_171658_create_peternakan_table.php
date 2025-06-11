<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
      {
            // 1) Buat tabel peternakan + owner_id FK → users.user_id
            Schema::create('peternakans', function (Blueprint $table) {
                  $table->id();
                  $table->string('nama');
                  $table->string('slug')->unique();

                  $table->foreignId('owner_id')->nullable()
                        ->constrained('users')
                        ->nullOnDelete(); // Laravel-style FK constraint

                  $table->boolean('is_active')->default(true);

                  $table->timestamps();
            });

            // 2) Sekarang tambahkan FK users.peternakan_id → peternakan.peternakan_id
            Schema::table('users', function (Blueprint $table) {
                  $table->foreign('peternakan_id')
                        ->references('id')
                        ->on('peternakans')
                        ->onDelete('set null');
            });
      }

      public function down(): void
      {
            // Hapus FK di users dulu
            Schema::table('users', function (Blueprint $table) {
                  $table->dropForeign(['peternakan_id']);
            });

            // Hapus FK di peternakan
            Schema::table('peternakans', function (Blueprint $table) {
                  $table->dropForeign(['owner_id']);
            });

            // Drop tabel peternakan
            Schema::dropIfExists('peternakans');
      }
};

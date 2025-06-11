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
                  $table->bigIncrements('id');
                  $table->string('nama', 255);
                  $table->string('slug', 255)->unique();

                  $table->bigInteger('owner_id')->unsigned()->nullable();
                  $table->foreign('owner_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('set null'); // PostgreSQL-style FK constraint

                  $table->boolean('is_active')->default(true);

                  $table->timestampsTz();
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

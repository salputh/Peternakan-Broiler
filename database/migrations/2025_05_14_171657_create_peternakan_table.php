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

                  // Hanya kolom tanpa foreign key constraint
                  $table->bigInteger('owner_id')->unsigned()->nullable();

                  $table->boolean('is_active')->default(true);
                  $table->timestampsTz();
            });

            // Hapus bagian Schema::table('users') juga
      }

      public function down(): void
      {
            // Hapus FK di peternakan
            Schema::table('peternakans', function (Blueprint $table) {
                  $table->dropForeign(['owner_id']);
            });

            // Drop tabel peternakan
            Schema::dropIfExists('peternakans');
      }
};

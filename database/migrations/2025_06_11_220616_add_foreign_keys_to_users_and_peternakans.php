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
        // Tambahkan foreign key di tabel peternakans
        Schema::table('peternakans', function (Blueprint $table) {
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        // Tambahkan foreign key di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('peternakan_id')
                ->references('id')
                ->on('peternakans')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dari tabel users terlebih dahulu
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peternakan_id']);
        });

        // Kemudian hapus foreign key dari tabel peternakans
        Schema::table('peternakans', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
        });
    }
};

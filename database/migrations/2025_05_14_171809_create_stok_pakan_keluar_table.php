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
        Schema::create('stok_pakan_keluar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('stok_pakan_id')
                ->constrained('stok_pakans')
                ->onDelete('cascade');
            $table->index('stok_pakan_id');
            $table->foreignId('data_periode_id')
                ->constrained('data_periodes')
                ->onDelete('cascade');
            $table->index('data_periode_id');
            $table->date('tanggal');
            $table->integer('jumlah_keluar');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Add trigger for auto-updating updated_at
        DB::unprepared('
            CREATE TRIGGER update_stok_pakan_keluar_timestamp 
            BEFORE UPDATE ON stok_pakan_keluar
            FOR EACH ROW
            EXECUTE PROCEDURE update_timestamp_column();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop trigger first
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_pakan_keluar_timestamp ON stok_pakan_keluar');

        Schema::dropIfExists('stok_pakan_keluar');
    }
};

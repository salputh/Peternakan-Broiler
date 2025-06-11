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
        Schema::create('stok_obat_masuk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nama_obat_input');
            $table->text('kategori_input');
            $table->bigInteger('stok_obat_id')->unsigned();
            $table->foreign('stok_obat_id')
                ->references('id')
                ->on('stok_obat')
                ->onDelete('cascade');
            $table->bigInteger('periode_id')->unsigned();
            $table->foreign('periode_id')
                ->references('id')
                ->on('periodes')
                ->onDelete('cascade');
            $table->bigInteger('kandang_id')->unsigned();
            $table->foreign('kandang_id')
                ->references('id')
                ->on('kandangs')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah_masuk');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Add trigger for auto-updating updated_at
        DB::unprepared('
            CREATE TRIGGER update_stok_obat_masuk_timestamp 
            BEFORE UPDATE ON stok_obat_masuk
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
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_obat_masuk_timestamp ON stok_obat_masuk');

        Schema::dropIfExists('stok_obat_masuk');
    }
};

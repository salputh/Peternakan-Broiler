<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('slug');
            $table->text('email');
            $table->text('phone');
            $table->text('password');
            $table->text('photo')->nullable();
            $table->bigInteger('peternakan_id')->unsigned()->nullable();
            $table->timestamps();

            $table->unique('slug');
            $table->unique('email');
        });

        // Tambahkan enum constraint setelah tabel dibuat
        DB::statement("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'owner'");
        DB::statement("ALTER TABLE users ADD CONSTRAINT check_role CHECK (role IN ('developer', 'owner', 'manager', 'operator'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

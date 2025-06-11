<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->text('role')->default('owner');
            $table->text('photo')->nullable();

            // FK ke peternakan nanti ditambahkan di migration peternakan
            $table->bigInteger('peternakan_id')->unsigned()->nullable();
            $table->foreign('peternakan_id')
                ->references('id')
                ->on('peternakans')
                ->onDelete('set null');

            $table->timestamps();

            $table->unique('slug');
            $table->unique('email');
            $table->check("role in ('developer', 'owner', 'manager', 'operator')");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

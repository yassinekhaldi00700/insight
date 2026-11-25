<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The application's users table (not Laravel's default one).
 * `departement` is a temporary text column: the 2026_11_24_000001 migration
 * moves it into the departements table and drops it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('email')->unique('email');
            $table->string('image')->nullable();
            $table->string('password');
            $table->integer('management_id')->default(0);
            $table->string('departement')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

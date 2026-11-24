<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Moves departments out of the free-text users.departement column into their own table.
 * users.departement_id now points to departements.id.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('departement_id')->nullable()->after('departement')
                ->constrained('departements')->restrictOnDelete();
        });

        // Copy existing department names into the new table, then link each user to it
        $names = DB::table('users')
            ->whereNotNull('departement')
            ->whereRaw("TRIM(departement) <> ''")
            ->selectRaw('DISTINCT TRIM(departement) AS name')
            ->pluck('name');

        foreach ($names as $name) {
            $id = DB::table('departements')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')
                ->whereRaw('TRIM(departement) = ?', [$name])
                ->update(['departement_id' => $id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('departement');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('departement')->nullable()->after('departement_id');
        });

        DB::statement('UPDATE users u JOIN departements d ON d.id = u.departement_id SET u.departement = d.name');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('departement_id');
        });

        Schema::dropIfExists('departements');
    }
};

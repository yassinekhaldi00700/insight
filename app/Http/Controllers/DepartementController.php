<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartementController extends Controller
{
    /**
     * List departments with their number of users.
     */
    public function index()
    {
        $departements = DB::table('departements as d')
            ->leftJoin('users as u', 'u.departement_id', '=', 'd.id')
            ->select('d.id', 'd.name', 'd.created_at', DB::raw('COUNT(u.id) as users_count'))
            ->groupBy('d.id', 'd.name', 'd.created_at')
            ->orderBy('d.name')
            ->get();

        return view('dashboard.departements', compact('departements'));
    }

    public function store(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departements,name',
        ], [
            'name.unique' => 'Ce département existe déjà.',
        ]);

        DB::table('departements')->insert([
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.departements')
            ->with('success', 'Département « ' . $validated['name'] . ' » ajouté.');
    }

    public function update(Request $request, int $id)
    {
        abort_unless(DB::table('departements')->where('id', $id)->exists(), 404);

        $request->merge(['name' => trim((string) $request->input('name'))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departements', 'name')->ignore($id)],
        ], [
            'name.unique' => 'Ce département existe déjà.',
        ]);

        DB::table('departements')->where('id', $id)->update([
            'name' => $validated['name'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.departements')
            ->with('success', 'Département renommé en « ' . $validated['name'] . ' ».');
    }

    public function destroy(int $id)
    {
        $departement = DB::table('departements')->where('id', $id)->first();
        abort_unless($departement, 404);

        $usersCount = DB::table('users')->where('departement_id', $id)->count();
        if ($usersCount > 0) {
            return redirect()->route('admin.departements')
                ->withErrors(['error' => "Impossible de supprimer « {$departement->name} » : {$usersCount} collaborateur(s) y sont rattachés."]);
        }

        DB::table('departements')->where('id', $id)->delete();

        return redirect()->route('admin.departements')
            ->with('success', 'Département « ' . $departement->name . ' » supprimé.');
    }
}

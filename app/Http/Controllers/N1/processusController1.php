<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class processusController1 extends Controller1
{

   
public function showForm($matricule, $name)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $matricule,'role' => 'n1'],
            [
                'current_page' => 0,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

    return view('N1.part0', compact('matricule', 'name'));
}


    public function submitForm(Request $request,$matricule, $name)
{
    
    $userId = session('user_id');
    
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    if ($request->has('save_quit')) {

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role' => 'n1'],
            [
                'current_page' => 0,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('show.page1')->with('success', 'Données enregistrées avec succès.');
    }


    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $matricule,'role' => 'n1'],
        [
            'current_page' => 1,
            'role' => 'n1',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('partN11', ['matricule' => $matricule, 'name' => $name])->with('success', 'Les réponses ont été soumises avec succès.');
}

    
}

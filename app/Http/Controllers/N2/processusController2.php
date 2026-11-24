<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class processusController2 extends Controller2
{

   
public function showForm($matricule, $name,$id)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $matricule,'role' => 'n2'],
            [
                'current_page' => 0,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

    return view('N2.part0', compact('matricule', 'name','id'));
}


    public function submitForm(Request $request,$matricule, $name,$id)
{
    
    $userId = session('user_id');
    
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    if ($request->has('save_quit')) {

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role' => 'n2'],
            [
                'current_page' => 0,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('show.page2', ['id' => $id])->with('success', 'Données enregistrées avec succès.');
    }


    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $matricule,'role' => 'n2'],
        [
            'current_page' => 1,
            'role' => 'n2',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('partN21', ['matricule' => $matricule, 'name' => $name,'id' => $id])->with('success', 'Les réponses ont été soumises avec succès.');
}
}

<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class processusController extends Controller
{

    public function showForm()
    {
     $userId = session('user_id'); 

        
      DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],  
            [
                'current_page' => 1,   
                'role' => 'n0',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );

        return view('part0');
}


    public function submitForm(Request $request)
{
    
    $userId = session('user_id');
    
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    if ($request->has('save_quit')) {

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 0,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('welcome')->with('success', 'Données enregistrées avec succès.');
    }


    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $userId,'role' => 'n0'],
        [
            'current_page' => 1,
            'role' => 'n0',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('part1')->with('success', 'Les réponses ont été soumises avec succès.');
}

    
}

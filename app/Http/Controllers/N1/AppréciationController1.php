<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AppréciationController1 extends Controller1
{
    public function showForm($matricule, $name) {
        $userId = session('user_id');
        
        $appreciation = DB::table('appreciations')
            ->where('user_id', $matricule)
            ->first();
        
        return view('N1.part5', compact('matricule', 'name'), [
            'oldPotential' => $appreciation->potential ?? '',
            'oldComment' => $appreciation->comment ?? '',
        ]);
    }
    public function submitForm(Request $request,$matricule,$name)
{

    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }
    

    DB::table('appreciations')->updateOrInsert(
        ['user_id' =>  $matricule,'role'=>'n1'], 
        [
            'user_id' =>  $matricule,
            'role'  => 'n1',
            'potential' => $request->input('potential'),
            'comment' => $request->input('comment'),
            'updated_at' => now(),
            'created_at' => now(),

        ]
    );
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],
            [
                'current_page' => 5,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
                
            ]
        );
        return redirect()->route('show.page1')->with('success', 'Données enregistrées avec succès.');
    }
    $request->validate([
        'potential' => 'required|in:élevé,moyen,faible',
        'comment' => 'nullable|string',
    ]);

   DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],  
            [
                'current_page' => 6,   
                'role' => 'n1',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );
        
        
        return redirect()->route('partN16', ['matricule' => $matricule, 'name' => $name])->with('success', 'Les réponses ont été soumises avec succès.');
}
}

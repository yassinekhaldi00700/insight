<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AssiduitéController1 extends Controller1
{
    public function showForm($matricule,$name)
{
    
    $userId = session('user_id');
    $responses = DB::table('evaluations')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->first(); 

    return view('N1.part2',compact('matricule', 'name'), [
        'oldAssiduite' => $responses->assiduite ?? '', 
        'oldDisponibilite' => $responses->disponibilite ?? '', 
    ]);
}
    public function submitForm(Request $request,$matricule,$name)
{

    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }
    
    $average=($request->input('assiduite')+$request->input('disponibilite'))/2;
    DB::table('evaluations')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n1'], 
        [

            'assiduite' => $request->input('assiduite'),
            'disponibilite' => $request->input('disponibilite'),
            'average' =>$average,
            'updated_at' => now(),
            'role' => 'n1',

        ]
    );
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],
            [
                'current_page' => 2,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
                
            ]
        );
        return redirect()->route('show.page1')->with('success', 'Données enregistrées avec succès.');
    }
    $request->validate([
        'assiduite' => 'required|integer|between:1,4',
        'disponibilite' => 'required|integer|between:1,4',
    ]);

   DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],  
            [
                'current_page' => 3,   
                'role' => 'n1',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );
        
        return redirect()->route('partN13', ['matricule' => $matricule, 'name' => $name])->with('success', 'Les réponses ont été soumises avec succès.');
}
}

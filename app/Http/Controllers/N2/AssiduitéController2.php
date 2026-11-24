<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AssiduitéController2 extends Controller2
{
    public function showForm($matricule,$name,$id)
{
    
    $userId = session('user_id');
    $responses = DB::table('evaluations')
        ->where('user_id', $matricule)
        ->where('role', 'n2')
        ->first(); 
        $responses1 = DB::table('evaluations')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->first();

        $responses2 = DB::table('evaluations')
        ->where('user_id', $matricule)
        ->where('role', 'n0')
        ->first();
        
        $username = DB::table('users')
        ->where('id', $id)
        ->value('name');

    return view('N2.part2',compact('matricule', 'name','id','responses1','responses2','username'), [
        'oldAssiduite' => $responses->assiduite ?? '', 
        'oldDisponibilite' => $responses->disponibilite ?? '', 
    ]);
}
    public function submitForm(Request $request,$matricule,$name,$id)
{

    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }
    
    $average=($request->input('assiduite')+$request->input('disponibilite'))/2;
    DB::table('evaluations')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n2'], 
        [

            'assiduite' => $request->input('assiduite'),
            'disponibilite' => $request->input('disponibilite'),
            'average' =>$average,
            'updated_at' => now(),
            'role' => 'n2',

        ]
    );
    
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],
            [
                'current_page' => 2,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
                
            ]
        );
        return redirect()->route('show.page2', ['id' => $id])->with('success', 'Données enregistrées avec succès.');
    }
    $request->validate([
        'assiduite' => 'required|integer|between:1,4',
        'disponibilite' => 'required|integer|between:1,4',
    ]);
     
   DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],  
            [
                'current_page' => 3,   
                'role' => 'n2',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );
        
        return redirect()->route('partN23', ['matricule' => $matricule, 'name' => $name ,'id'=>$id])->with('success', 'Les réponses ont été soumises avec succès.');
}
}

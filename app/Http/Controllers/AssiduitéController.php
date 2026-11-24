<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AssiduitéController extends Controller
{
    public function showForm()
{
    
    $userId = session('user_id');
    $responses = DB::table('evaluations')
        ->where('user_id', $userId)
        ->first(); 

    return view('part2', [
        'oldAssiduite' => $responses->assiduite ?? '', 
        'oldDisponibilite' => $responses->disponibilite ?? '', 
    ]);
}
    public function submitForm(Request $request)
{

    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }
    
    $average=($request->input('assiduite')+$request->input('disponibilite'))/2;
    DB::table('evaluations')->updateOrInsert(
        ['user_id' => $userId,'role' => 'n0'], 
        [
            'assiduite' => $request->input('assiduite'),
            'disponibilite' => $request->input('disponibilite'),
            'average' =>$average,
            'updated_at' => now(),
            'created_at' => now(),
            'role' => 'n0',
        ]
    );
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 2,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('welcome')->with('success', 'Données enregistrées avec succès !');
    }
    $request->validate([
        'assiduite' => 'required|integer|between:1,4',
        'disponibilite' => 'required|integer|between:1,4',
    ]);

   DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $userId],  
            [
                'current_page' => 3,   
                'role' => 'n0',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );
    

    return redirect()->route('part3')->with('success', 'Responses submitted successfully.');;
}
}

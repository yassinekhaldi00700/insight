<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SignatureController1 extends Controller1
{
    public function showForm($matricule, $name)
{
    $userId = session('user_id');

    $response = DB::table('signature')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->first();

    return view('N1.part6', [
        'oldFname' => $response->fname ?? '',
        'oldContenu' => $response->contenu ?? '',
    ],compact('matricule', 'name'));
}

public function submitForm(Request $request,$matricule, $name)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }

    
    
    DB::table('signature')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n1'],
        [
        'fname' => $request->input('fname'),
        'contenu' => $request->input('contenu'),
        'user_id' => $matricule, 
        'created_at' => now(),
        'updated_at' => now(),
        'role' => 'n1',
    ]);

    if ($request->has('quit_button')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],
            [
                'current_page' => 6,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('welcome')->with('success', 'Data saved successfully and exited.');
    }
    if (!$request->filled(['fname', 'contenu'])) {
        return redirect()->back()->withErrors([
            'error' => 'Vous devez remplir tous les champs pour continuer.',
        ])->withInput(); 
    }
            
    DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],  
            [
                'current_page' => 7,   
                'role' => 'n1',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );

    return redirect()->route('partN17',['matricule' => $matricule, 'name' => $name])->with('success', 'Responses submitted successfully.');
}

}

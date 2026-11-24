<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SignatureController2 extends Controller2
{
    public function showForm($matricule, $name,$id)
{
    $userId = session('user_id');

    $response = DB::table('signature')
        ->where('user_id', $matricule)
        ->where('role', 'n2')
        ->first();

    $name_n1=DB::table('users')
    ->where('id', $id)
    ->value('name');

    return view('N2.part7', [
        'oldFname' => $response->fname ?? '',
        'oldContenu' => $response->contenu ?? '',
    ],compact('matricule', 'name','id','name_n1'));
}

public function submitForm(Request $request,$matricule, $name,$id)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }

    
    
    DB::table('signature')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n2'],
        [
        'fname' => $request->input('fname'),
        'contenu' => $request->input('contenu'),
        'user_id' => $matricule, 
        'created_at' => now(),
        'updated_at' => now(),
        'role' => 'n2',
    ]);

    if ($request->has('quit_button')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],
            [
                'current_page' => 7,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('show.page2' , ['id'=> $id])->with('success', 'Données enregistrées avec succès !');
    }
    if (!$request->filled(['fname', 'contenu'])) {
        return redirect()->back()->withErrors([
            'error' => 'Vous devez remplir tous les champs pour continuer.',
        ])->withInput(); 
    }
            
    DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],  
            [
                'current_page' => 8,   
                'role' => 'n2',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );

        return redirect()->route('show.page2' , ['id'=> $id])->with('success', 'Données enregistrées avec succès !');
}

}

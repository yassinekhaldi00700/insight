<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class fixationController2 extends Controller2
{
    public function showForm($matricule,$name,$id)
{
    $qualitativeObjectives = DB::table('qualitative_objectives')
        ->where('user_id', $matricule)
        ->get();

    $quantitativeObjectives = DB::table('quantitative_objectives')
        ->where('user_id', $matricule)
        ->get();
    

    return view('N2.part3',['qualitativeObjectives' => $qualitativeObjectives,
        'quantitativeObjectives' => $quantitativeObjectives],compact('matricule', 'name' ,'id'));
}


public function submitForm(Request $request, $matricule, $name , $id)
{
    
    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }

   

    // Update user progress based on the "save and quit" action
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule, 'role' => 'n2'],
            [
                'current_page' => 3,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('show.page2', ['id' => $id])->with('success', 'Données enregistrées avec succès.');
    }

    // Update user progress and redirect to the next part
    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $matricule, 'role' => 'n2'],
        [
            'current_page' => 4,
            'role' => 'n2',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('partN24', ['matricule' => $matricule, 'name' => $name,'id' => $id])
        ->with('success', 'Les réponses ont été soumises avec succès.');
}

}

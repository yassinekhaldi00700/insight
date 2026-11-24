<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class fixationController1 extends Controller1
{
    public function showForm($matricule,$name)
{
    
    $qualitativeObjectives = DB::table('qualitative_objectives')
        ->where('user_id', $matricule)
        ->get();

    $quantitativeObjectives = DB::table('quantitative_objectives')
        ->where('user_id', $matricule)
        ->get();
    
        
    return view('N1.part3',['qualitativeObjectives' => $qualitativeObjectives,
        'quantitativeObjectives' => $quantitativeObjectives],compact('matricule', 'name'));
}


public function submitForm(Request $request, $matricule, $name)
{

   
    
    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }

    // Decode input data
    $qualitatifData = json_decode($request->input('qualitatif_data'), true);
    $quantitatifData = json_decode($request->input('quantitatif_data'), true);
    

    // Validate the decoded data
    if (!is_array($qualitatifData)) {
        
        return redirect()->back()->withErrors(['error' => 'Invalid qualitative data provided.']);
    }

    if (!is_array($quantitatifData)) {
        return redirect()->back()->withErrors(['error' => 'Invalid quantitative data provided.']);
    }

    
    foreach ($qualitatifData as $qualitative) {
    if (isset($qualitative['id']) && $qualitative['id']) {
        // Update
        DB::table('qualitative_objectives')
            ->where('id', $qualitative['id'])
            ->update([
                'objective' => $qualitative['objectif'] ?? null,
                'description' => $qualitative['description'] ?? null,
                'weight' => isset($qualitative['ponderation']) ? (int)$qualitative['ponderation'] : null,
                'updated_at' => now(),
            ]);
    } else {
        // Insert (ne pas inclure 'id')
        DB::table('qualitative_objectives')->insert([
            'user_id' => $matricule,
            'objective' => $qualitative['objectif'] ?? null,
            'description' => $qualitative['description'] ?? null,
            'weight' => isset($qualitative['ponderation']) ? (int)$qualitative['ponderation'] : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
    
    foreach ($quantitatifData as $quantitative) {
        if (isset($quantitative['id']) && $quantitative['id']) {
            // Update existing
            DB::table('quantitative_objectives')
                ->where('id', $quantitative['id'])
                ->update([
                    'objective' => $quantitative['objectif'] ?? null,
                    'description' => $quantitative['description'] ?? null,
                    'quantity' => isset($quantitative['quantite']) ? (int)$quantitative['quantite'] : null,
                    'weight' => isset($quantitative['ponderation']) ? (int)$quantitative['ponderation'] : null,
                    'updated_at' => now(),
                ]);
        } else {
            // Insert new (do not include id)
            DB::table('quantitative_objectives')->insert([
                'user_id' => $matricule,
                'objective' => $quantitative['objectif'] ?? null,
                'description' => $quantitative['description'] ?? null,
                'quantity' => isset($quantitative['quantite']) ? (int)$quantitative['quantite'] : null,
                'weight' => isset($quantitative['ponderation']) ? (int)$quantitative['ponderation'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // Update user progress based on the "save and quit" action
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule, 'role' => 'n1'],
            [
                'current_page' => 3,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('show.page1')->with('success', 'Données enregistrées avec succès.');
    }

    // Update user progress and redirect to the next part
    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $matricule, 'role' => 'n1'],
        [
            'current_page' => 4,
            'role' => 'n1',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('partN14', ['matricule' => $matricule, 'name' => $name])
        ->with('success', 'Les réponses ont été soumises avec succès.');
}
 public function supprimer($matricule, $name,$type, $id)
{

    
    // Try to delete from qualitative_objectives
    if ($type === 'qualitative') {
        DB::table('qualitative_objectives')
            ->where('id', $id)
            ->where('user_id', $matricule)
            ->delete();
    } elseif ($type === 'quantitative') {
        DB::table('quantitative_objectives')
            ->where('id', $id)
            ->where('user_id', $matricule)
            ->delete();
    }

    // Reload objectives
    $qualitativeObjectives = DB::table('qualitative_objectives')
        ->where('user_id', $matricule)
        ->get();

    $quantitativeObjectives = DB::table('quantitative_objectives')
        ->where('user_id', $matricule)
        ->get();

    return view('N1.part3', [
        'qualitativeObjectives' => $qualitativeObjectives,
        'quantitativeObjectives' => $quantitativeObjectives
    ], compact('matricule', 'name'));
}

}

<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class opportunitéController1 extends Controller1
{
    public function showForm($matricule,$name)
    {
        $userId = session('user_id');
    
        $responses = DB::table('opportunites')
            ->where('user_id', $matricule)
            ->first();

            $responses2=DB::table('opportunities1')
            ->where('user_id', $matricule)
            ->first();
    
        return view('N1.part4', [
            'oldCourtTerme' => $responses->court_terme ?? '',
            'oldLongTerme' => $responses->long_terme ?? '',
            'oldavis'=>$responses2->avis ?? '',
            'oldcommentaire'=> $responses2->commentaire ?? '',
        ],compact('matricule', 'name'));
    }
    
    public function submitForm(Request $request,$matricule,$name)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
        }
    
        
    
        DB::table('opportunities1')->updateOrInsert(
            ['user_id' => $matricule],
            [
                'avis' => $request->avis,
                'commentaire' => $request->commentaire,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        
        if ($request->has('save_quit')) {
            DB::table('user_progress')->updateOrInsert(
                ['user_id' => $matricule,'role'=>'n1'],
                [
                    'current_page' => 4,
                    'role' => 'n1',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            return redirect()->route('show.page1')->with('success', 'Données enregistrées avec succès !');
        }

        
         $request->validate([
            'avis' => 'required|in:favorable,neutre,défavorable',
            'commentaire' => 'nullable|string',
        ]);

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],
            [
                'current_page' => 5,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        
    
        return redirect()->route('partN15', ['matricule' => $matricule, 'name' => $name])->with('success', 'Responses submitted successfully.');
    }
}

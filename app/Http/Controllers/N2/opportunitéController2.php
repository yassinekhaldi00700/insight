<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class opportunitéController2 extends Controller2
{
    public function showForm($matricule,$name,$id)
    {
        $userId = session('user_id');
    
        $responses = DB::table('opportunites')
            ->where('user_id', $matricule)
            ->first();

            $responses2=DB::table('opportunities1')
            ->where('user_id', $matricule)
            ->first();
    
        return view('N2.part4', [
            'oldCourtTerme' => $responses->court_terme ?? '',
            'oldLongTerme' => $responses->long_terme ?? '',
            'oldavis'=>$responses2->avis ?? '',
            'oldcommentaire'=> $responses2->commentaire ?? '',
        ],compact('matricule', 'name','id'));
    }
    
    public function submitForm(Request $request,$matricule,$name,$id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
        }
    
        
    
      
        
        if ($request->has('save_quit')) {
            DB::table('user_progress')->updateOrInsert(
                ['user_id' => $matricule,'role'=>'n2'],
                [
                    'current_page' => 4,
                    'role' => 'n2',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            return redirect()->route('show.page2' , ['id'=> $id])->with('success', 'Données enregistrées avec succès !');
        }

        

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],
            [
                'current_page' => 5,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        
    
        return redirect()->route('partN25', ['matricule' => $matricule, 'name' => $name,'id'=>$id])->with('success', 'Responses submitted successfully.');
    }
}

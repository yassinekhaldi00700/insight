<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class resultController2 extends Controller2
{
    public function showForm($matricule, $name, $id)
{
    
    $userId = session('user_id');
    
    $userResult = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n0')
        ->orderBy('created_at', 'desc')
        ->first();
    
    $userResult1 = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->orderBy('created_at', 'desc')
        ->first();

    $userResult2 = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n2')
        ->orderBy('created_at', 'desc')
        ->first();

    $result = $userResult->result ?? null;
    $result1 = $userResult1->result ?? null;
    $result2 = $userResult2->result ?? null;

    $responses2 = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n2')
    ->first(); 
    $responses1 = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n1')
    ->first();

    $responses = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n0')
    ->first();

    
    $finalresult = DB::table('FinalResult')
    ->where('userid', $matricule)
    ->first();
    
    return view('N2.part6', compact('matricule', 'name', 'id', 'result', 'result1', 'result2','responses','responses1','responses2','finalresult'));
}
    
    public function submitForm(Request $request,$matricule, $name,$id)
    {
        $userId = session('user_id');
    
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit your result.']);
        }
    
       
        
    
        
        

        if ($request->has('save_quit')) {
            DB::table('user_progress')->updateOrInsert(
                ['user_id' => $matricule,'role'=>'n2'],
                [
                    'current_page' => 6,
                    'role' => 'n2',
                    'updated_at' => now(),
                    'created_at' => now(),
                    
                ]
            );
            return redirect()->route('show.page2' , ['id'=> $id])->with('success', 'Données enregistrées avec succès !');
        }

        if ($request->has('soumettre')) {
            

            DB::table('finalresult')->updateOrInsert(
                ['userid' => $matricule],
                [
                    'addedvalue' => $request->input('addedvalue_present') ,
                ]
            );

            $finalresult = DB::table('FinalResult')
            ->where('userid', $matricule)
            ->value('final_result');
           

            $addedValue = DB::table('FinalResult')
            ->where('userid', $matricule)
            ->value('addedvalue');

            $newvalue= $finalresult+ $addedValue;
            

            
            
            
            DB::table('finalresult')->updateOrInsert(
                ['userid' => $matricule],
                [
                    'final_result' =>$newvalue ,
                ]
            );


            return redirect()->route('partN26' , ['name' => $name,'matricule'=> $matricule,'id'=> $id])->with('success', 'Données enregistrées avec succès !');
        }

        DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],
            [
                'current_page' => 7,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    
        return redirect()->route('partN27', ['matricule' => $matricule, 'name' => $name  ,'id' => $id])->with('success', 'Les réponses ont été soumises avec succès.');
    }
    
}

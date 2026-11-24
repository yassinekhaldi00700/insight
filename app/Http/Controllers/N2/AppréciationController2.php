<?php 
namespace App\Http\Controllers\N2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AppréciationController2 extends Controller2
{
    public function showForm($matricule, $name,$id) {
        $userId = session('user_id');
        
        $appreciation = DB::table('appreciations')
            ->where('user_id', $matricule)
            ->where('role', 'n2')
            ->first();
            $appreciation1 = DB::table('appreciations')
            ->where('user_id', $matricule)
            ->where('role', 'n1')
            ->first();
            $username = DB::table('users')
        ->where('id', $id)
        ->value('name');
        
        return view('N2.part5', compact('matricule', 'name','id','appreciation1','username'), [
            'oldPotential' => $appreciation->potential ?? '',
            'oldComment' => $appreciation->comment ?? '',
        ]);
    }
    public function submitForm(Request $request,$matricule,$name,$id)
{

    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }
    

    DB::table('appreciations')->updateOrInsert(
        ['user_id' =>  $matricule,'role'  => 'n2'], 
        [
            'user_id' =>  $matricule,
            'role'  => 'n2',
            'potential' => $request->input('potential'),
            'comment' => $request->input('comment'),
            'updated_at' => now(),
            'created_at' => now(),

        ]
    );
    if ($request->has('save_quit')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],
            [
                'current_page' => 5,
                'role' => 'n2',
                'updated_at' => now(),
                'created_at' => now(),
                
            ]
        );
        return redirect()->route('show.page2' , ['id'=> $id])->with('success', 'Données enregistrées avec succès !');
    }
    $request->validate([
        'potential' => 'required|in:élevé,moyen,faible',
        'comment' => 'nullable|string',
    ]);

   DB::table('user_progress')
         ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n2'],  
            [
                'current_page' => 6,   
                'role' => 'n2',         
                'updated_at' => now(),  
                'created_at' => now()   
            ]
        );
        
        $totalValue = DB::table('responses') 
        ->where('user_id',$matricule )
        ->where('role', 'n2')
        ->sum('response_value');

    
    $totalQuestions = DB::table('responses')
        ->where('user_id', $matricule)
        ->where('role', 'n2')
        ->count();

    
    $result = ($totalQuestions > 0) ? $totalValue / $totalQuestions : 0;
    DB::table('user_results')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n2'],[
        'user_id' => $matricule,
        'result' => $result,
        'role' => 'n2',
        'created_at' => now(),
        'updated_at' => now(),
    ]);


    $userResult = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n0')
        ->value('result');
    
    $userResult1 = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->value('result');

    $userResult2 = DB::table('user_results')
        ->where('user_id', $matricule)
        ->where('role', 'n2')
        ->value('result');
        
    $responses2 = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n2')
    ->value('average');
    $responses1 = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n1')
    ->value('average');

    $responses = DB::table('evaluations')
    ->where('user_id', $matricule)
    ->where('role', 'n0')
    ->value('average');
     
    $resultn0=($responses+$userResult)/2;
    $resultn1=($responses1+$userResult1)/2;
    $resultn2=($responses2+$userResult2)/2;
    $final_result=$resultn0*0.2+$resultn1*0.4+$resultn2*0.4;
    DB::table('user_results')->updateOrInsert(
        ['user_id' => $matricule,'role'=>'n2'],[
        'user_id' => $matricule,
        'result' => $result,
        'role' => 'n2',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $existingRow = DB::table('finalresult')
    ->where('userid', $matricule)
    ->first();


    DB::table('finalresult')->updateOrInsert(
        ['userid' => $matricule],
        [
            'userid' => $matricule,
            'resultn0' => $resultn0,
            'resultn1' => $resultn1,
            'resultn2' => $resultn2,
            'final_result' => $final_result,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );


        return redirect()->route('partN26', ['matricule' => $matricule, 'name' => $name  ,'id' => $id])->with('success', 'Les réponses ont été soumises avec succès.');
}
}

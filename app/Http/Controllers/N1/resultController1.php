<?php 
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Support\Team;

class resultController1 extends Controller1
{
    public function showForm($matricule, $name)
    {
        $userId = session('user_id');
        $totalValue = DB::table('responses') 
            ->where('user_id',$matricule )
            ->where('role', 'n1')
            ->whereNotIn('question_id', Team::hiddenQuestionIds($matricule))
            ->sum('response_value');

        
        $totalQuestions = DB::table('responses')
            ->where('user_id', $matricule)
            ->where('role', 'n1')
            ->whereNotIn('question_id', Team::hiddenQuestionIds($matricule))
            ->count();

        
        $result = ($totalQuestions > 0) ? $totalValue / $totalQuestions : 0;
        

        
        return view('N1.part7',compact('matricule', 'name'), ['result' => $result]);
    }
    
    public function submitForm(Request $request,$matricule, $name)
    {
        $userId = session('user_id');
    
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit your result.']);
        }
    
       
        $result = $request->input('result', 0); 
    
        $request->validate([
            'result' => 'required|numeric|min:0|max:4',
        ]);
    
        
        DB::table('user_results')->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],[
            'user_id' => $matricule,
            'result' => $result,
            'role' => 'n1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $matricule,'role'=>'n1'],
            [
                'current_page' => 8,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    
        return redirect()->route('show.page1')->with('success', 'Your result has been saved successfully.');
    }
    
}

<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Support\Team;

class resultController extends Controller
{
    public function showForm()
    {
        $userId = session('user_id');
        $totalValue = DB::table('responses') // Replace 'your_table_name' with the actual table name
            ->where('user_id', $userId)
            ->where('role', 'n0')
            ->whereNotIn('question_id', Team::hiddenQuestionIds($userId))
            ->sum('response_value');

        // Count the number of questions answered by the user
        $totalQuestions = DB::table('responses')
            ->where('user_id', $userId)
            ->where('role', 'n0')
            ->whereNotIn('question_id', Team::hiddenQuestionIds($userId))
            ->count();

        // Normalize the score to a scale of 4 (divide by the number of questions and then divide by 4)
        $result = ($totalQuestions > 0) ? $totalValue / $totalQuestions : 0;
        
        return view('part5', ['result' => $result]);
    }
    
    public function submitForm(Request $request)
    {
        $userId = session('user_id');
    
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit your result.']);
        }
    
        // Assume $result is calculated and passed to the view
        $result = $request->input('result', 0); // Example: Retrieve result from the form or backend logic
    
        // Validate result (if needed)
        $request->validate([
            'result' => 'required|numeric|min:0|max:4',
        ]);
    
        
        DB::table('user_results')->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],[
            'user_id' => $userId,
            'result' => $result,
            'role' => 'n0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 6,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    
        return redirect()->route('welcome')->with('success', 'Your result has been saved successfully.');
    }
    
}

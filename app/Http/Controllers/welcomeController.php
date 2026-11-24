<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class welcomeController extends Controller
{
    public function showPage()
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to continue.']);
    }


    
    $userProgress = DB::table('user_progress')->where('user_id', $userId)->first();

    if ($userProgress && $userProgress->current_page == 6) {
        
        $userResult = DB::table('user_results')
            ->where('user_id', $userId)
            ->where('role', 'n0')
            ->orderBy('created_at', 'desc')
            ->first();

        // Pass the result to the view
        return view('welcome', [
            'result' => $userResult->result ?? null, // Pass the result if available
            'hideButton' => true, // Hide the button since the user completed the evaluation
        ]);
    }

    
    return view('welcome', ['hideButton' => false]);
}
public function liste()
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to continue.']);
    }
    $teamMembers = DB::table('users')
        ->where('supervisor_id', $userId) 
         
        ->select('id', 'name') 
        ->get();
    return view('listeN1', ['teamMembers' => $teamMembers]);
}



}

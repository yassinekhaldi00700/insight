<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SignatureController extends Controller
{
    public function showForm()
{
    $userId = session('user_id');

    // Retrieve the current user's record
    $user = DB::table('users')->where('id', $userId)->first();

    // Find the supervisor's name
    $supervisorName = null;
    if ($user && $user->supervisor_id) {
        $supervisor = DB::table('users')->where('id', $user->supervisor_id)->first();
        $supervisorName = $supervisor->name ?? null;
    }

    // Fetch the signature data
    $response = DB::table('signature')
        ->where('user_id', $userId)
        ->first();

    return view('part4', [
        'oldFname' => $response->fname ?? '',
        'oldContenu' => $response->contenu ?? '',
        'supervisorName' => $supervisorName, // Pass the supervisor's name to the view
    ]);
}

public function submitForm(Request $request)
{
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
    }

    
    
    DB::table('signature')->updateOrInsert(
        ['user_id' => $userId,'role' => 'n0'],
        [
        'fname' => $request->input('fname'),
        'contenu' => $request->input('contenu'),
        'user_id' => $userId, 
        'created_at' => now(),
        'updated_at' => now(),
        'role' => 'n0',
    ]);

    if ($request->has('quit_button')) {
        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 4,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect()->route('welcome')->with('success', 'Data saved successfully and exited.');
    }
    if (!$request->filled(['fname', 'contenu'])) {
        return redirect()->back()->withErrors([
            'error' => 'Vous devez remplir tous les champs pour continuer.',
        ])->withInput(); 
    }

    DB::table('user_progress')
        ->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 5,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

    return redirect()->route('part5')->with('success', 'Responses submitted successfully.');
}

}

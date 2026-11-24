<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class opportunitéController extends Controller
{
    public function showForm()
    {
        $userId = session('user_id');
    
        $responses = DB::table('opportunites')
            ->where('user_id', $userId)
            ->first();
    
        return view('part3', [
            'oldCourtTerme' => $responses->court_terme ?? '',
            'oldLongTerme' => $responses->long_terme ?? '',
        ]);
    }
    
    public function submitForm(Request $request)
    {

        $request->validate([
    'court_terme' => ['required', function ($attribute, $value, $fail) {
        if (str_word_count($value) < 5) {
            $fail('Le champ Court/Moyen Terme doit contenir au moins 5 mots.');
        }
    }],
    'long_terme' => ['required', function ($attribute, $value, $fail) {
        if (str_word_count($value) < 5) {
            $fail('Le champ Long Terme doit contenir au moins 5 mots.');
        }
    }],
]);

        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to submit responses.']);
        }
    
        
    
        DB::table('opportunites')->updateOrInsert(
            ['user_id' => $userId],
            [
                'court_terme' => $request->input('court_terme'),
                'long_terme' => $request->input('long_terme'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        if ($request->has('save_quit')) {
            DB::table('user_progress')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'current_page' => 3,
                    'role' => 'n0',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            return redirect()->route('welcome')->with('success', 'Données enregistrées avec succès !');
        }

        
        if (!$request->filled(['court_terme', 'long_terme'])) {
            return redirect()->back()->withErrors([
                'error' => 'Vous devez remplir tous les champs pour continuer.',
            ])->withInput(); 
        }


        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId],
            [
                'current_page' => 4,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        

    
        return redirect()->route('part4')->with('success', 'Responses submitted successfully.');
    }
}

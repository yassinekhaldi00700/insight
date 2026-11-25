<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Team;



class QuestionController extends Controller
{
    public function showForm()
{
    $userId = session('user_id');

    $categories = DB::table('categories')
        ->join('questions', 'categories.id', '=', 'questions.categorie_id')
        ->select('categories.name as category_name', 'questions.question_id', 'questions.question_text', 'questions.categorie_id')
        ->whereNotIn('questions.question_id', Team::hiddenQuestionIds($userId)) // Management questions only for people with a team
        ->orderBy('categories.name')
        ->get()
        ->groupBy('category_name'); 

        $responses = DB::table('responses')
        ->where('user_id', $userId)
        ->pluck('response_value', 'question_id');

    return view('part1', compact('categories', 'responses'));
}

public function submitForm(Request $request)
{
    $totalQuestions = $request->input('total_questions');
    $responses = $request->input('responses');
    $categories = $request->input('categories');
    $userId = session('user_id');
    $responses = Team::filterResponses($userId, $responses);

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    if ($request->has('save_quit')) {
      $responses = $responses ?? [];
        foreach ($responses as $questionId => $responseValue) {
            DB::table('responses')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'question_id' => $questionId,
                ],
                [
                    'categorie_id' => $categories[$questionId],
                    'response_value' => $responseValue,
                    'updated_at' => now(),
                    'created_at' => now(),
                    'role' => 'n0',
                ]
            );
        }

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $userId,'role' => 'n0'],
            [
                'current_page' => 1,
                'role' => 'n0',
                'updated_at' => now(),
                'created_at' => now(),
                
            ]
        );

        return redirect()->route('welcome')->with('success', 'Données enregistrées avec succès.');
    }

    

    foreach ($responses as $questionId => $responseValue) {
        DB::table('responses')->updateOrInsert(
            [
                'user_id' => $userId,
                'question_id' => $questionId,
                'role' => 'n0',
            ],
            [
                'categorie_id' => $categories[$questionId],
                'response_value' => $responseValue,
                'updated_at' => now(),
                'created_at' => now(),
                'role' => 'n0',
            ]
        );
    }
    $responses = $responses ?? [];
    if (count($responses) <$totalQuestions) {
        return redirect()->back()->withErrors(['error' => 'Veuillez remplir toutes les réponses avant de continuer.'])->withInput();
    }

    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $userId,'role' => 'n0'],
        [
            'current_page' => 2,
            'role' => 'n0',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('part2')->with('success', 'Les réponses ont été soumises avec succès.');
}





}

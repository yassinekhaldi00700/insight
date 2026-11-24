<?php 

namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Team;



class QuestionController1 extends Controller1
{
    public function showForm($matricule, $name)
{
    $userId = session('user_id');
    $categories = DB::table('categories')
        ->join('questions', 'categories.id', '=', 'questions.categorie_id')
        ->select('categories.name as category_name', 'questions.question_id', 'questions.question_text', 'questions.categorie_id')
        ->whereNotIn('questions.question_id', Team::hiddenQuestionIds($matricule)) // Management questions only for people with a team
        ->orderBy('categories.name')
        ->get()
        ->groupBy('category_name'); 

        $responses = DB::table('responses')
        ->where('user_id', $matricule)
        ->where('role', 'n1')
        ->pluck('response_value', 'question_id');

    return view('N1.part1', compact('categories', 'responses','matricule', 'name'));
}

public function submitForm(Request $request,$matricule, $name)
{
    $totalQuestions = $request->input('total_questions');
    $responses = $request->input('responses');
    $categories = $request->input('categories');
    $responses = Team::filterResponses($matricule, $responses);
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter pour soumettre les réponses.']);
    }

    if ($request->has('save_quit')) {
         $responses = $responses ?? [];
        foreach ($responses as $questionId => $responseValue) {
            DB::table('responses')->updateOrInsert(
                [
                    'user_id' => $matricule,
                    'question_id' => $questionId,
                    'role' => 'n1',
                ],
                [
                    'categorie_id' => $categories[$questionId],
                    'response_value' => $responseValue,
                    'updated_at' => now(),
                    'created_at' => now(),
                    'role' => 'n1',
                ]
            );
        }

        DB::table('user_progress')->updateOrInsert(
            ['user_id' => $matricule,'role' => 'n1'],
            [
                'current_page' => 1,
                'role' => 'n1',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('welcome')->with('success', 'Données enregistrées avec succès.');
    }

    

    foreach ($responses as $questionId => $responseValue) {
        DB::table('responses')->updateOrInsert(
            [
                'user_id' => $matricule,
                'question_id' => $questionId,
                'role' => 'n1',
            ],
            [
                'categorie_id' => $categories[$questionId],
                'response_value' => $responseValue,
                'updated_at' => now(),
                'created_at' => now(),
                'role' => 'n1',
            ]
        );
    }
    $responses = $responses ?? [];
    if (count($responses) <$totalQuestions) {
        return redirect()->back()->withErrors(['error' => 'Veuillez remplir toutes les réponses avant de continuer.'])->withInput();
    }

    DB::table('user_progress')->updateOrInsert(
        ['user_id' => $matricule,'role' => 'n1'],
        [
            'current_page' => 2,
            'role' => 'n1',
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return redirect()->route('partN12', ['matricule' => $matricule, 'name' => $name])->with('success', 'Les réponses ont été soumises avec succès.');
}





}

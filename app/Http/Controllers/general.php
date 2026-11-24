<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class general extends Controller
{



    public function showPage()
    {


        $userId = session('user_id'); 
        $progress = DB::table('user_progress')
            ->where('user_id', $userId)
            ->where('role', 'n0')
            ->select('current_page') 
            ->first();
        
        if ($progress) {
            $currentPage = $progress->current_page;
            $routeName = 'part' . $currentPage;
            if (\Route::has($routeName)) {
                return redirect()->route($routeName);
            }
        }
        return redirect()->route('part0')->with('success', 'Responses submitted successfully.');


    }
    public function showPage1()
{
    $n1Id = session('user_id'); // Assuming you're storing the N1 user's ID in the session

    // Fetch all N0 users supervised by the current N1 user
    $users = DB::table('users')
        ->where('supervisor_id', $n1Id)
        ->orderBy('name', 'asc')
        ->get();

    // Initialize counters
    $totalUsers = $users->count();
    $totalStatusFaite = 0;
    $totalActionFaite = 0;

    // Process users to fetch required details
    $userDetails = $users->map(function ($user) use (&$totalStatusFaite, &$totalActionFaite) {
        // Fetch all progress entries for the user
        $userProgress = DB::table('user_progress')
            ->where('user_id', $user->id)
            ->get();

        // Initialize status and action
        $status = 'Non Realisée';
        $action = 'Non Realisée';

        $n0Progress = $userProgress->firstWhere('role', 'n0');
        if ($n0Progress && $n0Progress->current_page == 6) {
            $status = 'Realisée';
            $action = 'remplir';
        }
        
        // Check for N1 progress
        $n1Progress = $userProgress->firstWhere('role', 'n1');
        if ($n1Progress && $n1Progress->current_page == 8) {
            $status = 'Realisée';
            $action = 'Realisée';
        }

        // Increment counters based on conditions
        if ($status === 'Realisée') {
            $totalStatusFaite++;
        }
        if ($action === 'Realisée') {
            $totalActionFaite++;
        }

        // Fetch entretien date
        $entretienDate = DB::table('entretien_date')
            ->where('user_id', $user->id)
            ->first();

        // Determine "fixer date" action and label
        $fixerDateAction = $entretienDate ? 'mise à jour' : 'fixer date';
        $fixerDateLabel = $entretienDate ? $entretienDate->date_entretien . " - " . $entretienDate->heure_entretien : '-';

        // Check if the user is a supervisor
        $isSupervisor = DB::table('users')->where('supervisor_id', $user->id)->exists();

        return [
            'matricule' => $user->matricule ?? "$user->id",
            'equipe' => $user->name,
            'status' => $status,
            'action' => $action,
            'fixer_date_action' => $fixerDateAction,
            'fixer_date_label' => $fixerDateLabel,
            'is_supervisor' => $isSupervisor ? true : false, // Add this key to the user details
        ];
    });

    // Calculate the percentage of "faite" in status
    $percentageStatusFaite = $totalUsers > 0 ? ($totalStatusFaite / $totalUsers) * 100 : 0;

    // Prepare additional data for the dashboard
    $dashboardData = [
        'totalUsers' => $totalUsers,
        'totalStatusFaite' => $totalStatusFaite,
        'totalActionFaite' => $totalActionFaite,
        'percentageStatusFaite' => round($percentageStatusFaite, 2),
    ];
    
    // Return the view with data
    return view('N1.dashboard', [
        'userDetails' => $userDetails,
        'dashboardData' => $dashboardData,
    ]);
}

    
    
public function remplir($matricule,$name)
{
    $userId = session('user_id'); 
        $progress = DB::table('user_progress')
            ->where('user_id', $matricule)
            ->where('role', 'n1')
            ->select('current_page') 
            ->first();
        
        if ($progress) {
            $currentPage = $progress->current_page;
            $routeName = 'partN1' . $currentPage;
            if (\Route::has($routeName)) {
                return redirect()->route($routeName, ['matricule' => $matricule, 'name' => $name]);
            }
        }
        return redirect()->route('partN10', ['matricule' => $matricule, 'name' => $name])->with('success', 'Responses submitted successfully.');
}
    
    
public function showPage2($id)
{
    // Ensure the logged-in user is an N2 supervisor
    $n2Id = session('user_id');
    if (!$n2Id) {
        return redirect()->route('login')->withErrors(['error' => 'Please log in to continue.']);
    }

    // Fetch all N0 team members supervised by the specified N1 user ($id)
    $users = DB::table('users')
        ->where('supervisor_id', $id) // Restrict to the team of the clicked N1
        ->orderBy('name', 'asc')
        ->get();

    // Initialize counters
    $totalUsers = $users->count();
    $totalStatusFaite = 0;
    $totalStatus1Faite = 0;
    $totalActionFaite = 0;

    // Process users to fetch required details
    $userDetails = $users->map(function ($user) use (&$totalStatusFaite, &$totalActionFaite,&$totalStatus1Faite) {
        // Fetch all progress entries for the user
        $userProgress = DB::table('user_progress')
            ->where('user_id', $user->id)
            ->get();

        // Initialize status and action
        $statusN0 = 'Non Realisée';
        $statusN1 = 'Non Realisée';
        $action = 'Non Realisée';

        // Check for N0 progress
        $n0Progress = $userProgress->firstWhere('role', 'n0');
        if ($n0Progress && $n0Progress->current_page == 6) {
            $statusN0 = 'Realisée';
        }

        // Check for N1 progress
        $n1Progress = $userProgress->firstWhere('role', 'n1');
        if ($n1Progress && $n1Progress->current_page == 8) {
            $statusN1 = 'Realisée';
        }

        // Determine action visibility based on both statuses
        if ($statusN0 === 'Realisée' && $statusN1 === 'Realisée') {
            $action = 'remplir';
        }

        $n2Progress = $userProgress->firstWhere('role', 'n2');
        if ($n1Progress && $n2Progress && $n2Progress->current_page == 8) { $action = 'Realisée'; }

        // Increment counters based on conditions
        if ($statusN0 === 'Realisée') {
            $totalStatusFaite++;
        }
        if ($statusN1 === 'Realisée') {
            $totalStatus1Faite++;
        }
        if ($action === 'Realisée') {
            $totalActionFaite++;
        }
        

        $entretienDate = DB::table('entretien_date')
            ->where('user_id', $user->id)
            ->first();
            $fixerDateLabel = $entretienDate ? $entretienDate->date_entretien . " - " . $entretienDate->heure_entretien : '-';
        return [
            'id' => $user->id,
            'name' => $user->name,
            'status_n0' => $statusN0,
            'status_n1' => $statusN1,
            'action' => $action,
            'fixer_date_label' => $fixerDateLabel,
        ];
    });

    
    $percentageStatusFaite = $totalUsers > 0 ? ($totalStatusFaite / $totalUsers) * 100 : 0;

    
    $dashboardData = [
        'totalUsers' => $totalUsers,
        'totalStatusFaite' => $totalStatusFaite,
        'totalStatus1Faite' => $totalStatus1Faite,
        'totalActionFaite' => $totalActionFaite,
        'percentageStatusFaite' => round($percentageStatusFaite, 2),
    ];

    
    return view('N2.dashboard', [
        'userDetails' => $userDetails,
        'dashboardData' => $dashboardData,
        'id'=>$id,
    ]);
}


public function remplir1($matricule,$name,$id)
{
    $userId = session('user_id'); 
        $progress = DB::table('user_progress')
            ->where('user_id', $matricule)
            ->where('role', 'n2')
            ->select('current_page') 
            ->first();
        
        if ($progress) {
            $currentPage = $progress->current_page;
            $routeName = 'partN2' . $currentPage;
            if (\Route::has($routeName)) {
                return redirect()->route($routeName, ['matricule' => $matricule, 'name' => $name,'id'=>$id]);
            }
        }
        return redirect()->route('partN20', ['matricule' => $matricule, 'name' => $name,'id'=>$id])->with('success', 'Responses submitted successfully.');
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GlobalDashboardController extends Controller
{
    public function index()
    {
        // 1. Global Overview Statistics
        $globalStats = $this->getGlobalOverview();
        
        // 2. Performance Analytics
        $performanceData = $this->getPerformanceAnalytics();
        
        // 3. Top and Bottom Performers
        $performers = $this->getPerformers();
        
        // 4. Competency Analysis
        $competencyAnalysis = $this->getCompetencyAnalysis();
        
        // 5. Objectives Tracking
        $objectivesData = $this->getObjectivesTracking();
        
        // 6. Feedback and Potential Analytics
        $feedbackData = $this->getFeedbackAnalytics();
        
        // 7. Interview Schedule
        $interviewData = $this->getInterviewSchedule();
        
        // 8. Performance Correlation
        $correlationData = $this->getPerformanceCorrelation();

        return view('dashboard.global', compact(
            'globalStats',
            'performanceData', 
            'performers',
            'competencyAnalysis',
            'objectivesData',
            'feedbackData',
            'interviewData',
            'correlationData'
        ));
    }

    private function getGlobalOverview()
    {
        $totalUsers = DB::table('users')->count();
        $totalEvaluationsCompleted = DB::table('user_results')->count();
        
        // Check if entretien_date table exists and has records
        try {
            $totalInterviews = DB::table('entretien_date')->count();
            $upcomingInterviews = DB::table('entretien_date')
                ->where('date_entretien', '>=', Carbon::now()->format('Y-m-d'))
                ->count();
        } catch (\Exception $e) {
            $totalInterviews = 0;
            $upcomingInterviews = 0;
        }
        
        $completedN0 = DB::table('user_progress')->where('role', 'n0')->where('current_page', '>=', 6)->count();
        $completedN1 = DB::table('user_progress')->where('role', 'n1')->where('current_page', '>=', 8)->count();
        $completedN2 = DB::table('user_progress')->where('role', 'n2')->where('current_page', '>=', 8)->count();
        
        $averagePerformanceN0 = DB::table('user_results')->where('role', 'n0')->avg('result') ?? 0;
        $averagePerformanceN1 = DB::table('user_results')->where('role', 'n1')->avg('result') ?? 0;
        $averagePerformanceN2 = DB::table('user_results')->where('role', 'n2')->avg('result') ?? 0;

        return [
            'totalUsers' => $totalUsers,
            'totalEvaluationsCompleted' => $totalEvaluationsCompleted,
            'totalInterviews' => $totalInterviews,
            'completedN0' => $completedN0,
            'completedN1' => $completedN1,
            'completedN2' => $completedN2,
            'averagePerformanceN0' => round($averagePerformanceN0, 2),
            'averagePerformanceN1' => round($averagePerformanceN1, 2),
            'averagePerformanceN2' => round($averagePerformanceN2, 2),
            'upcomingInterviews' => $upcomingInterviews,
            'completionRate' => $totalUsers > 0 ? round(($totalEvaluationsCompleted / ($totalUsers * 3)) * 100, 2) : 0
        ];
    }

    private function getPerformanceAnalytics()
    {
        // Performance by month
        $performanceByMonth = DB::table('user_results')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('AVG(result) as avg_score'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Performance by role
        $performanceByRole = DB::table('user_results')
            ->select('role', DB::raw('AVG(result) as avg_score'), DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        // Performance by department (based on supervisor hierarchy)
        $departmentPerformance = DB::table('users as u')
            ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
            ->leftJoin('users as supervisor', 'u.supervisor_id', '=', 'supervisor.id')
            ->select('supervisor.name as department', DB::raw('AVG(ur.result) as avg_score'), DB::raw('COUNT(ur.id) as evaluations'))
            ->whereNotNull('supervisor.name')
            ->groupBy('supervisor.name')
            ->get();

        return [
            'byMonth' => $performanceByMonth,
            'byRole' => $performanceByRole,
            'byDepartment' => $departmentPerformance
        ];
    }

    private function getPerformers()
    {
        // Try different table name variants for final results
        $finalResultTable = 'finalresult';
        try {
            DB::table($finalResultTable)->first();
        } catch (\Exception $e) {
            try {
                $finalResultTable = 'FinalResult';
                DB::table($finalResultTable)->first();
            } catch (\Exception $e2) {
                // If no final result table exists, use user_results instead
                $topPerformers = DB::table('users as u')
                    ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
                    ->select('u.name', 'u.email', DB::raw('AVG(ur.result) as avg_score'))
                    ->whereNotNull('ur.result')
                    ->groupBy('u.id', 'u.name', 'u.email')
                    ->orderBy('avg_score', 'desc')
                    ->limit(10)
                    ->get();

                $bottomPerformers = DB::table('users as u')
                    ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
                    ->select('u.name', 'u.email', DB::raw('AVG(ur.result) as avg_score'))
                    ->whereNotNull('ur.result')
                    ->groupBy('u.id', 'u.name', 'u.email')
                    ->orderBy('avg_score', 'asc')
                    ->limit(10)
                    ->get();

                return [
                    'top' => $topPerformers,
                    'bottom' => $bottomPerformers,
                    'coaching' => collect([])
                ];
            }
        }

        // Top 10 performers based on final results
        $topPerformers = DB::table('users as u')
            ->leftJoin($finalResultTable . ' as fr', 'u.id', '=', 'fr.id')
            ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
            ->select('u.name', 'u.email', 'fr.final_result', DB::raw('AVG(ur.result) as avg_score'))
            ->whereNotNull('fr.final_result')
            ->groupBy('u.id', 'u.name', 'u.email', 'fr.final_result')
            ->orderBy('fr.final_result', 'desc')
            ->limit(10)
            ->get();

        // Bottom 10 performers who need attention
        $bottomPerformers = DB::table('users as u')
            ->leftJoin($finalResultTable . ' as fr', 'u.id', '=', 'fr.id')
            ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
            ->select('u.name', 'u.email', 'fr.final_result', DB::raw('AVG(ur.result) as avg_score'))
            ->whereNotNull('fr.final_result')
            ->groupBy('u.id', 'u.name', 'u.email', 'fr.final_result')
            ->orderBy('fr.final_result', 'asc')
            ->limit(10)
            ->get();

        // High potential but low performance (needs coaching)
        try {
            $coachingCandidates = DB::table('users as u')
                ->leftJoin('opportunites as o', 'u.id', '=', 'o.id')
                ->leftJoin('user_results as ur', 'u.id', '=', 'ur.id')
                ->select('u.name', 'u.email', 'o.potential', DB::raw('AVG(ur.result) as avg_score'))
                ->where('o.potential', 'like', '%élevé%')
                ->groupBy('u.id', 'u.name', 'u.email', 'o.potential')
                ->havingRaw('AVG(ur.result) < 2.5')
                ->get();
        } catch (\Exception $e) {
            $coachingCandidates = collect([]);
        }

        return [
            'top' => $topPerformers,
            'bottom' => $bottomPerformers,
            'coaching' => $coachingCandidates
        ];
    }

    private function getCompetencyAnalysis()
    {
        // Check if questions table exists
        try {
            // Average scores by competency category
            $competencyAverages = DB::table('responses as r')
                ->leftJoin('questions as q', 'r.question_id', '=', 'q.id')
                ->select('q.category', DB::raw('AVG(r.response_value) as avg_score'))
                ->whereNotNull('q.category')
                ->groupBy('q.category')
                ->orderBy('avg_score', 'asc')
                ->get();

            // Lowest performing competencies that need attention
            $weakCompetencies = DB::table('responses as r')
                ->leftJoin('questions as q', 'r.question_id', '=', 'q.id')
                ->select('q.category', 'q.question_text', DB::raw('AVG(r.response_value) as avg_score'))
                ->whereNotNull('q.category')
                ->groupBy('q.category', 'q.question_text')
                ->havingRaw('AVG(r.response_value) < 2.0')
                ->orderBy('avg_score', 'asc')
                ->get();
        } catch (\Exception $e) {
            // If tables don't exist, return empty collections
            $competencyAverages = collect([]);
            $weakCompetencies = collect([]);
        }

        return [
            'averages' => $competencyAverages,
            'weakAreas' => $weakCompetencies
        ];
    }

    private function getObjectivesTracking()
    {
        // Check if objectives tables exist
        try {
            // Qualitative objectives completion
            $qualitativeStats = DB::table('qualitative_objectives')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('AVG(weight) as avg_weight'),
                    DB::raw('COUNT(CASE WHEN score >= 3 THEN 1 END) as completed')
                )
                ->first();
        } catch (\Exception $e) {
            $qualitativeStats = (object)[
                'total' => 0,
                'avg_weight' => 0,
                'completed' => 0
            ];
        }

        try {
            // Quantitative objectives completion
            $quantitativeStats = DB::table('quantitative_objectives')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('AVG(weight) as avg_weight'),
                    DB::raw('COUNT(CASE WHEN score >= 3 THEN 1 END) as completed')
                )
                ->first();
        } catch (\Exception $e) {
            $quantitativeStats = (object)[
                'total' => 0,
                'avg_weight' => 0,
                'completed' => 0
            ];
        }

        try {
            // Most common objective types
            $commonObjectives = DB::table('qualitative_objectives')
                ->select('objective_type', DB::raw('COUNT(*) as count'))
                ->whereNotNull('objective_type')
                ->groupBy('objective_type')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $commonObjectives = collect([]);
        }

        return [
            'qualitative' => $qualitativeStats,
            'quantitative' => $quantitativeStats,
            'common' => $commonObjectives
        ];
    }

    private function getFeedbackAnalytics()
    {
        // Appreciation sentiment analysis
        $appreciations = DB::table('appreciations')
            ->select('comment', 'potential')
            ->whereNotNull('comment')
            ->get();

        $potentialDistribution = DB::table('appreciations')
            ->select('potential', DB::raw('COUNT(*) as count'))
            ->groupBy('potential')
            ->get();

        // Comments with keywords analysis
        $positiveKeywords = ['excellent', 'outstanding', 'good', 'great', 'satisfactory'];
        $improvementKeywords = ['improve', 'develop', 'enhance', 'weak', 'need'];

        $sentimentAnalysis = [
            'positive' => 0,
            'improvement' => 0,
            'neutral' => 0
        ];

        foreach ($appreciations as $appreciation) {
            $comment = strtolower($appreciation->comment);
            $hasPositive = false;
            $hasImprovement = false;

            foreach ($positiveKeywords as $keyword) {
                if (strpos($comment, $keyword) !== false) {
                    $hasPositive = true;
                    break;
                }
            }

            foreach ($improvementKeywords as $keyword) {
                if (strpos($comment, $keyword) !== false) {
                    $hasImprovement = true;
                    break;
                }
            }

            if ($hasPositive && !$hasImprovement) {
                $sentimentAnalysis['positive']++;
            } elseif ($hasImprovement) {
                $sentimentAnalysis['improvement']++;
            } else {
                $sentimentAnalysis['neutral']++;
            }
        }

        return [
            'sentiment' => $sentimentAnalysis,
            'potential' => $potentialDistribution,
            'totalComments' => count($appreciations)
        ];
    }

    private function getInterviewSchedule()
    {
        try {
            $upcomingInterviews = DB::table('entretien_date as ed')
                ->leftJoin('users as u', 'ed.id', '=', 'u.id')
                ->select('u.name', 'u.email', 'ed.date_entretien', 'ed.heure_entretien', 'ed.status')
                ->where('ed.date_entretien', '>=', Carbon::now()->format('Y-m-d'))
                ->orderBy('ed.date_entretien', 'asc')
                ->limit(20)
                ->get();

            $interviewStats = DB::table('entretien_date')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed'),
                    DB::raw('COUNT(CASE WHEN status = "scheduled" THEN 1 END) as scheduled'),
                    DB::raw('COUNT(CASE WHEN date_entretien < NOW() AND status != "completed" THEN 1 END) as overdue')
                )
                ->first();
        } catch (\Exception $e) {
            $upcomingInterviews = collect([]);
            $interviewStats = (object)[
                'total' => 0,
                'completed' => 0,
                'scheduled' => 0,
                'overdue' => 0
            ];
        }

        return [
            'upcoming' => $upcomingInterviews,
            'stats' => $interviewStats
        ];
    }

    private function getPerformanceCorrelation()
    {
        try {
            // Try different table name variants for final results
            $finalResultTable = 'finalresult';
            try {
                DB::table($finalResultTable)->first();
            } catch (\Exception $e) {
                $finalResultTable = 'FinalResult';
            }

            // Correlation between assiduité/disponibilité and final performance
            $assiduitéCorrelation = DB::table('evaluations as e')
                ->leftJoin($finalResultTable . ' as fr', 'e.id', '=', 'fr.id')
                ->select(
                    'e.assiduite',
                    'e.disponibilite',
                    'e.average as behavior_avg',
                    'fr.final_result'
                )
                ->whereNotNull('fr.final_result')
                ->get();
        } catch (\Exception $e) {
            $assiduitéCorrelation = collect([]);
        }

        try {
            // Self vs Supervisor evaluation comparison
            $evaluationComparison = DB::table('user_results as ur_self')
                ->leftJoin('user_results as ur_supervisor', function($join) {
                    $join->on('ur_self.id', '=', 'ur_supervisor.id')
                         ->where('ur_self.role', '=', 'n0')
                         ->where('ur_supervisor.role', '=', 'n1');
                })
                ->leftJoin('users as u', 'ur_self.id', '=', 'u.id')
                ->select(
                    'u.name',
                    'ur_self.result as self_score',
                    'ur_supervisor.result as supervisor_score',
                    DB::raw('ABS(ur_self.result - ur_supervisor.result) as score_difference')
                )
                ->whereNotNull('ur_supervisor.result')
                ->get();
        } catch (\Exception $e) {
            $evaluationComparison = collect([]);
        }

        return [
            'assiduite' => $assiduitéCorrelation,
            'comparison' => $evaluationComparison
        ];
    }

    public function getChartData()
    {
        return response()->json([
            'performance' => $this->getPerformanceAnalytics(),
            'competency' => $this->getCompetencyAnalysis(),
            'objectives' => $this->getObjectivesTracking(),
            'feedback' => $this->getFeedbackAnalytics()
        ]);
    }

    public function exportData()
    {
        // Export functionality for Excel/PDF reports
        $allData = [
            'global' => $this->getGlobalOverview(),
            'performance' => $this->getPerformanceAnalytics(),
            'performers' => $this->getPerformers(),
            'competency' => $this->getCompetencyAnalysis(),
            'objectives' => $this->getObjectivesTracking(),
            'feedback' => $this->getFeedbackAnalytics(),
            'interviews' => $this->getInterviewSchedule(),
            'correlation' => $this->getPerformanceCorrelation()
        ];

        return response()->json($allData);
    }

    // Individual user detailed dashboard
    public function userDetail($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // User's complete evaluation history
        $evaluationHistory = DB::table('user_results')
            ->where('id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // User's objectives (check if tables exist)
        try {
            $qualitativeObjectives = DB::table('qualitative_objectives')
                ->where('id', $userId)
                ->get();
        } catch (\Exception $e) {
            $qualitativeObjectives = collect([]);
        }

        try {
            $quantitativeObjectives = DB::table('quantitative_objectives')
                ->where('id', $userId)
                ->get();
        } catch (\Exception $e) {
            $quantitativeObjectives = collect([]);
        }

        // User's appreciations and feedback
        try {
            $appreciations = DB::table('appreciations')
                ->where('id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            $appreciations = collect([]);
        }

        // User's final result (try different table names)
        $finalResult = null;
        try {
            $finalResult = DB::table('finalresult')
                ->where('id', $userId)
                ->first();
        } catch (\Exception $e) {
            try {
                $finalResult = DB::table('FinalResult')
                    ->where('id', $userId)
                    ->first();
            } catch (\Exception $e2) {
                $finalResult = null;
            }
        }

        // User's interview schedule
        try {
            $interviews = DB::table('entretien_date')
                ->where('id', $userId)
                ->orderBy('date_entretien', 'desc')
                ->get();
        } catch (\Exception $e) {
            $interviews = collect([]);
        }

        // User's competency scores
        try {
            $competencyScores = DB::table('responses as r')
                ->leftJoin('questions as q', 'r.question_id', '=', 'q.id')
                ->select('q.category', DB::raw('AVG(r.response_value) as avg_score'))
                ->where('r.id', $userId)
                ->whereNotNull('q.category')
                ->groupBy('q.category')
                ->get();
        } catch (\Exception $e) {
            $competencyScores = collect([]);
        }

        return view('dashboard.user-detail', compact(
            'user',
            'evaluationHistory',
            'qualitativeObjectives',
            'quantitativeObjectives',
            'appreciations',
            'finalResult',
            'interviews',
            'competencyScores'
        ));
    }
}

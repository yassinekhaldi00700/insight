<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        
        // Get team performance data
        $teamPerformance = $this->getTeamPerformance($role);
        
        // Get competencies list
        $competencies = $this->getCompetencies();
        
        // Get heatmap data
        $heatmapData = $this->getHeatmapData($role);
        
        // Get evaluation status
        $evaluationStatus = $this->getEvaluationStatus($role);
        
        // Get user status details
        $userStatus = $this->getUserStatus($role);
        
        // Get team ranking
        $teamRanking = $this->getTeamRanking($role);
        
        // Get competency summary
        $competencySummary = $this->getCompetencySummary($role);
        
        // Get comment summary
        $commentSummary = $this->getCommentSummary($role);
        
        // Get frequent keywords
        $frequentKeywords = $this->getFrequentKeywords($role);
        
        // Get performance trends
        $performanceTrend = $this->getPerformanceTrend($role);

        return view('dashboard.manager', compact(
            'role',
            'teamPerformance',
            'competencies',
            'heatmapData',
            'evaluationStatus',
            'userStatus',
            'teamRanking',
            'competencySummary',
            'commentSummary',
            'frequentKeywords',
            'performanceTrend'
        ));
    }

    private function getTeamPerformance($role = null)
    {
        $query = DB::table('users as supervisor')
            ->leftJoin('users as member', 'supervisor.id', '=', 'member.supervisor_id')
            ->leftJoin('user_results as ur', function($join) use ($role) {
                $join->on('member.id', '=', 'ur.user_id');
                if ($role) {
                    $join->where('ur.role', '=', $role);
                }
            })
            ->select(
                'supervisor.id as supervisor_id',
                'supervisor.name as supervisor_name',
                DB::raw('COUNT(DISTINCT member.id) as member_count'),
                DB::raw('AVG(ur.result) as average_score')
            )
            ->whereNotNull('member.id')
            ->groupBy('supervisor.id', 'supervisor.name')
            ->get();

        return $query->map(function($team) {
            return [
                'supervisor' => (object)['id' => $team->supervisor_id, 'name' => $team->supervisor_name],
                'memberCount' => $team->member_count,
                'averageScore' => $team->average_score ?? 0
            ];
        });
    }

    private function getCompetencies()
    {
        return DB::table('questions')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();
    }

    private function getHeatmapData($role = null)
    {
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->get();

        $competencies = $this->getCompetencies();
        
        $heatmapData = [];
        
        foreach ($users as $user) {
            $userScores = [];
            $totalScore = 0;
            $competencyCount = 0;
            
            foreach ($competencies as $competency) {
                $avgScore = DB::table('responses as r')
                    ->leftJoin('questions as q', 'r.question_id', '=', 'q.id')
                    ->where('r.user_id', $user->id)
                    ->where('q.category', $competency);
                
                if ($role) {
                    $avgScore = $avgScore->where('r.role', $role);
                }
                
                $avgScore = $avgScore->avg('r.response_value') ?? 0;
                
                $userScores[$competency] = $avgScore;
                $totalScore += $avgScore;
                $competencyCount++;
            }
            
            $heatmapData[] = [
                'user' => $user,
                'scores' => $userScores,
                'average' => $competencyCount > 0 ? $totalScore / $competencyCount : 0
            ];
        }

        // Sort by average score descending
        usort($heatmapData, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });

        return $heatmapData;
    }

    private function getEvaluationStatus($role = null)
    {
        $totalUsers = DB::table('users')->count();
        
        $completedQuery = DB::table('user_progress as up')
            ->where('up.current_page', '>=', 6);
        
        if ($role) {
            $completedQuery->where('up.role', $role);
        }
        
        $completed = $completedQuery->distinct('up.user_id')->count('up.user_id');
        
        $inProgressQuery = DB::table('user_progress as up')
            ->where('up.current_page', '>', 0)
            ->where('up.current_page', '<', 6);
        
        if ($role) {
            $inProgressQuery->where('up.role', $role);
        }
        
        $inProgress = $inProgressQuery->distinct('up.user_id')->count('up.user_id');
        
        $notStarted = $totalUsers - $completed - $inProgress;

        return [
            'completed' => $completed,
            'inProgress' => $inProgress,
            'notStarted' => max(0, $notStarted)
        ];
    }

    private function getUserStatus($role = null)
    {
        $users = DB::table('users')
            ->select('id', 'name')
            ->get();

        $userStatus = [];

        foreach ($users as $user) {
            $n0Status = $this->getProgressStatus($user->id, 'n0');
            $n1Status = $this->getProgressStatus($user->id, 'n1');
            $n2Status = $this->getProgressStatus($user->id, 'n2');

            $userStatus[] = [
                'id' => $user->id,
                'name' => $user->name,
                'n0' => $n0Status,
                'n1' => $n1Status,
                'n2' => $n2Status
            ];
        }

        return $userStatus;
    }

    private function getProgressStatus($userId, $userRole)
    {
        $progress = DB::table('user_progress')
            ->where('user_id', $userId)
            ->where('role', $userRole)
            ->first();

        if (!$progress) {
            return 'not_started';
        }

        $requiredPages = $userRole == 'n0' ? 6 : 8;
        
        if ($progress->current_page >= $requiredPages) {
            return 'completed';
        }

        return 'in_progress';
    }

    private function getTeamRanking($role = null)
    {
        $teamPerformance = $this->getTeamPerformance($role);
        
        return $teamPerformance->sortByDesc('averageScore')->values()->toArray();
    }

    private function getCompetencySummary($role = null)
    {
        $competencies = $this->getCompetencies();
        $summary = [];

        foreach ($competencies as $competency) {
            $query = DB::table('responses as r')
                ->leftJoin('questions as q', 'r.question_id', '=', 'q.id')
                ->where('q.category', $competency);

            if ($role) {
                $query->where('r.role', $role);
            }

            $avgScore = $query->avg('r.response_value') ?? 0;
            $count = $query->count();

            $summary[$competency] = [
                'average' => $avgScore,
                'count' => $count
            ];
        }

        return $summary;
    }

    private function getCommentSummary($role = null)
    {
        $query = DB::table('appreciations')
            ->whereNotNull('comment');

        if ($role) {
            // Note: You might need to adjust this based on how roles are stored in appreciations table
            $query->where('role', $role);
        }

        $comments = $query->pluck('comment');

        $positive = 0;
        $improvement = 0;
        $neutral = 0;

        $positiveKeywords = ['excellent', 'outstanding', 'good', 'great', 'satisfactory', 'superb', 'amazing'];
        $improvementKeywords = ['improve', 'develop', 'enhance', 'weak', 'need', 'better', 'lacking'];

        foreach ($comments as $comment) {
            $commentLower = strtolower($comment);
            $hasPositive = false;
            $hasImprovement = false;

            foreach ($positiveKeywords as $keyword) {
                if (strpos($commentLower, $keyword) !== false) {
                    $hasPositive = true;
                    break;
                }
            }

            foreach ($improvementKeywords as $keyword) {
                if (strpos($commentLower, $keyword) !== false) {
                    $hasImprovement = true;
                    break;
                }
            }

            if ($hasPositive && !$hasImprovement) {
                $positive++;
            } elseif ($hasImprovement) {
                $improvement++;
            } else {
                $neutral++;
            }
        }

        return [
            'positive' => $positive,
            'improvement' => $improvement,
            'neutral' => $neutral
        ];
    }

    private function getFrequentKeywords($role = null)
    {
        $query = DB::table('appreciations')
            ->whereNotNull('comment');

        if ($role) {
            $query->where('role', $role);
        }

        $comments = $query->pluck('comment');
        
        $allWords = [];
        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'can', 'may', 'might', 'must'];

        foreach ($comments as $comment) {
            $words = str_word_count(strtolower($comment), 1);
            foreach ($words as $word) {
                if (strlen($word) > 3 && !in_array($word, $stopWords)) {
                    if (!isset($allWords[$word])) {
                        $allWords[$word] = 0;
                    }
                    $allWords[$word]++;
                }
            }
        }

        arsort($allWords);
        return array_slice($allWords, 0, 10, true);
    }

    private function getPerformanceTrend($role = null)
    {
        $months = [];
        $datasets = [];

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('M Y');
        }

        // Get trend data for each role or specified role
        $roles = $role ? [$role] : ['n0', 'n1', 'n2'];
        $colors = ['n0' => '#3498db', 'n1' => '#2ecc71', 'n2' => '#e74c3c'];

        foreach ($roles as $currentRole) {
            $data = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $startDate = Carbon::now()->subMonths($i)->startOfMonth();
                $endDate = Carbon::now()->subMonths($i)->endOfMonth();
                
                $avgScore = DB::table('user_results')
                    ->where('role', $currentRole)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->avg('result') ?? 0;
                
                $data[] = round($avgScore, 2);
            }

            $datasets[] = [
                'label' => strtoupper($currentRole),
                'data' => $data,
                'borderColor' => $colors[$currentRole],
                'backgroundColor' => $colors[$currentRole] . '20',
                'tension' => 0.4
            ];
        }

        return [
            'months' => $months,
            'datasets' => $datasets
        ];
    }
}

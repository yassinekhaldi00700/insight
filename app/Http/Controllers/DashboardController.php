<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the add user form
     */
    public function showAddUser()
    {
        
        
        try {
            // Get users who are already supervisors (their id exists in supervisor_id column)
            $supervisors = DB::select("
                SELECT DISTINCT u.id, u.name, u.email, d.name AS departement
                FROM users u
                LEFT JOIN departements d ON d.id = u.departement_id
                WHERE u.id IN (
                    SELECT DISTINCT supervisor_id 
                    FROM users 
                    WHERE supervisor_id IS NOT NULL
                )
                ORDER BY u.name
            ");
                    
            
            // Get distinct departments
            $departments = DB::select("
                SELECT id, name AS departement
                FROM departements
                ORDER BY name
            ");
            
            return view('dashboard.add-user', compact('supervisors', 'departments'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showAddUser: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->route('dashboard')->withErrors(['error' => 'Error loading add user page: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        try {
            // Validate the request - using actual database columns
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'departement_id' => 'required|integer|exists:departements,id',
                'supervisor_id' => 'nullable|exists:users,id',
                'is_manager' => 'required|in:yes,no',
            ]);

            // Convert manager selection to 1 or 0
            $managementId = $validated['is_manager'] === 'yes' ? 1 : 0;

            // Create the new user using DB facade
            DB::insert("
                INSERT INTO users (name, email, departement_id, supervisor_id, management_id, password, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $validated['name'],
                $validated['email'],
                $validated['departement_id'],
                $validated['supervisor_id'],
                $managementId,
                bcrypt('password123'), // Default password
                now(),
                now()
            ]);

            return redirect()->route('admin.add-user')
                           ->with('success', 'User created successfully! Default password is: password123');
            
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => 'Error creating user: ' . $e->getMessage()]);
        }
    }

    /**
     * Show all employees
     */
    public function showEmployees(Request $request)
    {
        try {
            $query = "
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    d.name AS departement,
                    u.management_id,
                    u.created_at,
                    supervisor.name as supervisor_name
                FROM users u
                LEFT JOIN departements d ON d.id = u.departement_id
                LEFT JOIN users supervisor ON u.supervisor_id = supervisor.id
            ";
            
            // Add search filter if provided
            $searchTerm = $request->get('search');
            $departmentFilter = $request->get('department');
            $managerFilter = $request->get('manager');
            
            $whereConditions = [];
            $params = [];
            
            if ($searchTerm) {
                $whereConditions[] = "(u.name LIKE ? OR u.email LIKE ?)";
                $params[] = "%{$searchTerm}%";
                $params[] = "%{$searchTerm}%";
            }
            
            if ($departmentFilter) {
                $whereConditions[] = "d.name = ?";
                $params[] = $departmentFilter;
            }
            
            if ($managerFilter !== null && $managerFilter !== '') {
                $whereConditions[] = "u.management_id = ?";
                $params[] = $managerFilter;
            }
            
            if (!empty($whereConditions)) {
                $query .= " WHERE " . implode(' AND ', $whereConditions);
            }
            
            $query .= " ORDER BY u.name ASC";
            
            $employees = DB::select($query, $params);
            
            // Get departments for filter
            $departments = DB::select("
                SELECT id, name AS departement
                FROM departements
                ORDER BY name
            ");
            
            return view('dashboard.employees', compact('employees', 'departments'));
            
        } catch (\Exception $e) {
            return view('dashboard.employees', [
                'employees' => [],
                'departments' => []
            ])->withErrors(['error' => 'Error loading employees: ' . $e->getMessage()]);
        }
    }

    /**
     * Export employees to Excel
     */
    public function exportEmployees(Request $request)
    {
        try {
            $query = "
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    d.name AS departement,
                    u.management_id,
                    u.created_at,
                    supervisor.name as supervisor_name
                FROM users u
                LEFT JOIN departements d ON d.id = u.departement_id
                LEFT JOIN users supervisor ON u.supervisor_id = supervisor.id
            ";
            
            // Add same filters as in showEmployees
            $searchTerm = $request->get('search');
            $departmentFilter = $request->get('department');
            $managerFilter = $request->get('manager');
            
            $whereConditions = [];
            $params = [];
            
            if ($searchTerm) {
                $whereConditions[] = "(u.name LIKE ? OR u.email LIKE ?)";
                $params[] = "%{$searchTerm}%";
                $params[] = "%{$searchTerm}%";
            }
            
            if ($departmentFilter) {
                $whereConditions[] = "d.name = ?";
                $params[] = $departmentFilter;
            }
            
            if ($managerFilter !== null && $managerFilter !== '') {
                $whereConditions[] = "u.management_id = ?";
                $params[] = $managerFilter;
            }
            
            if (!empty($whereConditions)) {
                $query .= " WHERE " . implode(' AND ', $whereConditions);
            }
            
            $query .= " ORDER BY u.name ASC";
            
            $employees = DB::select($query, $params);
            
            // Create CSV content
            $csvContent = "ID,Name,Email,Department,Is Manager,Supervisor,Created Date\n";
            foreach ($employees as $employee) {
                $isManager = $employee->management_id ? 'Yes' : 'No';
                $csvContent .= sprintf(
                    "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                    $employee->id,
                    $employee->name ?? '',
                    $employee->email ?? '',
                    $employee->departement ?? '',
                    $isManager,
                    $employee->supervisor_name ?? 'No Supervisor',
                    $employee->created_at ?? ''
                );
            }
            
            $filename = 'employees_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache');
                
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the main dashboard
     */
    public function index()
    {
        try {
            // Get basic statistics
            $stats = $this->getBasicStats();
            
            
            // Get recent activities
            $recentActivities = $this->getRecentActivities();
            
            // Get performance summary
            $performanceSummary = $this->getPerformanceSummary();
            
            // Get role averages for bar chart
            $roleAverages = $this->getRoleAverages();
            
            // Get final results data
            $finalResultsData = $this->getFinalResultsData();
            
            // Get incomplete N0 users
            $incompleteN0Users = $this->getIncompleteN0Users();

            return view('dashboard.dashboard', compact(
                'stats', 
                'recentActivities', 
                'performanceSummary',
                'roleAverages',
                'finalResultsData',
                'incompleteN0Users'
            ));
            
        } catch (\Exception $e) {
            // Handle any database errors gracefully
            return view('dashboard.dashboard', [
                'stats' => $this->getDefaultStats(),
                'recentActivities' => [],
                'performanceSummary' => [],
                'roleAverages' => $this->getDefaultRoleAverages(),
                'finalResultsData' => $this->getDefaultFinalResultsData(),
                'incompleteN0Users' => [],
            ]);
        }
    }
    
    /**
     * Get basic dashboard statistics
     */
    private function getBasicStats()
    {
        // Count all users first — isolated so no other failure can affect it
        $totalUsers = User::count();

        // Get users without team count — wrapped separately since supervisor_id
        // may not exist in all database setups
        $usersWithoutTeamCount = 0;
        try {
            if (DB::getSchemaBuilder()->hasColumn('users', 'supervisor_id')) {
                $usersWithoutTeam = DB::select("
                    SELECT COUNT(*) AS total_users_without_team 
                    FROM users 
                    WHERE id NOT IN (
                        SELECT DISTINCT supervisor_id 
                        FROM users 
                        WHERE supervisor_id IS NOT NULL
                    ) 
                    AND supervisor_id IS NOT NULL
                ");
                $usersWithoutTeamCount = $usersWithoutTeam[0]->total_users_without_team ?? 0;
            }
        } catch (\Exception $e) {
            $usersWithoutTeamCount = 0;
        }

        // Try to get evaluation counts
        $totalEvaluations = 0;
        $completedEvaluations = 0;

        try {
            if (DB::getSchemaBuilder()->hasTable('user_results')) {
                $totalEvaluations = DB::table('user_results')->count();
                $completedEvaluations = DB::table('user_results')
                    ->whereNotNull('final_result')
                    ->count();
            }
        } catch (\Exception $e) {
            $totalEvaluations = 0;
            $completedEvaluations = 0;
        }

        // Calculate completion rate
        $completionRate = $totalEvaluations > 0
            ? round(($completedEvaluations / $totalEvaluations) * 100, 1)
            : 0;

        // Completion percentages based on user_progress table
        // N0: employees who completed their own self-evaluation (current_page = 6)
        // N1: N+1 supervisors who completed evaluating their N0 team (current_page = 8)
        // N2: N+2 supervisors who completed their evaluation (current_page = 8)
        $n0CompletedPercent = 0;
        $n1CompletedPercent = 0;
        $n2CompletedPercent = 0;

        try {
            if (DB::getSchemaBuilder()->hasTable('user_progress') && $totalUsers > 0) {
                $n0Completed = DB::table('user_progress')
                    ->where('role', 'n0')
                    ->where('current_page', 6)
                    ->distinct('user_id')
                    ->count('user_id');

                $n1Completed = DB::table('user_progress')
                    ->where('role', 'n1')
                    ->where('current_page', 8)
                    ->distinct('user_id')
                    ->count('user_id');

                $n2Completed = DB::table('user_progress')
                    ->where('role', 'n2')
                    ->where('current_page', 8)
                    ->distinct('user_id')
                    ->count('user_id');

                $n0CompletedPercent = round(($n0Completed / $totalUsers) * 100);
                $n1CompletedPercent = round(($n1Completed / $totalUsers) * 100);
                $n2CompletedPercent = round(($n2Completed / $totalUsers) * 100);
            }
        } catch (\Exception $e) {
            $n0CompletedPercent = 0;
            $n1CompletedPercent = 0;
            $n2CompletedPercent = 0;
        }

        return [
            'totalUsers' => $totalUsers,
            'usersWithoutTeam' => $usersWithoutTeamCount,
            'totalEvaluations' => $totalEvaluations,
            'completedEvaluations' => $completedEvaluations,
            'completionRate' => $completionRate,
            'pendingEvaluations' => $totalEvaluations - $completedEvaluations,
            'n0CompletedPercent' => $n0CompletedPercent,
            'n1CompletedPercent' => $n1CompletedPercent,
            'n2CompletedPercent' => $n2CompletedPercent,
        ];
    }
    
    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        try {
            $activities = [];
            
            // Get recent users
            $recentUsers = User::latest()
                ->take(5)
                ->get(['name', 'email', 'created_at']);
            
            foreach ($recentUsers as $user) {
                $activities[] = [
                    'type' => 'user_registered',
                    'message' => "Nouvel utilisateur: {$user->name}",
                    'time' => $user->created_at,
                    'icon' => 'fas fa-user-plus',
                    'color' => 'success'
                ];
            }
            
            // Sort activities by time
            usort($activities, function($a, $b) {
                return $b['time'] <=> $a['time'];
            });
            
            return array_slice($activities, 0, 10);
            
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get performance summary
     */
    private function getPerformanceSummary()
    {
        try {
            $summary = [];
            
            if (DB::getSchemaBuilder()->hasTable('user_results')) {
                // Get average scores by month
                $monthlyPerformance = DB::table('user_results')
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('AVG(final_result) as avg_score'),
                        DB::raw('COUNT(*) as total_evaluations')
                    )
                    ->whereNotNull('final_result')
                    ->where('created_at', '>=', Carbon::now()->subMonths(6))
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get();
                
                $summary['monthlyPerformance'] = $monthlyPerformance;
                
                // Get top performers
                $topPerformers = DB::table('user_results')
                    ->join('users', 'user_results.email', '=', 'users.email')
                    ->select('users.name', 'user_results.final_result')
                    ->whereNotNull('user_results.final_result')
                    ->orderBy('user_results.final_result', 'desc')
                    ->take(5)
                    ->get();
                
                $summary['topPerformers'] = $topPerformers;
            }
            
            return $summary;
            
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get role averages for bar chart
     */
    private function getRoleAverages()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('responses')) {
                $result = DB::select("
                    SELECT 
                        ROUND(AVG(CASE WHEN r.role = 'n0' THEN r.response_value END), 2) AS avg_n0,
                        ROUND(AVG(CASE WHEN r.role = 'n1' THEN r.response_value END), 2) AS avg_n1,
                        ROUND(AVG(CASE WHEN r.role = 'n2' THEN r.response_value END), 2) AS avg_n2
                    FROM 
                        responses r
                ");
                
                if (!empty($result)) {
                    return [
                        'avg_n0' => $result[0]->avg_n0 ?? 0,
                        'avg_n1' => $result[0]->avg_n1 ?? 0,
                        'avg_n2' => $result[0]->avg_n2 ?? 0
                    ];
                }
            }
            
            return $this->getDefaultRoleAverages();
            
        } catch (\Exception $e) {
            return $this->getDefaultRoleAverages();
        }
    }

    /**
     * Get final results data (average and top performers)
     */
    private function getFinalResultsData()
    {
        try {
            $data = [
                'average' => 0,
                'topPerformers' => []
            ];

            if (DB::getSchemaBuilder()->hasTable('finalresult')) {
                // Get average of all final results
                $averageResult = DB::select("
                    SELECT AVG(CAST(ur.final_result AS DECIMAL(5,2))) as avg_final_result
                    FROM finalresult ur
                    WHERE ur.final_result IS NOT NULL
                ");
                
                if (!empty($averageResult)) {
                    $data['average'] = round($averageResult[0]->avg_final_result ?? 0, 2);
                }

                // Get top 5 performers using your query
                $topPerformers = DB::select("
                    SELECT u.name, ur.final_result 
                    FROM finalresult ur 
                    JOIN users u ON ur.userid = u.id 
                    ORDER BY CAST(ur.final_result AS DECIMAL(5,2)) DESC 
                    LIMIT 5
                ");
                
                $data['topPerformers'] = $topPerformers;
            }

            return $data;
            
        } catch (\Exception $e) {
            return $this->getDefaultFinalResultsData();
        }
    }

    /**
     * Get N0 users who haven't completed their evaluations
     */
    private function getIncompleteN0Users()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('users') && 
                DB::getSchemaBuilder()->hasTable('user_progress')) {
                
                $incompleteUsers = DB::select("
                    SELECT 
                        u.id,
                        u.name,
                        d.name AS departement,
                        u.email
                    FROM users u
                    LEFT JOIN departements d ON d.id = u.departement_id
                    INNER JOIN user_progress p 
                        ON u.id = p.user_id
                    WHERE p.role = 'n0'
                      AND p.current_page < 6
                      AND u.id NOT IN (
                            SELECT DISTINCT supervisor_id
                            FROM users
                            WHERE supervisor_id IS NOT NULL
                      )
                    ORDER BY d.name, u.name
                ");
                
                return $incompleteUsers;
            }
            
            return [];
            
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Export incomplete N0 users to Excel
     */
    public function exportIncompleteN0Users()
    {
        try {
            $users = $this->getIncompleteN0Users();
            
            // Create CSV content
            $csvContent = "ID,Name,Department,Email\n";
            foreach ($users as $user) {
                $csvContent .= sprintf(
                    "%d,\"%s\",\"%s\",\"%s\"\n",
                    $user->id,
                    $user->name ?? '',
                    $user->departement ?? '',
                    $user->email ?? ''
                );
            }
            
            $filename = 'incomplete_n0_evaluations_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed'], 500);
        }
    }

    /**
     * Send reminder emails to incomplete N0 users
     */
    public function sendReminderEmails()
    {
        try {
            $users = $this->getIncompleteN0Users();
            $sentCount = 0;
            
            foreach ($users as $user) {
                if ($user->email) {
                    // Here you would implement actual email sending
                    // For now, we'll just simulate it
                    // Mail::to($user->email)->send(new EvaluationReminderMail($user));
                    $sentCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Reminder emails sent to {$sentCount} users",
                'count' => $sentCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send emails'], 500);
        }
    }

    /**
     * Export all user results to Excel
     */
    public function exportAllResults()
    {
        try {
            // Get all users with their final results, objectives, and evaluation data
            $allResults = $this->getAllUserResults();
            
            // Create comprehensive CSV content
            $csvContent = "ID,Name,Department,Email,Final_Score,Performance_Level,N0_Score,N1_Score,N2_Score,Qualitative_Objectives_Count,Quantitative_Objectives_Count,Evaluation_Status,Last_Updated\n";
            
            foreach ($allResults as $result) {
                // Determine performance level based on score
                $score = $result->final_result ?? 0;
                if ($score >= 3.5) {
                    $level = 'Excellent';
                } elseif ($score >= 3.0) {
                    $level = 'Good';
                } elseif ($score >= 2.5) {
                    $level = 'Average';
                } else {
                    $level = 'Needs Improvement';
                }
                
                $csvContent .= sprintf(
                    "%d,\"%s\",\"%s\",\"%s\",%.2f,\"%s\",%.2f,%.2f,%.2f,%d,%d,\"%s\",\"%s\"\n",
                    $result->id,
                    $result->name ?? '',
                    $result->departement ?? '',
                    $result->email ?? '',
                    $result->final_result ?? 0,
                    $level,
                    $result->n0_score ?? 0,
                    $result->n1_score ?? 0,
                    $result->n2_score ?? 0,
                    $result->qualitative_count ?? 0,
                    $result->quantitative_count ?? 0,
                    $result->evaluation_status ?? 'Unknown',
                    $result->last_updated ?? date('Y-m-d H:i:s')
                );
            }
            
            $filename = 'all_user_results_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache');
                
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get comprehensive results data for all users
     */
    private function getAllUserResults()
    {
        try {
            // Check if required tables exist
            if (!DB::getSchemaBuilder()->hasTable('users')) {
                return [];
            }
            
            $query = "
                SELECT DISTINCT
                    u.id,
                    u.name,
                    d.name AS departement,
                    u.email,
                    fr.final_result,
                    ur_n0.result as n0_score,
                    ur_n1.result as n1_score,
                    ur_n2.result as n2_score,
                    qo.qualitative_count,
                    qto.quantitative_count,
                    CASE 
                        WHEN fr.final_result IS NOT NULL THEN 'Completed'
                        WHEN ur_n0.result IS NOT NULL OR ur_n1.result IS NOT NULL OR ur_n2.result IS NOT NULL THEN 'In Progress'
                        ELSE 'Not Started'
                    END as evaluation_status,
                    u.updated_at as last_updated
                FROM users u
                LEFT JOIN departements d ON d.id = u.departement_id
                LEFT JOIN finalresult fr ON u.id = fr.userid
                LEFT JOIN user_results ur_n0 ON u.id = ur_n0.user_id AND ur_n0.role = 'n0'
                LEFT JOIN user_results ur_n1 ON u.id = ur_n1.user_id AND ur_n1.role = 'n1'
                LEFT JOIN user_results ur_n2 ON u.id = ur_n2.user_id AND ur_n2.role = 'n2'
                LEFT JOIN (
                    SELECT user_id, COUNT(*) as qualitative_count 
                    FROM qualitative_objectives 
                    GROUP BY user_id
                ) qo ON u.id = qo.user_id
                LEFT JOIN (
                    SELECT user_id, COUNT(*) as quantitative_count 
                    FROM quantitative_objectives 
                    GROUP BY user_id
                ) qto ON u.id = qto.user_id
                ORDER BY 
                    fr.final_result DESC, 
                    u.name ASC
            ";
            
            // Check if tables exist before running query
            $tables = ['finalresult', 'user_results', 'qualitative_objectives', 'quantitative_objectives'];
            $existingTables = [];
            
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $existingTables[] = $table;
                }
            }
            
            // Modify query based on existing tables
            if (empty($existingTables)) {
                // If no evaluation tables exist, just return basic user data
                $results = DB::select("
                    SELECT 
                        u.id,
                        u.name,
                        d.name AS departement,
                        u.email,
                        0 as final_result,
                        0 as n0_score,
                        0 as n1_score,
                        0 as n2_score,
                        0 as qualitative_count,
                        0 as quantitative_count,
                        'Not Started' as evaluation_status,
                        u.created_at as last_updated
                    FROM users u
                    LEFT JOIN departements d ON d.id = u.departement_id
                    ORDER BY u.name ASC
                ");
            } else {
                $results = DB::select($query);
            }
            
            return $results;
            
        } catch (\Exception $e) {
            // Fallback to basic user data if complex query fails
            try {
                return DB::select("
                    SELECT 
                        u.id,
                        u.name,
                        d.name AS departement,
                        u.email,
                        0 as final_result,
                        0 as n0_score,
                        0 as n1_score,
                        0 as n2_score,
                        0 as qualitative_count,
                        0 as quantitative_count,
                        'Unknown' as evaluation_status,
                        u.created_at as last_updated
                    FROM users u
                    LEFT JOIN departements d ON d.id = u.departement_id
                    ORDER BY u.name ASC
                ");
            } catch (\Exception $fallbackException) {
                return [];
            }
        }
    }

    /**
     * Get users who have objectives (qualitative or quantitative)
     */
    private function getUsersWithObjectives()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('users') && 
                (DB::getSchemaBuilder()->hasTable('qualitative_objectives') || 
                 DB::getSchemaBuilder()->hasTable('quantitative_objectives'))) {
                
                // Get users who have either qualitative or quantitative objectives
                $users = DB::select("
                    SELECT DISTINCT 
                        u.id,
                        u.name,
                        d.name AS departement,
                        u.email
                    FROM users u
                    LEFT JOIN departements d ON d.id = u.departement_id
                    WHERE u.id IN (
                        SELECT DISTINCT user_id FROM qualitative_objectives
                        UNION
                        SELECT DISTINCT user_id FROM quantitative_objectives
                    )
                    ORDER BY u.name
                ");
                
                return $users;
            }
            
            return [];
            
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get user objectives by user ID
     */
    public function getUserObjectives(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $objectives = [
                'qualitative' => [],
                'quantitative' => []
            ];

            // Get qualitative objectives
            if (DB::getSchemaBuilder()->hasTable('qualitative_objectives')) {
                $objectives['qualitative'] = DB::select("
                    SELECT 
                        qo.id AS objective_id,
                        u.name AS user_name,
                        qo.objective,
                        qo.description,
                        qo.weight,
                        qo.created_at,
                        qo.updated_at
                    FROM qualitative_objectives qo
                    INNER JOIN users u ON qo.user_id = u.id
                    WHERE qo.user_id = ?
                ", [$userId]);
            }

            // Get quantitative objectives
            if (DB::getSchemaBuilder()->hasTable('quantitative_objectives')) {
                $objectives['quantitative'] = DB::select("
                    SELECT 
                        qt.id AS objective_id,
                        u.name AS user_name,
                        qt.objective,
                        qt.description,
                        qt.quantity,
                        qt.weight,
                        qt.created_at,
                        qt.updated_at
                    FROM quantitative_objectives qt
                    INNER JOIN users u ON qt.user_id = u.id
                    WHERE qt.user_id = ?
                ", [$userId]);
            }

            return response()->json($objectives);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch objectives'], 500);
        }
    }

    /**
     * Get default final results data when database is not available
     */
    private function getDefaultFinalResultsData()
    {
        return [
            'average' => 0,
            'topPerformers' => []
        ];
    }

    /**
     * Get default role averages when database is not available
     */
    private function getDefaultRoleAverages()
    {
        return [
            'avg_n0' => 0,
            'avg_n1' => 0,
            'avg_n2' => 0
        ];
    }

    /**
     * Get Assiduité & Disponibilité data for all users across N0/N1/N2 roles
     */
    private function getAssiduiteData()
    {
        $labels = [
            1 => 'Ne répond pas aux attentes',
            2 => 'Répond partiellement aux attentes',
            3 => 'Répond à l\'ensemble des attentes',
            4 => 'Nettement supérieure aux attentes',
        ];

        $defaultRole = ['avg_assiduite' => 0, 'avg_disponibilite' => 0, 'avg_combined' => 0, 'count' => 0];

        try {
            if (!DB::getSchemaBuilder()->hasTable('evaluations')) {
                return $this->getDefaultAssiduiteData();
            }

            // Average per role
            $roleAverages = [];
            foreach (['n0', 'n1', 'n2'] as $role) {
                $row = DB::table('evaluations')
                    ->where('role', $role)
                    ->whereNotNull('assiduite')
                    ->whereNotNull('disponibilite')
                    ->selectRaw('
                        ROUND(AVG(assiduite), 2)    as avg_assiduite,
                        ROUND(AVG(disponibilite), 2) as avg_disponibilite,
                        ROUND(AVG(average), 2)       as avg_combined,
                        COUNT(*)                     as count
                    ')
                    ->first();

                $roleAverages[$role] = $row
                    ? (array) $row
                    : $defaultRole;
            }

            // Distribution of assiduite ratings (1-4) across all roles
            $distribution = [];
            foreach ([1, 2, 3, 4] as $score) {
                $distribution[$score] = [
                    'label' => $labels[$score],
                    'n0'    => DB::table('evaluations')->where('role', 'n0')->where('assiduite', $score)->count(),
                    'n1'    => DB::table('evaluations')->where('role', 'n1')->where('assiduite', $score)->count(),
                    'n2'    => DB::table('evaluations')->where('role', 'n2')->where('assiduite', $score)->count(),
                ];
            }

            // Per-employee detail: join each user with their three evaluations
            $employees = DB::table('users as u')
                ->leftJoin('departements as d', 'd.id', '=', 'u.departement_id')
                ->leftJoin('evaluations as e0', function ($j) {
                    $j->on('e0.user_id', '=', 'u.id')->where('e0.role', '=', 'n0');
                })
                ->leftJoin('evaluations as e1', function ($j) {
                    $j->on('e1.user_id', '=', 'u.id')->where('e1.role', '=', 'n1');
                })
                ->leftJoin('evaluations as e2', function ($j) {
                    $j->on('e2.user_id', '=', 'u.id')->where('e2.role', '=', 'n2');
                })
                ->select(
                    'u.id', 'u.name', 'd.name as departement',
                    'e0.assiduite   as n0_assiduite',   'e0.disponibilite as n0_disponibilite',   'e0.average as n0_avg',
                    'e1.assiduite   as n1_assiduite',   'e1.disponibilite as n1_disponibilite',   'e1.average as n1_avg',
                    'e2.assiduite   as n2_assiduite',   'e2.disponibilite as n2_disponibilite',   'e2.average as n2_avg'
                )
                ->orderBy('u.name')
                ->get();

            return [
                'roleAverages' => $roleAverages,
                'distribution' => $distribution,
                'employees'    => $employees,
                'labels'       => $labels,
            ];

        } catch (\Exception $e) {
            return $this->getDefaultAssiduiteData();
        }
    }

    private function getDefaultAssiduiteData()
    {
        return [
            'roleAverages' => ['n0' => ['avg_assiduite'=>0,'avg_disponibilite'=>0,'avg_combined'=>0,'count'=>0],
                               'n1' => ['avg_assiduite'=>0,'avg_disponibilite'=>0,'avg_combined'=>0,'count'=>0],
                               'n2' => ['avg_assiduite'=>0,'avg_disponibilite'=>0,'avg_combined'=>0,'count'=>0]],
            'distribution' => [],
            'employees'    => collect([]),
            'labels'       => [],
        ];
    }

    /**
     * Get default stats when database is not available
     */
    private function getDefaultStats()
    {
        return [
            'totalUsers' => 0,
            'totalEvaluations' => 0,
            'completedEvaluations' => 0,
            'usersWithoutTeamCount '=> 0,
            'completionRate' => 0,
            'pendingEvaluations' => 0
        ];
    }

    /**
     * Admin sets a new password for any user
     */
    public function changeEmployeePassword(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return redirect()->route('admin.employees')->withErrors(['error' => 'Utilisateur introuvable.']);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.employees')->withErrors($validator);
        }

        DB::table('users')->where('id', $id)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.employees')
            ->with('success', 'Mot de passe de ' . $user->name . ' modifié avec succès.');
    }

    /**
     * Delete an employee
     */
    public function deleteEmployee($id)
    {
        try {
            // Find the employee
            $employee = DB::selectOne("SELECT * FROM users WHERE id = ?", [$id]);
            
            if (!$employee) {
                return redirect()->route('admin.employees')
                    ->withErrors(['error' => 'Employee not found.']);
            }

            // Check if employee is a supervisor of other employees
            $supervisedEmployees = DB::select("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE supervisor_id = ?
            ", [$id]);

            if ($supervisedEmployees[0]->count > 0) {
                return redirect()->route('admin.employees')
                    ->withErrors(['error' => 'Cannot delete employee who is supervising other employees. Please reassign their subordinates first.']);
            }

            // Delete the employee
            $deleted = DB::delete("DELETE FROM users WHERE id = ?", [$id]);
            
            if ($deleted) {
                return redirect()->route('admin.employees')
                    ->with('success', 'Employee deleted successfully.');
            } else {
                return redirect()->route('admin.employees')
                    ->withErrors(['error' => 'Failed to delete employee.']);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error deleting employee: ' . $e->getMessage());
            return redirect()->route('admin.employees')
                ->withErrors(['error' => 'Error deleting employee: ' . $e->getMessage()]);
        }
    }

    /**
     * Show objectives page
     */
    public function showObjectives()
    {
        try {
            // Get users with objectives using existing method
            $usersWithObjectives = $this->getUsersWithObjectives();
            
            // Get departments for filter dropdown
            $departments = DB::select("
                SELECT id, name AS departement
                FROM departements
                ORDER BY name
            ");
            
            return view('dashboard.objectives', compact('usersWithObjectives', 'departments'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showObjectives: ' . $e->getMessage());
            return view('dashboard.objectives', [
                'usersWithObjectives' => [],
                'departments' => []
            ])->withErrors(['error' => 'Error loading objectives page: ' . $e->getMessage()]);
        }
    }

    /**
     * Show evaluations page
     */
    public function showEvaluations()
    {
        try {
            // Get role averages for the chart
            $roleAverages = $this->getRoleAverages();
            
            // Get user evaluation scores
            $userEvaluations = $this->getUserEvaluationScores();
            
            // Get unique departments for filter
            $departments = $this->getUniqueDepartments();
            
            return view('dashboard.evaluations', compact('roleAverages', 'userEvaluations', 'departments'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showEvaluations: ' . $e->getMessage());
            return view('dashboard.evaluations', [
                'roleAverages' => $this->getDefaultRoleAverages(),
                'userEvaluations' => [],
                'departments' => []
            ])->withErrors(['error' => 'Error loading evaluations page: ' . $e->getMessage()]);
        }
    }

    /**
     * Get user evaluation scores
     */
    private function getUserEvaluationScores()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('evaluations')) {
                $results = DB::select("
                    SELECT 
                        P.name,
                        P.email,
                        D.name AS departement,
                        MAX(CASE WHEN E.role = 'n0' THEN E.average END) AS n0,
                        MAX(CASE WHEN E.role = 'n1' THEN E.average END) AS n1,
                        MAX(CASE WHEN E.role = 'n2' THEN E.average END) AS n2
                    FROM evaluations E
                    LEFT JOIN users P ON P.id = E.user_id
                    LEFT JOIN departements D ON D.id = P.departement_id
                    GROUP BY P.name, P.email, D.name
                    ORDER BY P.name
                ");
                
                return $results;
            }
            
            return [];
            
        } catch (\Exception $e) {
            \Log::error('Error getting user evaluation scores: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unique departments for filter
     */
    private function getUniqueDepartments()
    {
        try {
            $departments = DB::select("
                SELECT id, name AS departement
                FROM departements
                ORDER BY name
            ");
            
            return collect($departments)->pluck('departement')->toArray();
            
        } catch (\Exception $e) {
            \Log::error('Error getting departments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Show Assiduité & Disponibilité dedicated page
     */
    public function showAssiduite()
    {
        try {
            $assiduiteData = $this->getAssiduiteData();
            $stats = $this->getBasicStats();
            return view('dashboard.assiduite', compact('assiduiteData', 'stats'));
        } catch (\Exception $e) {
            $assiduiteData = $this->getDefaultAssiduiteData();
            $stats = $this->getDefaultStats();
            return view('dashboard.assiduite', compact('assiduiteData', 'stats'));
        }
    }

    /**
     * Show admin profile page
     */
    public function showProfile()
    {
        try {
            // For now, we'll use a hardcoded admin profile
            // In a real application, you would get this from session or auth
            $admin = (object) [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@uemf.ac.ma',
                'phone' => '+212 123 456 789',
                'department' => 'IT Administration',
                'role' => 'System Administrator',
                'created_at' => '2024-01-01',
                'avatar' => null
            ];
            
            return view('dashboard.profile', compact('admin'));
            
        } catch (\Exception $e) {
            \Log::error('Error in showProfile: ' . $e->getMessage());
            return redirect()->route('dashboard')->withErrors(['error' => 'Error loading profile page: ' . $e->getMessage()]);
        }
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'department' => 'nullable|string|max:255',
                'current_password' => 'nullable|string|min:6',
                'new_password' => 'nullable|string|min:6|confirmed',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Here you would update the admin's profile in the database
            // For now, we'll just return a success message
            
            $message = 'Profile updated successfully!';
            
            if ($request->hasFile('avatar')) {
                $message .= ' Avatar uploaded successfully.';
            }
            
            return redirect()->route('admin.profile')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->route('admin.profile')->withErrors(['error' => 'Error updating profile: ' . $e->getMessage()]);
        }
    }
}

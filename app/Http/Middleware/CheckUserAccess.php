<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckUserAccess
{
    public function handle($request, Closure $next, $requiredRole = null)
    {
        $userId = session('user_id'); 

        // Check if the user is logged in
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access. Please log in.']);
        }

        // Check if the user exists in the 'sup' column
        $existsInSup = DB::table('users')
            ->where('supervisor_id', $userId)
            ->exists();

        if (!$existsInSup) {
            return abort(403, 'Unauthorized access: You are not allowed to view this page.');
        }

        // Optional: Check for required roles if specified
        $userRole = Session::get('role');
        if ($requiredRole && $userRole !== $requiredRole) {
            return abort(403, 'Unauthorized access: Insufficient permissions.');
        }

        return $next($request);
    }
}

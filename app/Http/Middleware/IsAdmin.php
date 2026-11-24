<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsAdmin
{
    /**
     * Allow the request only if the logged-in user is an admin.
     * The flag is re-read from the database so revoking admin rights takes effect immediately.
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['message' => 'Veuillez vous connecter.']);
        }

        $isAdmin = (bool) DB::table('users')->where('id', $userId)->value('is_admin');
        session(['is_admin' => $isAdmin]);

        if (!$isAdmin) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}

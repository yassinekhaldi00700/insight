<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Fetch the user based on email
        $user = DB::table('users')->where('email', $request->email)->first();
    
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user data in session
            session([
                'user_id' => $user->id,
                'management_id' => $user->management_id,
                'name' => $user->name,
                'image' => $user->image,
                'is_admin' => (bool) ($user->is_admin ?? false),
            ]);
           
    
            // Check if the user is a supervisor (exists in the `supervisor_id` column)
            $isSupervisor = DB::table('users')->where('supervisor_id', $user->id)->exists();
            session(['isSupervisor' => $isSupervisor]);
    
            // Check if the user is a supervisor of at least one other supervisor
            $isSupervisorOfSupervisor = DB::table('users')
                ->where('supervisor_id', $user->id)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('users as sub')
                          ->whereRaw('sub.supervisor_id = users.id');
                })
                ->exists();
            session(['isSupervisorOfSupervisor' => $isSupervisorOfSupervisor]);
    
            // Admins land on the dashboard, everyone else on the welcome page
            if (session('is_admin')) {
                return redirect()->route('dashboard')->with('success', 'Login successful!');
            }

            return redirect()->route('welcome')->with('success', 'Login successful!');
        }
    
        // Return back with error if login fails
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }
    
    
    
    public function logout(Request $request)
    {
        $request->session()->flush(); 
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
    public function create()
    {
        return view('auth.register'); 
    }
    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Insert user data into the users table using DB facade
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'management_id' => 0, // Default management_id
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect to login page
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}

<?php
namespace App\Http\Controllers\N1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntretienController1 extends Controller1
{
    public function fixerDate(Request $request)
{
    

    // Validate the request data
    $validated = $request->validate([
        'matricule' => 'required',
        'has_rdv' => 'required|in:oui,non',
        'rdv_date' => 'required|date',
        'rdv_time' => 'required'
    ]);

   

    // Insert data into the database
    DB::table('entretien_date')->updateOrInsert( [
        'user_id' => $request->matricule
    ],[
        'user_id' => $request->matricule,
        'date_entretien' => $request->rdv_date,
        'heure_entretien' => $request->rdv_time,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Redirect back to the same page with a success message
    return back()->with('success', 'Date successfully saved.');
}

    
}
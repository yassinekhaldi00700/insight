

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\general;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\welcomeController;
use App\Http\Controllers\processusController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AssiduitéController;
use App\Http\Controllers\opportunitéController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\resultController;

// Dashboard & admin routes — admins only (users.is_admin = 1)
Route::middleware(['check.session', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/user-objectives', [DashboardController::class, 'getUserObjectives'])->name('dashboard.user.objectives');
    Route::get('/dashboard/export-incomplete-n0', [DashboardController::class, 'exportIncompleteN0Users'])->name('dashboard.export.incomplete.n0');
    Route::get('/dashboard/export-all-results', [DashboardController::class, 'exportAllResults'])->name('dashboard.export.all.results');
    Route::post('/dashboard/send-reminder-emails', [DashboardController::class, 'sendReminderEmails'])->name('dashboard.send.reminders');

    Route::get('/admin/add-user', [DashboardController::class, 'showAddUser'])->name('admin.add-user');
    Route::post('/admin/add-user', [DashboardController::class, 'storeUser'])->name('admin.store-user');
    Route::get('/admin/employees', [DashboardController::class, 'showEmployees'])->name('admin.employees');
    Route::get('/admin/employees/export', [DashboardController::class, 'exportEmployees'])->name('admin.employees.export');
    Route::delete('/admin/employees/{id}/delete', [DashboardController::class, 'deleteEmployee'])->name('admin.employees.delete');
    Route::post('/admin/employees/{id}/password', [DashboardController::class, 'changeEmployeePassword'])->name('admin.employees.password');
    Route::get('/admin/objectives', [DashboardController::class, 'showObjectives'])->name('admin.objectives');
    Route::get('/admin/evaluations', [DashboardController::class, 'showEvaluations'])->name('admin.evaluations');
    Route::get('/admin/profile', [DashboardController::class, 'showProfile'])->name('admin.profile');
    Route::post('/admin/profile', [DashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/assiduite', [DashboardController::class, 'showAssiduite'])->name('admin.assiduite');

    // Departments management
    Route::get('/admin/departements', [DepartementController::class, 'index'])->name('admin.departements');
    Route::post('/admin/departements', [DepartementController::class, 'store'])->name('admin.departements.store');
    Route::put('/admin/departements/{id}', [DepartementController::class, 'update'])->name('admin.departements.update');
    Route::delete('/admin/departements/{id}', [DepartementController::class, 'destroy'])->name('admin.departements.destroy');
});


use App\Http\Controllers\N1\welcomeController1;
use App\Http\Controllers\N1\processusController1;
use App\Http\Controllers\N1\QuestionController1;
use App\Http\Controllers\N1\AssiduitéController1;
use App\Http\Controllers\N1\fixationController1;
use App\Http\Controllers\N1\opportunitéController1;
use App\Http\Controllers\N1\AppréciationController1;
use App\Http\Controllers\N1\SignatureController1;
use App\Http\Controllers\N1\resultController1;
use App\Http\Controllers\N1\EntretienController1;


use App\Http\Controllers\N2\processusController2;
use App\Http\Controllers\N2\QuestionController2;
use App\Http\Controllers\N2\AssiduitéController2;
use App\Http\Controllers\N2\fixationController2;
use App\Http\Controllers\N2\opportunitéController2;
use App\Http\Controllers\N2\AppréciationController2;
use App\Http\Controllers\N2\resultController2;
use App\Http\Controllers\N2\SignatureController2;













/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/' ,[welcomeController::class, 'showPage'])->name('welcome');
Route::get('/liste' ,[welcomeController::class, 'liste'])->name('liste');


Route::get('/show-page', [general::class, 'showPage'])->name('show.page');
Route::get('/show-page1', [general::class, 'showPage1'])->middleware('checkAccess')->name('show.page1');
Route::get('/show-page2/{id}', [general::class, 'showPage2'])->name('show.page2');
Route::get('/remplir/{matricule}/{name}', [general::class, 'remplir'])->name('remplir');
Route::get('/remplir1/{matricule}/{name}/{id}', [general::class, 'remplir1'])->name('remplir1');





                                        // n0
Route::get('/processus', [processusController::class, 'showForm'])->name('part0');
Route::post('/processus', [processusController::class, 'submitForm'])->name('processus.submit');




Route::get('/questions', [QuestionController::class, 'showForm'])->name('part1');
Route::post('/questions', [QuestionController::class, 'submitForm'])->name('questions.submit');


Route::get('/Assiduité', [AssiduitéController::class, 'showForm'])->name('part2');
Route::post('/Assiduité', [AssiduitéController::class, 'submitForm'])->name('Assiduité.submit');

Route::get('/opportunité', [opportunitéController::class, 'showForm'])->name('part3');
Route::post('/opportunité', [opportunitéController::class, 'submitForm'])->name('opportunité.submit');

Route::get('/signature', [SignatureController::class, 'showForm'])->name('part4');
Route::post('/signature', [SignatureController::class, 'submitForm'])->name('signature.submit');

Route::get('/result', [resultController::class, 'showForm'])->name('part5');
Route::post('/result', [resultController::class, 'submitForm'])->name('result.submit');

                                                // N1


Route::get('/processusN1/{matricule}/{name}', [processusController1::class, 'showForm'])->name('partN10');
Route::post('/processusN1/{matricule}/{name}', [processusController1::class, 'submitForm'])->name('processus.submit.N1');




Route::get('/questionsN1/{matricule}/{name}', [QuestionController1::class, 'showForm'])->middleware('checkAccess')->name('partN11');
Route::post('/questionsN1/{matricule}/{name}', [QuestionController1::class, 'submitForm'])->middleware('checkAccess')->name('questions.submit.N1');


Route::get('/AssiduitéN1/{matricule}/{name}', [AssiduitéController1::class, 'showForm'])->middleware('checkAccess')->name('partN12');
Route::post('/AssiduitéN1/{matricule}/{name}', [AssiduitéController1::class, 'submitForm'])->middleware('checkAccess')->name('Assiduité.submit.N1');

Route::get('/fixationN1/{matricule}/{name}', [fixationController1::class, 'showForm'])->middleware('checkAccess')->name('partN13');
Route::post('/fixationN1/{matricule}/{name}', [fixationController1::class, 'submitForm'])->middleware('checkAccess')->name('fixation.submit.N1');
Route::post('/fixation/supprimer/{matricule}/{name}/{type}/{id}', [fixationController1::class, 'supprimer'])->middleware('checkAccess')->name('fixation.supprimer.N1');

Route::get('/opportunitéN1/{matricule}/{name}', [opportunitéController1::class, 'showForm'])->middleware('checkAccess')->name('partN14');
Route::post('/opportunitéN1/{matricule}/{name}', [opportunitéController1::class, 'submitForm'])->middleware('checkAccess')->name('opportunité.submit.N1');

Route::get('/AppréciationN1/{matricule}/{name}', [AppréciationController1::class, 'showForm'])->middleware('checkAccess')->name('partN15');
Route::post('/AppréciationN1/{matricule}/{name}', [AppréciationController1::class, 'submitForm'])->middleware('checkAccess')->name('Appréciation.submit.N1');

Route::get('/signatureN1/{matricule}/{name}', [SignatureController1::class, 'showForm'])->middleware('checkAccess')->name('partN16');
Route::post('/signatureN1/{matricule}/{name}', [SignatureController1::class, 'submitForm'])->middleware('checkAccess')->name('signature.submit.N1');

Route::get('/resultN1/{matricule}/{name}', [resultController1::class, 'showForm'])->middleware('checkAccess')->name('partN17');
Route::post('/resultN1/{matricule}/{name}', [resultController1::class, 'submitForm'])->middleware('checkAccess')->name('result.submit.N1');


Route::post('/fixer-date', [EntretienController1::class, 'fixerDate'])->name('fixer-date');


                                                    // N2
Route::get('/processusN2/{matricule}/{name}/{id}', [processusController2::class, 'showForm'])->name('partN20');
Route::post('/processusN2/{matricule}/{name}/{id}', [processusController2::class, 'submitForm'])->name('processus.submit.N2');




Route::get('/questionsN2/{matricule}/{name}/{id}', [QuestionController2::class, 'showForm'])->name('partN21');
Route::post('/questionsN2/{matricule}/{name}/{id}', [QuestionController2::class, 'submitForm'])->name('questions.submit.N2');


Route::get('/AssiduitéN2/{matricule}/{name}/{id}', [AssiduitéController2::class, 'showForm'])->name('partN22');
Route::post('/AssiduitéN2/{matricule}/{name}/{id}', [AssiduitéController2::class, 'submitForm'])->name('Assiduité.submit.N2');

Route::get('/fixationN2/{matricule}/{name}/{id}', [fixationController2::class, 'showForm'])->name('partN23');
Route::post('/fixationN2/{matricule}/{name}/{id}', [fixationController2::class, 'submitForm'])->name('fixation.submit.N2');

Route::get('/opportunitéN2/{matricule}/{name}/{id}', [opportunitéController2::class, 'showForm'])->name('partN24');
Route::post('/opportunitéN2/{matricule}/{name}/{id}', [opportunitéController2::class, 'submitForm'])->name('opportunité.submit.N2');

Route::get('/AppréciationN2/{matricule}/{name}/{id}', [AppréciationController2::class, 'showForm'])->name('partN25');
Route::post('/AppréciationN2/{matricule}/{name}/{id}', [AppréciationController2::class, 'submitForm'])->name('Appréciation.submit.N2');

Route::get('/resultN2/{matricule}/{name}/{id}', [resultController2::class, 'showForm'])->name('partN26');
Route::post('/resultN2/{matricule}/{name}/{id}', [resultController2::class, 'submitForm'])->name('result.submit.N2');

Route::get('/signatureN2/{matricule}/{name}/{id}', [SignatureController2::class, 'showForm'])->name('partN27');
Route::post('/signatureN2/{matricule}/{name}/{id}', [SignatureController2::class, 'submitForm'])->name('signature.submit.N2');









                                              //auth 


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

Route::middleware('check.session')->group(function () {
    Route::get('/show-page', [general::class, 'showPage'])->name('show.page');
    
});
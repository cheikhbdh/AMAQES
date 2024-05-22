<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
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

// Login Routes
Route::get('/', function () {
    return view('authentification.pages-login');
})->name('login');

Route::post('/', [AuthController::class, 'login'])->name('login.post');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', function () {
    return view('authentification.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register');

// Middleware for authenticated users
Route::middleware(['auth', 'redirectIfnotEVL_I'])->group(function () {
    Route::get('/dash', function () {
        return view('layout.liste');
    })->name('dash');
});

// Middleware for admin users
Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashadmin.dashboard');
    })->name('dashadmin');

    Route::get('/users', [AuthController::class, 'user'])->name('user');

    Route::get('/champs', function () {
        return view('dashadmin.champ');
    })->name('champ');  

    Route::get('/admins', function () {
        return view('dashadmin.admin');
    })->name('admin');

    Route::get('/profile', function () {
        return view('dashadmin.profile');
    })->name('profile');

// web.php



Route::post('/utilisateurs/ajouter', [AuthController::class, 'ajouter_user'])->name('utilisateur.ajouter');
Route::put('/utilisateurs/{id}/modifier', [AuthController::class, 'modifier_user'])->name('utilisateur.modifier');
Route::delete('/utilisateurs/{id}/supprimer', [AuthController::class, 'supprimer_user'])->name('utilisateur.supprimer');


Route::post('/champ/ajouter', [AuthController::class, 'ajouter_champ'])->name('champ.ajouter');
#Route::get('/utilisateur/{id}/modifier', [AuthController::class, 'modifierForm'])->name('utilisateur.modifierForm');
Route::put('/champs/{id}/modifier', [AuthController::class, 'modifier_champ'])->name('champ.modifier');
Route::delete('/champ/{id}/supprimer', [AuthController::class, 'supprimer_champ'])->name('champ.supprimer');
Route::get('/champs/{champId}/criteres', [AuthController::class, 'showCriteres'])->name('champs.criteres');

Route::post('/critere/ajouter/{champ_id}', [AuthController::class, 'ajouter_critere'])->name('critere.ajouter');
Route::put('/critere/{id}/modifier', [AuthController::class, 'modifier_critere'])->name('critere.modifier');
Route::delete('/critere/{id}/supprimer', [AuthController::class, 'supprimer_critere'])->name('critere.supprimer');

Route::post('/useradmin/ajouter', [AuthController::class, 'store_admin'])->name('useradmin.ajouter');
Route::put('/useradmin/{id}/modifier', [AuthController::class, 'update_admin'])->name('useradmin.modifier');
Route::delete('/useradmin/{id}/supprimer', [AuthController::class, 'destroy_admin'])->name('useradmin.supprimer');

Route::get('/admin/utilisateurs', [AuthController::class, 'adminIndex'])->name('admin.utilisateurs');
// routes/web.php


Route::get('/institutions', [EducationController::class, 'indexInstitutions'])->name('institutions.index');
Route::post('/institutions', [EducationController::class, 'storeInstitution'])->name('institutions.store');
Route::put('/institutions/{id}', [EducationController::class, 'updateInstitution'])->name('institutions.update');
Route::delete('/institutions/{id}', [EducationController::class, 'destroyInstitution'])->name('institutions.destroy');

Route::get('/etablissement', [EducationController::class, 'indexEtablissement'])->name('etablissement.index');
Route::put('/etablissement/{id}', [EducationController::class, 'updateEtablissement'])->name('etablissement.update');
Route::delete('/etablissement/{id}', [EducationController::class, 'destroyEtablissement'])->name('etablissement.destroy');
Route::post('/etablissement', [EducationController::class, 'storeEtablissement'])->name('etablissement.store');
Route::get('/departement', [EducationController::class, 'indexDepartement'])->name('departement.index');
Route::put('/departement/{id}', [EducationController::class, 'updateDepartement'])->name('departement.update');
Route::delete('/departement/{id}', [EducationController::class, 'destroyDepartement'])->name('departement.destroy');
Route::post('/departement', [EducationController::class, 'storeDepartement'])->name('departement.store');
Route::get('/filiere', [EducationController::class, 'indexFiliere'])->name('filiere.index');
Route::put('/filiere/{id}', [EducationController::class, 'updateFiliere'])->name('filiere.update');
Route::delete('/filiere/{id}', [EducationController::class, 'destroyFiliere'])->name('filiere.destroy');
Route::post('/filiere', [EducationController::class, 'storeFiliere'])->name('filiere.store');



Route::get('/evaluateur_in/utilisateurs', [AuthController::class, 'userInIndex'])->name('evaluateur_in.utilisateurs');


Route::get('/evaluateur_ex/utilisateurs', [AuthController::class, 'userExIndex'])->name('evaluateur_ex.utilisateurs');

Route::post('/userEx/ajouter', [AuthController::class, 'store_userEx'])->name('store_userEx');
Route::put('/userEx/{id}/modifier', [AuthController::class, 'update_userEx'])->name('update_userEx');
Route::delete('/userEx/{id}/supprimer', [AuthController::class, 'destroy_userEx'])->name('destroy_userEx');


Route::post('/userIn/ajouter', [AuthController::class, 'store_userIn'])->name('store_userIn');
Route::put('/userIn/{id}/modifier', [AuthController::class, 'update_userIn'])->name('update_userIn');
Route::delete('/userIn/{id}/supprimer', [AuthController::class, 'destroy_userIn'])->name('destroy_userIn');

});



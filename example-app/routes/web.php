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

Route::get('/', function(){
    return view('authentification.pages-login');
})->name('login');

Route::post('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function(){
    return view('authentification.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'redirectIfnotEVL_I'])->group(function () {
    Route::get('/dash', function () {
        return view('layout.liste');
    })->name('dash');
});

Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashadmin.dashboard');
    })->name('dashadmin');
    
    Route::get('/users', function () {
        return view('dashadmin.users');
    })->name('user');
    Route::get('/champs', function () {
        return view('dashadmin.champ');
    })->name('champ');
    
    Route::get('/admins', function () {
        return view('dashadmin.admin');
    })->name('admin');
    
    Route::get('/profile', function () {
        return view('dashadmin.profile');
    })->name('profile');


    Route::post('/utilisateur/ajouter', [AuthController::class, 'ajouter_user'])->name('utilisateur.ajouter');
#Route::get('/utilisateur/{id}/modifier', [AuthController::class, 'modifierForm'])->name('utilisateur.modifierForm');
Route::put('/utilisateur/{id}/modifier', [AuthController::class, 'modifier_user'])->name('utilisateur.modifier');
Route::delete('/utilisateur/{id}/supprimer', [AuthController::class, 'supprimer_user'])->name('utilisateur.supprimer');
#Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');

Route::post('/champ/ajouter', [AuthController::class, 'ajouter_champ'])->name('champ.ajouter');
#Route::get('/utilisateur/{id}/modifier', [AuthController::class, 'modifierForm'])->name('utilisateur.modifierForm');
Route::put('/champ/{id}/modifier', [AuthController::class, 'modifier_champ'])->name('champ.modifier');
Route::delete('/champ/{id}/supprimer', [AuthController::class, 'supprimer_champ'])->name('champ.supprimer');
Route::get('/champs/{champId}/criteres', [AuthController::class, 'showCriteres'])->name('champs.criteres');

Route::post('/critere/ajouter/{champ_id}', [AuthController::class, 'ajouter_critere'])->name('critere.ajouter');
Route::put('/critere/{id}/modifier', [AuthController::class, 'modifier_critere'])->name('critere.modifier');
Route::delete('/critere/{id}/supprimer', [AuthController::class, 'supprimer_critere'])->name('critere.supprimer');

Route::post('/utilisateur/ajouter', [AuthController::class, 'store_admin'])->name('useradmin.ajouter');
Route::put('/utilisateur/{id}/modifier', [AuthController::class, 'update_admin'])->name('useradmin.modifier');
Route::delete('/utilisateur/{id}/supprimer', [AuthController::class, 'destroy_admin'])->name('useradmin.supprimer');

Route::get('/admin/utilisateurs', [AuthController::class, 'adminIndex'])->name('admin.utilisateurs');
// routes/web.php*
Route::get('/institutions', [EducationController::class, 'indexInstitutions'])->name('institutions.index');
Route::post('/institutions', [EducationController::class, 'storeInstitution'])->name('institutions.store');
Route::put('/institutions/{id}', [EducationController::class, 'updateInstitution'])->name('institutions.update');
Route::delete('/institutions/{id}', [EducationController::class, 'destroyInstitution'])->name('institutions.destroy');

});

Route::middleware(['auth', 'role:evaluateur_in'])->group(function () {
    Route::get('/evaluateur_in/utilisateurs', [AuthController::class, 'userInIndex'])->name('evaluateur_in.utilisateurs');
});

Route::middleware(['auth', 'role:evaluateur_ex'])->group(function () {
    Route::get('/evaluateur_ex/utilisateurs', [AuthController::class, 'userExIndex'])->name('evaluateur_ex.utilisateurs');
});

   
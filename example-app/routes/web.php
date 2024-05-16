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


Route::middleware(['auth', 'redirectIfnotEVL_I'])->group(function () {

    Route::get('/dash',function(){
        return view('layout.liste');
    })->name('dash');
    
});

Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
    Route::get('/dashbord',function(){
        return view('dashadmin.dashboard');
    })->name('dashadmin');
    Route::get('/users',function(){
        return view('dashadmin.users');
    })->name('user');
    Route::get('/profile',function(){
        return view('dashadmin.profile');
    })->name('profile');
    Route::get('/admins',function(){
        return view('dashadmin.admin');
    })->name('admin');
   
    Route::get('/institutions', [EducationController::class, 'indexInstitutions'])->name('institutions.index');
Route::post('/institutions', [EducationController::class, 'storeInstitution'])->name('institutions.store');
Route::put('/institutions/{id}', [EducationController::class, 'updateInstitution'])->name('institutions.update');
Route::delete('/institutions/{id}', [EducationController::class, 'destroyInstitution'])->name('institutions.destroy');
});

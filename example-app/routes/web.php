<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\etdcontroller;
use App\Http\Controllers\AuthController;
use Illuminate\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;

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
    return view('authentification.login');
})->name('login');

Route::post('/', [AuthController::class, 'login'])->name('login');

Route::get('/register', function(){
    return view('authentification.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth', 'redirectIfnotEVL_I'])->group(function () {

    Route::get('/dash',function(){
        return view('layout.liste');
    })->name('dash');
    
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
    Route::get('/dashbord',function(){
        return view('dashadmin.dashboard');
    })->name('dashadmin');
});
Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
Route::get('/users',function(){
    return view('dashadmin.users');
})->name('user');
});
Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
Route::get('/admins',function(){
    return view('dashadmin.admin');
})->name('admin');
});
Route::middleware(['auth', 'redirectIfAdmin'])->group(function () {
Route::get('/profile',function(){
    return view('dashadmin.profile');
})->name('profile');
});
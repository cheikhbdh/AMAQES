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


Route::get('/dash',function(){
    return view('layout.liste');
})->name('dash');
Route::get('/dashbord',function(){
    return view('dashadmin.home');
})->name('dashadmin');
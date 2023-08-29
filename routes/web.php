<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Member\RegisterController;
use App\Http\Controllers\Member\LoginMemberController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Member\DashboardMemberController;
use GuzzleHttp\Middleware;

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

// Login Admin
Route::get('admin/login', [LoginController::class, 'index'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'authenticate'])->name('admin.login.auth');
Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

// Login & Daftar Akun Member
Route::get('/register', [RegisterController::class, 'index'])->name('member.register');
Route::post('/register', [RegisterController::class, 'store'])->name('member.register.store');

Route::get('/login', [LoginMemberController::class, 'index'])->name('member.login');
Route::post('/login', [LoginMemberController::class, 'auth'])->name('member.login.auth');


// index awal
Route::view('/', 'index')->name('home');



Route::group(['prefix' => 'admin', 'middleware' => 'admin.auth'], function () {
    Route::view('/', 'admin.dasboard')->name('admin.dasboard');

    Route::get('transaction', [TransactionController::class, 'index'])->name('admin.transaction');

    Route::group(['prefix' => 'movie'], function () {
        Route::get('/', [MovieController::class,  'index'])->name('admin.movie');
        Route::get('/create', [MovieController::class, 'create'])->name('admin.movie.create');
        Route::post('/store', [MovieController::class, 'store'])->name('admin.movie.store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('admin.movie.edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('admin.movie.update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('admin.movie.delete');
    });



});
// Member Dashboard
Route::group(['prefix' => 'member'], function () {
    Route::get('/dashboard', [DashboardMemberController::class, 'index'])->name('member.dashboard');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CycleCountAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('cycle-count/admin')->group(function () {
    Route::get('/upload', [CycleCountAdminController::class, 'upload_excel']);
    Route::POST('/upload', [CycleCountAdminController::class, 'post_upload_excel']);
    Route::POST('/delete', [CycleCountAdminController::class, 'delete']);
});
Route::get('cycle-count/aktifitas', [CycleCountAdminController::class, 'aktifitas']);
Route::get('cycle-count/jadwal', [CycleCountAdminController::class, 'jadwal']);

Route::get('/profile', function(){
    return view('user.profile');
})->name('profile');

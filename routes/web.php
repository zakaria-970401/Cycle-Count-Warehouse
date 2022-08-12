<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CycleCountAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CycleCountGudangController;
use App\Http\Controllers\CycleCountSuperAdminController;
use App\Http\Controllers\CycleCountReportController;
use App\Http\Controllers\PermissionController;

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

Route::prefix('cycle-count/gudang')->group(function () {
    Route::get('/hitung', [CycleCountGudangController::class, 'hitung']);
    Route::get('/getListBlok', [CycleCountGudangController::class, 'getListBlok']);
    Route::get('/formHitung/{blok}/{tgl_upload}', [CycleCountGudangController::class, 'formHitung']);
    Route::get('/getCycleCount/{blok}/{tgl_upload}', [CycleCountGudangController::class, 'getCycleCount']);
    Route::post('/postCycleCount', [CycleCountGudangController::class, 'postCycleCount']);
    Route::get('/revisiCycleCount', [CycleCountGudangController::class, 'revisiCycleCount']);
    Route::POST('/revisiCycleCount', [CycleCountGudangController::class, 'postrevisiCycleCount']);
});

Route::prefix('cycle-count/superadmin')->group(function () {
    Route::get('/user', [CycleCountSuperAdminController::class, 'masterUser']);
    Route::get('/deleteUser/{id}', [CycleCountSuperAdminController::class, 'deleteUser']);
    Route::POST('/post_user', [CycleCountSuperAdminController::class, 'postUser']);
    Route::get('/showUser/{id}', [CycleCountSuperAdminController::class, 'showUser']);
    Route::get('/resetPassword/{id}', [CycleCountSuperAdminController::class, 'resetPassword']);
    Route::POST('/updateUser', [CycleCountSuperAdminController::class, 'updateUser']);
    Route::get('/menu', [CycleCountSuperAdminController::class, 'aksesMenu']);
});
Route::POST('/change-password', [CycleCountSuperAdminController::class, 'change_password']);

Route::prefix('cycle-count/report')->group(function () {
    Route::get('/', [CycleCountReportController::class, 'index']);
    Route::get('/searchByTahun/{tahun}', [CycleCountReportController::class, 'searchByTahun']);
});

Route::prefix('permission/')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/add', [PermissionController::class, 'store'])->name('permission.add');
    Route::post('/add_group', [PermissionController::class, 'add_group'])->name('permission.add_group');
    Route::get('/lihat_permission/{id}', [PermissionController::class, 'lihat_permission']);
    Route::post('/add_group_permission', [PermissionController::class, 'add_group_permission'])->name('permission.add_group_permission');
    Route::get('/hapus_permission/{kategori}/{id}', [PermissionController::class, 'hapus_permission']);
    Route::get('/update_permission/{kategori}/{nama}/{id}', [PermissionController::class, 'update_permission']);
});

Route::get('cycle-count/aktifitas', [CycleCountAdminController::class, 'aktifitas']);
Route::get('cycle-count/jadwal', [CycleCountAdminController::class, 'jadwal']);
Route::get('cycle-count/generateExcel', [CycleCountAdminController::class, 'generateExcel']);
Route::get('cycle-count/cariData/{tgl_mulai}/{tgl_selesai}', [CycleCountAdminController::class, 'cariData']);
Route::get('cycle-count/showJadwal/{tanggal}', [CycleCountAdminController::class, 'showJadwal']);
// Route::get('user/profile', [UserController::class, 'index']);

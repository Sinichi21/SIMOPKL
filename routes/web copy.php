<?php

use App\Http\Controllers\AdminLandingPageController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ComplaintConctroller;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Get CSRF token for API testing
Route::get('/token', function () {
    return csrf_token();
});

Route::prefix('auth')->group(function () {
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
});

// Tambahin middleware 'isAdmin' nanti
Route::prefix('admin')->group(function () {
    Route::get('/', AdminLandingPageController::class)->name('admin.index');
    Route::get('/login', [LoginController::class, 'adminLoginIndex'])->name('admin.login');

    //Landing Page
    Route::prefix('LandingPage')->group(function () {
        Route::get('/carolusel', [LandingPageController::class, 'caroluselindex'])->name('landingpage.carolusel');
        Route::post('/carolusel/save', [LandingPageController::class, 'caroluselsave'])->name('landingpage.carouselsave');
        Route::post('/carolusel/upload', [LandingPageController::class, 'caroluselupload'])->name('landingpage.carouselupload');

        Route::get('/sosialmedia', [LandingPageController::class, 'sosialmediaindex'])->name('landingpage.sosialmedia');
        Route::post('/sosialmedia/save', [LandingPageController::class, 'sosialmediasave'])->name('landingpage.sosialmediasave');
        Route::post('/sosialmedia/cancel', [LandingPageController::class, 'sosialmediacancel'])->name('landingpage.sosialmediacancel');

        Route::get('/iconmenu', [LandingPageController::class, 'iconmenuindex'])->name('landingpage.iconmenu');
        Route::post('/iconmenu/save', [LandingPageController::class, 'iconmenusave'])->name('landingpage.iconmenu.save');
        Route::post('/iconmenu/upload', [LandingPageController::class, 'iconmenuupload'])->name('landingpage.iconmenu.upload');

        Route::get('/tentang', [LandingPageController::class, 'tentangindex'])->name('landingpage.tentang');
        Route::post('/tentang/update', [LandingPageController::class, 'tentangupdate'])->name('landingpage.tentangupdate');

        Route::get('/kontak', [LandingPageController::class, 'kontakindex'])->name('landingpage.kontak');
        Route::post('/kontak/save', [LandingPageController::class, 'kontaksave'])->name('landingpage.kontaksave');
        Route::post('/kontak/cancel', [LandingPageController::class, 'kontakcancel'])->name('landingpage.kontakcancel');
    });

    // User routes
    Route::prefix('User')->group(function () {
        Route::get('/list', [UserController::class, 'index'])->name('user.index');
        Route::get('/approve', [UserController::class, 'approveIndex'])->name('user.approve');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::post('/user/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
        Route::post('/user/update/status', [UserController::class, 'updateStatus'])->name('user.update.status');
        Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    });

    // Master data routes
    Route::prefix('data')->group(function () {
        Route::get('/fakultas', [MasterDataController::class, 'facultyIndex'])->name('faculty.index');
        Route::post('/fakultas', [MasterDataController::class, 'storeFaculty'])->name('faculty.create');
        Route::post('/fakultas/update', [MasterDataController::class, 'updateFaculty'])->name('faculty.update');
        Route::post('/fakultas/delete', [MasterDataController::class, 'deleteFaculty'])->name('faculty.delete');

        Route::get('/program-studi', [MasterDataController::class, 'studyProgramIndex'])->name('studyProgram.index');
        Route::post('/program-studi', [MasterDataController::class, 'storeStudyProgram'])->name('studyProgram.create');
        Route::post('/program-studi/update', [MasterDataController::class, 'updateStudyProgram'])->name('studyProgram.update');
        Route::post('/program-studi/delete', [MasterDataController::class, 'deleteStudyProgram'])->name('studyProgram.delete');

        Route::get('/jenis-pengaduan', [MasterDataController::class, 'complaintTypeIndex'])->name('complaintType.index');
        Route::post('/jenis-pengaduan', [MasterDataController::class, 'storeComplaintType'])->name('complaintType.create');
        Route::post('/jenis-pengaduan/update', [MasterDataController::class, 'updateComplaintType'])->name('complaintType.update');
        Route::post('/jenis-pengaduan/delete', [MasterDataController::class, 'deleteComplaintType'])->name('complaintType.delete');
    });
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::post('/faq/delete', [FaqController::class, 'delete'])->name('faq.delete');
    Route::post('/faq/update', [FaqController::class, 'update'])->name('faq.update');
    Route::post('/faq/update/status', [FaqController::class, 'updateStatus'])->name('faq.update.status');
    Route::post('/faq/upload', [FaqController::class, 'upload'])->name('faq.upload');
    Route::post('/faq/upload/delete', [FaqController::class, 'deleteUploaded'])->name('faq.upload.delete');
    Route::get('/faq/{faq}', [FaqController::class, 'show'])->name('faq.show');
    Route::get('/faq/edit/{faq}', [FaqController::class, 'edit'])->name('faq.edit');

    Route::get('/complaint', [ComplaintConctroller::class, 'index'])->name('admin.complaint.index');
    Route::get('/complaint/report-pdf', [ComplaintConctroller::class, 'pdf'])->name('report.pdf');
    Route::get('/complaint/report-excel', [ComplaintConctroller::class, 'excel'])->name('report.excel');
    Route::get('/complaint/{complaint}', [ComplaintConctroller::class, 'show'])->name('admin.complaint.show');
});

Route::get('/complaint', [ComplaintConctroller::class, 'index'])->name('complaint.index');
Route::post('/complaint', [ComplaintConctroller::class, 'store'])->name('complaint.store');
Route::get('/complaint/create', [ComplaintConctroller::class, 'create'])->name('complaint.create');
Route::post('/complaint/delete', [ComplaintConctroller::class, 'delete'])->name('complaint.delete');
Route::get('/complaint/{complaint}', [ComplaintConctroller::class, 'show'])->name('complaint.show');

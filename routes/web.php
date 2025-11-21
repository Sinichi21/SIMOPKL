<?php

use App\Http\Controllers\AdminLandingPageController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ComplaintConctroller;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserRestoreController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\AdminPaymentSettingsController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;

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

Route::prefix('/')->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('index');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    // Auth service
    Route::prefix('auth')->group(function () {
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('/logout', LogoutController::class)->name('logout');
    });
    Route::get('/faq', [FaqController::class, 'landingPageIndex'])->name('landing-page.faq.index');
    Route::get('/faq/{faq}', [FaqController::class, 'landingPageShow'])->name('landing-page.faq.show');

    Route::get('/guest/awardee', [UserController::class, 'landingPageIndex'])->name('landing-page.awardee.index');
    Route::get('/guest/awardee/{user}', [UserController::class, 'landingPageShow'])->name('landing-page.awardee.show');

    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'handleOpenNotification'])->middleware('auth')->name('notifications.read');

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

    Route::get('/calendar-event', [CalendarController::class, 'index'])->name('landing-page.calendar.index');
    // Route::get('/calendar/search', [CalendarController::class, 'kalenderSearch'])->name('landingpage.calendar.search');
});

//Mail test
// Route::get('test-email', function () {
//     \Illuminate\Support\Facades\Mail::raw('Test email body', function ($message) {
//         $message->to('conan@student.ui.ac.id')
//                 ->subject('Test Email');
//     });
//     return 'Email sent!';
// });
// Route::get('/test-email', function () {
//     $url = 'http://example.com/reset-password-link';
//     \Mail::to('test@example.com')->send(new \App\Mail\YourResetPasswordMail($url));
//     return 'Email sent!';
// });

//Reset kata sandi
require __DIR__ . '/password_reset.php';

// Get CSRF token for API testing
Route::get('/token', function () {
    return csrf_token();
});

// Tambahin middleware 'isAdmin' nanti
Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'isAdmin', 'isActivityLog'])->group(function () {
        Route::get('/', AdminLandingPageController::class)->name('admin.index');

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

            Route::get('/berita', [NewsController::class, 'indexAdmin'])->name('news.berita');
            Route::prefix('news')->group(function () {
                Route::get('/create', [NewsController::class, 'create'])->name('news.create');
                Route::post('/store', [NewsController::class, 'store'])->name('news.store');
                Route::post('/upload', [NewsController::class, 'upload'])->name('news.upload');
                Route::post('/upload/delete', [NewsController::class, 'deleteUploaded'])->name('news.upload.delete');
                Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
                Route::post('/{id}/update', [NewsController::class, 'update'])->name('news.update');
                Route::delete('/{id}/delete', [NewsController::class, 'delete'])->name('news.delete');
                Route::get('/{id}', [NewsController::class, 'showAdmin'])->name('news.detail');
            });

            Route::get('/kalender', [CalendarController::class, 'indexAdmin'])->name('index.kalender');
            Route::prefix('kalender')->group(function () {
                Route::get('/create', [CalendarController::class, 'create'])->name('calendar.create');
                Route::post('/store', [CalendarController::class, 'store'])->name('calendar.store');
                Route::get('/{id}/edit', [CalendarController::class, 'edit'])->name('calendar.edit');
                Route::post('/{id}/update', [CalendarController::class, 'update'])->name('calendar.update');
                Route::delete('/{id}/delete', [CalendarController::class, 'delete'])->name('calendar.delete');
            });
        });

        // User routes
        Route::prefix('User')->group(function () {
            Route::get('/list', [UserController::class, 'index'])->name('user.index');
            Route::get('/approve', [UserController::class, 'approveIndex'])->name('user.approve');
            Route::post('/approve/update/status', [UserController::class, 'approve'])->name('user.approve.status');
            Route::post('/user/reject', [UserController::class, 'reject'])->name('user.reject');
            Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/user', [UserController::class, 'store'])->name('user.store');
            Route::post('/user/delete', [UserController::class, 'delete'])->name('user.delete');
            Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
            Route::post('/user/update/status', [UserController::class, 'updateStatus'])->name('user.update.status');
            Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
            Route::get('/user/{user}/approve', [UserController::class, 'showApprove'])->name('user.show_approve');
            Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
            Route::get('users/export', [UserController::class, 'exportExcel'])->name('user.export');
            // Route::post('/user/reset-password/{user}', [UserController::class, 'resetPassword'])->name('user.passwordReset');

            Route::get('/restore-all-users/{id}', [UserRestoreController::class, 'restore'])->name('users.restore.all');
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

            Route::get('/partner', [MasterDataController::class, 'partnerIndex'])->name('partner.index');
            Route::post('/partner', [MasterDataController::class, 'storePartner'])->name('partner.create');
            Route::post('/partner/update', [MasterDataController::class, 'updatePartner'])->name('partner.update');
            Route::get('/partner/{mitra}', [MasterDataController::class, 'show'])->name('partner.show');
            Route::get('/partner/edit/{mitra}', [MasterDataController::class, 'editPartner'])->name('partner.edit');
            Route::post('/partner/delete', [MasterDataController::class, 'deletePartner'])->name('partner.delete');
            Route::post('/partner/update/status', [MasterDataController::class, 'updatePartnerStatus'])->name('partner.update.status');
            Route::get('partner/export', [MasterDataController::class, 'exportExcel'])->name('partner.export');

            Route::get('/periode', [MasterDataController::class, 'PeriodIndex'])->name('Period.index');
            Route::post('/periode', [MasterDataController::class, 'storePeriod'])->name('Period.create');
            Route::post('/periode/update', [MasterDataController::class, 'updatePeriod'])->name('Period.update');
            Route::post('/periode/delete', [MasterDataController::class, 'deletePeriod'])->name('Period.delete');
        });

        Route::prefix('logs')->group(function () {
            Route::get('/logs', [LogActivityController::class, 'index'])->name('admin.logs');
            Route::post('/logs/delete', [LogActivityController::class, 'delete'])->name('logs.delete');
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

        Route::get('/complaint', [ComplaintConctroller::class, 'adminIndex'])->name('admin.complaint.index');
        Route::get('/complaint/report-pdf', [ComplaintConctroller::class, 'pdf'])->name('report.pdf');
        Route::get('/complaint/report-excel', [ComplaintConctroller::class, 'excel'])->name('report.excel');
        Route::get('/complaint/{complaint}', [ComplaintConctroller::class, 'adminShow'])->name('admin.complaint.show');

        Route::get('/thread/{thread}', [ThreadController::class, 'adminShow'])->name('admin.thread.show');

        Route::prefix('document')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('doc.index');
            Route::get('/create', [DocumentController::class, 'create'])->name('doc.create');
            Route::post('/store', [DocumentController::class, 'store'])->name('doc.store');
            Route::get('/{id}/edit', [DocumentController::class, 'edit'])->name('doc.edit');
            Route::post('/{id}/update', [DocumentController::class, 'update'])->name('doc.update');
            Route::post('/delete', [DocumentController::class, 'delete'])->name('doc.delete');
            Route::get('/{id}', [DocumentController::class, 'show'])->name('doc.show');
        });

        Route::prefix('fee')->group(function () {
            Route::get('/', [FeeController::class, 'index'])->name('fee.index');
            Route::get('/create', [FeeController::class, 'create'])->name('fee.create');
            Route::post('/store', [FeeController::class, 'store'])->name('fee.store');
            Route::get('/{id}/edit', [FeeController::class, 'edit'])->name('fee.edit');
            Route::post('/{id}/update', [FeeController::class, 'update'])->name('fee.update');
            Route::post('/delete', [FeeController::class, 'delete'])->name('fee.delete');
            Route::get('/paid-awardees', [FeeController::class, 'paidAwardees'])->name('paid-awardees.index');
        });

        Route::prefix('payment-settings')->group(function () {
            Route::get('/', [AdminPaymentSettingsController::class, 'index'])->name('payment-settings.index');
            Route::get('/create', [AdminPaymentSettingsController::class, 'create'])->name('payment-settings.create');
            Route::post('/store', [AdminPaymentSettingsController::class, 'store'])->name('payment-settings.store');
            Route::get('/{id}/edit', [AdminPaymentSettingsController::class, 'edit'])->name('payment-settings.edit');
            Route::post('/{id}/update', [AdminPaymentSettingsController::class, 'update'])->name('payment-settings.update');
            Route::post('/delete', [AdminPaymentSettingsController::class, 'delete'])->name('payment-settings.delete');
        });

        Route::prefix('payments')->group(function () {
            Route::get('/confirm', [AdminPaymentController::class, 'confirm'])->name('confirm-payment.index');
            Route::get('/show/{payment}', [AdminPaymentController::class, 'show'])->name('confirm-payment.show');
            Route::post('/approve/{payment}', [AdminPaymentController::class, 'approve'])->name('confirm-payment.approve');
            Route::post('/reject/{payment}', [AdminPaymentController::class, 'reject'])->name('confirm-payment.reject');
        });
    });
});

Route::middleware('auth')->prefix('/awardee')->group(function () {
    Route::get('/complaint', [ComplaintConctroller::class, 'awardeeIndex'])->name('complaint.index');
    Route::post('/complaint', [ComplaintConctroller::class, 'store'])->name('complaint.store');
    Route::get('/complaint/create', [ComplaintConctroller::class, 'create'])->name('complaint.create');
    Route::post('/complaint/delete', [ComplaintConctroller::class, 'delete'])->name('complaint.delete');
    Route::get('/complaint/{complaint}', [ComplaintConctroller::class, 'show'])->name('complaint.show');

    Route::get('/document', [DocumentController::class, 'awardeeIndex'])->name('document.index');
    Route::get('/document/{id}', [DocumentController::class, 'awardeeShow'])->name('document.show');

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/pay/{fee}', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::post('/store/{fee}', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{id}/detail', [PaymentController::class, 'show'])->name('payments.detail');
        Route::get('/detail/{transaction}', [TransactionDetailController::class, 'show'])->name('transaction.detail');
    });

    Route::prefix('payments/confirm')->group(function () {
        Route::get('/{payment}', [TransactionDetailController::class, 'confirm'])->name('payments.confirm');
        Route::post('/store/{payment}', [TransactionDetailController::class, 'store'])->name('payments.confirm.store');
    });

    Route::post('/thread', [ThreadController::class, 'storeChat'])->name('thread.chat.store');
    Route::get('/thread/{thread}', [ThreadController::class, 'awardeeShow'])->name('thread.show');

    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('awardee.profile.update');
    Route::post('/profile/reset-password', [UserController::class, 'resetPasswordStore'])->name('awardee.resetPassword.store');
    Route::get('/profile/reset-password/{user}', [UserController::class, 'resetPassword'])->name('awardee.resetPassword');
    Route::get('/profile/{user}', [UserController::class, 'profile'])->name('awardee.profile');
});

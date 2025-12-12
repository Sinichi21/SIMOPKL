<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiPklController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED ROUTES (hanya bisa akses pakai token JWT)


Route::middleware('auth:api')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']); // current user
    Route::post('/profile', [ApiUserController::class, 'updateProfile']);

    Route::prefix('documents')->group(function () {
        Route::get('/', [ApiPklController::class, 'getDocument']);
        Route::get('/types', [ApiPklController::class, 'documentTypes']);
        Route::post('/store', [ApiPklController::class, 'docomentStore']);
    });

    Route::prefix('registation')->group(function () {
        Route::get('/', [ApiPklController::class, 'registrationIndex']);
        Route::get('/form', [ApiPklController::class, 'registrationForm']);
        Route::post('/document', [ApiPklController::class, 'RegistrationDocumentStore']);
        Route::post('/form', [ApiPklController::class, 'registrationFormStore']);
    });
});

Route::prefix('admin')->group(function () {
    Route::middleware('auth:api', 'isAdmin')->group(function (){
        Route::prefix('documents')->group(function () {
            Route::get('/', [ApiPklController::class, 'documentIndex']);
            Route::get('/{document}', [ApiPklController::class, 'documentShow']);
            Route::post('/{document}/approve', [ApiPklController::class, 'documentApprove']);
            Route::post('/{document}/reject', [ApiPklController::class, 'documentReject']);
        });
    });
});
<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\LoginController as AdminLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| Protected by Sanctum Middleware
*/
Route::middleware('auth:sanctum')->group(function (): void {
    // Admin routes
    Route::prefix('admin')->group(function (): void {
        Route::post('create', [AdminController::class, 'createAdmin']);
    });

});

/**
 * ----------------------------------------
 * Guest routes
 * ----------------------------------------
 */

/** Public Admin routes */
Route::prefix('admin')->group(function (): void {
    Route::post('login', [AdminLoginController::class, 'login']);
});



<?php

use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...

    // public routes
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register.api');

});

Route::middleware('auth:api')->group(function () {
    // our routes to be protected will go in here

    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//test routes go here

Route::middleware(['auth:api'])->group(function () {
    Route::get('/test', [\App\Http\Controllers\TestController::class, 'index'])->name('test');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/generate_paytring_payment_req', [RouteController::class, 'generate_paytring_payment_req']);
// Route::post('/callback_at_payment', [RouteController::class, 'callback_at_payment']);
Route::post('/callback_at_payment/{orderid}', [RouteController::class, 'callback_at_payment']);
Route::post('/create_bigcommerce_order', [RouteController::class, 'create_bigcommerce_order']);

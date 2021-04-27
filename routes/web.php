<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Models\Data\Cart;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

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

Route::get('/email', function () {
    $cart = Cart::with('goods')
            ->where('user_id', 1)
            ->get();
    return view('emails.mail', ['cart' => $cart, 'name' => 'Carlos']);
});

Route::get('/emailverif', function () {
    return view("emails.verifmail", ['code' => 11827382]);
});

Route::post('login', [AuthController::class, 'login']);

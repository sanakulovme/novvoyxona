<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BakerController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TabletController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;

// ---- services ----
use App\Http\Controllers\TabletServiceController;
use App\Http\Controllers\CourierServiceController;

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

Route::group([

    // 'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('tablet-login', [AuthController::class, 'tabletLogin']);
    Route::post('courier-login', [AuthController::class, 'courierLogin']);
    Route::post('session', [AuthController::class, 'me']);
    Route::post('tablet-session', [TabletServiceController::class, 'me']);
    Route::post('courier-session', [CourierServiceController::class, 'me']);
});

Route::group([

    // 'middleware' => 'api',
    'prefix' => 'dashboard'

], function ($router) {
    Route::get('analytics', [DashboardController::class, 'analytics']);
});

Route::resource('product', ProductController::class);
Route::resource('branch', BranchController::class);
Route::resource('baker', BakerController::class);
Route::resource('courier', CourierController::class);
Route::resource('order', OrderController::class);
Route::resource('tablet', TabletController::class);
Route::resource('employee', EmployeeController::class);

Route::get('branch/{id}/products', [BranchController::class, 'products']);
Route::get('courier/{id}/location', [CourierController::class, 'location']);

Route::put('customer/debd', [CustomerController::class, 'debd']);
Route::get('customer', [CustomerController::class, 'get']);
Route::get('customers', [CustomerController::class, 'all']);
Route::get('tozalangan-fayl', [DashboardController::class, 'clear']);


Route::group([
    'prefix' => 'tablet-service'
], function ($router) {
    Route::get('analytics', [TabletServiceController::class, 'analytics']);
    Route::get('products', [TabletServiceController::class, 'products']);
    Route::get('orders', [TabletServiceController::class, 'orders']);
    Route::get('sales', [TabletServiceController::class, 'sales']);
    Route::get('production-fetch', [TabletServiceController::class, 'productionFetch']);
    Route::get('employees', [TabletServiceController::class, 'employees']);

    // --- POST ---
    Route::post('employee/{id}/change-status', [TabletServiceController::class, 'changeEmployeeStatus']);
    Route::post('production-product', [TabletServiceController::class, 'productionProduct']);
    Route::post('create-order', [TabletServiceController::class, 'createOrder']);
    Route::post('create-sales', [TabletServiceController::class, 'createSale']);
    Route::post('acceptance', [TabletServiceController::class, 'acceptance']);
});

Route::group([
    'prefix' => 'courier-service'
], function ($router) {
    Route::get('me', [CourierServiceController::class, 'me']);
    Route::get('orders', [CourierServiceController::class, 'orders']);
    Route::get('history', [CourierServiceController::class, 'history']);
    Route::get('change-status', [CourierServiceController::class, 'changeStatus']);
    Route::get('active-order', [CourierServiceController::class, 'activeOrder']);

    // --- POST ---
    Route::post('change-location', [CourierServiceController::class, 'changeLocation']);
    Route::post('acceptance', [CourierServiceController::class, 'acceptance']);
    Route::post('success-order', [CourierServiceController::class, 'successOrder']);
    Route::post('cencel-order', [CourierServiceController::class, 'cencelOrder']);
});
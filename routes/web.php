<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Role;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MangoPresentationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UnitOfMeasurementController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ReportController;

$root = Role::ROOT;
$boss = Role::BOSS;
$admin = Role::ADMIN;
$subadmin = Role::SUBADMIN;

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware(['auth:sanctum',"accessroles:{$root},{$boss},{$admin}"])->group(function () {
    Route::resource('/users', UserController::class)->only('index','store','destroy');
    Route::patch('/users/restore/{user}', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('/unit_of_measurement', UnitOfMeasurementController::class)->only('index','store','update');
    Route::resource('/mango_presentations', MangoPresentationController::class)->only('index','store','update','destroy');
    Route::resource('/customers', CustomerController::class)->only('index','store','update','destroy');
    Route::patch('/customers/restore/{customer}', [CustomerController::class, 'restore'])->name('customers.restore');
});

Route::middleware(['auth:sanctum',"accessroles:{$root},{$boss}"])->group(function () {
    Route::resource('/transfers', TransferController::class)->only('index','store','show','destroy');
    Route::get('/reports',[ReportController::class, 'index'])->name('reports.index');
});
Route::middleware(['auth:sanctum',"accessroles:{$root},{$boss},{$admin},{$subadmin}"])->group(function () {
    Route::get('/get/mango_presentations', [MangoPresentationController::class, 'getAllRecords'])->name('mango_presentations.all');
    Route::resource('/expenses', ExpenseController::class)->only('index','store','destroy');
    Route::get('/get/customers', [CustomerController::class, 'getAllRecords'])->name('customers.all');
    Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');
    Route::post('/payments/{sale}', [PaymentController::class, 'store'])->name('payment.store');
    Route::patch('/sales/change_status/shipping/{sale}', [SaleController::class, 'shipping'])->name('sale.update.ship');
    Route::resource('/sales', SaleController::class)->only('index','store','show');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ManagerController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/services', [CustomerController::class, 'services'])->name('services');
    Route::get('/services/{service}/booking', [CustomerController::class, 'bookingForm'])->name('booking.form');
    Route::post('/services/{service}/booking', [CustomerController::class, 'storeBooking'])->name('booking.store');
    Route::get('/payment/{booking}', [CustomerController::class, 'payment'])->name('payment');
    Route::post('/payment/{booking}/confirm', [CustomerController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/receipt/{booking}', [CustomerController::class, 'receipt'])->name('receipt');
    Route::get('/bookings', [CustomerController::class, 'myBookings'])->name('bookings');
    Route::get('/rewards', [CustomerController::class, 'rewards'])->name('rewards');
    Route::post('/rewards/redeem', [CustomerController::class, 'redeemVoucher'])->name('rewards.redeem');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
});

// Kasir Routes
Route::middleware(['auth', 'kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
    
    // Services Management
    Route::get('/services', [KasirController::class, 'listServices'])->name('services');
    Route::get('/services/create', [KasirController::class, 'createServiceForm'])->name('services.create');
    Route::post('/services', [KasirController::class, 'storeService'])->name('services.store');
    Route::get('/services/{service}/edit', [KasirController::class, 'editServiceForm'])->name('services.edit');
    Route::put('/services/{service}', [KasirController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [KasirController::class, 'deleteService'])->name('services.delete');
    
    // Bookings Management
    Route::get('/bookings', [KasirController::class, 'listBookings'])->name('bookings');
    Route::get('/bookings/create', [KasirController::class, 'createBookingForm'])->name('bookings.create');
    Route::post('/bookings', [KasirController::class, 'storeBooking'])->name('bookings.store');
    Route::get('/bookings/{booking}/detail', [KasirController::class, 'bookingDetail'])->name('bookings.detail');
    Route::put('/bookings/{booking}', [KasirController::class, 'updateBookingStatus'])->name('bookings.update');
    Route::get('/bookings/{booking}/print', [KasirController::class, 'printReceipt'])->name('bookings.print');
    Route::delete('/bookings/{booking}', [KasirController::class, 'deleteBooking'])->name('bookings.delete');
    
    // Services Management
    Route::get('/services', [KasirController::class, 'listServices'])->name('services');
    Route::get('/services/create', [KasirController::class, 'createServiceForm'])->name('services.create');
    Route::post('/services', [KasirController::class, 'storeService'])->name('services.store');
    Route::get('/services/{service}/edit', [KasirController::class, 'editServiceForm'])->name('services.edit');
    Route::put('/services/{service}', [KasirController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [KasirController::class, 'deleteService'])->name('services.delete');
    
    // Users/Customers Management
    Route::get('/users', [KasirController::class, 'listUsers'])->name('users');
    Route::get('/users/create', [KasirController::class, 'createUserForm'])->name('users.create');
    Route::post('/users', [KasirController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/detail', [KasirController::class, 'userDetails'])->name('users.detail');
    Route::put('/users/{user}', [KasirController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [KasirController::class, 'deleteUser'])->name('users.delete');
    
    // Customers alias (backward compatibility)
    Route::get('/customers', [KasirController::class, 'listUsers'])->name('customers');
    Route::get('/customers/create', [KasirController::class, 'createUserForm'])->name('customers.create');
    Route::post('/customers', [KasirController::class, 'storeUser'])->name('customers.store');
    Route::get('/customers/{user}/detail', [KasirController::class, 'userDetails'])->name('customers.detail');
});

// Manager Routes
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    
    // Sales Reports
    Route::get('/sales-report', [ManagerController::class, 'salesReport'])->name('sales-report');
    Route::get('/reports/sales', [ManagerController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/customers', [ManagerController::class, 'customerReport'])->name('reports.customers');
    
    // Kasir Management
    Route::get('/kasir', [ManagerController::class, 'listKasir'])->name('kasir');
    Route::get('/kasir/create', [ManagerController::class, 'createKasirForm'])->name('kasir.create');
    Route::post('/kasir', [ManagerController::class, 'storeKasir'])->name('kasir.store');
    Route::delete('/kasir/{kasir}', [ManagerController::class, 'deleteKasir'])->name('kasir.delete');
    
    // Admin Management (backward compatibility)
    Route::get('/admins', [ManagerController::class, 'listKasir'])->name('admins');
    Route::get('/admins/create', [ManagerController::class, 'createKasirForm'])->name('admins.create');
    Route::post('/admins', [ManagerController::class, 'storeKasir'])->name('admins.store');
    Route::delete('/admins/{kasir}', [ManagerController::class, 'deleteKasir'])->name('admins.delete');
    
    // View Bookings
    Route::get('/bookings', [ManagerController::class, 'allBookings'])->name('bookings');
});

<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\OrganizerEventController;
use App\Http\Controllers\Organizer\ReportController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\ReportController as WebReportController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\TicketVerificationController;
use Illuminate\Support\Facades\Route;

// Public Buyer Views
Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/reserve', [CheckoutController::class, 'reserve'])->name('events.reserve');

// Checkout Flow
Route::get('/checkout/{reservation}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{reservation}/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/status/{order:order_number}', [CheckoutController::class, 'status'])
    ->name('checkout.status');
Route::get('/orders/{order:order_number}', [CheckoutController::class, 'success'])->name('orders.success');

// Attendee Ticket Wallet
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

// Entry Staff Scanner App
Route::middleware(['auth','organizer'])->group(function () {
    Route::get('/scanner', [TicketVerificationController::class, 'index'])->name('scanner.index');
    Route::post('/scanner/verify', [TicketVerificationController::class, 'verify'])->name('scanner.verify');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Organizer profile onboarding
Route::middleware(['auth','organizer'])->group(function () {
    Route::get('/organizer/create', [OrganizerController::class, 'create'])->name('organizer.create');
    Route::post('/organizer', [OrganizerController::class, 'store'])->name('organizer.store');
});

// Organizer Backstage
Route::middleware(['auth','organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Event Routes
    Route::get('/events', [OrganizerEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
    Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [OrganizerEventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [OrganizerEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy'])->name('events.destroy');

    // Reports Route
    Route::get('/reports', [WebReportController::class, 'index'])->name('reports.index');
});
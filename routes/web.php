<?php

use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Marketer\MarketerDashboardController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventMarketerController;
use App\Http\Controllers\Organizer\MarketerController;
use App\Http\Controllers\Organizer\OrganizerEventController;
use App\Http\Controllers\Organizer\ReportController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\ReportController as WebReportController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\TicketVerificationController;
use App\Http\Controllers\MarketerInvitationController as PublicMarketerInvitationController;
use App\Http\Controllers\Organizer\MarketerInvitationController as OrganizerMarketerInvitationController;
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
    Route::get('/tickets/{ticket}/pdf', [TicketController::class, 'downloadPdf'])->name('tickets.pdf');
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
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:10,1');

    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:5,1');

    // OTP Verification
    Route::get('/verify-otp', [EmailOtpController::class, 'create'])
        ->name('otp.create');

    Route::post('/verify-otp', [EmailOtpController::class, 'store'])
        ->name('otp.verify')
        ->middleware('throttle:10,1');

    Route::post('/verify-otp/resend', [EmailOtpController::class, 'resend'])
        ->name('otp.resend')
        ->middleware('throttle:5,1');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email')
        ->middleware('throttle:5,1');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
        ->name('password.update')
        ->middleware('throttle:5,1');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
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


Route::middleware(['auth'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        Route::resource(
            'marketers',
            MarketerController::class
        );

        Route::get(
            'events/{event}/marketers',
            [EventMarketerController::class, 'index']
        )->name('events.marketers');

        Route::post(    
            'events/{event}/marketers',
            [EventMarketerController::class, 'store']
        )->name('events.marketers.store');

        Route::delete(
            'events/{event}/marketers/{marketer}',
            [EventMarketerController::class, 'destroy']
        )->name('events.marketers.destroy');

        // Route::get(
        //     'events/{event}/marketing',
        //     [EventMarketingController::class, 'index']
        // )->name('events.marketing');
    });

    if (app()->environment('local')) {
    Route::prefix('test-errors')->group(function () {
        Route::get('/403', fn () => abort(403));
        Route::get('/404', fn () => abort(404));
        Route::get('/419', fn () => abort(419));
        Route::get('/500', fn () => abort(500));
        Route::get('/503', fn () => abort(503));
    });
}

Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    // ... existing marketer routes
    Route::post('marketers/{marketer}/payout', [App\Http\Controllers\Organizer\MarketerPayoutController::class, 'processPayout'])
        ->name('marketers.payout');
});


// Opening the shared link requires authentication. Laravel's auth middleware
// stores the intended URL and returns the user here after login.
Route::middleware(['auth'])->group(function () {
    Route::get('/marketer-invitations/{token}', [PublicMarketerInvitationController::class, 'show'])
        ->name('marketer-invitations.show');

    Route::post('/marketer-invitations/{token}/apply', [PublicMarketerInvitationController::class, 'apply'])
        ->name('marketer-invitations.apply');

    Route::prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/events/{event}/marketer-invitations/create', [OrganizerMarketerInvitationController::class, 'create'])
            ->name('events.marketer-invitations.create');

        Route::get('/events/{event}/marketer-invitations', [
            OrganizerMarketerInvitationController::class,
            'index'
        ])->name('events.marketer-invitations.index');

        Route::post('/events/{event}/marketer-invitations', [OrganizerMarketerInvitationController::class, 'store'])
            ->name('events.marketer-invitations.store');

        Route::get('/marketer-invitations/{invitation}', [OrganizerMarketerInvitationController::class, 'show'])
            ->name('marketer-invitations.show');

        Route::get('/marketer-invitations/{invitation}/applications', [OrganizerMarketerInvitationController::class, 'applications'])
            ->name('marketer-invitations.applications');

        Route::post('/marketer-invitations/{invitation}/applications/{application}/approve', [OrganizerMarketerInvitationController::class, 'approve'])
            ->name('marketer-invitations.applications.approve');

        Route::post('/marketer-invitations/{invitation}/applications/{application}/reject', [OrganizerMarketerInvitationController::class, 'reject'])
            ->name('marketer-invitations.applications.reject');

        Route::post('/marketer-invitations/{invitation}/revoke', [OrganizerMarketerInvitationController::class, 'revoke'])
            ->name('marketer-invitations.revoke');
    });
});


Route::middleware(['auth'])
    ->prefix('marketer')
    ->name('marketer.')
    ->group(function () {

        Route::get('/dashboard', [MarketerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/events', [MarketerDashboardController::class, 'events'])
            ->name('events.index');

        Route::get('/events/{event}', [MarketerDashboardController::class, 'showEvent'])
            ->name('events.show');

        Route::get('/sales', [MarketerDashboardController::class, 'sales'])
            ->name('sales.index');

        Route::get('/commissions', [MarketerDashboardController::class, 'commissions'])
            ->name('commissions.index');

        // Route::get('/payouts', [MarketerPayoutController::class, 'index'])
        //     ->name('payouts.index');

        // Route::get('/profile', [MarketerProfileController::class, 'edit'])
        //     ->name('profile.edit');
    });
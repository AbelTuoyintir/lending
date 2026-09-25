<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\InterestCycleController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanProductController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | Global Search
    |--------------------------------------------------------------------------
    */
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'customers',
        CustomerController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Loan Products
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'loan-products',
        LoanProductController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Loans
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'loans',
        LoanController::class
    );

    Route::get(
        '/loans/{loan}/statement',
        [StatementController::class, 'show']
    )->name('loans.statement');

    Route::post(
        '/loans/{loan}/approve',
        [LoanController::class, 'approve']
    )->name('loans.approve');

    Route::post(
        '/loans/{loan}/disburse',
        [LoanController::class, 'disburse']
    )->name('loans.disburse');

    Route::post(
        '/loans/{loan}/cancel',
        [LoanController::class, 'cancel']
    )->name('loans.cancel');

    Route::post(
        '/loans/{loan}/default',
        [LoanController::class, 'markDefaulted']
    )->name('loans.default');

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/payments',
        [PaymentController::class, 'index']
    )->name('payments.index');

    Route::get(
        '/payments/create',
        [PaymentController::class, 'manualCreate']
    )->name('payments.manual-create');

    Route::post(
        '/payments',
        [PaymentController::class, 'manualStore']
    )->name('payments.manual-store');

    Route::get(
        '/payments/{payment}',
        [PaymentController::class, 'show']
    )->name('payments.show');

    Route::get(
        '/loans/{loan}/payments/create',
        [PaymentController::class, 'create']
    )->name('payments.create');

    Route::post(
        '/loans/{loan}/payments',
        [PaymentController::class, 'store']
    )->name('payments.store');

    Route::post(
        '/payments/{payment}/reverse',
        [PaymentController::class, 'reverse']
    )->name('payments.reverse');

    /*
    |--------------------------------------------------------------------------
    | Monthly Compound Interest
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/loans/{loan}/interest/preview',
        [InterestCycleController::class, 'preview']
    )->name('loans.interest.preview');

    Route::post(
        '/loans/{loan}/interest/apply',
        [InterestCycleController::class, 'apply']
    )->name('loans.interest.apply');

    /*
    |--------------------------------------------------------------------------
    | Collections
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/collections',
        [CollectionsController::class, 'index']
    )->name('collections.index');

    Route::get(
        '/collections/{loan}',
        [CollectionsController::class, 'show']
    )->name('collections.show');

    /*
    |--------------------------------------------------------------------------
    | Transactions Ledger
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/transactions',
        [TransactionController::class, 'index']
    )->name('transactions.index');

    /*
    |--------------------------------------------------------------------------
    | Administrators, Settings & Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/administrators', [AdministratorController::class, 'index'])->name('administrators.index');
    Route::get('/administrators/create', [AdministratorController::class, 'create'])->name('administrators.create');
    Route::post('/administrators', [AdministratorController::class, 'store'])->name('administrators.store');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('financial-accounts', FinancialAccountController::class)->only(['index', 'create', 'store']);

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports',
        [ReportsController::class, 'index']
    )->name('reports.index');

    Route::get(
        '/reports/loans',
        [ReportsController::class, 'loans']
    )->name('reports.loans');

    Route::get(
        '/reports/payments',
        [ReportsController::class, 'payments']
    )->name('reports.payments');

    Route::get(
        '/reports/customers',
        [ReportsController::class, 'customers']
    )->name('reports.customers');

    Route::get(
        '/reports/interest',
        [ReportsController::class, 'interest']
    )->name('reports.interest');

    Route::get(
        '/reports/outstanding',
        [ReportsController::class, 'outstanding']
    )->name('reports.outstanding');
});

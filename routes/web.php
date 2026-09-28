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
use App\Http\Controllers\Portal\PortalDashboardController;
use App\Http\Controllers\Portal\PortalFinancialHistoryController;
use App\Http\Controllers\Portal\PortalLoanController;
use App\Http\Controllers\Portal\PortalNotificationController;
use App\Http\Controllers\Portal\PortalPaymentController;
use App\Http\Controllers\Portal\PortalProfileController;
use App\Http\Controllers\Portal\PortalRegisterController;
use App\Http\Controllers\Portal\PortalSupportController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\EnsureCustomerAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isCustomer()
            ? redirect()->route('portal.dashboard')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication & Registration Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [PortalRegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [PortalRegisterController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureCustomerAccess::class])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');

    Route::get('/loans', [PortalLoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/apply', [PortalLoanController::class, 'create'])->name('loans.create');
    Route::post('/loans/apply', [PortalLoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [PortalLoanController::class, 'show'])->name('loans.show');
    Route::get('/loans/{loan}/agreement', [PortalLoanController::class, 'agreement'])->name('loans.agreement');
    Route::get('/loans/{loan}/statement', [PortalLoanController::class, 'statement'])->name('loans.statement');

    Route::get('/payments', [PortalPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/receipt', [PortalPaymentController::class, 'receipt'])->name('payments.receipt');

    Route::post('/payments/paystack/initialize', [PortalPaymentController::class, 'initializePaystack'])->name('payments.paystack.initialize');
    Route::get('/payments/paystack/callback', [PortalPaymentController::class, 'paystackCallback'])->name('payments.paystack.callback');

    Route::get('/financial-history', [PortalFinancialHistoryController::class, 'index'])->name('financial-history');

    Route::get('/notifications', [PortalNotificationController::class, 'index'])->name('notifications');

    Route::get('/support', [PortalSupportController::class, 'index'])->name('support');
    Route::post('/support/inquiry', [PortalSupportController::class, 'submitInquiry'])->name('support.inquiry');

    Route::get('/profile', [PortalProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [PortalProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [PortalProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Administrator Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureAdminAccess::class])->group(function () {

    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('loan-products', LoanProductController::class);
    Route::resource('loans', LoanController::class);

    Route::get('/loans/{loan}/statement', [StatementController::class, 'show'])->name('loans.statement');
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('/loans/{loan}/cancel', [LoanController::class, 'cancel'])->name('loans.cancel');
    Route::post('/loans/{loan}/default', [LoanController::class, 'markDefaulted'])->name('loans.default');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'manualCreate'])->name('payments.manual-create');
    Route::post('/payments', [PaymentController::class, 'manualStore'])->name('payments.manual-store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/loans/{loan}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/loans/{loan}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/{payment}/reverse', [PaymentController::class, 'reverse'])->name('payments.reverse');

    Route::get('/loans/{loan}/interest/preview', [InterestCycleController::class, 'preview'])->name('loans.interest.preview');
    Route::post('/loans/{loan}/interest/apply', [InterestCycleController::class, 'apply'])->name('loans.interest.apply');

    Route::get('/collections', [CollectionsController::class, 'index'])->name('collections.index');
    Route::get('/collections/{loan}', [CollectionsController::class, 'show'])->name('collections.show');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::get('/administrators', [AdministratorController::class, 'index'])->name('administrators.index');
    Route::get('/administrators/create', [AdministratorController::class, 'create'])->name('administrators.create');
    Route::post('/administrators', [AdministratorController::class, 'store'])->name('administrators.store');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('financial-accounts', FinancialAccountController::class)->only(['index', 'create', 'store']);

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/loans', [ReportsController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/payments', [ReportsController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/interest', [ReportsController::class, 'interest'])->name('reports.interest');
    Route::get('/reports/outstanding', [ReportsController::class, 'outstanding'])->name('reports.outstanding');
});

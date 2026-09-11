<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InterestCycleController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

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


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

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
    | Reports
    |--------------------------------------------------------------------------
    */

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
});

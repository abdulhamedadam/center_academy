<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('/payments/{id}/print-invoice', [\App\Http\Controllers\PaymentsController::class, 'printInvoice'])->name('payments.print-invoice');

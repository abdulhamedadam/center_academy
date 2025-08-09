<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransactions;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function printInvoice($id)
    {
        $payment = PaymentTransactions::with(['coursePayment.student'])->findOrFail($id);
        return view('payments.print-invoice', compact('payment'));
    }
} 
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index() {
        $transaction = Transaction::with(['package','user'])->get();
        //  
        return view('admin.transaction', ['transactions' => $transaction]);
    }
}

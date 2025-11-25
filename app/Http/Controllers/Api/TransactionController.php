<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionDetailResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::with(['customer', 'user'])->get();
        return TransactionResource::collection($transactions);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'transaction_code' => 'required|string|unique:transactions,transaction_code',
            'customer_id' => 'required|exists:customers,id_customer',
            'user_id' => 'required|exists:users,id_user',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }

        $transaction = Transaction::create([
            'transaction_code' => $request->transaction_code,
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
        ]);
        return new TransactionResource($transaction->load(['customer', 'user']));
    }

    public function show(Transaction $transaction){
        return new TransactionResource($transaction->load(['customer', 'user']));
    }

    public function showDetails(Transaction $transaction){
        $details = $transaction->details()->with(['product'])->get();
        return TransactionDetailResource::collection($details);
    }

    public function update(Request $request, Transaction $transaction){
        $validator = Validator::make($request->all(), [
            'transaction_code' => 'required|string|unique:transactions,transaction_code,'.$transaction->id_transaction.',id_transaction',
            'customer_id' => 'required|exists:customers,id_customer',
            'user_id' => 'required|exists:users,id_user',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }

        $transaction->update([
            'transaction_code' => $request->transaction_code,
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
        ]);
        return new TransactionResource($transaction->load(['customer', 'user']));
    }

    public function destroy(Transaction $transaction){
        $transaction->delete();
        return response()->json(null, 204);
    }
}

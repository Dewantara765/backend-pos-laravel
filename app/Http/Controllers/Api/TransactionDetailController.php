<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionDetailResource;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Product;


class TransactionDetailController extends Controller
{
    public function index(){
        $transactionDetails = TransactionDetail::with(['transaction', 'product'])->get();
        return TransactionDetailResource::collection($transactionDetails);
    }

    public function store(Request $request){
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id_transaction',
            'product_id' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $product = Product::find($request->product_id);

        $transactionDetail = TransactionDetail::create([
            'transaction_id' => $request->transaction_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'subtotal' => $product->price * $request->quantity,
        ]);

        $transaction = Transaction::find($request->transaction_id);
        $transaction->update([
            'total_amount' => $transaction->total_amount + $transactionDetail->subtotal
        ]);

        $product->update([
            'stock' => $product->stock - $request->quantity
        ]);

        return new TransactionDetailResource($transactionDetail->load(['transaction', 'product']));
    }

    public function show(TransactionDetail $transactionDetail){
        return new TransactionDetailResource($transactionDetail->load(['transaction', 'product']));
    }
}

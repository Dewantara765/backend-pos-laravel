<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'id_transaction';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'transaction_code',
        'date',
        'total_amount',
        'payment_method',
        'user_id',
        'customer_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id_transaction');
    }
}

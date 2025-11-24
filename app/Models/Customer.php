<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'id_customer';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 'email','phone', 'address',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

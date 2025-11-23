<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'id_category';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id_category');
    }
}

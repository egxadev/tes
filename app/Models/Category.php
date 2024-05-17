<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'nama', 'createdAt', 'updatedAt',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'produk_categories', 'categoryId', 'produkId');
    }
}

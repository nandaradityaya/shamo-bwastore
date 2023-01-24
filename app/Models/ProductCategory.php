<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    // relasi ke model Product
    public function products()
    {
        return $this->hasMany(Product::class, 'categories_id', 'id'); // params 1 adalah model yg berelasi, params 2 field yg berelasi denggan model ProductCategory atau model local, params 3 field local yg berelasi dengan model Product
    }
}

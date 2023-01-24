<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;


     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    // yg bisa di isi itu fillable
    protected $fillable = [
        'name',
        'description',
        'price',
        'categories_id',
        'tags'
    ];

    // relasi ke table ProductGallery
    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'products_id', 'id'); // params 1 adalah model yg berelasi, params 2 field yg berelasi denggan model Product atau model local yaotu field product_id, params 3 field local yg berelasi dengan model ProductGallery yaitu field id
    }

    // relasi ke table ProductCategory | pakenya belongsTo karena relasi ke table ProductCategory dan dari banyak ke satu
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'categories_id', 'id'); // params 1 adalah model yg berelasi, params 2 field yg berelasi denggan model Product atau model local, params 3 field local yg berelasi dengan model ProductCategory
    }
}

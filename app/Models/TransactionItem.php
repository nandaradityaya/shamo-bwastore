<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'users_id',
        'products_id',
        'transactions_id',
        'quantity',
    ];
    
    // relasi ke table Product | relasinya one to one
    public function product() {
        return $this->hasOne(Product::class, 'id', 'products_id'); // params 1 adalah model yg berelasi, params 2 field yg berelasi dengan model TransactionItem atau model local yaitu id, params 3 field local yg berelasi dengan model Product yaitu products_id
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'users_id',
        'address',
        'payment',
        'total_price',
        'shipping_price',
        'status',
    ];

    // relasi ke table user
    public function user() {
        return $this->belongsTo(User::class, 'users_id', 'id'); // params 1 adalah model yg berelasi, params 2 field model local yg berelasi dgn model User, params 3 field User yg berelasi dengan model local atau model Transaction
    }

    public function items() {
        return $this->hasMany(TransactionItem::class, 'transactions_id', 'id'); // params 1 adalah model yg berelasi, params 2 field model yg berelasi dgn model Transaction, params 3 field Transaction yg berelasi dengan model TransactionDetail
    }
}

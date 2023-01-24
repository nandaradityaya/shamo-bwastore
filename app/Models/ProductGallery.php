<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Model
{
    use HasFactory, SoftDeletes; // pake softdelete pastiin ada deleted_at di table

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'products_id',
        'url',
    ];

    // ini buat nampilin url gambar. buat API. jadi nanti di API nya nanti url nya langsung bisa di akses, jadi intinya ngubah alamat gambar jadi URL
    public function getUrlAttribute($url)
    {
        return config('app.url') . Storage::url($url);
    }
}

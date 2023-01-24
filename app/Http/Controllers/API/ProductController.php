<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id'); // ambil data id dari request
        $limit = $request->input('limit', 6); // ambil data limit dari request | limit 6 adalah defaultnya
        $name = $request->input('name'); // ambil data name dari request
        $description = $request->input('description'); // ambil data description dari request
        $tags = $request->input('tags'); // ambil data tags dari request
        $categories = $request->input('categories'); // ambil data categories dari request

        $price_from = $request->input('price_from'); // ambil data price_from dari request | utk filter range harga
        $price_to = $request->input('price_to'); // ambil data price_to dari request | utk filter range harga 

        if($id) { 
            $product = Product::with(['category', 'galleries'])->find($id); // ambil data dari model Product bersama dengan relasinya yaitu category dan galleries lalu cari berdasarkan id
        
            if($product) { // jika data product ada
                return ResponseFormatter::success( // ResponseFormatter adalah helper yg dibuat di folder app\Helpers\ResponseFormatter.php | ini di buat sendiri oleh kita
                    $product,
                    'Data produk berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
            }
        }

        $product = Product::with(['category', 'galleries']); // ambil data dari model Product bersama dengan relasinya yaitu category dan galleries

        if($name) { // jika ada data name
            $product->where('name', 'like', '%' . $name . '%'); // cari data name yg berisi data name yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu like artinya yg mirip, params 3 adalah data yg akan di cari
        }

        if($description) { // jika ada data description
            $product->where('description', 'like', '%' . $description . '%'); // cari data description yg berisi data description yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu like artinya yg mirip, params 3 adalah data yg akan di cari
        }

        if($tags) { // jika ada data tags
            $product->where('tags', 'like', '%' . $tags . '%'); // cari data tags yg berisi data tags yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu like artinya yg mirip, params 3 adalah data yg akan di cari
        }

        if($price_from) {
            $product->where('price', '>=', $price_from); // cari data price yg lebih besar atau sama dengan data price_from yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu >= artinya lebih besar atau sama dengan karena kita filter dari from, params 3 adalah data yg akan di cari 
        }

        if($price_to) {
            $product->where('price', '<=', $price_to); // cari data price yg lebih kecil atau sama dengan data price_to yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu <= artinya lebih kecil atau sama dengan karena kita filter dari to, params 3 adalah data yg akan di cari 
        }

        if($categories) { // jika ada data categories
            $product->where('categories', $categories); // cari data di field categories yg berisi data categories yg di inputkan 
        }

        return ResponseFormatter::success(
            $product->paginate($limit), // paginate adalah fungsi yg ada di laravel untuk membatasi data yg di tampilkan | params 1 adalah data yg akan di paginate, params 2 adalah jumlah data yg akan di tampilkan
            'Data list produk berhasil diambil'
        );
    }
}

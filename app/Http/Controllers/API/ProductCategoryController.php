<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id'); // ambil data id dari request
        $limit = $request->input('limit'); // ambil data limit dari request
        $name = $request->input('name'); // ambil data name dari request
        $show_product = $request->input('show_product'); // ambil data show_product dari request

        // filter berdasarkan id
        if($id) { 
            $category = ProductCategory::with(['products'])->find($id); // ambil data dari model ProductCategory bersama dengan relasinya yaitu products lalu cari berdasarkan id
        
            if($category) { // jika data product ada
                return ResponseFormatter::success( // ResponseFormatter adalah helper yg dibuat di folder app\Helpers\ResponseFormatter.php | ini di buat sendiri oleh kita
                    $category,
                    'Data kategori berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404
                );
            }
        }

        $category = ProductCategory::query(); // ambil data dari model ProductCategory dan query kosong

        // filter berdasarkan name
        if($name) { // jika ada data name
            $category->where('name', 'like', '%' . $name . '%'); // cari data name yg berisi data name yg di inputkan | params 1 adalah field yg akan di cari, params 2 adalah operator yaitu like artinya yg mirip, params 3 adalah data yg akan di cari
        }

        // filter berdasarkan show_product
        if($show_product) { // jika ada data show_product
            $category->with('products'); // ambil data relasi products
        }

        return ResponseFormatter::success(
            $category->paginate($limit), // paginate adalah fungsi yg ada di laravel untuk membatasi data yg di tampilkan | params 1 adalah data yg akan di paginate, params 2 adalah jumlah data yg akan di tampilkan
            'Data list kategori berhasil diambil'
        );
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');

        if($id) {
            $transaction = Transaction::with(['items.product', 'user'])->find($id); // panggil model Transaction bersama dengan relasinya dan cari berdasarkan id

            // jika datanya ada
            if($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            // jika transaksi tidak ada
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }
        

        // ambil berdasarkan semua data
        $transaction = Transaction::with(['items.product', 'user'])->where('users_id', Auth::user()->id); // panggil model Transaction bersama dengan relasinya dan cari berdasarkan users_id dan jangan lupa Auth::user()->id untuk mengambil id user yang sedang login

        // filter
        if($status) {
            $transaction->where('status', $status); // cari berdasarkan status
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit), // paginate untuk menampilkan data berdasarkan limit
            'Data list transaksi berhasil diambil'
        );
    }

    // checkout
    public function checkout(Request $request) {
        $request->validate([
            'items' => 'required|array', // validasi items harus array
            'items.*.id' => 'exists:products,id', // validasi items, itemnya harus ada di table products dan id nya, klo itemnya gada di tabel product berarti dia gabisa checkout | * itu artinya semua, jadi validasi semua item yg ada di items
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED', // validasi status. data yg harus ada di status cuma PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED
        ]);

        // simpan ke database
        $transaction = Transaction::create([
            'users_id' => Auth::user()->id, // cek siapa usernya
            'address' => $request->address, // ambil data address dari request
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status,
        ]);

        // simpan arraynya satu2 ke database, jadinya ini pake foreach. karena items itu array
        foreach($request->items as $product) {
            TransactionItem::create([
                'users_id' => Auth::user()->id, // cetak siapa usernya
                'products_id' => $product['id'], // ambil id dari product
                'transactions_id' => $transaction->id, // ambil id dari transaction yg baru dibuat diatas
                'quantity' => $product['quantity'], // ambil quantity dari product
            ]);
        }

        // kembalikan data
        return ResponseFormatter::success($transaction->load('items.product'), 'Transaksi berhasil'); // load untuk mengambil relasi, jadi kita ambil relasi items.product
    }
}

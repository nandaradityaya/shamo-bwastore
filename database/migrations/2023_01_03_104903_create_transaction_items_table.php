<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("users_id"); // ini adalah foreign key untuk relasi ke tabel users
            $table->bigInteger("products_id"); // ini adalah foreign key untuk relasi ke tabel products
            $table->bigInteger("transactions_id"); // ini adalah foreign key untuk relasi ke tabel transactions
            $table->bigInteger("quantity"); // kuantitas dari transaksi item tsb
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("users_id"); // ini adalah foreign key untuk relasi ke tabel users
            $table->text("address")->nullable();
            $table->float("total_price")->default(0); // total price klo kosong defaultnya 0
            $table->float("shipping_price")->default(0); // total price klo kosong defaultnya 0
            $table->string("status")->default("PENDING"); // defaultnya pending
            $table->string("payment")->default("MANUAL"); // defaultnya manual
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
};

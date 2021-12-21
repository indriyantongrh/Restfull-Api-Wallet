<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_keluar', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('drying_kedua_id');
            $table->string('kode_partai');
            $table->string('kode_transaksi');
            $table->string('tanggal_proses');
            $table->string('jumlah_sbw');
            $table->string('status');
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
        Schema::dropIfExists('stock_keluar');
    }
}

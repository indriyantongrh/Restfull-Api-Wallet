<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDataGradingAkhir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_data_grading_akhir', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('kode_transaksi_grading');
            $table->string('id_dry_kedua');
            $table->string('kode_transaksi');
            $table->string('kode_register');
            $table->string('kode_partai');
            $table->string('jumlah_saldo');
            $table->string('jumlah_sbw_grading');
            $table->string('jumlah_pcs');
            $table->string('id_jenis_garding');
            $table->string('name_jenis_garding');
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
        Schema::dropIfExists('transaksi_data_grading_akhir');
    }
}

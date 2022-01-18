<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDryPertamaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dry_pertama', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('adding_id');
            $table->integer('gradding_id');
            $table->integer('koreksi_id');
            $table->integer('mandor_id');
            $table->string('kode_transaksi');
            $table->integer('kode_partai');
            $table->integer('tanggal_proses');
            $table->integer('jumlah_sbw');
            $table->integer('jumlah_box');
            $table->integer('jumlah_keping');
            $table->integer('status');
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
        Schema::dropIfExists('dry_pertama');
    }
}

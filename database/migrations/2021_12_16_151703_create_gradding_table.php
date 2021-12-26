<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraddingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gradding', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('adding_id');
            $table->string('kode_partai');
            $table->string('no_register');
            $table->string('tanggal_proses');
            $table->string('jumlah_sbw');
            $table->string('jumlah_keping');
            $table->string('jumlah_box');
            $table->string('jenis_grade');
            $table->string('kode_transaksi');
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
        Schema::dropIfExists('gradding');
    }
}

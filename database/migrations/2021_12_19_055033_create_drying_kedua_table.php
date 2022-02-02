<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDryingKeduaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dry_kedua', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('gradding_id');
            $table->integer('adding_id');
            $table->integer('dry_pertama_id');
            $table->integer('koreksi_id');
            $table->integer('mandor_id');
            $table->integer('molding_id');
            $table->string('kode_transaksi');
            $table->string('kode_partai');
            $table->string('no_register');
            $table->string('tanggal_proses');
            $table->string('jumlah_sbw');
            $table->string('jumlah_box');
            $table->string('jumlah_keping');
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
        Schema::dropIfExists('drying_kedua');
    }
}

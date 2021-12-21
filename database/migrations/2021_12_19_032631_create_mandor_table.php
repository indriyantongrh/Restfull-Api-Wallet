<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMandorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mandor', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('gradding_id');
            $table->integer('adding_id');
            $table->integer('wash_id');
            $table->string('kode_transaksi');
            $table->string('kode_partai');
            $table->string('tanggal_proses');
            $table->string('tanggal_selesai');
            $table->string('jumlah_sbw');
            $table->string('nama_pekerja');
            $table->string('progres_pekerja');
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
        Schema::dropIfExists('mandor');
    }
}

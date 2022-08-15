<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streaming', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('kode_transaksi_grading');
            $table->string('temperatur_pre_heating');
            $table->string('waktu_pre_heating');
            $table->string('temperatur_tot');
            $table->string('waktu_tot');
            $table->string('jumlah_keping');
            $table->string('jumlah_pending_keping');
            $table->string('tanggal_proses');
            $table->string('keterangan');
            $table->string('status');
            $table->string('status_jual')->default("sold");
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
        Schema::dropIfExists('streaming');
    }
}

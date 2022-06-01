<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('grade_akhir_id');
            $table->string('kode_transaksi_grading');
            $table->string('jenis_kemasan');
            $table->string('box');
            $table->string('koli');
            $table->string('tanggal_packing');
            $table->string('tanggal_pengiriman');
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
        Schema::dropIfExists('packing');
    }
}

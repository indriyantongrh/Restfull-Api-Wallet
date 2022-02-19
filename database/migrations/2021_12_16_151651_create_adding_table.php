<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adding', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('no_register');
            $table->string('kode_partai');
            $table->string('legal_source');
            $table->string('jenis_sbw_kotor');
            $table->date('tanggal_panen');
            $table->date('tanggal_penerima');
            $table->text('alamat');
            $table->string('no_kendaraan');
            $table->string('jumlah_sbw_kotor');
            $table->string('jumlah_pcs');
            $table->string('warna');
            $table->string('kondisi');
            $table->string('harga_kulak');
            $table->string('jumlah_box');
            $table->string('kadar_air');
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
        Schema::dropIfExists('adding');
    }
}

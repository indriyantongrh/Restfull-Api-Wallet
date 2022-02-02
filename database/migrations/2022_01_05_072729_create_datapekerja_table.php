<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatapekerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datapekerja', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('nama_pekerja');
            $table->string('nik');
            $table->string('bagian');
            $table->string('tanggal_masuk');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('alamat');
            $table->string('status');
            $table->string('no_telp');
            $table->string('status_karyawan');
        
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
        Schema::dropIfExists('datapekerja');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class datapekerja extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datapekerja';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'nama_pekerja', 'nik', 'bagian', 'tanggal_masuk', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'status','no_telp','status_karyawan','created_at','updated_at'];

}
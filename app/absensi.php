<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class absensi extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'absensi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id','id_pekerja', 'nama_pekerja', 'nik', 'tanggal','checkin', 'checkout','isDelete','created_at','updated_at'];
}

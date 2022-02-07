<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pemanas extends Model
{
              /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pemanas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id', 'dry_kedua_id','kode_partai','no_register', 'tanggal_proses', 'suhu','waktu', 'kode_transaksi', 'status', 'created_at', 'updated_at'];

}
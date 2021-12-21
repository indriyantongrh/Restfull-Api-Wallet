<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wash extends Model
{
       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wash';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id', 'gradding_id','kode_partai', 'tanggal_proses', 'jumlah_sbw', 'kode_transaksi', 'status', 'created_at', 'updated_at'];

}

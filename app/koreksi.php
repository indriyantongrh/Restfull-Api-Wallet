<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class koreksi extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'koreksi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id', 'gradding_id','mandor_id', 'kode_transaksi', 'kode_partai', 'no_register', 'tanggal_proses', 'jumlah_sbw', 'jumlah_box', 'jumlah_keping', 'progres_koreksi','jumlah_pending','status', 'created_at', 'updated_at'];

}

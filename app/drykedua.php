<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class drykedua extends Model
{
              /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dry_kedua';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id','kode_partai','no_register', 'tanggal_proses', 'jumlah_sbw','jumlah_keping', 'jumlah_box', 'kode_transaksi','jumlah_sbw_saldo','jumlah_box_saldo','jumlah_keping_saldo', 'status', 'created_at', 'updated_at'];

}

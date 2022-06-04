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
    protected $table = 'streaming';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'kode_transaksi_grading','temperatur_pre_heating', 'waktu_pre_heating','temperatur_tot', 'waktu_tot', 'tanggal_proses', 'jumlah_keping', 'jumlah_pending_keping', 'status', 'keterangan', 'created_at', 'updated_at'];

}
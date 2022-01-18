<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mandor extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mandor';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'tanggal_proses', 'tanggal_selesai','jumlah_sbw', 'jumlah_box','jumlah_keping', 'nama_pekerja',  'progres_pekerja',  'jumlah_sbw_selesai', 'jumlah_box_selesai', 'jumlah_keping_selesai','status', 'created_at', 'updated_at'];
}

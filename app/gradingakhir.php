<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gradingakhir extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_data_grading_akhir';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'kode_transaksi_grading','user_id','id_dry_kedua', 'kode_transaksi', 'kode_register','kode_partai', 'jumlah_saldo', 'jumlah_sbw_grading', 'id_jenis_garding', 'name_jenis_garding', 'created_at', 'updated_at'];

}

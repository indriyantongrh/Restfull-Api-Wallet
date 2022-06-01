<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class packing extends Model
{
              /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packing';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */

    protected $fillable = ['id', 'user_id', 'grade_akhir_id','kode_transaksi_grading','jenis_kemasan', 'box','koli', 'tanggal_packing', 'tanggal_pengiriman', 'created_at', 'updated_at'];

}
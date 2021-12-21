<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gradding extends Model
{
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gradding';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'adding_id','kode_partai', 'tanggal_proses', 'jumlah_sbw', 'jenis_grade', 'kode_transaksi', 'status', 'created_at', 'updated_at'];

}

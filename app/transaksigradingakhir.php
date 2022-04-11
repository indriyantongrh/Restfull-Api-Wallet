<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaksigradingakhir extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_grading_akhir';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id','id_user', 'kode_transaksi_grading', 'created_at', 'updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class adding extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adding';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'no_register','legal_source', 'kode_partai', 'jenis_sbw_kotor', 'tanggal_panen', 'tanggal_penerima', 'alamat', 'no_kendaraan', 'jumlah_sbw_kotor','jumlah_box', 'kadar_air','jumlah_pcs', 'warna', 'kondisi', 'grade', 'keputusan', 'status','harga_kulak', 'status_approval' ,'created_at', 'updated_at'];

}

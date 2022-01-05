<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class datapekerja extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datapekerja';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'nama_pekerja', 'status' ,'created_at', 'updated_at'];
}

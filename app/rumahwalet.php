<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rumahwalet extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_rumah_walet';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'no_register','nama', 'alamat' ,'created_at', 'updated_at'];
}

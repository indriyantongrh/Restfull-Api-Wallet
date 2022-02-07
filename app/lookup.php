<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lookup extends Model
{
              /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lookup';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'type','name'];

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['id', 'role_name', 'created_at', 'updated_at'];
}

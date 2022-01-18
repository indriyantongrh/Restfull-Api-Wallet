<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

///use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'role_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
     public function adding()
    {
        return $this->hasMany(adding::class);
    }
     public function gradding()
    {
        return $this->hasMany(gradding::class);
    }

    public function mandor()
    {
        return $this->hasMany(mandor::class);
    }

    public function datapekerja()
    {
        return $this->hasMany(datapekerja::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function koreksi()
        {
            return $this->hasMany(koreksi::class);
        }

         public function drypertama()
        {
            return $this->hasMany(drypertama::class);
        }
}

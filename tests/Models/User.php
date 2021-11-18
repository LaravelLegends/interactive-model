<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email', 
        'password', 
        'last_login', 
        'metadata',
        'active'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'metadata'   => 'json',
        'name'       => 'string',
        'last_login' => 'datetime',
        'active'     => 'boolean',
    ];

    public function save(array $options = [])
    {
        return true;
    }
}
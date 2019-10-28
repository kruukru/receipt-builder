<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Authenticatable
{
    use SoftDeletes;

	protected $table = 'account';

    protected $fillable = [
    	'name',
    	'username', 
        'password',
        'type',
    ];

    protected $hidden = [
    	'password',
        'remember_token',
    ];
}

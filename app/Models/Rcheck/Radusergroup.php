<?php

namespace App\Models\Rcheck;

use Illuminate\Database\Eloquent\Model;

class Radusergroup extends Model
{
    public $timestamps = false;
    protected $table = 'radusergroup';
    protected $fillable = [
        'username',
        'groupname',
        'priority',
    ];
}

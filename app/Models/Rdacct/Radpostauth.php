<?php

namespace App\Models\Rdacct;

use Illuminate\Database\Eloquent\Model;

class Radpostauth extends Model
{
    public $timestamps = false;
    protected $table = 'radpostauth';
    protected $fillable = [
        'username',
        'pass',
        'reply',
        'authdate',
        'class',
    ];
}

<?php

namespace App\Models\Rcheck;

use Illuminate\Database\Eloquent\Model;

class Radgroupcheck extends Model
{
    public $timestamps = false;
    protected $table = 'radgroupcheck';
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value',
    ];
}

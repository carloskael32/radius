<?php

namespace App\Models\Rcheck;

use Illuminate\Database\Eloquent\Model;

class Radgroupreply extends Model
{
    
    public $timestamps = false;
    protected $table = 'radgroupreply';
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value',
    ];
}

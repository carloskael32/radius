<?php

namespace App\Models\Nas;

use Illuminate\Database\Eloquent\Model;

class Nas extends Model
{
    public $timestamps = false;
    protected $table = 'nas';
    protected $fillable = [
        'id',
        'nasname',
        'shortname',
        'type',
        'ports',
        'secret',
      /*   'server',
        'community',
        'description',
        'require_ma',
        'limit_proxy_state', */
        'host',
        'user',
        'pass',
        'port',
        'status',
    ];
}

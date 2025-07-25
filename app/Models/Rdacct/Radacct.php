<?php

namespace App\Models\Rdacct;

use Illuminate\Database\Eloquent\Model;

class Radacct extends Model
{
    public $timestamps = false;
    protected $table ='radacct';
    protected $fillable = [
        'radacctid',
        'acctsessionid',
        'acctuniqueid',
        'username',
        'groupname',
        'realm',
        'nasipaddress',  
        'nasportid',      
        'nasporttype',
        'acctstarttime',
        'acctstoptime',
        'acctinterval',
        'acctsessiontime',
        'acctauthentic',
        'connectinfo_start',
        'connectinfo_stop',
        'acctinputoctets',
        'acctoutputoctets',
        'calledstationid',
        'callingstationid',
        'acctterminatecause',
        'servicetype',
        'framedprotocol',
        'framedipaddress',
   
        
    ];
    protected $primaryKey = 'radacctid';
    public $incrementing = true;    
}

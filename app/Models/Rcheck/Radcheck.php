<?php

namespace App\Models\Rcheck;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Radcheck extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table= 'radcheck';
    protected $fillable =[
        'username',
        'attribute',
        'op',
        'value',
    ];
}

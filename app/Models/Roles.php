<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'nombre_rol',
        'descripcion',
        'nivel_permiso',
    ];

    //relacion con usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol','id');
        
    }
}

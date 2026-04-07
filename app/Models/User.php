<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'nombres',
        'apellidos',
        'telefono',
        'direccion',
        'password',
        'id_rol',
        'activo',
        'ultimo_acceso',
    ];

    //Relacion con ROLes
    public function rol(){
        return $this->belongsTo(Roles::class, 'id_rol', 'id');
    }

   

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    // Ocultar campos en arrays/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

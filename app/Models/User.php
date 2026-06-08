<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable, HasRoles;
    use LogsActivity;

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
        'estado',
        'ultimo_acceso',
    ];

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
            'estado' => 'string',
        ];
    }



    //registra LOGS
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Usuarios')
            ->logOnly([
                'username',
                'email',
                'nombres',
                'apellidos',
                'telefono',
                'direccion',
                'password',
                'estado',
                'ultimo_acceso'
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {

        //obtenemos el nombre de usuario autenticado
        $user = Auth::user();
        $userName = $user ? $user->username : 'Sistema';

        // 1. Limpieza de password en los logs
        $properties = $activity->properties->toArray();

        if (isset($properties['attributes']['password'])) {
            $properties['attributes']['password'] = '[OCULTO]';
        }

        if (isset($properties['old']['password'])) {
            $properties['old']['password'] = '[OCULTO]';
        }

        $header = [
            'autor' => $userName,
            'username' => $this->username
        ];

        $activity->properties = collect($header)->merge($properties);
    }
}

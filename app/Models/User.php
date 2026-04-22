<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable;
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
        'id_rol',
        'activo',
        'ultimo_acceso',
    ];

    //Relacion con ROLes
    public function rol()
    {
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
                'id_rol',
                'activo',
                'ultimo_acceso'
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {

        //obtenemos el nombre de usuario autenticado
        $user = Auth::user();
        $userName = $user ? $user->username : 'Sistema';

/* 
        // 1. Convertimos la colección a un array de PHP puro
        $properties = $activity->properties->toArray();

        // 2. Verificamos si existe la clave 'password' en los atributos
        if (isset($properties['attributes']['password'])) {
            $properties['attributes']['password'] = '****** (Actualizada por seguridad)';
        }

        // 3. Verificamos si existe en los valores antiguos (en caso de updates)
        if (isset($properties['old']['password'])) {
            $properties['old']['password'] = '******';
        } */
    
         //creamos el encabezado con los datos 
         $header =[
            'autor' => $userName,
            'username' => $this->username
         ];
        
        // 2. Usamos merge() para añadir las propiedades que Spatie ya generó (attributes, old, etc.)
      /*   $activity->properties = collect(['autor' => $userName][$properties['username']])
            ->merge($properties); */
            $activity->properties = collect($header)
            ->merge($activity->properties);
    }
}

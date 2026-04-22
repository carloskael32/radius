<?php

namespace App\Models\Nas;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Nas extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'nas';
    protected $fillable = [
        'id',
        'nasname',
        'shortname',
        'type',
        'ports',
        'secret',
        'host',
        'user',
        'pass',
        'port',
        'status',
    ];


    //registra LOGS
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Nas')
            ->logOnly([
                'id',
                'nasname',
                'shortname',
                'type',
                'ports',
                'secret',
                'host',
                'user',
                'pass',
                'port',
                'status'
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {

        //obtenemos el nombre de usuario autenticado
        $user = Auth::user();
        $userName = $user ? $user->username : 'Sistema';

        // 1. Convertimos la colección a un array de PHP puro
        $properties = $activity->properties->toArray();

        // 2. Verificamos si existe la clave 'password' en los atributos
        if (isset($properties['attributes']['pass'])) {
            $properties['attributes']['pass'] = '****** (Actualizada por seguridad)';
        }

        // 3. Verificamos si existe en los valores antiguos (en caso de updates)
        if (isset($properties['old']['pass'])) {
            $properties['old']['pass'] = '******';
        }

        // 1. Creamos una colección nueva con el nombre del usuario primero
        // 2. Usamos merge() para añadir las propiedades que Spatie ya generó (attributes, old, etc.)
        $activity->properties = collect(['autor' => $userName])
            ->merge($properties);
    }
}

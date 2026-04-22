<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Roles extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'roles';

    protected $fillable = [
        'nombre_rol',
        'descripcion',
        'nivel_permiso',
    ];

    //relacion con usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Roles')
            ->logOnly([
                'nombre_rol',
                'descripcion',
                'nivel_permiso'
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {

        //obtenemos el nombre de usuario autenticado
        $user = Auth::user();
        $userName = $user ? $user->username : 'Sistema';

        // 1. Creamos una colección nueva con el nombre del usuario primero
        // 2. Usamos merge() para añadir las propiedades que Spatie ya generó (attributes, old, etc.)
        $activity->properties = collect(['autor' => $userName])
            ->merge($activity->properties);
    }
}

<?php

namespace App\Models\Rcheck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Radgroupreply extends Model
{

    use LogsActivity;

    public $timestamps = false;
    protected $table = 'radgroupreply';
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Plan de Servicio')
            ->logOnly(['groupname', 'attribute', 'value'])
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

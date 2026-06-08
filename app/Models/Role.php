<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Facades\CauserResolver;

class Role extends SpatieRole
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    /**
     * Configuración del Activity Log para los Roles
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Roles')
            ->logOnly([
                'name',
                'description',
            ])
            ->logOnlyDirty();
    }

    /**
     * Personaliza el Activity Log agregando el usuario autenticado como autor
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        // Obtenemos el nombre de usuario autenticado
        $user = Auth::user();
        $userName = $user ? $user->username : 'Sistema';

        // Creamos una colección nueva con el nombre del usuario primero
        // Usamos merge() para añadir las propiedades que Spatie ya generó (attributes, old, etc.)
        $activity->properties = collect(['autor' => $userName])
            ->merge($activity->properties);
    }

    /**
     * Sobrescribimos syncPermissions para registrar cambios en permisos
     */
    public function syncPermissions(...$permissions)
    {
        // Obtenemos los permisos actuales antes del cambio
        $permisosAntes = $this->permissions->pluck('name')->toArray();

        // Ejecutamos la sincronización original
        parent::syncPermissions(...$permissions);

        // Obtenemos los permisos después del cambio
        $permisosDespues = $this->fresh()->permissions->pluck('name')->toArray();

        // Calculamos permisos adicionados y quitados
        $permisosAdicionados = array_diff($permisosDespues, $permisosAntes);
        $permisosQuitados = array_diff($permisosAntes, $permisosDespues);

        // Si hay cambios, registramos la actividad
        if (!empty($permisosAdicionados) || !empty($permisosQuitados)) {
            $user = Auth::user();
            $userName = $user ? $user->username : 'Sistema';

            activity()
                ->useLog('Roles')
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'autor' => $userName,
                    'permisosAdicionados' => array_values($permisosAdicionados),
                    'permisosQuitados' => array_values($permisosQuitados),
                ])
                ->log('updated');
        }
    }
}

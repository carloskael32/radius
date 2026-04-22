<?php

namespace App\Models\Client;

use App\Models\Rcheck\Radcheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Client extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $table = 'clients';

    protected $fillable = [
        'username',
        'nombre_completo',
        'email',
        'telefono',
        'direccion',
        'plan',
        'estado',
        'observaciones',
    ];

    /*   protected $attributes = [
        'estado' => 'activo'
    ]; */

    //Relacion con radcheck 
    public function radcheck()
    {
        return $this->hasMany(Radcheck::class, 'username', 'username');
    }

    // Métodos de utilidad
    public function tieneCredencialesRadius()
    {
        return $this->radcheck()->where('attribute', 'Cleartext-Password')->exists();
    }


    //registra LOGS
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Clientes')
            ->logOnly([
                'username',
                'nombre_completo',
                'email',
                'telefono',
                'direccion',
                'plan',
                'estado',
                'observaciones'
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

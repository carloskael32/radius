<?php

namespace App\Models\Client;

use App\Models\Rcheck\Radcheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Client extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'clients';

    protected $fillable = [
        'username',
        'nombre_completo',
        'email',
        'telefono',
        'direccion',
        /*      'plan',
        'velocidad_subida',
        'velocidad_bajada',
        'tipo_conexion',
        'ip_asignada',
        'mac_address',
        'fecha_instalacion',
        'fecha_corte', */
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
  /*   public function activar()
    {
        $this->update(['estado' => 'activo']);

        // Agregar credenciales en RADIUS si no existen
        if (!$this->tieneCredencialesRadius()) {
            // Crear registro en Radcheck si lo necesitas
            Radcheck::create([
                'username' => $this->username,
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => 'password_por_defecto'
            ]);
        }
    } */
}

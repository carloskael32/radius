<?php

namespace App\Http\Controllers\MikroTik;   // Indica que está en la carpeta Controllers

use App\Models\Nas\Nas;          // Importamos el modelo NAS (tabla con tus MikroTiks)
use App\Services\MikrotikService; // Importamos el servicio que hicimos antes
use Illuminate\Http\JsonResponse; // Para devolver respuestas en formato JSON
use App\Http\Controllers\Controller; // Importamos la clase base Controller


class MikrotikController extends Controller
{
    protected $mikrotik;

     // Constructor: Laravel inyecta el servicio automáticamente
    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    //Método para obtener sesiones PPPoE de todos los NAS
    public function sessions(): JsonResponse
    {
        $results = [];

        // 1. Obtener todos los NAs de la base de datos
        $nasList = Nas::select('nasname','host','user','pass','port')->get();

        // 2. Recorrer cada NAS
        foreach ($nasList as $nas){
            try{
                // 3. Conectarse al Mkrotik y obtener sesiones activas
                $sessions = $this->mikrotik->getActiveSessions(
                    $nas->host,
                    $nas->user,
                    $nas->pass,
                    $nas->port
                );

                // 4. Guardar el resultado en el arreglo
                $results[$nas->nasname] = $sessions;

            } catch (\Throwable $e){
                //Si hay error, lo guardamos tambien
                $results[$nas->nasname] = ['No se pudo conectar :(' => $e->getMessage()];

            }
        }
        return response()->json($results);

    }

}
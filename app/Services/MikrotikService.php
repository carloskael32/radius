<?php
// Esto indica que la clase está en la carpeta Services
namespace App\Services;

// Importamos las clases de la librería RouterOS

use Illuminate\Support\Facades\Crypt;
use RouterOS\Client;
use RouterOS\Query;

class MikrotikService
{

     /**
     * Método para obtener sesiones PPPoE activas
     */
    public function getActiveSessions($host, $user, $pass, $port = 8728): array
    {
        // 1. Creamos el cliente (conexión al MikroTik)
        $client = new Client([
            'host' => $host,
            'user' => $user,
            'pass' => Crypt::decryptString($pass),
            'port' => $port,
        ]);

        // 2. Definimos el comando que queremos ejecutar
        $query = new Query('/system/resource/print');

        // 3. Ejecutamos el comando y obtenemos la respuesta
        return $client->query($query)->read();
    }
}
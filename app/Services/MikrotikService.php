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

    public function logoutUsers($host, $user, $pass, $username, $port = 8728): array
    {
        // 1. Creamos el cliente (conexión al MikroTik)
        $client = new Client([
            'host' => $host,
            'user' => $user,
            'pass' => Crypt::decryptString($pass),
            'port' => $port,
        ]);

        //listamos las sesiones activas
        $query = new Query('/ppp/active/print');
        
        $sessions = $client->query($query)->read();

        //buscar la session por nombre de usuario
        foreach ($sessions as $session) {
            if ($session['name'] === $username) {
                $id = $session['.id']; // identificador interno de usuario

                // matamos la sesion del usuario temporalmente
                $kill = new Query('/ppp/active/remove');
                $kill->equal('.id', $id);
                
              return  $client->query($kill)->read();
            }
        }
        // Si no se encontró el usuario, devolver vacío o mensaje
        return ['error' => 'Usuario no encontrado en sesiones activas'];
    }
}

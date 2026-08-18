<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radusergroup;
use App\Models\Rcheck\Radgroupreply;
use App\Models\Rdacct\Radacct;
use App\Services\MikrotikService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use League\Config\Exception\ValidationException;

class ClientController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $clients = Client::orderBy('id', 'desc')->get();

        //$rgreply = Radgroupreply::orderBy('id', 'desc')->get();

        $rgreply = Radgroupreply::where('value', '!=', 'morosos')->get();

        return Inertia::render('Client/Index', [
            'clients' => $clients,
            'grupos' => $rgreply,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'username' => 'required|string|max:64|unique:clients,username',
                'nombre_completo' => 'required|string|max:100',
                'email' => 'nullable|email|max:100',
                'telefono' => 'required|string|max:20',
                'direccion' => 'required|string',
                'password_radius' => 'required|string|min:6',
                //'estado' => 'nullable|in:activo,inactivo',
                'observaciones' => 'nullable|string',
                'plan' => 'required|string',
            ]);



            Client::create([
                'username' => $validate['username'],
                'nombre_completo' => $validate['nombre_completo'],
                'email' => $validate['email'],
                'telefono' => $validate['telefono'],
                'direccion' => $validate['direccion'],
                'password_radius' => $validate['password_radius'],
                'estado' => $request->estado ?? 'activo',
                'observaciones' => $validate->observaciones ?? 'Ninguna',
                'plan' => $validate['plan'],
            ]);

            Radcheck::create([
                'username' => $validate['username'],
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $validate['password_radius'],
            ]);


            //actualizar el grupo de servicio de los clientes
            if ($request->estado == 'inactivo') {

                Radusergroup::create([
                    'username' => $validate['username'],
                    'groupname' => 'inactivo',
                    'priority' => '1',

                ]);


                /*    Radusergroup::where('username', $validate['username'])
                    ->update(['groupname' => 'inactivo']); */
            } else {
                /*   Radusergroup::where('username', $validate['username'])
                    ->update(['groupname' => $validate['plan']]);
 */
                Radusergroup::create([
                    'username' => $validate['username'],
                    'groupname' => $validate['plan'],
                    'priority' => '1',

                ]);
            }




            //para refrescar las tablas en freeradius
            //exec('sudo systemctl kill -s USR1 freeradius.service');
            return redirect()->route('client.index');
        } catch (ValidationException $e) {
            throw $e;
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $clientes = $client;

        $clientes->fecha_creacion = $clientes->created_at->format('d/m/Y H:i');

        //return response()->json($clientes);

        return inertia::render('Client/Show', [
            'clientes' => $clientes,
        ]);
    }


    public function showRcheck(string $id)
    {

        $client = Client::find($id);
        if (!$client) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $rcheck = Radcheck::where('username', $client->username)->first();
        if (!$rcheck) {
            return response()->json(['error' => 'Radcheck no encontrado'], 404);
        }

        return response()->json([
            'pass' => $rcheck->value,
        ]);
    }


    /**
     * Toggle cliente estado (activo/inactivo) via AJAX
     */
    public function toggle(Request $request, string $id)
    {
        $validate = $request->validate([
            'estado' => 'required|in:activo,inactivo',
        ]);

        $client = Client::find($id);
        if (!$client) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $newEstado = $validate['estado'];

        $client->estado = $newEstado;
        $client->save();

        // actualizar o crear Radusergroup según el estado y plan
        $radusrg = Radusergroup::where('username', $client->username)->first();
        $groupname = $newEstado === 'inactivo' ? 'inactivo' : ($client->plan ?? '');

        if ($radusrg) {
            $radusrg->update(['groupname' => $groupname]);
        } else {
            Radusergroup::create([
                'username' => $client->username,
                'groupname' => $groupname,
                'priority' => '1',
            ]);
        }

        //desconectar usuario temporalmente para poder actualizar el nuevo plan o estado
        $nas = Radacct::where('username', $client->username)
            ->join('nas', 'radacct.nasipaddress', '=', 'nas.nasname')
            ->select('radacct.username', 'nas.host', 'nas.user', 'nas.pass')
            ->orderBy('acctstarttime', 'desc')
            ->first();

        $results = [];
        $nasName = $nas?->host ?? 'sin-nas';

        try {
            if (!$nas || empty($nas->host) || empty($nas->user) || empty($nas->pass)) {
                throw new \Exception('No se encontró información válida del NAS para desconectar al usuario.');
            }

            $sessions = $this->mikrotik->logoutUsers(
                $nas->host,
                $nas->user,
                $nas->pass,
                $client->username,
            );

            $results[$nasName] = $sessions;
            
            if (isset($sessions['error'])) {
                //$results[$nasName] = ['error' => $sessions['error']];
                $radusrg->update(['groupname' => 'activo']);
                $client->update(['estado' => 'activo']);
            } else {
                $radusrg->update(['groupname' => 'inactivo']);
                $client->update(['estado' => 'inactivo']);
            }
        } catch (\Throwable $e) {
            $results[$nasName] = ['No se pudo conectar :(' => $e->getMessage()];
        }

        return response()->json(['estado' => $client->estado]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validate = $request->validate([
            'username' => 'required|string|max:64',
            'nombre_completo' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'password_radius' => 'required|string|min:6',
            //'estado' => 'nullable|in:activo,inactivo',
            'observaciones' => 'nullable|string',
            'plan' => 'required|string',
        ]);


        $client = Client::find($id);
        $rad = Radcheck::where('username', $client->username)->first();
        $radusrg = Radusergroup::where('username', $client->username)->first();
        $rad->update([
            'username' => $validate['username'],
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => $validate['password_radius'],
        ]);

        $client->update([
            'username' => $validate['username'],
            'nombre_completo' => $validate['nombre_completo'],
            'email' => $request->email ?? 'no tiene',
            'telefono' => $validate['telefono'],
            'direccion' => $validate['direccion'],
            'password_radius' => $validate['password_radius'],
            'estado' => $request->estado ?? 'activo',
            'observaciones' => $validate['observaciones'],
            'plan' => $validate['plan'],
        ]);

        $groupname = $request->estado === 'inactivo' ? 'inactivo' : ($client->plan ?? '');


        if ($radusrg) {
            $radusrg->update(['groupname' => $groupname]);

            //desconectar usuario temporalmente para poder actualizar el nuevo plan o estado
            $nas = Radacct::where('username', $client->username)
                ->join('nas', 'radacct.nasipaddress', '=', 'nas.nasname')
                ->select('radacct.username', 'nas.host', 'nas.user', 'nas.pass')
                ->orderBy('acctstarttime', 'desc')
                ->first();

            $results = [];
            $nasName = $nas?->host ?? 'sin-nas';

            try {
                if (!$nas || empty($nas->host) || empty($nas->user) || empty($nas->pass)) {
                    throw new \Exception('No se encontró información válida del NAS para desconectar al usuario.');
                }

                $sessions = $this->mikrotik->logoutUsers(
                    $nas->host,
                    $nas->user,
                    $nas->pass,
                    $client->username,
                );
                $results[$nasName] = $sessions;

                // if (isset($sessions['error'])) {
                //     //$results[$nasName] = ['error' => $sessions['error']];
                //     $radusrg->update(['groupname' => 'activo']);
                //     $client->update(['estado' => 'activo']);
                // } else {
                //     $radusrg->update(['groupname' => 'inactivo']);
                //     $client->update(['estado' => 'inactivo']);
                // }
            } catch (\Throwable $e) {
                $results[$nasName] = ['No se pudo conectar :(' => $e->getMessage()];
            }
        } else {
            Radusergroup::create([
                'username' => $client->username,
                'groupname' => $groupname,
                'priority' => '1',
            ]);
        }


        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {

        $client = Client::find($id);
        Radcheck::where('username', $client->username)->delete();
        Radusergroup::where('username', $client->username)->delete();
        $client->delete();

        //exec('sudo systemctl kill -s USR1 freeradius.service');


        return redirect()->route('client.index');
        //->with('success', 'Cliente eliminado exitosamente.');
    }
}

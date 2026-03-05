<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radusergroup;
use App\Models\Rcheck\Radgroupreply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use League\Config\Exception\ValidationException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $clients = Client::orderBy('id', 'desc')->get();

        //$rgreply = Radgroupreply::orderBy('id', 'desc')->get();

        $rgreply = Radgroupreply::where('groupname', '!=', 'inactivo')->get();

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
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string',
                'password_radius' => 'required|string|min:6',
                'estado' => 'nullable|in:activo,inactivo',
                'observaciones' => 'nullable|string',
                'plan' => 'nullable|string',
            ]);


            Client::create($validate);

            Radcheck::create([
                'username' => $validate['username'],
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $validate['password_radius'],
            ]);


            //actualizar el grupo de servicio de los clientes
            if ($validate['estado'] == 'inactivo') {

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

        // refrescar freeradius
        /*   try {
            exec('sudo systemctl kill -s USR1 freeradius.service');
        } catch (\Throwable $e) {
            // no bloquear si falla el reload; loguear en futuro si es necesario
        } */

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
            'estado' => 'nullable|in:activo,inactivo',
            'observaciones' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);


        $client = Client::find($id);
        $rad = Radcheck::where('username', $client->username)->first();
        $radusrg = Radusergroup::where('username', $client->username)->first();

        $client->update($validate);

        $rad->update([
            'username' => $validate['username'],
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => $validate['password_radius'],
        ]);

          $groupname = $validate['estado'] === 'inactivo' ? 'inactivo' : ($client->plan ?? '');

         if ($radusrg) {
            $radusrg->update(['groupname' => $groupname]);
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

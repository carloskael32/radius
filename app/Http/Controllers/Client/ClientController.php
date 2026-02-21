<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radusergroup;
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
        $rgrchk = Radgroupcheck::orderBy('id','desc')->get();

        return Inertia::render('Client/Index', [
            'clients' => $clients,
            'grupos' => $rgrchk,
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
                'estado' => 'required|in:activo,inactivo',
                'observaciones' => 'nullable|string',
                'plan'=>'required|string',
            ]);


            Client::create($validate);

            Radcheck::create([
                'username' => $validate['username'],
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $validate['password_radius'],
            ]);

            Radusergroup::create([
                'username' => $validate['username'],
                'groupname' => $validate['plan'],
                'priority' => '1',

            ]);

            //para refrescar las tablas en freeradius
            exec('sudo systemctl kill -s USR1 freeradius.service');
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
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string',
            'plan'=>'nullable|string',
        ]);


        $client = Client::find($id);
        $rad = Radcheck::where('username', $client->username)->first();
        $radusrg = Radusergroup::where('username', $client->username)->first();

        $client->update($validate);
        if ($rad) {
            $rad->update([
                'username' => $validate['username'],
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $validate['password_radius'],
            ]);
        }
        if ($radusrg) {
            $radusrg->update([
                'username' => $validate['username'],
                'groupname' => $validate['plan'],
            ]);
        }else{
              Radusergroup::create([
                'username' => $validate['username'],
                'groupname' => $validate['plan'],
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

        exec('sudo systemctl kill -s USR1 freeradius.service');


        return redirect()->route('client.index');
        //->with('success', 'Cliente eliminado exitosamente.');
    }
}

<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Rcheck\Radcheck;
use App\Models\Rdacct\Radacct;
use App\Models\Nas\Nas;
use Carbon\Carbon;

class RadcheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $currentTime = Carbon::now();
        dd($currentTime);  */

        $radchecks = Radcheck::select('id', 'username', 'value')->get();
        return Inertia::render('Userspp/Index', [
            'radchecks' => $radchecks,
            //  'success' => session('success'), // Pasar el mensaje flash a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:15',
            /*  'attribute' => 'required',
            'op' => 'required', */
            //op = password
            'password' => 'required|string',
        ]);

        //datos obligatorios segun freeradius
        $attribute = 'Cleartext-Password';
        $opvalue = ':=';


        //llenado de datos en la tabla radcheck
        $Radcheck = Radcheck::create([
            'username' => $validate['username'],
            'attribute' => $attribute,
            'op' => $opvalue,
            //'value'=> bcrypt($validate['password']),
            'value' => $validate['password'],
        ]);


        return redirect()->route('radcheck.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*  DESCONECTAR A USUARIO AL MOMENTO DE ACTUALIZAR SUS DATOS
        $radcheck = Radcheck::findOrFail($id);
        $username = $radcheck->username;

        //borrado de usuario con datos de Radacct       
        $rdacc = Radacct::where('username', $username)
            ->orderBy('radacctid', 'desc')
            ->first();

        if ($rdacc) {
            $user = $rdacc->username;
            $ip = $rdacc->nasipaddress;

            //obteniendo datos de nas
            $nas = Nas::where('nasname', $ip)->first();
            $secret = $nas->secret;

            //ejecutando comando para desconectar a usuario
            //exec('echo "User-Name='.$user.'" | radclient -x '.$ip.' disconnect"'.$secret.'"');
            $command = 'echo "User-Name=' . $user . '" | radclient -x ' . $ip . ' disconnect "' . $secret . '"';

            $output = [];
            $result = exec($command, $output);

            if ($result === false) {
                // Error al ejecutar
                logger()->error('Error al ejecutar radclient');
            } else {
                // Éxito
                logger()->info('Usuario desconectado: ' . $user, ['output' => $output]);
            }
        }
 */


        // Validación de los datos del formulario

        $validate = $request->validate([
            'username' => 'required|string|max:15',
            'password' => 'required|string',
        ]);

        // Actualización de los datos en la tabla radcheck
        $user = Radcheck::findOrFail($id);
        $user->update([
            'username' => $validate['username'],
            'value' => $validate['password'],
        ]);

        return redirect()->route('radcheck.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        /* PARA DESCONECTAR A UN USUARIO PPPOE DE FORMA BRUTA */
        /* $radcheck = Radcheck::findOrFail($id);
        $username = $radcheck->username;

        //borrado de usuario con datos de Radacct       
        $rdacc = Radacct::where('username', $username)
            ->orderBy('radacctid', 'desc')
            ->first();

        if ($rdacc) {
            $user = $rdacc->username;
            $ip = $rdacc->nasipaddress;

            //obteniendo datos de nas
            $nas = Nas::where('nasname', $ip)->first();
            $secret = $nas->secret;

            //ejecutando comando para desconectar a usuario
            //exec('echo "User-Name='.$user.'" | radclient -x '.$ip.' disconnect"'.$secret.'"');
            $command = 'echo "User-Name=' . $user . '" | radclient -x ' . $ip . ' disconnect "' . $secret . '"';

            $output = [];
            $result = exec($command, $output);

            if ($result === false) {
                // Error al ejecutar
                logger()->error('Error al ejecutar radclient');
            } else {
                // Éxito
                logger()->info('Usuario desconectado: ' . $user, ['output' => $output]);
            }
        }
        $radcheck->delete(); */
        $uspp = Radcheck::find($id);
        $uspp->delete();
        return redirect()->route('radcheck.index');
    }
}

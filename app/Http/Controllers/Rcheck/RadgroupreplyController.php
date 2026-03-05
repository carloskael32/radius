<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rcheck\Radusergroup;
use Illuminate\Support\Facades\DB;
use App\Models\Client\Client;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radgroupreply;
use Inertia\Inertia;

class RadgroupreplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuariosPorGrupo = DB::table('radgroupreply')
            ->join('radusergroup', 'radgroupreply.groupname', '=', 'radusergroup.groupname')
            ->select('radgroupreply.groupname', DB::raw('COUNT(radusergroup.username) as total_usuarios'))
            ->groupBy('radgroupreply.groupname')
            ->get()
            ->keyBy('groupname'); // Convertir a array asociativo clave-valor

        //$rgreply = Radgroupreply::select('id', 'groupname', 'value')->get();
        $rgreply = Radgroupreply::where('groupname', '!=', 'inactivo')->get();

        // Combinar los datos
        $datosCombinados = $rgreply->map(function ($item) use ($usuariosPorGrupo) {
            return [
                'id' => $item->id,
                'groupname' => $item->groupname,
                'value' => $item->value,
                'total_usuarios' => $usuariosPorGrupo[$item->groupname]->total_usuarios ?? 0
            ];
        });

        return inertia::render('Rgreply/Index', [
            'rgreply' => $datosCombinados
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
            'groupname' => 'required|string|max:100',
            'valued' => 'required|integer',
            'valueu' => 'required|integer',
            'navd' => 'required|string|max:5',
            'navu' => 'required|string|max:5',
        ]);

        //return response()->json($validate);

        Radgroupreply::create([
            'groupname' => $validate['groupname'],
            'attribute' => 'Mikrotik-Rate-Limit',
            'op' => ':=',
            'value' => $validate['valued'] . $validate['navd'] . '/' . $validate['valueu'] . $validate['navu'],

        ]);

        //crea el grupo en radgroupcheck 
        Radgroupcheck::create([
            'groupname' => $validate['groupname'],
            'attribute' => 'Auth-Type',
            'op' => ':=',
            'value' => 'Accept',

        ]);

        return redirect()->route('rgreply.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $groupname)
    {
        //devuelve el nombre de  clientes del grupo selecionado
        $usrcongrupo = Radusergroup::select('id', 'username')->where('groupname', $groupname)
            ->get();


        //devuelve usuarios que no esten en ningun grupo de radusergroup
        $usrsingrupo = DB::table('radcheck')
            ->whereNotIn('username', function ($query) {
                $query->select('username')
                    ->from('radusergroup');
            })
            ->get();

        //return response()->json($usrsingrupo);

        return response()->json([
            'clients' => $usrcongrupo,
            'clsngr' => $usrsingrupo,
        ]);
    }

    // para REGISTRAR USUARIOS CON GRUPOS DE SERVICIO
    public function assignClients(Request $request, $groupId)
    {
        $validated = $request->validate([
            'clients' => 'required|array|min:1',
            'clients.*' => 'string'
        ]);

        // Obtener el nombre del grupo
        $rgreply = Radgroupreply::find($groupId);

        if (!$rgreply) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // Asignar clientes al grupo
        foreach ($validated['clients'] as $username) {
            // Verificar si ya existe la asignación
            $existe = Radusergroup::where('username', $username)
                ->where('groupname', $rgreply->groupname)
                ->first();

            if (!$existe) {
                Radusergroup::create([
                    'username' => $username,
                    'groupname' => $rgreply->groupname,
                    'priority' => 1,
                ]);
                Client::where('username', $username)
                    ->update([
                        'plan' => $rgreply->groupname,
                    ]);
            }
        }

        return response()->json([
            'message' => 'Clientes asignados correctamente',
            'success' => true
        ]);
    }

    //PARA ELIMINAR LOS CIENTES DE LOS GRUPOS DE SERVICIO
    public function delClients(string $id)
    {
        $rgroup = Radusergroup::find($id);
        $rgroup->delete();

        Client::where('username', $rgroup->username)
            ->update(['plan' => null]);

        return response()->json([
            'message' => 'Clientes eliminado correctamente',
            'success' => true
        ]);
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
        $validate = $request->validate([
            'groupname' => 'required|string|max:50',
            'valued' => 'required|integer',
            'valueu' => 'required|integer',
            'navd' => 'required|string|max:5',
            'navu' => 'required|string|max:5',
        ]);

        $rgreply = Radgroupreply::find($id);

        //actualizar el grupo de servicio de los clientes
        Radusergroup::where('groupname', $rgreply->groupname)
            ->update(['groupname' => $validate['groupname']]);

        //actualiza el nombre de grupo en radgroupcheck
        Radgroupcheck::where('groupname', $rgreply->groupname)
            ->update(['groupname' => $validate['groupname']]);


        $rgreply->update([
            'groupname' => $validate['groupname'],
            /*  'attribute' => 'Mikrotik-Rate-Limit',
            'op' => '=', */
            'value' => $validate['valued'] . $validate['navd'] . '/' . $validate['valueu'] . $validate['navu'],
        ]);


        return redirect()->route('rgreply.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rgreply = Radgroupreply::find($id);

        //Borra a todos los clientes del grupo 
        Radusergroup::where('groupname', $rgreply->groupname)->delete();

        //Borra el grupo de radGroupCheck
        Radgroupcheck::where('groupname', $rgreply->groupname)->delete();

        $rgreply->delete();
        return redirect()->route('rgreply.index');
    }
}

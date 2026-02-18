<?php

namespace App\Http\Controllers\Rcheck;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radusergroup;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RadgroupcheckController extends Controller
{

    public function index()
    {

        $usuariosPorGrupo = DB::table('radgroupcheck')
            ->join('radusergroup', 'radgroupcheck.groupname', '=', 'radusergroup.groupname')
            ->select('radgroupcheck.groupname', DB::raw('COUNT(radusergroup.username) as total_usuarios'))
            ->groupBy('radgroupcheck.groupname')
            ->get()
            ->keyBy('groupname'); // Convertir a array asociativo clave-valor

        $rgroupchk = Radgroupcheck::select('id', 'groupname', 'value')->get();

        // Combinar los datos
        $datosCombinados = $rgroupchk->map(function ($item) use ($usuariosPorGrupo) {
            return [
                'id' => $item->id,
                'groupname' => $item->groupname,
                'value' => $item->value,
                'total_usuarios' => $usuariosPorGrupo[$item->groupname]->total_usuarios ?? 0
            ];
        });

        return inertia::render('Rgroup/Index', [
            'rgroupchk' => $datosCombinados
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

        Radgroupcheck::create([
            'groupname' => $validate['groupname'],
            'attribute' => 'Mikrotik-Rate-Limit',
            'op' => '=',
            'value' => $validate['valued'] . $validate['navd'] . '/' . $validate['valueu'] . $validate['navu'],

        ]);

        return redirect()->route('rgroup.index');
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
        $rgroup = Radgroupcheck::find($groupId);

        if (!$rgroup) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // Asignar clientes al grupo
        foreach ($validated['clients'] as $username) {
            // Verificar si ya existe la asignación
            $existe = Radusergroup::where('username', $username)
                ->where('groupname', $rgroup->groupname)
                ->first();

            if (!$existe) {
                Radusergroup::create([
                    'username' => $username,
                    'groupname' => $rgroup->groupname,
                    'priority' => 1,
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

        $rgroup = Radgroupcheck::find($id);

        //actualizar el grupo de servicio de los clientes
        Radusergroup::where('groupname', $rgroup->groupname)
        ->update(['groupname' => $validate['groupname']]);

        $rgroup->update([
            'groupname' => $validate['groupname'],
            /*  'attribute' => 'Mikrotik-Rate-Limit',
            'op' => '=', */
            'value' => $validate['valued'] . $validate['navd'] . '/' . $validate['valueu'] . $validate['navu'],
        ]);

        return redirect()->route('rgroup.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rgroup = Radgroupcheck::find($id);

        $delClient = Radusergroup::where('groupname', $rgroup->groupname)->delete();
 
        $rgroup->delete();
        return redirect()->route('rgroup.index');
    }
}

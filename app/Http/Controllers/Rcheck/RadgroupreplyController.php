<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rcheck\Radusergroup;
use Illuminate\Support\Facades\DB;
use App\Models\Client\Client;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rcheck\Radgroupreply;
use App\Models\Rdacct\Radacct;
use Inertia\Inertia;
use App\Services\MikrotikService;

class RadgroupreplyController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function index()
    {

        $usuariosPorGrupo = DB::table('radgroupcheck')
            ->join('radusergroup', 'radgroupcheck.groupname', '=', 'radusergroup.groupname')
            ->select('radgroupcheck.groupname', DB::raw('COUNT(radusergroup.username) as total_usuarios'))
            ->groupBy('radgroupcheck.groupname')
            ->get()
            ->keyBy('groupname'); // Convertir a array asociativo clave-valor


        //$rgreply = Radgroupreply::select('id', 'groupname', 'value')->get();
        // $rgreply = Radgroupreply::where('value', '!=', 'morosos')
        //     ->get();

        $suspendidos = DB::table('radgroupreply as rreply')
            ->whereIn('rreply.groupname', function ($query) {
                $query->select('sub.groupname')
                    ->from('radgroupreply as sub')
                    ->where('sub.value', '=', 'morosos');
            })
            ->select('rreply.groupname')
            ->first();

        $rgreply = Radgroupreply::where('groupname', '!=', $suspendidos->groupname)->get();
        //return response()->json($rgreply);

        
        // Combinar los datos
        $datosCombinados = $rgreply->map(function ($item) use ($usuariosPorGrupo) {
            return [
                'id' => $item->id,
                'groupname' => $item->groupname,
                'value' => $item->value,
                'total_usuarios' => $usuariosPorGrupo[$item->groupname]->total_usuarios ?? 0,
            ];
        });


        //datos del grupo suspendidos y cantidad de suspendidos
        $dagrupo = DB::table('radgroupreply as rreply')
            ->whereIn('rreply.groupname', function ($query) {
                $query->select('sub.groupname')
                    ->from('radgroupreply as sub')
                    ->where('sub.value', '=', 'morosos');
            })
            ->where('rreply.value', '!=', 'morosos')
            ->join('radusergroup as rgroup', 'rreply.groupname', '=', 'rgroup.groupname')
            ->join('clients as c', 'rgroup.username', '=', 'c.username')
            ->select(
                'rreply.id',
                'rreply.groupname',
                // 'rreply.attribute',
                // 'rreply.op',
                'rreply.value',
                DB::raw('COUNT(c.username) as total')
            )
            ->groupBy('rreply.id', 'rreply.groupname', 'rreply.value')
            ->get();


        // clientes en el grupo de suspendidos
        $morosos = DB::table('radusergroup as rgroup')
            ->join('radgroupreply as rreply', 'rgroup.groupname', '=', 'rreply.groupname')
            ->join('radgroupreply as datos', 'rgroup.groupname', '=', 'datos.groupname')
            ->join('clients as c', 'rgroup.username', '=', 'c.username')
            ->where('rreply.value', '=', 'morosos')
            ->where('datos.value', '!=', 'morosos')
            ->select(
                'rgroup.username',
                //'rgroup.groupname',
                // 'datos.id',
                // 'datos.attribute',
                // 'datos.op',
                //'datos.value',
                'c.nombre_completo',
                'c.telefono',
            )
            ->get();

        //return response()->json($dagrupo);

        return inertia::render('Rgreply/Index', [
            'rgreply' => $datosCombinados,
            'morosos' => $morosos,
            'dagrupo' => $dagrupo
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

        //devuelve el nombre de usuario clientes del grupo selecionado
        $usrcongrupo = Radusergroup::join('clients', 'radusergroup.username', '=', 'clients.username')
            ->select('radusergroup.id', 'radusergroup.username', 'clients.nombre_completo')
            ->where('groupname', $groupname)
            ->get();


        // devuelve los usuarios que no tiene ningun grupo asgnado
        $usrsingrupo = DB::table('radcheck as r')
            ->join('clients', 'r.username', '=', 'clients.username')
            ->leftJoin('radusergroup as rug', 'r.username', '=', 'rug.username')
            ->leftJoin('radgroupreply as rr', 'rug.groupname', '=', 'rr.groupname')
            ->where(function ($q) {
                $q->whereNull('rug.username') // no tiene grupo
                    ->orWhere('rr.value', '=', 'morosos'); // o está en grupo morosos
            })
            ->select('clients.nombre_completo', 'r.username', 'clients.plan', 'rr.value')
            ->get();
        //return response()->json($usrsingrupo);


        //clientes en el grupo de suspendidos
        $morosos = DB::table('radusergroup as rgroup')
            ->join('radgroupreply as rreply', 'rgroup.groupname', '=', 'rreply.groupname')
            ->join('radgroupreply as datos', 'rgroup.groupname', '=', 'datos.groupname')
            ->join('clients as c', 'rgroup.username', '=', 'c.username')
            ->where('rreply.value', '=', 'morosos')
            ->where('datos.value', '!=', 'morosos')
            ->select(
                'rgroup.username',
                'rgroup.groupname',
                // 'datos.id',
                // 'datos.attribute',
                // 'datos.op',
                'datos.value',
                'c.nombre_completo',
                'c.telefono'
            )
            ->get();



        return response()->json([
            'clients' => $usrcongrupo,
            'clsngr' => $usrsingrupo,
            'morosos' => $morosos,
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
            Radusergroup::updateOrCreate(
                ['username' => $username],
                ['groupname' => $rgreply->groupname, 'priority' => 1]
            );

            Client::where('username', $username)
                ->update(['plan' => $rgreply->groupname]);


            //  desconectar usuario temporalmente para poder actualizar el nuevo plan o estado
            $client = Client::where('username', $username)->first();
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
            } catch (\Throwable $e) {
                $results[$nasName] = ['No se pudo conectar :(' => $e->getMessage()];
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
        $rgreply = Radgroupreply::where('value', 'morosos')
            ->first();

        $rgroup->update([
            'groupname' => $rgreply->groupname,
        ]);
        //$rgroup->delete();

        Client::where('username', $rgroup->username)
            ->update(['plan' => $rgreply->groupname]);



        //desconectar usuario temporalmente para poder actualizar el nuevo plan o estado
        $client = Client::where('username', $rgroup->username)->first();
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
        } catch (\Throwable $e) {
            $results[$nasName] = ['No se pudo conectar :(' => $e->getMessage()];
        }


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

        Radgroupreply::where('groupname', $rgreply->groupname)
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

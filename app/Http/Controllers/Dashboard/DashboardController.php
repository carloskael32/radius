<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Nas\Nas;
use App\Models\Rdacct\Radacct;
use App\Models\Rdacct\Radpostauth;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupreply;
use App\Models\Rcheck\Radusergroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //para mostrar Clientes y planes
        $planes = Radgroupreply::where('groupname', '!=', 'inactivo')
            ->pluck('groupname')
            ->toArray();
        foreach ($planes as $plan) {
            $clientesPorPlan[$plan] = Radusergroup::where('groupname', $plan)->count();
        }

        // Clientes sin grupo asignado
        $clientesSinGrupo = Radcheck::leftJoin('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
            ->whereNull('radusergroup.username')
            ->count();

        // Obtener total de usuarios por NAS (todos los usuarios que han usado cada NAS)
        $nasList = Nas::all();
        $usersByNas = [];

        foreach ($nasList as $nas) {
            // Intentar obtener el nombre del NAS (shortname > nasname)
            $nasName = $nas->shortname ?: $nas->nasname;

            // Contar usuarios únicos que han usado este NAS (activos e inactivos)
            $count = Radacct::where(function ($query) use ($nas) {
                $query->where('nasipaddress', $nas->nasname)
                    ->orWhere('nasipaddress', $nas->shortname)
                    ->orWhere('nasipaddress', $nas->host);
            })
                ->distinct('username')
                ->count('username');

            $usersByNas[] = [
                'name' => $nasName,
                'count' => $count,
            ];
        }

        // Ordenar de mayor a menor cantidad de usuarios (solo los que tengan al menos 1 usuario)
        $usersByNas = array_filter($usersByNas, function ($item) {
            return $item['count'] > 0;
        });
        $usersByNas = array_values($usersByNas);

        usort($usersByNas, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Obtener conexiones exitosas y fallidas desde radpostauth (totales generales)
        $successfulAttempts = Radpostauth::where('reply', 'Access-Accept')
        ->whereDate('authdate', Carbon::today())
        ->count();        

        $failedAttempts = Radpostauth::where('reply', 'Access-Reject')
        ->whereDate('authdate', Carbon::today())
        ->count();

        //obtener clientes en ONLINE y OFFLINE
        $online = Radacct::whereNull('acctstoptime')
            ->select('username', 'nasipaddress')
            ->orderBy('acctstarttime', 'desc')
            ->get();


        // Usuarios OFFLINE (excluyendo los que están ONLINE)
        $offline = Radacct::whereNotNull('acctstoptime')
            ->whereNotIn('username', function ($query) {
                $query->select('username')
                    ->from('radacct')
                    ->whereNull('acctstoptime');
            })
            ->select('username', 'acctstoptime', 'nasipaddress')
            ->orderBy('acctstoptime', 'desc')
            ->get()
            ->groupBy('username')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        // $offline =Radacct::whereNotNull('acctstoptime')
        // ->whereNotIn('username', $online->pluck('username'))
        // ->select('username','acctstoptime','nasipaddress')
        // ->orderBy('acctstoptime','desc')
        // ->get()
        // ->groupBy('username')
        // ->map(function ($group){
        //     return $group->first();
        // })
        // ->values();


        // Obtener conexiones diarias de los últimos 7 días desde radpostauth
        $daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $dailySuccess = [];
        $dailyFailed = [];
        $dailyLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = $daysOfWeek[(int) now()->subDays($i)->format('w')];

            $dailyLabels[] = $dayName;
            $dailySuccess[] = Radpostauth::where('reply', 'Access-Accept')
                ->whereDate('authdate', $date)
                ->count();
            $dailyFailed[] = Radpostauth::where('reply', 'Access-Reject')
                ->whereDate('authdate', $date)
                ->count();
        }

        return Inertia::render('Dashboard', [
            'totalClient' => Client::count(),
            'totalNAS' => Nas::count(),
            'connectedClient' => Client::where('estado', 'activo')->count(),
            'disconnectedClient' => Client::where('estado', 'inactivo')->count(),
            'Planes' => $planes,
            'clientesPorPlan' => $clientesPorPlan,
            'clientesSinGrupo' => $clientesSinGrupo,
            'usersByNas' => $usersByNas,
            'successfulAttempts' => $successfulAttempts,
            'failedAttempts' => $failedAttempts,
            'dailyLabels' => $dailyLabels,
            'dailySuccess' => $dailySuccess,
            'dailyFailed' => $dailyFailed,
            'online' => $online,
            'offline' => $offline,
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
        //
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
        //
    }


    public function destroy(string $id)
    {
        //
    }
}

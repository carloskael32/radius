<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Nas\Nas;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupreply;
use App\Models\Rcheck\Radusergroup;
use App\Models\User;
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
        $planes = Radgroupreply::where('groupname','!=','inactivo')
        ->pluck('groupname')
        ->toArray();
        foreach ($planes as $plan){
            $clientesPorPlan[$plan] = Radusergroup::where('groupname', $plan)->count();
        }

        // Clientes sin grupo asignado
        $clientesSinGrupo = Radcheck::leftJoin('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
            ->whereNull('radusergroup.username')
            ->count();

        // O usando whereNotIn
        // $clientesSinGrupo = Client::whereNotIn('username', 
        //     Radusergroup::pluck('username'))->count();

        // O usando whereDoesntHave (si hay relación definida)
        // $clientesSinGrupo = Client::whereDoesntHave('radusergroup')->count();

         return Inertia::render('Dashboard', [
            'totalClient' => Client::count(),
            'totalNAS' => Nas::count(),
            'connectedClient' => Client::where('estado', 'activo')->count(),
            'disconnectedClient' => Client::where('estado', 'inactivo')->count(),
            'Planes' => $planes,
            'clientesPorPlan' => $clientesPorPlan,
            'clientesSinGrupo' => $clientesSinGrupo,
         
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

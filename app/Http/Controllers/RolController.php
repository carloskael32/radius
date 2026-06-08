<?php

namespace App\Http\Controllers;


use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Rol/Index', [
            'roles' => Role::with('permissions')->get(),
            'permisos' => Permission::all(),
        ]);

        //return response()->json([ 'roles' => Role::all()]);

        /*   $nas = Nas::all();
        $total = Nas::count();
        return inertia::render('Nas/Index', compact('nas', 'total')); */
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
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|max:255',
            'permisosSelect' => 'required|array|min:1',
            //'permisosSelect.*' => 'string'
        ]);

        $rol = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Usar la relación de Spatie
        $rol->syncPermissions($request['permisosSelect']);


        return redirect()->route('rol.index');
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
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'required|max:255',
            'permisosSelect' => 'required|array|min:1',
            //'permisosSelect.*' => 'string'
        ]);

        $rol = Role::find($id);

        $rol->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $rol->syncPermissions($request['permisosSelect']);

        return redirect()->route('rol.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Roles que no se pueden eliminar
        $rolesProtegidos = ['Administrador', 'Operador', 'Usuario de Consulta'];
        
        $rol = Role::find($id);
        
        // Validar que el rol no sea uno de los protegidos
        if (in_array($rol->name, $rolesProtegidos)) {
            return redirect()->route('rol.index')->with('error', 'No se puede eliminar este rol protegido.');
        }
        
        $rol->delete();
        return redirect()->route('rol.index');
    }
}

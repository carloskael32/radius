<?php

namespace App\Http\Controllers\Nas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Nas\Nas;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class NasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nas = Nas::all();
        $total = Nas::count();
        return inertia::render('Nas/Index', compact('nas', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     * Not used - uses Vue modal instead
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
            'nasname' => 'required|string|max:50|unique:nas,nasname',
            'shortname' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'ports' => 'nullable|integer',
            'secret' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'user' => 'required|string|max:50',
            'pass' => 'required|string|max:255',
            'port' => 'nullable|integer',
            // 'status' => 'required|string|max:10',
        ]);
        
        Nas::create([
            'nasname' => $validate['nasname'],
            'shortname' => $validate['shortname'],
            'type' => $validate['type'],
            'ports' => $validate['ports'],
            'secret' => $validate['secret'],
            'description' => $validate['description'],
            'host' => $validate['nasname'],
            'user' => $validate['user'],
            'pass' => Crypt::encryptString($validate['pass']),
            'port' => $request->port ?? '8622',
            'status' => $request->status ?? 'activo',
        ]);
        
        // Reiniciar servicio FreeRADIUS para aplicar cambios
        // exec('sudo systemctl kill -s USR1 freeradius.service');

        return redirect()->route('nas.index');
    }

    /**
     * Display the specified resource.
     * Not used in this implementation
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Not used - uses Vue modal instead
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
            'nasname' => 'required|string|max:50',
            'shortname' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'ports' => 'nullable|integer',
            'secret' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'user' => 'required|string|max:50',
            'pass' => 'required|string|max:255',
            'port' => 'nullable|integer',
            // 'status' => 'required|string|max:10',

        ]);
        $nas = Nas::findOrFail($id);
        $nas->update([
            'nasname' => $validate['nasname'],
            'shortname' => $validate['shortname'],
            'type' => $validate['type'],
            'ports' => $validate['ports'],
            'secret' => $validate['secret'],
            'description' => $validate['description'],
            'host' => $validate['nasname'],
            'user' => $validate['user'],
            'pass' => Crypt::encryptString($validate['pass']),
            'port' => $request->port ?? '8622',
            'status' => $request->status ?? 'activo',

        ]);

        // Reiniciar servicio FreeRADIUS para aplicar cambios
        // exec('sudo systemctl kill -s USR1 freeradius.service');
        // return redirect()->route('nas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nas = Nas::findOrFail($id);
        $nas->delete();
        
        // Reiniciar servicio FreeRADIUS para aplicar cambios
        // exec('sudo systemctl kill -s USR1 freeradius.service');
        // return redirect()->route('nas.index');
    }

  
    public function toggle(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:activo,inactivo',
            ]);

            $nas = Nas::find($id);
            $newEstado = $validated['status'];

            $nas->status = $newEstado;
            $nas->save();
            
            // Reiniciar servicio de FreeRADIUS como en los demás métodos
            //exec('sudo systemctl kill -s USR1 freeradius.service');

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'data' => [
                    'id' => $nas->id,
                    'status' => $nas->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}

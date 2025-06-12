<?php

namespace App\Http\Controllers\Nas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Nas\Nas;

class NasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nas = Nas::all();
        return inertia::render('Nas/Index', [
            'nas' => $nas,
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
         'nasname' => 'required|string|max:50',
            'shortname' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'ports' => 'nullable|integer',
            'secret' => 'required|string|max:50',
            'server' => 'nullable|string|max:50',
            /*  'community' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255', */
        ]);
        Nas::create([
            'nasname' => $validate['nasname'],
            'shortname' => $validate['shortname'],
            'type' => $validate['type'],
            'ports' => $validate['ports'],
            'secret' => $validate['secret'],
            'server' => $validate['server'],
            /*  'community' => $validate['community'],
            'description' => $validate['description'], */
        ]);
        return redirect()->route('nas.index');
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
        $validate = $request->validate([
            'nasname' => 'required|string|max:50',
            'shortname' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'ports' => 'nullable|integer',
            'secret' => 'required|string|max:50',
            'server' => 'nullable|string|max:50',
            /*  'community' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255', */
        ]);
        $nas = Nas::find($id);
        $nas->update([
            'nasname' => $validate['nasname'],
            'shortname' => $validate['shortname'],
            'type' => $validate['type'],
            'ports' => $validate['ports'],
            'secret' => $validate['secret'],
            'server' => $validate['server'],
            /*  'community' => $validate['community'],
            'description' => $validate['description'], */
        ]);

/*
        $nas->update(array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $validate)); */
        return redirect()->route('nas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nas = Nas::find($id);
        $nas->delete();
        return redirect()->route('nas.index');
    }
}

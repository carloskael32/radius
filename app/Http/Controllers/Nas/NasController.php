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
            'description' => 'nullable|string|max:255',
            /* 'host' => 'required|string|max:50', */
            'user' => 'required|string|max:50',
            'pass' => 'required|string|max:255',
            'port' => 'nullable|integer',
            'status' => 'required|string|max:10',
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
            'port' => $validate['port'],
            'status' => $validate['status'],

        ]);
        exec('sudo systemctl kill -s USR1 freeradius.service');

        //echo $output;
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
            'description' => 'nullable|string|max:255',
            'user' => 'required|string|max:50',
            'pass' => 'required|string|max:255',
            'port' => 'nullable|integer',
            'status' => 'required|string|max:10',

        ]);
        $nas = Nas::find($id);
        $nas->update([
            'nasname' => $validate['nasname'],
            'shortname' => $validate['shortname'],
            'type' => $validate['type'],
            'ports' => $validate['ports'],
            'secret' => $validate['secret'],
            'description' => $validate['description'],
            'host' => $validate['nasname'],
            'user' => $validate['user'],
            //'pass' => Hash::make($validate['pass']),
            'pass' => Crypt::encryptString($validate['pass']),
            'port' => $validate['port'],
            'status' => $validate['status'],

        ]);

        /*
        $nas->update(array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $validate)); */
        /*       $command = "ssh debian@10.2.2.4 'sudo systemctl kill -s USR1 freeradius.service'";
        $output = shell_exec($command);
        //echo $output;
        exec('sudo systemctl kill -s USR1 freeradius.service'); */
        return redirect()->route('nas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nas = Nas::find($id);
        $nas->delete();
        exec('sudo systemctl kill -s USR1 freeradius.service');
        return redirect()->route('nas.index');
       
    }
}

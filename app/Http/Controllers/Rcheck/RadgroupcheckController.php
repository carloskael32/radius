<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rcheck\Radgroupcheck;
use Inertia\Inertia;

class RadgroupcheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rgroupchk = Radgroupcheck::select('id', 'groupname', 'value')->get();
        return inertia::render('Rgroup/Index', [
            'rgroupchk' => $rgroupchk,
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
            'groupname' => 'required|string|max:50',
            /*  'attribute' => 'required|string|max:50',
            'op' => 'required|string|max:2', */
            'value' => 'required|string|max:30',
        ]);

        //Verificar que no haya duplicaod
        $exists = Radgroupcheck::where('groupname', $validate['groupname'])->exists();

        if ($exists) {
            return back()->withErrors([
                'error' => 'El correo ya está en uso',
            ]);
        } else {
            Radgroupcheck::create([
                'groupname' => $validate['groupname'],
                'attribute' => 'Rate-Limit',
                'op' => '=',
                'value' => $validate['value'],
            ]);
            $rgroupchk = Radgroupcheck::select('id', 'groupname', 'value')->get();
            return inertia::render('Rgroup/Index', [
                'rgroupchk' => $rgroupchk,
            ]);
        }
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
            'groupname' => 'required|string|max:50',
            /*  'attribute' => 'required|string|max:50',
            'op' => 'required|string|max:2', */
            'value' => 'required|string|max:30',
        ]);
        $rgroup = Radgroupcheck::find($id);
        $rgroup->update([
            'groupname' => $validate['groupname'],
            /* 'attribute' => 'Rate-Limit',
            'op' => '=', */
            'value' => $validate['value'],
        ]);

        return redirect()->route('rgroup.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rgroup = Radgroupcheck::find($id);
        $rgroup->delete();
        return redirect()->route('rgroup.index');
    }
}

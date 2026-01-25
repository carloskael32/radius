<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rcheck\Radusergroup;
use App\Models\Rcheck\Radcheck;
use App\Models\Rcheck\Radgroupcheck;
use App\Models\Rdacct\Radacct;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class RadusergroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$rusergrp = Radusergroup::select('id','username','groupname','priority')->get();


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
        $userppp = $request->input('usuarios');
        $perfil = $request->input('perfil');
        foreach ($userppp as $user) {
            Radusergroup::create([
                'username' => $user,
                'groupname' => $perfil,
                'priority' => 1,
            ]);
        }

        return redirect()->route('ruserg.index');
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $groupname)
    {

        $grppf = $groupname;
        $usrprf = Radusergroup::select('id', 'username')->where('groupname', $grppf)->get();

        //$usrpp = DB::select('select username from radcheck where username not in (select username from radusergroup)');

        $usrpp = DB::table('radcheck')
            ->whereNotIn('username', function ($query) {
                $query->select('username')
                    ->from('radusergroup');
            })
            ->get();


     /*    return response()->json([
            'grppf' => $grppf,
            'usrpp' => $usrpp,
            'usrprf' => $usrprf,
            'usuarios' => $usuarios,
        ]);
 */

        return inertia::render('Rusergroup/Index', [
            'grppf' => $grppf,
            'usrpp' => $usrpp,
            'usrprf' => $usrprf,
        ]);
    }

    /**
     * 
     * 
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
        $delusr = Radusergroup::find($id);
        $delusr->delete();
        return redirect()->route('ruserg.index');
    }
}

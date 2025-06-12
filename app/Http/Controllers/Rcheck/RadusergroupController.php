<?php

namespace App\Http\Controllers\Rcheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rcheck\Radusergroup;
use App\Models\Rcheck\Radcheck;
use App\Models\Rdacct\Radacct;
use Inertia\Inertia;


class RadusergroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$rusergrp = Radusergroup::select('id','username','groupname','priority')->get();
        $usrpp = Radcheck::select('id','username')->get();
        return inertia::render('Rusergroup/Index',[
            'usrpp' =>$usrpp, 
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

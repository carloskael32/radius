<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Status');
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
        function pingHost($host, $count = 4, $timeout = 2)
        {
            $command = "ping -c $count -W $timeout $host 2>&1";

            exec($command, $output, $returnCode);
            return [
                'success' => ($returnCode === 0),
                'output' => implode("\n", $output),
                'return_code' => $returnCode
            ];
        }

        $result = pingHost('10.2.2.2');
        if ($result['success']) {
            return response("ONLINE");
        } else {
            return response("offline");
        }

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

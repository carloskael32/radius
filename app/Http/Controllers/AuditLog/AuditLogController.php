<?php

namespace App\Http\Controllers\AuditLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $logs = DB::table("activity_log")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $props = json_decode($item->properties);
                // Creamos una propiedad nueva al primer nivel del objeto
                $item->autor_nombre = $props->autor ?? 'N/A';
               // $item->properties = $props; // Mantenemos el original por si acaso
        
                return $item;
            });

        /* return response()->json($logs); */

        return Inertia::render('AuditLog/Index', [
            'logs' => $logs,
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstacionesAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-estacion|crear-estacion|editar-estacion|borrar-estacion', ['only' => ['index']]);
        $this->middleware('permission:crear-estacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-estacion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-estacion', ['only' => ['destroy']]);
    }

    public function index()
    {
        //
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

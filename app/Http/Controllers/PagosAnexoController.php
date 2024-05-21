<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagosAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-pagos|crear-pagos|editar-pagos|borrar-pagos', ['only' => ['index']]);
        $this->middleware('permission:crear-pagos', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-pagos', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-pagos', ['only' => ['destroy']]);
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

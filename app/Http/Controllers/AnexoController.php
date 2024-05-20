<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnexoController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-anexo|crear-anexo|editar-anexo|borrar-anexo', ['only' => ['index']]);
        $this->middleware('permission:crear-anexo', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-anexo', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-anexo', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
       
        return view('armonia.anexo.index');
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

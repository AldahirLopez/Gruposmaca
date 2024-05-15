<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArchivosOp;
use Illuminate\Http\Request;

class ArchivosDicController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-archivos|crear-archivos|editar-archivos|borrar-archivos', ['only' => ['index']]);
        $this->middleware('permission:crear-archivos', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-archivos', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-archivos', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el ID del dictamen de la URL
        $dictamen_id = $request->dictamen_id;

        // Obtener los archivos relacionados con el dictamen y el usuario logueado
        $archivos = ArchivosOp::where('numdicop_id', $dictamen_id)
            ->paginate(5);

        // Pasar el usuario, los archivos y el ID del dictamen a la vista
        return view('armonia.archivos.index', [
            'archivos' => $archivos,
            'dictamen_id' => $dictamen_id,
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

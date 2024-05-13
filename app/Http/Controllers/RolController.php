<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Spatie
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\DB;

class RolController extends Controller
{

    function __construct()
    {
        $this -> middleware('permission:ver-rol|crear-rol|editar-rol|borrar-rol',['only' => ['index'] ]);
        $this -> middleware('permission:crear-rol', ['only' => ['create','store'] ]);
        $this -> middleware('permission:editar-rol',['only' => ['edit', 'update'] ]);
        $this -> middleware('permission:borrar-rol',['only' => ['destroy'] ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(5);
        return view('roles.index', compact('roles'));
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

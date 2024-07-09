<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth; // Importa la clase Auth

use Illuminate\Support\Carbon;

class OperacionController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-operacion|crear-operacion|editar-operacion|borrar-operacion', ['only' => ['index']]);
        $this->middleware('permission:crear-operacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-operacion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-operacion', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pasar los dictámenes a la vista
        return view('armonia.operacion.index'   );
    }

    
}

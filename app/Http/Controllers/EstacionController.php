<?php

namespace App\Http\Controllers;

use App\Models\Documento_Estacion;
use App\Models\Estacion;
use App\Http\Controllers\Controller;
use App\Models\Direccion;
use App\Models\EstacionDireccion;
use App\Models\Estados\Estados;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class EstacionController extends Controller
{

    protected $connection = 'segunda_db';

    public function seleccionestacion()
    {

        return view('armonia.estacion.seleccion');
    }
    public function estacion_usuario()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Obtener la lista de estados (asumiendo que se necesita en la vista)
        $estados = Estados::where('id_country', 42)->get();
        // Inicializar la variable para almacenar las estaciones
        $estaciones = [];

        if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Mostrar todas las estaciones si el usuario es administrador o auditor
            $estaciones = Estacion::all();
        } else {
            $estacionesDirectas = Estacion::where('usuario_id', $usuario->id)->get();
            // Inicializar una colección para las estaciones relacionadas
            $estacionesRelacionadas = collect();

            // Verificar si el usuario no es administrador para buscar relaciones
            if (!$usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                // Obtener las relaciones de usuario a estación
                $relaciones = Usuario_Estacion::where('usuario_id', $usuario->id)->get();

                // Recorrer las relaciones para obtener las estaciones relacionadas
                foreach ($relaciones as $relacion) {
                    // Obtener la estación relacionada y añadirla a la colección
                    $estacionRelacionada = Estacion::find($relacion->estacion_id);
                    if ($estacionRelacionada) {
                        $estacionesRelacionadas->push($estacionRelacionada);
                    }
                }
            }
            // Combinar estaciones directas y relacionadas y eliminar duplicados
            $estaciones = $estacionesDirectas->merge($estacionesRelacionadas)->unique('id');
        }

        // Pasar los datos a la vista
        return view('armonia.estacion.estaciones_usuario', compact('usuario', 'estados', 'estaciones'));
    }

    public function estacion_generales()
    {
        // Mostrar todas las estaciones si el usuario es administrador o auditor
        $estaciones = Estacion::all();


        // Pasar los datos a la vista
        return view('armonia.estacion.estaciones_generales', compact('estaciones'));
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $data = $request->validate([
                'id_usuario' => 'required|integer',
                'numestacion' => 'required|string|max:255',
                'razonsocial' => 'required|string|max:255',
                'rfc' => 'required|string|max:255',
                'repre' => 'required',
                'telefono' => 'nullable|string|max:255',
                'correo' => 'nullable|email|max:255',
                'estado' => 'required|string|max:255',
            ]);

            // Verificar si el número de estación ya existe en la segunda base de datos
            $exists = DB::connection('segunda_db')->table('estacion')
                ->where('num_estacion', $data['numestacion'])
                ->exists();

            if ($exists) {
                return redirect()->route('estaciones.usuario')->with('error', 'Error la estacion ya existe (numero de estacion).');
            } else {
                // Crear una nueva instancia del modelo Estacion en la base de datos principal
                $estacionServicio = new Estacion();

                // Asignar los datos validados al modelo
                $estacionServicio->num_estacion = $data['numestacion'];
                $estacionServicio->razon_social = $data['razonsocial'];
                $estacionServicio->rfc = $data['rfc'];
                $estacionServicio->telefono = $data['telefono'];
                $estacionServicio->correo_electronico = $data['correo'];
                $estacionServicio->nombre_representante_legal = $data['repre'];
                $estacionServicio->estado_republica_estacion = $data['estado'];
                $estacionServicio->usuario_id = $data['id_usuario'];

                // Guardar el objeto en la base de datos
                $estacionServicio->save();

                // Redirigir con un mensaje de éxito
                return redirect()->route('estaciones.usuario')->with('success', 'Estación agregada exitosamente');
            }
        } catch (\Exception $e) {
            // Captura cualquier excepción y muestra el mensaje
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al intentar guardar la estación.']);
        }
    }


    public function destroy($id)
    {
        try {
            $estacion = Estacion::findOrFail($id);
            $estacion->delete();

            return redirect()->route('estaciones.usuario')->with('success', 'Estación eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('estaciones.usuario')->with('error', 'Error al eliminar la estación.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'numestacion' => 'required',
            'razonsocial' => 'required',
            'rfc' => 'required',
            'domicilio_fiscal' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'repre' => 'required',
            'domicilio_estacion' => 'required',
            'estado' => 'required'
        ]);

        try {
            $estacion = Estacion::findOrFail($id);
            $estacion->num_estacion = $request->numestacion;
            $estacion->razon_social = $request->razonsocial;
            $estacion->rfc = $request->rfc;
            $estacion->domicilio_fiscal = $request->domicilio_fiscal;
            $estacion->telefono = $request->telefono;
            $estacion->correo_electronico = $request->correo;
            $estacion->nombre_representante_legal = $request->repre;
            $estacion->domicilio_estacion_servicio = $request->domicilio_estacion;
            $estacion->estado_republica_estacion = $request->estado;
            $estacion->save();

            return redirect()->route('estaciones.usuario')->with('success', 'Estación actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('estaciones.usuario')->with('error', 'Error al actualizar la estación.');
        }
    }

    public function storedocumentoestacion(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'rutadoc_estacion' => 'required|file',
            'estacion_id' => 'required|integer',
            'usuario_id' => 'required|integer',
            'razon_social' => 'required|string|max:255',
        ]);

        try {
            $documento = new Documento_Estacion();

            // Si se sube un archivo, guardarlo
            if ($request->hasFile('rutadoc_estacion')) {
                // Obtener el archivo subido y el nombre especificado por el usuario
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['nombre'] . '.' . $archivoSubido->getClientOriginalExtension(); // Nombre personalizado con extensión original

                // Definir la carpeta de destino dentro de 'public/storage'
                $razonSocial = str_replace([' ', '.'], '_', $data['razon_social']); // Eliminar espacios y puntos en la razón social
                $customFolderPath = "armonia/estaciones/{$razonSocial}";

                // Verificar si la carpeta principal existe, si no, crearla
                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                // Guardar el archivo en el sistema de archivos con el nombre personalizado
                $rutaArchivo = $archivoSubido->storeAs(
                    "public/{$customFolderPath}",
                    $nombreArchivoPersonalizado
                );

                // Actualizar la ruta del archivo en la base de datos
                $documento->rutadoc_estacion = str_replace('public/', '', $customFolderPath);
            }

            $documento->estacion_id = $data['estacion_id'];
            $documento->usuario_id = $data['usuario_id'];
            $documento->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('estaciones.usuario')->with('success', 'Documento agregado exitosamente.');
        } catch (\Exception $e) {
            // Capturar y manejar cualquier excepción
            return redirect()->route('estaciones.usuario')->with('error', 'Error al agregar el documento.');
        }
    }

    public function verDirecciones($id)
    {
        // Encuentra la estación por su ID
        $estacion = Estacion::findOrFail($id);

        // Obtener las direcciones asociadas a la estación desde la tabla de pivote
        $estacionDirecciones = EstacionDireccion::where('id_estacion', $id)->get();

        // Obtener los IDs de las direcciones
        $direccionIds = $estacionDirecciones->pluck('id_direccion');

        // Obtener las direcciones completas desde la tabla de direcciones
        $direcciones = Direccion::whereIn('id', $direccionIds)->get();

        // Separar las direcciones por tipo
        $direccionFiscal = $direcciones->firstWhere('tipo', 'Fiscal');
        $direccionEstacion = $direcciones->firstWhere('tipo', 'Estacion');

        // Pasar los datos a la vista
        return view('armonia.estacion.direcciones_estacion', [
            'estacion' => $estacion,
            'direccionFiscal' => $direccionFiscal,
            'direccionEstacion' => $direccionEstacion
        ]);
    }



    public function guardarDireccion(Request $request)
    {
        // Validación inicial
        $request->validate([
            'direccionSelect' => 'required|in:fiscal,estacion',
        ]);

        // Determina el tipo de dirección y realiza la validación y el guardado correspondientes
        $tipoDireccion = $request->input('direccionSelect');
        $campos = [
            'fiscal' => [
                'calle_fiscal' => 'required|max:255',
                'numero_ext_fiscal' => 'required|max:10',
                'numero_int_fiscal' => 'nullable|max:10',
                'colonia_fiscal' => 'required|max:255',
                'codigo_postal_fiscal' => 'required',
                'municipio_id_fiscal' => 'required',
                'localidad_fiscal' => 'required',
                'entidad_federativa_id_fiscal' => 'required',
            ],
            'estacion' => [
                'calle_estacion' => 'required|max:255',
                'numero_ext_estacion' => 'required|max:10',
                'numero_int_estacion' => 'nullable|max:10',
                'colonia_estacion' => 'required|max:255',
                'codigo_postal_estacion' => 'required',
                'municipio_id_estacion' => 'required',
                'localidad_estacion' => 'required',
                'entidad_federativa_id_estacion' => 'required',
            ],
        ];

        // Validación específica para el tipo de dirección
        $request->validate($campos[$tipoDireccion]);

        // Capitaliza el tipo de dirección
        $tipoDireccionCapitalizado = ucfirst($tipoDireccion);

        // Crear nueva dirección
        $direccion = new Direccion();
        $direccion->tipo = $tipoDireccionCapitalizado;
        $direccion->calle = $request->input("calle_{$tipoDireccion}");
        $direccion->numero = $request->input("numero_ext_{$tipoDireccion}");
        $direccion->numero_interior = $request->input("numero_int_{$tipoDireccion}");
        $direccion->colonia = $request->input("colonia_{$tipoDireccion}");
        $direccion->codigo_postal = $request->input("codigo_postal_{$tipoDireccion}");
        $direccion->localidad = $request->input("localidad_{$tipoDireccion}");
        $direccion->municipio = $request->input("municipio_id_{$tipoDireccion}");
        $direccion->entidad_federativa = $request->input("entidad_federativa_id_{$tipoDireccion}");
        $direccion->save();

        // Crear relación con la estación
        $estacionDireccion = new EstacionDireccion();
        $estacionDireccion->id_estacion = $request->input('estacion_id');
        $estacionDireccion->id_direccion = $direccion->id;
        $estacionDireccion->save();

        return redirect()->back()->with('success', 'Dirección guardada exitosamente.');
    }

    public function ObtenerDatosDireccion($id)
    {
        try {
            // Buscar la dirección por ID
            $direccion = Direccion::findOrFail($id);

            // Retornar los datos de la dirección en formato JSON
            return response()->json([
                'id' => $direccion->id,
                'calle' => $direccion->calle,
                'numero_ext' => $direccion->numero,
                'numero_int' => $direccion->numero_interior,
                'colonia' => $direccion->colonia,
                'codigo_postal' => $direccion->codigo_postal,
                'municipio' => $direccion->municipio,
                'localidad' => $direccion->localidad,
                'entidad_federativa' => $direccion->entidad_federativa
            ]);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['error' => 'No se pudo encontrar la dirección.'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Documento_Estacion;
use App\Models\Estacion;
use App\Http\Controllers\Controller;
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

        // Lista de estados
        $estados = [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Coahuila',
            'Colima',
            'Ciudad de México',
            'Durango',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'México',
            'Michoacán',
            'Morelos',
            'Nayarit',
            'Nuevo León',
            'Oaxaca',
            'Puebla',
            'Querétaro',
            'Quintana Roo',
            'San Luis Potosí',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatán',
            'Zacatecas'
        ];

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
                'domicilio_fiscal' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:255',
                'correo' => 'nullable|email|max:255',
                'domicilio_estacion' => 'required|string|max:255',
                'estado' => 'required|string|max:255',
            ]);

            // Crear una nueva instancia del modelo Estacion
            $estacionServicio = new Estacion();

            // Asignar los datos validados al modelo
            $estacionServicio->num_estacion = $data['numestacion'];
            $estacionServicio->razon_social = $data['razonsocial'];
            $estacionServicio->rfc = $data['rfc'];
            $estacionServicio->domicilio_fiscal = $data['domicilio_fiscal'];
            $estacionServicio->telefono = $data['telefono'];
            $estacionServicio->correo_electronico = $data['correo'];
            $estacionServicio->domicilio_estacion_servicio = $data['domicilio_estacion'];
            $estacionServicio->estado_republica_estacion = $data['estado'];
            $estacionServicio->usuario_id = $data['id_usuario'];

            // Definir la carpeta de destino dentro de 'public/storage'
            $razonSocial = str_replace([' ', '.'], '_', $data['razonsocial']); // Eliminar espacios y puntos en la razón social
            $customFolderPath = "armonia/estaciones/{$razonSocial}";

            // Crear la carpeta si no existe
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Guardar el objeto en la base de datos
            $estacionServicio->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('estacion.index')->with('success', 'Estación agregada exitosamente');
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

            return redirect()->route('estacion.index')->with('success', 'Estación eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('estacion.index')->with('error', 'Error al eliminar la estación.');
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
            $estacion->domicilio_estacion_servicio = $request->domicilio_estacion;
            $estacion->estado_republica_estacion = $request->estado;
            $estacion->save();

            return redirect()->route('estacion.index')->with('success', 'Estación actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('estacion.index')->with('error', 'Error al actualizar la estación.');
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
            return redirect()->route('estacion.index')->with('success', 'Documento agregado exitosamente.');
        } catch (\Exception $e) {
            // Capturar y manejar cualquier excepción
            return redirect()->route('estacion.index')->with('error', 'Error al agregar el documento.');
        }
    }
}

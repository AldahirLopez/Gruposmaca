<?php

use App\Http\Controllers\AdminController;

//Servicio Anexo Vista general 
use App\Http\Controllers\Servicio_Anexo_30Controller;

//Servicio Inspector Anexo 30
use App\Http\Controllers\Servicio_Inspector_Anexo_30Controller;

//Datos del servicio anexo 30 expediente
use App\Http\Controllers\Datos_Servicio_Inspector_Anexo_30Controller;


use App\Http\Controllers\ApprovalController;

use App\Http\Controllers\ArchivosDicController;
use App\Http\Controllers\Documentacion_EstacionController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\FormatosHistorialController;
use App\Http\Controllers\Usuario_EstacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\PagosAnexoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ServicioOperacionController;
use App\Http\Controllers\TramitesEmaController;
use App\Http\Controllers\FormatosController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::group(['middleware' => ['auth']], function () {

    Route::view('/', 'home')->name('home');
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);



    //Servicio Anexo Vista general 
    Route::resource('servicio_anexo_30', Servicio_Anexo_30Controller::class);

    Route::delete('/approve-servicio-deletion/{id}', 'App\Http\Controllers\ApprovalController@approveServicioDeletion')
        ->name('approve.servicio.deletion');

    Route::post('/approval/{id}/cancel', [ApprovalController::class, 'cancelDeletion'])->name('approval.cancel');
    

    //Generar PDF cotizacion anexo 30 
    Route::post('/pdf_cotizacion', [Servicio_Anexo_30Controller::class, 'generarpdfcotizacion'])->name('pdf.cotizacion');

    Route::get('/descargar-cotizacion-ajax', [Servicio_Anexo_30Controller::class, 'descargarCotizacionAjax'])->name('descargar.cotizacion.ajax');


    //Generar PDF cotizacion operacion
    
    Route::post('/pdf_cotizacion_operacion', [OperacionController::class, 'generarpdfcotizacion'])->name('pdf.cotizacion.operacion');
    
   



    //Servicios Para Aprobar
    Route::get('/apro_anexo', [Servicio_Anexo_30Controller::class, 'apro_servicio_anexo'])->name('apro.anexo');
    Route::get('servicio_anexo/apro/{id}', [Servicio_Anexo_30Controller::class, 'apro'])->name('servicio_anexo.apro');
    
    //Servicios para aprobar de operacion
    Route::get('/apro_operacion_mantenimiento', [OperacionController::class, 'apro_servicio_operacion_mantenimiento'])->name('apro.operacion');
    Route::get('/operacion_mantenimiento/apro/{id}', [OperacionController::class, 'apro'])->name('servicio_operacion.apro');


    //Servicio Inspector Anexo 30
    Route::resource('servicio_inspector_anexo_30', Servicio_Inspector_Anexo_30Controller::class);
    Route::get('/obtener-servicios', [Servicio_Inspector_Anexo_30Controller::class, 'obtenerServicios'])->name('servicio_inspector_anexo_30.obtenerServicios');

    


    //Servicio operacion y mantenimiento

    Route::get('/obtener-servicios-operacion', [OperacionController::class, 'obtenerServicios'])->name('servicio-operacion.obtenerServicios');

    //Ruta para cada inspector para su expediente
    Route::get('/expediente/anexo30/{slug}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'ExpedienteInspectorAnexo30'])->name('expediente.anexo30');
    //Ruta para cada inspector para su expediente en servicios de operacion y mantenimiento
    Route::get('/expediente/operacion/{slug}', [OperacionController::class, 'ExpedienteInspectorOperacion'])->name('expediente.operacion');

    //Documentacion Por servicio
    Route::get('/documentacion-anexo', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'DocumentacionAnexo'])->name('documentacion_anexo');

    Route::post('/documentacion-anexo/store', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'storeanexo'])->name('documentacion_anexo.store');
   
    //Docuemtnacion para servicio de operacion 

    Route::get('/documentacion-operacion', [OperacionController::class, 'DocumentacionOperacion'])->name('documentacion_operacion');

    Route::post('/documentacion-operacion-archivos/store', [OperacionController::class, 'storeDocumenctacionOperacion'])->name('documentacion_operacion_archivos.store');

    //Descargar la documentacion de operacion
    Route::post('/descargar-documentacion-operacion/{documento}', [OperacionController::class,'descargardocumentacion'])->name('descargar.documentos.operacion');

 

    Route::post('/generate-word', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'generateWord'])->name('generate.word');

    Route::post('/generate-word-operacion', [OperacionController::class, 'generateWord'])->name('generate.word.operacion');
    
    Route::post('/generate-expedientes-operacion', [OperacionController::class, 'generarExpedientesOperacion'])->name('generate.expedientes.operacion');



    Route::get('/list-generated-files/{nomenclatura}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'listGeneratedFiles']);
   
    Route::get('/list-generated-files-operacion/{nomenclatura}', [OperacionController::class, 'listGeneratedFiles']);


    Route::get('/api/consulta/{id}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'validarDatosExpediente']); 
    
    Route::get('/api/consulta/operacion/{id}', [OperacionController::class, 'validarDatosExpediente']);
   


    Route::get('/descargar-archivo/{archivo}/{estacion}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'descargarWord'])
        ->name('descargar.archivo');


    Route::get('/descargar-archivo-operacion/{archivo}/{estacion}', [OperacionController::class, 'descargarWord'])
        ->name('descargar.archivo.operacion');


    Route::get('/obtener-datos-estacion/{id}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'obtenerDatosEstacion']);

    // En web.php (o routes.php)
    Route::post('/guardar-dictamenes', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'guardarDictamenes'])->name('guardar.dictamenes');


    //PARTE DE PAGOS DE SERVICIO DE OPERACION
    Route::post('/pago-operacion/store', [OperacionController::class, 'storePago'])->name('pago_operacion.store');




    // Route::get('/listas/anexo30/{slug}', 'ListasInspeccionController@verAnexo30')->name('listas.anexo30');
    //  Route::get('/archivos/{slug}', 'ArchivosController@index')->name('archivos.index');

    //PARTE DE PAGOS EN LA VISTA DE ADMINISTRADO PARA LAS FACTURAS

    Route::get('/pagos', [OperacionController::class, 'pagos'])->name('pagos.index');
    Route::get('/descarga-pago', [OperacionController::class, 'descargarPago'])->name('descargar.pago.operacion');
    Route::post('/factura-operacion/store', [OperacionController::class, 'storeFactura'])->name('factura_operacion.store');
    
    Route::get('/descarga-factura', [OperacionController::class, 'descargarFactura'])->name('descargar.factura.operacion');



    Route::resource('estacion', EstacionController::class);

    Route::get('/seleccion-estacion', [EstacionController::class, 'seleccionestacion'])->name('estacion.selecccion');

    Route::get('/estaciones_usuario', [EstacionController::class, 'estacion_usuario'])->name('estaciones.usuario');

    Route::get('/estaciones_generales', [EstacionController::class, 'estacion_generales'])->name('estaciones.generales');

    Route::resource('documentacion_estacion', Documentacion_EstacionController::class);

    Route::post('/documentacion-operacion/store', [Documentacion_EstacionController::class, 'storeoperacion'])->name('documentacion_operacion.store');


    Route::resource('usuario_estacion', Usuario_EstacionController::class);
    Route::post('/asignar-usuarios', [Usuario_EstacionController::class, 'AsignarEstacion'])->name('asignar-usuarios.AsignarEstacion');



    Route::resource('operacion', OperacionController::class);
    
    Route::resource('archivos', ArchivosDicController::class);
    Route::resource('notificaciones', ApprovalController::class);


    Route::resource('servicio_operacion', ServicioOperacionController::class);
    Route::resource('pago_anexo', PagosAnexoController::class);
    //Route::resource('estacion_anexo', EstacionesAnexoController::class);
    Route::resource('ema', TramitesEmaController::class);
    Route::resource('historialformatos', FormatosHistorialController::class);
    Route::get('/armonia/formatos/anexo30', [FormatosController::class, 'index'])->name('historialformatos.anexo30.index');

    Route::post('/filtrar-archivos', [FormatosHistorialController::class, 'filtrarArchivos'])->name('filtrar.archivos');



    Route::get('/armonia/formatos/anexo30', [FormatosController::class, 'listarAnexo30'])->name('listar.anexo30');
    Route::get('/armonia/formatos/anexo30/nuevo', [FormatosController::class, 'create'])->name('archivosanexo.create');
    Route::get('/armonia/formatos/anexo30/editar/{id}', [FormatosController::class, 'edit'])->name('archivos.anexo.edit');

    Route::post('/armonia/formatos/anexo30/save/{id?}', [FormatosController::class, 'save'])->name('archivos.save');
    Route::delete('/armonia/formatos/anexo30/destroy/{id}', [FormatosController::class, 'destroy'])->name('archivos.anexo.destroy');


    Route::get('/fetch-notifications', [NotificationController::class, 'fetchNotifications']);

    // Rutas para las notificaciones
    Route::get('/approval/{id}', [ApprovalController::class, 'show'])->name('approval.show');
    
    Route::delete('/approve-dictamen-deletion/{id}', 'App\Http\Controllers\ApprovalController@approveServicioOperacionDeletion')
        ->name('approve.dictamen.deletion');


    Route::get('/aprobacion_servicios', [NotificationController::class, 'notificacionesAprobacion'])->name('aprobacion.servicios');  





    //ruta cambio de contraseña
    Route::get('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'showChangePasswordForm'])->name('usuarios.showchangepasswordform');

    Route::post('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'updatePassword'])->name('usuarios.cambiar-contrasena');





});

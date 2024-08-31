<?php

use App\Http\Controllers\AdminController;

//Servicio Anexo Vista general 
use App\Http\Controllers\DictamenDisenoController;
use App\Http\Controllers\Servicio_Anexo_30Controller;
use App\Http\Controllers\ListasAnexo30;

//Servicio Inspector Anexo 30
use App\Http\Controllers\Servicio_Inspector_Anexo_30Controller;

//Datos del servicio anexo 30 expediente
use App\Http\Controllers\Datos_Servicio_Inspector_Anexo_30Controller;


use App\Http\Controllers\ApprovalController;

use App\Http\Controllers\ArchivosDicController;
use App\Http\Controllers\DIctamenConstruccionController;
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
use App\Http\Controllers\DictamenDatosController;
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

    Route::get('/pagos-anexo', [Servicio_Anexo_30Controller::class, 'pagosAnexo'])->name('pagosAnexo.index');


    //PARTE DE PAGOS DE Anexo
    Route::post('/pago-anexo/store', [Servicio_Anexo_30Controller::class, 'storePagoAnexo'])->name('pago_anexo.store');

    //PARTE DE PAGOS EN LA VISTA DE ADMINISTRADO PARA LAS FACTURAS

    Route::get('/descarga-pago-anexo', [Servicio_Anexo_30Controller::class, 'descargarPagoAnexo'])->name('descargar.pago.anexo');
    Route::post('/factura-anexo/store', [Servicio_Anexo_30Controller::class, 'storeFacturaAnexo'])->name('factura_anexo.store');

    Route::get('/descarga-factura-anexo', [Servicio_Anexo_30Controller::class, 'descargarFacturaAnexo'])->name('descargar.factura.anexo');

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


    Route::post('/documentacion-anexo/generate-medicion', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'generarSistemaMedicion'])->name('documentacion_anexo_medicion.generate');


    //Docuemtnacion para servicio de operacion 

    Route::get('/documentacion-operacion', [OperacionController::class, 'DocumentacionOperacion'])->name('documentacion_operacion');

    Route::post('/documentacion-operacion-archivos/store', [OperacionController::class, 'storeDocumenctacionOperacion'])->name('documentacion_operacion_archivos.store');

    //Descargar la documentacion de operacion
    Route::post('/descargar-documentacion-operacion/{documento}', [OperacionController::class, 'descargardocumentacion'])->name('descargar.documentos.operacion');



    Route::post('/generate-word', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'generateWord'])->name('generate.word');

    Route::post('/generate-word-operacion', [OperacionController::class, 'generateWord'])->name('generate.word.operacion');

    Route::post('/generate-expedientes-operacion', [OperacionController::class, 'generarExpedientesOperacion'])->name('generate.expedientes.operacion');

    Route::post('/generate-comprobante-operacion', [OperacionController::class, 'generarComprobanteTraslado'])->name('generate.comprobante.operacion');

    Route::post('/generate-acta-operacion', [OperacionController::class, 'generarActaVerificacion'])->name('generate.acta.operacion');


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
    Route::post('/guardar-dictamenes-informatico', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'guardarDictamenesInformatico'])->name('guardar.dictamenesinformatico');

    Route::post('/guardar-dictamenes-medicion', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'guardarDictamenesMedicion'])->name('guardar.dictamenesmedicion');

    Route::post('/guardar-certificado', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'guardarCertificado'])->name('guardar.certificado');
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

    Route::post('/guardar-direccion', [EstacionController::class, 'guardarDireccion'])->name('guardar.direccion');

    Route::get('/estacion/{id}/direcciones', [EstacionController::class, 'verDirecciones'])->name('estacion.direcciones');


    Route::post('/direccion/store', [EstacionController::class, 'storedirecciones'])->name('direccion.store');

    Route::get('/direccion/{id}', [EstacionController::class, 'ObtenerDatosDireccion'])->name('direccion.obtenerdatos');

    Route::put('estacion/{id}/direcciones', [EstacionController::class, 'updateDireccion'])->name('direcciones.update');




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
    //Route::resource('pago_anexo', PagosAnexoController::class);
    //Route::resource('estacion_anexo', EstacionesAnexoController::class);
    Route::resource('ema', TramitesEmaController::class);
    Route::get('/historial-formatos/{tipo_doc?}', [FormatosHistorialController::class, 'index'])->name('historialformatos.index');
    Route::delete('/historialformatos/{id}', [FormatosHistorialController::class, 'destroy'])->name('historialformatos.destroy');



    Route::post('/filtrar-archivos', [FormatosHistorialController::class, 'filtrarArchivos'])->name('filtrar.archivos');



    //Rutas de formatos
    Route::get('/listar/anexo30', [FormatosController::class, 'ListarAnexo30'])->name('listar.anexo30');
    Route::get('/listar/ope', [FormatosController::class, 'ListarOpe'])->name('listar.ope');
    Route::get('/listar/diseno', [FormatosController::class, 'ListarDiseno'])->name('listar.diseno');
    Route::get('/listar/const', [FormatosController::class, 'ListarConst'])->name('listar.const');



    //
    Route::get('/armonia/formatos/nuevo', [FormatosController::class, 'create'])->name('archivosanexo.create');
    Route::get('/armonia/formatos/editar/{id}', [FormatosController::class, 'edit'])->name('archivos.edit');

    Route::post('/armonia/formatos/save/{id?}', [FormatosController::class, 'save'])->name('archivos.save');
    Route::delete('/armonia/formatos/destroy/{id}', [FormatosController::class, 'destroy'])->name('archivos.destroy');


    Route::get('/fetch-notifications', [NotificationController::class, 'fetchNotifications']);

    // Rutas para las notificaciones
    Route::get('/approval/{id}', [ApprovalController::class, 'show'])->name('approval.show'); 

    // Rutas para las notificaciones Eliminacion
    Route::get('/approval-servicio-operacion/{id}', [ApprovalController::class, 'showOperacion'])->name('approval.showOperacion'); 
    Route::get('/approval-servicio-anexo/{id}', [ApprovalController::class, 'showAnexo'])->name('approval.showAnexo'); 

    Route::delete('/approve-dictamen-deletion/{id}', 'App\Http\Controllers\ApprovalController@approveServicioOperacionDeletion')
        ->name('approve.dictamen.deletion');


    Route::get('/aprobacion_servicios', [NotificationController::class, 'notificacionesAprobacion'])->name('aprobacion.servicios');





    //ruta cambio de contraseña
    Route::get('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'showChangePasswordForm'])->name('usuarios.showchangepasswordform');

    Route::post('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'updatePassword'])->name('usuarios.cambiar-contrasena');

    Route::resource('diseño', DictamenDisenoController::class);

    Route::get('diseño/{id}/download', [DictamenDisenoController::class, 'download'])->name('diseño.download');

    Route::post('diseño/{id}/subir-sustento', [DictamenDisenoController::class, 'subirSustento'])->name('diseño.subirSustento');


    //Dictamenes Construccion 

    Route::resource('construccion', DIctamenConstruccionController::class);

    Route::get('construccion/{id}/download', [DIctamenConstruccionController::class, 'download'])->name('construccion.download');

    Route::post('construccion/{id}/subir-sustento', [DIctamenConstruccionController::class, 'subirSustento'])->name('construccion.subirSustento');


    //Dictamen de datos

    Route::get('/dictamen-datos', [DictamenDatosController::class, 'create'])->name('dictamen_datos.create');

    Route::post('/dictamen-datos', [DictamenDatosController::class, 'store'])->name('dictamen_datos.store');



    //Documentacion view main 
    Route::get('/documentacion-anexo', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'documentacion'])->name('documentacion_anexo');


    //LISTA DE DOCUMENTOS GENERALES REQUERIDOS ANEZO 30 Y 31 RMF 2024 

    Route::get('/documentacion-anexo-general', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'documentosGenerales'])->name('documentacion_anexo_general');
    Route::post('/documentacion-anexo-general/store', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'storeDocumentosGenerales'])->name('documentacion_anexo_general.store');


    //REQUSISITOS PARA LA APROBACIÓN DEL SISTEMA INFORMATICO ANEXOS 30 Y 31
    Route::get('/documentacion-anexo-informaticos', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'documentosSistemaInformatico'])->name('documentacion_anexo_informaticos');
    Route::post('/documentacion-anexo-informaticos/store', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'storeDocumentosSistemaInformatico'])->name('documentacion_anexo_informatico.store');


    //LISTA DE DOCUMENTOS REQUERIDOS SITEMAS DE MEDICION ANEXO 30 y 31 RMF 2024
    Route::get('/documentacion-anexo-medicion', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'DocumentacionAnexo'])->name('documentacion_anexo_medicion');
    Route::post('/documentacion-anexo-medicion/store', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'storeanexo'])->name('documentacion_anexo_medicion.store');

    //DURANTE LA INSPECCIÓN DE SOLICITARAN LAS SIGUIENTES EVIDENCIAS AL MOMENTO. 

    Route::get('/documentacion-anexo-inspeccion', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'documentosInspeccion'])->name('documentacion_anexo_inspeccion');
    Route::post('/documentacion-anexo-inspeccion/store', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'storedocumentosInspeccion'])->name('documentacion_anexo_inspeccion.store');



    //LISTAS DE INSPECCION
    Route::get('/lista-inspeccion-anexo/{id_servicio?}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'ListaInspeccion'])->name('lista_inspeccion_anexo');

    Route::get('/form/{type}', [ListasAnexo30::class, 'loadForm']);

    
});

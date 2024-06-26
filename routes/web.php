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
use App\Http\Controllers\EstacionesAnexoController;
use App\Http\Controllers\FormatoController;
use App\Http\Controllers\FormatosHistorialController;
use App\Models\Cotizacion_Servicio_Anexo30;
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

    //Servicios Para Aprobar
    Route::get('/apro_anexo', [Servicio_Anexo_30Controller::class, 'apro_servicio_anexo'])->name('apro.anexo');

    Route::get('servicio_anexo/apro/{id}', [Servicio_Anexo_30Controller::class, 'apro'])->name('servicio_anexo.apro');

    //Servicio Inspector Anexo 30
    Route::resource('servicio_inspector_anexo_30', Servicio_Inspector_Anexo_30Controller::class);
    Route::get('/obtener-servicios', [Servicio_Inspector_Anexo_30Controller::class, 'obtenerServicios'])->name('servicio_inspector_anexo_30.obtenerServicios');


    //Ruta para cada inspector para su expediente
    Route::get('/expediente/anexo30/{slug}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'ExpedienteInspectorAnexo30'])->name('expediente.anexo30');

    Route::post('/generate-word', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'generateWord'])->name('generate.word');

    Route::get('/list-generated-files/{nomenclatura}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'listGeneratedFiles']);

    Route::get('/api/consulta/{id}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'validarDatosExpediente']);

    Route::get('/descargar-archivo/{archivo}/{estacion}', [Datos_Servicio_Inspector_Anexo_30Controller::class, 'descargarWord'])
        ->name('descargar.archivo');





    // Route::get('/listas/anexo30/{slug}', 'ListasInspeccionController@verAnexo30')->name('listas.anexo30');
    //  Route::get('/archivos/{slug}', 'ArchivosController@index')->name('archivos.index');










    Route::resource('operacion', OperacionController::class);
    Route::resource('archivos', ArchivosDicController::class);
    Route::resource('notificaciones', ApprovalController::class);


    Route::resource('servicio_operacion', ServicioOperacionController::class);
    Route::resource('pago_anexo', PagosAnexoController::class);
    Route::resource('estacion_anexo', EstacionesAnexoController::class);
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
    Route::delete('/approve-dictamen-deletion/{id}', 'App\Http\Controllers\ApprovalController@approveDictamenDeletion')
        ->name('approve.dictamen.deletion');





    //ruta cambio de contraseña
    Route::get('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'showChangePasswordForm'])->name('usuarios.showchangepasswordform');

    Route::post('/usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'updatePassword'])->name('usuarios.cambiar-contrasena');




});

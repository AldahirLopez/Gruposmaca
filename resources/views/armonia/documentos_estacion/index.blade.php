@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Documentación de la estación {{ $estacion->razon_social }} </h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('estacion.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i> Volver</a>

                            <!-- Botón que abre el modal para agregar nuevo documento -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarDocumentoModal-{{ $id }}">
                                Agregar Nuevo Documento
                            </button>

                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3" placeholder="Buscar estación...">
                        <table class="table table-striped" >
                            <thead style="text-align: center;">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descargar</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEstaciones">
                                @foreach($documentos as $documento)
                                <tr>
                                    <td>{{ $documento->nombre }}</td>
                                    <td> <center><a href="{{ $documento->ruta }}" class="btn btn-info" target="_blank"><i class="bi bi-download"></i></a></center></td>
                                    <td>
                                        <!-- Agregar formulario para eliminar documentos si es necesario -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para agregar documentos -->
<div class="modal fade" id="agregarDocumentoModal-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="agregarDocumentoLabel-{{ $id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #007bff; color: #ffffff;">
                <h5 class="modal-title" id="agregarDocumentoLabel-{{ $id }}">Agregar Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('documentacion_estacion.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre del Documento</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rutadoc_estacion">Ruta del Documento</label>
                                <input type="file" name="rutadoc_estacion" class="form-control" required>
                            </div>
                        </div>
                        <input type="hidden" name="estacion_id" value="{{ $id }}">
                        <input type="hidden" name="razon_social" value="{{ $estacion->razon_social}}">
                        <!-- No es necesario pasar 'razon_social' como input hidden -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="padding-top: 20px;">
                        <button type="submit" class="btn btn-primary btn-agregar">Agregar Documento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir jQuery, Popper.js y Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous" defer></script>
@endsection
@extends('layouts.app')

@section('content')
@can('Generar-documentacion-anexo_30')  
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Documentación del servicio
            {{ $servicio->nomenclatura }}
        </h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('servicio_inspector_anexo_30.index', ['id' => $servicio->id]) }}"
                                class="btn btn-danger"><i class="bi bi-arrow-return-left"></i> Volver</a>
                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3"
                            placeholder="Buscar estación...">
                        <table class="table table-striped">
                            <thead style="text-align: center;">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    @can('Generar-documentacion-anexo_30')
                                    <th scope="col">Agregar</th>
                                    @endcan
                                    <th scope="col">Descargar</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEstaciones">
                                @foreach($requiredDocuments as $doc)
                                                                <tr>
                                                                    <td>{{ $doc }}</td>
                                                                    @can('Generar-documentacion-anexo_30')
                                                                    <td>
                                                                        <!-- Botón que abre el modal para agregar nuevo documento -->
                                                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                                                            data-target="#agregarDocumentoModal-{{ Str::slug($doc) }}">
                                                                            <i class="bi bi-upload"></i> Agregar
                                                                        </button>
                                                                    </td>
                                                                    @endcan
                                                                    <td>
                                                                        @php
                                                                            $docExists = false;
                                                                            $docUrl = '';
                                                                            foreach ($documentos as $documento) {
                                                                                if ($documento->nombre === $doc) {
                                                                                    $docExists = true;
                                                                                    $docUrl = $documento->ruta;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        @if($docExists)
                                                                            <a href="{{ $docUrl }}" class="btn btn-info" target="_blank"><i
                                                                                    class="bi bi-download"></i> Descargar</a>
                                                                        @else
                                                                            <span>No disponible</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @can('Generar-documentacion-operacion')        
       
                                                                <!-- Modal para agregar documento -->
                                                                <div class="modal fade" id="agregarDocumentoModal-{{ Str::slug($doc) }}" tabindex="-1"
                                                                    role="dialog" aria-labelledby="agregarDocumentoLabel-{{ Str::slug($doc) }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header"
                                                                                style="background-color: #007bff; color: #ffffff;">
                                                                                <h5 class="modal-title"
                                                                                    id="agregarDocumentoLabel-{{ Str::slug($doc) }}">
                                                                                    Agregar Documento: {{ $doc }}</h5>
                                                                                <button type="button" class="btn-close btn-close-white"
                                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="{{ route('documentacion_anexo.store') }}" 
                                                                                    method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <label for="rutadoc_estacion">Seleccionar Archivo</label>
                                                                                        <input type="file" name="rutadoc_estacion" class="form-control"
                                                                                            required>
                                                                                    </div>
                                                                                    <input type="hidden" name="servicio_id" value="{{ $id }}">
                                                                                    <input type="hidden" name="nomenclatura"
                                                                                        value="{{ $servicio->nomenclatura }}">
                                                                                    <input type="hidden" name="nombre" value="{{ $doc }}">
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary"
                                                                                            data-dismiss="modal">Cerrar</button>
                                                                                        <button type="submit" class="btn btn-primary">Agregar
                                                                                            Documento</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endcan  
                                                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Incluir jQuery, Popper.js y Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"
    defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"
    defer></script>
@endcan
@endsection
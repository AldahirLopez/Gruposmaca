@extends('layouts.app')

@section('content')
@can('Generar-documentacion-anexo_30')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">LISTA DE DOCUMENTOS GENERALES REQUERIDOS ANEZO 30 Y 31 RMF 2024
                {{ $servicio->nomenclatura }}
            </h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div style="margin-top: 15px;">
                                <form action="{{ route('documentacion_anexo') }}" method="GET" style="display:inline;">
                                            <input type="hidden" name="id" value="{{ $servicio->id }}">
                                            <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-arrow-return-left"></i>Volver
                                            </button>
                                                                
                                </form>
                            </div>
                            <div style="margin-top: 15px;">
                              
                               
                            </div>


                            <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3"
                                placeholder="Buscar estación...">

                                <form action="{{route('documentacion_anexo_medicion.generate')}}" method="POST" class="text-end">
                                    @csrf
                                    <input type="hidden" name="nomenclatura" value="{{ $servicio->nomenclatura }}">
                                    <input type="hidden" name="servicio_id" value="{{ $id }}">
                                    <button  class="btn btn-info" type="submit">
                                    <i class="bi bi-download"></i> Sistema de medicion
                                    </button>
                                </form>


                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th scope="col">DESCRIPCIÓN</th>
                                        <th scope="col">CÓDIGO O REFERENCIA</th>
                                        <th scope="col">TIPO REFERENCIA</th>
                                        @can('Generar-documentacion-anexo_30')
                                            <th scope="col">Agregar</th>
                                        @endcan
                                        <th scope="col">Descargar</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaEstaciones">
                                    @foreach($requiredDocuments as $doc)
                                                                <tr>
                                                                    <td>{{ $doc['descripcion'] }}</td>
                                                                    <td>
                                                                        @php
                                                                            $docExists = false;
                                                                            $docReferencia = '';
                                                                            foreach ($documentos as $documento) {
                                                                                if ($documento->nombre === $doc['descripcion']) {
                                                                                    $docExists = true;
                                                                                    $docReferencia = $documento->referencia;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{ $docExists ? $docReferencia : 'No disponible' }}
                                                                    </td>
                                                                    <td>{{ $doc['tipo'] }}</td>
                                                                    @can('Generar-documentacion-anexo_30')
                                                                        <td>
                                                                            <!-- Botón que abre el modal para agregar nuevo documento -->
                                                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                                                data-target="#agregarDocumentoModal-{{ Str::slug($doc['descripcion']) }}">
                                                                                <i class="bi bi-upload"></i> Agregar
                                                                            </button>
                                                                        </td>
                                                                    @endcan
                                                                    <td>
                                                                        @php
                                                                            $docExists = false;
                                                                            $docUrl = '';
                                                                            foreach ($documentos as $documento) {
                                                                                if ($documento->nombre === $doc['descripcion']) {
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
                                                                    <div class="modal fade" id="agregarDocumentoModal-{{ Str::slug($doc['descripcion']) }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="agregarDocumentoLabel-{{ Str::slug($doc['descripcion']) }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header"
                                                                                    style="background-color: #007bff; color: #ffffff;">
                                                                                    <h5 class="modal-title"
                                                                                        id="agregarDocumentoLabel-{{ Str::slug($doc['descripcion']) }}">
                                                                                        Agregar Documento: {{ $doc['descripcion'] }}</h5>
                                                                                    <button type="button" class="btn-close btn-close-white"
                                                                                        data-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form action="{{ route('documentacion_anexo_general.store') }}" method="POST"
                                                                                        enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        <div class="form-group">
                                                                                            <label for="rutadoc_estacion">Seleccionar Archivo</label>
                                                                                            <input type="file" name="rutadoc_estacion" class="form-control"
                                                                                                required>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="referencia">Seleccionar Archivo</label>
                                                                                            <input type="text" name="referencia" class="form-control"
                                                                                                required>
                                                                                        </div>
                                                                                        <input type="hidden" name="servicio_id" value="{{ $id }}">

                                                                                        <input type="hidden" name="id_documento" value="{{ $doc['id'] }}">
                                                                                        
                                                                                        <input type="hidden" name="nomenclatura"
                                                                                            value="{{ $servicio->nomenclatura }}">
                                                                                            <input type="hidden" name="nombre" value="{{ $doc['descripcion'] }}">
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
                                                                @endcan                                 @endforeach
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
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header"> 
        <h3 class="page__heading">Pagos de Servicios de Operaciones y Mantenimiento </h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('operacion.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de servicio</th>
                                    <th scope="col">Observaciones</th>                                 
                                    <th scope="col">Pago</th>
                                    <th scope="col">Factura</th>
                                    <th scope="col">Estatus</th>                              
                                </tr>
                            </thead>
                            <tbody >
                                @forelse($pagos as $pago)
                                <tr>
                                    <td scope="row">{{$pago->servicio->nomenclatura }}</td>
                     
                                    <td>{{$pago->observaciones}}</td> 
                                                                                                    
                                    <td scope="row">
                                        <form action="{{ route('descargar.pago.operacion') }}" method="POST">
                                            @csrf
                                            <input type="text"  value="{{$pago->servicio->nomenclatura}}" style="display:none;" name="nomenclatura" >
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-file-pdf-fill"></i></button>
                                        </form>
                                                                                                                                         
                                    </td>


                                    <td scope="row"> 
                                                <button type="button" class="btn btn-success" data-toggle="modal"
                                                        data-target="#agregarDocumentoModal-{{$pago->servicio->nomenclatura }}">
                                                        <i class="bi bi-file-earmark-check-fill">Subir</i> 
                                                </button>                                      
                                        
                                    </td>

                                    <td scope="row"> 
                                        @if ($pago->estado_facturado == false)
                                            Pediente por facturar                                          
                                        @else   
                                                Facturado
                                        @endif                                                                              
                                    </td>
                                </tr>

                                        <!-- Modal para agregar documento -->
                                        <div class="modal fade" id="agregarDocumentoModal-{{$pago->servicio->nomenclatura }}" tabindex="-1"
                                                                    role="dialog" aria-labelledby="agregarDocumentoLabel-{{$pago->servicio->nomenclatura}}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header"
                                                                                style="background-color: #007bff; color: #ffffff;">
                                                                                <h5 class="modal-title"
                                                                                    id="agregarDocumentoLabel-{{$pago->servicio->nomenclatura }}">
                                                                                    Agregar Factura:{{$pago->servicio->nomenclatura}}</h5>
                                                                                <button type="button" class="btn-close btn-close-white"
                                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="{{ route('factura_operacion.store') }}" 
                                                                                    method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <label for="rutadoc">Seleccionar Archivo</label>
                                                                                        <input type="file" name="rutadoc" class="form-control"
                                                                                            required>
                                                                                    </div>
                                                                                    <input type="hidden" name="servicio_id" value="{{$pago->servicio->id }}">
                                                                                    <input type="hidden" name="nomenclatura"
                                                                                        value="{{$pago->servicio->nomenclatura }}">
                                                                                    
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary"
                                                                                            data-dismiss="modal">Cerrar</button>
                                                                                        <button type="submit" class="btn btn-primary">Agregar
                                                                                            pago</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- FIN Modal para agregar documento -->

                                @empty
                                <tr>
                                    <td colspan="7">No se encontraron servicios para mostrar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>


@endsection
@extends('layouts.app')

@section('content')

@if(auth()->check() && auth()->user()->hasAnyRole(['Administrador']))
<section class="section">
    <div class="section-header"> 
        <h3 class="page__heading">Aprobaciones Servicios de Operaciones y Mantenimiento </h3>
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
                                    <th scope="col">Razon Social</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">Cotizacion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @forelse($servicios as $servicio)
                                <tr>
                                    <td scope="row">{{ $servicio->nomenclatura }}</td>
                                    @foreach($servicio->estacionServicios as $estacion)
                                            <td>{{ $estacion->razon_social ?? 'Sin datos' }}</td>
                                            <td>{{ $estacion->domicilio_estacion_servicio ?? 'Sin datos' }}</td>
                                            <!-- Otros campos -->
                                    @endforeach   

                                    @can('Generar-cotizacion-operacion')
                                    <td scope="row">
                                        @if(!$servicio->pending_apro_servicio)

                                        <button class="btn btn-primary" disabled><i class="bi bi-file-pdf-fill"></i></button>

                                        @elseif($servicio->pending_deletion_servicio)

                                        <button class="btn btn-primary" disabled><i class="bi bi-file-pdf-fill"></i></button>

                                        @else
                                            @foreach($servicio->estacionServicios as $estacion)
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modal" data-servicio_id="{{ $servicio->id }}"
                                                        data-id="{{ $servicio->nomenclatura }}"
                                                        data-razon-social="{{ $estacion->razon_social ?? 'Sin datos' }}"
                                                        data-direccion="{{$estacion->domicilio_estacion_servicio ?? 'Sin datos' }}">
                                                        <i class="bi bi-file-pdf-fill"></i>
                                                    </button>
                                            @endforeach
                                        @endif
                                    </td>
                                    @endcan
                                    
                                    <td scope="row">
                                        @if($servicio->pending_apro_servicio)

                                        <button class="btn btn-primary" disabled><i class="bi bi-file-earmark-check-fill"></i></button>

                                        @elseif($servicio->pending_deletion_servicio)

                                        <button class="btn btn-primary" disabled><i class="bi bi-file-earmark-check-fill"></i></button>

                                        @else
                                        <a class="btn btn-primary" href="{{ route('servicio_operacion.apro', $servicio->id) }}">
                                            <i class="bi bi-file-earmark-check-fill"></i>
                                        </a>
                                        @endif

                                        @can('borrar-servicio_operacion_mantenimiento')
                                        @if($servicio->pending_deletion_servicio)
                                        <button class="btn btn-danger" disabled><i class="bi bi-trash-fill">Eliminando</i></button>
                                        @else
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_inspector_anexo_30.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                        {!! Form::close() !!}
                                        @endif
                                        @endcan
                                    </td>
                                </tr>
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
    
<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar cotización</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('pdf.cotizacion.operacion') }}" method="POST" id="cotizacionForm">
                    @csrf
                    <input type="hidden" name="nomenclatura">
                    <input type="hidden" name="id_servicio">
                    <div class="form-group col-md-6">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" name="razon_social" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="costo">Costo</label>
                        <input type="text" name="costo" class="form-control">
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modal');

        modal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var servicioIdestacion = button.getAttribute('data-servicio_id');
            var servicioId = button.getAttribute('data-id');
            var razonSocial = button.getAttribute('data-razon-social') || 'Sin datos';
            var direccion = button.getAttribute('data-direccion') || 'Sin datos';

            // Llenar los campos del formulario con los datos del servicio seleccionado
            document.querySelector('input[name="razon_social"]').value = razonSocial;
            document.querySelector('input[name="direccion"]').value = direccion;
            document.querySelector('input[name="id_servicio"]').value = servicioIdestacion;
            document.querySelector('input[name="nomenclatura"]').value = servicioId;
        });

        var formularioCotizacion = document.querySelector('#cotizacionForm');
        
        formularioCotizacion.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(formularioCotizacion);

            fetch('{{ route("pdf.cotizacion.operacion") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.pdf_url) {
                        window.open(data.pdf_url, '_blank');
                    } else {
                        console.error('URL de PDF no encontrada en la respuesta.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
        
    });
</script>


@endif
@endsection
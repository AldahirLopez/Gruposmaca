@extends('layouts.app')

@section('content')
@if(auth()->check() && auth()->user()->hasAnyRole(['Administrador']))

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Aprobaciones Servicios Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('servicio_anexo_30.index') }}" class="btn btn-danger"><i
                                    class="bi bi-arrow-return-left"></i></a>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Número de Servicio</th>
                                    <th scope="col">Razón Social</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Cotización</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($servicios as $servicio)
                                @foreach($servicio->estacionServicios as $estacion)
                                <tr>
                                    <td scope="row">{{ $servicio->nomenclatura }}</td>
                                    <td>{{ $estacion->razon_social ?? 'Sin datos' }}</td>
                                    <td>
                                        <!-- Dirección de servicio -->
                                        <div style="line-height: 1.5;">
                                            <strong>Calle:</strong> {{ $estacion->direccionServicio->calle ?? 'Sin datos' }}<br>
                                            <strong>Número Ext:</strong> {{ $estacion->direccionServicio->numero_ext ?? '' }} {{ $estacion->direccionServicio->numero_int ?? '' }}<br>
                                            <strong>Colonia:</strong> {{ $estacion->direccionServicio->colonia ?? '' }}<br>
                                            <strong>C.P.:</strong> {{ $estacion->direccionServicio->codigo_postal ?? '' }}<br>
                                            <strong>Municipio:</strong> {{ $estacion->direccionServicio->municipio ?? '' }}<br>
                                            <strong>Entidad Federativa:</strong> {{ $estacion->direccionServicio->entidad_federativa ?? '' }}
                                        </div>
                                    </td>
                                    @can('Generar-cotizacion-anexo_30')
                                    <td>
                                        @if($servicio->pending_apro_servicio && !$servicio->pending_deletion_servicio)
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal"
                                            data-servicio_id="{{ $servicio->id }}"
                                            data-id="{{ $servicio->nomenclatura }}"
                                            data-razon-social="{{ $estacion->razon_social ?? 'Sin datos' }}"
                                            data-direccion="
                                                    {{ $estacion->direccionServicio->calle ?? 'Sin datos' }}, 
                                                    {{ $estacion->direccionServicio->numero ?? '' }},
                                                    {{ $estacion->direccionServicio->numero_interior ?? '' }}, 
                                                    {{ $estacion->direccionServicio->colonia ?? '' }}, 
                                                    {{ $estacion->direccionServicio->codigo_postal ?? '' }}, 
                                                    {{ $estacion->direccionServicio->municipio ?? '' }}, 
                                                    {{ $estacion->direccionServicio->entidad_federativa ?? '' }}
                                                ">
                                            <i class="bi bi-file-pdf-fill"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-primary" disabled>
                                            <i class="bi bi-file-pdf-fill"></i>
                                        </button>
                                        @endif
                                    </td>
                                    @endcan
                                    <td>
                                        @if($servicio->pending_apro_servicio || $servicio->pending_deletion_servicio)
                                        <button class="btn btn-primary" disabled>
                                            <i class="bi bi-file-earmark-check-fill"></i>
                                        </button>
                                        @else
                                        <a class="btn btn-primary" href="{{ route('servicio_anexo.apro', $servicio->id) }}">
                                            <i class="bi bi-file-earmark-check-fill"></i>
                                        </a>
                                        @endif

                                        @can('borrar-servicio_anexo_30')
                                        @if($servicio->pending_deletion_servicio)
                                        <button class="btn btn-danger" disabled>
                                            <i class="bi bi-trash-fill"> Eliminando</i>
                                        </button>
                                        @else
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_inspector_anexo_30.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                        {!! Form::close() !!}
                                        @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No se encontraron servicios para mostrar.</td>
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
                <form class="row g-3" action="{{ route('pdf.cotizacion') }}" method="POST" id="cotizacionForm">
                    @csrf
                    <input type="hidden" name="nomenclatura" id="modal-nomenclatura">
                    <input type="hidden" name="id_servicio" id="modal-id_servicio">
                    <div class="form-group col-md-6">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" name="razon_social" class="form-control" id="modal-razon_social">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="calle">Calle</label>
                        <input type="text" name="calle" class="form-control" id="modal-calle">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="numero_ext">Número Ext</label>
                        <input type="text" name="numero_ext" class="form-control" id="modal-numero_ext">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="numero_int">Número Int</label>
                        <input type="text" name="numero_int" class="form-control" id="modal-numero_int">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="colonia">Colonia</label>
                        <input type="text" name="colonia" class="form-control" id="modal-colonia">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="codigo_postal">C.P.</label>
                        <input type="text" name="codigo_postal" class="form-control" id="modal-codigo_postal">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="municipio">Municipio</label>
                        <input type="text" name="municipio" class="form-control" id="modal-municipio">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="entidad_federativa">Entidad Federativa</label>
                        <input type="text" name="entidad_federativa" class="form-control" id="modal-entidad_federativa">
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
        const modal = document.getElementById('modal');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const servicioIdestacion = button.getAttribute('data-servicio_id');
            const servicioId = button.getAttribute('data-id');
            const razonSocial = button.getAttribute('data-razon-social') || 'Sin datos';
            const direccion = button.getAttribute('data-direccion') || '';

            // Extraer cada parte de la dirección
            const direccionParts = direccion.split(',').map(part => part.trim());
            const [calle, numeroExt, numeroInt, colonia, codigoPostal, municipio, entidadFederativa] = direccionParts;

            // Llenar los campos del formulario con los datos del servicio seleccionado
            document.querySelector('input[name="razon_social"]').value = razonSocial;
            document.querySelector('input[name="calle"]').value = calle || '';
            document.querySelector('input[name="numero_ext"]').value = numeroExt || '';
            document.querySelector('input[name="numero_int"]').value = numeroInt || '';
            document.querySelector('input[name="colonia"]').value = colonia || '';
            document.querySelector('input[name="codigo_postal"]').value = codigoPostal || '';
            document.querySelector('input[name="municipio"]').value = municipio || '';
            document.querySelector('input[name="entidad_federativa"]').value = entidadFederativa || '';
            document.querySelector('input[name="id_servicio"]').value = servicioIdestacion;
            document.querySelector('input[name="nomenclatura"]').value = servicioId;
        });

        const formularioCotizacion = document.querySelector('#cotizacionForm');

        formularioCotizacion.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(formularioCotizacion);

            fetch('{{ route("pdf.cotizacion") }}', {
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
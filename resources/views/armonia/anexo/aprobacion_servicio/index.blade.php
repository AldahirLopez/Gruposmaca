@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Aprobacion servicios anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('anexo.index') }}" class="btn btn-danger"><i
                                    class="bi bi-arrow-return-left"></i></a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de servicio</th>
                                    <th scope="col">Cotizacion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $servicio)
                                    <tr>
                                        <td scope="row">{{$servicio->nomenclatura}}</td>
                                        <td scope="row">
                                            @if(!$servicio->pending_apro_servicio)

                                                <button class="btn btn-primary" disabled><i
                                                        class="bi bi-file-pdf-fill"></i></button>

                                            @elseif($servicio->pending_deletion_servicio)

                                                <button class="btn btn-primary" disabled><i
                                                        class="bi bi-file-pdf-fill"></i></button>

                                            @else
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal" data-id="{{ $servicio->nomenclatura }}">
                                                    <i class="bi bi-file-pdf-fill"></i>
                                                </button>
                                            @endif
                                        </td>
                                        <td scope="row">
                                            @if($servicio->pending_apro_servicio)

                                                <button class="btn btn-primary" disabled><i
                                                        class="bi bi-file-earmark-check-fill"></i></button>

                                            @elseif($servicio->pending_deletion_servicio)

                                                <button class="btn btn-primary" disabled><i
                                                        class="bi bi-file-earmark-check-fill"></i></button>

                                            @else
                                                <a class="btn btn-primary"
                                                    href="{{ route('servicio_anexo.apro', $servicio->id) }}">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                </a>
                                            @endif

                                            @can('borrar-servicio')
                                                @if($servicio->pending_deletion_servicio)
                                                    <button class="btn btn-danger" disabled><i
                                                            class="bi bi-trash-fill">Eliminando</i></button>
                                                @else
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_anexo.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::button('<i class="bi bi-trash-fill"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'title' => 'Eliminar']) !!}
                                                    {!! Form::close() !!}
                                                @endif
                                            @endcan
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

<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar cotización</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('pdf.cotizacion') }}" method="POST" id="cotizacionForm">
                    @csrf
                    <input type="hidden" name="nomenclatura" value="{{$servicio->nomenclatura}}">
                    <div class="form-group col-md-6">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" name="razon_social" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="estado">Estado</label>
                        <input type="text" name="estado" class="form-control">
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
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #002855; border-color: #002855;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modal');

        modal.addEventListener('shown.bs.modal', function (event) {
            var button = event.relatedTarget;
            var serviceId = button.getAttribute('data-id');
        });

        var formularioCotizacion = document.querySelector('#modal form');

        formularioCotizacion.addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(formularioCotizacion);

            fetch('{{ route("pdf.cotizacion") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La solicitud no pudo completarse correctamente.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.pdf_url) {
                        var pdfUrl = data.pdf_url.replace(/\\/g, '');
                        window.open(pdfUrl, '_blank');
                    } else {
                        console.error('URL de PDF no encontrada en la respuesta.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>


@endsection
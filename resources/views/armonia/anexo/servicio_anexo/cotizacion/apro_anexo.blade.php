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
                            <a href="{{ route('home') }}" class="btn btn-danger">Home</a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de servicio</th>
                                    <th scope="col">Estacion de servicio</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Cotizacion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $servicio)
                                    <tr>
                                        <td scope="row">{{$servicio->nomenclatura}}</td>
                                        <td scope="row">{{$servicio->nombre_estacion}}</td>
                                        <td scope="row">{{$servicio->direccion_estacion}}</td>
                                        <td scope="row">{{$servicio->estado_estacion}}</td>
                                        <td scope="row">
                                            <center>
                                                @if($servicio->pending_deletion)
                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-file-pdf-fill"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modal"><i class="bi bi-file-pdf-fill"></i></button>
                                                @endif
                                            </center>
                                        </td>
                                        <td scope="row">
                                            @can('editar-servicio')
                                                @if($servicio->pending_deletion)
                                                    <button class="btn btn-primary" disabled><i
                                                            class="bi bi-pencil-square"></i></button>
                                                @else
                                                    <a class="btn btn-primary"
                                                        href="{{ route('servicio_anexo.edit', $servicio->nomenclatura) }}"><i
                                                            class="bi bi-file-earmark-check-fill"></i></a>
                                                @endif
                                            @endcan
                                            @can('borrar-servicio')
                                                @if($servicio->pending_deletion)
                                                    <button class="btn btn-danger" disabled><i
                                                            class="bi bi-trash-fill"></i></button>
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
                <form class="row g-3" action="{{ route('pdf.cotizacion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="nomenclatura" value="{{ $servicio->nomenclatura }}">
                    <input type="hidden" name="nombre_estacion" value="{{ $servicio->nombre_estacion }}">
                    <input type="hidden" name="direccion_estacion" value="{{ $servicio->direccion_estacion }}">
                    <input type="hidden" name="estado_estacion" value="{{ $servicio->estado_estacion }}">
                    <div class="form-group">
                        <label for="costo">Costo</label>
                        <input type="text" name="costo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="iva">IVA</label>
                        <input type="text" name="iva" class="form-control">
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
<!-- End Vertically centered Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var formularioCotizacion = document.querySelector('#modal form');

        formularioCotizacion.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario por defecto

            // Obtener los datos del formulario
            var formData = new FormData(formularioCotizacion);

            // Hacer la solicitud para generar el PDF
            fetch('{{ route("pdf.cotizacion") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    window.open(data.pdf_url, '_blank'); // Abrir el PDF en una nueva ventana
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>


@endsection
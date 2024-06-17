<!-- resources/views/partials/tabla_servicios.blade.php -->
<table class="table table-striped">
    <thead style="text-align: center;">
        <tr>
            <th scope="col">Numero de servicio</th>
            <th scope="col">Cotizacion</th>
            <th scope="col">Expediente</th>
            <th scope="col">Listas de Inspeccion</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody style="text-align: center;">
        @forelse($servicios as $servicio)
            <tr>
                <td scope="row">{{ $servicio->nomenclatura }}</td>
                <td scope="row">
                    @if(!optional($servicio->cotizacion)->estado_cotizacion)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-file-earmark-excel-fill"></i></button>
                        </center>
                    @elseif($servicio->pending_deletion_servicio)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-file-earmark-excel-fill"></i></button>
                        </center>
                    @else
                        <a href="{{ $servicio->cotizacion ? $servicio->cotizacion->ruta_doc_url : '#' }}" target="_blank"
                            class="btn btn-primary btn-generar-pdf">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @if(!$servicio->pending_apro_servicio)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                        </center>
                    @elseif($servicio->pending_deletion_servicio)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                        </center>
                    @else
                        <a href="{{ route('expediente.anexo30', ['servicio_anexo_id' => $servicio->id]) }}"
                            class="btn btn-primary">
                            <i class="bi bi-folder-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @if(!$servicio->pending_apro_servicio)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                        </center>
                    @elseif($servicio->pending_deletion_servicio)
                        <center>
                            <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                        </center>
                    @else
                        <a href="{{ route('listas.anexo30', ['servicio_anexo_id' => $servicio->id]) }}" class="btn btn-primary">
                            <i class="bi bi-folder-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @if(!$servicio->pending_apro_servicio)

                        <button class="btn btn-primary" disabled><i class="bi bi-pencil-square"></i></button>

                    @elseif($servicio->pending_deletion_servicio)

                        <button class="btn btn-primary" disabled><i class="bi bi-pencil-square"></i></button>

                    @else
                        <!-- Modifica tu botón para usar la clase 'btn-generar-pdf' -->
                        <a href="{{ route('archivos.index', ['servicio_id' => $servicio->id]) }}"
                            class="btn btn-primary btn-generar-pdf">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </a>
                    @endif
                    @can('borrar-servicio')
                        @if($servicio->pending_deletion_servicio)
                            <button class="btn btn-danger" disabled>(pendiente)</button>
                        @else
                            {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_anexo.destroy', $servicio->id], 'style' => 'display:inline']) !!}
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
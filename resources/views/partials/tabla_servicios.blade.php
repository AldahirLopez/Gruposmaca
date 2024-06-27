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
                    @if(!$servicio->cotizacion || !$servicio->cotizacion->estado_cotizacion || $servicio->pending_deletion_servicio)
                        <button class="btn btn-primary" disabled>
                            <i class="bi bi-file-earmark-excel-fill"></i>
                        </button>
                    @else
                        <a href="{{ route('descargar.cotizacion.ajax') }}?rutaDocumento={{ urlencode($servicio->cotizacion->rutadoc_cotizacion) }}"
                            class="btn btn-primary btn-descargar-pdf" data-carpeta="{{ $servicio->nomenclatura }}">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @if(!$servicio->pending_apro_servicio || $servicio->pending_deletion_servicio || !$servicio->id)
                        <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                    @else
                        <a href="{{ route('expediente.anexo30', ['slug' => $servicio->id]) }}" class="btn btn-primary">
                            <i class="bi bi-folder-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @if(!$servicio->pending_apro_servicio || $servicio->pending_deletion_servicio || !$servicio->slug)
                        <button class="btn btn-primary" disabled><i class="bi bi-folder-fill"></i></button>
                    @else
                        <a href="{{ route('listas.anexo30', ['slug' => $servicio->slug]) }}" class="btn btn-primary">
                            <i class="bi bi-folder-fill"></i>
                        </a>
                    @endif
                </td>
                <td scope="row">
                    @can('borrar-servicio')
                        @if($servicio->pending_deletion_servicio)
                            <button class="btn btn-danger" disabled>(pendiente)</button>
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
                <td colspan="5">No se encontraron servicios para mostrar.</td>
            </tr>
        @endforelse
    </tbody>
</table>
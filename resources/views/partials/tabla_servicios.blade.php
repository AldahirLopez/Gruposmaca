<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div id="tablaServicios">
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
                                <td scope="row">{{ $servicio->nomenclatura }}</td>
                                <td scope="row">{{ $servicio->nombre_estacion }}</td>
                                <td scope="row">{{ $servicio->direccion_estacion }}</td>
                                <td scope="row">{{ $servicio->estado_estacion }}</td>
                                <td scope="row">
                                    @if(!$servicio->estado)
                                    <center>
                                        <button class="btn btn-primary" disabled><i class="bi bi-file-earmark-excel-fill"></i></button>
                                    </center>
                                    @else
                                    <a href="{{ route('archivos.index', ['servicio_id' => $servicio->id]) }}" class="btn btn-info"><i class="bi bi-file-earmark-check-fill"></i></a>
                                    @endif
                                </td>
                                <td scope="row">
                                    @can('editar-servicio')
                                    @if(!$servicio->estado)
                                    <button class="btn btn-primary" disabled><i class="bi bi-pencil-square"></i></button>
                                    @else
                                    <a class="btn btn-primary" href="{{ route('servicio_anexo.edit', $servicio->id) }}"><i class="bi bi-pencil-square"></i></a>
                                    @endif
                                    @endcan
                                    @can('borrar-servicio')
                                    @if($servicio->pending_deletion)
                                    <button class="btn btn-danger" disabled>(pendiente)</button>
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
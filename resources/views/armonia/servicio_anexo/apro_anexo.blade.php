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
                                        @if($servicio->estado)
                                        <button class="btn btn-primary" disabled>Cotizacion</button>
                                        @else
                                        <a href="{{ route('pdf.cotizacion', ['nombre_estacion' => $servicio->nombre_estacion, 'direccion_estacion' => $servicio->direccion_estacion, 'estado_estacion' => $servicio->estado_estacion]) }}" class="btn btn-info">Cotización</a>
                                        @endif
                                    </td>
                                    <td scope="row">
                                        @can('editar-servicio')
                                        @if($servicio->estado)
                                        <button class="btn btn-primary" disabled>Editar</button>
                                        @else
                                        <a class="btn btn-primary" href="{{ route('servicio_anexo.edit', $servicio->nomenclatura) }}">Aprobar</a>
                                        @endif
                                        @endcan
                                        @can('borrar-servicio')
                                        @if($servicio->pending_deletion)
                                        <button class="btn btn-danger" disabled>(pendiente de aprobación)</button>
                                        @else
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['servicio_anexo.destroy', $servicio->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Eliminar', ['class' => 'btn btn-danger']) !!}
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
@endsection
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Servicios Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger">Home</a>
                            @can('crear-anexo')
                            <a class="btn btn-success" href="{{route('servicio_anexo.create')}}">Nuevo</a>
                            @endcan
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de servicio</th>
                                    <th scope="col">Estacion de servicio</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $servicio)
                                <tr>
                                    <td scope="row">{{$servicio->nomenclatura}}</td>
                                    <td scope="row">{{$servicio->nombre_estacion}}</td>
                                    <td scope="row">{{$servicio->direccion_estacion}}</td>
                                    <td scope="row">
                                        @if(!$servicio->estado)
                                        <button class="btn btn-primary" disabled>Añadir Archivos</button>
                                        @else
                                        <a href="{{ route('archivos.index', ['servicio_id' => $servicio->id]) }}" class="btn btn-info">Añadir Archivos</a>
                                        @endif
                                    </td>
                                    <td scope="row">
                                        @can('editar-servicio')
                                        @if(!$servicio->estado)
                                        <button class="btn btn-primary" disabled>Editar</button>
                                        @else
                                        <a class="btn btn-primary" href="{{ route('servicio_anexo.edit', $servicio->id) }}">Editar</a>
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
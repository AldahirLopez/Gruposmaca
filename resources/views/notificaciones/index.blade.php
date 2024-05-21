@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Pendientes de Eliminar Dictámenes Operación y Mantenimiento</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger">Home</a>
                            @can('crear-operacion')
                            <a class="btn btn-success" href="{{ route('operacion.create') }}">Nuevo</a>
                            @endcan
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Identificador</th>
                                    <th scope="col">Detalles</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dictamenes as $dictamen)
                                <tr>
                                    <td scope="row">{{$dictamen->nombre}}</td>
                                    <td scope="row">
                                        <a href="{{ route('archivos.index', ['dictamen_id' => $dictamen->id]) }}" class="btn btn-info">Listar Archivos</a>
                                    </td>
                                    <td scope="row">
                                        @can('editar-operacion')
                                        <a class="btn btn-primary" href="{{ route('operacion.edit', $dictamen->id) }}">Editar</a>
                                        @endcan
                                        @can('borrar-operacion')
                                        <form action="{{ route('approval.cancel', $dictamen->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Cancelar Eliminacion</button>
                                        </form>
                                        <form action="{{ route('approval.approve', $dictamen->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach

                                @foreach($servicios as $servicio)
                                <tr>
                                    <td scope="row">{{$servicio->nomenclatura}}</td>
                                    <td scope="row">
                                       
                                    </td>
                                    <td scope="row">
                                        @can('editar-servicio')
                                        <a class="btn btn-primary" href="{{ route('servicio_anexo.edit', $servicio->id) }}">Editar</a>
                                        @endcan
                                        @can('borrar-servicio')
                                        <form action="{{ route('approval.cancel', $servicio->nomenclatura) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Cancelar Eliminacion</button>
                                        </form>
                                        <form action="{{ route('approval.approve', $servicio->nomenclatura) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
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
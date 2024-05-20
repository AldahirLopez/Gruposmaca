@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Dictamenes Operacion y Mantenimiento</h3>
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
                                    <th scope="col">Numero de dictamen</th>
                                    <th scope="col">Detalles</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dictamenes as $dictamen)
                                    <tr>
                                        <td scope="row">{{$dictamen->nombre}}</td>
                                        <td scope="row">
                                            @if($dictamen->pending_deletion)
                                                <button class="btn btn-primary" disabled>Ver Archivos</button>
                                            @else
                                                <a href="{{ route('archivos.index', ['dictamen_id' => $dictamen->id]) }}"
                                                    class="btn btn-info">Listar Archivos</a>
                                            @endif
                                        </td>
                                        <td scope="row">
                                            @can('editar-operacion')
                                                @if($dictamen->pending_deletion)
                                                    <button class="btn btn-primary" disabled>Editar</button>
                                                @else
                                                    <a class="btn btn-primary"
                                                        href="{{ route('operacion.edit', $dictamen->id) }}">Editar</a>
                                                @endif
                                            @endcan
                                            @can('borrar-operacion')
                                                @if($dictamen->pending_deletion)
                                                    <button class="btn btn-danger" disabled>(pendiente de aprobación)</button>
                                                @else
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['operacion.destroy', $dictamen->id], 'style' => 'display:inline']) !!}
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
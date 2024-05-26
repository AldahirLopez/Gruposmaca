@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Archivos del dictamen "{{ $dictamen }}"</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card"> 
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('operacion.index') }}" class="btn btn-danger">Regresar</a>
                            @if ($cantidadArchivos < 3) @can('crear-archivos') <a class="btn btn-success" href="{{ route('archivos.create', ['dictamen_id' => $dictamen_id]) }}">Nuevo</a>
                                @endcan
                                @else
                                <!-- No mostrar el botón y en su lugar mostrar un mensaje indicando que se alcanzó el límite de archivos -->
                                <a class="btn btn-warning"> No se pueden agregar más archivos. Se alcanzó el límite de ocho archivos.</a>
                                @endif
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de dictamen</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archivos as $archivo)
                                <tr>
                                    <td scope="row">{{$archivo->nombre}}</td>
                                    <td scope="row">
                                        <a href="#" class="btn btn-info" onclick="mostrarArchivo('{{ Storage::url($archivo->rutadoc) }}')">Mostrar
                                            Archivo</a>
                                        <script>
                                            function mostrarArchivo(url) {
                                                // Abrir una nueva ventana o pestaña con la URL del archivo
                                                window.open(url, '_blank');
                                            }
                                        </script>
                                    </td>
                                    </td>
                                    <td scope="row">

                                        @can('editar-archivos')
                                        <a class="btn btn-primary" href="{{ route('archivos.edit', $archivo->id) }}">Editar</a>
                                        @endcan

                                        @can('borrar-archivos')
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['archivos.destroy', $archivo->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
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
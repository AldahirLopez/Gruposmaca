@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Formatos Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger"><i
                                    class="bi bi-arrow-return-left"></i></a>
                            @can('crear-formato')
                                <a class="btn btn-success" href="{{ route('archivosanexo.create') }}">Nuevo</a>
                            @endcan
                        </div>
                        <table class="table table-striped">
                            <thead style="text-align: center;">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Documento</th>
                                    @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                        <th scope="col">Acciones</th>
                                    @endif
                                    <th scope="col">Fecha de Actualizacion</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @foreach($archivos as $archivo)
                                    <tr>
                                        <td scope="row">{{ $archivo->nombre }}</td>
                                        <td scope="row">
                                            <a href="#" class="btn btn-info"
                                                onclick="mostrarArchivo('{{ Storage::url($archivo->rutadoc) }}')">Mostrar
                                                Archivo</a>
                                            <script>
                                                function mostrarArchivo(url) {
                                                    // Abrir una nueva ventana o pestaña con la URL del archivo
                                                    window.open(url, '_blank');
                                                }
                                            </script>
                                        </td>
                                        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                            <td scope="row">
                                                @can('editar-formato')
                                                    <a class="btn btn-primary"
                                                        href="{{ route('archivos.edit', $archivo->id) }}">Editar</a>
                                                @endcan

                                                @can('borrar-formato')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['archivos.destroy', $archivo->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        @endif
                                        <td scope="row">{{ $archivo->created_at->format('Y-m-d') }}</td>
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
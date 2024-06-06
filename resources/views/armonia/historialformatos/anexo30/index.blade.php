@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Historial Formatos Anexo 30</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('home') }}" class="btn btn-danger">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>
                        </div>
                        @if(auth()->check() && auth()->user()->hasAnyRole('Administrador', 'Auditor'))
                                                <div style="margin-top: 15px;">
                                                    <label for="filtrohistorial">Filtrar Documento:</label>
                                                    <select id="filtrohistorial" class="form-select" aria-label="Default select example">
                                                        <option value="">Todos los documentos</option>
                                                        @php
                                                            $nombresUnicos = $archivos->unique('nombre');
                                                        @endphp
                                                        @foreach($nombresUnicos as $archivo)
                                                            <option value="{{ $archivo->nombre }}">{{ $archivo->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                        @endif
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
                            <tbody id="tabla-archivos" style="text-align: center;">
                                @foreach($archivos as $archivo)
                                    <tr>
                                        <td scope="row">{{ $archivo->nombre }}</td>
                                        <td scope="row">
                                            <a href="#" class="btn btn-info"
                                                onclick="mostrarArchivo('{{ Storage::url($archivo->rutadoc) }}')">Mostrar
                                                Archivo</a>
                                            <script>
                                                function mostrarArchivo(url) {
                                                    window.open(url, '_blank');
                                                }
                                            </script>
                                        </td>
                                        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                                            <td scope="row">
                                                @can('borrar-formato')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['historialformatos.destroy', $archivo->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        @endif
                                        <td scope="row">{{ $archivo->created_at->format('d-m-Y') }}</td>
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

<script>
    document.getElementById('filtrohistorial').addEventListener('change', function () {
        var nombre = this.value;

        fetch('{{ route("filtrar.archivos") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nombre: nombre })
        })
            .then(response => response.json())
            .then(data => {
                var tbody = document.getElementById('tabla-archivos');
                tbody.innerHTML = '';

                data.forEach(archivo => {
                    var tr = document.createElement('tr');
                    tr.innerHTML = `
                <td scope="row">${archivo.nombre}</td>
                <td scope="row">
                    <a href="#" class="btn btn-info" onclick="mostrarArchivo('${archivo.rutadoc}')">Mostrar Archivo</a>
                </td>
                @if(auth()->check() && auth()->user()->hasRole('Administrador'))
                    <td scope="row">
                        <form method="POST" action="/historialformatos/${archivo.id}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-danger" value="Borrar">
                        </form>
                    </td>
                @endif
                <td scope="row">${archivo.formatted_date}</td>`;
                    tbody.appendChild(tr);
                });
            });
    });

    function mostrarArchivo(url) {
        window.open(url, '_blank');
    }
</script>
@endsection
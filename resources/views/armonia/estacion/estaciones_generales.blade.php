@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Estaciones de servicio</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: 15px;">
                            <a href="{{ route('estacion.selecccion') }}"  class="btn btn-danger"><i class="bi bi-arrow-return-left"></i></a>
                        </div>

                        <input style="margin-top: 15px;" type="text" id="buscarEstacion" class="form-control mb-3" placeholder="Buscar estación...">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Numero de estacion</th>
                                    <th scope="col">Razon Social</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEstaciones">
                                @foreach($estaciones as $estacion)
                                <tr>
                                    <td>{{ $estacion->num_estacion }}</td>
                                    <td>{{ $estacion->razon_social }}</td>
                                    <td>{{ $estacion->estado_republica }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Incluir jQuery y Bootstrap, preferiblemente desde un CDN para aprovechar el caché del navegador -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <script>
        $(document).ready(function() {
            $('#buscarEstacion').keyup(function() {
                var searchText = $(this).val().toLowerCase();
                $('#tablaEstaciones tr').each(function() {
                    var found = false;
                    $(this).each(function() {
                        if ($(this).text().toLowerCase().indexOf(searchText) >= 0) {
                            found = true;
                            return false;
                        }
                    });
                    found ? $(this).show() : $(this).hide();
                });
            });
        });
    </script>
    @endsection
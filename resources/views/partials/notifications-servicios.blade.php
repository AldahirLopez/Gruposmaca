<!-- Otros elementos del header -->
@if(auth()->check() && auth()->user()->hasRole('Administrador'))
<li class="nav-item dropdown">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number">{{ $pendingDeletionsDictamen->count() + $pendingDeletionsServicioAn->count() }}</span>

    </a><!-- End Notification Icon -->

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
            Tienes {{ $pendingDeletionsDictamen->count() }} nuevas notifiaciones
            <a href="{{ route('notificaciones.index')}}"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todos</span></a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>

        @foreach($pendingDeletionsDictamen as $dictamen)
        <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
                <h4>Solicitud de eliminación</h4>
                <p>El dictamen "{{ $dictamen->nombre }}" está pendiente de aprobación para ser eliminado.</p>
                <p>{{ $dictamen->updated_at->diffForHumans() }}</p>
                <a href="{{ route('approval.show', $dictamen->id) }}" class="btn btn-primary">Ver detalles</a>
            </div>
        </li>
        <li> 
            <hr class="dropdown-divider">
        </li>
        @endforeach

        @foreach($pendingDeletionsServicioAn as $servicio)
        <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
                <h4>Solicitud de eliminación</h4>
                <p>El servicio "{{ $servicio->nomenclatura }}" está pendiente de aprobación para ser eliminado.</p>
                <p>{{ $servicio->updated_at->diffForHumans() }}</p>
                <a href="{{ route('approval.show', $servicio->nomenclatura) }}" class="btn btn-primary">Ver detalles</a>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @endforeach

        <li class="dropdown-footer">
            <a href="{{ route('notificaciones.index')}}">Ver todas las notificaciones</a>
        </li>
    </ul><!-- End Notification Dropdown Items -->
</li><!-- End Notification Nav -->
@endif

@if(auth()->check() && auth()->user()->hasRole('Administrador') )
<li class="nav-item dropdown">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number">{{ $pendingDeletionsServicio->count() + $pendingAproServicioOperacion->count() }}</span>
    </a><!-- End Notification Icon -->

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
            Tienes {{ $pendingDeletionsServicio->count() + $pendingAproServicioOperacion->count()}} nuevas notificaciones
            <a href="{{ route('apro.anexo') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todos</span></a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>

        @foreach($pendingDeletionsServicio as $servicio)
        <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
                <h4>Solicitud de aprobacion</h4>
                <p>El servicio de anexo 30 "{{ $servicio->nomenclatura }}" está pendiente de aprobación</p>
                <p>{{ $servicio->updated_at->diffForHumans() }}</p>
            </div>
        </li>

        
        <li>
            <hr class="dropdown-divider">
        </li>
        @endforeach


        @foreach ($pendingAproServicioOperacion as $servicio)
            
        <li class="notification-item">
                    <i class="bi bi-exclamation-circle text-warning"></i>
                    <div>
                        <h4>Solicitud de aprobacion</h4>
                        <p>El servicio de operacion y mantenimiento "{{ $servicio->nomenclatura }}" está pendiente de aprobación</p>
                        <p>{{ $servicio->updated_at->diffForHumans() }}</p>
                    </div>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        @endforeach

        <li class="dropdown-footer">
            <a href="{{ route('apro.anexo') }}">Ver todas las notificaciones</a> 
        </li>
    </ul><!-- End Notification Dropdown Items -->
</li><!-- End Notification Nav -->
@endif
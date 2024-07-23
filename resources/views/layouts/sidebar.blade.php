<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('home') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#usuarios-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Usuarios</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="usuarios-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('usuarios.index') }}">
                        <i class="bi bi-circle"></i><span>Ver Usuarios</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#roles-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-shield-lock-fill"></i><span>Roles</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="roles-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('roles.index') }}">
                        <i class="bi bi-circle"></i><span>Ver Roles</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->check() && auth()->user()->hasRole(['Verificador Anexo 30', 'Administrador', 'Operacion y Mantenimiento', 'Auditor']))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check-fill"></i><span>Formatos Vigentes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('listar.anexo30') }}">
                        <i class="bi bi-circle"></i><span>Anexo 30</span>
                    </a>
                </li>
            </ul>
        </li>

        @endif

        @if(auth()->check() && auth()->user()->hasRole(['Administrador']))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#formshistori-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-week-fill"></i><span>Formatos Historial</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="formshistori-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('historialformatos.index') }}">
                        <i class="bi bi-circle"></i><span>Anexo 30</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif



        @if(auth()->check() && auth()->user()->hasAnyRole(['Operacion y Mantenimiento', 'Auditor']))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#operacion-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-folder"></i><span>Operacion y Mantenimiento</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="operacion-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('operacion.index') }}">
                        <i class="bi bi-circle"></i><span>Ver Dictamenes</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->check() && auth()->user()->hasAnyRole(['Verificador Anexo 30', 'Auditor', 'Operacion y Mantenimiento']))
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('estacion.selecccion') }}">
                <i class="bx bxs-data"></i>
                <span>Estaciones de servicio</span>
            </a>
        </li>
        @endif

        @if(auth()->check() && auth()->user()->hasAnyRole(['Verificador Anexo 30', 'Auditor']))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#anexo-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-folder"></i><span>Anexo 30</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="anexo-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('servicio_anexo_30.index') }}">
                        <i class="bi bi-circle"></i><span>Ver Servicios</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->check() && auth()->user()->hasRole(['Administrador']))
        <!-- End Components Nav -->
        <li class="nav-heading">Paginas</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-calendar3"></i>
                <span>Recordatorios</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('ema.index') }}">
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <span>Tramites Entidad Mexicana de Acreditacion, A.C.</span>
            </a>
        </li>


        @can('ver-servicio_operacion_mantenimiento')
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('operacion.index') }}">
                <i class="bi bi-hammer"></i>
                <span>Operacion y Mantenimiento</span>
            </a>
        </li>
        @endcan



        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('servicio_anexo_30.index') }}">
                <i class="bi bi-folder-check"></i>
                <span>Anexo 30</span>
            </a>
        </li>



        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('estacion.selecccion') }}">
                <i class="bx bxs-data"></i>
                <span>Estaciones de servicio</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('usuario_estacion.index') }}">
                <i class="bi bi-person-plus-fill"></i>
                <span>Estaciones por usuario</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('notificaciones.index') }}">
                <i class="bi bi-trash-fill"></i>
                <span>Pendientes de eliminacion dictamenes</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('diseño.index') }}">
                <i class="bi bi-brush-fill"></i>
                <span>Dictamenes Diseño</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('construccion.index') }}">
                <i class="bi bi-brush-fill"></i>
                <span>Dictamenes Construccion</span>
            </a>
        </li>

        <!-- End Profile Page Nav -->

        @endif

    </ul>


</aside>
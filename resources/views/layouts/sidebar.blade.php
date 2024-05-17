<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="index.html">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#usuarios-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Usuarios</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="usuarios-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('usuarios.index') }}">
                            <i class="bi bi-circle"></i><span>Ver Usuarios</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#roles-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Roles</span><i class="bi bi-chevron-down ms-auto"></i>
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
        @if(auth()->check() && auth()->user()->hasRole('Operacion y Mantenimiento'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#operacion-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Operacion y Mantenimiento</span><i
                        class="bi bi-chevron-down ms-auto"></i>
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
        <!-- End Components Nav -->
        @if(auth()->check() && auth()->user()->hasRole('Administrador'))
            <li class="nav-heading">Paginas</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('operacion.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Operacion y Mantenimiento</span>
                </a>
            </li><!-- End Profile Page Nav -->
        @endif

    </ul>

</aside>
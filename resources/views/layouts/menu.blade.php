<li class="side-menus" style="background-color: #495057;">
    <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="/souichi">
        <i class="fas fa-house"></i><span>Dashboard Souichi</span>
    </a>


    <a class="nav-link {{ Request::is('usuarios') ? 'active' : '' }}" href="/usuarios">
        <i class=" fas fa-users"></i><span>Usuarios</span>
    </a>

    <a class="nav-link {{ Request::is('roles') ? 'active' : '' }} " href="/roles">
        <i class="fas fa-user-lock"></i><span>Roles</span>
    </a>


</li>
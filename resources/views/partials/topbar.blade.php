<nav class="navbar navbar-expand navbar-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-dark">
        <i class="fa fa-bars"></i>
    </button>
   
    <div class="ml-auto"></div>
    <ul class="navbar-nav">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hallo, {{ Auth::user()->nama_user ?? 'Guest' }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('img/profile.jpeg') }}" style="height:35px;">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ url('/profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
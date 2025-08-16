<ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color:#2F3645;" id="accordionSidebar">
 

  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin/dashboard') }}">
  <div class="sidebar-brand-icon">
  <img src="{{ asset('img/logo.png') }}" alt="Logo Diskominfo" style="height:70px;">
  </div>
  </a>
 

  <div class="sidebar-brand-text mx-3 text-center" style="font-family: 'Poppins', sans-serif;">DISKOMINFOTIK</div>
 

  <hr class="sidebar-divider mt-3 mb-3">
 

  <li class="nav-item">
  <a class="nav-link" href="{{ url('/admin/dashboard') }}">
  <i class="fas fa-tachometer-alt"></i>
  <span>Dashboard</span>
  </a>
  </li>
 

  <li class="nav-item">
  <a class="nav-link" href="{{ url('/admin/users') }}">
  <i class="fas fa-users"></i>
  <span>Users</span>
  </a>
  </li>
 

  <li class="nav-item">
  <a class="nav-link" href="{{ url('/admin/penjadwalan') }}">
  <i class="fas fa-calendar-alt"></i>
  <span>Penjadwalan</span>
  </a>
  </li>
 

  <li class="nav-item">
  <a class="nav-link" href="{{ url('/admin/absensi') }}">
  <i class="fas fa-check-circle"></i>
  <span>Absensi</span>
  </a>
  </li>
 

  <li class="nav-item">
  <a class="nav-link" href="{{ url('/admin/peralatan') }}">
  <i class="fas fa-tools"></i>
  <span>Peralatan</span>
  </a>
  </li>
 

  <hr class="sidebar-divider">
 

<li class="nav-item">
    <a class="nav-link text-danger" href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
 

 </ul>
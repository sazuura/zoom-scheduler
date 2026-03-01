<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Diskominfo</title>

    <!-- Boxicons & Google Fonts -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/adminhub.css') }}">
</head>
<body>

    <!-- Sidebar -->
    <section id="sidebar">
       <a href="{{ route('operator.dashboard') }}" class="brand" style="flex-direction: column; text-align: center;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:60px; width:auto; margin-bottom:8px;">
            <span class="text">DISKOMINFOTIK</span>
       </a>

        <ul class="side-menu top">
            <li class="{{ request()->is('operator/dashboard') ? 'active' : '' }}">
                <a href="{{ route('operator.dashboard') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('operator/jadwal*') ? 'active' : '' }}">
                <a href="{{ route('operator.jadwal.index') }}">
                    <i class='bx bxs-calendar'></i>
                    <span class="text">Jadwal Saya</span>
                </a>
            </li>
            <li class="{{ request()->is('operator/absensi*') ? 'active' : '' }}">
                <a href="{{ route('operator.absensi.index') }}">
                    <i class='bx bxs-check-circle'></i>
                    <span class="text">Absensi</span>
                </a>
            </li>
            <li class="{{ request()->is('operator/peralatan*') ? 'active' : '' }}">
    <a href="{{ route('operator.peralatan.index') }}">
        <i class='bx bxs-wrench'></i>
        <span class="text">Peralatan</span>
    </a>
</li>

        </ul>

        <ul class="side-menu">
            <li>
                <a href="{{ route('logout') }}" class="logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
    </section>
    <!-- End Sidebar -->

    <!-- Content -->
    <section id="content">
        <nav style="display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center;">
        <i class='bx bx-menu'></i>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="{{ asset('img/amanah.png') }}" alt="Bandung Barat Amanah" style="height:40px; width:auto;">
        <img src="{{ asset('img/jabaristimewa.png') }}" alt="Jabar Istimewa" style="height:40px; width:auto;">
        <img src="{{ asset('img/berakhlak.png') }}" alt="ASN BerAKHLAK" style="height:40px; width:auto;">
        
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        
        <a href="#" class="profile">
            <span class="ms-2">Hallo, {{ Auth::user()->nama_user }}</span>
        </a>
    </div>
</nav>


        @yield('content')
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/adminhub.js') }}"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diskominfo - @yield('title')</title>
<link rel="icon" type="image/png" href="{{ asset('img/logo-diskominfotik.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #FAFAF7;
            color: #2E2E2E;
        }
        .navbar {
            background-color: #1A237E;
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: #fff !important;
        }
        .btn-primary {
            background-color: #1A237E;
            border-color: #1A237E;
        }
        .btn-primary:hover {
            background-color: #0D1B2A;
            border-color: #0D1B2A;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Diskominfo</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Halo, {{ Auth::user()->name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

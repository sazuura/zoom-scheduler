<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diskominfo - @yield('title')</title>

    {{-- Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    {{-- Font & Icons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- AdminHub CSS --}}
    <link rel="stylesheet" href="{{ asset('css/adminhub.css') }}">

    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Content -->
    <section id="content">
        <!-- Topbar -->
        @include('partials.topbar')

        <!-- Main -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </section>

    <!-- AdminHub JS -->
    <script src="{{ asset('js/adminhub.js') }}"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIPIKA - @yield('title')</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet"
          href="{{ asset('css/app.css') }}">
</head>

<body>

<div class="sipika-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sipika-sidebar">

        <div class="brand">
            SIPIKA
        </div>

        <nav class="sidebar-menu">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <i class="bi bi-house-fill"></i>

                <span>Dashboard</span>

            </a>


            {{-- Data Pengeluaran --}}
            <a href="{{ route('pengeluaran.index') }}"
               class="{{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">

                <i class="bi bi-calendar3"></i>

                <span>Data Pengeluaran</span>

            </a>


            {{-- Data Infaq --}}
            <a href="{{ route('infaq.index') }}"
               class="{{ request()->routeIs('infaq.*') ? 'active' : '' }}">

                <i class="bi bi-calendar3"></i>

                <span>Data Infaq</span>

            </a>


            {{-- Laporan --}}
            <a href="{{ route('laporan.index') }}"
               class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">

                <i class="bi bi-clipboard-text-fill"></i>

                <span>Laporan</span>

            </a>


            {{-- Profil --}}
            <a href="{{ route('profil.index') }}"
               class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">

                <i class="bi bi-person-fill"></i>

                <span>Profil</span>

            </a>


            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <a href="#"
                   onclick="event.preventDefault(); this.closest('form').submit();">

                    <i class="bi bi-box-arrow-right"></i>

                    <span>Logout</span>

                </a>

            </form>

        </nav>

    </aside>


    {{-- AREA KANAN --}}
    <div class="sipika-content">

        {{-- TOPBAR --}}
        <header class="sipika-topbar">

            <button type="button" class="hamburger">

                <i class="bi bi-list"></i>

            </button>


            <div class="topbar-profile">

                <i class="bi bi-person-circle"></i>

            </div>

        </header>


        {{-- JUDUL HALAMAN --}}
        <div class="page-title">

            <h1>
                @yield('title')
            </h1>

        </div>


        {{-- CONTENT --}}
        <main class="sipika-main">

            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif

            @yield('content')

        </main>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

@stack('scripts')

</body>
</html>
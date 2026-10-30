
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - @yield('title')</title>

    {{-- Bootstrap CSS --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    {{-- CSS Utama SIPIKA --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >

    @stack('styles')
</head>

<body>

@php
    $user = auth()->user();

    $isInfaqMassal = request()->routeIs('infaq.massal*');

    $isDataInfaq = request()->routeIs(
        'infaq.index',
        'infaq.create',
        'infaq.store',
        'infaq.edit',
        'infaq.update',
        'infaq.destroy'
    );

    $fotoProfil = $user?->foto_profil;
    $pathFotoProfil = $fotoProfil
        ? public_path('storage/' . $fotoProfil)
        : null;
@endphp

<div class="sipika-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sipika-sidebar">

        {{-- BRAND --}}
        <div class="brand">
            SIPIS
        </div>

        {{-- MENU NAVIGASI --}}
        <nav class="sidebar-menu">

            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>

            {{-- Data Pengeluaran --}}
            <a
                href="{{ route('pengeluaran.index') }}"
                class="{{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}"
            >
                <i class="bi bi-cash-stack"></i>
                <span>Data Pengeluaran</span>
            </a>

            {{-- Data Infaq --}}
            <a
                href="{{ route('infaq.index') }}"
                class="{{ $isDataInfaq ? 'active' : '' }}"
            >
                <i class="bi bi-wallet2"></i>
                <span>Data Infaq</span>
            </a>

            {{-- Input Infaq Massal --}}
            <a
                href="{{ route('infaq.massal') }}"
                class="{{ $isInfaqMassal ? 'active' : '' }}"
            >
                <i class="bi bi-people-fill"></i>
                <span>Input Infaq Massal</span>
            </a>

            {{-- Laporan --}}
            <a
                href="{{ route('laporan.index') }}"
                class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"
            >
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Laporan</span>
            </a>

            {{-- Profil --}}
            <a
                href="{{ route('profil.index') }}"
                class="{{ request()->routeIs('profil.*') ? 'active' : '' }}"
            >
                <i class="bi bi-person-fill"></i>
                <span>Profil</span>
            </a>

            {{-- Logout --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                class="sidebar-logout-form"
            >
                @csrf

                <button
                    type="submit"
                    class="sidebar-logout-button"
                    onclick="return confirm('Yakin ingin keluar dari SIPIKA?')"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        </nav>

    </aside>

    {{-- AREA KONTEN --}}
    <div class="sipika-content">

        {{-- TOPBAR --}}
        <header class="sipika-topbar">

            {{-- Tombol Toggle Sidebar --}}
            <button
                type="button"
                id="sidebarToggle"
                class="hamburger"
                aria-label="Buka atau tutup sidebar"
                aria-expanded="true"
            >
                <i class="bi bi-arrow-left"></i>
            </button>

            {{-- Profil Administrator --}}
            <a
                href="{{ route('profil.index') }}"
                class="topbar-admin"
                aria-label="Buka profil administrator"
            >

                <div class="topbar-admin-info">
                    <span class="topbar-admin-name">
                        {{ $user?->name ?? 'Administrator' }}
                    </span>

                    <span class="topbar-admin-role">
                        Administrator
                    </span>
                </div>

                <div class="topbar-profile">

                    @if(
                        $fotoProfil &&
                        $pathFotoProfil &&
                        file_exists($pathFotoProfil)
                    )
                        <img
                            src="{{ asset('storage/' . $fotoProfil) }}"
                            alt="Foto Profil Administrator"
                            class="topbar-profile-img"
                        >
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif

                </div>

            </a>

        </header>

        {{-- JUDUL HALAMAN --}}
        <div class="page-title">
            <h1>@yield('title')</h1>
        </div>

        {{-- KONTEN UTAMA --}}
        <main class="sipika-main">

            {{-- Notifikasi Berhasil --}}
            @if(session('success'))
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            @endif

            {{-- Notifikasi Peringatan --}}
            @if(session('warning'))
                <div
                    class="alert alert-warning alert-dismissible fade show"
                    role="alert"
                >
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('warning') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >
                    <i class="bi bi-x-circle-fill me-2"></i>
                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

{{-- JAVASCRIPT SIDEBAR --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.querySelector('.sipika-wrapper');
    const toggle = document.getElementById('sidebarToggle');

    if (!wrapper || !toggle) {
        return;
    }

    const icon = toggle.querySelector('i');

    toggle.addEventListener('click', function () {
        const collapsed = wrapper.classList.toggle('sidebar-collapsed');

        if (icon) {
            icon.classList.toggle('bi-arrow-left', !collapsed);
            icon.classList.toggle('bi-arrow-right', collapsed);
        }

        toggle.setAttribute('aria-expanded', String(!collapsed));
    });
});
</script>

{{-- Bootstrap JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Script Tambahan per Halaman --}}
@stack('scripts')

</body>
</html>

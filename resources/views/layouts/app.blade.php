<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem SPPG') - SPPG Terpadu</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <!-- Third Party Plugin(OverlayScrollbars) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <!-- Required Plugin(AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css">
    @yield('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <!-- Header -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <!-- Start Navbar Links -->
            <ul class="navbar-nav">
                <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
                <li class="nav-item d-none d-md-block"> <a href="{{ route('dashboard') }}" class="nav-link">Beranda</a> </li>
            </ul>
            <!-- End Navbar Links -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        @if(session('pengguna.foto_profil'))
                            <img src="{{ asset('uploads/profil/' . session('pengguna.foto_profil')) }}" class="user-image rounded-circle shadow" alt="User Image">
                        @else
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                        @endif
                        <span class="d-none d-md-inline">{{ session('pengguna.nama', 'Staf') }}</span> </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            @if(session('pengguna.foto_profil'))
                                <img src="{{ asset('uploads/profil/' . session('pengguna.foto_profil')) }}" class="rounded-circle shadow" alt="User Image">
                            @else
                                <i class="bi bi-person-circle fs-1"></i>
                            @endif
                            <p>
                                {{ session('pengguna.nama', 'Staf') }}
                                <small>{{ session('pengguna.raw_role', 'Tanpa Peran') }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="{{ route('profil.index') }}" class="btn btn-default btn-flat">Profil Pribadi</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline float-end">
                                @csrf
                                <button type="submit" class="btn btn-default btn-flat">Keluar</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand"> <a href="{{ route('dashboard') }}" class="brand-link"> <i class="bi bi-shield-check brand-image opacity-75"></i> <span class="brand-text fw-light">SPPG Terpadu</span> </a> </div>
        <div class="sidebar-wrapper">
            
            <!-- Sidebar Profile Panel Removed -->

            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    @php $role = session('pengguna.role'); @endphp
                    
                    @if(in_array($role, ['kepala_sppg', 'ahli_gizi', 'petugas']))
                    <li class="nav-item"> <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"> <i class="nav-icon bi bi-speedometer"></i> <p>Dashboard</p> </a> </li>
                    @endif

                    @php 
                        $username = session('pengguna.username'); 
                    @endphp

                    @if($role === 'kepala_sppg' || ($role === 'petugas' && $username !== 'petugasgudang'))
                    <li class="nav-item"> <a href="{{ route('penerima.index') }}" class="nav-link {{ request()->routeIs('penerima.*') ? 'active' : '' }}"> <i class="nav-icon bi bi-people"></i> <p>Penerima Manfaat</p> </a> </li>
                    @endif

                    @if(in_array($role, ['kepala_sppg', 'ahli_gizi']))
                    <li class="nav-item"> <a href="{{ route('menu.index') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"> <i class="nav-icon bi bi-egg-fried"></i> <p>Menu Gizi</p> </a> </li>
                    @endif

                    @if($role === 'kepala_sppg' || ($role === 'petugas' && $username === 'petugasdistribusi'))
                    <li class="nav-item {{ request()->routeIs('distribusi.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('distribusi.*') ? 'active' : '' }}"> <i class="nav-icon bi bi-box-seam"></i> <p>Distribusi <i class="nav-arrow bi bi-chevron-right"></i></p> </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"> <a href="{{ route('distribusi.index') }}" class="nav-link {{ request()->routeIs('distribusi.index') ? 'active' : '' }}"> <i class="nav-icon bi bi-circle"></i> <p>Data Distribusi</p> </a> </li>
                            <li class="nav-item"> <a href="{{ route('distribusi.scan') }}" class="nav-link {{ request()->routeIs('distribusi.scan') ? 'active' : '' }}"> <i class="nav-icon bi bi-qr-code-scan"></i> <p>Scan QR Code</p> </a> </li>
                            <li class="nav-item"> <a href="{{ route('distribusi.riwayat') }}" class="nav-link {{ request()->routeIs('distribusi.riwayat') ? 'active' : '' }}"> <i class="nav-icon bi bi-clock-history"></i> <p>Riwayat</p> </a> </li>
                        </ul>
                    </li>
                    @endif

                    @if($role === 'kepala_sppg' || ($role === 'petugas' && $username === 'petugasgudang'))
                    <li class="nav-item"> <a href="{{ route('logistik.index') }}" class="nav-link {{ request()->routeIs('logistik.*') ? 'active' : '' }}"> <i class="nav-icon bi bi-box2"></i> <p>Logistik Gudang</p> </a> </li>
                    @endif

                    @if($role === 'kepala_sppg')
                    <li class="nav-header">ADMINISTRATOR</li>
                    <li class="nav-item"> <a href="{{ route('akun.index') }}" class="nav-link {{ request()->routeIs('akun.*') ? 'active' : '' }}"> <i class="nav-icon bi bi-person-gear"></i> <p>Manajemen Akun</p> </a> </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('page-title')</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Sistem SPPG</div>
        <strong>Copyright &copy; 2026.</strong> All rights reserved.
    </footer>
</div>

<!-- JavaScripts -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
<script>
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined") {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
@yield('scripts')
</body>
</html>
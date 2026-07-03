<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') | Sistem Rekomendasi</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style (AdminLTE v4) -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('adminlte/assets/img/avatar5.png') }}" class="user-image rounded-circle shadow" alt="User Image">
                            <span class="d-none d-md-inline" id="topbar-admin-name">Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset('adminlte/assets/img/avatar5.png') }}" class="rounded-circle shadow" alt="User Image">
                                <p>
                                    <span id="dropdown-admin-name">Administrator</span>
                                    <small id="dropdown-admin-email">admin@example.com</small>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <button onclick="logout('/admin/login')" class="btn btn-default btn-flat float-end">Sign out</button>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->

        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link">
                    <i class="fas fa-bolt brand-image opacity-75" style="margin-left: .8rem; margin-right: .5rem; font-size: 1.5rem; color: #ffc107;"></i>
                    <span class="brand-text fw-light">Sistem Rekomendasi</span>
                </a>
            </div>
            <!--end::Sidebar Brand-->

            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">MASTER DATA</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.training-centers.index') }}" class="nav-link {{ request()->routeIs('admin.training-centers.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Training Center</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.pelatihan.index') }}" class="nav-link {{ request()->routeIs('admin.pelatihan.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>

                        <li class="nav-header">MANAGEMENT</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>

                        <li class="nav-header">TRANSAKSI</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.enrollments.index') }}" class="nav-link {{ request()->routeIs('admin.enrollments.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Enrollments</p>
                            </a>
                        </li>

                        <li class="nav-header">SYSTEM</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.log-activities.index') }}" class="nav-link {{ request()->routeIs('admin.log-activities.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Activity Log</p>
                            </a>
                        </li>
                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">@yield('page_title')</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::App Content Header-->

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">SRTP V2</div>
            <strong>Copyright &copy; 2026 <a href="#" class="text-decoration-none">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!-- Global API JS -->
    <script src="{{ asset('js/api.js') }}"></script>

    <!-- AdminLTE JS (Bootstrap 5 bundle included usually, or we add BS5 explicitly if needed by v4) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Note: We are mocking the adminlte js here if the file is missing, but assuming it exists -->
    <script src="{{ asset('adminlte/js/adminlte.js') }}" onerror="console.warn('adminlte.js not found, layout might degrade slightly')"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const user = window.getApiUser();

            // Protect Admin Layout
            if (!window.getApiToken() || !user) {
                window.location.href = '/admin/login';
            } else if (user.role !== 'admin') {
                window.location.href = '/user/dashboard';
            } else {
                // Populate Admin Data
                document.getElementById('topbar-admin-name').textContent = user.name;
                document.getElementById('dropdown-admin-name').textContent = user.name;
                document.getElementById('dropdown-admin-email').textContent = user.email;
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
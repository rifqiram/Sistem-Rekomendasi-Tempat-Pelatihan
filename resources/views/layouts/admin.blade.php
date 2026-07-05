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
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* SRTP Admin Custom Theme Override */
        :root {
            --bs-primary: #4f46e5;
            --bs-primary-rgb: 79, 70, 229;
            --bs-link-color: #4f46e5;
            --bs-link-hover-color: #3730a3;
        }

        .app-sidebar[data-bs-theme="dark"] {
            background-color: #1e293b !important; /* Darker Slate for enterprise look */
        }

        .sidebar-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .nav-link.active {
            background-color: rgba(79, 70, 229, 0.9) !important;
            color: #ffffff !important;
        }

        .text-bg-primary {
            background-color: var(--bs-primary) !important;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        /* Global SweetAlert Modernization */
        .swal2-popup {
            border-radius: 1rem !important;
            padding: 1.5rem !important;
        }
        .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }
        .swal2-html-container {
            color: #64748b !important;
            font-size: 0.95rem !important;
        }
        .swal2-confirm {
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            padding: 0.6rem 1.5rem !important;
            background-color: var(--bs-primary) !important;
        }
        .swal2-cancel {
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            padding: 0.6rem 1.5rem !important;
        }
    </style>
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
                    <i class="fas fa-bolt brand-image opacity-75" style="margin-left: .8rem; margin-right: .5rem; font-size: 1.5rem; color: #38bdf8;"></i>
                    <span class="brand-text fw-semibold">Admin SRTP</span>
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
            <div class="float-end d-none d-sm-inline fw-semibold text-muted">Versi 2.0</div>
            <strong>Copyright &copy; 2026 <a href="#" class="text-decoration-none" style="color: var(--bs-primary);">Sistem Rekomendasi Tempat Pelatihan</a>.</strong> All rights reserved.
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!-- Global API JS -->
    <script src="{{ asset('js/api.js') }}"></script>

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('adminlte/js/adminlte.js') }}" onerror="console.warn('adminlte.js not found')"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        // Global SweetAlert Helpers
        window.showAlert = function(type, title, message) {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                confirmButtonText: 'Mengerti',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary swal2-confirm'
                }
            });
        };

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.showToast = function(type, title) {
            Toast.fire({
                icon: type,
                title: title
            });
        };

        window.confirmAction = function(title, text, confirmText, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText || 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger swal2-confirm me-2',
                    cancelButton: 'btn btn-secondary swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        };

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
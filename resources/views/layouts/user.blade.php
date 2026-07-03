<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'User Dashboard') | Sistem Rekomendasi</title>

    <!-- Google Font: Plus Jakarta Sans (Modern Look for Users) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-user {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .nav-link {
            font-weight: 500;
            color: #4b5563;
        }
        .nav-link.active, .nav-link:hover {
            color: #4f46e5;
        }
        .main-content {
            min-height: 80vh;
            padding: 30px 0;
        }
        .footer-user {
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 20px 0;
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-user sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('user.dashboard') }}">
                <i class="fas fa-bolt text-warning me-1"></i> SRTP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">Profil Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.questionnaire') ? 'active' : '' }}" href="{{ route('user.questionnaire') }}">Kuesioner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.recommendations') ? 'active' : '' }}" href="{{ route('user.recommendations') }}">Rekomendasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.enrollments') ? 'active' : '' }}" href="{{ route('user.enrollments') }}">Riwayat Pendaftaran</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;" id="user-avatar">
                                U
                            </div>
                            <span id="user-name">Pengguna</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small" id="user-email">user@example.com</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i> Pengaturan Akun</a></li>
                            <li><button class="dropdown-item text-danger" onclick="logout('/user/login')"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-user text-center">
        <div class="container">
            &copy; 2026 Sistem Rekomendasi Tempat Pelatihan. Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Global API JS -->
    <script src="{{ asset('js/api.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const user = window.getApiUser();

            // Skip protection logic on auth pages
            if (window.location.pathname === '/user/login' || window.location.pathname === '/user/register') {
                return;
            }

            // Protect User Layout
            if (!window.getApiToken() || !user) {
                window.location.href = '/user/login';
            } else if (user.role === 'admin') {
                window.location.href = '/admin/dashboard';
            } else {
                // Populate User Data
                const displayName = user.name || 'Pengguna';
                const userNameEl = document.getElementById('user-name');
                const userEmailEl = document.getElementById('user-email');
                const userAvatarEl = document.getElementById('user-avatar');

                if (userNameEl) userNameEl.textContent = displayName;
                if (userEmailEl) userEmailEl.textContent = user.email || '';
                if (userAvatarEl) userAvatarEl.textContent = displayName.charAt(0).toUpperCase();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
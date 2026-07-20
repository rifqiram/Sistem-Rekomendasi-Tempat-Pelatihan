@extends('layouts.auth')

@section('title', 'Masuk Admin | SRTP')

@push('styles')
<style>
    body {
        background-color: #F8FAFC;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        background: #FFFFFF;
        border: none;
        border-radius: 18px;
        box-shadow: 0 12px 40px rgba(15,23,42,.10);
        overflow: hidden;
        animation: cardFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform: scale(0.98);
        opacity: 0;
    }

    @keyframes cardFadeIn {
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .auth-header {
        padding: 32px 32px 0 32px;
        text-align: center;
    }

        .auth-header img {
        height: auto;
        width: 200px;
        object-fit: contain;
        margin-bottom: 12px;
    }

    .auth-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 24px 0;
        line-height: 1.4;
    }

    .divider {
        height: 1px;
        background-color: #E2E8F0;
        margin: 24px 32px;
    }

    .auth-body {
        padding: 0 32px;
    }

    .auth-body .login-title {
        font-size: 26px;
        font-weight: 600;
        color: #111827;
        text-align: center;
        margin: 0 0 30px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        height: 48px;
        padding: 0 16px;
        border-radius: 8px;
        border: 1px solid #CBD5E1;
        font-size: 0.95rem;
        color: #111827;
        background-color: #FFFFFF;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .auth-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .remember-me input[type="checkbox"] {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1px solid #CBD5E1;
        accent-color: #3B82F6;
        cursor: pointer;
    }

    .remember-me label {
        font-size: 0.875rem;
        color: #475569;
        font-weight: 500;
        margin: 0;
        cursor: pointer;
    }

    .forgot-password {
        font-size: 0.875rem;
        font-weight: 600;
        color: #3B82F6;
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-password:hover {
        color: #2563EB;
        text-decoration: underline;
    }

    .auth-submit-btn {
        width: 100%;
        height: 48px;
        background: #3B82F6;
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-submit-btn:hover {
        background: #2563EB;
    }

    .auth-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .auth-footer {
        padding: 0 32px 20px 32px;
        text-align: center;
    }

    .auth-footer p {
        margin: 0;
        font-size: 12px;
        color: #94A3B8;
    }

    @media (max-width: 640px) {
        .auth-wrapper {
            padding: 1rem;
        }
        .auth-header {
            padding: 24px 24px 0 24px;
        }
        .auth-body {
            padding: 0 24px;
        }
        .auth-footer {
            padding: 0 24px 20px 24px;
        }
        .divider {
            margin: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-header">
            <img src="{{ asset('images/logoSRTP.png') }}" alt="Logo SRTP">
            <h2>Kabupaten Magetan</h2>
        </div>

        <div class="divider"></div>

        <div class="auth-body">
            <h3 class="login-title">Halaman Login Admin</h3>

            <form id="adminLoginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" required>
                </div>

                <div class="auth-actions">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-password" onclick="event.preventDefault(); window.showToast('Fitur Lupa Password segera hadir', 'info');">Lupa Password?</a>
                </div>

                <button type="submit" class="auth-submit-btn" id="loginSubmit">
                    Masuk
                </button>
            </form>
        </div>

        <div class="divider"></div>

        <div class="auth-footer">
            <p>&copy; 2026 Sistem Rekomendasi Tempat Pelatihan</p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const loginForm = document.getElementById('adminLoginForm');
    const loginSubmit = document.getElementById('loginSubmit');

    async function redirectIfLoggedIn() {
        const token = getApiToken();
        const user = getApiUser();

        if (token && user && user.role === 'admin') {
            window.location.href = '/admin/dashboard';
        } else if (token && user) {
            window.location.href = '/user/dashboard';
        }
    }

    redirectIfLoggedIn();

    loginForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const originalText = loginSubmit.innerHTML;
        loginSubmit.disabled = true;
        loginSubmit.innerHTML = 'Memproses...';

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        try {
            const response = await fetch(window.apiBase + '/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) {
                const error = await response.json();
                window.showError('Login Gagal', error.message || 'Periksa kembali email dan password Anda.');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;

            // Pastikan role admin
            if (data.user.role !== 'admin') {
                window.showError('Akses Ditolak', 'Hanya administrator yang bisa masuk dari halaman ini.');
                return;
            }

            window.showToast('Berhasil masuk', 'success');

            setApiToken(data.token);
            setApiUser(data.user);

            setTimeout(() => {
                window.location.href = '/admin/dashboard';
            }, 800);

        } catch (error) {
            window.showError('Koneksi Terputus', 'Terjadi kesalahan jaringan. Silakan coba beberapa saat lagi.');
        } finally {
            if(!loginSubmit.disabled || loginSubmit.innerHTML.includes('Memproses')) {
                loginSubmit.disabled = false;
                loginSubmit.innerHTML = originalText;
            }
        }
    });
</script>
@endpush
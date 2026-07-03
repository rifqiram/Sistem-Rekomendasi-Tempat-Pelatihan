@extends('layouts.auth')

@section('title', 'Login Admin | SRTP')

@section('content')
<style>
    .admin-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-container {
        width: 100%;
        max-width: 400px;
        padding: 24px;
    }
    .login-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
        margin-bottom: 28px;
    }
    .login-brand .logo-box {
        width: 36px; height: 36px;
        background: #4f46e5;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px;
    }
    .login-brand .logo-name {
        font-size: 20px; font-weight: 700;
        color: #111827; letter-spacing: -0.3px;
    }
    .login-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .login-card h2 {
        font-size: 20px; font-weight: 700;
        color: #111827; margin: 0 0 4px;
        text-align: center;
    }
    .login-card .subtitle {
        font-size: 13.5px; color: #6b7280;
        text-align: center; margin: 0 0 24px;
    }
    .login-divider {
        height: 1px; background: #e5e7eb;
        margin: 20px 0;
    }
    .login-submit-btn {
        width: 100%;
        padding: 10px;
        background: #4f46e5;
        color: #fff; border: none;
        border-radius: 6px;
        font-size: 14px; font-weight: 600;
        cursor: pointer; font-family: inherit;
        transition: background 0.15s ease, transform 0.15s ease;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .login-submit-btn:hover { background: #4338ca; transform: translateY(-1px); }
    .login-submit-btn:active { transform: none; }
    .login-hint {
        margin-top: 20px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        font-size: 12px;
        color: #6b7280;
        text-align: center;
        line-height: 1.6;
    }
    .login-hint code {
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 11.5px;
        color: #4f46e5;
        font-weight: 500;
    }
    .input-with-icon { position: relative; }
    .input-with-icon .form-control { padding-right: 38px; }
    .input-with-icon .field-icon {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 13px; pointer-events: none;
    }
    .form-label-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 5px;
    }
    .form-label-row label { font-size: 12.5px; font-weight: 500; color: #6b7280; }
</style>

<div class="login-container">
    {{-- Brand --}}
    <div class="login-brand">
        <div class="logo-box"><i class="fas fa-bolt"></i></div>
        <span class="logo-name">SRTP Admin</span>
    </div>

    {{-- Card --}}
    <div class="login-card">
        <h2>Masuk ke Panel Admin</h2>
        <p class="subtitle">Masukkan kredensial Anda untuk melanjutkan</p>

        <div id="loginAlert" class="alert alert-danger d-none" style="margin-bottom:16px;"></div>

        <form id="loginForm">
            <div class="form-group">
                <div class="form-label-row">
                    <label for="email">Alamat Email</label>
                </div>
                <div class="input-with-icon">
                    <input type="email" id="email" class="form-control" placeholder="Masukkan email Anda" required>
                    <span class="field-icon"><i class="fas fa-envelope"></i></span>
                </div>
            </div>

            <div class="form-group">
                <div class="form-label-row">
                    <label for="password">Password</label>
                </div>
                <div class="input-with-icon">
                    <input type="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <span class="field-icon"><i class="fas fa-lock"></i></span>
                </div>
            </div>

            <div class="login-divider"></div>

            <button type="submit" class="login-submit-btn" id="loginSubmit">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="login-hint">
            Demo: <code>admin@example.com</code> &nbsp;/&nbsp; <code>password</code>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const loginForm = document.getElementById('loginForm');
    const loginAlert = document.getElementById('loginAlert');
    const loginSubmit = document.getElementById('loginSubmit');

    async function redirectToDashboard() {
        const token = getApiToken();
        const user = getApiUser();

        if (!token) {
            return;
        }

        if (user && user.role) {
            window.location.href = user.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
            return;
        }

        try {
            const response = await fetch(window.apiBase + '/me', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            const user = payload.data?.user ?? payload.user;
            setApiUser(user);
            window.location.href = user.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
        } catch (error) {
            // ignore and allow login form to show
        }
    }

    redirectToDashboard();

    loginForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        loginAlert.classList.add('d-none');
        loginSubmit.disabled = true;
        loginSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        try {
            const response = await fetch(window.apiBase + '/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) {
                const error = await response.json();
                loginAlert.textContent = error.message || 'Login gagal. Periksa kembali email dan password Anda.';
                loginAlert.classList.remove('d-none');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;
            setApiToken(data.token);
            setApiUser(data.user);
            window.location.href = data.user.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
        } catch (error) {
            loginAlert.textContent = 'Terjadi kesalahan jaringan. Coba ulangi.';
            loginAlert.classList.remove('d-none');
        } finally {
            loginSubmit.disabled = false;
            loginSubmit.innerHTML = '<i class="fas fa-sign-in-alt"></i> Masuk';
        }
    });
</script>
@endpush

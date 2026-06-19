@extends('admin.Layout.layout')

@section('content')
<style>
    body { background: var(--color-bg); }
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--color-bg);
    }
    .auth-card {
        width: 100%;
        max-width: 420px;
        padding: 28px;
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }
    .auth-card h2 {
        margin-bottom: 6px;
    }
    .auth-card .subtitle {
        margin-bottom: 24px;
        color: var(--color-text-secondary);
    }
    .auth-card .form-group { margin-bottom: 16px; }
    .auth-card .form-control { width: 100%; padding: 12px 14px; }
    .auth-submit-btn {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: var(--radius-sm);
        background: var(--color-primary);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .auth-submit-btn:hover { background: var(--color-primary-hover); }
    .auth-link { display: block; margin-top: 18px; text-align: center; color: var(--color-primary); }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Masuk sebagai Pengguna</h2>
        <p class="subtitle">Gunakan email dan password Anda untuk mengakses dashboard user.</p>

        <div id="loginAlert" class="alert alert-danger d-none" style="margin-bottom:16px;"></div>

        <form id="userLoginForm">
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="auth-submit-btn" id="loginSubmit">
                Masuk
            </button>
        </form>

        <a href="{{ route('user.register') }}" class="auth-link">Belum punya akun? Daftar disini</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const loginForm = document.getElementById('userLoginForm');
    const loginAlert = document.getElementById('loginAlert');
    const loginSubmit = document.getElementById('loginSubmit');

    async function redirectIfLoggedIn() {
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
            if (!response.ok) return;
            const payload = await response.json();
            const user = payload.data?.user ?? payload.user;
            setApiUser(user);
            window.location.href = user.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
        } catch (error) {
            // ignore
        }
    }

    redirectIfLoggedIn();

    loginForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        loginAlert.classList.add('d-none');
        loginSubmit.disabled = true;
        loginSubmit.textContent = 'Memproses...';

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
            loginSubmit.textContent = 'Masuk';
        }
    });
</script>
@endpush

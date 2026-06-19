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
        max-width: 460px;
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
        <h2>Daftar Pengguna</h2>
        <p class="subtitle">Buat akun baru untuk mengakses dashboard user.</p>

        <div id="registerAlert" class="alert alert-danger d-none" style="margin-bottom:16px;"></div>

        <form id="userRegisterForm">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" class="form-control" placeholder="Masukkan nama" required>
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="form-group">
                <label for="passwordConfirmation">Ulangi Password</label>
                <input type="password" id="passwordConfirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="auth-submit-btn" id="registerSubmit">
                Daftar
            </button>
        </form>

        <a href="{{ route('user.login') }}" class="auth-link">Sudah punya akun? Masuk disini</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const registerForm = document.getElementById('userRegisterForm');
    const registerAlert = document.getElementById('registerAlert');
    const registerSubmit = document.getElementById('registerSubmit');

    async function redirectIfLoggedIn() {
        const token = getApiToken();
        const user = getApiUser();

        if (!token) return;
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
        } catch (error) {}
    }

    redirectIfLoggedIn();

    registerForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        registerAlert.classList.add('d-none');
        registerSubmit.disabled = true;
        registerSubmit.textContent = 'Memproses...';

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const passwordConfirmation = document.getElementById('passwordConfirmation').value.trim();

        if (password !== passwordConfirmation) {
            registerAlert.textContent = 'Password tidak cocok.';
            registerAlert.classList.remove('d-none');
            registerSubmit.disabled = false;
            registerSubmit.textContent = 'Daftar';
            return;
        }

        try {
            const response = await fetch(window.apiBase + '/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation: passwordConfirmation,
                }),
            });

            if (!response.ok) {
                const error = await response.json();
                registerAlert.textContent = error.message || 'Pendaftaran gagal. Periksa input Anda.';
                registerAlert.classList.remove('d-none');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;
            setApiToken(data.token);
            setApiUser(data.user);
            window.location.href = '/user/dashboard';
        } catch (error) {
            registerAlert.textContent = 'Terjadi kesalahan jaringan. Coba ulangi.';
            registerAlert.classList.remove('d-none');
        } finally {
            registerSubmit.disabled = false;
            registerSubmit.textContent = 'Daftar';
        }
    });
</script>
@endpush

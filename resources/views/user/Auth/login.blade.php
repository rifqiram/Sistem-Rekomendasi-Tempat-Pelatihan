@extends('layouts.auth')

@section('title', 'Login User | SRTP')

@push('styles')
<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
    }

    .auth-container {
        width: 100%;
        max-width: 420px;
    }

    .auth-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 2rem;
    }

    .auth-brand .logo-box {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--secondary-color, #10b981), #34d399);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.2rem;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }

    .auth-brand .logo-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .auth-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 1rem;
        padding: 2.5rem 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
    }

    .auth-card h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 0.5rem;
        text-align: center;
    }

    .auth-card .subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-align: center;
        margin: 0 0 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .form-label-row label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon .form-control {
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 0.5rem;
        border-color: #cbd5e1;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .input-with-icon .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .input-with-icon .field-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.2s;
    }

    .input-with-icon .form-control:focus + .field-icon,
    .input-with-icon .form-control:not(:placeholder-shown) + .field-icon {
        color: var(--primary-color);
    }

    .auth-submit-btn {
        width: 100%;
        padding: 0.875rem;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        margin-top: 1.5rem;
    }

    .auth-submit-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.3);
    }

    .auth-submit-btn:active {
        transform: none;
    }

    .auth-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 1.5rem 0;
    }

    .auth-link {
        display: block;
        text-align: center;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .auth-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        {{-- Brand --}}
        <div class="auth-brand">
            <div class="logo-box"><i class="fas fa-user-graduate"></i></div>
            <span class="logo-name">Portal Siswa</span>
        </div>

        <div class="auth-card">
            <h2>Masuk ke Akun Anda</h2>
            <p class="subtitle">Gunakan email dan password Anda untuk melanjutkan pencarian pelatihan.</p>

            <form id="userLoginForm">
                <div class="form-group">
                    <div class="form-label-row">
                        <label for="email">Alamat Email</label>
                    </div>
                    <div class="input-with-icon">
                        <input type="email" id="email" class="form-control" placeholder="Masukkan alamat email" required>
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

                <button type="submit" class="auth-submit-btn" id="loginSubmit">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div class="auth-divider"></div>

            <a href="{{ route('user.register') }}" class="auth-link">
                Belum punya akun? <span style="text-decoration: underline;">Daftar di sini</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const loginForm = document.getElementById('userLoginForm');
    const loginSubmit = document.getElementById('loginSubmit');

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
            const userData = payload.data?.user ?? payload.user;
            setApiUser(userData);
            // Modified: Force regular users to check their profile/questionnaire status first
            window.location.href = userData.role === 'admin' ? '/admin/dashboard' : '/user/profile';
        } catch (error) {
            // ignore
        }
    }

    redirectIfLoggedIn();

    loginForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const originalText = loginSubmit.innerHTML;
        loginSubmit.disabled = true;
        loginSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

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
                window.showAlert('error', 'Login Gagal', error.message || 'Periksa kembali email dan password Anda.');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;

            window.showToast('success', 'Berhasil masuk');

            setApiToken(data.token);
            setApiUser(data.user);

            setTimeout(() => {
                // Modified: Force regular users to check their profile/questionnaire status first
                window.location.href = data.user.role === 'admin' ? '/admin/dashboard' : '/user/profile';
            }, 800);

        } catch (error) {
            window.showAlert('error', 'Koneksi Terputus', 'Terjadi kesalahan jaringan. Silakan coba beberapa saat lagi.');
        } finally {
            if(!loginSubmit.disabled || loginSubmit.innerHTML.includes('Memproses')) {
                loginSubmit.disabled = false;
                loginSubmit.innerHTML = originalText;
            }
        }
    });
</script>
@endpush
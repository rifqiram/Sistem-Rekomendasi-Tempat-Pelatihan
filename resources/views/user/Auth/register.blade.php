@extends('layouts.auth')

@section('title', 'Daftar Akun | SRTP')

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
        max-width: 480px; /* Slightly wider than login for the 2-column layout or just general comfort with more fields */
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
        padding: 28px 28px 0 28px;
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
        margin: 24px 28px;
    }

    .auth-body {
        padding: 0 28px;
    }

    .auth-body .login-title {
        font-size: 24px;
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

    .form-group label .required {
        color: #EF4444;
        margin-left: 2px;
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

    .form-control.is-invalid {
        border-color: #EF4444;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .field-hint {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #6B7280;
    }

    .field-error {
        display: none;
        margin-top: 6px;
        font-size: 12.5px;
        color: #EF4444;
        font-weight: 500;
    }

    .field-error.show {
        display: block;
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
        gap: 8px;
        margin-top: 10px;
    }

    .auth-submit-btn:hover {
        background: #2563EB;
    }

    .auth-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .register-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        font-size: 0.875rem;
        color: #6B7280;
    }

    .register-link a {
        color: #3B82F6;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .auth-footer {
        padding: 0 28px 20px 28px;
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
            <h3 class="login-title">Daftar Akun Baru</h3>

            <form id="userRegisterForm" novalidate>
                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="name" class="form-control" required autofocus maxlength="255">
                    <small class="field-hint">Sesuai KTP atau nama asli Anda</small>
                    <small class="field-error" id="nameError"></small>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email <span class="required">*</span></label>
                    <input type="email" id="email" class="form-control" required>
                    <small class="field-error" id="emailError"></small>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" class="form-control" required minlength="8">
                    <small class="field-hint">Minimal 8 karakter</small>
                    <small class="field-error" id="passwordError"></small>
                </div>

                <div class="form-group">
                    <label for="passwordConfirmation">Ulangi Password <span class="required">*</span></label>
                    <input type="password" id="passwordConfirmation" class="form-control" required>
                    <small class="field-error" id="passwordConfirmationError"></small>
                </div>

                <button type="submit" class="auth-submit-btn" id="registerSubmit">
                    Daftar Sekarang
                </button>

                <div class="register-link">
                    Sudah punya akun? <a href="{{ route('user.login') }}">Masuk di sini</a>
                </div>
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
    const registerForm = document.getElementById('userRegisterForm');
    const registerSubmit = document.getElementById('registerSubmit');

    function showFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const error = document.getElementById(fieldId + 'Error');
        if (input) input.classList.add('is-invalid');
        if (error) {
            error.textContent = message;
            error.classList.add('show');
        }
    }

    function clearFieldErrors() {
        document.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.field-error.show').forEach(el => el.classList.remove('show'));
    }

    function focusFirstError() {
        const first = document.querySelector('.form-control.is-invalid');
        if (first) first.focus();
    }

    function validateForm() {
        clearFieldErrors();
        let valid = true;
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        if (!name) {
            showFieldError('name', 'Nama lengkap wajib diisi.');
            valid = false;
        } else if (name.length < 3) {
            showFieldError('name', 'Nama minimal 3 karakter.');
            valid = false;
        }

        if (!email) {
            showFieldError('email', 'Email wajib diisi.');
            valid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showFieldError('email', 'Email harus berformat valid (nama@domain.com)');
            valid = false;
        }

        if (!password) {
            showFieldError('password', 'Password wajib diisi.');
            valid = false;
        } else if (password.length < 8) {
            showFieldError('password', 'Password minimal 8 karakter.');
            valid = false;
        }

        if (!passwordConfirmation) {
            showFieldError('passwordConfirmation', 'Konfirmasi password wajib diisi.');
            valid = false;
        } else if (password !== passwordConfirmation) {
            showFieldError('passwordConfirmation', 'Password tidak cocok. Periksa kembali.');
            valid = false;
        }

        if (!valid) focusFirstError();
        return valid;
    }

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
            window.location.href = userData.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
        } catch (error) {}
    }

    redirectIfLoggedIn();

    registerForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (!validateForm()) return;

        const originalText = registerSubmit.innerHTML;
        registerSubmit.disabled = true;
        registerSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const passwordConfirmation = document.getElementById('passwordConfirmation').value.trim();

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
                clearFieldErrors();

                if (error.errors) {
                    Object.keys(error.errors).forEach(field => {
                        const messages = Array.isArray(error.errors[field]) ? error.errors[field] : [error.errors[field]];
                        showFieldError(field, messages[0]);
                    });
                    focusFirstError();
                    return;
                }

                window.showError('Pendaftaran Gagal', error.message || 'Periksa kembali input Anda.');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;

            window.showToast('Berhasil mendaftar', 'success');

            setApiToken(data.token);
            setApiUser(data.user);

            setTimeout(() => {
                window.location.href = '/user/profile';
            }, 800);

        } catch (error) {
            window.showError('Terjadi Kesalahan', 'Gagal terhubung ke server. Coba beberapa saat lagi.');
        } finally {
            if(!registerSubmit.disabled || registerSubmit.innerHTML.includes('Memproses')) {
                registerSubmit.disabled = false;
                registerSubmit.innerHTML = originalText;
            }
        }
    });

    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
            const error = document.getElementById(this.id + 'Error');
            if (error) error.classList.remove('show');
        });
    });
</script>
@endpush
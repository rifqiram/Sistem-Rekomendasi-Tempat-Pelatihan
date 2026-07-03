@extends('layouts.auth')

@section('title', 'Daftar User | SRTP')

@section('content')
<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .auth-card {
        width: 100%;
        max-width: 460px;
        padding: 32px 28px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .auth-card h2 {
        margin-bottom: 6px;
        font-size: 22px;
        letter-spacing: -0.3px;
        font-weight: 700;
        color: #111827;
    }
    .auth-card .subtitle {
        margin-bottom: 24px;
        color: #6b7280;
        font-size: 13.5px;
        line-height: 1.5;
    }
    .auth-card .form-group { margin-bottom: 18px; }
    .auth-card .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    .auth-card .form-group label .required { color: #ef4444; margin-left: 2px; }
    .auth-card .form-control {
        width: 100%;
        padding: 12px 14px;
        font-size: 14px;
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
        font-family: inherit;
        background: #fff;
        color: #111827;
    }
    .auth-card .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
    }
    .auth-card .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
    }
    .auth-card .form-control::placeholder { color: #9ca3af; font-size: 13px; }

    .field-hint {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #6b7280;
    }
    .field-error {
        display: none;
        margin-top: 4px;
        font-size: 12.5px;
        color: #ef4444;
        font-weight: 500;
    }
    .field-error.show { display: block; }

    .auth-submit-btn {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 6px;
        background: #4f46e5;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.1s;
        font-family: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .auth-submit-btn:hover { background: #4338ca; }
    .auth-submit-btn:active { transform: scale(0.98); }
    .auth-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    .auth-submit-btn .spinner {
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .auth-link {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #4f46e5;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
    }
    .auth-link:hover { text-decoration: underline; }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
    }
    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
    }
    .alert-success {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .form-helper {
        margin-top: 6px;
        padding: 10px 14px;
        background: #f9fafb;
        border-radius: 6px;
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
    }

    .privacy-note {
        margin-top: 16px;
        text-align: center;
        font-size: 11.5px;
        color: #9ca3af;
        line-height: 1.5;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Daftar Pengguna</h2>
        <p class="subtitle">Buat akun baru untuk mengakses layanan pelatihan dan rekomendasi keahlian.</p>

        <div id="registerAlert" class="alert alert-danger d-none">
            <i class="fas fa-exclamation-circle"></i>
            <span id="registerAlertText"></span>
        </div>

        <form id="userRegisterForm" novalidate>
            <p style="margin-bottom: 18px; font-size: 12.5px; color: var(--color-text-secondary);">
                <i class="fas fa-info-circle" style="color: var(--color-primary);"></i>
                Isi data dengan benar agar pendaftaran diproses cepat.
            </p>

            <div class="form-group">
                <label for="name">Nama Lengkap <span class="required">*</span></label>
                <input type="text" id="name" class="form-control" placeholder="cth: Budi Santoso" required autofocus maxlength="255">
                <small class="field-hint">Sesuai KTP / nama yang biasa digunakan</small>
                <small class="field-error" id="nameError"></small>
            </div>
            <div class="form-group">
                <label for="email">Alamat Email <span class="required">*</span></label>
                <input type="email" id="email" class="form-control" placeholder="cth: nama@domain.com" required>
                <small class="field-hint">Akan digunakan untuk login ke sistem</small>
                <small class="field-error" id="emailError"></small>
            </div>
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
                <small class="field-hint">Gunakan kombinasi huruf dan angka untuk keamanan</small>
                <small class="field-error" id="passwordError"></small>
            </div>
            <div class="form-group">
                <label for="passwordConfirmation">Ulangi Password <span class="required">*</span></label>
                <input type="password" id="passwordConfirmation" class="form-control" placeholder="Ketik ulang password" required>
                <small class="field-hint">Harus sama dengan password di atas</small>
                <small class="field-error" id="passwordConfirmationError"></small>
            </div>

            <button type="submit" class="auth-submit-btn" id="registerSubmit">
                <span id="registerBtnText">Daftar</span>
            </button>
        </form>

        <a href="{{ route('user.login') }}" class="auth-link">Sudah punya akun? Masuk disini</a>
        <p class="privacy-note">Data Anda digunakan hanya untuk kebutuhan pendaftaran dan akses pelatihan.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const registerForm = document.getElementById('userRegisterForm');
    const registerAlert = document.getElementById('registerAlert');
    const registerAlertText = document.getElementById('registerAlertText');
    const registerSubmit = document.getElementById('registerSubmit');
    const registerBtnText = document.getElementById('registerBtnText');

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

    function showAlert(message, type) {
        registerAlertText.textContent = message;
        registerAlert.className = 'alert alert-' + (type || 'danger');
        registerAlert.classList.remove('d-none');
    }

    function setLoading(isLoading) {
        registerSubmit.disabled = isLoading;
        registerBtnText.textContent = isLoading ? 'Memproses...' : 'Daftar';
        if (isLoading) {
            registerBtnText.insertAdjacentHTML('beforebegin', '<span class="spinner" id="registerSpinner"></span>');
        } else {
            const spinner = document.getElementById('registerSpinner');
            if (spinner) spinner.remove();
        }
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
            const user = payload.data?.user ?? payload.user;
            setApiUser(user);
            window.location.href = user.role === 'admin' ? '/admin/dashboard' : '/user/dashboard';
        } catch (error) {}
    }

    redirectIfLoggedIn();

    registerForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        registerAlert.classList.add('d-none');

        if (!validateForm()) return;

        setLoading(true);

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

                // Handle field-level errors from Laravel validation
                if (error.errors) {
                    Object.keys(error.errors).forEach(field => {
                        const messages = Array.isArray(error.errors[field]) ? error.errors[field] : [error.errors[field]];
                        showFieldError(field, messages[0]);
                    });
                    focusFirstError();
                }

                showAlert(error.message || 'Pendaftaran gagal. Periksa kembali input Anda.', 'danger');
                return;
            }

            const payload = await response.json();
            const data = payload.data ?? payload;
            setApiToken(data.token);
            setApiUser(data.user);
            window.location.href = '/user/dashboard';
        } catch (error) {
            showAlert('Terjadi kesalahan jaringan. Coba ulangi.', 'danger');
        } finally {
            setLoading(false);
        }
    });

    // Clear field error on input
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
            const error = document.getElementById(this.id + 'Error');
            if (error) error.classList.remove('show');
        });
    });
</script>
@endpush

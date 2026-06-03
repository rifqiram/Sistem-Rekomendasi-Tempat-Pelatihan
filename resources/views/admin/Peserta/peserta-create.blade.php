@extends('admin.Layout.layout')

@section('content')
<aside class="admin-sidebar" id="adminSidebar">
    <a href="#" class="sidebar-brand">
        <div class="brand-logo"><i class="fas fa-bolt"></i></div>
        <span class="brand-text">PelatihanIT</span>
    </a>

    <nav class="sidebar-nav">
        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:2px;">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#dashboard" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#pelatihan" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <p>Pelatihan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#mentor" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                    <p>Mentor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#peserta" class="nav-link active">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <p>Peserta</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#pendaftaran" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-file-signature"></i></span>
                    <p>Pendaftaran</p>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" id="logoutButton">
            <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
            Logout
        </button>
    </div>
</aside>

<div class="admin-main">
    <header class="admin-topbar">
        <button id="hamburgerBtn" class="hamburger-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-search">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Cari sesuatu...">
        </div>
        <div class="topbar-spacer"></div>
        <div class="topbar-actions">
            <div class="topbar-divider"></div>
            <div class="topbar-user">
                <div class="user-avatar-top" id="userInitial">A</div>
                <span class="user-name-top" id="adminEmailTopbar">Admin</span>
                <i class="fas fa-chevron-down" style="font-size:10px; color:var(--color-text-muted); margin-left:2px;"></i>
            </div>
        </div>
    </header>

    <main class="admin-content">
        <div class="page-header">
            <div>
                <h1>Tambah Peserta</h1>
                <p>Isi data peserta baru dan simpan ke sistem.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Form Tambah Peserta</span>
            </div>
            <div class="card-body">
                <form id="pesertaCreateForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama <span class="required">*</span></label>
                                <input type="text" id="pesertaNama" class="form-control" placeholder="Nama lengkap peserta" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" id="pesertaEmail" class="form-control" placeholder="email@domain.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" id="pesertaTelepon" class="form-control" placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keahlian</label>
                                <input type="text" id="pesertaKeahlian" class="form-control" placeholder="Keahlian yang dimiliki">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Peserta
                        </button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function authFetch(url, options = {}) {
        const token = getApiToken();
        if (!token) { window.location.href = '/admin/login'; return; }
        options.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(options.headers || {}),
            'Authorization': 'Bearer ' + token,
        };
        return fetch(url, options).then(async response => {
            if (response.status === 401 || response.status === 403) {
                clearApiToken();
                window.location.href = '/admin/login';
                return Promise.reject('Unauthorized');
            }
            return response;
        });
    }

    document.addEventListener('DOMContentLoaded', async function () {
        if (!getApiToken()) {
            window.location.href = '/admin/login';
            return;
        }
        const user = getApiUser();
        const email = user?.email || 'Administrator';
        document.getElementById('adminEmailTopbar').textContent = email.split('@')[0];
        document.getElementById('userInitial').textContent = (email.charAt(0) || 'A').toUpperCase();

        document.getElementById('pesertaCreateForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            await authFetch(window.apiBase + '/peserta', {
                method: 'POST',
                body: JSON.stringify({
                    nama: document.getElementById('pesertaNama').value,
                    email: document.getElementById('pesertaEmail').value,
                    telepon: document.getElementById('pesertaTelepon').value,
                    keahlian: document.getElementById('pesertaKeahlian').value,
                }),
            });
            window.location.href = '{{ route('admin.dashboard') }}';
        });

        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function (e) {
                e.preventDefault();
                clearApiToken();
                window.location.href = '/admin/login';
            });
        }
    });
</script>
@endpush

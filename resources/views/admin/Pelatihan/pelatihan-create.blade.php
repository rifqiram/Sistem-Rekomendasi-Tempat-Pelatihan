@extends('admin.Layout.layout')

@section('content')
{{-- ===== SIDEBAR ===== --}}
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
                <a href="{{ route('admin.dashboard') }}#pelatihan" class="nav-link active">
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
                <a href="{{ route('admin.dashboard') }}#peserta" class="nav-link">
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
                <h1>Tambah Pelatihan</h1>
                <p>Isi data pelatihan baru dan simpan ke sistem.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Form Tambah Pelatihan</span>
            </div>
            <div class="card-body">
                <form id="pelatihanCreateForm">
                    <div class="form-section">
                        <div class="form-section-title">Informasi Umum</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul <span class="required">*</span></label>
                                    <input type="text" id="pelatihanJudul" class="form-control" placeholder="Masukkan judul pelatihan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mentor</label>
                                    <select id="pelatihanMentor" class="form-control"></select>
                                    <small class="text-muted">Pilih mentor dari data yang tersedia.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea id="pelatihanDeskripsi" class="form-control" placeholder="Deskripsi pelatihan..."></textarea>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section-title">Detail Pelatihan</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <input type="text" id="pelatihanKategori" class="form-control" placeholder="Contoh: Pemrograman">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Level</label>
                                    <input type="text" id="pelatihanLevel" class="form-control" placeholder="Dasar / Menengah / Lanjutan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Durasi</label>
                                    <input type="text" id="pelatihanDurasi" class="form-control" placeholder="Contoh: 30 Jam">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sertifikat</label>
                                    <input type="text" id="pelatihanSertifikat" class="form-control" placeholder="Ya / Tidak">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="required">*</span></label>
                                    <input type="date" id="pelatihanMulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Selesai <span class="required">*</span></label>
                                    <input type="date" id="pelatihanSelesai" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" id="pelatihanStatus" class="form-control" placeholder="Aktif / Nonaktif">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="padding-top:22px;">
                                    <div class="form-check">
                                        <input type="checkbox" id="pelatihanActive" class="form-check-input" checked>
                                        <label class="form-check-label" for="pelatihanActive">Tandai sebagai Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pelatihan
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

    function parseApiResponse(response) {
        return response.json().then(payload => payload.data ?? payload);
    }

    async function loadMentors() {
        const mentors = await authFetch(window.apiBase + '/mentor').then(parseApiResponse);
        const mentorSelect = document.getElementById('pelatihanMentor');
        if (!mentorSelect) return;
        if (!mentors.length) {
            mentorSelect.innerHTML = '<option value="">— Belum ada mentor seeded —</option>';
        } else {
            mentorSelect.innerHTML = '<option value="">— Pilih Mentor —</option>' + mentors.map(m => `<option value="${m.id}">${m.nama}</option>`).join('');
        }
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
        await loadMentors();
    });

    document.getElementById('pelatihanCreateForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        await authFetch(window.apiBase + '/pelatihan', {
            method: 'POST',
            body: JSON.stringify({
                judul: document.getElementById('pelatihanJudul').value,
                deskripsi: document.getElementById('pelatihanDeskripsi').value,
                kategori: document.getElementById('pelatihanKategori').value,
                level: document.getElementById('pelatihanLevel').value,
                durasi: document.getElementById('pelatihanDurasi').value,
                sertifikat: document.getElementById('pelatihanSertifikat').value,
                mentor_id: document.getElementById('pelatihanMentor').value || null,
                tanggal_mulai: document.getElementById('pelatihanMulai').value,
                tanggal_selesai: document.getElementById('pelatihanSelesai').value,
                is_active: document.getElementById('pelatihanActive').checked,
                status: document.getElementById('pelatihanStatus').value,
            }),
        });
        window.location.href = '{{ route('admin.dashboard') }}';
    });

    document.getElementById('logoutButton').addEventListener('click', function (e) {
        e.preventDefault();
        clearApiToken();
        window.location.href = '/admin/login';
    });
</script>
@endpush

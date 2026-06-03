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
                <a href="{{ route('admin.dashboard') }}#pelatihan" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <p>Pelatihan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}#mentor" class="nav-link active">
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
                <h1>Edit Mentor</h1>
                <p id="pageSubtitle">Ubah data mentor yang ada di sistem.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Form Edit Mentor</span>
            </div>
            <div class="card-body">
                <div id="loadingSpinner" style="text-align:center; padding:20px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                </div>
                <form id="mentorEditForm" style="display:none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama <span class="required">*</span></label>
                                <input type="text" id="mentorNama" class="form-control" placeholder="Nama lengkap mentor" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" id="mentorEmail" class="form-control" placeholder="email@domain.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" id="mentorTelepon" class="form-control" placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keahlian</label>
                                <input type="text" id="mentorKeahlian" class="form-control" placeholder="Contoh: Laravel, React, SQL">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px; margin-top: 16px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Mentor
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const token = getApiToken();
    if (!token) {
        window.location.href = '/admin/login';
    }

    const urlParams = new URLSearchParams(window.location.search);
    const mentorId = urlParams.get('id');

    if (!mentorId) {
        document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">ID Mentor tidak ditemukan</p>';
    }

    async function loadMentorData() {
        try {
            const response = await fetch(window.apiBase + '/mentor/' + mentorId, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data mentor (Status: ' + response.status + ')');
            }

            const payload = await response.json();
            const mentor = payload.data || payload;

            console.log('Mentor data:', mentor);

            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('mentorEditForm').style.display = 'block';

            document.getElementById('mentorNama').value = mentor.nama || '';
            document.getElementById('mentorEmail').value = mentor.email || '';
            document.getElementById('mentorTelepon').value = mentor.telepon || '';
            document.getElementById('mentorKeahlian').value = mentor.keahlian || '';

            const user = getApiUser();
            const email = user?.email || 'Administrator';
            document.getElementById('adminEmailTopbar').textContent = email.split('@')[0];
            document.getElementById('userInitial').textContent = (email.charAt(0) || 'A').toUpperCase();

        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">Error: ' + error.message + '</p>';
        }
    }

    loadMentorData();

    document.getElementById('mentorEditForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const data = {
            nama: document.getElementById('mentorNama').value,
            email: document.getElementById('mentorEmail').value,
            telepon: document.getElementById('mentorTelepon').value,
            keahlian: document.getElementById('mentorKeahlian').value,
        };

        console.log('Sending data:', data);

        try {
            const response = await fetch(window.apiBase + '/mentor/' + mentorId, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            console.log('Response:', result);

            if (!response.ok) {
                throw new Error(result.message || 'Gagal update mentor');
            }

            alert('Data mentor berhasil diupdate!');
            window.location.href = '{{ route('admin.dashboard') }}';
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        }
    });

    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', async function () {
            await fetch(window.apiBase + '/logout', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                },
            });
            clearApiToken();
            window.location.href = '/admin/login';
        });
    }
</script>
@endpush

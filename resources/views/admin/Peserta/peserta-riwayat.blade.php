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
                <h1>Riwayat Pelatihan</h1>
                <p id="pageSubtitle">Memuat riwayat pelatihan peserta...</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card mb-4" id="pesertaDetailCard" style="display:none;">
            <div class="card-header">
                <span class="card-title">Informasi Peserta</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <strong>Nama:</strong>
                        <div id="detailNama" style="margin-top:4px; color:var(--color-text-secondary);">—</div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Email:</strong>
                        <div id="detailEmail" style="margin-top:4px; color:var(--color-text-secondary);">—</div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Telepon:</strong>
                        <div id="detailTelepon" style="margin-top:4px; color:var(--color-text-secondary);">—</div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Keahlian:</strong>
                        <div id="detailKeahlian" style="margin-top:4px; color:var(--color-text-secondary);">—</div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Daftar Pelatihan yang Diikuti</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <div id="loadingSpinner" style="text-align:center; padding:20px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                </div>
                <table class="table table-hover text-nowrap" id="riwayatTable" style="display:none;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Pelatihan</th>
                            <th>Mentor</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable">
                        <tr><td colspan="5" style="text-align:center; color:var(--color-text-muted); padding:24px;">Memuat data...</td></tr>
                    </tbody>
                </table>
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
    const pesertaId = urlParams.get('id');

    if (!pesertaId) {
        document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">ID Peserta tidak ditemukan</p>';
    }

    function statusBadge(status) {
        if (!status) return '<span class="badge badge-gray">-</span>';
        const s = status.toLowerCase();
        if (s === 'aktif' || s === 'terdaftar' || s === 'selesai') return `<span class="badge badge-success">${status}</span>`;
        if (s === 'nonaktif' || s === 'ditolak') return `<span class="badge badge-danger">${status}</span>`;
        return `<span class="badge badge-info">${status}</span>`;
    }

    async function loadHistoryData() {
        try {
            const [pesertaRes, historyRes] = await Promise.all([
                fetch(window.apiBase + '/peserta/' + pesertaId, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                }),
                fetch(window.apiBase + '/peserta/' + pesertaId + '/riwayat', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                }),
            ]);

            if (!pesertaRes.ok) {
                throw new Error('Gagal memuat profil peserta (Status: ' + pesertaRes.status + ')');
            }
            if (!historyRes.ok) {
                throw new Error('Gagal memuat riwayat pelatihan (Status: ' + historyRes.status + ')');
            }

            const payloadPeserta = await pesertaRes.json();
            const payloadHistory = await historyRes.json();

            const peserta = payloadPeserta.data || payloadPeserta;
            const history = payloadHistory.data || payloadHistory;

            console.log('Peserta profil:', peserta);
            console.log('Peserta riwayat:', history);

            // Update Page Subtitle with Participant Name
            document.getElementById('pageSubtitle').textContent = `Riwayat program pelatihan yang diikuti oleh ${peserta.nama}.`;

            // Populate Profile Card
            document.getElementById('detailNama').textContent = peserta.nama || '—';
            document.getElementById('detailEmail').textContent = peserta.email || '—';
            document.getElementById('detailTelepon').textContent = peserta.telepon || '—';
            document.getElementById('detailKeahlian').textContent = peserta.keahlian || '—';

            document.getElementById('pesertaDetailCard').style.display = 'block';

            // Populate Table
            const historyTableBody = document.getElementById('historyTable');
            historyTableBody.innerHTML = '';

            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('riwayatTable').style.display = 'table';

            if (!history || !history.length) {
                historyTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--color-text-muted);padding:24px;">Peserta belum terdaftar di program pelatihan apa pun</td></tr>';
            } else {
                history.forEach((item, index) => {
                    historyTableBody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td style="color:var(--color-text-muted);font-size:12px;">${index + 1}</td>
                            <td><span class="font-semibold">${item.pelatihan?.judul || '—'}</span></td>
                            <td>${item.pelatihan?.mentor?.nama || '<span style="color:var(--color-text-muted);">—</span>'}</td>
                            <td style="color:var(--color-text-secondary);">${item.tanggal_daftar || '—'}</td>
                            <td>${statusBadge(item.status)}</td>
                        </tr>
                    `);
                });
            }

            const user = getApiUser();
            const email = user?.email || 'Administrator';
            document.getElementById('adminEmailTopbar').textContent = email.split('@')[0];
            document.getElementById('userInitial').textContent = (email.charAt(0) || 'A').toUpperCase();

        } catch (error) {
            console.error('Error loading history:', error);
            document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">Error: ' + error.message + '</p>';
        }
    }

    loadHistoryData();

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

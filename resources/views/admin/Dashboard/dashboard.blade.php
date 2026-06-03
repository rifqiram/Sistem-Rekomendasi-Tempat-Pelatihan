@extends('admin.Layout.layout')

@section('content')
{{-- ===== SIDEBAR (Bagisto style) ===== --}}
<aside class="admin-sidebar" id="adminSidebar">
    {{-- Brand --}}
    <a href="#" class="sidebar-brand">
        <div class="brand-logo"><i class="fas fa-bolt"></i></div>
        <span class="brand-text">PelatihanIT</span>
    </a>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:2px;">
            <li class="nav-item">
                <a href="#" class="nav-link active" data-target="dashboard">
                    <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-target="pelatihan">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <p>Pelatihan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-target="mentor">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                    <p>Mentor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-target="peserta">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <p>Peserta</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-target="pendaftaran">
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

{{-- ===== MAIN ===== --}}
<div class="admin-main">
    {{-- Topbar --}}
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

    {{-- Main content --}}
    <main class="admin-content">

        {{-- ========== DASHBOARD ========== --}}
        <div id="tab-dashboard">
            <div class="page-header">
                <div>
                    <h1>Dashboard</h1>
                    <p>Ringkasan data sistem pelatihan IT</p>
                </div>
            </div>

            {{-- Stat cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon info"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-body">
                        <div class="stat-value" id="countPelatihan">0</div>
                        <div class="stat-label">Total Pelatihan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success"><i class="fas fa-user-tie"></i></div>
                    <div class="stat-body">
                        <div class="stat-value" id="countMentor">0</div>
                        <div class="stat-label">Total Mentor</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning"><i class="fas fa-users"></i></div>
                    <div class="stat-body">
                        <div class="stat-value" id="countPeserta">0</div>
                        <div class="stat-label">Total Peserta</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon danger"><i class="fas fa-file-signature"></i></div>
                    <div class="stat-body">
                        <div class="stat-value" id="countPendaftaran">0</div>
                        <div class="stat-label">Total Pendaftaran</div>
                    </div>
                </div>
            </div>

            {{-- Quick info card --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Panduan Penggunaan</span>
                </div>
                <div class="card-body">
                    <p style="color:var(--color-text-secondary); line-height:1.75; font-size:13.5px; margin:0;">
                        Gunakan menu di sebelah kiri untuk mengelola <strong style="color:var(--color-text);">Pelatihan</strong>,
                        <strong style="color:var(--color-text);">Mentor</strong>,
                        <strong style="color:var(--color-text);">Peserta</strong>, dan
                        <strong style="color:var(--color-text);">Pendaftaran</strong>.
                        Semua aksi terhubung langsung ke API backend.
                    </p>
                </div>
            </div>
        </div>

        {{-- ========== PELATIHAN ========== --}}
        <div id="tab-pelatihan" class="d-none">
            <div class="page-header">
                <div>
                    <h1>Pelatihan</h1>
                    <p>Kelola daftar program pelatihan</p>
                </div>
                <a href="{{ route('admin.pelatihan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pelatihan
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <span class="card-title">Daftar Pelatihan</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Pelatihan</th>
                                <th>Mentor</th>
                                <th>Level</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pelatihanTable">
                            <tr><td colspan="7" style="text-align:center; color:var(--color-text-muted); padding:24px;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ========== MENTOR ========== --}}
        <div id="tab-mentor" class="d-none">
            <div class="page-header">
                <div>
                    <h1>Mentor</h1>
                    <p>Kelola daftar mentor pelatihan</p>
                </div>
                <a href="{{ route('admin.mentor.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Mentor
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <span class="card-title">Daftar Mentor</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Keahlian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mentorTable">
                            <tr><td colspan="6" style="text-align:center; color:var(--color-text-muted); padding:24px;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ========== PESERTA ========== --}}
        <div id="tab-peserta" class="d-none">
            <div class="page-header">
                <div>
                    <h1>Peserta</h1>
                    <p>Kelola daftar peserta pelatihan</p>
                </div>
                <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.peserta.create') }}'">
                    <i class="fas fa-plus"></i> Tambah Peserta
                </button>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <span class="card-title">Daftar Peserta</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Keahlian</th>

                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pesertaTable">
                            <tr><td colspan="6" style="text-align:center; color:var(--color-text-muted); padding:24px;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <p>Form peserta sekarang berada di halaman terpisah. Tekan tombol "Tambah Peserta" untuk membuka halaman pendaftaran peserta baru.</p>
                </div>
            </div>


        </div>

        {{-- ========== PENDAFTARAN ========== --}}
        <div id="tab-pendaftaran" class="d-none">
            <div class="page-header">
                <div>
                    <h1>Pendaftaran</h1>
                    <p>Kelola pendaftaran peserta ke program pelatihan</p>
                </div>
                <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.pendaftaran.create') }}'">
                    <i class="fas fa-plus"></i> Tambah Pendaftaran
                </button>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <span class="card-title">Daftar Pendaftaran</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Peserta</th>
                                <th>Pelatihan</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pendaftaranTable">
                            <tr><td colspan="6" style="text-align:center; color:var(--color-text-muted); padding:24px;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <p>Form pendaftaran sekarang berada di halaman terpisah. Tekan tombol "Tambah Pendaftaran" untuk membuka halaman pendaftaran baru.</p>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    const sections = ['dashboard', 'pelatihan', 'mentor', 'peserta', 'pendaftaran'];
    const pageTitles = {
        dashboard: 'Dashboard',
        pelatihan: 'Pelatihan',
        mentor: 'Mentor',
        peserta: 'Peserta',
        pendaftaran: 'Pendaftaran',
    };

    function showTab(tab) {
        if (!sections.includes(tab)) tab = 'dashboard';
        sections.forEach(name => {
            document.getElementById('tab-' + name).classList.toggle('d-none', name !== tab);
        });
        document.querySelectorAll('.nav-link[data-target]').forEach(link => {
            link.classList.toggle('active', link.dataset.target === tab);
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
        history.replaceState(null, null, '#' + tab);
    }
    
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1);
        if (sections.includes(hash)) {
            showTab(hash);
        }
    });

    document.querySelectorAll('.nav-link[data-target]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            showTab(this.dataset.target);
        });
    });

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

    function statusBadge(status) {
        if (!status) return '<span class="badge badge-gray">-</span>';
        const s = status.toLowerCase();
        if (s === 'aktif' || s === 'terdaftar' || s === 'selesai') return `<span class="badge badge-success">${status}</span>`;
        if (s === 'nonaktif' || s === 'ditolak') return `<span class="badge badge-danger">${status}</span>`;
        return `<span class="badge badge-info">${status}</span>`;
    }

    async function loadSummary() {
        const [pelatihan, mentor, peserta, pendaftaran] = await Promise.all([
            authFetch(window.apiBase + '/pelatihan').then(parseApiResponse),
            authFetch(window.apiBase + '/mentor').then(parseApiResponse),
            authFetch(window.apiBase + '/peserta').then(parseApiResponse),
            authFetch(window.apiBase + '/pendaftaran').then(parseApiResponse),
        ]);
        document.getElementById('countPelatihan').textContent = pelatihan.length;
        document.getElementById('countMentor').textContent = mentor.length;
        document.getElementById('countPeserta').textContent = peserta.length;
        document.getElementById('countPendaftaran').textContent = pendaftaran.length;
        return { pelatihan, mentor, peserta, pendaftaran };
    }

    async function loadMentors() {
        const mentors = await authFetch(window.apiBase + '/mentor').then(parseApiResponse);
        const mentorTable = document.getElementById('mentorTable');
        mentorTable.innerHTML = '';
        if (!mentors.length) {
            mentorTable.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:24px;">Belum ada data mentor</td></tr>';
        }
        mentors.forEach((mentor, i) => {
            mentorTable.insertAdjacentHTML('beforeend', `
                <tr>
                    <td style="color:var(--color-text-muted);font-size:12px;">${i + 1}</td>
                    <td><span class="font-semibold">${mentor.nama}</span></td>
                    <td style="color:var(--color-text-secondary);">${mentor.email}</td>
                    <td>${mentor.telepon || '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>${mentor.keahlian ? `<span class="badge badge-info">${mentor.keahlian}</span>` : '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editMentor(${mentor.id})"><i class="fas fa-pencil-alt"></i> Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-type="mentor" data-id="${mentor.id}"><i class="fas fa-trash"></i> Hapus</button>
                    </td>
                </tr>
            `);
        });
        const mentorSelect = document.getElementById('pelatihanMentor');
        if (mentorSelect) {
            mentorSelect.innerHTML = '<option value="">— Pilih Mentor —</option>' + mentors.map(m => `<option value="${m.id}">${m.nama}</option>`).join('');
        }
        return mentors;
    }

    async function loadPelatihan() {
        const pelatihan = await authFetch(window.apiBase + '/pelatihan').then(parseApiResponse);
        const pelatihanTable = document.getElementById('pelatihanTable');
        pelatihanTable.innerHTML = '';
        if (!pelatihan.length) {
            pelatihanTable.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--color-text-muted);padding:24px;">Belum ada data pelatihan</td></tr>';
        }
        pelatihan.forEach((item, i) => {
            pelatihanTable.insertAdjacentHTML('beforeend', `
                <tr>
                    <td style="color:var(--color-text-muted);font-size:12px;">${i + 1}</td>
                    <td><span class="font-semibold">${item.judul}</span></td>
                    <td>${item.mentor?.nama || '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>${item.level ? `<span class="badge badge-gray">${item.level}</span>` : '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>${item.durasi || '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>${statusBadge(item.status)}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editPelatihan(${item.id})"><i class="fas fa-pencil-alt"></i> Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-type="pelatihan" data-id="${item.id}"><i class="fas fa-trash"></i> Hapus</button>
                    </td>
                </tr>
            `);
        });
        const pelatihanSelect = document.getElementById('pendaftaranPelatihan');
        pelatihanSelect.innerHTML = '<option value="">— Pilih Pelatihan —</option>' + pelatihan.map(p => `<option value="${p.id}">${p.judul}</option>`).join('');
        return pelatihan;
    }

    async function loadPeserta() {
        const peserta = await authFetch(window.apiBase + '/peserta').then(parseApiResponse);
        const pesertaTable = document.getElementById('pesertaTable');
        pesertaTable.innerHTML = '';
        if (!peserta.length) {
            pesertaTable.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:24px;">Belum ada data peserta</td></tr>';
        }
        peserta.forEach((item, i) => {
            pesertaTable.insertAdjacentHTML('beforeend', `
                <tr>
                    <td style="color:var(--color-text-muted);font-size:12px;">${i + 1}</td>
                    <td><span class="font-semibold">${item.nama}</span></td>
                    <td style="color:var(--color-text-secondary);">${item.email}</td>
                    <td>${item.telepon || '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td>${item.keahlian || '<span style="color:var(--color-text-muted);">-</span>'}</td>

                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editPeserta(${item.id})"><i class="fas fa-pencil-alt"></i> Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-type="peserta" data-id="${item.id}"><i class="fas fa-trash"></i> Hapus</button>
                        <button class="btn btn-sm btn-info" onclick="loadHistory(${item.id})"><i class="fas fa-history"></i> Riwayat</button>
                    </td>
                </tr>
            `);
        });
        const pesertaSelect = document.getElementById('pendaftaranPeserta');
        pesertaSelect.innerHTML = '<option value="">— Pilih Peserta —</option>' + peserta.map(p => `<option value="${p.id}">${p.nama}</option>`).join('');
        return peserta;
    }

    async function loadPendaftaran() {
        const data = await authFetch(window.apiBase + '/pendaftaran').then(parseApiResponse);
        const table = document.getElementById('pendaftaranTable');
        table.innerHTML = '';
        if (!data.length) {
            table.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:24px;">Belum ada data pendaftaran</td></tr>';
        }
        data.forEach((item, i) => {
            table.insertAdjacentHTML('beforeend', `
                <tr>
                    <td style="color:var(--color-text-muted);font-size:12px;">${i + 1}</td>
                    <td><span class="font-semibold">${item.peserta?.nama || '-'}</span></td>
                    <td>${item.pelatihan?.judul || '<span style="color:var(--color-text-muted);">-</span>'}</td>
                    <td style="color:var(--color-text-secondary);">${item.tanggal_daftar || '-'}</td>
                    <td>${statusBadge(item.status)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-type="pendaftaran" data-id="${item.id}"><i class="fas fa-trash"></i> Hapus</button>
                    </td>
                </tr>
            `);
        });
        return data;
    }

    async function loadHistory(pesertaId) {
        window.location.href = '{{ route('admin.peserta.riwayat') }}?id=' + pesertaId;
    }

    async function deleteMentor(id) {
        const ok = await showConfirmModal(
            'Hapus Mentor',
            'Apakah Anda yakin ingin menghapus data mentor ini? Jika mentor masih memiliki kelas, penghapusan tidak akan dilanjutkan.'
        );
        if (!ok) return;
        try {
            const res = await authFetch(window.apiBase + '/mentor/' + id, { method: 'DELETE' });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                return showErrorModal('Gagal Menghapus Mentor', getDeleteErrorMessage(err.message, err.message || 'Terjadi kesalahan.'));
            }
            await refreshAll();
        } catch (e) { showErrorModal('Gagal Menghapus Mentor', e.message); }
    }

    function getDeleteErrorMessage(message, defaultMessage) {
        const mapping = {
            'Masih Ada Peserta!': 'Tidak dapat menghapus karena masih ada data pendaftaran atau peserta terkait. Hapus data terkait terlebih dahulu.',
            'Masih Ada Kelas!': 'Tidak dapat menghapus karena masih ada pelatihan terkait. Hapus pelatihan terkait terlebih dahulu.',
        };
        return mapping[message] || defaultMessage;
    }

    async function deletePelatihan(id) {
        const ok = await showConfirmModal(
            'Hapus Pelatihan',
            'Apakah Anda yakin ingin menghapus data pelatihan ini? Jika pelatihan masih memiliki peserta terdaftar, penghapusan tidak akan dilanjutkan.'
        );
        if (!ok) return;
        try {
            const res = await authFetch(window.apiBase + '/pelatihan/' + id, { method: 'DELETE' });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                return showErrorModal('Gagal Menghapus Pelatihan', getDeleteErrorMessage(err.message, err.message || 'Terjadi kesalahan.'));
            }
            await refreshAll();
        } catch (e) { showErrorModal('Gagal Menghapus Pelatihan', e.message); }
    }
    async function deletePeserta(id) {
        const ok = await showConfirmModal(
            'Hapus Peserta',
            'Apakah Anda yakin ingin menghapus data peserta ini? Jika peserta masih memiliki pendaftaran, penghapusan tidak akan dilanjutkan.'
        );
        if (!ok) return;
        try {
            const res = await authFetch(window.apiBase + '/peserta/' + id, { method: 'DELETE' });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                return showErrorModal('Gagal Menghapus Peserta', getDeleteErrorMessage(err.message, err.message || 'Terjadi kesalahan.'));
            }
            await refreshAll();
        } catch (e) { showErrorModal('Gagal Menghapus Peserta', e.message); }
    }
    async function deletePendaftaran(id) {
        const ok = await showConfirmModal(
            'Hapus Pendaftaran',
            'Apakah Anda yakin ingin menghapus data pendaftaran ini? Tindakan ini tidak dapat dibatalkan.'
        );
        if (!ok) return;
        try {
            const res = await authFetch(window.apiBase + '/pendaftaran/' + id, { method: 'DELETE' });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                return showErrorModal('Gagal Menghapus Pendaftaran', getDeleteErrorMessage(err.message, err.message || 'Terjadi kesalahan.'));
            }
            await refreshAll();
        } catch (e) { showErrorModal('Gagal Menghapus Pendaftaran', e.message); }
    }

    function handleDeleteButtonClick(event) {
        const button = event.target.closest('button[data-action="delete"]');
        if (!button) return;
        const type = button.dataset.type;
        const id = button.dataset.id;
        if (!type || !id) return;

        switch (type) {
            case 'mentor':
                deleteMentor(id);
                break;
            case 'pelatihan':
                deletePelatihan(id);
                break;
            case 'peserta':
                deletePeserta(id);
                break;
            case 'pendaftaran':
                deletePendaftaran(id);
                break;
            default:
                console.warn('Unknown delete type:', type);
        }
    }

    async function editMentor(id) {
        window.location.href = '{{ route('admin.mentor.edit') }}?id=' + id;
    }

    async function editPelatihan(id) {
        window.location.href = '{{ route('admin.pelatihan.edit') }}?id=' + id;
    }

    async function editPeserta(id) {
        window.location.href = '{{ route('admin.peserta.edit') }}?id=' + id;
    }



    async function refreshAll() {
        const user = getApiUser();
        const email = user?.email || 'Administrator';
        const adminEmailElem = document.getElementById('adminEmail');
        if (adminEmailElem) {
            adminEmailElem.textContent = email;
        }
        document.getElementById('adminEmailTopbar').textContent = email.split('@')[0];
        const initial = (email.charAt(0) || 'A').toUpperCase();
        document.getElementById('userInitial').textContent = initial;

        await Promise.all([
            loadSummary().catch(err => console.error('loadSummary failed', err)),
            loadMentors().catch(err => console.error('loadMentors failed', err)),
            loadPelatihan().catch(err => console.error('loadPelatihan failed', err)),
            loadPeserta().catch(err => console.error('loadPeserta failed', err)),
            loadPendaftaran().catch(err => console.error('loadPendaftaran failed', err)),
        ]);
    }

    document.addEventListener('DOMContentLoaded', async function () {
        if (!getApiToken()) {
            window.location.href = '/admin/login';
            return;
        }
        const hash = window.location.hash.substring(1);
        showTab(sections.includes(hash) ? hash : 'dashboard');
        await refreshAll();

        const pesertaForm = document.getElementById('pesertaForm');
        if (pesertaForm) {
            pesertaForm.addEventListener('submit', async function (e) {
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
                e.target.reset(); await refreshAll();
            });
        }

        const pendaftaranForm = document.getElementById('pendaftaranForm');
        if (pendaftaranForm) {
            pendaftaranForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                await authFetch(window.apiBase + '/pendaftaran', {
                    method: 'POST',
                    body: JSON.stringify({
                        peserta_id: document.getElementById('pendaftaranPeserta').value,
                        pelatihan_id: document.getElementById('pendaftaranPelatihan').value,
                        tanggal_daftar: document.getElementById('pendaftaranTanggal').value,
                        status: document.getElementById('pendaftaranStatus').value,
                    }),
                });
                e.target.reset();
                const statusInput = document.getElementById('pendaftaranStatus');
                if (statusInput) statusInput.value = 'terdaftar';
                await refreshAll();
            });
        }

        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function (e) {
                e.preventDefault();
                clearApiToken();
                window.location.href = '/admin/login';
            });
        }

        ['mentorTable', 'pelatihanTable', 'pesertaTable', 'pendaftaranTable'].forEach(tableId => {
            const table = document.getElementById(tableId);
            if (table) {
                table.addEventListener('click', handleDeleteButtonClick);
            }
        });
    });
</script>
@endpush


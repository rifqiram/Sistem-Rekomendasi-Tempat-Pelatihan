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
                <h1>Edit Pelatihan</h1>
                <p id="pageSubtitle">Ubah data pelatihan yang ada di sistem.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Form Edit Pelatihan</span>
            </div>
            <div class="card-body">
                <div id="loadingSpinner" style="text-align:center; padding:20px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                </div>
                <form id="pelatihanEditForm" style="display:none;">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <input type="text" id="pelatihanKategori" class="form-control" placeholder="Contoh: Pemrograman">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Level</label>
                                    <input type="text" id="pelatihanLevel" class="form-control" placeholder="Dasar / Menengah / Lanjutan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Durasi</label>
                                    <input type="text" id="pelatihanDurasi" class="form-control" placeholder="Contoh: 30 Jam">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sertifikat</label>
                                    <input type="text" id="pelatihanSertifikat" class="form-control" placeholder="Ya / Tidak">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="required">*</span></label>
                                    <input type="date" id="pelatihanMulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                        <input type="checkbox" id="pelatihanActive" class="form-check-input">
                                        <label class="form-check-label" for="pelatihanActive">Tandai sebagai Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Pelatihan
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
    const pelatihanId = urlParams.get('id');

    if (!pelatihanId) {
        document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">ID Pelatihan tidak ditemukan</p>';
    }

    async function loadPelatihanData() {
        try {
            const [pelatihanRes, mentorsRes] = await Promise.all([
                fetch(window.apiBase + '/pelatihan/' + pelatihanId, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                }),
                fetch(window.apiBase + '/mentor', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                    },
                }),
            ]);

            if (!pelatihanRes.ok) {
                throw new Error('Gagal memuat data pelatihan (Status: ' + pelatihanRes.status + ')');
            }
            if (!mentorsRes.ok) {
                throw new Error('Gagal memuat daftar mentor (Status: ' + mentorsRes.status + ')');
            }

            const pelatihan = await pelatihanRes.json();
            const mentors = await mentorsRes.json();

            console.log('Pelatihan data:', pelatihan);
            console.log('Mentors data:', mentors);

            // Handle API response format (could be wrapped in 'data' or direct)
            const pelatihanData = pelatihan.data || pelatihan;
            const mentorsData = Array.isArray(mentors) ? mentors : (mentors.data || []);

            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('pelatihanEditForm').style.display = 'block';

            document.getElementById('pelatihanJudul').value = pelatihanData.judul || '';
            document.getElementById('pelatihanDeskripsi').value = pelatihanData.deskripsi || '';
            document.getElementById('pelatihanKategori').value = pelatihanData.kategori || '';
            document.getElementById('pelatihanLevel').value = pelatihanData.level || '';
            document.getElementById('pelatihanDurasi').value = pelatihanData.durasi || '';
            document.getElementById('pelatihanSertifikat').value = pelatihanData.sertifikat || '';
            document.getElementById('pelatihanMulai').value = pelatihanData.tanggal_mulai || '';
            document.getElementById('pelatihanSelesai').value = pelatihanData.tanggal_selesai || '';
            document.getElementById('pelatihanStatus').value = pelatihanData.status || '';
            document.getElementById('pelatihanActive').checked = pelatihanData.is_active || false;

            const mentorSelect = document.getElementById('pelatihanMentor');
            mentorSelect.innerHTML = '<option value="">— Pilih Mentor —</option>' + 
                mentorsData.map(m => `<option value="${m.id}" ${pelatihanData.mentor_id === m.id ? 'selected' : ''}>${m.nama}</option>`).join('');

        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('loadingSpinner').innerHTML = '<p style="color:var(--color-text-muted);">Error: ' + error.message + '</p>';
        }
    }

    loadPelatihanData();

    document.getElementById('pelatihanEditForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const data = {
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
        };

        console.log('Sending data:', data);

        try {
            const response = await fetch(window.apiBase + '/pelatihan/' + pelatihanId, {
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
                throw new Error(result.message || 'Gagal update pelatihan');
            }

            alert('Data pelatihan berhasil diupdate!');
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

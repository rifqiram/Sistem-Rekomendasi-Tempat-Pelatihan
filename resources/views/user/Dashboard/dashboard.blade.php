@extends('layouts.user')

@section('content')
<style>
    /* ===== USER SHARED STYLES ===== */
    .field-hint {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: var(--color-text-muted);
    }
    .field-error {
        display: none;
        margin-top: 4px;
        font-size: 12.5px;
        color: var(--color-danger);
        font-weight: 500;
    }
    .field-error.show { display: block; }
    .form-control.is-invalid {
        border-color: var(--color-danger) !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08) !important;
    }

    .spinner-inline {
        width: 14px; height: 14px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .alert-success {
        background: var(--color-success-light);
        border: 1px solid #6EE7B7;
        color: #065F46;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert-info-box {
        background: var(--color-info-light);
        border: 1px solid #93C5FD;
        color: #1E40AF;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 18px;
    }

    /* Welcome card */
    .welcome-card {
        background: linear-gradient(135deg, var(--color-primary), #7C3AED);
        border-radius: var(--radius-lg);
        padding: 28px;
        color: #fff;
        margin-bottom: 20px;
    }
    .welcome-card h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .welcome-card p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    /* Quick action cards */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .action-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: 20px;
        text-decoration: none;
        color: var(--color-text);
        transition: all 0.2s ease;
        cursor: pointer;
        display: block;
    }
    .action-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-color: var(--color-primary);
    }
    .action-card .action-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
    }
    .action-card .action-icon.purple { background: #EEF2FF; color: var(--color-primary); }
    .action-card .action-icon.green { background: var(--color-success-light); color: var(--color-success); }
    .action-card .action-icon.blue { background: var(--color-info-light); color: var(--color-info); }
    .action-card .action-icon.orange { background: var(--color-warning-light); color: var(--color-warning); }
    .action-card h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .action-card p {
        font-size: 12.5px;
        color: var(--color-text-secondary);
        margin: 0;
        line-height: 1.4;
    }

    /* Profile completeness */
    .progress-bar-container {
        background: var(--color-bg);
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
        margin-top: 8px;
    }
    .progress-bar-fill {
        height: 100%;
        background: var(--color-primary);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    @media (max-width: 768px) {
        .quick-actions { grid-template-columns: 1fr; }
    }
</style>

        {{-- Welcome Card --}}
        <div class="welcome-card">
            <h2>Selamat datang, <span id="welcomeName">Pengguna</span>!</h2>
            <p>Kelola profil, keahlian, dan temukan pelatihan yang sesuai untuk Anda.</p>
        </div>

        {{-- Profile Completeness --}}
        <div class="card mb-4" id="profileCard">
            <div class="card-body">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <h4 style="font-size:14px; font-weight:600; margin-bottom:2px;">Kelengkapan Profil</h4>
                        <p style="font-size:12.5px; color:var(--color-text-secondary); margin:0;" id="profileStatus">Memuat...</p>
                    </div>
                    <span style="font-size:20px; font-weight:700; color:var(--color-primary);" id="profilePercent">0%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="profileBar" style="width:0%"></div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card">
                <div class="stat-icon info"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-body">
                    <div class="stat-value" id="countKeahlian">0</div>
                    <div class="stat-label">Kuesioner Diisi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-body">
                    <div class="stat-value" id="countRekomendasi">0</div>
                    <div class="stat-label">Rekomendasi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warning"><i class="fas fa-file-signature"></i></div>
                <div class="stat-body">
                    <div class="stat-value" id="countPendaftaran">0</div>
                    <div class="stat-label">Terdaftar</div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="page-header" style="margin-top:8px;">
            <div>
                <h1 style="font-size:16px;">Aksi Cepat</h1>
                <p>Pilih menu untuk memulai</p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="{{ route('user.profile') }}" class="action-card">
                <div class="action-icon purple"><i class="fas fa-user-edit"></i></div>
                <h4>Lengkapi Profil</h4>
                <p>Perbarui data pribadi Anda</p>
            </a>
            <a href="{{ route('user.questionnaire') }}" class="action-card">
                <div class="action-icon green"><i class="fas fa-cogs"></i></div>
                <h4>Isi Kuesioner</h4>
                <p>Sesuaikan preferensi Anda</p>
            </a>
            <a href="{{ route('user.recommendations') }}" class="action-card">
                <div class="action-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
                <h4>Rekomendasi</h4>
                <p>Lihat TC terbaik untuk Anda</p>
            </a>
            <a href="{{ route('user.enrollments') }}" class="action-card">
                <div class="action-icon orange"><i class="fas fa-history"></i></div>
                <h4>Riwayat Pendaftaran</h4>
                <p>Lihat pelatihan yang didaftar</p>
            </a>
        </div>

        {{-- Account Info --}}
        <div class="card mt-4">
            <div class="card-header">
                <span class="card-title fw-bold">Informasi Akun</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <strong>Nama:</strong>
                        <div id="infoName" style="margin-top:4px; color:var(--color-text-secondary);">-</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Email:</strong>
                        <div id="infoEmail" style="margin-top:4px; color:var(--color-text-secondary);">-</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Role:</strong>
                        <div style="margin-top:4px;"><span class="badge bg-info text-white" id="infoRole">-</span></div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    const user = getApiUser();
    const token = getApiToken();

    if (!token) {
        window.location.href = '/user/login';
    }
    if (!user || !user.role) {
        window.location.href = '/user/login';
    }
    if (user.role !== 'user') {
        window.location.href = '/admin/dashboard';
    }

    // Populate user info
    const displayName = user.name || user.email || 'Pengguna';
    document.getElementById('welcomeName').textContent = displayName.split(' ')[0];
    document.getElementById('infoName').textContent = user.name || '-';
    document.getElementById('infoEmail').textContent = user.email || '-';
    document.getElementById('infoRole').textContent = user.role || '-';

    // Load dashboard stats & Redirect Flow Logic
    async function loadDashboardData() {
        try {
            // Load base profile
            const profile = await window.authFetch(window.apiBase + '/profile').then(window.parseApi).catch(() => null);

            // AUTOMATIC REDIRECT FLOW - "Gatekeeper"
            // Sesuai dengan spesifikasi alur sistem yang kaku:
            // Dashboard -> Cek Profil -> Belum -> Arahkan ke Profil
            // Profil -> Cek Kuesioner -> Belum -> Arahkan ke Kuesioner
            // Kuesioner -> Sudah -> Arahkan ke Rekomendasi

            if (!profile || !profile.age || !profile.latitude) {
                // Profil Belum Lengkap
                window.location.href = '/user/profile';
                return;
            }

            const kuesioner = await window.authFetch(window.apiBase + '/questionnaire').then(window.parseApi).catch(() => null);

            if (!kuesioner || !kuesioner.bidang_diminati) {
                // Profil sudah, tapi Kuesioner Belum
                window.location.href = '/user/questionnaire';
                return;
            }

            // Keduanya sudah lengkap.
            // Jika Anda ingin user otomatis dilempar ke rekomendasi, nyalakan baris di bawah:
            // window.location.href = '/user/recommendations';
            // Namun secara best practice UI, user tetap boleh mengakses Dashboard mereka.

            let profileComplete = 100;

            let rekomendasiCount = 0;
            try {
                const rekom = await window.authFetch(window.apiBase + '/recommendations').then(window.parseApi);
                rekomendasiCount = Array.isArray(rekom) ? rekom.length : 0;
            } catch (e) {}

            let pendaftaranCount = 0;
            try {
                const enrolls = await window.authFetch(window.apiBase + '/enrollments').then(window.parseApi);
                pendaftaranCount = Array.isArray(enrolls) ? enrolls.length : 0;
            } catch (e) {}

            document.getElementById('countKeahlian').textContent = kuesioner ? 1 : 0;
            document.getElementById('countRekomendasi').textContent = rekomendasiCount;
            document.getElementById('countPendaftaran').textContent = pendaftaranCount;

            document.getElementById('profilePercent').textContent = profileComplete + '%';
            document.getElementById('profileBar').style.width = profileComplete + '%';
            document.getElementById('profileBar').style.background = 'var(--color-success)';
            document.getElementById('profileStatus').textContent = 'Profil Anda sudah lengkap!';

        } catch (e) {
            console.error('Dashboard load error:', e);
        }
    }

    loadDashboardData();

</script>
@endpush

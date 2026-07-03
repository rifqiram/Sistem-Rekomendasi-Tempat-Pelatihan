@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3 id="stat-tc">0</h3>
                <p>Training Center</p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-building text-white opacity-50"></i>
            </div>
            <a href="{{ route('admin.training-centers.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Kelola Data <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3 id="stat-pelatihan">0</h3>
                <p>Pelatihan Aktif</p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-book text-white opacity-50"></i>
            </div>
            <a href="{{ route('admin.pelatihan.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Kelola Data <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3 id="stat-users">0</h3>
                <p>Pencari Kerja (Users)</p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-users text-dark opacity-50"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                Kelola Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3 id="stat-enrollments">0</h3>
                <p>Pendaftaran Masuk</p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-clipboard-list text-white opacity-50"></i>
            </div>
            <a href="{{ route('admin.enrollments.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Pendaftar Terbaru -->
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title fw-bold">Pendaftar Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Pelatihan</th>
                            <th>Training Center</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recent-enrollments">
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary"></div> Memuat...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await window.authFetch(window.apiBase + '/admin/stats');
            const data = await res.json();
            const stats = data.data;

            // Metrics
            document.getElementById('stat-tc').textContent = stats.metrics.training_centers;
            document.getElementById('stat-pelatihan').textContent = stats.metrics.pelatihan;
            document.getElementById('stat-users').textContent = stats.metrics.users;
            document.getElementById('stat-enrollments').textContent = stats.metrics.enrollments;

            // Recent
            const tbody = document.getElementById('recent-enrollments');
            tbody.innerHTML = '';

            if(stats.recent_enrollments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada pendaftar terbaru</td></tr>';
                return;
            }

            stats.recent_enrollments.forEach(item => {
                const user = item.user ? item.user.name : '-';
                const pel = item.pelatihan ? item.pelatihan.judul : '-';
                const tc = item.training_center ? item.training_center.nama : '-';
                const date = new Date(item.created_at).toLocaleDateString('id-ID');

                const statusBadge = item.status === 'terdaftar'
                    ? '<span class="badge bg-primary">Terdaftar</span>'
                    : `<span class="badge bg-secondary">${item.status}</span>`;

                const tr = `
                    <tr>
                        <td class="fw-semibold">${user}</td>
                        <td>${pel}</td>
                        <td>${tc}</td>
                        <td>${date}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', tr);
            });

        } catch (error) {
            console.error(error);
        }
    });
</script>
@endpush
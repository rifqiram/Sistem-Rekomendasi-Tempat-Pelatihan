@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page_title', 'Activity Log')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activity Log</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title fw-bold">Jejak Aktivitas Pengguna</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 150px;">Waktu</th>
                                <th>User</th>
                                <th>Tipe Aktivitas</th>
                                <th>Konteks (TC / Pelatihan)</th>
                            </tr>
                        </thead>
                        <tbody id="log-table-body">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <div class="mt-2">Memuat log...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const tbody = document.getElementById('log-table-body');
        try {
            const res = await window.authFetch(window.apiBase + '/admin/log-activities');
            const data = await window.parseApi(res);

            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Belum ada log aktivitas terdeteksi.</td></tr>`;
                return;
            }

            data.forEach((item, index) => {
                const dateObj = new Date(item.created_at);
                const dateStr = dateObj.toLocaleDateString('id-ID');
                const timeStr = dateObj.toLocaleTimeString('id-ID');

                const user = item.user ? item.user.name : 'System/Unknown';
                let context = '-';

                if (item.training_center) {
                    context = `<i class="fas fa-building text-secondary me-1"></i> ${item.training_center.nama}`;
                }
                if (item.pelatihan) {
                    context += `<br><i class="fas fa-book text-secondary me-1"></i> ${item.pelatihan.judul}`;
                }

                let typeBadge = '';
                switch (item.activity_type.toLowerCase()) {
                    case 'login': typeBadge = '<span class="badge bg-success">Login</span>'; break;
                    case 'logout': typeBadge = '<span class="badge bg-secondary">Logout</span>'; break;
                    case 'enroll': typeBadge = '<span class="badge bg-primary">Enrollment</span>'; break;
                    case 'view_detail': typeBadge = '<span class="badge bg-info text-dark">View Detail</span>'; break;
                    default: typeBadge = `<span class="badge bg-light text-dark border">${item.activity_type}</span>`;
                }

                const tr = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${dateStr} <br><small class="text-muted">${timeStr}</small></td>
                        <td class="fw-semibold">${user}</td>
                        <td>${typeBadge}</td>
                        <td>${context}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', tr);
            });
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Gagal memuat log aktivitas.</td></tr>`;
        }
    });
</script>
@endpush
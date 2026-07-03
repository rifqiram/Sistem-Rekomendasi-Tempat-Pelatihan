@extends('layouts.admin')

@section('title', 'Data Pendaftaran')
@section('page_title', 'Data Pendaftaran (Enrollments)')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pendaftaran</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title fw-bold">Riwayat Pendaftaran User</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal Daftar</th>
                                <th>Nama Peserta</th>
                                <th>Pelatihan</th>
                                <th>Training Center</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="enrollment-table-body">
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <div class="mt-2">Memuat data...</div>
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
        const tbody = document.getElementById('enrollment-table-body');

        try {
            const res = await window.authFetch(window.apiBase + '/admin/enrollments');
            const payload = await res.json();
            const data = payload.data?.data ?? payload.data; // Handle pagination wrapper

            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Belum ada pendaftaran</td></tr>`;
                return;
            }

            data.forEach((item, index) => {
                const user = item.user ? item.user.name : '-';
                const pel = item.pelatihan ? item.pelatihan.judul : '-';
                const tc = item.training_center ? item.training_center.nama : '-';
                const date = new Date(item.tanggal_daftar || item.created_at).toLocaleDateString('id-ID');

                const statusBadge = item.status === 'terdaftar'
                    ? '<span class="badge bg-primary">Terdaftar</span>'
                    : `<span class="badge bg-secondary">${item.status}</span>`;

                const tr = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${date}</td>
                        <td class="fw-semibold">${user}</td>
                        <td>${pel}</td>
                        <td>${tc}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Gagal memuat data pendaftaran.</td></tr>`;
        }
    });
</script>
@endpush
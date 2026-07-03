@extends('layouts.user')

@section('title', 'Riwayat Pendaftaran')

@push('styles')
<style>
    .history-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    @media (min-width: 768px) {
        .history-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .history-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .history-info h5 {
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
        font-size: 1.1rem;
    }
    .history-info p {
        color: #6b7280;
        margin: 0;
        font-size: 0.9rem;
    }
    .history-status {
        text-align: left;
    }
    @media (min-width: 768px) {
        .history-status { text-align: right; }
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-terdaftar { background: #dbeafe; color: #1e40af; }
    .status-selesai { background: #d1fae5; color: #065f46; }
    .status-batal { background: #fee2e2; color: #b91c1c; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <h3 class="fw-bold mb-1">Riwayat Pendaftaran</h3>
        <p class="text-muted mb-4">Daftar pelatihan yang telah Anda pilih berdasarkan rekomendasi sistem.</p>

        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-3 text-muted">Memuat data riwayat...</div>
        </div>

        <div id="emptyState" class="text-center py-5 d-none">
            <div style="font-size: 4rem; color: #d1d5db; margin-bottom: 16px;"><i class="fas fa-clipboard-list"></i></div>
            <h5 class="fw-bold text-gray-800">Belum Ada Pendaftaran</h5>
            <p class="text-muted mb-4">Anda belum mendaftar pelatihan apa pun.</p>
            <a href="{{ route('user.recommendations') }}" class="btn btn-primary px-4">Lihat Rekomendasi</a>
        </div>

        <div id="historyContainer">
            <!-- Rendered via JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const loading = document.getElementById('loadingState');
        const empty = document.getElementById('emptyState');
        const container = document.getElementById('historyContainer');

        try {
            const data = await window.authFetch(window.apiBase + '/enrollments').then(window.parseApi);
            loading.classList.add('d-none');

            if (!data || data.length === 0) {
                empty.classList.remove('d-none');
                return;
            }

            data.forEach(item => {
                const pel = item.pelatihan;
                const tc = item.training_center;
                if(!pel || !tc) return;

                let badgeClass = 'status-terdaftar';
                let icon = 'fa-clock';
                let statusText = 'Terdaftar Aktif';

                if (item.status === 'selesai') { badgeClass = 'status-selesai'; icon = 'fa-check-circle'; statusText = 'Selesai'; }
                if (item.status === 'batal') { badgeClass = 'status-batal'; icon = 'fa-times-circle'; statusText = 'Dibatalkan'; }

                const dateStr = new Date(item.tanggal_daftar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                const card = `
                    <div class="history-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="history-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="history-info">
                                <h5>${pel.judul}</h5>
                                <p><i class="fas fa-building me-1"></i> ${tc.nama}</p>
                            </div>
                        </div>
                        <div class="history-status mt-3 mt-md-0">
                            <div class="status-badge ${badgeClass} mb-2">
                                <i class="fas ${icon}"></i> ${statusText}
                            </div>
                            <div class="text-muted small">Tgl Daftar: ${dateStr}</div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', card);
            });

        } catch (error) {
            loading.classList.add('d-none');
            empty.classList.remove('d-none');
        }
    });
</script>
@endpush
@extends('layouts.user')

@section('title', 'Riwayat Pendaftaran')

@push('styles')
<style>
    /* Status Colors based on CSS Variables */
    :root {
        --status-active-bg: rgba(59, 130, 246, 0.1);
        --status-active-text: var(--info-color);
        --status-success-bg: rgba(16, 185, 129, 0.1);
        --status-success-text: var(--secondary-color);
        --status-danger-bg: rgba(239, 68, 68, 0.1);
        --status-danger-text: var(--danger-color);
    }

    .history-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    /* Status Indicator Line */
    .history-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        transition: background-color 0.3s;
    }

    .history-card.status-active::before { background-color: var(--status-active-text); }
    .history-card.status-success::before { background-color: var(--status-success-text); }
    .history-card.status-danger::before { background-color: var(--status-danger-text); }

    .history-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }

    @media (min-width: 768px) {
        .history-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
        }
    }

    .history-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .history-info h5 {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
        font-size: 1.1rem;
    }

    .history-info p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.9rem;
    }

    .history-status-container {
        text-align: left;
    }

    @media (min-width: 768px) {
        .history-status-container {
            text-align: right;
        }
    }

    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 1rem;
        border-radius: 50rem;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--border-color);
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        {{-- Header Page --}}
        <div class="d-flex align-items-center mb-4 gap-3">
            <div class="bg-amber-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0" style="color: var(--text-main);">Riwayat Pendaftaran</h3>
                <p class="text-muted mb-0 small">Lacak status program pelatihan yang telah Anda pilih.</p>
            </div>
        </div>

        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem; border-width: 0.25em;" role="status"></div>
            <h5 class="fw-bold" style="color: var(--text-main);">Memuat Data</h5>
            <div class="text-muted small">Mengambil riwayat pendaftaran Anda...</div>
        </div>

        <div id="emptyState" class="text-center py-5 d-none">
            <div class="empty-state-icon"><i class="fas fa-clipboard-check"></i></div>
            <h4 class="fw-bold mb-2" style="color: var(--text-main);">Belum Ada Pendaftaran</h4>
            <p class="text-muted mb-4 small mx-auto" style="max-width: 400px;">Anda belum mendaftar ke program pelatihan apa pun. Mari temukan pelatihan yang cocok untuk Anda.</p>
            <a href="{{ route('user.recommendations') }}" class="btn fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: var(--primary-color); color: white;">
                <i class="fas fa-search me-1"></i> Lihat Rekomendasi
            </a>
        </div>

        <div id="historyContainer" class="d-flex flex-column gap-2">
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

                // Set dynamic attributes based on status
                let indicatorClass = 'status-active';
                let badgeBg = 'var(--status-active-bg)';
                let badgeText = 'var(--status-active-text)';
                let icon = 'fa-clock';
                let statusText = 'Terdaftar Aktif';

                if (item.status === 'selesai') {
                    indicatorClass = 'status-success';
                    badgeBg = 'var(--status-success-bg)';
                    badgeText = 'var(--status-success-text)';
                    icon = 'fa-check-circle';
                    statusText = 'Selesai';
                }

                if (item.status === 'batal') {
                    indicatorClass = 'status-danger';
                    badgeBg = 'var(--status-danger-bg)';
                    badgeText = 'var(--status-danger-text)';
                    icon = 'fa-times-circle';
                    statusText = 'Dibatalkan';
                }

                // Format Date
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const dateStr = new Date(item.tanggal_daftar).toLocaleDateString('id-ID', dateOptions);

                const card = `
                    <div class="history-card ${indicatorClass}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="history-icon-wrapper" style="background-color: rgba(79, 70, 229, 0.08); color: var(--primary-color);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="history-info">
                                <h5>${pel.judul}</h5>
                                <p><i class="fas fa-building me-1 opacity-75"></i> ${tc.nama}</p>
                            </div>
                        </div>
                        <div class="history-status-container mt-2 mt-md-0">
                            <div class="status-badge-modern mb-2" style="background-color: ${badgeBg}; color: ${badgeText}; border: 1px solid ${badgeText}33;">
                                <i class="fas ${icon}"></i> ${statusText}
                            </div>
                            <div class="text-muted small fw-medium"><i class="far fa-calendar-alt me-1"></i> ${dateStr}</div>
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
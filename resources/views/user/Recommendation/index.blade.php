@extends('layouts.user')

@section('title', 'Rekomendasi Pelatihan')

@push('styles')
<style>
    /* Modern Header Area */
    .recom-header {
        background: linear-gradient(135deg, var(--primary-color), var(--info-color));
        color: #fff;
        padding: 3rem 0;
        border-radius: var(--radius-lg);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .recom-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .recom-header::after {
        content: '';
        position: absolute;
        bottom: -50%; right: -5%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    /* Recommendation Card Styling */
    .tc-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
    }

    .tc-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }

    .tc-cover {
        height: 130px;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(59, 130, 246, 0.1));
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
    }

    .tc-cover i {
        font-size: 3.5rem;
        color: rgba(79, 70, 229, 0.25);
    }

    /* Rank Badges */
    .tc-rank {
        position: absolute;
        top: 1rem; left: 1rem;
        width: 38px; height: 38px;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        box-shadow: var(--shadow-sm);
        z-index: 2;
        border: 2px solid white;
    }
    .tc-rank.rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; }
    .tc-rank.rank-2 { background: linear-gradient(135deg, #cbd5e1, #94a3b8); color: #fff; }
    .tc-rank.rank-3 { background: linear-gradient(135deg, #fdba74, #ea580c); color: #fff; }
    .tc-rank.rank-other { background: white; color: var(--text-muted); border-color: var(--border-color); }

    /* Score Badge */
    .score-badge {
        position: absolute;
        top: 1rem; right: 1rem;
        padding: 0.4rem 0.8rem;
        border-radius: 50rem;
        font-weight: 700;
        font-size: 0.8rem;
        background: white;
        color: var(--text-main);
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 2;
    }

    .tc-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .tc-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .tc-address {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tc-stats {
        display: flex;
        gap: 1rem;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .tc-stat-item {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Modal Customization */
    .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(59, 130, 246, 0.05));
        padding: 2rem 1.5rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .modal-close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.2s;
        z-index: 10;
    }

    .modal-close-btn:hover {
        background: var(--bg-color);
        color: var(--danger-color);
    }

    /* List Pelatihan dalam Modal */
    .pelatihan-item {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin-bottom: 0.75rem;
        transition: border-color 0.2s;
        background-color: var(--surface-color);
    }

    .pelatihan-item:hover {
        border-color: var(--primary-color);
    }

    /* Custom Spinners & Empty State */
    .empty-state-icon {
        font-size: 4rem;
        color: var(--border-color);
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

<!-- Header -->
<div class="recom-header px-4 px-md-5">
    <div class="row align-items-center header-content">
        <div class="col-md-8 text-center text-md-start">
            <h2 class="fw-bold mb-2">Rekomendasi Tempat Pelatihan</h2>
            <p class="mb-0 text-white-50 small">Sistem telah menganalisis preferensi kuesioner dan lokasi profil Anda. Berikut adalah hasil pemetaan algoritma cerdas kami.</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            <a href="{{ route('user.questionnaire') }}" class="btn bg-white text-primary fw-bold rounded-pill shadow-sm px-4 py-2" style="transition: transform 0.2s;">
                <i class="fas fa-sliders-h me-1"></i> Sesuaikan Kriteria
            </a>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loadingState" class="text-center py-5">
    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem; border-width: 0.25em;" role="status"></div>
    <h5 class="fw-bold" style="color: var(--text-main);">Menyusun Rekomendasi</h5>
    <div class="text-muted small">Algoritma sedang mengkalkulasi kecocokan...</div>
</div>

<!-- Empty State -->
<div id="emptyState" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-search-minus"></i></div>
    <h4 class="fw-bold mb-2" style="color: var(--text-main);">Belum Ada Rekomendasi</h4>
    <p class="text-muted mb-4 small mx-auto" style="max-width: 400px;">Sistem tidak menemukan rekomendasi, atau Anda belum mengisi kuesioner preferensi dengan lengkap.</p>
    <a href="{{ route('user.questionnaire') }}" class="btn fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: var(--primary-color); color: white;">
        <i class="fas fa-clipboard-list me-1"></i> Isi Kuesioner Sekarang
    </a>
</div>

<!-- Data State -->
<div class="row g-4" id="recommendationContainer">
    <!-- Cards Rendered Here via JS -->
</div>

<!-- MODAL DETAIL TC & PELATIHAN -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <button type="button" class="modal-close-btn shadow-sm" data-bs-dismiss="modal">
                <i class="fas fa-times"></i>
            </button>

            <div class="modal-header-custom text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 64px; height: 64px; background: white; color: var(--primary-color); font-size: 1.5rem;">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="fw-bold mb-2" style="color: var(--text-main);" id="modalTcName">Nama Lembaga</h3>
                <p class="text-muted small mb-3 mx-auto" style="max-width: 500px;" id="modalTcAddress">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i> Alamat Lengkap
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(16, 185, 129, 0.1); color: var(--secondary-color); border: 1px solid rgba(16,185,129,0.2);" id="modalTcScore">
                        <i class="fas fa-percentage me-1"></i> Skor: 0%
                    </span>
                    <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(59, 130, 246, 0.1); color: var(--info-color); border: 1px solid rgba(59,130,246,0.2);" id="modalTcDistance">
                        <i class="fas fa-location-arrow me-1"></i> Jarak: 0 km
                    </span>
                </div>
            </div>

            <div class="modal-body p-4 p-md-5" style="background-color: var(--bg-color);">
                <div id="btnMapsContainer" class="d-none text-center mb-4">
                    <a href="#" target="_blank" id="btnOpenMaps" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-map-pin me-1"></i> Lihat Lokasi (Google Maps)
                    </a>
                </div>

                <div class="d-flex align-items-center mb-3 gap-2">
                    <i class="fas fa-book-open text-primary"></i>
                    <h6 class="fw-bold mb-0" style="color: var(--text-main);">Daftar Pelatihan Tersedia</h6>
                </div>
                <div id="modalPelatihanList" class="d-flex flex-column gap-3">
                    <!-- List Pelatihan Rendered Here via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rawRecommendations = [];
    const detailModalElement = document.getElementById('detailModal');
    const detailModal = new bootstrap.Modal(detailModalElement);

    document.addEventListener('DOMContentLoaded', async () => {
        await loadRecommendations();
    });

    async function loadRecommendations() {
        const loading = document.getElementById('loadingState');
        const empty = document.getElementById('emptyState');
        const container = document.getElementById('recommendationContainer');

        try {
            const data = await window.authFetch(window.apiBase + '/recommendations').then(window.parseApi);
            loading.classList.add('d-none');

            if (!data || data.length === 0) {
                empty.classList.remove('d-none');
                return;
            }

            rawRecommendations = data;

            data.forEach(item => {
                if(!item.training_center) return; // safety
                const tc = item.training_center;
                const distanceStr = item.distance ? `${item.distance.toFixed(1)} km` : 'N/A';

                // Determine Rank Style
                let rankClass = 'rank-other';
                if(item.rank === 1) rankClass = 'rank-1';
                else if(item.rank === 2) rankClass = 'rank-2';
                else if(item.rank === 3) rankClass = 'rank-3';

                const cardHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="tc-card" onclick="openDetail(${item.rank})">
                            <div class="tc-rank ${rankClass}">#${item.rank}</div>
                            <div class="score-badge">
                                <i class="fas fa-star text-warning"></i> ${item.score}%
                            </div>

                            <div class="tc-cover">
                                <i class="fas fa-building"></i>
                            </div>

                            <div class="tc-body">
                                <div class="tc-title">${tc.nama}</div>
                                <div class="tc-address">${tc.alamat}</div>

                                <div class="tc-stats">
                                    <div class="tc-stat-item" title="Jarak dari lokasi Anda">
                                        <i class="fas fa-map-marker-alt" style="color: var(--danger-color);"></i> ${distanceStr}
                                    </div>
                                    <div class="tc-stat-item" title="Jumlah Pelatihan">
                                        <i class="fas fa-book-open text-primary"></i> ${item.jumlah_pelatihan} Kelas
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', cardHtml);
            });

        } catch (error) {
            loading.classList.add('d-none');
            empty.classList.remove('d-none');
        }
    }

    function openDetail(rank) {
        const item = rawRecommendations.find(r => r.rank === rank);
        if (!item || !item.training_center) return;

        const tc = item.training_center;
        document.getElementById('modalTcName').textContent = tc.nama;
        document.getElementById('modalTcAddress').innerHTML = `<i class="fas fa-map-marker-alt text-danger me-1"></i> ${tc.alamat}`;
        document.getElementById('modalTcScore').innerHTML = `<i class="fas fa-percentage me-1"></i> Kecocokan: ${item.score}%`;
        document.getElementById('modalTcDistance').innerHTML = item.distance ? `<i class="fas fa-location-arrow me-1"></i> Jarak: ${item.distance.toFixed(1)} km` : '<i class="fas fa-location-arrow me-1"></i> Jarak N/A';

        // Logika Peta & Google Maps Button
        const btnContainer = document.getElementById('btnMapsContainer');
        const btnOpenMaps = document.getElementById('btnOpenMaps');

        // Conditional Rendering
        if (tc.google_maps_url) {
            btnOpenMaps.href = tc.google_maps_url;
            btnContainer.classList.remove('d-none');
        } else {
            // Jika kosong/null, sembunyikan sepenuhnya (Sesuai instruksi requirement baru)
            btnOpenMaps.href = "#";
            btnContainer.classList.add('d-none');
        }

        const listContainer = document.getElementById('modalPelatihanList');
        listContainer.innerHTML = '';

        if (!item.daftar_pelatihan || item.daftar_pelatihan.length === 0) {
            listContainer.innerHTML = '<div class="text-center text-muted py-4 small bg-white rounded-3 border">Tidak ada kelas aktif yang sesuai di lembaga ini.</div>';
        } else {
            item.daftar_pelatihan.forEach(pel => {
                const isDraft = !pel.is_active;
                if(isDraft) return; // sembunyikan yg tidak aktif

                // Badge styling using CSS variables equivalents for background
                let skillBadgeStyle = '';
                if(pel.required_skill === 'Advanced') {
                    skillBadgeStyle = 'background-color: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2);';
                } else if(pel.required_skill === 'Intermediate') {
                    skillBadgeStyle = 'background-color: rgba(245, 158, 11, 0.1); color: var(--warning-color); border: 1px solid rgba(245, 158, 11, 0.2);';
                } else {
                    skillBadgeStyle = 'background-color: rgba(16, 185, 129, 0.1); color: var(--secondary-color); border: 1px solid rgba(16, 185, 129, 0.2);';
                }

                const html = `
                    <div class="pelatihan-item d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 p-md-4">
                        <div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main);">${pel.judul}</h6>
                            <div class="text-muted small mb-2 d-flex flex-wrap gap-2">
                                <span><i class="fas fa-tag me-1 opacity-75"></i>${pel.interest_category || 'Umum'}</span>
                                <span><i class="fas fa-chalkboard-teacher me-1 opacity-75"></i>${pel.method || 'Offline'}</span>
                            </div>
                            <span class="badge rounded-pill px-2 py-1" style="${skillBadgeStyle}">${pel.required_skill}</span>
                        </div>
                        <button class="btn fw-bold px-4 rounded-pill shadow-sm flex-shrink-0" onclick="enroll(${pel.id}, '${pel.judul}', this)" style="background-color: var(--primary-color); color: white; transition: all 0.2s;">
                            <i class="fas fa-paper-plane me-1"></i> Daftar
                        </button>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', html);
            });
        }

        detailModal.show();
    }

    async function enroll(pelatihanId, judulPelatihan, btn) {
        window.confirmAction(
            'Konfirmasi Pendaftaran',
            `Anda akan mendaftar ke pelatihan "${judulPelatihan}". Lanjutkan?`,
            'Ya, Daftar',
            async () => {
                try {
                    const originalHTML = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses';
                    btn.style.opacity = '0.8';

                    const response = await window.authFetch(window.apiBase + '/enrollments', {
                        method: 'POST',
                        body: JSON.stringify({ pelatihan_id: pelatihanId })
                    });

                    if (!response.ok) {
                        const err = await response.json();
                        throw new Error(err.message || 'Gagal mendaftar');
                    }

                    // Success State
                    btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Terdaftar';
                    btn.style.backgroundColor = 'var(--secondary-color)';
                    btn.style.borderColor = 'var(--secondary-color)';

                    window.showToast('success', 'Berhasil mendaftar!');
                    setTimeout(() => {
                        detailModal.hide(); // auto close modal
                    }, 1000);

                } catch (error) {
                    window.showAlert('error', 'Gagal', error.message);
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Daftar';
                }
            }
        );
    }
</script>
@endpush
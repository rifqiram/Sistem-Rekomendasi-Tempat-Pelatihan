@extends('layouts.user')

@section('title', 'Rekomendasi Pelatihan')

@push('styles')
<style>
    .recom-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: #fff;
        padding: 40px 0;
        border-radius: 16px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .recom-header::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .tc-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        cursor: pointer;
    }
    .tc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        border-color: #a5b4fc;
    }
    .tc-rank {
        position: absolute;
        top: 16px; left: 16px;
        width: 36px; height: 36px;
        background: #fff;
        color: #4f46e5;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .tc-rank.rank-1 { background: #fef08a; color: #854d0e; }
    .tc-rank.rank-2 { background: #e2e8f0; color: #475569; }
    .tc-rank.rank-3 { background: #fed7aa; color: #9a3412; }

    .tc-cover {
        height: 140px;
        background: #eef2ff;
        position: relative;
        display: flex; align-items: center; justify-content: center;
    }
    .tc-cover i { font-size: 3rem; color: #c7d2fe; }

    .tc-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .tc-title { font-size: 1.15rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
    .tc-address { font-size: 0.85rem; color: #6b7280; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .tc-stats {
        display: flex; gap: 12px; margin-top: auto;
        padding-top: 16px; border-top: 1px solid #f3f4f6;
    }
    .tc-stat-item {
        font-size: 0.85rem; color: #4b5563; font-weight: 500;
        display: flex; align-items: center; gap: 4px;
    }
    .score-badge {
        position: absolute; top: 16px; right: 16px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700; font-size: 0.85rem;
        background: #ecfdf5; color: #059669;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')

<!-- Header -->
<div class="recom-header px-4 px-md-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-2">Rekomendasi Tempat Pelatihan</h2>
            <p class="mb-0 text-white-50">Sistem telah menganalisis preferensi dan lokasi Anda. Berikut adalah Top 5 Training Center paling cocok.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('user.questionnaire') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fas fa-redo-alt me-1"></i> Hitung Ulang
            </a>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loadingState" class="text-center py-5">
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-3 text-muted">Mengambil data rekomendasi...</div>
</div>

<!-- Empty State -->
<div id="emptyState" class="text-center py-5 d-none">
    <div style="font-size: 4rem; color: #d1d5db; margin-bottom: 16px;"><i class="fas fa-folder-open"></i></div>
    <h4 class="fw-bold text-gray-800">Belum Ada Rekomendasi</h4>
    <p class="text-muted mb-4">Sistem tidak menemukan rekomendasi, atau Anda belum mengisi kuesioner preferensi.</p>
    <a href="{{ route('user.questionnaire') }}" class="btn btn-primary px-4">Isi Kuesioner Sekarang</a>
</div>

<!-- Data State -->
<div class="row g-4" id="recommendationContainer">
    <!-- Cards Rendered Here -->
</div>

<!-- MODAL DETAIL TC & PELATIHAN -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-gray-800 mb-1" id="modalTcName">Nama Lembaga</h3>
                    <p class="text-muted small mb-3" id="modalTcAddress"><i class="fas fa-map-marker-alt me-1 text-danger"></i> Alamat Lengkap</p>
                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge bg-success" id="modalTcScore">Skor: 0%</span>
                        <span class="badge bg-info" id="modalTcDistance">Jarak: 0 km</span>
                    </div>
                </div>

                <h5 class="fw-bold border-bottom pb-2 mb-3">Daftar Pelatihan Tersedia</h5>
                <div id="modalPelatihanList" class="list-group mb-3">
                    <!-- List Pelatihan -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rawRecommendations = [];
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

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
                const distanceStr = item.distance ? `${item.distance.toFixed(1)} km` : 'Jarak tidak diketahui';

                const cardHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="tc-card position-relative" onclick="openDetail(${item.rank})">
                            <div class="tc-rank rank-${item.rank}">#${item.rank}</div>
                            <div class="score-badge"><i class="fas fa-star text-warning me-1"></i> ${item.score}%</div>

                            <div class="tc-cover">
                                <i class="fas fa-building"></i>
                            </div>

                            <div class="tc-body">
                                <div class="tc-title">${tc.nama}</div>
                                <div class="tc-address">${tc.alamat}</div>

                                <div class="tc-stats">
                                    <div class="tc-stat-item" title="Jarak dari lokasi Anda">
                                        <i class="fas fa-location-arrow text-primary"></i> ${distanceStr}
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
        document.getElementById('modalTcAddress').innerHTML = `<i class="fas fa-map-marker-alt me-1 text-danger"></i> ${tc.alamat}`;
        document.getElementById('modalTcScore').textContent = `Kecocokan: ${item.score}%`;
        document.getElementById('modalTcDistance').textContent = item.distance ? `Jarak: ${item.distance.toFixed(1)} km` : 'Jarak N/A';

        const listContainer = document.getElementById('modalPelatihanList');
        listContainer.innerHTML = '';

        if (!item.daftar_pelatihan || item.daftar_pelatihan.length === 0) {
            listContainer.innerHTML = '<div class="text-center text-muted py-3">Tidak ada kelas aktif di lembaga ini.</div>';
        } else {
            item.daftar_pelatihan.forEach(pel => {
                const isDraft = !pel.is_active;
                if(isDraft) return; // sembunyikan yg tidak aktif

                const skillBadge = pel.required_skill === 'Advanced' ? 'bg-danger' : (pel.required_skill === 'Intermediate' ? 'bg-warning text-dark' : 'bg-success');

                const html = `
                    <div class="list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3">
                        <div>
                            <h6 class="fw-bold mb-1">${pel.judul}</h6>
                            <div class="text-muted small mb-2">${pel.interest_category || 'Umum'} • ${pel.method || 'Offline'}</div>
                            <span class="badge ${skillBadge}">${pel.required_skill}</span>
                        </div>
                        <button class="btn btn-primary btn-sm px-4" onclick="enroll(${pel.id}, this)">
                            <i class="fas fa-clipboard-check me-1"></i> Daftar
                        </button>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', html);
            });
        }

        detailModal.show();
    }

    async function enroll(pelatihanId, btn) {
        if(!confirm('Apakah Anda yakin ingin mendaftar pelatihan ini?')) return;

        try {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const response = await window.authFetch(window.apiBase + '/enrollments', {
                method: 'POST',
                body: JSON.stringify({ pelatihan_id: pelatihanId })
            });

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.message || 'Gagal mendaftar');
            }

            alert('Berhasil! Anda telah terdaftar dalam pelatihan ini. Silakan cek menu Riwayat Pendaftaran.');
            btn.innerHTML = '<i class="fas fa-check"></i> Terdaftar';
            btn.classList.replace('btn-primary', 'btn-success');

        } catch (error) {
            alert(error.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-clipboard-check me-1"></i> Daftar';
        }
    }
</script>
@endpush
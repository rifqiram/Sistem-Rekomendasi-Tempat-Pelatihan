@extends('layouts.admin')

@section('title', 'Manajemen Training Center')
@section('page_title', 'Training Center')

@section('breadcrumb')
    <li class="breadcrumb-item active">Training Center</li>
@endsection

@push('styles')
<style>
    /* Admin Table Modernization */
    .card-modern {
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-radius: 0.75rem;
    }

    .card-modern .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
    }

    .table-modern thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    .table-modern tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Minimalist Action Buttons */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-icon.edit { color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); border: none; }
    .btn-icon.edit:hover { background-color: #3b82f6; color: white; }

    .btn-icon.delete { color: #ef4444; background-color: rgba(239, 68, 68, 0.1); border: none; }
    .btn-icon.delete:hover { background-color: #ef4444; color: white; }

    /* Form Modernization */
    .modal-content {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .form-control, .form-select {
        border-color: #cbd5e1;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-modern mb-4">
            <div class="card-header d-flex w-100 justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark"><i class="fas fa-building text-muted me-2"></i> Daftar Tempat Pelatihan</h3>
                <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold rounded-pill shadow-sm" onclick="showModal('create')">
                    <i class="fas fa-plus me-1"></i> Tambah TC
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-modern mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Lembaga</th>
                                <th>Lokasi (Lat/Lng)</th>
                                <th>Kontak</th>
                                <th>Status</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tc-table-body">
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                    <div class="small">Memuat data master...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="tcModal" tabindex="-1" aria-labelledby="tcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="tcModalLabel">Tambah Training Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tcForm">
                <div class="modal-body p-4">
                    <input type="hidden" id="tc_id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Lembaga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light" id="nama" required placeholder="Contoh: PT. Prima IT Course">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Operasional</label>
                            <select class="form-select" id="status">
                                <option value="active">Aktif (Ditampilkan)</option>
                                <option value="inactive">Tidak Aktif (Disembunyikan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" rows="2" required placeholder="Jalan, RT/RW, Kota, Kode Pos"></textarea>
                    </div>

                    <div class="p-3 mb-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i>
                            <strong class="text-primary small">Sistem Geolocation (Opsional)</strong>
                        </div>
                        <p class="text-muted small mb-2 lh-sm">Data Latitude dan Longitude digunakan oleh Mesin Algoritma untuk menghitung jarak ke pengguna. Jika dikosongkan, skor jarak otomatis bernilai 0.</p>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="number" step="any" class="form-control form-control-sm" id="latitude" placeholder="Latitude (Cth: -7.xxx)">
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="any" class="form-control form-control-sm" id="longitude" placeholder="Longitude (Cth: 111.xxx)">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telepon" required placeholder="08xxxxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" placeholder="info@lembaga.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Situs Web</label>
                            <input type="url" class="form-control" id="website" placeholder="https://...">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Deskripsi / Fasilitas Singkat</label>
                        <textarea class="form-control" id="deskripsi" rows="2" placeholder="Tuliskan keunggulan lembaga di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="btnSave">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let tcModal;

    document.addEventListener('DOMContentLoaded', () => {
        tcModal = new bootstrap.Modal(document.getElementById('tcModal'));
        loadData();
    });

    async function loadData() {
        const tbody = document.getElementById('tc-table-body');
        try {
            const data = await window.authFetch(`${window.apiBase}/training-centers`).then(window.parseApi);
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-folder-open fs-2 mb-3 opacity-25 d-block"></i>Belum ada data Training Center</td></tr>`;
                return;
            }

            data.forEach((item, index) => {
                const tr = document.createElement('tr');

                const badgeStatus = item.status === 'active'
                    ? '<span class="badge rounded-pill" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);"><i class="fas fa-circle me-1" style="font-size:8px;"></i> Aktif</span>'
                    : '<span class="badge rounded-pill" style="background-color: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100,116,139,0.2);"><i class="fas fa-circle me-1" style="font-size:8px;"></i> Inaktif</span>';

                const latLng = (item.latitude && item.longitude)
                    ? `<span class="badge bg-light text-dark border text-monospace font-monospace"><i class="fas fa-map-marker-alt text-primary me-1"></i> ${item.latitude}, ${item.longitude}</span>`
                    : '<span class="text-muted small fst-italic">Kosong</span>';

                const contactHtml = `
                    <div class="fw-semibold text-dark"><i class="fas fa-phone-alt me-1 text-muted small"></i> ${item.telepon}</div>
                    ${item.email ? `<div class="small text-muted"><i class="fas fa-envelope me-1 opacity-50"></i> ${item.email}</div>` : ''}
                `;

                tr.innerHTML = `
                    <td class="text-muted">${index + 1}</td>
                    <td class="fw-bold text-dark">${item.nama}</td>
                    <td>${latLng}</td>
                    <td>${contactHtml}</td>
                    <td>${badgeStatus}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn-icon edit" onclick='editData(${JSON.stringify(item).replace(/'/g, "\\'")})' title="Edit Lembaga">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn-icon delete" onclick="confirmDelete(${item.id}, '${item.nama}')" title="Hapus Lembaga">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-1"></i> Gagal memuat data</td></tr>`;
        }
    }

    function showModal(mode) {
        document.getElementById('tcForm').reset();

        if (mode === 'create') {
            document.getElementById('tcModalLabel').textContent = 'Tambah Training Center Baru';
            document.getElementById('tc_id').value = '';
            document.getElementById('status').value = 'active';
        }
        tcModal.show();
    }

    function editData(item) {
        showModal('edit');
        document.getElementById('tcModalLabel').textContent = 'Edit Data Training Center';

        document.getElementById('tc_id').value = item.id;
        document.getElementById('nama').value = item.nama;
        document.getElementById('status').value = item.status;
        document.getElementById('alamat').value = item.alamat;
        document.getElementById('latitude').value = item.latitude || '';
        document.getElementById('longitude').value = item.longitude || '';
        document.getElementById('telepon').value = item.telepon;
        document.getElementById('email').value = item.email || '';
        document.getElementById('website').value = item.website || '';
        document.getElementById('deskripsi').value = item.deskripsi || '';
    }

    document.getElementById('tcForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('tc_id').value;
        const btnSave = document.getElementById('btnSave');

        const payload = {
            nama: document.getElementById('nama').value,
            status: document.getElementById('status').value,
            alamat: document.getElementById('alamat').value,
            latitude: document.getElementById('latitude').value || null,
            longitude: document.getElementById('longitude').value || null,
            telepon: document.getElementById('telepon').value,
            email: document.getElementById('email').value || null,
            website: document.getElementById('website').value || null,
            deskripsi: document.getElementById('deskripsi').value || null,
        };

        const url = id ? `${window.apiBase}/training-centers/${id}` : `${window.apiBase}/training-centers`;
        const method = id ? 'PUT' : 'POST';

        try {
            const originalText = btnSave.innerHTML;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

            const response = await window.authFetch(url, {
                method: method,
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal menyimpan data');
            }

            tcModal.hide();
            window.showToast('success', 'Data berhasil disimpan!');
            loadData();
            btnSave.innerHTML = originalText;
        } catch (error) {
            window.showAlert('error', 'Gagal!', error.message);
            btnSave.disabled = false;
            btnSave.innerHTML = 'Simpan Data';
        }
    });

    function confirmDelete(id, nama) {
        window.confirmAction(
            'Hapus Lembaga?',
            `Anda akan menghapus "${nama}". Seluruh Pelatihan di dalamnya juga akan terhapus.`,
            'Ya, Hapus Permanen',
            async () => {
                try {
                    const response = await window.authFetch(`${window.apiBase}/training-centers/${id}`, {
                        method: 'DELETE'
                    });

                    if (!response.ok) {
                        const resData = await response.json();
                        throw new Error(resData.message || 'Gagal menghapus');
                    }

                    window.showToast('success', 'Lembaga berhasil dihapus');
                    loadData();
                } catch (error) {
                    window.showAlert('error', 'Gagal', error.message);
                }
            }
        );
    }
</script>
@endpush
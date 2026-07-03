@extends('layouts.admin')

@section('title', 'Manajemen Pelatihan')
@section('page_title', 'Data Pelatihan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pelatihan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Pelatihan Teknis</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="showModal('create')">
                        <i class="fas fa-plus me-1"></i> Tambah Pelatihan
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Pelatihan</th>
                                <th>Lembaga (TC)</th>
                                <th>Bidang / Metode</th>
                                <th>Skill Min.</th>
                                <th>Status</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pelatihan-table-body">
                            <tr>
                                <td colspan="7" class="text-center py-4">
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

<!-- Modal CRUD -->
<div class="modal fade" id="pelatihanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pelatihanModalLabel">Tambah Pelatihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pelatihanForm">
                <div class="modal-body">
                    <div id="pelatihanAlert" class="alert alert-danger d-none"></div>
                    <input type="hidden" id="pelatihan_id">

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Judul Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Training Center <span class="text-danger">*</span></label>
                            <select class="form-select" id="training_center_id" required>
                                <option value="">Pilih Lembaga...</option>
                                <!-- Loaded via JS -->
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3"></textarea>
                    </div>

                    <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2 text-primary">Atribut Recommendation Engine</h6>
                    <div class="row bg-light p-3 rounded mb-3">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Bidang (Kategori)</label>
                            <select class="form-select" id="interest_category">
                                <option value="">Pilih...</option>
                                <option value="IT">IT & Teknologi</option>
                                <option value="Bisnis">Bisnis & Manajemen</option>
                                <option value="Desain">Desain & Kreatif</option>
                                <option value="Bahasa">Bahasa</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Metode</label>
                            <select class="form-select" id="method">
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Syarat Skill (Min)</label>
                            <select class="form-select" id="required_skill">
                                <option value="Beginner">Beginner (Pemula)</option>
                                <option value="Intermediate">Intermediate (Menengah)</option>
                                <option value="Advanced">Advanced (Mahir)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Popularitas Skor (0-100)</label>
                            <input type="number" class="form-control" id="popularity" value="0" min="0" max="100">
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3 fw-bold border-bottom pb-2">Informasi Tambahan</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mulai" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_selesai" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sertifikat</label>
                            <input type="text" class="form-control" id="sertifikat" placeholder="Ya / Tidak">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status Tayang</label>
                            <select class="form-select" id="is_active">
                                <option value="1">Aktif (Tampil)</option>
                                <option value="0">Draft (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pelatihan <strong id="deleteName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let pelatihanModal;
    let deleteModal;
    let currentDeleteId = null;
    let trainingCentersList = [];

    document.addEventListener('DOMContentLoaded', async () => {
        pelatihanModal = new bootstrap.Modal(document.getElementById('pelatihanModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        await fetchTrainingCenters();
        loadData();
    });

    async function fetchTrainingCenters() {
        try {
            trainingCentersList = await window.authFetch(`${window.apiBase}/training-centers`).then(window.parseApi);
            const selectTc = document.getElementById('training_center_id');
            trainingCentersList.forEach(tc => {
                const opt = document.createElement('option');
                opt.value = tc.id;
                opt.textContent = tc.nama;
                selectTc.appendChild(opt);
            });
        } catch (e) {
            console.error("Gagal load TC list", e);
        }
    }

    async function loadData() {
        const tbody = document.getElementById('pelatihan-table-body');
        try {
            const data = await window.authFetch(`${window.apiBase}/pelatihan`).then(window.parseApi);
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data Pelatihan</td></tr>`;
                return;
            }

            data.forEach((item, index) => {
                const tr = document.createElement('tr');
                const badgeActive = item.is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Draft</span>';

                let tcName = '<i class="text-danger">TC Tidak Ditemukan</i>';
                // Pada resource V1 lama mungkin blm eager load training center, kita cari dari list lokal
                const tcMatch = trainingCentersList.find(tc => tc.id == item.training_center_id);
                if (tcMatch) tcName = tcMatch.nama;
                // Jika dari resource sudah bawa (item.training_center.nama) gunakan itu
                if (item.training_center) tcName = item.training_center.nama;

                tr.innerHTML = `
                    <td class="align-middle">${index + 1}</td>
                    <td class="align-middle fw-semibold">${item.judul}</td>
                    <td class="align-middle">${tcName}</td>
                    <td class="align-middle">${item.interest_category || '-'} <br><small class="text-muted">${item.method || '-'}</small></td>
                    <td class="align-middle">${item.required_skill || '-'}</td>
                    <td class="align-middle">${badgeActive}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-info text-white" onclick='editData(${JSON.stringify(item).replace(/'/g, "\\'")})' title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(${item.id}, '${item.judul}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Gagal memuat data: ${error}</td></tr>`;
        }
    }

    function showModal(mode) {
        const alertBox = document.getElementById('pelatihanAlert');
        alertBox.classList.add('d-none');
        document.getElementById('pelatihanForm').reset();

        if (mode === 'create') {
            document.getElementById('pelatihanModalLabel').textContent = 'Tambah Pelatihan';
            document.getElementById('pelatihan_id').value = '';
            document.getElementById('is_active').value = '1';
        }
        pelatihanModal.show();
    }

    function editData(item) {
        showModal('edit');
        document.getElementById('pelatihanModalLabel').textContent = 'Edit Pelatihan';

        document.getElementById('pelatihan_id').value = item.id;
        document.getElementById('judul').value = item.judul;
        document.getElementById('training_center_id').value = item.training_center_id || '';
        document.getElementById('deskripsi').value = item.deskripsi || '';
        document.getElementById('interest_category').value = item.interest_category || '';
        document.getElementById('method').value = item.method || 'Online';
        document.getElementById('required_skill').value = item.required_skill || 'Beginner';
        document.getElementById('popularity').value = item.popularity || 0;
        document.getElementById('tanggal_mulai').value = item.tanggal_mulai ? item.tanggal_mulai.split('T')[0] : '';
        document.getElementById('tanggal_selesai').value = item.tanggal_selesai ? item.tanggal_selesai.split('T')[0] : '';
        document.getElementById('sertifikat').value = item.sertifikat || '';
        document.getElementById('is_active').value = item.is_active ? '1' : '0';
    }

    document.getElementById('pelatihanForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('pelatihan_id').value;
        const btnSave = document.getElementById('btnSave');
        const alertBox = document.getElementById('pelatihanAlert');

        const payload = {
            judul: document.getElementById('judul').value,
            training_center_id: document.getElementById('training_center_id').value,
            deskripsi: document.getElementById('deskripsi').value,
            interest_category: document.getElementById('interest_category').value,
            method: document.getElementById('method').value,
            required_skill: document.getElementById('required_skill').value,
            popularity: document.getElementById('popularity').value,
            tanggal_mulai: document.getElementById('tanggal_mulai').value,
            tanggal_selesai: document.getElementById('tanggal_selesai').value,
            sertifikat: document.getElementById('sertifikat').value,
            is_active: document.getElementById('is_active').value === '1',
        };

        const url = id ? `${window.apiBase}/pelatihan/${id}` : `${window.apiBase}/pelatihan`;
        const methodHttp = id ? 'PUT' : 'POST';

        try {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            alertBox.classList.add('d-none');

            const response = await window.authFetch(url, {
                method: methodHttp,
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                let errMsg = resData.message || 'Gagal menyimpan data';
                if(resData.errors) {
                    const firstKey = Object.keys(resData.errors)[0];
                    errMsg = resData.errors[firstKey][0];
                }
                throw new Error(errMsg);
            }

            pelatihanModal.hide();
            loadData();
        } catch (error) {
            alertBox.textContent = error.message;
            alertBox.classList.remove('d-none');
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Data';
        }
    });

    function confirmDelete(id, judul) {
        currentDeleteId = id;
        document.getElementById('deleteName').textContent = judul;
        deleteModal.show();
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
        if (!currentDeleteId) return;

        const btn = this;
        try {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';

            const response = await window.authFetch(`${window.apiBase}/pelatihan/${currentDeleteId}`, {
                method: 'DELETE'
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal menghapus');
            }

            deleteModal.hide();
            loadData();
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Ya, Hapus';
            currentDeleteId = null;
        }
    });
</script>
@endpush
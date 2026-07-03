@extends('layouts.admin')

@section('title', 'Manajemen Training Center')
@section('page_title', 'Training Center')

@section('breadcrumb')
    <li class="breadcrumb-item active">Training Center</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Tempat Pelatihan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="showModal('create')">
                        <i class="fas fa-plus me-1"></i> Tambah TC
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Lembaga</th>
                                <th>Lokasi (Lat/Lng)</th>
                                <th>Kontak</th>
                                <th>Status</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tc-table-body">
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

<!-- Modal CRUD -->
<div class="modal fade" id="tcModal" tabindex="-1" aria-labelledby="tcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tcModalLabel">Tambah Training Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tcForm">
                <div class="modal-body">
                    <div id="tcAlert" class="alert alert-danger d-none"></div>
                    <input type="hidden" id="tc_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lembaga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status">
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" id="latitude" placeholder="-7.xxx">
                            <small class="text-muted">Opsional, untuk perhitungan engine rekomendasi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" id="longitude" placeholder="111.xxx">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telepon" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" placeholder="https://">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3"></textarea>
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
                Apakah Anda yakin ingin menghapus lembaga <strong id="deleteName"></strong>?
                <p class="text-danger mt-2 small"><i class="fas fa-exclamation-triangle"></i> Peringatan: Menghapus TC akan otomatis menghapus seluruh Pelatihan di dalamnya.</p>
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
    let tcModal;
    let deleteModal;
    let currentDeleteId = null;

    document.addEventListener('DOMContentLoaded', () => {
        tcModal = new bootstrap.Modal(document.getElementById('tcModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        loadData();
    });

    async function loadData() {
        const tbody = document.getElementById('tc-table-body');
        try {
            const data = await window.authFetch(`${window.apiBase}/training-centers`).then(window.parseApi);
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Belum ada data Training Center</td></tr>`;
                return;
            }

            data.forEach((item, index) => {
                const tr = document.createElement('tr');
                const badgeClass = item.status === 'active' ? 'bg-success' : 'bg-secondary';
                const latLng = (item.latitude && item.longitude) ? `${item.latitude}, ${item.longitude}` : '<i class="text-muted">Tidak diset</i>';

                tr.innerHTML = `
                    <td class="align-middle">${index + 1}</td>
                    <td class="align-middle fw-semibold">${item.nama}</td>
                    <td class="align-middle">${latLng}</td>
                    <td class="align-middle">${item.telepon} <br><small class="text-muted">${item.email || ''}</small></td>
                    <td class="align-middle"><span class="badge ${badgeClass}">${item.status === 'active' ? 'Aktif' : 'Tidak Aktif'}</span></td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-info text-white" onclick='editData(${JSON.stringify(item).replace(/'/g, "\\'")})' title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(${item.id}, '${item.nama}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Gagal memuat data</td></tr>`;
        }
    }

    function showModal(mode) {
        const alertBox = document.getElementById('tcAlert');
        alertBox.classList.add('d-none');
        document.getElementById('tcForm').reset();

        if (mode === 'create') {
            document.getElementById('tcModalLabel').textContent = 'Tambah Training Center';
            document.getElementById('tc_id').value = '';
            document.getElementById('status').value = 'active';
        }
        tcModal.show();
    }

    function editData(item) {
        showModal('edit');
        document.getElementById('tcModalLabel').textContent = 'Edit Training Center';

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
        const alertBox = document.getElementById('tcAlert');

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
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            alertBox.classList.add('d-none');

            const response = await window.authFetch(url, {
                method: method,
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal menyimpan data');
            }

            tcModal.hide();
            loadData();
        } catch (error) {
            alertBox.textContent = error.message;
            alertBox.classList.remove('d-none');
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Data';
        }
    });

    function confirmDelete(id, nama) {
        currentDeleteId = id;
        document.getElementById('deleteName').textContent = nama;
        deleteModal.show();
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
        if (!currentDeleteId) return;

        const btn = this;
        try {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';

            const response = await window.authFetch(`${window.apiBase}/training-centers/${currentDeleteId}`, {
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
@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page_title', 'Activity Log')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activity Log</li>
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
        vertical-align: middle;
    }

    .table-modern tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Custom Checkbox styles */
    .form-check-input {
        cursor: pointer;
        width: 1.2em;
        height: 1.2em;
        border-color: #cbd5e1;
    }
    .form-check-input:checked {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-modern mb-4">
            <div class="card-header d-flex w-100 justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">
                    <i class="fas fa-shoe-prints text-muted me-2"></i> Jejak Aktivitas Pengguna
                </h3>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill" id="btnRefresh" onclick="loadData()">
                        <i class="fas fa-sync-alt me-1"></i> Segarkan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm px-3 fw-semibold rounded-pill shadow-sm d-none" id="btnDeleteBulk" onclick="confirmBulkDelete()">
                        <i class="fas fa-trash-alt me-1"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-modern mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input class="form-check-input" type="checkbox" id="checkAll" onchange="toggleAllCheckboxes(this)">
                                </th>
                                <th style="width: 50px;">No</th>
                                <th style="width: 180px;">Waktu</th>
                                <th>User</th>
                                <th>Tipe Aktivitas</th>
                                <th>Konteks (TC / Pelatihan)</th>
                            </tr>
                        </thead>
                        <tbody id="log-table-body">
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                    <div class="small">Memuat log...</div>
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
    let logsData = [];

    document.addEventListener('DOMContentLoaded', async () => {
        await loadData();
    });

    async function loadData() {
        const tbody = document.getElementById('log-table-body');
        const btnRefresh = document.getElementById('btnRefresh');

        try {
            if(btnRefresh) {
                btnRefresh.innerHTML = '<i class="fas fa-sync-alt fa-spin me-1"></i> Memuat...';
                btnRefresh.disabled = true;
            }

            // Reset UI states
            document.getElementById('checkAll').checked = false;
            updateDeleteButton();

            const res = await window.authFetch(window.apiBase + '/admin/log-activities');
            const parsed = await window.parseApi(res);

            // Handle potential array wrapping
            logsData = Array.isArray(parsed) ? parsed : (parsed.data || []);

            tbody.innerHTML = '';

            if (!logsData || logsData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-inbox fs-2 mb-3 opacity-25 d-block"></i>Belum ada log aktivitas terdeteksi.</td></tr>`;
                return;
            }

            logsData.forEach((item, index) => {
                const dateObj = new Date(item.created_at);
                const dateOptions = { day: 'numeric', month: 'short', year: 'numeric' };
                const dateStr = dateObj.toLocaleDateString('id-ID', dateOptions);
                const timeStr = dateObj.toLocaleTimeString('id-ID');

                const user = item.user ? item.user.name : 'System/Unknown';
                let context = '<span class="text-muted small fst-italic">Tanpa Konteks Khusus</span>';

                if (item.training_center) {
                    context = `<div class="mb-1"><i class="fas fa-building text-muted me-1 small opacity-75"></i> ${item.training_center.nama}</div>`;
                }
                if (item.pelatihan) {
                    context += `<div><i class="fas fa-book text-muted me-1 small opacity-75"></i> ${item.pelatihan.judul}</div>`;
                }

                let typeBadge = '';
                switch (item.activity_type.toLowerCase()) {
                    case 'login': typeBadge = '<span class="badge rounded-pill" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">Login</span>'; break;
                    case 'logout': typeBadge = '<span class="badge rounded-pill" style="background-color: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100,116,139,0.2);">Logout</span>'; break;
                    case 'enroll': typeBadge = '<span class="badge rounded-pill" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2);">Enrollment</span>'; break;
                    case 'view_detail': typeBadge = '<span class="badge rounded-pill" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">View Detail</span>'; break;
                    default: typeBadge = `<span class="badge bg-light text-dark border rounded-pill">${item.activity_type}</span>`;
                }

                const tr = `
                    <tr>
                        <td class="text-center">
                            <input class="form-check-input row-checkbox" type="checkbox" value="${item.id}" onchange="handleCheckboxChange()">
                        </td>
                        <td class="text-muted">${index + 1}</td>
                        <td>
                            <div class="fw-semibold text-dark">${dateStr}</div>
                            <div class="text-muted small"><i class="far fa-clock me-1 opacity-50"></i> ${timeStr}</div>
                        </td>
                        <td class="fw-bold text-dark">${user}</td>
                        <td>${typeBadge}</td>
                        <td>${context}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', tr);
            });
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-1"></i> Gagal memuat log aktivitas.</td></tr>`;
        } finally {
            if(btnRefresh) {
                btnRefresh.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Segarkan';
                btnRefresh.disabled = false;
            }
        }
    }

    // Checkbox Logic
    function toggleAllCheckboxes(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updateDeleteButton();
    }

    function handleCheckboxChange() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const masterCheckbox = document.getElementById('checkAll');

        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        const someChecked = Array.from(checkboxes).some(cb => cb.checked);

        masterCheckbox.checked = allChecked;
        masterCheckbox.indeterminate = someChecked && !allChecked;

        updateDeleteButton();
    }

    function updateDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const count = checkedBoxes.length;
        const btnDeleteBulk = document.getElementById('btnDeleteBulk');
        const spanCount = document.getElementById('selectedCount');

        if(count > 0) {
            spanCount.textContent = count;
            btnDeleteBulk.classList.remove('d-none');
            btnDeleteBulk.classList.add('d-inline-flex'); // show
        } else {
            btnDeleteBulk.classList.add('d-none');
            btnDeleteBulk.classList.remove('d-inline-flex'); // hide
        }
    }

    // Bulk Delete Action
    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const idsToDelete = Array.from(checkedBoxes).map(cb => cb.value);

        if(idsToDelete.length === 0) return;

        window.confirmAction(
            'Hapus Log Terpilih?',
            `Anda yakin ingin menghapus ${idsToDelete.length} data riwayat aktivitas secara permanen?`,
            'Ya, Hapus',
            async () => {
                const btnDeleteBulk = document.getElementById('btnDeleteBulk');
                const originalText = btnDeleteBulk.innerHTML;

                btnDeleteBulk.disabled = true;
                btnDeleteBulk.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...';

                try {
                    // Because standard REST API usually doesn't have a built-in mass delete endpoint out of the box,
                    // we simulate mass delete via Promise.all (concurrent requests)
                    // Note: If you have a true bulk-delete API (e.g. POST /api/admin/log-activities/bulk-delete),
                    // you should use that instead for better performance.

                    const deletePromises = idsToDelete.map(id => {
                        return window.authFetch(`${window.apiBase}/admin/log-activities/${id}`, { method: 'DELETE' });
                    });

                    const results = await Promise.allSettled(deletePromises);

                    // Check if any failed
                    const failures = results.filter(r => r.status === 'rejected' || !r.value.ok);

                    if(failures.length > 0) {
                        window.showAlert('warning', 'Penghapusan Parsial', `Berhasil menghapus sebagian, namun ${failures.length} log gagal dihapus.`);
                    } else {
                        window.showToast('success', `${idsToDelete.length} Log berhasil dihapus`);
                    }

                    // Reload
                    await loadData();
                } catch (error) {
                    window.showAlert('error', 'Gagal', 'Terjadi kesalahan sistem saat menghapus.');
                } finally {
                    btnDeleteBulk.disabled = false;
                    btnDeleteBulk.innerHTML = originalText;
                }
            }
        );
    }
</script>
@endpush
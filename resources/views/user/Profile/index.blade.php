@extends('layouts.user')

@section('title', 'Profil Saya')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    #map {
        height: 350px;
        width: 100%;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        z-index: 1; /* prevent dropdown overlap issues */
    }

    .form-control, .form-select {
        border-color: var(--border-color);
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        color: var(--text-main);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }

    .section-container {
        background-color: var(--bg-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .section-title-modern {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-location {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        font-weight: 600;
        border-radius: 50rem;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-location:hover {
        background-color: var(--primary-color);
        color: white;
        box-shadow: var(--shadow-sm);
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4 gap-3">
            <div class="bg-indigo-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(79, 70, 229, 0.1);">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0" style="color: var(--text-main);">Profil Saya</h3>
                <p class="text-muted mb-0 small">Lengkapi data diri dan lokasi Anda untuk mendapatkan rekomendasi akurat.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4" style="background: var(--surface-color);">
            <div class="card-body p-4 p-md-5">
                <form id="profileForm">

                    {{-- DEMOGRAFI SECTION --}}
                    <div class="section-container">
                        <div class="section-title-modern">
                            <i class="fas fa-id-card"></i> Data Demografi
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Usia <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="age" min="15" max="100" required placeholder="Contoh: 24">
                                    <span class="input-group-text bg-white text-muted border-start-0">Tahun</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" placeholder="08xxxxxxxx" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pendidikan Terakhir</label>
                                <select class="form-select" id="education">
                                    <option value="">Pilih Pendidikan...</option>
                                    <option value="SMA/SMK">SMA / SMK / Sederajat</option>
                                    <option value="D3">D3 (Diploma)</option>
                                    <option value="S1">S1 (Sarjana)</option>
                                    <option value="S2">S2 (Magister)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan / Distrik</label>
                                <input type="text" class="form-control" id="district" placeholder="Cth: Magetan">
                            </div>
                        </div>
                    </div>

                    {{-- LOKASI SECTION --}}
                    <div class="section-container">
                        <div class="section-title-modern">
                            <i class="fas fa-map-marked-alt"></i> Informasi Geografis (Lokasi)
                        </div>

                        <div class="alert bg-blue-subtle border-0 rounded-3 text-dark d-flex align-items-start gap-3 p-3 mb-4" style="background-color: rgba(59, 130, 246, 0.1);">
                            <i class="fas fa-info-circle text-primary mt-1"></i>
                            <div class="small lh-sm">
                                Lokasi Anda digunakan oleh sistem untuk menghitung jarak ke tempat pelatihan terdekat. Silakan klik tombol <strong>Gunakan Lokasi Saat Ini</strong> atau geser pin merah pada peta di bawah ini secara manual.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat_lengkap" rows="2" placeholder="Cth: Jl. Raya Madiun-Magetan No 12..."></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control" style="background-color: #f1f5f9; color: var(--text-muted);" id="latitude" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control" style="background-color: #f1f5f9; color: var(--text-muted);" id="longitude" readonly required>
                            </div>
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="fw-bold mb-0 text-dark">Peta Titik Lokasi</h6>
                            <button type="button" class="btn-location" id="btnGetCurrentLocation">
                                <i class="fas fa-location-arrow me-1"></i> Deteksi Lokasi Saya
                            </button>
                        </div>

                        <div class="p-2 border rounded-3 bg-white shadow-sm mb-2">
                            <div id="map"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn fw-bold px-4 py-2 rounded-pill shadow-sm" id="btnSave" style="background-color: var(--primary-color); color: white;">
                            <i class="fas fa-save me-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    let map;
    let marker;
    const defaultLat = -7.6500; // Default Magetan
    const defaultLng = 111.3300;

    document.addEventListener('DOMContentLoaded', async () => {
        initMap();
        await loadProfile();
    });

    function initMap() {
        map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        // Update input saat marker digeser
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            const lat = position.lat.toFixed(6);
            const lng = position.lng.toFixed(6);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            reverseGeocode(lat, lng);
        });

        // Update marker saat peta diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            reverseGeocode(lat, lng);
        });
    }

    // Geolocation API (Browser)
    document.getElementById('btnGetCurrentLocation').addEventListener('click', () => {
        if (navigator.geolocation) {
            const btn = document.getElementById('btnGetCurrentLocation');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mencari...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updateMapMarker(lat, lng, true); // true = auto fill address
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                },
                (error) => {
                    window.showAlert('error', 'Akses Ditolak', 'Gagal mendapatkan lokasi. Pastikan izin lokasi (GPS) di browser diaktifkan.');
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            window.showAlert('error', 'Tidak Didukung', 'Browser Anda tidak mendukung fitur Geolocation.');
        }
    });

    async function reverseGeocode(lat, lng) {
        try {
            // Using Nominatim API from OpenStreetMap (Free, no API key required)
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
            const data = await response.json();

            if (data && data.address) {
                // Update address textarea
                document.getElementById('alamat_lengkap').value = data.display_name || '';

                // Try to extract district/kecamatan
                let district = data.address.city_district || data.address.suburb || data.address.county || data.address.city || '';
                document.getElementById('district').value = district;
            }
        } catch (error) {
            console.warn('Reverse geocoding failed:', error);
        }
    }

    function updateMapMarker(lat, lng, reverseGeo = false) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        const newLatLng = new L.LatLng(lat, lng);
        marker.setLatLng(newLatLng);
        map.flyTo(newLatLng, 15);

        if (reverseGeo) {
            reverseGeocode(lat, lng);
        }
    }

    async function loadProfile() {
        try {
            const data = await window.authFetch(window.apiBase + '/profile').then(window.parseApi);
            if (data) {
                document.getElementById('age').value = data.age || '';
                document.getElementById('phone').value = data.phone || '';
                document.getElementById('education').value = data.education || '';
                document.getElementById('district').value = data.district || '';
                document.getElementById('alamat_lengkap').value = data.alamat_lengkap || '';

                if (data.latitude && data.longitude) {
                    updateMapMarker(data.latitude, data.longitude);
                }
            }
        } catch (e) {
            // Profile probably not created yet (404), do nothing and let user fill it
        }
    }

    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btnSave = document.getElementById('btnSave');

        const payload = {
            age: document.getElementById('age').value,
            phone: document.getElementById('phone').value,
            education: document.getElementById('education').value,
            district: document.getElementById('district').value,
            alamat_lengkap: document.getElementById('alamat_lengkap').value,
            latitude: document.getElementById('latitude').value,
            longitude: document.getElementById('longitude').value,
        };

        try {
            const originalHTML = btnSave.innerHTML;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

            const response = await window.authFetch(window.apiBase + '/profile', {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal menyimpan profil.');
            }

            btnSave.innerHTML = originalHTML;
            btnSave.disabled = false;

            // Success with redirect option
            Swal.fire({
                icon: 'success',
                title: 'Profil Tersimpan',
                text: 'Data profil dan lokasi Anda berhasil diperbarui.',
                showCancelButton: true,
                confirmButtonText: 'Lanjut ke Kuesioner',
                cancelButtonText: 'Tutup',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal2-confirm border-0 text-white me-2',
                    cancelButton: 'swal2-cancel border-0 text-white'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/user/questionnaire';
                }
            });

        } catch (error) {
            window.showAlert('error', 'Gagal Disimpan', error.message);
            btnSave.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Profil';
            btnSave.disabled = false;
        }
    });
</script>
@endpush
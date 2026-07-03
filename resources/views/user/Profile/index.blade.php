@extends('layouts.user')

@section('title', 'Profil Saya')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    #map {
        height: 350px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        z-index: 1; /* prevent dropdown overlap issues */
    }
    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h3 class="fw-bold mb-4">Profil Saya</h3>

        <div id="alertBox" class="alert d-none mb-4"></div>

        <div class="profile-card">
            <form id="profileForm">
                <div class="section-title">Data Demografi</div>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Usia <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="age" min="15" max="100" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" placeholder="08xxxxxxxx" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                        <select class="form-select" id="education">
                            <option value="">Pilih Pendidikan...</option>
                            <option value="SMA/SMK">SMA / SMK / Sederajat</option>
                            <option value="D3">D3 (Diploma)</option>
                            <option value="S1">S1 (Sarjana)</option>
                            <option value="S2">S2 (Magister)</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kecamatan / Distrik</label>
                        <input type="text" class="form-control" id="district" placeholder="Cth: Magetan">
                    </div>
                </div>

                <div class="section-title">Informasi Geografis (Lokasi)</div>
                <p class="text-muted small mb-3">
                    <i class="fas fa-info-circle text-primary"></i>
                    Lokasi Anda akan digunakan oleh sistem untuk menghitung jarak ke tempat pelatihan terdekat. Silakan geser pin pada peta di bawah ini.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat_lengkap" rows="2" placeholder="Cth: Jl. Raya Madiun-Magetan No 12..."></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" step="any" class="form-control bg-light" id="latitude" readonly required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" step="any" class="form-control bg-light" id="longitude" readonly required>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="btnGetCurrentLocation">
                        <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                    </button>
                    <div id="map"></div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2" id="btnSave">
                        Simpan Profil
                    </button>
                </div>
            </form>
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
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updateMapMarker(lat, lng, true); // true = auto fill address
                    btn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini';
                    btn.disabled = false;
                },
                (error) => {
                    alert('Gagal mendapatkan lokasi. Pastikan izin lokasi (GPS) di browser diaktifkan.');
                    btn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini';
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            alert('Browser Anda tidak mendukung Geolocation.');
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
        const alertBox = document.getElementById('alertBox');

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
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            alertBox.classList.add('d-none');

            const response = await window.authFetch(window.apiBase + '/profile', {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal menyimpan profil.');
            }

            alertBox.className = 'alert alert-success mt-3';
            alertBox.innerHTML = '<i class="fas fa-check-circle"></i> Profil berhasil disimpan! <a href="/user/questionnaire" class="alert-link">Lanjutkan ke Kuesioner <i class="fas fa-arrow-right"></i></a>';
            alertBox.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } catch (error) {
            alertBox.className = 'alert alert-danger mt-3';
            alertBox.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + error.message;
            alertBox.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Profil';
        }
    });
</script>
@endpush
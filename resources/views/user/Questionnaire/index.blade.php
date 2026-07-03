@extends('layouts.user')

@section('title', 'Kuesioner Preferensi')

@push('styles')
<style>
    .question-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 24px;
        transition: border-color 0.2s;
    }
    .question-card:hover {
        border-color: #d1d5db;
    }
    .question-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }
    .question-desc {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    /* Custom Radio Cards */
    .radio-card-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .radio-card-input {
        display: none;
    }
    .radio-card-label {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background: #fff;
    }
    .radio-card-label:hover {
        border-color: #a5b4fc;
        background: #f8fafc;
    }
    .radio-card-input:checked + .radio-card-label {
        border-color: #4f46e5;
        background: #eef2ff;
    }
    .radio-card-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
        font-size: 1rem;
    }
    .radio-card-input:checked + .radio-card-label .radio-card-title {
        color: #4f46e5;
    }
    .radio-card-text {
        font-size: 0.85rem;
        color: #6b7280;
    }

    #loadingOverlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.85);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .spinner-border {
        width: 3rem; height: 3rem;
        border-width: 0.25em;
    }
</style>
@endpush

@section('content')
<div id="loadingOverlay" class="d-none">
    <div class="spinner-border text-primary mb-3" role="status"></div>
    <h4 class="fw-bold text-gray-800">Menganalisis Kecocokan...</h4>
    <p class="text-muted">Recommendation Engine sedang memproses data Anda.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 20px;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0">Kuesioner Preferensi</h3>
                <p class="text-muted mb-0">Jawaban Anda menentukan hasil rekomendasi sistem.</p>
            </div>
        </div>

        <div id="alertBox" class="alert d-none mb-4"></div>

        <form id="kuesionerForm">

            <!-- Pertanyaan 1: Bidang -->
            <div class="question-card">
                <div class="question-title">1. Bidang pelatihan apa yang paling Anda minati? <span class="text-danger">*</span></div>
                <div class="question-desc">Sistem akan memprioritaskan pelatihan di bidang ini (Bobot 35%).</div>

                <select class="form-select form-select-lg" id="q_bidang" required>
                    <option value="">-- Pilih Bidang Keahlian --</option>
                    <option value="IT">IT & Teknologi (Pemrograman, Jaringan, Data)</option>
                    <option value="Bisnis">Bisnis & Manajemen (Marketing, Akuntansi)</option>
                    <option value="Desain">Desain & Kreatif (Grafis, UI/UX, Video)</option>
                    <option value="Bahasa">Bahasa Asing</option>
                    <option value="Lainnya">Lainnya / Umum</option>
                </select>
            </div>

            <!-- Pertanyaan 2: Skill Level -->
            <div class="question-card">
                <div class="question-title">2. Bagaimana tingkat keahlian dasar Anda saat ini? <span class="text-danger">*</span></div>
                <div class="question-desc">Penting untuk menyesuaikan kurikulum agar Anda tidak tertinggal (Bobot 20%).</div>

                <div class="radio-card-wrapper">
                    <div>
                        <input type="radio" name="q_skill" id="skill_beginner" value="Beginner" class="radio-card-input" required>
                        <label for="skill_beginner" class="radio-card-label">
                            <span class="radio-card-title">Beginner</span>
                            <span class="radio-card-text">Pemula, baru belajar dari nol.</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="q_skill" id="skill_intermediate" value="Intermediate" class="radio-card-input">
                        <label for="skill_intermediate" class="radio-card-label">
                            <span class="radio-card-title">Intermediate</span>
                            <span class="radio-card-text">Sudah paham dasar, butuh materi menengah.</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="q_skill" id="skill_advanced" value="Advanced" class="radio-card-input">
                        <label for="skill_advanced" class="radio-card-label">
                            <span class="radio-card-title">Advanced</span>
                            <span class="radio-card-text">Mahir, fokus pada studi kasus kompleks.</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan 3: Metode -->
            <div class="question-card">
                <div class="question-title">3. Metode pelatihan seperti apa yang Anda inginkan? <span class="text-danger">*</span></div>
                <div class="question-desc">Sistem akan mencocokkan jadwal dan kehadiran (Bobot 15%).</div>

                <div class="radio-card-wrapper">
                    <div>
                        <input type="radio" name="q_metode" id="metode_online" value="Online" class="radio-card-input" required>
                        <label for="metode_online" class="radio-card-label">
                            <i class="fas fa-laptop mb-2 text-primary fs-4"></i>
                            <span class="radio-card-title">Online</span>
                            <span class="radio-card-text">Belajar dari rumah (Zoom/Meet).</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="q_metode" id="metode_offline" value="Offline" class="radio-card-input">
                        <label for="metode_offline" class="radio-card-label">
                            <i class="fas fa-building mb-2 text-primary fs-4"></i>
                            <span class="radio-card-title">Offline (Tatap Muka)</span>
                            <span class="radio-card-text">Hadir langsung di kelas.</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="q_metode" id="metode_hybrid" value="Hybrid" class="radio-card-input">
                        <label for="metode_hybrid" class="radio-card-label">
                            <i class="fas fa-sync-alt mb-2 text-primary fs-4"></i>
                            <span class="radio-card-title">Hybrid</span>
                            <span class="radio-card-text">Kombinasi Online dan Offline.</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan 4: Jarak -->
            <div class="question-card">
                <div class="question-title">4. Jarak maksimal ke lokasi pelatihan yang dapat Anda tempuh? <span class="text-danger">*</span></div>
                <div class="question-desc">Digunakan oleh Haversine Distance Calculator (Bobot 20%).</div>

                <div class="input-group input-group-lg">
                    <input type="number" class="form-control" id="q_jarak" min="1" max="1000" value="50" required>
                    <span class="input-group-text bg-light">Kilometer (KM)</span>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="btnSubmit">
                    <i class="fas fa-magic me-2"></i> Temukan Rekomendasi Saya
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        await loadKuesioner();
    });

    async function loadKuesioner() {
        try {
            const data = await window.authFetch(window.apiBase + '/questionnaire').then(window.parseApi);
            if (data) {
                if(data.bidang_diminati) document.getElementById('q_bidang').value = data.bidang_diminati;

                if(data.tingkat_keahlian) {
                    const el = document.querySelector(`input[name="q_skill"][value="${data.tingkat_keahlian}"]`);
                    if(el) el.checked = true;
                }

                if(data.metode_pelatihan) {
                    const el = document.querySelector(`input[name="q_metode"][value="${data.metode_pelatihan}"]`);
                    if(el) el.checked = true;
                }

                if(data.jarak_maksimal) {
                    document.getElementById('q_jarak').value = data.jarak_maksimal;
                }
            }
        } catch (e) {
            // No questionnaire data yet
        }
    }

    document.getElementById('kuesionerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Ambil value dari form
        const bidang = document.getElementById('q_bidang').value;
        const skillEl = document.querySelector('input[name="q_skill"]:checked');
        const metodeEl = document.querySelector('input[name="q_metode"]:checked');
        const jarak = document.getElementById('q_jarak').value;

        if (!bidang || !skillEl || !metodeEl || !jarak) {
            alert('Mohon isi semua pertanyaan.');
            return;
        }

        const payload = {
            answers: {
                bidang_diminati: bidang,
                tingkat_keahlian: skillEl.value,
                metode_pelatihan: metodeEl.value,
                jarak_maksimal: jarak
            }
        };

        const overlay = document.getElementById('loadingOverlay');
        const alertBox = document.getElementById('alertBox');
        alertBox.classList.add('d-none');
        overlay.classList.remove('d-none'); // Tampilkan UI Loading

        try {
            const response = await window.authFetch(window.apiBase + '/questionnaire', {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const resData = await response.json();
                throw new Error(resData.message || 'Gagal memproses rekomendasi.');
            }

            // Tunggu Engine selesai, lalu pindah ke halaman rekomendasi
            window.location.href = '/user/recommendations';

        } catch (error) {
            overlay.classList.add('d-none');
            alertBox.className = 'alert alert-danger';
            alertBox.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + error.message;
            alertBox.classList.remove('d-none');
            window.scrollTo(0,0);
        }
    });
</script>
@endpush
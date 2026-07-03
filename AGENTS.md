# AGENTS.md

## Tujuan
Project ini merupakan Sistem Rekomendasi Tempat Pelatihan berbasis Rule-Based Scoring.
Sistem ini membuang arsitektur lama (Mentor, Peserta, Pendaftaran Manual) dan sepenuhnya mengadopsi arsitektur desentralisasi via REST API V2 (Training Center, Pelatihan, Profile User, Questionnaire, Enrollment Rekomendasi).

Sistem menggunakan data Profile, QuestionnaireResponse, dan Pelatihan untuk menghasilkan rekomendasi yang dipersonalisasi. Rekomendasi dikompilasi (diagregasi) dari tingkat Pelatihan ke tingkat entitas *Training Center* (TC).

## Status Terkini (FINAL)
Seluruh lapisan aplikasi, mulai dari Database, Logic Backend, REST API, hingga Frontend (Admin Panel & User UI) **telah diselesaikan 100% dan terintegrasi secara E2E (End-to-End)**. Tidak ada lagi sisa-sisa *legacy code* atau UI jadul yang mengganggu.

---

## 1. Arsitektur Logic Backend (Recommendation Engine)
Recommendation dihitung oleh `RecommendationEngine` menggunakan tiga fase berurutan:

1. **Phase 1: Hard Filter**
   - Mengeliminasi Pelatihan yang `is_active` = false atau tidak memiliki `training_center_id`.
   - Melakukan filter ketat berdasarkan preferensi Kuesioner User (Bidang/Kategori, Metode [Online/Offline/Hybrid], dan Tingkat Keahlian).
   - *Catatan: Hybrid dianggap selalu match dengan preferensi Online/Offline.*

2. **Phase 2: Weighted Scoring & Aggregation**
   Skor dasar dihitung dari masing-masing Pelatihan yang lolos *Hard Filter*:
   - **Bidang Diminati:** 35%
   - **Skill Match:** 20%
   - **Metode Match:** 15%
   - **Popularitas:** 10%
   
   *Aggregasi:* Jika satu Training Center memiliki banyak pelatihan yang *match*, Engine hanya mengambil **skor tertinggi** untuk mewakili TC tersebut.
   
   *Distance Calculation (Haversine Formula):* 20%
   - Menghitung jarak lurus (berdasarkan lengkung bumi) antara Latitude & Longitude Profile User ke Latitude & Longitude Training Center.
   - Jarak 0 km = +20 Poin. Jarak >= 100 km = 0 Poin.
   
   **Total Maksimal = 100%.**

3. **Phase 3: Persist Recommendation**
   - Top 5 Training Center disimpan permanen ke tabel `recommendations`. 
   - Endpoint frontend hanya bertugas menarik (GET) data dari tabel ini secara instan, menghemat beban *query* CPU di server.

---

## 2. Alur Integrasi Sistem (Frontend ↔ Backend REST API)

Integrasi telah berjalan secara *Decoupled Architecture*.
- **Authentication:** `POST /api/login`. Menggunakan Laravel Sanctum. Menyimpan Token di `localStorage`. Di-*handle* oleh middleware kustom `SystemAuth`.
- **Layout & Protection:** `window.authFetch` secara asinkron menyisipkan Header `Bearer Token`. Jika API mengembalikan 401, *Frontend UI* akan langsung *logout* paksa user.
- **User Module:**
  1. *Gatekeeper API:* Di `user/Dashboard/dashboard.blade.php`, sistem secara otomatis memanggil `GET /api/profile` dan `GET /api/questionnaire`. 
  2. Jika profil kosong, *User* diarahkan ke `/user/profile` untuk input Demografi dan Map Pinpoint.
  3. Jika kuesioner kosong, *User* diarahkan ke `/user/questionnaire`.
  4. Submit Kuesioner via `POST /api/questionnaire` otomatis me-*trigger* kalkulasi `RecommendationEngine` di *background*.
  5. *Recommendation UI:* Mengambil `GET /api/recommendations` dan me-render *Card TC* dengan persentase skor kecocokan dan jarak.
  6. *Enrollment (Pendaftaran):* Mengklik Modal *Daftar* memanggil `POST /api/enrollments`. Hasilnya masuk ke riwayat pendaftaran.
- **Admin Module:**
  - Menggunakan UI/UX modern berbasis *AdminLTE v4*.
  - Mengelola entitas dari *API Master Data* (`GET/POST/PUT/DELETE /api/training-centers` dan `/api/pelatihan`).
  - *Dashboard Metrik* & *Activity Log* mengambil data *real-time* dari `GET /api/admin/stats` dan `GET /api/admin/log-activities`.
  - Admin dapat memblokir/mengaktifkan kembali user via API `PATCH /api/admin/users/{id}/status`.

---

## 3. Map Geolocation API
- Frontend Profile User telah terintegrasi dengan **Leaflet.js (OpenStreetMap)**.
- Fitur *Drag Pin* dan Klik titik pada peta.
- Fitur **Reverse Geocoding**: Memanggil Nominatim API untuk menterjemahkan titik koordinat ke teks Alamat Lengkap dan Kecamatan secara otomatis (*Auto-fill*).
- Fitur GPS (Browser Geolocation) tersemat di tombol *Gunakan Lokasi Saat Ini*.

---

## 4. Entity List & Relasi Database (V2 Core)

Sistem telah di-refactor menggunakan Relasi Eloquent (ORM) yang efisien:

- **`User`** (tabel: `tabel_users`)
  - Menyimpan Credential (email, password), Role (`admin`, `user`), dan Status Aktif (`is_active`).
  - *Relasi:* 
    - `hasOne(Profile)`
    - `hasOne(QuestionnaireResponse)`
    - `hasMany(Recommendation)`
    - `hasMany(Enrollment)`
    - `hasMany(LogActivity)`

- **`Profile`** (tabel: `profiles`)
  - Menyimpan Demografi (age, education) & Geospatial Koordinat (latitude, longitude, district).
  - *Relasi:* `belongsTo(User)`

- **`QuestionnaireResponse`** (tabel: `questionnaire_responses`)
  - Menyimpan JSON Jawaban Preferensi User (`bidang_diminati`, `tingkat_keahlian`, `metode_pelatihan`, `jarak_maksimal`).
  - *Relasi:* `belongsTo(User)`

- **`TrainingCenter`** (tabel: `training_centers`)
  - Lembaga pelaksana, entitas induk untuk Pelatihan. Memiliki koordinat spasial untuk perhitungan jarak (Haversine).
  - *Relasi:*
    - `hasMany(Pelatihan)`
    - `hasMany(Recommendation)`
    - `hasMany(Enrollment)`
    - `hasMany(LogActivity)`

- **`Pelatihan`** (tabel: `tabel_pelatihan`)
  - Produk/Kursus teknis. Menyumbangkan poin pada sistem melalui atribut skor (`interest_category`, `method`, `required_skill`, `popularity`).
  - *Relasi:*
    - `belongsTo(TrainingCenter)` (Otomatis Cascade Delete)
    - `hasMany(Enrollment)`
    - `hasMany(LogActivity)`

- **`Recommendation`** (tabel: `recommendations`)
  - Tabel temporer/statis penampung Top-5 hasil komputasi *Engine*.
  - Menyimpan field `score` (0-100), `distance` (km), dan `rank`.
  - *Relasi:* `belongsTo(User)`, `belongsTo(TrainingCenter)`

- **`Enrollment`** (tabel: `enrollments`)
  - Bukti keberhasilan (konversi) rekomendasi menjadi Pendaftaran Aktual.
  - *Relasi:* `belongsTo(User)`, `belongsTo(TrainingCenter)`, `belongsTo(Pelatihan)`

- **`LogActivity`** (tabel: `log_activities`)
  - Jejak audit aktivitas user dalam sistem (Tracking: Login, Enroll).
  - *Relasi:* `belongsTo(User)`, `belongsTo(TrainingCenter)`, `belongsTo(Pelatihan)`

---

## 5. Security & Technical Debt (Selesai/Dihapus)
- [x] Perubahan branding UI dan Middleware menjadi "Sistem Rekomendasi Tempat Pelatihan (SRTP)".
- [x] Seluruh *Sisa Legacy Mentor* dihapus total (Migration, Models, Controllers, Seeders, View Blade).
- [x] Tabel-tabel *Pendaftaran Manual* dan *Peserta* dihapus total dari Views.
- [x] Impelementasi `Global Exception Handler` dengan format Response JSON ketat untuk menangkal Error Leakage ke Frontend.
- [x] API Security: API diproteksi ketat menggunakan `Laravel Sanctum`.
- [x] Security Policy: Middleware kustom memfilter Hak Akses agar akun bertipe 'user' tidak bisa membaca endpoint maupun memanipulasi UI Admin, begitu pun sebaliknya.
- [x] Perbaikan *Error 403 HTTP Interceptor* di frontend yang sebelumnya menghapus token secara tidak sengaja, kini ditangani dengan metode yang aman.

Project telah bersih dan siap di-*deploy* ke *production* untuk keperluan Sidang Skripsi.
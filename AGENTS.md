# AGENTS.md

## Tujuan
Project ini merupakan Sistem Rekomendasi Tempat Pelatihan berbasis Rule-Based Scoring.
Sistem ini membuang arsitektur lama (Mentor, Peserta, Pendaftaran Manual) dan sepenuhnya mengadopsi arsitektur desentralisasi via REST API V2 (Training Center, Pelatihan, Profile User, Questionnaire, Enrollment Rekomendasi).

Sistem menggunakan data Profile, QuestionnaireResponse, dan Pelatihan untuk menghasilkan rekomendasi yang dipersonalisasi. Rekomendasi dikompilasi (diagregasi) dari tingkat Pelatihan ke tingkat entitas *Training Center* (TC).

## Status Terkini (FINAL)
Seluruh lapisan aplikasi, mulai dari Database, Logic Backend, REST API, hingga Frontend (Admin Panel, User UI, Landing Page) **telah diselesaikan 100% dan terintegrasi secara E2E (End-to-End)**. Tidak ada lagi sisa-sisa *legacy code* atau UI jadul yang mengganggu.

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
  - Mengelola entitas dari *API Master Data* (`GET/POST/PUT/DELETE /api/training-centers` dan `/api/trainings`).
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
- [x] Impelementasi `Global Exception Handler` dengan format Response JSON ketat untuk menangkal Error Leakage ke Frontend.
- [x] API Security: API diproteksi ketat menggunakan `Laravel Sanctum`.
- [x] Security Policy: Middleware kustom memfilter Hak Akses agar akun bertipe 'user' tidak bisa membaca endpoint maupun memanipulasi UI Admin, begitu pun sebaliknya.
- [x] Perbaikan *Error 403 HTTP Interceptor* di frontend yang sebelumnya menghapus token secara tidak sengaja, kini ditangani dengan metode yang aman.
- [x] **Pembersihan Final (Refactoring Phase):** Seluruh arsitektur lama (*Legacy Code*) SIREKPEL yang berupa Controller (`PendaftaranController`, `PelatihanController` duplikat, `PesertaController`, `RekomendasiController`), Model, dan Resource berbahasa Indonesia telah dihapus total. *Routing* diseragamkan ke bahasa Inggris (`/trainings`, `/enrollments`) demi *Clean Architecture*.

Project telah bersih dan siap di-*deploy* ke *production* untuk keperluan Sidang Skripsi.

---

## 6. Refactoring UI/UX Frontend & Landing Page (Selesai - Juli 2026)
Pada fase akhir pengembangan, tampilan di sisi *User Module*, Admin, dan *Landing Page* telah dirombak secara total oleh Tim Senior Engineer untuk mencapai standar visual *Modern SaaS / Enterprise App*. Refactoring ini meliputi:

1. **Landing Page Redesign (Agregator Concept):**
   - Merombak *copywriting* dan *Information Architecture* di `welcome.blade.php` agar mencerminkan "Mesin Rekomendasi Pintar / Agregator", bukan lagi LMS tertutup.
   - Sinkronisasi Data *Real-time*: Tabel Statistik dan "Kategori Minat Pelatihan" tidak lagi *hardcoded*, melainkan diagregasi langsung secara dinamis dari database Backend via `HomeController`.
   - Modifikasi CTA (Call to Action) dan UX Flow agar pengguna baru langsung dituntun untuk mendaftar akun dan mengisi kuesioner.

2. **Dashboard UI (User):**
   - Redesign tata letak (*layout*) card dari *fixed grid CSS* manual ke sistem kolom dan *row native* milik Bootstrap agar terjamin responsivitasnya (*mobile-first*).
   - Indikator persentase kelengkapan profil dibuat interaktif dengan custom progress bar.

3. **Kuesioner & Rekomendasi:**
   - Mengubah *Radio Buttons* standar menjadi komponen kartu interaktif yang bisa ditekan di seluruh permukaannya dengan perubahan warna *border* (state: *hover* dan *checked*).
   - Merombak hasil dari *Recommendation Engine* menjadi Card *Training Center* modern dengan medali ranking (Emas/Perak/Perunggu), badge presentase (*alpha transparency*), dan efek elevasi 3D.
   - Modal pendaftaran dibuat lebih organik (*mobile-friendly*) untuk menampilkan daftar modul studi di TC terkait.

4. **Status Pendaftaran:**
   - Tabel *history* digantikan oleh bentuk Timeline Card modern. Kartu ini memiliki pseudo-elemen bergaris warna cerdas (Biru = Aktif, Hijau = Selesai, Merah = Batal) agar terbaca jelas oleh mata sekilas. Format tanggal pun diubah menjadi format lokal (Hari, DD Bulan YYYY).

Fase refactoring UI ini ditujukan untuk memberikan kepuasan, kepercayaan, dan mengurangi hambatan UX (Friction) bagi calon siswa.

---

## 7. Refactoring UI/UX Admin Module (Selesai - Juli 2026)
Melanjutkan perombakan di sisi *User*, Tim Senior Engineer juga menyelaraskan gaya visual (*design language*) pada area *Admin Panel* (AdminLTE v4) agar lebih *modern*, *clean*, dan meminimalisir kelelahan visual (eye-strain) bagi Administrator. Fokus perubahan ini adalah:

1. **Global Admin Layout (\`admin.blade.php\`)**:
   - Menerapkan injeksi *CSS Variables* untuk mewarnai tombol dan elemen navigasi *sidebar* selaras dengan identitas aplikasi (Indigo).
   - Mengubah skema warna *sidebar* menjadi *Slate/Navy Dark* (\`#1e293b\`) agar lebih terkesan seperti panel *SaaS Enterprise*.
   - Menerapkan *white-labeling* dengan mengganti referensi *AdminLTE.io* di bagian footer.

2. **Dashboard Metrics (\`admin/Dashboard/index.blade.php\`)**:
   - Membuang komponen \`.small-box\` warna-warni yang terlalu mencolok dan menggantinya dengan \`Metric Card\` berwarna latar putih (shadow halus, hover efek 3D, border tipis).
   - Mengubah format Tabel Data Pendaftar Terbaru menjadi \`.table-modern\` (dengan latar \`thead\` abu-abu muda, serta tipografi kapital).

3. **Master Data & Bug Fixes (Training Center & Pelatihan)**:
   - Menerapkan konsistensi UI Tabel (mengubah tombol Edit/Delete konvensional menjadi \`Minimalist Action Button\` berlatar transparan).
   - Menata ulang Form Modal. Bagian input krusial (seperti Geolocation di Training Center dan Variabel Kalkulasi di Pelatihan) dibungkus (*highlight*) dengan panel *alert/card* khusus untuk memandu Administrator bahwa field tersebut mempengaruhi hasil *Recommendation Engine*.

4. **Refactoring Auth (Login Pages):**
   - Halaman Login Admin dan User dirombak ulang menggunakan desain *Modern Card* berbayang dengan latar belakang *gradient* (\`f8fafc\` ke \`e2e8f0\`).
   - Menerapkan komponen input *icon-group* yang dapat merespon *focus state*.
   - **Perbaikan UX Login:** Akun bertipe reguler (User) yang melakukan *login* atau *auto-login* (Sanctum) kini di-*redirect* secara paksa ke `/user/profile` ketimbang `/user/dashboard` untuk menjamin kepatuhan *onboarding* sistem kuesioner.

5. **Global SweetAlert2 Integration:**
   - Menghapus manipulasi DOM manual (\`alertBox\`) dan \`window.alert()\` konvensional di seluruh proyek.
   - Menginjeksi *SweetAlert2* via CDN di \`layouts/admin.blade.php\` dan \`layouts/user.blade.php\`.
   - Mengimplementasikan helper JS global: \`window.showToast()\` untuk notifikasi sukses (non-blocking) dan \`window.confirmAction()\` untuk konfirmasi penghapusan data dengan tampilan UI yang serasi (Rounded UI).

6. **Activity Log & Bulk Action:**
   - Menambahkan fitur *Checkbox (Select All/Individual)* pada tabel modul Log Activity.
   - Mengimplementasikan fitur *Bulk Delete* menggunakan arsitektur pemrosesan konkuren \`Promise.allSettled()\` di *Frontend*, sehingga admin dapat menghapus puluhan riwayat aktivitas sekaligus tanpa menyiksa memori browser.

---

## 8. Standarisasi Pengujian Otomatis (Automated Testing)
Sebagai bentuk jaminan kualitas perangkat lunak (*Quality Assurance*), Tim Senior Engineer telah merancang dan mengeksekusi serangkaian pengujian (*Automated Tests*) berbasis *PHPUnit* yang komprehensif, mencakup logika algoritma, alur aplikasi, otorisasi, dan uji regresi keamanan. Pada fase refactoring akhir, pengujian telah dikalibrasi ulang terhadap V2 API Endpoint.

**Daftar Pengujian (Test Coverage) yang telah dirancang:**

1. **Unit Tests (Core Logic):**
   - \`DistanceServiceTest\`: Memvalidasi keakuratan perhitungan jarak menggunakan rumus matematika *Haversine Formula*.
   - \`RecommendationEngineTest\`: Pengujian algoritma inti. Memastikan *Hard Filter* (Kategori/Metode) bekerja, memastikan perhitungan proporsi skor gabungan valid, dan menjaga konsistensi Top-5 limit pada database.

2. **Feature Tests (End-to-End API Integration):**
   - \`BackendFlowTest\`: Simulasi *User Journey* utama mulai dari Autentikasi, pengisian Profil, submit Kuesioner, hingga kemunculan data di endpoint rekomendasi.
   - \`AdminCrudTest\`: Uji coba modul Master Data. Memastikan fungsi CRUD pada *Training Center* dan *Pelatihan* (\`/api/trainings\`) bekerja. **Catatan Arsitektur:** Termasuk uji coba keamanan \`Cascade Delete\` untuk memverifikasi bahwa penghapusan TC akan menyapu bersih semua Pelatihan di bawahnya untuk menghindari data yatim (*Orphaned Data*).
   - \`EnrollmentLogicTest\`: Pengujian fungsionalitas transaksi. Terdapat validasi *Edge-case* yang memverifikasi bahwa sistem menolak (*HTTP 409 Conflict*) pendaftaran ganda dari satu user pada modul pelatihan yang sama.
   - \`SecurityAndLogTest\`: Pengujian ketat untuk keamanan akses (*Gate/Middleware*). Meliputi pengujian fitur Blokir Akun (*Ban User*), dimana *SystemAuth Middleware* akan segera menggagalkan akses user yang dinonaktifkan meski token JWT/Sanctum-nya masih berlaku. Termasuk validasi penulisan jejak otomatis ke dalam tabel *Log Activity*.

*Seluruh test suites (skenario uji) ini menjamin bahwa Sistem Rekomendasi Tempat Pelatihan bersifat robust, tangguh dari regresi (bug berulang), dan siap untuk fase production.*

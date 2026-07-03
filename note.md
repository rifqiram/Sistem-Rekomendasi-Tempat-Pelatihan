# Adaptasi Sistem Rekomendasi (Draft)

## 1. Entitas & Data Model
- Lama: `Peserta`, `Keahlian`, `Kategori`.
- Baru: `Profile` (demografi/kecamatan), `QuestionnaireResponse` (preferensi), `Training`, `Recommendation` (hasil komputasi DB), `LogActivity`.

## 2. Alur Pengguna (User Flow)
- Register/Login → Cek kelengkapan → Redirect `/user-profile` & `/questionnaire` (jika kosong) → Trigger `RecommendationEngine` → Tampil `/recommendations` atau dashboard.

## 3. Logika Rekomendasi (`RecommendationEngine`)
- **Phase 1 (Hard Filter):** Drop training mismatch (skill terlalu tinggi, lokasi strict, metode strict).
- **Phase 2 (Weighted Score):** Interest (35%) + Skill Match (15%) + Method Match (15%) + Priority (15%) + Location (10%) + Popularity (10%).
- **Phase 3 (Persist DB):** Flush data lama user, insert Top N ke tabel `recommendations` dgn field `score` & `rank`.

## 4. Rute & Endpoint API Baru
- `GET/POST /api/profile`
- `GET/POST /api/questionnaire`
- `GET /api/recommendations` (Buka tabel DB, bukan hitung ulang)
- `POST /api/log-activity`

## 5. Role/Aksesibilitas
- Admin: Master data (Training, Category), pantau metrics/log.
- User: Isi kuesioner, lihat rekomendasi, track klik/enroll (LogActivity).
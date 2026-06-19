# AGENTS.md

## Tujuan

Project ini mengadopsi teknikal SIREKPEL tanpa rombak total. Struktur lama (`mentor`, `peserta`, `pelatihan`, `pendaftaran`) tetap digunakan, lalu ditambah entitas teknikal rekomendasi:

- `kategori`
- `keahlian`
- `pelatihan_keahlian`
- `peserta_keahlian`
- endpoint rekomendasi

## Prinsip Implementasi

- API-only changes berada di `routes/api.php` dan controller terkait.
- Response API wajib memakai envelope:

```json
{
  "success": true,
  "message": "...",
  "data": {}
}
```

- Error response:

```json
{
  "success": false,
  "message": "...",
  "errors": {}
}
```

- Gunakan helper di `app/Http/Controllers/Controller.php`:
  - `successResponse()`
  - `errorResponse()`
  - `authorizeAdmin()`

## Autentikasi (Dual Auth Mode)

API menggunakan custom middleware `auth.sirekpel` yang mendukung dua metode login:
1. **Laravel Sanctum** (token dinamis, format `1|xyz...`). Ideal untuk frontend web.
2. **API Token / API Key Statis** (membaca kolom `api_token` di table `tabel_users`). Ideal untuk pengujian langsung di Postman atau integrasi luar. Token ini bisa dikirim via:
   - Header `Authorization: Bearer <token>`
   - Header `X-API-Key: <token>`
   - Query parameter `?api_token=<token>`

### Akun Demo Default (Seeded)
- **Role Admin**:
  - Email: `admin@example.com`
  - Password: `password`
  - Static API Key: `admintoken` (bisa langsung digunakan di Postman!)

## Role

- `admin`: kelola master data dan delete/update data penting.
- `user`: dipakai sebagai pencari kerja/peserta secara kompatibel dengan struktur lama.

## Entity SIREKPEL Tambahan

Tabel tambahan:

- `tabel_kategori`
- `tabel_keahlian`
- `tabel_pelatihan_keahlian`
- `tabel_peserta_keahlian`

Model tambahan:

- `App\Models\Kategori`
- `App\Models\Keahlian`

Controller tambahan:

- `KategoriController`
- `KeahlianController`
- `ProfilKeahlianController`
- `RekomendasiController`

## Endpoint Penting

```txt
POST   /api/login
POST   /api/register
GET    /api/me
POST   /api/logout
GET    /api/kategori
POST   /api/kategori
GET    /api/keahlian
POST   /api/keahlian
GET    /api/pelatihan
POST   /api/pelatihan
GET    /api/peserta/{peserta}/keahlian
PUT    /api/peserta/{peserta}/keahlian
GET    /api/rekomendasi?peserta_id=1
POST   /api/pelatihan/{pelatihan}/pendaftaran
GET    /api/peserta/{peserta}/riwayat
```

## Rekomendasi Logic

`RekomendasiController@index`:

- input: `peserta_id`
- ambil keahlian peserta
- ambil pelatihan aktif
- exclude pelatihan yang sudah didaftari peserta
- hitung `matched_skills`, `missing_skills`, `match_count`, `gap_count`, `score`
- sort by `score` descending

## File Artefak

- Postman Collection: `SIREKPEL_Collection.json` dan `public/docs/My Collection.postman_collection.json` (keduanya identik dan memiliki default token `admintoken`)
- Dokumentasi markdown: `SIREKPEL_Dokumentasi_Baru.md`
- Plan awal: `C:\Users\user\.claude\plans\atomic-orbiting-rain.md`

## Verifikasi Wajib Setelah Perubahan

```bash
php artisan route:list --path=api
php artisan migrate
php artisan test
```

Status implementasi terakhir:
- Seluruh 10 test suite (40 assertions) passed dengan sukses!

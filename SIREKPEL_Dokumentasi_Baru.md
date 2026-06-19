# Dokumentasi API SIREKPEL

## Ringkasan

Project ini mengadopsi teknikal SIREKPEL tanpa rombak total. Struktur lama (`mentor`, `peserta`, `pelatihan`, `pendaftaran`) tetap digunakan, lalu ditambah entitas teknikal rekomendasi:

- `kategori`
- `keahlian`
- `pelatihan_keahlian`
- `peserta_keahlian`
- endpoint rekomendasi

Base URL lokal:

```txt
http://127.0.0.1:8000/api
```

Semua response memakai format:

```json
{
  "success": true,
  "message": "...",
  "data": {}
}
```

Error memakai format:

```json
{
  "success": false,
  "message": "...",
  "errors": {}
}
```

## Autentikasi

API memakai Laravel Sanctum.

Header untuk endpoint protected:

```txt
Authorization: Bearer <token>
Accept: application/json
```

### Register

`POST /register`

```json
{
  "name": "User SIREKPEL",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login

`POST /login`

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

Token tersedia di `data.token`.

### Me

`GET /me`

### Logout

`POST /logout`

## Kategori

- `GET /kategori`
- `POST /kategori` admin
- `GET /kategori/{id}`
- `PUT/PATCH /kategori/{id}` admin
- `DELETE /kategori/{id}` admin

Body create/update:

```json
{
  "nama": "Teknologi",
  "deskripsi": "Kategori teknologi"
}
```

## Keahlian

- `GET /keahlian`
- `POST /keahlian` admin
- `GET /keahlian/{id}`
- `PUT/PATCH /keahlian/{id}` admin
- `DELETE /keahlian/{id}` admin

Body create/update:

```json
{
  "kategori_id": 1,
  "nama": "Laravel",
  "deskripsi": "Framework PHP"
}
```

## Pelatihan

- `GET /pelatihan`
- `POST /pelatihan` admin
- `GET /pelatihan/{id}`
- `PUT/PATCH /pelatihan/{id}` admin
- `DELETE /pelatihan/{id}` admin

Body create:

```json
{
  "judul": "Pelatihan Laravel",
  "deskripsi": "Belajar Laravel API",
  "kategori": "Teknologi",
  "level": "Pemula",
  "durasi": "3 Hari",
  "sertifikat": "Ya",
  "mentor_id": 1,
  "tanggal_mulai": "2026-07-01",
  "tanggal_selesai": "2026-07-03",
  "is_active": true,
  "keahlian_ids": [1, 2]
}
```

## Peserta

- `GET /peserta` admin
- `POST /peserta` admin
- `GET /peserta/{id}`
- `PUT/PATCH /peserta/{id}`
- `DELETE /peserta/{id}` admin

Body create:

```json
{
  "nama": "Peserta Satu",
  "email": "peserta@example.com",
  "telepon": "08123456789",
  "keahlian": "PHP"
}
```

## Profil Keahlian Peserta

### Lihat keahlian peserta

`GET /peserta/{peserta}/keahlian`

### Set keahlian peserta

`PUT /peserta/{peserta}/keahlian`

```json
{
  "keahlian": [
    { "id": 1, "level": "Dasar" },
    { "id": 2, "level": "Menengah" }
  ]
}
```

## Pendaftaran

- `GET /pendaftaran` admin
- `POST /pendaftaran` admin/manual
- `GET /pendaftaran/{id}` admin
- `PUT/PATCH /pendaftaran/{id}` admin
- `DELETE /pendaftaran/{id}` admin

Body create:

```json
{
  "peserta_id": 1,
  "pelatihan_id": 1,
  "tanggal_daftar": "2026-06-20",
  "status": "terdaftar"
}
```

`tanggal_daftar` dan `status` opsional. Default:

- `tanggal_daftar = now()`
- `status = terdaftar`

### Daftar ke pelatihan

`POST /pelatihan/{pelatihan}/pendaftaran`

```json
{
  "peserta_id": 1
}
```

Endpoint ini menolak duplicate peserta-pelatihan.

## Riwayat Peserta

`GET /peserta/{peserta}/riwayat`

Mengembalikan daftar pendaftaran/pelatihan peserta.

## Rekomendasi

`GET /rekomendasi?peserta_id=1`

Logic:

1. Ambil keahlian peserta dari `tabel_peserta_keahlian`.
2. Ambil pelatihan aktif dari `tabel_pelatihan`.
3. Cocokkan dengan `tabel_pelatihan_keahlian`.
4. Exclude pelatihan yang sudah didaftari peserta.
5. Hitung:
   - `matched_skills`
   - `missing_skills`
   - `match_count`
   - `gap_count`
   - `score`

Contoh response item:

```json
{
  "pelatihan": {},
  "matched_skills": ["Laravel"],
  "missing_skills": ["REST API"],
  "match_count": 1,
  "gap_count": 1,
  "score": 50
}
```

## Verifikasi Lokal

```bash
composer install
php artisan migrate
php artisan route:list --path=api
php artisan test
```

Status terakhir: test suite pass.

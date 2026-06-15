<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

ini loh ya

# 🚀 Uts-RekomendasiPelatihan

Sistem manajemen pelatihan berbasis Laravel yang mengelola data mentor, kelas, dan proses pendaftaran peserta secara terstruktur.

---

## 📌 Karakteristik Sistem

* **⚙️ Arsitektur:** Menggunakan MVC & Eloquent ORM dengan relasi tabel pivot antara Peserta dan Pelatihan.
* **🔒 Keamanan:** Proteksi hak akses Admin/User via *middleware* otorisasi dan validasi input yang ketat.
* **🧩 Fleksibilitas:** Menyediakan API Controller & Resource untuk kemudahan integrasi dengan platform lain.

---

## 🌐 Dokumentasi API

Aplikasi ini telah menyediakan RESTful API. Kamu bisa melihat dokumentasi lengkapnya melalui tautan di bawah ini:

* 📄 [Lihat Dokumentasi API (PDF)](./public/docs/api-documentation.pdf)
* 📄 [Dokumentasi Sistem](public/docs/dokumentasi-sistem-pelatihanIT.pdf)
* 🚀 [Postman Collection (JSON)](./public/docs/My%20Collection.postman_collection.json)

---

## 🛠️ Langkah Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Clone Repositori

https://github.com/rifqiram/Uts-RekomendasiPelatihan.git

---

## 📂 Struktur Sistem

Beberapa struktur utama pada project:

```txt
app/            -> Logic aplikasi (Controller, Model, Middleware)
config/         -> Konfigurasi aplikasi
database/       -> Migration, Seeder, Factory
public/         -> Asset publik dan dokumentasi API
resources/      -> View, CSS, JS
routes/         -> Routing web dan API
storage/        -> Penyimpanan file sistem
tests/          -> Pengujian aplikasi
```

---

## 🚀 Instalasi Project

Ikuti langkah berikut untuk menjalankan project di komputer lokal.

### 1. Clone Repository

```bash
git clone https://github.com/rifqiram/Uts-RekomendasiPelatihan.git
```

---

### 2. Install Dependency

Pastikan **Composer** telah terinstal, lalu jalankan:

```bash
composer install
```

Jika project menggunakan frontend asset (Vite/NPM):

```bash
npm install
```

---

### 3. Setup Environment

Salin file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Kemudian buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_DATABASE=uts_rekomendasi_pelatihan
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Migrasi Database dan Seeder

Pastikan MySQL aktif, kemudian jalankan:

```bash
php artisan migrate --seed
```

Perintah ini akan:

* Membuat seluruh tabel database
* Menjalankan relasi antar tabel
* Mengisi data awal (seed)

---

### 5. Jalankan Server Laravel

```bash
php artisan serve
```

Aplikasi dapat diakses pada:

```txt
http://127.0.0.1:8000
```

---

## 🔍 API Endpoint

Contoh endpoint API:

```txt
GET    /api/pelatihan
POST   /api/pelatihan
GET    /api/peserta
POST   /api/pendaftaran
```


---

## 🧪 Testing

Menjalankan pengujian Laravel:

```bash
php artisan test
```

atau

```bash
php artisan test --filter NamaTest
```

---

## 👨‍💻 Developer

**Rifqi Ramadhan** |
**6B** |
**2305101030** |
Project UTS Pemrograman Web Laravel

---

## 📝 License

Project ini bersifat **open-source** dan menggunakan lisensi **MIT License**.

```
```

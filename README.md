# RekrutPro

````md

> Make Recruit Process Look Easy# RekrutPro



RekrutPro adalah aplikasi web berbasis **Laravel 12** untuk membantu tim HR dan rekruter dalam mengelola proses rekrutmen secara terstruktur — mulai dari pembukaan lowongan, pengelolaan kandidat, penjadwalan interview, hingga penawaran kerja.> Make Recruit Process Look Easy



<div align="center">RekrutPro adalah aplikasi web berbasis Laravel untuk membantu tim HR dan rekruter dalam mengelola proses rekrutmen secara lebih terstruktur, mulai dari pembukaan lowongan, pengelolaan kandidat, hingga proses interview.



[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square&logo=laravel)](https://laravel.com)Repo ini dibuat sebagai project pembelajaran/pengembangan sistem rekrutmen dengan fokus pada praktik _clean code_, _testing_, dan dokumentasi.

[![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)](https://php.net)

[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.x-38bdf8?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)---



</div>## ✨ Fitur Utama (High Level)



---RekrutPro menyediakan fitur lengkap untuk mengelola seluruh proses rekrutmen:



## ✨ Fitur Utama### 🎯 Manajemen Lowongan

- Membuat dan mengelola posisi/jabatan yang dibuka

### 🎯 Manajemen Lowongan- Mengatur deskripsi pekerjaan, kualifikasi, dan requirements

- Membuat dan mengelola posisi/jabatan yang dibuka- Publikasi lowongan dengan status aktif/tidak aktif

- Mengatur deskripsi pekerjaan, kualifikasi, dan requirements- Filter lowongan berdasarkan divisi, lokasi, dan tipe kontrak

- Publikasi lowongan dengan status aktif/tidak aktif

- Filter lowongan berdasarkan divisi, lokasi, dan tipe kontrak### 👥 Manajemen Kandidat

- Registrasi dan profil kandidat lengkap

### 👥 Manajemen Kandidat- Upload CV dan dokumen pendukung

- Registrasi dan profil kandidat lengkap- Apply lamaran ke lowongan yang tersedia

- Upload CV dan dokumen pendukung- Tracking status aplikasi real-time

- Apply lamaran ke lowongan yang tersedia

- Tracking status aplikasi real-time### 📋 Proses Rekrutmen

- **Screening**: Review aplikasi kandidat oleh HR

### 📋 Alur Proses Rekrutmen- **Interview**: Penjadwalan dan feedback interview (multi-tahap)

| Tahap | Deskripsi |- **Assessment**: Evaluasi kandidat dengan scoring system

|-------|-----------|- **Offering**: Penawaran kerja dengan sistem negosiasi gaji

| **Screening** | Review aplikasi kandidat oleh HR |- **Hiring**: Finalisasi kandidat yang diterima

| **Interview** | Penjadwalan dan feedback interview multi-tahap |

| **Assessment** | Evaluasi kandidat dengan scoring system |### 💼 Sistem Offer Management (NEW! ✨)

| **Offering** | Penawaran kerja dengan sistem negosiasi gaji |- **Kandidat dapat**:

| **Hiring** | Finalisasi kandidat yang diterima |  - ✅ Menerima penawaran kerja

  - ❌ Menolak penawaran dengan alasan

### 💼 Offer Management & Negosiasi  - 💬 Mengajukan negosiasi gaji dengan alasan detail

- Kandidat dapat **menerima**, **menolak**, atau **mengajukan negosiasi gaji**- **HR dapat**:

- HR dapat **mengedit penawaran**, **approve/reject negosiasi**  - ✏️ Edit penawaran yang sudah dibuat (posisi, gaji, benefits, dll)

- Riwayat negosiasi lengkap dengan timeline  - ✅ Approve negosiasi kandidat (gaji otomatis terupdate)

  - ❌ Reject negosiasi dengan catatan

### 📊 Dashboard & Audit- **Tracking**: Riwayat negosiasi lengkap dengan timeline

- Dashboard HR untuk monitoring rekrutmen

- Dashboard Kandidat untuk tracking aplikasi### 📊 Dashboard & Reporting

- Audit log untuk semua aktivitas penting- Dashboard HR untuk monitoring rekrutmen

- Dashboard Kandidat untuk tracking aplikasi

### 🔐 Role & Permission- Statistik dan metrik proses rekrutmen

| Role | Akses |- Audit log untuk semua aktivitas penting

|------|-------|

| **SuperAdmin** | Full access ke seluruh sistem & konfigurasi |### 🔐 Role & Permission Management

| **HR** | Manage lowongan, review kandidat, manage offers |- **Admin**: Full access ke seluruh sistem

| **Interviewer** | Akses interview & feedback |- **HR**: Manage lowongan, review kandidat, manage offers

| **Candidate** | Apply lowongan, respond offers, track status |- **Interviewer**: Akses ke interview & feedback

- **Candidate**: Apply lowongan, respond offers, track status

---

> 📚 **Dokumentasi Lengkap**:

## 🛠 Tech Stack> - `DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md` - Dokumentasi sistem lengkap (5776+ baris)

> - `DOKUMENTASI_UPDATE_OFFER_MANAGEMENT.md` - Update fitur Offer Management terbaru

### Backend> - `README_SISTEM_REKRUTMEN.md` - Overview sistem rekrutmen

- **PHP 8.2+**> - `INTERVIEWER_FEATURES_COMPLETED.md` - Fitur interviewer

- **Laravel 12.x**> - `TESTING_GUIDE.md` - Panduan testing

- **MySQL / MariaDB**> - `CHANGELOG.md` - Riwayat perubahan

- **Eloquent ORM**

---

### Frontend

- **Blade** — Templating engine## 🛠 Tech Stack

- **Tailwind CSS 3.x** — Utility-first CSS framework

- **Alpine.js 3.x** — Lightweight JavaScript frameworkProyek ini dibangun menggunakan:



### Build Tools### Backend

- **Vite** — Frontend bundler- **PHP 8.2+** 

- **Composer** — PHP dependency manager- **Laravel 11.x** - Framework utama

- **npm** — Node package manager- **MySQL / MariaDB** - Database relasional

- **Eloquent ORM** - Database abstraction

### Dev Packages

- **Laravel Breeze** — Authentication scaffolding### Frontend

- **Laravel Pint** — Code style fixer- **Blade** - Templating engine

- **Laravel Sail** — Docker environment (opsional)- **Tailwind CSS** - Utility-first CSS framework

- **PHPUnit 11** — Testing framework- **Alpine.js** - Lightweight JavaScript framework

- **Font Awesome** - Icon library

---

### Build Tools & Development

## 📦 Prasyarat- **Vite** - Frontend bundler (lihat `vite.config.js`)

- **Composer** - PHP dependency manager

Pastikan sudah menginstall:- **npm** - Node package manager

- **Laravel Sail** (optional) - Docker development environment

- **PHP 8.2+** (dengan ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo)

- **Composer** (latest)### Key Laravel Packages

- **Node.js 18+ & npm**- **Laravel Sanctum** - API authentication

- **MySQL 8.0+ / MariaDB 10.3+**- **Laravel Pint** - Code style fixer

- **Git**- **PHPUnit** - Testing framework



---Detail versi dependency lengkap:

- `composer.json` - PHP dependencies

## 🚀 Instalasi & Menjalankan Secara Lokal- `package.json` - JavaScript dependencies



### 1. Clone repository---



```bash## 📦 Prasyarat

git clone https://github.com/ghilmanfz/rekrutpro.git

cd rekrutproSebelum menjalankan proyek ini, pastikan sudah menginstall:

```

- **PHP 8.2 atau lebih baru**

### 2. Copy file environment- **Composer** (latest version)

- **Node.js 18+ & npm**

```bash- **MySQL 8.0+ / MariaDB 10.3+**

cp .env.example .env- **Git** (untuk clone repository)

```- **Web Server** (Apache/Nginx) atau gunakan built-in PHP server



### 3. Konfigurasi database di `.env`### Ekstensi PHP yang Diperlukan:

- OpenSSL

```env- PDO

DB_CONNECTION=mysql- Mbstring

DB_HOST=127.0.0.1- Tokenizer

DB_PORT=3306- XML

DB_DATABASE=rekrutpro- Ctype

DB_USERNAME=root- JSON

DB_PASSWORD=- BCMath

```- Fileinfo



### 4. Install dependencies---



```bash## 🚀 Cara Menjalankan Proyek Secara Lokal

composer install

npm install1. **Clone repository**

```

   ```bash

### 5. Generate application key   git clone https://github.com/ghilmanfz/rekrutpro.git

   cd rekrutpro

```bash````

php artisan key:generate

```2. **Copy file environment**



### 6. Jalankan migrasi & seeder   ```bash

   cp .env.example .env

```bash   ```

php artisan migrate --seed

```3. **Atur konfigurasi database di `.env`**



> ⚠️ Gunakan `php artisan migrate:fresh --seed` untuk reset database dari awal (semua data terhapus).   Sesuaikan dengan konfigurasi lokal kamu:



### 7. Build frontend assets   ```env

   DB_CONNECTION=mysql

```bash   DB_HOST=127.0.0.1

# Development (dengan hot reload)   DB_PORT=3306

npm run dev   DB_DATABASE=rekrutpro

   DB_USERNAME=root

# Production build   DB_PASSWORD=

npm run build   ```

```

4. **Install dependency backend (Composer)**

### 8. Jalankan server

   ```bash

```bash   composer install

php artisan serve   ```

```

5. **Generate application key**

Akses aplikasi di: **http://localhost:8000**

   ```bash

---   php artisan key:generate

   ```

### 🎭 Default Akun (setelah seeder)

6. **Jalankan migrasi (dan seeder jika tersedia)**

| Role | Email | Password |

|------|-------|----------|   ```bash

| SuperAdmin | `superadmin@rekrutpro.com` | `password` |   php artisan migrate

| HR | `hr@rekrutpro.com` | `password` |   ```

| Interviewer | `interviewer@rekrutpro.com` | `password` |

| Candidate | `candidate@rekrutpro.com` | `password` |   Untuk generate data dummy (optional):



> 💡 Ubah password setelah login pertama kali!   ```bash

   php artisan db:seed

---   ```



## 🧪 Testing   Atau jalankan sekaligus:



```bash   ```bash

# Jalankan semua tests   php artisan migrate:fresh --seed

php artisan test   ```



# Test spesifik   > ⚠️ **Warning**: `migrate:fresh` akan menghapus semua data yang ada!

php artisan test --filter=NamaTest

7. **Install dependency frontend (npm)**

# Dengan coverage report

php artisan test --coverage   ```bash

```   npm install

   ```

Struktur test:

- `tests/Feature/` — Feature tests (HTTP, Database, Integration)8. **Jalankan build/dev frontend**

- `tests/Unit/` — Unit tests (Model, Service, Helper)

   Untuk development:

---

   ```bash

## 🗂 Struktur Folder   npm run dev

   ```

```

rekrutpro/   Untuk production build:

├── app/

│   ├── Http/Controllers/   ```bash

│   │   ├── Auth/           # Autentikasi   npm run build

│   │   ├── SuperAdmin/     # SuperAdmin controllers   ```

│   │   ├── HR/             # HR controllers

│   │   ├── Interviewer/    # Interviewer controllers9. **Jalankan server Laravel**

│   │   └── Candidate/      # Candidate controllers

│   ├── Models/             # Eloquent models   ```bash

│   ├── Services/           # Business logic services   php artisan serve

│   └── Providers/          # Service providers   ```

├── database/

│   ├── migrations/         # Database migrations10. **Akses aplikasi**

│   ├── seeders/            # Database seeders

│   └── factories/          # Model factories    Buka di browser:

├── resources/

│   ├── views/              # Blade templates    ```text

│   │   ├── admin/    http://localhost:8000

│   │   ├── hr/    ```

│   │   ├── interviewer/

│   │   └── candidate/### 🎭 Default Users (jika menggunakan seeder)

│   ├── css/

│   └── js/Jika kamu menjalankan seeder, berikut adalah user default yang bisa digunakan untuk login:

├── routes/

│   └── web.php#### Admin

└── tests/- Email: `admin@rekrutpro.com`

    ├── Feature/- Password: `password`

    └── Unit/

```#### HR

- Email: `hr@rekrutpro.com`

---- Password: `password`



## 🤝 Kontribusi#### Interviewer

- Email: `interviewer@rekrutpro.com`

1. **Fork** repository ini- Password: `password`

2. Buat branch baru:

   ```bash#### Candidate

   git checkout -b feature/nama-fitur- Email: `candidate@rekrutpro.com`

   ```- Password: `password`

3. Commit dengan pesan yang jelas:

   ```bash> 💡 **Note**: Ubah password default setelah login pertama kali untuk keamanan!

   git commit -m "feat: menambahkan fitur X"

   ```---

   > Format: `feat:` | `fix:` | `docs:` | `refactor:` | `test:`

4. Push dan buat **Pull Request** ke branch `main`## 🧪 Testing



---RekrutPro dilengkapi dengan test suite untuk memastikan kualitas code.



## 🐛 Bug Report & Feature Request### Menjalankan Tests



Buat [Issue baru](https://github.com/ghilmanfz/rekrutpro/issues) dengan menyertakan:```bash

- Deskripsi masalah/fitur# Menjalankan semua tests

- Steps to reproduce (untuk bug)php artisan test

- Expected vs Actual behavior

- Environment (PHP version, OS, dll)# atau menggunakan PHPUnit langsung

./vendor/bin/phpunit

---

# Menjalankan test spesifik

## 🚀 Roadmapphp artisan test --filter=UserTest



### ✅ Selesai# Dengan coverage report

- [x] Authentication & role-based authorizationphp artisan test --coverage

- [x] Job posting management```

- [x] Application tracking

- [x] Interview scheduling & feedback### Test Structure

- [x] Offer management + negosiasi gaji

- [x] Audit logging- `tests/Feature/` - Feature tests (HTTP, Database, Integration)

- [x] Dashboard & reporting- `tests/Unit/` - Unit tests (Model, Helper, Service)



### 📋 Planned### Dokumentasi Testing Lengkap

- [ ] Email notifications

- [ ] Advanced search & filtersPanduan detail tersedia di **`TESTING_GUIDE.md`**, termasuk:

- [ ] Export to PDF/Excel- Cara menulis test

- [ ] Mobile responsive optimization- Best practices

- [ ] Multi-language support- Mocking & Factories

- [ ] Advanced analytics & reporting- Database testing



------



## 📧 Kontak## 📂 Struktur & Dokumentasi Tambahan



- **GitHub**: [@ghilmanfz](https://github.com/ghilmanfz)RekrutPro dilengkapi dengan dokumentasi lengkap untuk memudahkan development:

- **Issues**: [github.com/ghilmanfz/rekrutpro/issues](https://github.com/ghilmanfz/rekrutpro/issues)

### 📄 Dokumentasi Utama

---- **`DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md`** ⭐ - Dokumentasi sistem lengkap (5776+ baris)

  - Database schema & ERD

## 📜 License  - Alur bisnis proses

  - API endpoints

Project ini dibuat untuk tujuan pembelajaran dan pengembangan. Silakan digunakan dan dimodifikasi sesuai kebutuhan.  - Controller & Model documentation

  - Troubleshooting guide

---

- **`DOKUMENTASI_UPDATE_OFFER_MANAGEMENT.md`** 🆕 - Update terbaru sistem Offer Management

## 📸 Screenshots  - Fitur negosiasi gaji

  - HR edit offer functionality

<img width="1883" alt="Dashboard HR" src="https://github.com/user-attachments/assets/2d811d76-fb8c-4945-b415-9e2b90672b89" />  - Testing scenarios

  - Code examples

<img width="1905" alt="Job Posting" src="https://github.com/user-attachments/assets/05337634-8576-4118-abaf-349a123570b2" />

### 📋 Dokumentasi Fitur

<img width="1885" alt="Kandidat Management" src="https://github.com/user-attachments/assets/689ae0d9-6334-4508-85fb-706c16804c10" />- **`README_SISTEM_REKRUTMEN.md`** - Overview sistem rekrutmen

- **`INTERVIEWER_FEATURES_COMPLETED.md`** - Fitur-fitur interviewer

<img width="1886" alt="Proses Interview" src="https://github.com/user-attachments/assets/f87d8043-7c53-4161-8e82-31042d6eaeef" />- **`TESTING_GUIDE.md`** - Panduan testing lengkap



<img width="1755" alt="Offer Management" src="https://github.com/user-attachments/assets/66eb81e0-66bb-4933-8f63-9022a5ff65b8" />### 📊 Progress & History

- **`CHANGELOG.md`** - Catatan perubahan versi

<img width="1919" alt="Dashboard Kandidat" src="https://github.com/user-attachments/assets/31abf65b-cfa7-459d-b011-8703684da8a8" />- **`PROGRESS_SUMMARY.md`** - Ringkasan progres pengembangan

- **`SESSION_2_SUMMARY.md`** - Ringkasan sesi pengembangan

<img width="1755" alt="Assessment" src="https://github.com/user-attachments/assets/5978a39a-75f7-42ac-8440-411e6fa234a7" />

### 🗂 Struktur Folder Penting

<img width="1880" alt="Aplikasi Lamaran" src="https://github.com/user-attachments/assets/306032ac-fe3e-4b6a-929a-d0a8f5a7944b" />

```

<img width="1889" alt="Profile Kandidat" src="https://github.com/user-attachments/assets/50041f76-899c-495c-9827-588cf75051be" />rekrutpro/

├── app/

<img width="1755" alt="Negosiasi Gaji" src="https://github.com/user-attachments/assets/4ec55341-9128-452e-b0b3-a95ebb947f90" />│   ├── Http/Controllers/

│   │   ├── Admin/          # Admin controllers

<img width="1884" alt="Audit Log" src="https://github.com/user-attachments/assets/cd5e8b9a-7136-427e-a08c-35f67b969dd2" />│   │   ├── HR/             # HR controllers

│   │   ├── Interviewer/    # Interviewer controllers

<img width="1911" alt="SuperAdmin Panel" src="https://github.com/user-attachments/assets/6af6c46d-afcd-4334-a9f3-52dfe5954a39" />│   │   └── Candidate/      # Candidate controllers

│   ├── Models/             # Eloquent models

---│   └── Providers/          # Service providers

├── database/

<div align="center">│   ├── migrations/         # Database migrations

│   ├── seeders/            # Database seeders

**Made with ❤️ using Laravel**│   └── factories/          # Model factories

├── resources/

⭐ Star this repo if you find it helpful!│   ├── views/              # Blade templates

│   │   ├── admin/

</div>│   │   ├── hr/

│   │   ├── interviewer/
│   │   └── candidate/
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── tests/
    ├── Feature/            # Feature tests
    └── Unit/               # Unit tests
```

---

## 🤝 Kontribusi

Project ini masih dalam tahap pengembangan aktif. Kontribusi sangat diterima!

### Cara Berkontribusi

1. **Fork** repository ini
2. **Buat branch** baru untuk fitur/bugfix kamu:

   ```bash
   git checkout -b feature/nama-fitur
   ```

3. **Commit** perubahan kamu dengan pesan yang jelas:

   ```bash
   git commit -m "feat: Menambahkan fitur X"
   ```

   Format commit message yang direkomendasikan:
   - `feat:` untuk fitur baru
   - `fix:` untuk bugfix
   - `docs:` untuk perubahan dokumentasi
   - `refactor:` untuk refactoring code
   - `test:` untuk menambahkan tests

4. **Push** ke branch kamu:

   ```bash
   git push origin feature/nama-fitur
   ```

5. **Buat Pull Request** ke branch `main` dengan deskripsi lengkap

### Guidelines

- Ikuti coding standard Laravel (PSR-12)
- Tulis tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Pastikan semua tests passing sebelum PR

---

## 🐛 Bug Reports & Feature Requests

Jika menemukan bug atau ingin request fitur:

1. Cek [Issues](https://github.com/ghilmanfz/rekrutpro/issues) apakah sudah dilaporkan
2. Jika belum, buat Issue baru dengan detail:
   - Deskripsi masalah/fitur
   - Steps to reproduce (untuk bug)
   - Expected vs Actual behavior
   - Screenshots (jika perlu)
   - Environment (PHP version, Laravel version, dll)

---

## 📧 Kontak & Support

### Developer
- **GitHub**: [@ghilmanfz](https://github.com/ghilmanfz)
- **Repository**: [rekrutpro](https://github.com/ghilmanfz/rekrutpro)

### Need Help?
- 📖 Baca dokumentasi lengkap di folder root
- 🐛 Report bug via [GitHub Issues](https://github.com/ghilmanfz/rekrutpro/issues)
- 💬 Diskusi di [GitHub Discussions](https://github.com/ghilmanfz/rekrutpro/discussions)

---

## 📜 License

Project ini dibuat untuk tujuan pembelajaran dan pengembangan. 

---

## 🙏 Acknowledgments

Terima kasih kepada:
- Laravel Framework Team
- Tailwind CSS Team
- Open source community

---

## 🚀 Roadmap

### ✅ Completed
- [x] User authentication & authorization
- [x] Job posting management
- [x] Application tracking system
- [x] Interview scheduling & feedback
- [x] Offer management with negotiation
- [x] Audit logging
- [x] Dashboard & reporting

### 🔄 In Progress
- [ ] Email notifications
- [ ] Advanced search & filters
- [ ] Bulk operations
- [ ] API documentation

### 📋 Planned
- [ ] Candidate assessment scoring
- [ ] Interview video call integration
- [ ] Mobile responsive optimization
- [ ] Export to PDF/Excel
- [ ] Multi-language support
- [ ] Advanced analytics

---

<div align="center">

**Made with ❤️ using Laravel**

⭐ Star this repo if you find it helpful!

</div>


<img width="1883" height="914" alt="Image" src="https://github.com/user-attachments/assets/2d811d76-fb8c-4945-b415-9e2b90672b89" />

<img width="1905" height="916" alt="Image" src="https://github.com/user-attachments/assets/05337634-8576-4118-abaf-349a123570b2" />

<img width="1885" height="915" alt="Image" src="https://github.com/user-attachments/assets/689ae0d9-6334-4508-85fb-706c16804c10" />

<img width="1886" height="918" alt="Image" src="https://github.com/user-attachments/assets/f87d8043-7c53-4161-8e82-31042d6eaeef" />

<img width="1755" height="1991" alt="Image" src="https://github.com/user-attachments/assets/66eb81e0-66bb-4933-8f63-9022a5ff65b8" />

<img width="1919" height="1013" alt="Image" src="https://github.com/user-attachments/assets/31abf65b-cfa7-459d-b011-8703684da8a8" />

<img width="1755" height="1264" alt="Image" src="https://github.com/user-attachments/assets/5978a39a-75f7-42ac-8440-411e6fa234a7" />

<img width="1880" height="918" alt="Image" src="https://github.com/user-attachments/assets/306032ac-fe3e-4b6a-929a-d0a8f5a7944b" />

<img width="1889" height="919" alt="Image" src="https://github.com/user-attachments/assets/50041f76-899c-495c-9827-588cf75051be" />

<img width="1755" height="1975" alt="Image" src="https://github.com/user-attachments/assets/4ec55341-9128-452e-b0b3-a95ebb947f90" />

<img width="1884" height="1409" alt="Image" src="https://github.com/user-attachments/assets/cd5e8b9a-7136-427e-a08c-35f67b969dd2" />

<img width="1911" height="917" alt="Image" src="https://github.com/user-attachments/assets/6af6c46d-afcd-4334-a9f3-52dfe5954a39" />




```

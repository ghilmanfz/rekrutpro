
````md
# RekrutPro

> Make Recruit Process Look Easy

RekrutPro adalah aplikasi web berbasis Laravel untuk membantu tim HR dan rekruter dalam mengelola proses rekrutmen secara lebih terstruktur, mulai dari pembukaan lowongan, pengelolaan kandidat, hingga proses interview.

Repo ini dibuat sebagai project pembelajaran/pengembangan sistem rekrutmen dengan fokus pada praktik _clean code_, _testing_, dan dokumentasi.

---

## ✨ Fitur Utama (High Level)

RekrutPro menyediakan fitur lengkap untuk mengelola seluruh proses rekrutmen:

### 🎯 Manajemen Lowongan
- Membuat dan mengelola posisi/jabatan yang dibuka
- Mengatur deskripsi pekerjaan, kualifikasi, dan requirements
- Publikasi lowongan dengan status aktif/tidak aktif
- Filter lowongan berdasarkan divisi, lokasi, dan tipe kontrak

### 👥 Manajemen Kandidat
- Registrasi dan profil kandidat lengkap
- Upload CV dan dokumen pendukung
- Apply lamaran ke lowongan yang tersedia
- Tracking status aplikasi real-time

### 📋 Proses Rekrutmen
- **Screening**: Review aplikasi kandidat oleh HR
- **Interview**: Penjadwalan dan feedback interview (multi-tahap)
- **Assessment**: Evaluasi kandidat dengan scoring system
- **Offering**: Penawaran kerja dengan sistem negosiasi gaji
- **Hiring**: Finalisasi kandidat yang diterima

### 💼 Sistem Offer Management (NEW! ✨)
- **Kandidat dapat**:
  - ✅ Menerima penawaran kerja
  - ❌ Menolak penawaran dengan alasan
  - 💬 Mengajukan negosiasi gaji dengan alasan detail
- **HR dapat**:
  - ✏️ Edit penawaran yang sudah dibuat (posisi, gaji, benefits, dll)
  - ✅ Approve negosiasi kandidat (gaji otomatis terupdate)
  - ❌ Reject negosiasi dengan catatan
- **Tracking**: Riwayat negosiasi lengkap dengan timeline

### 📊 Dashboard & Reporting
- Dashboard HR untuk monitoring rekrutmen
- Dashboard Kandidat untuk tracking aplikasi
- Statistik dan metrik proses rekrutmen
- Audit log untuk semua aktivitas penting

### 🔐 Role & Permission Management
- **Admin**: Full access ke seluruh sistem
- **HR**: Manage lowongan, review kandidat, manage offers
- **Interviewer**: Akses ke interview & feedback
- **Candidate**: Apply lowongan, respond offers, track status

> 📚 **Dokumentasi Lengkap**:
> - `DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md` - Dokumentasi sistem lengkap (5776+ baris)
> - `DOKUMENTASI_UPDATE_OFFER_MANAGEMENT.md` - Update fitur Offer Management terbaru
> - `README_SISTEM_REKRUTMEN.md` - Overview sistem rekrutmen
> - `INTERVIEWER_FEATURES_COMPLETED.md` - Fitur interviewer
> - `TESTING_GUIDE.md` - Panduan testing
> - `CHANGELOG.md` - Riwayat perubahan

---

## 🛠 Tech Stack

Proyek ini dibangun menggunakan:

### Backend
- **PHP 8.2+** 
- **Laravel 11.x** - Framework utama
- **MySQL / MariaDB** - Database relasional
- **Eloquent ORM** - Database abstraction

### Frontend
- **Blade** - Templating engine
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Font Awesome** - Icon library

### Build Tools & Development
- **Vite** - Frontend bundler (lihat `vite.config.js`)
- **Composer** - PHP dependency manager
- **npm** - Node package manager
- **Laravel Sail** (optional) - Docker development environment

### Key Laravel Packages
- **Laravel Sanctum** - API authentication
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework

Detail versi dependency lengkap:
- `composer.json` - PHP dependencies
- `package.json` - JavaScript dependencies

---

## 📦 Prasyarat

Sebelum menjalankan proyek ini, pastikan sudah menginstall:

- **PHP 8.2 atau lebih baru**
- **Composer** (latest version)
- **Node.js 18+ & npm**
- **MySQL 8.0+ / MariaDB 10.3+**
- **Git** (untuk clone repository)
- **Web Server** (Apache/Nginx) atau gunakan built-in PHP server

### Ekstensi PHP yang Diperlukan:
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo

---

## 🚀 Cara Menjalankan Proyek Secara Lokal

1. **Clone repository**

   ```bash
   git clone https://github.com/ghilmanfz/rekrutpro.git
   cd rekrutpro
````

2. **Copy file environment**

   ```bash
   cp .env.example .env
   ```

3. **Atur konfigurasi database di `.env`**

   Sesuaikan dengan konfigurasi lokal kamu:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rekrutpro
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Install dependency backend (Composer)**

   ```bash
   composer install
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Jalankan migrasi (dan seeder jika tersedia)**

   ```bash
   php artisan migrate
   ```

   Untuk generate data dummy (optional):

   ```bash
   php artisan db:seed
   ```

   Atau jalankan sekaligus:

   ```bash
   php artisan migrate:fresh --seed
   ```

   > ⚠️ **Warning**: `migrate:fresh` akan menghapus semua data yang ada!

7. **Install dependency frontend (npm)**

   ```bash
   npm install
   ```

8. **Jalankan build/dev frontend**

   Untuk development:

   ```bash
   npm run dev
   ```

   Untuk production build:

   ```bash
   npm run build
   ```

9. **Jalankan server Laravel**

   ```bash
   php artisan serve
   ```

10. **Akses aplikasi**

    Buka di browser:

    ```text
    http://localhost:8000
    ```

### 🎭 Default Users (jika menggunakan seeder)

Jika kamu menjalankan seeder, berikut adalah user default yang bisa digunakan untuk login:

#### Admin
- Email: `admin@rekrutpro.com`
- Password: `password`

#### HR
- Email: `hr@rekrutpro.com`
- Password: `password`

#### Interviewer
- Email: `interviewer@rekrutpro.com`
- Password: `password`

#### Candidate
- Email: `candidate@rekrutpro.com`
- Password: `password`

> 💡 **Note**: Ubah password default setelah login pertama kali untuk keamanan!

---

## 🧪 Testing

RekrutPro dilengkapi dengan test suite untuk memastikan kualitas code.

### Menjalankan Tests

```bash
# Menjalankan semua tests
php artisan test

# atau menggunakan PHPUnit langsung
./vendor/bin/phpunit

# Menjalankan test spesifik
php artisan test --filter=UserTest

# Dengan coverage report
php artisan test --coverage
```

### Test Structure

- `tests/Feature/` - Feature tests (HTTP, Database, Integration)
- `tests/Unit/` - Unit tests (Model, Helper, Service)

### Dokumentasi Testing Lengkap

Panduan detail tersedia di **`TESTING_GUIDE.md`**, termasuk:
- Cara menulis test
- Best practices
- Mocking & Factories
- Database testing

---

## 📂 Struktur & Dokumentasi Tambahan

RekrutPro dilengkapi dengan dokumentasi lengkap untuk memudahkan development:

### 📄 Dokumentasi Utama
- **`DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md`** ⭐ - Dokumentasi sistem lengkap (5776+ baris)
  - Database schema & ERD
  - Alur bisnis proses
  - API endpoints
  - Controller & Model documentation
  - Troubleshooting guide

- **`DOKUMENTASI_UPDATE_OFFER_MANAGEMENT.md`** 🆕 - Update terbaru sistem Offer Management
  - Fitur negosiasi gaji
  - HR edit offer functionality
  - Testing scenarios
  - Code examples

### 📋 Dokumentasi Fitur
- **`README_SISTEM_REKRUTMEN.md`** - Overview sistem rekrutmen
- **`INTERVIEWER_FEATURES_COMPLETED.md`** - Fitur-fitur interviewer
- **`TESTING_GUIDE.md`** - Panduan testing lengkap

### 📊 Progress & History
- **`CHANGELOG.md`** - Catatan perubahan versi
- **`PROGRESS_SUMMARY.md`** - Ringkasan progres pengembangan
- **`SESSION_2_SUMMARY.md`** - Ringkasan sesi pengembangan

### 🗂 Struktur Folder Penting

```
rekrutpro/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── HR/             # HR controllers
│   │   ├── Interviewer/    # Interviewer controllers
│   │   └── Candidate/      # Candidate controllers
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   │   ├── admin/
│   │   ├── hr/
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

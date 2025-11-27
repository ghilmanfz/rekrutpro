# SISTEM REKRUTMEN - EasyRecruit

## 📋 Deskripsi Sistem

EasyRecruit adalah sistem manajemen rekrutmen end-to-end yang komprehensif untuk mengelola seluruh proses perekrutan dari publikasi lowongan hingga penawaran kerja.

## 🎯 Fitur Utama

### 1. **Multi-Role System**
- **Super Admin**: Manajemen penuh sistem
- **HR/Recruiter**: Manajemen lowongan dan kandidat
- **Interviewer**: Penilaian kandidat
- **Candidate**: Pendaftaran dan tracking lamaran

### 2. **Manajemen Lowongan**
- Publikasi lowongan kerja
- Filter berdasarkan divisi, posisi, lokasi
- Status lowongan (draft, active, closed, archived)
- Kuota dan tracking aplikasi

### 3. **Proses Rekrutmen**
- Screening administratif
- Penjadwalan interview
- Penilaian kandidat
- Penawaran kerja
- Status tracking real-time

### 4. **Notifikasi Otomatis**
- Email notifications
- WhatsApp notifications
- Template system dengan placeholders
- Trigger otomatis untuk setiap perubahan status

### 5. **Audit & Reporting**
- Activity logs
- Rekrutmen analytics
- Export data kandidat
- Dashboard metrics

## 🏗️ Struktur Database

### **Tables:**

#### 1. **roles**
- Menyimpan peran pengguna (super_admin, hr, interviewer, candidate)

#### 2. **users**
- Data pengguna sistem
- Fields tambahan: role_id, division_id, phone, address, profile_photo, is_active, otp_code, is_verified

#### 3. **divisions**
- Divisi perusahaan (IT, HR, Marketing, dll)

#### 4. **positions**
- Posisi/jabatan yang tersedia

#### 5. **locations**
- Lokasi kantor/penempatan

#### 6. **job_postings**
- Lowongan pekerjaan
- Status: draft, active, closed, archived
- Informasi detail: deskripsi, requirements, salary range, quota

#### 7. **applications**
- Lamaran kandidat
- Status flow: submitted → screening_passed → interview_scheduled → interview_passed → offered → hired
- Data lengkap kandidat: CV, education, experience, documents

#### 8. **interviews**
- Jadwal interview
- Interviewer assignment
- Location (physical/online)

#### 9. **assessments**
- Penilaian dari interviewer
- Technical skills, communication, problem solving
- Recommendation

#### 10. **offers**
- Penawaran kerja
- Salary, benefits, contract details
- Status: pending, accepted, rejected

#### 11. **notification_templates**
- Template notifikasi dengan placeholder system
- Support email, WhatsApp, SMS

#### 12. **audit_logs**
- Tracking semua aktivitas user
- Change tracking untuk status kandidat

## 🔄 Alur Sistem Lengkap

### **1. Persiapan Sistem (Super Admin)**
```
Super Admin Setup:
├── Membuat akun user internal (HR, Interviewer)
├── Mengatur master data (divisi, posisi, lokasi)
├── Setup template notifikasi
├── Konfigurasi sistem
└── Monitor audit logs
```

### **2. Pra-Rekrutmen (HR)**
```
HR Process:
├── Terima kebutuhan dari departemen
├── Buat lowongan di sistem
│   ├── Isi deskripsi & kualifikasi
│   ├── Set lokasi & quota
│   └── Tentukan salary range
└── Publish lowongan (status: active)
```

### **3. Pendaftaran Kandidat**
```
Candidate Journey:
├── Registrasi & verifikasi OTP
├── Lengkapi profil
├── Browse lowongan aktif
├── Apply dengan upload dokumen
│   ├── CV (required)
│   ├── Cover Letter
│   ├── Portfolio
│   └── Other documents
├── Generate kode lamaran
└── Status: SUBMITTED
```

### **4. Screening Awal (HR)**
```
HR Screening:
├── Review lamaran (status: submitted)
├── Check kualifikasi kandidat
└── Decision:
    ├── Lolos → screening_passed
    └── Tidak lolos → rejected_admin
```

### **5. Penjadwalan Interview (HR)**
```
Interview Scheduling:
├── Pilih kandidat lolos screening
├── Tentukan jadwal interview
├── Assign interviewer
├── Set lokasi (meeting room/online)
├── Status → interview_scheduled
└── Sistem kirim notifikasi otomatis
```

### **6. Interview & Penilaian (Interviewer)**
```
Interview Process:
├── Interviewer lihat jadwal & profil kandidat
├── Conduct interview
├── Isi form penilaian:
│   ├── Technical skills (0-100)
│   ├── Communication (sangat_baik/baik/cukup/kurang)
│   ├── Problem solving (0-100)
│   ├── Teamwork potential (tinggi/sedang/rendah)
│   ├── Overall score
│   └── Recommendation
└── Submit assessment
```

### **7. Review Interview (HR)**
```
HR Review:
├── Lihat hasil penilaian
├── Decision:
│   ├── Lulus → interview_passed/offered
│   └── Tidak lulus → rejected_interview
└── Sistem kirim notifikasi
```

### **8. Offer Kerja (HR)**
```
Job Offer:
├── Isi detail penawaran:
│   ├── Position & salary
│   ├── Contract type & duration
│   ├── Benefits
│   ├── Start date
│   └── Terms & conditions
├── Status → offered
├── Set valid_until date
└── Kirim surat penawaran

Candidate Response:
├── Terima → hired
└── Tolak → rejected_offer
```

### **9. Penutupan & Monitoring**
```
Closure:
├── Jika quota terpenuhi → close lowongan
├── Kandidat pending → archived
└── Generate reports

Monitoring (Super Admin):
├── Laporan per posisi/divisi/periode
├── Audit logs review
├── Analytics dashboard
└── Export data
```

## 📊 Status Flow Diagram

```
APPLICATION STATUS FLOW:

submitted
    ↓
screening_passed ←→ rejected_admin
    ↓
interview_scheduled
    ↓
interview_passed ←→ rejected_interview
    ↓
offered
    ↓
hired ←→ rejected_offer
```

## 🔐 Role & Permissions

### **Super Admin**
✅ Kelola user (create, update, delete, activate/deactivate)
✅ Kelola master data (divisions, positions, locations)
✅ Setup notification templates
✅ Konfigurasi sistem
✅ Access audit logs
✅ Generate all reports
✅ Data correction

### **HR / Recruiter**
✅ Kelola lowongan (CRUD)
✅ Review & screening kandidat
✅ Ubah status kandidat
✅ Schedule interviews
✅ Create job offers
✅ Export kandidat data
✅ View reports untuk divisi sendiri

### **Interviewer**
✅ View jadwal interview
✅ View profil kandidat
✅ Isi form penilaian
❌ TIDAK BISA ubah status kandidat
❌ TIDAK BISA lihat salary info
✅ View own assessment history

### **Candidate**
✅ Register & verify OTP
✅ Kelola profil
✅ Browse lowongan aktif
✅ Apply lowongan
✅ Upload dokumen
✅ Track status lamaran
✅ View interview schedule
✅ Respond to job offer

## 🚀 Installation Guide

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Installation Steps

```bash
# 1. Clone repository
git clone <repository-url>
cd easyrecruit

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database configuration
# Edit .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easyrecruit
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Run seeders (import data awal)
php artisan db:seed

# 7. Storage link
php artisan storage:link

# 8. Compile assets
npm run dev

# 9. Run server
php artisan serve
```

### Default Accounts (After Seeding)

**Super Admin:**
- Email: admin@rekrutpro.com
- Password: password

**HR:**
- Email: hr@rekrutpro.com
- Password: password

**Interviewer:**
- Email: interviewer@rekrutpro.com
- Password: password

## 📧 Notification Templates

### Available Placeholders:
- `{{nama}}` - Nama kandidat
- `{{email}}` - Email kandidat
- `{{posisi}}` - Posisi yang dilamar
- `{{kode_lamaran}}` - Kode lamaran
- `{{tanggal}}` - Tanggal
- `{{waktu}}` - Waktu
- `{{lokasi}}` - Lokasi interview
- `{{interviewer}}` - Nama interviewer
- `{{gaji}}` - Penawaran gaji
- `{{tanggal_mulai}}` - Tanggal mulai kerja

### Template Events:
1. `application_submitted` - Saat kandidat submit lamaran
2. `screening_passed` - Lolos screening admin
3. `screening_rejected` - Tidak lolos screening
4. `interview_scheduled` - Undangan interview
5. `interview_reminder` - Reminder H-1 interview
6. `interview_passed` - Lolos interview
7. `interview_rejected` - Tidak lolos interview
8. `offer_sent` - Penawaran kerja dikirim
9. `offer_accepted` - Kandidat terima offer
10. `offer_rejected` - Kandidat tolak offer

## 📁 Project Structure

```
easyrecruit/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SuperAdmin/
│   │   │   │   ├── UserManagementController.php
│   │   │   │   ├── MasterDataController.php
│   │   │   │   ├── ConfigurationController.php
│   │   │   │   └── AuditLogController.php
│   │   │   ├── HR/
│   │   │   │   ├── JobPostingController.php
│   │   │   │   ├── ApplicationController.php
│   │   │   │   ├── InterviewController.php
│   │   │   │   └── OfferController.php
│   │   │   ├── Interviewer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── AssessmentController.php
│   │   │   └── Candidate/
│   │   │       ├── AuthController.php
│   │   │       ├── JobController.php
│   │   │       ├── ApplicationController.php
│   │   │       └── DashboardController.php
│   │   └── Middleware/
│   │       ├── IsSuperAdmin.php
│   │       ├── IsHR.php
│   │       ├── IsInterviewer.php
│   │       └── IsCandidate.php
│   ├── Models/
│   │   ├── Role.php
│   │   ├── User.php
│   │   ├── Division.php
│   │   ├── Position.php
│   │   ├── Location.php
│   │   ├── JobPosting.php
│   │   ├── Application.php
│   │   ├── Interview.php
│   │   ├── Assessment.php
│   │   ├── Offer.php
│   │   ├── NotificationTemplate.php
│   │   └── AuditLog.php
│   └── Services/
│       ├── NotificationService.php
│       └── AuditService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── super-admin/
│       ├── hr/
│       ├── interviewer/
│       ├── candidate/
│       └── public/
└── routes/
    └── web.php
```

## 🛠️ Development Guide

### Adding New Features

1. **Create Migration**
```bash
php artisan make:migration create_table_name
```

2. **Create Model**
```bash
php artisan make:model ModelName
```

3. **Create Controller**
```bash
php artisan make:controller ControllerName
```

4. **Add Routes** in `routes/web.php`

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## 📝 API Documentation

### Authentication Endpoints

```
POST /api/register
POST /api/login
POST /api/logout
POST /api/verify-otp
```

### Job Postings

```
GET    /api/jobs              - List active jobs
GET    /api/jobs/{id}         - Job detail
POST   /api/jobs              - Create job (HR only)
PUT    /api/jobs/{id}         - Update job (HR only)
DELETE /api/jobs/{id}         - Delete job (HR only)
```

### Applications

```
POST   /api/applications      - Submit application
GET    /api/applications/{code} - Track by code
GET    /api/my-applications   - Candidate applications
PUT    /api/applications/{id}/status - Update status (HR only)
```

## 🔧 Configuration

### Email Setup (.env)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rekrutpro.com
MAIL_FROM_NAME="RekrutPro"
```

### WhatsApp Integration
```
WHATSAPP_API_URL=https://api.whatsapp.com/send
WHATSAPP_TOKEN=your-whatsapp-token
```

## 🐛 Troubleshooting

### Issue: Migration Error
```bash
php artisan migrate:fresh --seed
```

### Issue: Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Class not found
```bash
composer dump-autoload
```

## 📞 Support

Untuk pertanyaan dan support, silakan hubungi:
- Email: support@rekrutpro.com
- Website: https://rekrutpro.com

## 📄 License

This project is licensed under the MIT License.

## 👥 Credits

Developed by RekrutPro Team
© 2025 RekrutPro. All rights reserved.

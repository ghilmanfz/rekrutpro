# DOKUMENTASI ALUR PROGRAM REKRUTPRO - SISTEM REKRUTMEN KARYAWAN

**Versi**: 1.0  
**Tanggal**: 28 November 2025  
**Framework**: Laravel 12.x  
**Database**: MySQL

---

## DAFTAR ISI

### PART 1: OVERVIEW & ARSITEKTUR SISTEM
1. [Gambaran Umum Sistem](#part-1-overview--arsitektur-sistem)
2. [Arsitektur Aplikasi](#arsitektur-aplikasi)
3. [Struktur Database](#struktur-database)
4. [Role & Permission](#role--permission)

### PART 2: ALUR REGISTRASI & AUTENTIKASI
5. [Alur Registrasi Kandidat](#part-2-alur-registrasi--autentikasi)
6. [Alur Login](#alur-login)
7. [Alur Logout](#alur-logout)

### PART 3: ALUR KANDIDAT
8. [Alur Melihat Lowongan](#part-3-alur-kandidat)
9. [Alur Melamar Pekerjaan](#alur-melamar-pekerjaan)
10. [Alur Dashboard Kandidat](#alur-dashboard-kandidat)

### PART 4: ALUR HR
11. [Alur Membuat Lowongan](#part-4-alur-hr)
12. [Alur Mengelola Lamaran](#alur-mengelola-lamaran)
13. [Alur Interview](#alur-interview)

### PART 5: ALUR SUPER ADMIN
14. [Alur Manajemen User](#part-5-alur-super-admin)
15. [Alur Master Data](#alur-master-data)
16. [Alur Audit Log](#alur-audit-log)

---

# PART 1: OVERVIEW & ARSITEKTUR SISTEM

## 1. GAMBARAN UMUM SISTEM

### 1.1 Tujuan Aplikasi
RekrutPro adalah sistem manajemen rekrutmen karyawan berbasis web yang dirancang untuk:
- Memudahkan perusahaan dalam memposting lowongan pekerjaan
- Memfasilitasi kandidat dalam mencari dan melamar pekerjaan
- Mengelola seluruh proses rekrutmen dari pendaftaran hingga hiring
- Menyediakan analytics dan reporting untuk pengambilan keputusan

### 1.2 Fitur Utama

#### A. Untuk Kandidat (Pelamar)
- ✅ Registrasi akun dengan 5 step (Data Diri → Pendidikan → Pengalaman → Dokumen → Konfirmasi)
- ✅ Browse lowongan pekerjaan dengan filter (divisi, lokasi, tipe pekerjaan)
- ✅ Melamar pekerjaan dengan upload CV dan dokumen pendukung
- ✅ Tracking status lamaran (submitted, screening, interview, offered, hired)
- ✅ Dashboard untuk melihat riwayat lamaran
- ✅ Notifikasi status lamaran

#### B. Untuk HR (Human Resources)
- ✅ Membuat dan mengelola lowongan pekerjaan
- ✅ Review lamaran masuk
- ✅ Screening kandidat (accept/reject)
- ✅ Penjadwalan interview
- ✅ Penilaian kandidat
- ✅ Membuat job offer
- ✅ Dashboard analytics (total aplikasi, hire rate, dll)

#### C. Untuk Interviewer
- ✅ Melihat jadwal interview
- ✅ Melakukan penilaian kandidat
- ✅ Input hasil interview
- ✅ Memberikan rekomendasi (hire/not hire)

#### D. Untuk Super Admin
- ✅ Manajemen user (tambah, edit, hapus, activate/deactivate)
- ✅ Manajemen master data (divisions, positions, locations)
- ✅ Konfigurasi sistem
- ✅ Audit log (tracking semua aktivitas user)
- ✅ Backup & restore data

### 1.3 User Roles

| Role | Deskripsi | Akses |
|------|-----------|-------|
| **Super Admin** | Administrator tertinggi | Full access ke semua fitur |
| **HR** | HR Manager/Staff | Kelola lowongan, lamaran, interview |
| **Interviewer** | Pewawancara | Lihat jadwal, nilai kandidat |
| **Candidate** | Pelamar kerja | Browse & apply lowongan, track status |

---

## 2. ARSITEKTUR APLIKASI

### 2.1 Teknologi Stack

```
┌─────────────────────────────────────────────┐
│            FRONTEND (Blade + Tailwind)       │
├─────────────────────────────────────────────┤
│         BACKEND (Laravel 11.x)               │
│  ┌──────────────────────────────────────┐   │
│  │  Controllers (MVC Pattern)            │   │
│  │  - AuthController                     │   │
│  │  - CandidateController               │   │
│  │  - HRController                      │   │
│  │  - SuperAdminController              │   │
│  └──────────────────────────────────────┘   │
│  ┌──────────────────────────────────────┐   │
│  │  Models (Eloquent ORM)               │   │
│  │  - User, Role, JobPosting            │   │
│  │  - Application, Interview, Offer     │   │
│  └──────────────────────────────────────┘   │
│  ┌──────────────────────────────────────┐   │
│  │  Middleware                          │   │
│  │  - Authenticate                      │   │
│  │  - RoleCheck                         │   │
│  │  - EnsureRegistrationCompleted       │   │
│  └──────────────────────────────────────┘   │
├─────────────────────────────────────────────┤
│         DATABASE (MySQL)                     │
│  - users, roles, job_postings               │
│  - applications, interviews, offers         │
│  - divisions, positions, locations          │
│  - audit_logs, notifications                │
└─────────────────────────────────────────────┘
```

### 2.2 Struktur Folder Laravel

```
easyrecruit/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── PasswordResetLinkController.php
│   │   │   ├── Candidate/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ApplicationController.php
│   │   │   │   └── ProfileController.php
│   │   │   ├── HR/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── JobPostingController.php
│   │   │   │   ├── ApplicationController.php
│   │   │   │   └── InterviewController.php
│   │   │   ├── Interviewer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── InterviewController.php
│   │   │   ├── SuperAdmin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── MasterDataController.php
│   │   │   │   └── AuditLogController.php
│   │   │   └── PublicJobController.php
│   │   └── Middleware/
│   │       ├── EnsureRegistrationCompleted.php
│   │       └── RoleMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── JobPosting.php
│   │   ├── Application.php
│   │   ├── Interview.php
│   │   ├── Offer.php
│   │   ├── Division.php
│   │   ├── Position.php
│   │   ├── Location.php
│   │   ├── AuditLog.php
│   │   └── Notification.php
│   └── Services/
│       ├── NotificationService.php
│       └── EmailService.php
├── database/
│   ├── migrations/
│   │   ├── 2025_11_27_000001_create_roles_table.php
│   │   ├── 2025_11_27_000002_create_users_table.php
│   │   ├── 2025_11_27_000003_create_divisions_table.php
│   │   ├── 2025_11_27_000004_create_positions_table.php
│   │   ├── 2025_11_27_000005_create_locations_table.php
│   │   ├── 2025_11_27_102945_create_job_postings_table.php
│   │   ├── 2025_11_27_103024_create_applications_table.php
│   │   ├── 2025_11_27_103100_create_interviews_table.php
│   │   └── 2025_11_27_103200_create_offers_table.php
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── DivisionSeeder.php
│       ├── PositionSeeder.php
│       └── LocationSeeder.php
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── register-steps/
│       │       ├── step1.blade.php (Data Diri)
│       │       ├── step2.blade.php (Pendidikan)
│       │       ├── step3.blade.php (Pengalaman)
│       │       ├── step4.blade.php (Dokumen)
│       │       └── step5.blade.php (Konfirmasi)
│       ├── candidate/
│       │   ├── dashboard.blade.php
│       │   ├── applications/
│       │   └── profile/
│       ├── hr/
│       │   ├── dashboard.blade.php
│       │   ├── job-postings/
│       │   ├── applications/
│       │   └── interviews/
│       ├── interviewer/
│       │   ├── dashboard.blade.php
│       │   └── interviews/
│       ├── superadmin/
│       │   ├── dashboard.blade.php
│       │   ├── users/
│       │   ├── master-data/
│       │   └── audit-logs/
│       ├── public/
│       │   ├── home.blade.php
│       │   └── jobs/
│       │       ├── index.blade.php
│       │       └── show.blade.php
│       └── layouts/
│           ├── app.blade.php
│           ├── guest.blade.php
│           └── navigation.blade.php
└── routes/
    ├── web.php
    ├── api.php
    └── auth.php
```

---

## 3. STRUKTUR DATABASE

### 3.1 Entity Relationship Diagram (ERD)

```
┌─────────────┐         ┌──────────────┐
│    roles    │────1:N──│    users     │
└─────────────┘         └──────────────┘
                             │ 1
                             │
                             │ N
                        ┌────┴────────┐
                        │             │
                    ┌───▼─────────┐   │
                    │ applications│   │
                    └─────────────┘   │
                         │ 1          │
                         │            │
                         │ N          │
                    ┌────▼────────┐   │
                    │ interviews  │   │
                    └─────────────┘   │
                         │ 1          │
                         │            │
                         │ 1          │
                    ┌────▼────────┐   │
                    │   offers    │   │
                    └─────────────┘   │
                                     │
┌─────────────┐                      │
│  divisions  │                      │
└─────────────┘                      │
     │ 1                             │
     │                               │
     │ N                             │
┌────▼──────────┐              ┌─────▼────────┐
│ job_postings  │──────1:N─────│ applications │
└───────────────┘              └──────────────┘
     │ N
     │
     │ 1
┌────▼──────────┐
│  positions    │
└───────────────┘
     │ N
     │
     │ 1
┌────▼──────────┐
│  locations    │
└───────────────┘
```

### 3.2 Tabel-Tabel Database

#### A. Tabel: `roles`
**Deskripsi**: Menyimpan jenis-jenis role dalam sistem

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(50) | Nama role (super_admin, hr, interviewer, candidate) |
| description | TEXT | Deskripsi role |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

**Data Default**:
- ID 1: super_admin
- ID 2: hr
- ID 3: interviewer
- ID 4: candidate

---

#### B. Tabel: `users`
**Deskripsi**: Menyimpan data pengguna sistem

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| role_id | BIGINT | Foreign key ke roles |
| name | VARCHAR(255) | Nama lengkap |
| email | VARCHAR(255) | Email (unique) |
| password | VARCHAR(255) | Password (hashed) |
| phone | VARCHAR(20) | Nomor telepon |
| address | TEXT | Alamat lengkap |
| birth_date | DATE | Tanggal lahir |
| gender | ENUM | male/female |
| profile_photo | VARCHAR(255) | Path foto profil |
| registration_step | INT | Step registrasi (1-5) untuk candidate |
| registration_completed | BOOLEAN | Status registrasi selesai |
| is_active | BOOLEAN | Status aktif user |
| is_verified | BOOLEAN | Status verifikasi email |
| last_login_at | TIMESTAMP | Waktu login terakhir |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

**Indexes**:
- UNIQUE: email
- INDEX: role_id
- INDEX: is_active

**Model Events** (User.php):
```php
static::creating(function ($user) {
    // Auto-assign candidate role if empty
    if (empty($user->role_id)) {
        $user->role_id = 4; // candidate
    }
    
    // Auto-complete registration for internal users
    if (in_array($user->role_id, [1,2,3])) { // super_admin, hr, interviewer
        $user->registration_completed = true;
        $user->is_verified = true;
        $user->is_active = true;
    }
});
```

---

#### C. Tabel: `divisions`
**Deskripsi**: Menyimpan data divisi perusahaan

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(100) | Nama divisi |
| code | VARCHAR(10) | Kode divisi (unique) |
| description | TEXT | Deskripsi divisi |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

**Data Default**:
1. Teknologi Informasi (TI)
2. Keuangan (FIN)
3. Pemasaran (MKT)
4. Operasional (OPS)
5. Sumber Daya Manusia (HRD)
6. Produksi (PRD)

---

#### D. Tabel: `positions`
**Deskripsi**: Menyimpan data posisi/jabatan

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| division_id | BIGINT | Foreign key ke divisions |
| name | VARCHAR(100) | Nama posisi |
| code | VARCHAR(10) | Kode posisi (unique) |
| description | TEXT | Deskripsi posisi |
| level | ENUM | entry, junior, mid, senior, manager |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

**Data Default** (34 posisi):
- Software Engineer (SE)
- Frontend Developer (FE)
- Backend Developer (BE)
- DevOps Engineer (DO)
- QA Engineer (QA)
- Product Manager (PM)
- Project Manager (PJM)
- dst...

---

#### E. Tabel: `locations`
**Deskripsi**: Menyimpan data lokasi kantor

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(100) | Nama lokasi |
| address | TEXT | Alamat lengkap |
| city | VARCHAR(50) | Kota |
| province | VARCHAR(50) | Provinsi |
| postal_code | VARCHAR(10) | Kode pos |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

**Data Default**:
1. Jakarta Pusat
2. Jakarta Selatan
3. Bandung
4. Surabaya
5. Yogyakarta
6. Semarang
7. Bali

---

#### F. Tabel: `job_postings`
**Deskripsi**: Menyimpan data lowongan pekerjaan

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| code | VARCHAR(50) | Kode lowongan (unique, auto-generated) |
| position_id | BIGINT | Foreign key ke positions |
| division_id | BIGINT | Foreign key ke divisions |
| location_id | BIGINT | Foreign key ke locations |
| created_by | BIGINT | Foreign key ke users (HR) |
| title | VARCHAR(255) | Judul lowongan |
| description | TEXT | Deskripsi pekerjaan |
| requirements | TEXT | Kualifikasi yang dibutuhkan |
| responsibilities | TEXT | Tanggung jawab |
| benefits | TEXT | Benefit yang ditawarkan |
| quota | INT | Jumlah lowongan |
| employment_type | ENUM | full_time, part_time, contract, internship |
| experience_level | ENUM | entry, junior, mid, senior, lead, manager |
| salary_min | DECIMAL(12,2) | Gaji minimum |
| salary_max | DECIMAL(12,2) | Gaji maksimum |
| salary_currency | VARCHAR(3) | IDR, USD, dll |
| published_at | DATE | Tanggal dipublikasi |
| closed_at | DATE | Tanggal deadline |
| status | ENUM | draft, active, closed, archived |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

**Auto-Generated Code Pattern**: `{POSITION_CODE}-{NUMBER}`
- Contoh: SE-001, SE-002, FE-001, BE-001

**Code Generator Logic**:
```php
protected function generateJobCode($positionId)
{
    $position = Position::find($positionId);
    $prefix = strtoupper($position->code);
    
    // Get existing codes (including soft deleted)
    $existingCodes = JobPosting::withTrashed()
        ->where('code', 'like', $prefix . '-%')
        ->pluck('code')
        ->toArray();
    
    // Extract numbers and find max
    $existingNumbers = [];
    foreach ($existingCodes as $code) {
        $parts = explode('-', $code);
        if (count($parts) >= 2) {
            $number = (int) end($parts);
            $existingNumbers[] = $number;
        }
    }
    
    // Next number
    $newNumber = empty($existingNumbers) ? 1 : max($existingNumbers) + 1;
    $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
    return $newCode;
}
```

**Scope Methods**:
```php
public function scopePublished($query)
{
    return $query->where('status', 'active')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
}

public function scopeActive($query)
{
    return $query->where('status', 'active');
}
```

---

#### G. Tabel: `applications` ✨ **[UPDATED - SNAPSHOT APPROACH]**
**Deskripsi**: Menyimpan data lamaran kandidat dengan snapshot data saat melamar

> **⚠️ PENTING - Menggunakan Pendekatan Snapshot**:  
> Field `candidate_snapshot` menyimpan **snapshot data kandidat pada saat melamar**.  
> Ini memastikan data historis tetap terjaga meskipun kandidat mengupdate profil mereka.

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| code | VARCHAR(50) | Kode lamaran (unique, auto-generated) |
| job_posting_id | BIGINT | Foreign key ke job_postings |
| candidate_id | BIGINT | Foreign key ke users |
| **candidate_snapshot** | **JSON** | **📸 Snapshot data kandidat saat apply** |
| cv_file | VARCHAR(255) | Path file CV |
| cover_letter | TEXT | Surat lamaran |
| portfolio_file | VARCHAR(255) | Path file portfolio |
| other_documents | JSON | Dokumen lainnya (array) |
| status | ENUM | Status lamaran (10 status) |
| rejection_reason | TEXT | Alasan penolakan |
| reviewed_by | BIGINT | Foreign key ke users (HR/Admin) |
| reviewed_at | TIMESTAMP | Waktu direview |
| screening_passed_at | TIMESTAMP | Waktu lulus screening |
| interview_scheduled_at | TIMESTAMP | Waktu interview dijadwalkan |
| interview_passed_at | TIMESTAMP | Waktu lulus interview |
| offered_at | TIMESTAMP | Waktu diberi offer |
| hired_at | TIMESTAMP | Waktu diterima kerja |
| created_at | TIMESTAMP | Waktu apply |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

**Status Flow**:
```
submitted (Baru Apply)
    ↓
screening_passed (Lulus Screening) / rejected_admin (Ditolak Admin)
    ↓
interview_scheduled (Interview Dijadwalkan)
    ↓
interview_passed (Lulus Interview) / rejected_interview (Ditolak Interview)
    ↓
offered (Diberi Penawaran)
    ↓
hired (Diterima) / rejected_offer (Menolak Offer)
    ↓
archived (Diarsipkan)
```

**Candidate Snapshot JSON Format**:
```json
{
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "08123456789",
    "address": "Jl. Sudirman No. 1, Jakarta",
    "birth_date": "1995-05-15",
    "gender": "male",
    "profile_photo": "storage/photos/john-doe.jpg",
    "education": [
        {
            "degree": "S1",
            "institution": "Universitas Indonesia",
            "major": "Teknik Informatika",
            "graduation_year": "2020",
            "gpa": "3.75"
        }
    ],
    "experience": [
        {
            "position": "Software Engineer",
            "company": "PT. Tech Indonesia",
            "start_date": "2020-01",
            "end_date": "2023-12",
            "is_current": false,
            "description": "Develop web applications..."
        }
    ],
    "snapshot_at": "2025-11-28 10:30:00"
}
```

**Keuntungan Snapshot Approach**:
1. ✅ **Historical Accuracy** - Data asli saat apply tetap tersimpan
2. ✅ **Audit Trail** - Untuk compliance dan legal requirements
3. ✅ **No Sync Issue** - Tidak terpengaruh perubahan profil user
4. ✅ **Data Integrity** - HR selalu lihat kondisi kandidat saat melamar

---

#### H. Tabel: `interviews`
**Deskripsi**: Menyimpan data interview kandidat

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| application_id | BIGINT | Foreign key ke applications |
| interviewer_id | BIGINT | Foreign key ke users (interviewer) |
| scheduled_at | DATETIME | Jadwal interview |
| location | VARCHAR(255) | Lokasi interview / Meeting link |
| type | ENUM | online, offline |
| notes | TEXT | Catatan interview |
| rating | INT | Rating 1-5 |
| recommendation | ENUM | hire, not_hire, maybe |
| status | ENUM | scheduled, completed, cancelled, no_show |
| completed_at | TIMESTAMP | Waktu selesai interview |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

---

#### I. Tabel: `offers`
**Deskripsi**: Menyimpan data penawaran kerja

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| application_id | BIGINT | Foreign key ke applications |
| offered_by | BIGINT | Foreign key ke users (HR) |
| position_offered | VARCHAR(255) | Posisi yang ditawarkan |
| salary_offered | DECIMAL(12,2) | Gaji yang ditawarkan |
| start_date | DATE | Tanggal mulai kerja |
| benefits | TEXT | Benefit |
| offer_letter_file | VARCHAR(255) | Path file offer letter |
| valid_until | DATE | Berlaku sampai |
| status | ENUM | pending, accepted, rejected, expired |
| response_notes | TEXT | Catatan response kandidat |
| responded_at | TIMESTAMP | Waktu response |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

---

#### J. Tabel: `audit_logs`
**Deskripsi**: Menyimpan log aktivitas user

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| user_id | BIGINT | Foreign key ke users |
| action | VARCHAR(50) | create, update, delete, login, logout |
| model_type | VARCHAR(100) | Nama model (JobPosting, Application, dll) |
| model_id | BIGINT | ID record yang diubah |
| old_data | JSON | Data sebelum perubahan |
| new_data | JSON | Data setelah perubahan |
| ip_address | VARCHAR(45) | IP address user |
| user_agent | TEXT | Browser user agent |
| created_at | TIMESTAMP | Waktu aktivitas |

**Usage Example**:
```php
AuditLog::log('create', $jobPosting, [], $validated);
AuditLog::log('update', $jobPosting, $oldData, $newData);
AuditLog::log('delete', $jobPosting, $oldData, []);
```

---

#### K. Tabel: `notifications`
**Deskripsi**: Menyimpan notifikasi user

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary key |
| user_id | BIGINT | Foreign key ke users |
| type | VARCHAR(50) | Tipe notifikasi |
| title | VARCHAR(255) | Judul notifikasi |
| message | TEXT | Isi notifikasi |
| data | JSON | Data tambahan |
| read_at | TIMESTAMP | Waktu dibaca |
| created_at | TIMESTAMP | Waktu dibuat |

---

## 4. ROLE & PERMISSION

### 4.1 Role Matrix

| Feature / Menu | Super Admin | HR | Interviewer | Candidate |
|----------------|-------------|----|-----------|-----------| 
| **Dashboard** | ✅ | ✅ | ✅ | ✅ |
| **Public Jobs** | ✅ | ✅ | ✅ | ✅ |
| **Browse Jobs** | ✅ | ✅ | ✅ | ✅ |
| **Apply Job** | ❌ | ❌ | ❌ | ✅ |
| **My Applications** | ❌ | ❌ | ❌ | ✅ |
| **Manage Job Postings** | ✅ | ✅ | ❌ | ❌ |
| **Review Applications** | ✅ | ✅ | ❌ | ❌ |
| **Schedule Interview** | ✅ | ✅ | ❌ | ❌ |
| **Conduct Interview** | ✅ | ✅ | ✅ | ❌ |
| **Make Offer** | ✅ | ✅ | ❌ | ❌ |
| **User Management** | ✅ | ❌ | ❌ | ❌ |
| **Master Data** | ✅ | ❌ | ❌ | ❌ |
| **Audit Logs** | ✅ | ❌ | ❌ | ❌ |
| **System Config** | ✅ | ❌ | ❌ | ❌ |

### 4.2 Middleware Protection

**File**: `app/Http/Middleware/RoleMiddleware.php`

```php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $userRole = auth()->user()->role->name;

    if (!in_array($userRole, $roles)) {
        abort(403, 'Unauthorized access');
    }

    return $next($request);
}
```

**Route Protection Example**:
```php
// Super Admin only
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index']);
    Route::resource('/superadmin/users', UserController::class);
});

// HR only
Route::middleware(['auth', 'role:hr'])->group(function () {
    Route::get('/hr/dashboard', [HRDashboardController::class, 'index']);
    Route::resource('/hr/job-postings', JobPostingController::class);
});

// Interviewer + HR
Route::middleware(['auth', 'role:hr,interviewer'])->group(function () {
    Route::get('/interviews', [InterviewController::class, 'index']);
});

// Candidate only
Route::middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/candidate/dashboard', [CandidateDashboardController::class, 'index']);
    Route::post('/jobs/{id}/apply', [ApplicationController::class, 'store']);
});
```

### 4.3 Registration Completion Check

**File**: `app/Http/Middleware/EnsureRegistrationCompleted.php`

```php
public function handle($request, Closure $next)
{
    $user = auth()->user();
    
    // Skip check untuk internal users
    if (in_array($user->role->name, ['super_admin', 'hr', 'interviewer'])) {
        return $next($request);
    }
    
    // Candidate harus complete registration
    if (!$user->registration_completed) {
        return redirect()->route('register.step', ['step' => $user->registration_step]);
    }
    
    return $next($request);
}
```

**Applied to**:
```php
Route::middleware(['auth', 'registration.completed'])->group(function () {
    Route::get('/candidate/dashboard', ...);
    Route::get('/candidate/applications', ...);
    Route::get('/candidate/profile', ...);
});
```

---

# PART 2: ALUR REGISTRASI & AUTENTIKASI

## 5. ALUR REGISTRASI KANDIDAT

### 5.1 Overview Registrasi 5 Step

Registrasi kandidat dibagi menjadi 5 step untuk memastikan data lengkap dan terstruktur:

```
Step 1: Data Diri Utama
   ↓
Step 2: Data Pendidikan
   ↓
Step 3: Data Pengalaman Kerja
   ↓
Step 4: Upload Dokumen
   ↓
Step 5: Konfirmasi & Selesai
```

### 5.2 Step 1: Data Diri Utama

**Route**: `GET /register` dan `POST /register`

**Controller**: `RegisteredUserController@create` dan `RegisteredUserController@store`

**View**: `resources/views/auth/register.blade.php`

**Flow Diagram**:
```
User mengakses /register
    ↓
Tampilkan form Step 1
    ↓
User input: name, email, password, phone
    ↓
POST /register
    ↓
Validasi input:
  - email unique
  - password min 8 karakter
  - phone format Indonesia
    ↓
Simpan ke database:
  - role_id = 4 (candidate)
  - registration_step = 1
  - registration_completed = false
  - is_active = true ✅ (FIXED: agar bisa login lagi untuk lanjut registrasi)
  - is_verified = false
    ↓
Login otomatis (Auth::login)
    ↓
Redirect ke /register/step/2
```

> **💡 PENTING - Bug Fix (Nov 2025)**:  
> Field `is_active` di-set `true` sejak Step 1 agar kandidat bisa logout/timeout dan login kembali untuk melanjutkan registrasi. Sebelumnya, `is_active = false` menyebabkan kandidat ter-lock dan tidak bisa login lagi.  
> - `is_active = true` → Kandidat bisa login untuk lanjut registrasi  
> - `registration_completed = false` → Middleware akan redirect ke step yang belum selesai  
> - Suspension account hanya untuk user yang sudah `registration_completed = true`

**Kode Controller** (`RegisterController.php`):
```php
public function processStep1(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:20',
        'agree_terms' => 'required|accepted',
    ]);

    // Get candidate role
    $candidateRole = Role::where('name', 'candidate')->first();

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'phone' => $validated['phone'],
        'role_id' => $candidateRole->id,
        'registration_step' => 1,
        'registration_completed' => false,
        'is_active' => true, // ✅ FIXED: Set true agar bisa login lagi
        'is_verified' => false,
    ]);

    // Auto login
    Auth::login($user);

    // Redirect to step 2
    return redirect()->route('register.step2')
        ->with('success', 'Akun berhasil dibuat! Silakan lanjutkan ke tahap selanjutnya.');
}
```

**Form Fields**:
- Nama Lengkap (required, max 255)
- Email (required, unique, valid email)
- Password (required, min 8, confirmed)
- Konfirmasi Password (required, must match password)
- Nomor Telepon (required, max 20)

---

### 5.3 Step 2: Data Pendidikan

**Route**: `GET /register/step/2` dan `POST /register/step/2`

**Controller**: `RegisteredUserController@showStep` dan `RegisteredUserController@updateStep`

**View**: `resources/views/auth/register-steps/step2.blade.php`

**Flow Diagram**:
```
User di Step 2
    ↓
Tampilkan form pendidikan
    ↓
User bisa tambah multiple pendidikan
    ↓
Untuk setiap pendidikan input:
  - Jenjang (SD/SMP/SMA/D3/S1/S2/S3)
  - Nama Institusi
  - Jurusan
  - Tahun Lulus
  - IPK/Nilai (opsional)
    ↓
POST /register/step/2
    ↓
Validasi:
  - Minimal 1 pendidikan
  - Semua field required kecuali IPK
    ↓
Simpan ke JSON column di users:
  - education = JSON array
    ↓
Update:
  - registration_step = 2
    ↓
Redirect ke /register/step/3
```

**Kode Controller**:
```php
public function updateStep(Request $request, $step)
{
    $user = auth()->user();
    
    if ($step == 2) {
        $validated = $request->validate([
            'education' => 'required|array|min:1',
            'education.*.degree' => 'required|string',
            'education.*.institution' => 'required|string|max:255',
            'education.*.major' => 'required|string|max:255',
            'education.*.graduation_year' => 'required|integer|min:1950|max:' . (date('Y') + 5),
            'education.*.gpa' => 'nullable|numeric|min:0|max:4',
        ]);

        $user->update([
            'education' => json_encode($validated['education']),
            'registration_step' => 2,
        ]);

        return redirect()->route('register.step', ['step' => 3]);
    }
}
```

**Education JSON Format**:
```json
[
    {
        "degree": "S1",
        "institution": "Universitas Indonesia",
        "major": "Teknik Informatika",
        "graduation_year": "2020",
        "gpa": "3.75"
    },
    {
        "degree": "SMA",
        "institution": "SMA Negeri 1 Jakarta",
        "major": "IPA",
        "graduation_year": "2016",
        "gpa": "90.5"
    }
]
```

**Form Features**:
- Dynamic form: Bisa tambah/hapus pendidikan dengan JavaScript
- Button "Tambah Pendidikan Lain"
- Button "Hapus" untuk setiap pendidikan
- Dropdown jenjang: SD, SMP, SMA, D3, S1, S2, S3
- Validation real-time dengan JavaScript

---

### 5.4 Step 3: Data Pengalaman Kerja

**Route**: `GET /register/step/3` dan `POST /register/step/3`

**Controller**: `RegisteredUserController@showStep` dan `RegisteredUserController@updateStep`

**View**: `resources/views/auth/register-steps/step3.blade.php`

**Flow Diagram**:
```
User di Step 3
    ↓
Tampilkan form pengalaman kerja
    ↓
User bisa tambah multiple pengalaman (opsional)
    ↓
Untuk setiap pengalaman input:
  - Posisi/Jabatan
  - Nama Perusahaan
  - Tanggal Mulai
  - Tanggal Selesai (atau "Masih Bekerja")
  - Deskripsi Pekerjaan
    ↓
POST /register/step/3
    ↓
Validasi:
  - Array bisa kosong (fresh graduate)
  - Jika ada, semua field required
  - Tanggal selesai >= tanggal mulai
    ↓
Simpan ke JSON column:
  - experience = JSON array
    ↓
Update:
  - registration_step = 3
    ↓
Redirect ke /register/step/4
```

**Kode Controller**:
```php
public function updateStep(Request $request, $step)
{
    $user = auth()->user();
    
    if ($step == 3) {
        $validated = $request->validate([
            'experience' => 'nullable|array',
            'experience.*.position' => 'required_with:experience|string|max:255',
            'experience.*.company' => 'required_with:experience|string|max:255',
            'experience.*.start_date' => 'required_with:experience|date',
            'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            'experience.*.is_current' => 'nullable|boolean',
            'experience.*.description' => 'nullable|string',
        ]);

        $user->update([
            'experience' => $validated['experience'] ? json_encode($validated['experience']) : null,
            'registration_step' => 3,
        ]);

        return redirect()->route('register.step', ['step' => 4]);
    }
}
```

**Experience JSON Format**:
```json
[
    {
        "position": "Software Engineer",
        "company": "PT. Tech Indonesia",
        "start_date": "2020-01-15",
        "end_date": "2023-12-31",
        "is_current": false,
        "description": "Mengembangkan aplikasi web menggunakan Laravel dan Vue.js"
    },
    {
        "position": "Junior Developer",
        "company": "PT. Startup Digital",
        "start_date": "2018-06-01",
        "end_date": "2019-12-31",
        "is_current": false,
        "description": "Maintenance website perusahaan"
    }
]
```

**Form Features**:
- Checkbox "Masih Bekerja di Sini" → disable end_date
- Dynamic add/remove experience
- Text area untuk deskripsi pekerjaan
- Bisa skip jika fresh graduate (tombol "Lewati, Saya Fresh Graduate")

---

### 5.5 Step 4: Upload Dokumen

**Route**: `GET /register/step/4` dan `POST /register/step/4`

**Controller**: `RegisteredUserController@showStep` dan `RegisteredUserController@updateStep`

**View**: `resources/views/auth/register-steps/step4.blade.php`

**Flow Diagram**:
```
User di Step 4
    ↓
Tampilkan form upload dokumen
    ↓
User upload:
  - Foto Profil (required, max 2MB, jpg/png)
  - CV (required, max 5MB, pdf)
  - Portfolio (opsional, max 10MB, pdf/zip)
  - Dokumen Lain (opsional, multiple files)
    ↓
POST /register/step/4
    ↓
Validasi file:
  - Tipe file sesuai
  - Ukuran tidak melebihi batas
    ↓
Simpan file ke storage:
  - storage/app/public/candidates/{user_id}/
    ↓
Simpan path ke database:
  - profile_photo
  - cv_file (di JSON)
  - portfolio_file (di JSON)
  - other_documents (array di JSON)
    ↓
Update:
  - registration_step = 4
    ↓
Redirect ke /register/step/5
```

**Kode Controller**:
```php
public function updateStep(Request $request, $step)
{
    $user = auth()->user();
    
    if ($step == 4) {
        $validated = $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'cv_file' => 'required|file|mimes:pdf|max:5120',
            'portfolio_file' => 'nullable|file|mimes:pdf,zip|max:10240',
            'other_documents.*' => 'nullable|file|mimes:pdf,jpg,png,zip|max:5120',
        ]);

        // Upload profile photo
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store(
                "candidates/{$user->id}/profile",
                'public'
            );
            $user->profile_photo = $photoPath;
        }

        // Upload CV
        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store(
                "candidates/{$user->id}/documents",
                'public'
            );
        }

        // Upload Portfolio
        $portfolioPath = null;
        if ($request->hasFile('portfolio_file')) {
            $portfolioPath = $request->file('portfolio_file')->store(
                "candidates/{$user->id}/documents",
                'public'
            );
        }

        // Upload Other Documents
        $otherDocs = [];
        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $path = $file->store(
                    "candidates/{$user->id}/documents",
                    'public'
                );
                $otherDocs[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        // Update documents JSON
        $documents = [
            'cv' => $cvPath,
            'portfolio' => $portfolioPath,
            'others' => $otherDocs,
        ];

        $user->update([
            'documents' => json_encode($documents),
            'registration_step' => 4,
        ]);
        
        $user->save();

        return redirect()->route('register.step', ['step' => 5]);
    }
}
```

**Storage Structure**:
```
storage/app/public/
└── candidates/
    └── {user_id}/
        ├── profile/
        │   └── profile_photo.jpg
        └── documents/
            ├── cv.pdf
            ├── portfolio.pdf
            └── other_document_1.pdf
```

**File Size Limits**:
- Foto Profil: Max 2MB (jpg, png)
- CV: Max 5MB (pdf only)
- Portfolio: Max 10MB (pdf, zip)
- Dokumen Lain: Max 5MB per file (pdf, jpg, png, zip)

---

### 5.6 Step 5: Konfirmasi & Selesai

**Route**: `GET /register/step/5` dan `POST /register/complete`

**Controller**: `RegisteredUserController@showStep` dan `RegisteredUserController@complete`

**View**: `resources/views/auth/register-steps/step5.blade.php`

**Flow Diagram**:
```
User di Step 5
    ↓
Tampilkan ringkasan semua data:
  - Data Diri (Step 1)
  - Pendidikan (Step 2)
  - Pengalaman (Step 3)
  - Dokumen (Step 4)
    ↓
User review dan konfirmasi
    ↓
POST /register/complete
    ↓
Update database:
  - registration_step = 5
  - registration_completed = true
  - is_active = true (menunggu verifikasi admin)
    ↓
Kirim email verifikasi
    ↓
Redirect ke /candidate/dashboard
    ↓
Show success message:
  "Registrasi berhasil! Akun Anda menunggu verifikasi admin."
```

**Kode Controller**:
```php
public function complete(Request $request)
{
    $user = auth()->user();

    // Update registration status
    $user->update([
        'registration_step' => 5,
        'registration_completed' => true,
        'is_active' => true,
    ]);

    // Send verification email (opsional)
    // Mail::to($user->email)->send(new RegistrationCompleted($user));

    // Create notification
    Notification::create([
        'user_id' => $user->id,
        'type' => 'registration_completed',
        'title' => 'Registrasi Berhasil',
        'message' => 'Selamat! Registrasi Anda telah selesai. Silakan lengkapi profil Anda.',
    ]);

    return redirect()->route('candidate.dashboard')
        ->with('success', 'Registrasi berhasil! Selamat datang di RekrutPro.');
}
```

**Display Summary**:
```php
// Step 5 View
@php
    $education = json_decode($user->education, true) ?? [];
    $experience = json_decode($user->experience, true) ?? [];
    $documents = json_decode($user->documents, true) ?? [];
@endphp

<!-- Data Diri -->
<h3>Data Diri</h3>
<p>Nama: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Telepon: {{ $user->phone }}</p>

<!-- Pendidikan -->
<h3>Pendidikan</h3>
@foreach($education as $edu)
    <p>{{ $edu['degree'] }} - {{ $edu['institution'] }}</p>
@endforeach

<!-- Pengalaman -->
<h3>Pengalaman Kerja</h3>
@foreach($experience as $exp)
    <p>{{ $exp['position'] }} di {{ $exp['company'] }}</p>
@endforeach

<!-- Dokumen -->
<h3>Dokumen</h3>
<p>Foto Profil: <img src="{{ asset('storage/' . $user->profile_photo) }}"></p>
<p>CV: {{ $documents['cv'] ?? 'Belum upload' }}</p>
<p>Portfolio: {{ $documents['portfolio'] ?? 'Tidak ada' }}</p>
```

---

### 5.7 Handling Step Navigation

**Middleware Check**: `EnsureRegistrationCompleted.php`

```php
public function handle($request, Closure $next)
{
    $user = auth()->user();
    
    // Skip untuk internal users (super_admin, hr, interviewer)
    if (in_array($user->role->name, ['super_admin', 'hr', 'interviewer'])) {
        return $next($request);
    }
    
    // Jika candidate belum complete registration
    if (!$user->registration_completed) {
        // Allow access to register steps
        if ($request->is('register/*')) {
            return $next($request);
        }
        
        // Redirect to current step
        return redirect()->route('register.step', ['step' => $user->registration_step]);
    }
    
    return $next($request);
}
```

**Step Validation Logic**:
```php
public function showStep($step)
{
    $user = auth()->user();
    
    // Validate step number
    if ($step < 1 || $step > 5) {
        abort(404);
    }
    
    // Prevent skip steps
    if ($step > ($user->registration_step + 1)) {
        return redirect()->route('register.step', ['step' => $user->registration_step + 1])
            ->with('error', 'Harap selesaikan step sebelumnya terlebih dahulu.');
    }
    
    // Prevent go back to completed steps (opsional)
    // if ($step < $user->registration_step && !request()->has('edit')) {
    //     return redirect()->route('register.step', ['step' => $user->registration_step + 1]);
    // }
    
    return view("auth.register-steps.step{$step}", compact('user'));
}
```

**Progress Bar Component**:
```html
<!-- Progress bar di setiap step -->
<div class="registration-progress mb-6">
    <div class="flex justify-between items-center">
        <div class="step {{ $user->registration_step >= 1 ? 'completed' : '' }}">
            <div class="step-number">1</div>
            <div class="step-title">Data Diri</div>
        </div>
        <div class="step {{ $user->registration_step >= 2 ? 'completed' : '' }}">
            <div class="step-number">2</div>
            <div class="step-title">Pendidikan</div>
        </div>
        <div class="step {{ $user->registration_step >= 3 ? 'completed' : '' }}">
            <div class="step-number">3</div>
            <div class="step-title">Pengalaman</div>
        </div>
        <div class="step {{ $user->registration_step >= 4 ? 'completed' : '' }}">
            <div class="step-number">4</div>
            <div class="step-title">Dokumen</div>
        </div>
        <div class="step {{ $user->registration_step >= 5 ? 'completed' : '' }}">
            <div class="step-number">5</div>
            <div class="step-title">Konfirmasi</div>
        </div>
    </div>
</div>
```

---

## 6. ALUR LOGIN

### 6.1 Login Flow Diagram

```
User mengakses /login
    ↓
Tampilkan form login
    ↓
User input email & password
    ↓
POST /login
    ↓
Validasi credentials
    ├── GAGAL → Redirect back with error
    │           "Email atau password salah"
    │
    └── BERHASIL
        ↓
        Auth::login($user)
        ↓
        Update last_login_at
        ↓
        Check registration_completed (untuk candidate)
        ├── FALSE (Candidate belum selesai registrasi)
        │   ↓
        │   ✅ Redirect ke /register/step{N} untuk lanjut registrasi
        │   (Smart redirect ke step yang belum selesai)
        │
        └── TRUE
            ↓
            Check is_active
            ├── FALSE (Account suspended by admin)
            │   ↓
            │   Logout & error: "Akun dinonaktifkan administrator"
            │
            └── TRUE
                ↓
                Redirect based on role
                ├── super_admin → /superadmin/dashboard
                ├── hr → /hr/dashboard
                ├── interviewer → /interviewer/dashboard
                └── candidate → /candidate/dashboard
```

> **💡 PENTING - Bug Fix (Nov 2025)**:  
> Login flow telah diperbaiki untuk menangani kandidat yang belum selesai registrasi:
> - Kandidat yang `registration_completed = false` akan di-redirect ke step registrasi yang belum selesai (bukan di-reject)
> - Hanya user dengan `is_active = false` DAN `registration_completed = true` yang di-block (suspended by admin)
> - Ini memastikan kandidat bisa logout/timeout dan login kembali untuk melanjutkan registrasi

### 6.2 Login Controller

**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Method**: `store(LoginRequest $request)`

```php
public function store(LoginRequest $request): RedirectResponse
{
    // Authenticate user
    $request->authenticate();

    // Regenerate session
    $request->session()->regenerate();

    // Get authenticated user
    $user = auth()->user();
    
    // ✅ PERBAIKAN: Redirect kandidat yang belum complete registrasi
    if (!$user->registration_completed && $user->role->name === 'candidate') {
        // Redirect ke step yang sesuai berdasarkan registration_step
        $step = $user->registration_step ?? 1;
        
        return redirect()->route("register.step{$step}")
            ->with('info', 'Silakan lanjutkan proses registrasi Anda.');
    }

    // ✅ PERBAIKAN: Check suspended account (setelah complete registrasi)
    if (!$user->is_active && $user->registration_completed) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi tim support.']);
    }

    // Update last login
    $user->update(['last_login_at' => now()]);

    // Log activity
    AuditLog::create([
        'user_id' => $user->id,
        'action' => 'login',
        'model_type' => 'User',
        'model_id' => $user->id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // Redirect based on role
    return $this->redirectBasedOnRole($user);
}

protected function redirectBasedOnRole($user): RedirectResponse
{
    $roleName = $user->role->name ?? null;
    
    switch ($roleName) {
        case 'super_admin':
            return redirect()->route('superadmin.dashboard');
        case 'hr':
            return redirect()->route('hr.dashboard');
        case 'interviewer':
            return redirect()->route('interviewer.dashboard');
        case 'candidate':
            return redirect()->route('candidate.dashboard');
        default:
            return redirect()->route('dashboard'); // fallback
    }
}
```

### 6.3 Login Request Validation

**File**: `app/Http/Requests/Auth/LoginRequest.php`

```php
public function rules()
{
    return [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];
}

public function authenticate()
{
    $this->ensureIsNotRateLimited();

    if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('Email atau password yang Anda masukkan salah.'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
    
    // ✅ Catatan: Validasi is_active dipindahkan ke AuthenticatedSessionController
    // untuk membedakan antara "suspended" vs "incomplete registration"
}

public function ensureIsNotRateLimited()
{
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]),
    ]);
}

public function throttleKey()
{
    return Str::lower($this->input('email')).'|'.$this->ip();
}
```

### 6.4 Login View

**File**: `resources/views/auth/login.blade.php`

```html
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    
    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Email
        </label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autofocus
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            Password
        </label>
        <input 
            id="password" 
            type="password" 
            name="password" 
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input 
                id="remember" 
                type="checkbox" 
                name="remember"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-900">
                Ingat Saya
            </label>
        </div>

        <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Lupa Password?
            </a>
        </div>
    </div>

    <!-- Submit Button -->
    <div>
        <button 
            type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            Masuk
        </button>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Daftar Sekarang
            </a>
        </p>
    </div>
</form>
```

### 6.5 Rate Limiting

Laravel menggunakan rate limiting untuk mencegah brute force attack:

**Config**: `config/auth.php`

```php
'throttle' => [
    'max_attempts' => 5,
    'decay_minutes' => 1,
],
```

**Behavior**:
- Maximum 5 login attempts per email/IP
- Lockout duration: 1 minute after 5 failed attempts
- Counter reset after successful login

---

## 7. ALUR LOGOUT

### 7.1 Logout Flow Diagram

```
User klik tombol Logout
    ↓
POST /logout
    ↓
Log activity (audit log)
    ↓
Auth::logout()
    ↓
Invalidate session
    ↓
Regenerate CSRF token
    ↓
Redirect ke /login
    ↓
Show message: "Anda telah logout"
```

### 7.2 Logout Controller

**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Method**: `destroy(Request $request)`

```php
public function destroy(Request $request)
{
    // Log logout activity
    if (auth()->check()) {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'logout',
            'model_type' => 'User',
            'model_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    // Logout
    Auth::guard('web')->logout();

    // Invalidate session
    $request->session()->invalidate();

    // Regenerate token
    $request->session()->regenerateToken();

    // Redirect to login
    return redirect()->route('login')
        ->with('success', 'Anda telah berhasil logout.');
}
```

### 7.3 Logout Button

**Di Navigation Bar**:
```html
<form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button 
        type="submit"
        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
    >
        <i class="fas fa-sign-out-alt mr-1"></i>
        Logout
    </button>
</form>
```

**Dengan Konfirmasi JavaScript**:
```html
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button 
        type="button"
        onclick="confirmLogout()"
        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
    >
        <i class="fas fa-sign-out-alt mr-1"></i>
        Logout
    </button>
</form>

<script>
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        document.getElementById('logout-form').submit();
    }
}
</script>
```

---

# PART 3: ALUR KANDIDAT

## 8. ALUR MELIHAT LOWONGAN

### 8.1 Public Job Listing (Tanpa Login)

**Route**: `GET /jobs`

**Controller**: `PublicJobController@index`

**View**: `resources/views/public/jobs/index.blade.php`

**Flow Diagram**:
```
User (guest/logged in) akses /jobs
    ↓
Query database job_postings:
  - WHERE status = 'active'
  - WHERE published_at <= today
  - WHERE closed_at >= today
    ↓
Apply filters (jika ada):
  - Division filter
  - Location filter
  - Employment type filter
  - Search keyword (title, description)
    ↓
Pagination (12 per page)
    ↓
Tampilkan grid lowongan:
  - Job title
  - Division & Position
  - Location
  - Employment type
  - Salary range (jika ditampilkan)
  - Deadline
  - "Lihat Detail" button
    ↓
User klik "Lihat Detail"
    ↓
Redirect ke /jobs/{id}
```

**Kode Controller**:
```php
public function index(Request $request)
{
    $query = JobPosting::query()
        ->with(['position', 'division', 'location'])
        ->where('status', 'active')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->where('closed_at', '>=', now());

    // Filter by division
    if ($request->filled('division')) {
        $query->where('division_id', $request->division);
    }

    // Filter by location
    if ($request->filled('location')) {
        $query->where('location_id', $request->location);
    }

    // Filter by employment type
    if ($request->filled('type')) {
        $query->where('employment_type', $request->type);
    }

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('requirements', 'like', "%{$search}%");
        });
    }

    // Sort
    $query->orderBy('published_at', 'desc');

    // Paginate
    $jobs = $query->paginate(12);

    // Get filter options
    $divisions = Division::where('is_active', true)->get();
    $locations = Location::where('is_active', true)->get();

    return view('public.jobs.index', compact('jobs', 'divisions', 'locations'));
}
```

**Filter Sidebar**:
```html
<aside class="w-full lg:w-1/4">
    <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
        
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium mb-2">Cari Lowongan</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Kata kunci..."
                class="w-full border rounded px-3 py-2"
            >
        </div>

        <!-- Division Filter -->
        <div>
            <label class="block text-sm font-medium mb-2">Divisi</label>
            <select name="division" class="w-full border rounded px-3 py-2">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" 
                        {{ request('division') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Location Filter -->
        <div>
            <label class="block text-sm font-medium mb-2">Lokasi</label>
            <select name="location" class="w-full border rounded px-3 py-2">
                <option value="">Semua Lokasi</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}"
                        {{ request('location') == $location->id ? 'selected' : '' }}>
                        {{ $location->city }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Employment Type Filter -->
        <div>
            <label class="block text-sm font-medium mb-2">Tipe Pekerjaan</label>
            <select name="type" class="w-full border rounded px-3 py-2">
                <option value="">Semua Tipe</option>
                <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>
                    Full Time
                </option>
                <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>
                    Part Time
                </option>
                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>
                    Contract
                </option>
                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>
                    Internship
                </option>
            </select>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
            <i class="fas fa-search mr-2"></i>
            Filter
        </button>

        <!-- Reset Button -->
        <a 
            href="{{ route('jobs.index') }}"
            class="block w-full text-center border border-gray-300 py-2 rounded hover:bg-gray-50"
        >
            Reset Filter
        </a>
    </form>
</aside>
```

**Job Card Component**:
```html
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jobs as $job)
        <div class="bg-white border rounded-lg shadow hover:shadow-lg transition p-6">
            <!-- Job Code & Type Badge -->
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs text-gray-500 font-mono">{{ $job->code }}</span>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                    {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                </span>
            </div>

            <!-- Job Title -->
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ $job->title }}
            </h3>

            <!-- Position & Division -->
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <i class="fas fa-briefcase mr-2"></i>
                {{ $job->position->name }}
            </div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <i class="fas fa-building mr-2"></i>
                {{ $job->division->name }}
            </div>

            <!-- Location -->
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <i class="fas fa-map-marker-alt mr-2"></i>
                {{ $job->location->city }}
            </div>

            <!-- Experience Level -->
            <div class="flex items-center text-sm text-gray-600 mb-3">
                <i class="fas fa-layer-group mr-2"></i>
                {{ ucfirst($job->experience_level) }} Level
            </div>

            <!-- Salary Range (Optional) -->
            @if($job->salary_min && $job->salary_max)
                <div class="text-sm text-green-600 font-semibold mb-3">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    {{ $job->salary_currency }} {{ number_format($job->salary_min, 0, ',', '.') }} 
                    - {{ number_format($job->salary_max, 0, ',', '.') }}
                </div>
            @endif

            <!-- Deadline -->
            <div class="text-xs text-gray-500 mb-4">
                <i class="far fa-calendar mr-1"></i>
                Deadline: {{ \Carbon\Carbon::parse($job->closed_at)->format('d M Y') }}
            </div>

            <!-- Action Button -->
            <a 
                href="{{ route('jobs.show', $job->id) }}"
                class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
            >
                Lihat Detail
            </a>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-briefcase text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Tidak ada lowongan yang tersedia saat ini.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $jobs->links() }}
</div>
```

---

### 8.2 Job Detail Page

**Route**: `GET /jobs/{id}`

**Controller**: `PublicJobController@show`

**View**: `resources/views/public/jobs/show.blade.php`

**Flow Diagram**:
```
User akses /jobs/{id}
    ↓
Query job posting by ID
    ↓
Check if job is active & published
    ├── NO → 404 Not Found
    │
    └── YES
        ↓
        Load relationships:
          - Position
          - Division
          - Location
        ↓
        If user logged in as candidate:
          - Check if already applied
          - Show "Lamar Sekarang" or "Sudah Melamar"
        ↓
        If user not logged in or other role:
          - Show "Login untuk Melamar"
        ↓
        Display job details:
          - Job info
          - Description
          - Requirements
          - Responsibilities
          - Benefits
          - Apply button (conditional)
```

**Kode Controller**:
```php
public function show($id)
{
    $job = JobPosting::with(['position', 'division', 'location', 'created_by'])
        ->where('status', 'active')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->findOrFail($id);

    // Check if already applied (for logged in candidates)
    $hasApplied = false;
    if (auth()->check() && auth()->user()->role->name === 'candidate') {
        $hasApplied = $job->applications()
            ->where('candidate_id', auth()->id())
            ->exists();
    }

    return view('public.jobs.show', compact('job', 'hasApplied'));
}
```

**View Layout**:
```html
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline mr-4">
            <i class="fas fa-home mr-1"></i> Beranda
        </a>
        <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Lowongan
        </a>
    </div>

    <!-- Job Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <span class="text-sm text-gray-500 font-mono">{{ $job->code }}</span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mt-1">
                    {{ $job->title }}
                </h1>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                {{ $job->status === 'active' ? 'Aktif' : ucfirst($job->status) }}
            </span>
        </div>

        <!-- Job Meta Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="flex items-center text-gray-700">
                <i class="fas fa-briefcase w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Posisi</div>
                    <div class="font-medium">{{ $job->position->name }}</div>
                </div>
            </div>
            
            <div class="flex items-center text-gray-700">
                <i class="fas fa-building w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Divisi</div>
                    <div class="font-medium">{{ $job->division->name }}</div>
                </div>
            </div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-map-marker-alt w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Lokasi</div>
                    <div class="font-medium">{{ $job->location->city }}, {{ $job->location->province }}</div>
                </div>
            </div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-clock w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Tipe Pekerjaan</div>
                    <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</div>
                </div>
            </div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-layer-group w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Level Pengalaman</div>
                    <div class="font-medium">{{ ucfirst($job->experience_level) }}</div>
                </div>
            </div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-users w-5 mr-3 text-blue-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Jumlah Lowongan</div>
                    <div class="font-medium">{{ $job->quota }} posisi</div>
                </div>
            </div>

            @if($job->salary_min && $job->salary_max)
                <div class="flex items-center text-gray-700">
                    <i class="fas fa-money-bill-wave w-5 mr-3 text-green-600"></i>
                    <div>
                        <div class="text-xs text-gray-500">Gaji</div>
                        <div class="font-medium text-green-600">
                            {{ $job->salary_currency }} {{ number_format($job->salary_min, 0, ',', '.') }} 
                            - {{ number_format($job->salary_max, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center text-gray-700">
                <i class="far fa-calendar w-5 mr-3 text-red-600"></i>
                <div>
                    <div class="text-xs text-gray-500">Deadline Lamaran</div>
                    <div class="font-medium text-red-600">
                        {{ \Carbon\Carbon::parse($job->closed_at)->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Apply Button -->
        <div class="border-t pt-6">
            @guest
                <a href="{{ route('login') }}" 
                   class="block w-full sm:w-auto text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login untuk Melamar
                </a>
            @else
                @if(auth()->user()->role->name === 'candidate')
                    @if($hasApplied)
                        <button disabled
                                class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed">
                            <i class="fas fa-check-circle mr-2"></i>
                            Anda Sudah Melamar
                        </button>
                    @else
                        <a href="{{ route('jobs.apply', $job->id) }}"
                           class="block w-full sm:w-auto text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Lamar Sekarang
                        </a>
                    @endif
                @else
                    <p class="text-gray-500 italic">
                        <i class="fas fa-info-circle mr-2"></i>
                        Fitur melamar hanya tersedia untuk kandidat.
                    </p>
                @endif
            @endguest
        </div>
    </div>

    <!-- Job Description -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-file-alt text-blue-600 mr-2"></i>
            Deskripsi Pekerjaan
        </h2>
        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($job->description)) !!}
        </div>
    </div>

    <!-- Requirements -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-check-circle text-blue-600 mr-2"></i>
            Kualifikasi
        </h2>
        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($job->requirements)) !!}
        </div>
    </div>

    <!-- Responsibilities -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-tasks text-blue-600 mr-2"></i>
            Tanggung Jawab
        </h2>
        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($job->responsibilities)) !!}
        </div>
    </div>

    <!-- Benefits -->
    @if($job->benefits)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-gift text-blue-600 mr-2"></i>
                Benefit
            </h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($job->benefits)) !!}
            </div>
        </div>
    @endif

    <!-- Footer CTA -->
    <div class="bg-blue-50 rounded-lg p-6 text-center">
        <p class="text-gray-700 mb-4">
            Tertarik dengan posisi ini? Jangan lewatkan kesempatan ini!
        </p>
        @guest
            <a href="{{ route('login') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                Login untuk Melamar
            </a>
        @else
            @if(auth()->user()->role->name === 'candidate' && !$hasApplied)
                <a href="{{ route('jobs.apply', $job->id) }}"
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                    Lamar Sekarang
                </a>
            @endif
        @endguest
    </div>
</div>
```

---

## 9. ALUR MELAMAR PEKERJAAN

### 9.1 Application Flow Diagram

```
Candidate klik "Lamar Sekarang"
    ↓
Redirect ke /jobs/{id}/apply
    ↓
Check prerequisites:
  - User logged in? (middleware auth)
  - Role = candidate? (middleware role)
  - Registration completed? (middleware registration.completed)
  - Already applied? (check database)
    ├── Already applied → Redirect back with error
    │
    └── OK, proceed
        ↓
        Tampilkan form lamaran:
          - Auto-fill from user profile
          - Upload CV (required)
          - Cover letter (optional)
          - Portfolio (optional)
          - Other documents (optional)
        ↓
        User submit form
        ↓
        POST /jobs/{id}/apply
        ↓
        Validate:
          - All required fields filled
          - CV file uploaded
          - File size & type valid
        ↓
        Store files to storage
        ↓
        Create application record:
          - status = 'submitted'
          - Auto-fill from user data + JSON
        ↓
        Send notification to HR
        ↓
        Create notification for candidate
        ↓
        Log audit
        ↓
        Redirect to /candidate/applications
        ↓
        Show success message
```

### 9.2 Apply Form Page

**Route**: `GET /jobs/{id}/apply`

**Controller**: `PublicJobController@apply`

**View**: `resources/views/public/jobs/apply.blade.php`

**Kode Controller**:
```php
public function apply($id)
{
    $job = JobPosting::with(['position', 'division', 'location'])
        ->where('status', 'active')
        ->findOrFail($id);

    $user = auth()->user();

    // Check if already applied
    $hasApplied = $job->applications()
        ->where('candidate_id', $user->id)
        ->exists();

    if ($hasApplied) {
        return redirect()->route('jobs.show', $job->id)
            ->with('error', 'Anda sudah melamar lowongan ini sebelumnya.');
    }

    // Get user data
    $education = json_decode($user->education, true) ?? [];
    $experience = json_decode($user->experience, true) ?? [];
    $documents = json_decode($user->documents, true) ?? [];

    return view('public.jobs.apply', compact('job', 'user', 'education', 'experience', 'documents'));
}
```

**Form View**:
```html
<form method="POST" action="{{ route('jobs.apply.store', $job->id) }}" 
      enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Job Info (Read-only) -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-semibold text-lg mb-2">Melamar untuk:</h3>
        <p class="text-xl font-bold text-blue-900">{{ $job->title }}</p>
        <p class="text-sm text-gray-600">{{ $job->division->name }} - {{ $job->location->city }}</p>
    </div>

    <!-- Personal Data (Auto-filled, can be edited) -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Data Pribadi</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('full_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('phone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('birth_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Alamat</label>
                <textarea name="address" rows="3" required 
                          class="w-full border rounded px-3 py-2">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                <select name="gender" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih</option>
                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
                @error('gender')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Education (Auto-filled from registration) -->
    <input type="hidden" name="education" value="{{ json_encode($education) }}">

    <!-- Experience (Auto-filled from registration) -->
    <input type="hidden" name="experience" value="{{ json_encode($experience) }}">

    <!-- Document Upload -->
    <div class="bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Dokumen</h3>

        <!-- CV Upload -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Upload CV <span class="text-red-600">*</span>
            </label>
            <input type="file" name="cv_file" accept=".pdf" required 
                   class="w-full border rounded px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">Format: PDF, Max: 5MB</p>
            @error('cv_file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Cover Letter -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Surat Lamaran (Opsional)</label>
            <textarea name="cover_letter" rows="6" 
                      class="w-full border rounded px-3 py-2" 
                      placeholder="Tuliskan surat lamaran Anda...">{{ old('cover_letter') }}</textarea>
            @error('cover_letter')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Portfolio Upload -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Portfolio (Opsional)</label>
            <input type="file" name="portfolio_file" accept=".pdf,.zip" 
                   class="w-full border rounded px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">Format: PDF atau ZIP, Max: 10MB</p>
            @error('portfolio_file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Other Documents -->
        <div>
            <label class="block text-sm font-medium mb-1">Dokumen Lainnya (Opsional)</label>
            <input type="file" name="other_documents[]" multiple 
                   accept=".pdf,.jpg,.jpeg,.png,.zip"
                   class="w-full border rounded px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">
                Format: PDF, JPG, PNG, ZIP. Max: 5MB per file. Bisa upload multiple.
            </p>
            @error('other_documents.*')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Terms & Conditions -->
    <div class="bg-white border rounded-lg p-6">
        <label class="flex items-start">
            <input type="checkbox" name="agree_terms" value="1" required 
                   class="mt-1 mr-3">
            <span class="text-sm text-gray-700">
                Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat 
                dipertanggungjawabkan. Saya bersedia untuk mengikuti seluruh proses 
                rekrutmen yang ditetapkan oleh perusahaan.
            </span>
        </label>
        @error('agree_terms')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('jobs.show', $job->id) }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" 
                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Lamaran
        </button>
    </div>
</form>
```

---

### 9.3 Submit Application

**Route**: `POST /jobs/{id}/apply`

**Controller**: `Candidate\ApplicationController@store`

> **💡 PENTING - Snapshot Approach (Nov 2025)**:  
> Saat kandidat submit application, sistem membuat **snapshot** dari data profil user saat itu.  
> Snapshot disimpan dalam field JSON `candidate_snapshot` untuk menjaga data historis.  
> Jika kandidat update profil setelah melamar, data di application tetap menunjukkan kondisi saat apply.

**Kode Controller**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'job_posting_id' => 'required|exists:job_postings,id',
        'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        'portfolio' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'cover_letter' => 'nullable|string',
        'agree_terms' => 'required|accepted',
    ]);

    // Check if already applied
    $existingApplication = Application::where('candidate_id', auth()->id())
        ->where('job_posting_id', $request->job_posting_id)
        ->first();

    if ($existingApplication) {
        return redirect()
            ->route('candidate.applications.show', $existingApplication->id)
            ->with('info', 'Anda sudah melamar untuk posisi ini.');
    }

    $user = auth()->user();

    // Upload CV
    $cvPath = $this->fileUploadService->uploadCV(
        $request->file('cv'),
        $user->full_name
    );

    // Upload Portfolio (optional)
    $portfolioPath = null;
    if ($request->hasFile('portfolio')) {
        $portfolioPath = $this->fileUploadService->uploadPortfolio(
            $request->file('portfolio'),
            $user->full_name
        );
    }

    // Generate application code
    $applicationCode = 'APP-' . strtoupper(Str::random(8));

    // ✅ Buat snapshot data kandidat saat apply
    $candidateSnapshot = [
        'full_name' => $user->full_name,
        'email' => $user->email,
        'phone' => $user->phone,
        'address' => $user->address,
        'birth_date' => $user->birth_date,
        'gender' => $user->gender,
        'education' => $user->education ?? [], // JSON dari users table
        'experience' => $user->experience ?? [], // JSON dari users table
        'profile_photo' => $user->profile_photo,
        'snapshot_at' => now()->toDateTimeString(), // Timestamp snapshot
    ];

    // Create application
    $application = Application::create([
        'candidate_id' => $user->id,
        'job_posting_id' => $validated['job_posting_id'],
        'application_code' => $applicationCode,
        'candidate_snapshot' => $candidateSnapshot, // ✅ Simpan snapshot
        'cv_file' => $cvPath,
        'portfolio_file' => $portfolioPath,
        'cover_letter' => $validated['cover_letter'],
        'status' => 'submitted',
    ]);

    // Create audit log
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'application_submitted',
        'model_type' => 'Application',
        'model_id' => $application->id,
        'description' => 'Kandidat mengirim lamaran untuk posisi: ' . $application->jobPosting->title,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // TODO: Send notification to candidate and HR

    return redirect()
        ->route('candidate.applications.show', $application->id)
        ->with('success', 'Lamaran Anda berhasil dikirim! Kode lamaran: ' . $applicationCode);
}
```

**Penjelasan Snapshot**:
1. **Data Dikumpulkan**: Semua data profil user saat submit (full_name, email, phone, education, experience, dll)
2. **Disimpan sebagai JSON**: Dalam field `candidate_snapshot` di tabel applications
3. **Timestamp Added**: Untuk tracking kapan snapshot diambil
4. **Immutable**: Data snapshot tidak berubah meskipun user update profil
5. **Accessor Methods**: Model Application punya accessor untuk akses mudah (e.g., `$application->candidate_name`)

**Keuntungan**:
- ✅ HR melihat data asli saat kandidat apply (historical accuracy)
- ✅ Audit trail lengkap untuk compliance
- ✅ Tidak ada sync issue jika kandidat update profil
- ✅ Bisa compare data snapshot vs data terkini
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    return $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}
```

**Application Code Format**: `APP-{JOB_CODE}-{NUMBER}`
- Contoh: `APP-SE-001-0001`, `APP-SE-001-0002`, `APP-FE-003-0001`

---

## 10. ALUR DASHBOARD KANDIDAT

### 10.1 Dashboard Overview

**Route**: `GET /candidate/dashboard`

**Controller**: `Candidate\DashboardController@index`

**View**: `resources/views/candidate/dashboard.blade.php`

**Flow Diagram**:
```
Candidate login & akses /candidate/dashboard
    ↓
Load data:
  - Total lamaran
  - Status breakdown (submitted, screening, interview, dll)
  - Recent applications (5 terbaru)
  - Active job recommendations
  - Profile completion percentage
    ↓
Display dashboard:
  - Welcome message
  - Statistics cards
  - Recent applications table
  - Quick actions
  - Job recommendations
```

**Kode Controller**:
```php
public function index()
{
    $user = auth()->user();

    // Get statistics
    $stats = [
        'total_applications' => Application::where('candidate_id', $user->id)->count(),
        'submitted' => Application::where('candidate_id', $user->id)
            ->where('status', 'submitted')->count(),
        'screening' => Application::where('candidate_id', $user->id)
            ->whereIn('status', ['screening_passed', 'interview_scheduled'])->count(),
        'interview' => Application::where('candidate_id', $user->id)
            ->where('status', 'interview_passed')->count(),
        'offered' => Application::where('candidate_id', $user->id)
            ->where('status', 'offered')->count(),
        'hired' => Application::where('candidate_id', $user->id)
            ->where('status', 'hired')->count(),
        'rejected' => Application::where('candidate_id', $user->id)
            ->whereIn('status', ['rejected_admin', 'rejected_interview', 'rejected_offer'])->count(),
    ];

    // Recent applications
    $recentApplications = Application::with(['jobPosting.position', 'jobPosting.division'])
        ->where('candidate_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Active job recommendations (based on user's experience/education)
    $recommendations = JobPosting::with(['position', 'division', 'location'])
        ->where('status', 'active')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->where('closed_at', '>=', now())
        ->whereDoesntHave('applications', function($q) use ($user) {
            $q->where('candidate_id', $user->id);
        })
        ->limit(4)
        ->get();

    // Profile completion
    $profileCompletion = $this->calculateProfileCompletion($user);

    // Unread notifications
    $unreadNotifications = Notification::where('user_id', $user->id)
        ->whereNull('read_at')
        ->count();

    return view('candidate.dashboard', compact(
        'stats',
        'recentApplications',
        'recommendations',
        'profileCompletion',
        'unreadNotifications'
    ));
}

protected function calculateProfileCompletion($user)
{
    $fields = [
        'name' => !empty($user->name),
        'email' => !empty($user->email),
        'phone' => !empty($user->phone),
        'address' => !empty($user->address),
        'birth_date' => !empty($user->birth_date),
        'gender' => !empty($user->gender),
        'profile_photo' => !empty($user->profile_photo),
        'education' => !empty($user->education),
        'experience' => !empty($user->experience),
        'documents' => !empty($user->documents),
    ];

    $completed = count(array_filter($fields));
    $total = count($fields);

    return round(($completed / $total) * 100);
}
```

**Dashboard View**:
```html
<div class="container mx-auto px-4 py-6">
    
    <!-- Welcome Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Selamat Datang, {{ $user->name }}!
        </h1>
        <p class="text-gray-600 mt-1">
            Kelola lamaran Anda dan temukan pekerjaan impian.
        </p>
    </div>

    <!-- Profile Completion Alert -->
    @if($profileCompletion < 100)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm text-yellow-700">
                        Profil Anda {{ $profileCompletion }}% lengkap. 
                        <a href="{{ route('candidate.profile.edit') }}" class="font-semibold underline">
                            Lengkapi profil
                        </a> untuk meningkatkan peluang diterima.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Lamaran</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Dalam Proses</p>
                    <p class="text-3xl font-bold text-yellow-600">
                        {{ $stats['submitted'] + $stats['screening'] + $stats['interview'] }}
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-spinner text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Offered -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Penawaran</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['offered'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-handshake text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Lamaran Terbaru</h2>
                <a href="{{ route('candidate.applications.index') }}" 
                   class="text-blue-600 hover:underline text-sm">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Posisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Divisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentApplications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono">{{ $app->code }}</td>
                            <td class="px-6 py-4 text-sm">{{ $app->jobPosting->title }}</td>
                            <td class="px-6 py-4 text-sm">{{ $app->jobPosting->division->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $app->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $app->status === 'submitted' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $app->status === 'screening_passed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $app->status === 'offered' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ str_contains($app->status, 'rejected') ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('candidate.applications.show', $app->id) }}" 
                                   class="text-blue-600 hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada lamaran. 
                                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:underline">
                                    Cari lowongan sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Job Recommendations -->
    @if($recommendations->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Rekomendasi Lowongan</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                @foreach($recommendations as $job)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <h3 class="font-semibold text-lg mb-2">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $job->division->name }} - {{ $job->location->city }}
                        </p>
                        <p class="text-sm text-gray-500 mb-3">
                            Deadline: {{ \Carbon\Carbon::parse($job->closed_at)->format('d M Y') }}
                        </p>
                        <a href="{{ route('jobs.show', $job->id) }}" 
                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
```

---

# PART 4: ALUR HR

## 11. ALUR MEMBUAT LOWONGAN

### 11.1 Create Job Posting Flow Diagram

```
HR login & akses /hr/dashboard
    ↓
Klik menu "Lowongan Pekerjaan"
    ↓
Tampilkan daftar job postings
    ↓
Klik tombol "Buat Lowongan Baru"
    ↓
Redirect ke /hr/job-postings/create
    ↓
Tampilkan form create job posting:
  - Job Info (Title, Position, Division, Location)
  - Details (Description, Requirements, Responsibilities)
  - Employment Info (Type, Level, Quota, Salary)
  - Timeline (Published Date, Deadline)
  - Benefits
  - Status (draft/active)
    ↓
User submit form
    ↓
POST /hr/job-postings
    ↓
Validasi semua field
    ↓
Generate job code otomatis
    ↓
Simpan ke database
    ↓
Log audit
    ↓
Redirect ke /hr/job-postings
    ↓
Show success message
```

### 11.2 Job Posting Index (List)

**Route**: `GET /hr/job-postings`

**Controller**: `HR\JobPostingController@index`

**View**: `resources/views/hr/job-postings/index.blade.php`

**Kode Controller**:
```php
public function index(Request $request)
{
    $query = JobPosting::with(['position', 'division', 'location', 'created_by'])
        ->orderBy('created_at', 'desc');

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by division
    if ($request->filled('division')) {
        $query->where('division_id', $request->division);
    }

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('title', 'like', "%{$search}%");
        });
    }

    $jobPostings = $query->paginate(15);

    // Get filter options
    $divisions = Division::where('is_active', true)->get();
    $statuses = ['draft', 'active', 'closed', 'archived'];

    return view('hr.job-postings.index', compact('jobPostings', 'divisions', 'statuses'));
}
```

**View dengan Action Buttons**:
```html
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Lowongan Pekerjaan</h2>
        <a href="{{ route('hr.job-postings.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Buat Lowongan Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Cari kode/judul..." 
                   value="{{ request('search') }}"
                   class="border rounded px-3 py-2">
            
            <select name="division" class="border rounded px-3 py-2">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $div)
                    <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>
                        {{ $div->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($jobPostings as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono">{{ $job->code }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $job->title }}</td>
                        <td class="px-6 py-4 text-sm">{{ $job->division->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $job->location->city }}</td>
                        <td class="px-6 py-4 text-sm">{{ $job->quota }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ \Carbon\Carbon::parse($job->closed_at)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $job->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $job->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $job->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $job->status === 'archived' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('hr.job-postings.show', $job->id) }}" 
                                   class="text-blue-600 hover:underline" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('hr.job-postings.edit', $job->id) }}" 
                                   class="text-yellow-600 hover:underline" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('hr.job-postings.destroy', $job->id) }}" 
                                      class="inline" onsubmit="return confirm('Yakin hapus lowongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t">
        {{ $jobPostings->links() }}
    </div>
</div>
```

---

### 11.3 Create Job Posting Form

**Route**: `GET /hr/job-postings/create`

**Controller**: `HR\JobPostingController@create`

**View**: `resources/views/hr/job-postings/create.blade.php`

**Kode Controller**:
```php
public function create()
{
    $divisions = Division::where('is_active', true)->get();
    $positions = Position::where('is_active', true)->get();
    $locations = Location::where('is_active', true)->get();

    return view('hr.job-postings.create', compact('divisions', 'positions', 'locations'));
}
```

**Form View** (Panjang, dengan semua field):
```html
<form method="POST" action="{{ route('hr.job-postings.store') }}" class="space-y-6">
    @csrf

    <!-- Job Information Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Informasi Lowongan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Job Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">
                    Judul Lowongan <span class="text-red-600">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" 
                       required class="w-full border rounded px-3 py-2"
                       placeholder="e.g. Senior Software Engineer">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Posisi <span class="text-red-600">*</span>
                </label>
                <select name="position_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Posisi</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->name }} ({{ $position->code }})
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Division -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Divisi <span class="text-red-600">*</span>
                </label>
                <select name="division_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Divisi</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
                @error('division_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Lokasi <span class="text-red-600">*</span>
                </label>
                <select name="location_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Lokasi</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->city }}, {{ $location->province }}
                        </option>
                    @endforeach
                </select>
                @error('location_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Employment Type -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Tipe Pekerjaan <span class="text-red-600">*</span>
                </label>
                <select name="employment_type" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Tipe</option>
                    <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>
                        Full Time
                    </option>
                    <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>
                        Part Time
                    </option>
                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>
                        Contract
                    </option>
                    <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>
                        Internship
                    </option>
                </select>
                @error('employment_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Job Details Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Detail Pekerjaan</h3>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Deskripsi Pekerjaan <span class="text-red-600">*</span>
            </label>
            <textarea name="description" rows="5" required 
                      class="w-full border rounded px-3 py-2"
                      placeholder="Jelaskan tentang pekerjaan ini...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Requirements -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Kualifikasi <span class="text-red-600">*</span>
            </label>
            <textarea name="requirements" rows="5" required 
                      class="w-full border rounded px-3 py-2"
                      placeholder="Tuliskan kualifikasi yang dibutuhkan (pisahkan dengan enter)...">{{ old('requirements') }}</textarea>
            @error('requirements')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Responsibilities -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Tanggung Jawab <span class="text-red-600">*</span>
            </label>
            <textarea name="responsibilities" rows="5" required 
                      class="w-full border rounded px-3 py-2"
                      placeholder="Tuliskan tanggung jawab pekerjaan...">{{ old('responsibilities') }}</textarea>
            @error('responsibilities')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Benefits -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Benefit (Opsional)
            </label>
            <textarea name="benefits" rows="4" 
                      class="w-full border rounded px-3 py-2"
                      placeholder="Tuliskan benefit yang ditawarkan...">{{ old('benefits') }}</textarea>
            @error('benefits')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Employment Details Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Detail Kepegawaian</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Experience Level -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Level Pengalaman <span class="text-red-600">*</span>
                </label>
                <select name="experience_level" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Level</option>
                    <option value="entry" {{ old('experience_level') == 'entry' ? 'selected' : '' }}>
                        Entry Level
                    </option>
                    <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>
                        Junior
                    </option>
                    <option value="mid" {{ old('experience_level') == 'mid' ? 'selected' : '' }}>
                        Mid Level
                    </option>
                    <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>
                        Senior
                    </option>
                    <option value="lead" {{ old('experience_level') == 'lead' ? 'selected' : '' }}>
                        Lead
                    </option>
                    <option value="manager" {{ old('experience_level') == 'manager' ? 'selected' : '' }}>
                        Manager
                    </option>
                </select>
                @error('experience_level')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quota -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Jumlah Lowongan <span class="text-red-600">*</span>
                </label>
                <input type="number" name="quota" value="{{ old('quota', 1) }}" 
                       required min="1" class="w-full border rounded px-3 py-2">
                @error('quota')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Salary Min -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Gaji Minimum (Opsional)
                </label>
                <input type="number" name="salary_min" value="{{ old('salary_min') }}" 
                       class="w-full border rounded px-3 py-2" placeholder="5000000">
                @error('salary_min')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Salary Max -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Gaji Maximum (Opsional)
                </label>
                <input type="number" name="salary_max" value="{{ old('salary_max') }}" 
                       class="w-full border rounded px-3 py-2" placeholder="10000000">
                @error('salary_max')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Salary Currency -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Mata Uang
                </label>
                <select name="salary_currency" class="w-full border rounded px-3 py-2">
                    <option value="IDR" {{ old('salary_currency', 'IDR') == 'IDR' ? 'selected' : '' }}>
                        IDR (Rupiah)
                    </option>
                    <option value="USD" {{ old('salary_currency') == 'USD' ? 'selected' : '' }}>
                        USD (Dollar)
                    </option>
                </select>
                @error('salary_currency')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timeline</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Published Date -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Tanggal Publikasi <span class="text-red-600">*</span>
                </label>
                <input type="date" name="published_at" 
                       value="{{ old('published_at', date('Y-m-d')) }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('published_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deadline -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Deadline Lamaran <span class="text-red-600">*</span>
                </label>
                <input type="date" name="closed_at" 
                       value="{{ old('closed_at') }}" 
                       required class="w-full border rounded px-3 py-2">
                @error('closed_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Status Lowongan <span class="text-red-600">*</span>
                </label>
                <select name="status" required class="w-full border rounded px-3 py-2">
                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>
                        Closed
                    </option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('hr.job-postings.index') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" 
                class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            <i class="fas fa-save mr-2"></i>
            Simpan Lowongan
        </button>
    </div>
</form>
```

---

### 11.4 Store Job Posting

**Route**: `POST /hr/job-postings`

**Controller**: `HR\JobPostingController@store`

**Kode Controller**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'position_id' => 'required|exists:positions,id',
        'division_id' => 'required|exists:divisions,id',
        'location_id' => 'required|exists:locations,id',
        'employment_type' => 'required|in:full_time,part_time,contract,internship',
        'description' => 'required|string',
        'requirements' => 'required|string',
        'responsibilities' => 'required|string',
        'benefits' => 'nullable|string',
        'experience_level' => 'required|in:entry,junior,mid,senior,lead,manager',
        'quota' => 'required|integer|min:1',
        'salary_min' => 'nullable|numeric|min:0',
        'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
        'salary_currency' => 'nullable|in:IDR,USD',
        'published_at' => 'required|date',
        'closed_at' => 'required|date|after_or_equal:published_at',
        'status' => 'nullable|in:draft,active,closed,archived',
    ]);

    // Generate unique job code
    $validated['code'] = $this->generateJobCode($validated['position_id']);
    
    // Set created_by
    $validated['created_by'] = auth()->id();

    // Create job posting
    $jobPosting = JobPosting::create($validated);

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model_type' => 'JobPosting',
        'model_id' => $jobPosting->id,
        'new_data' => json_encode($jobPosting->toArray()),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('hr.job-postings.index')
        ->with('success', 'Lowongan pekerjaan berhasil dibuat dengan kode: ' . $jobPosting->code);
}

protected function generateJobCode($positionId)
{
    $position = Position::find($positionId);
    $prefix = strtoupper($position->code);
    
    // Get existing codes including soft deleted
    $existingCodes = JobPosting::withTrashed()
        ->where('code', 'like', $prefix . '-%')
        ->pluck('code')
        ->toArray();
    
    // Extract numbers
    $existingNumbers = [];
    foreach ($existingCodes as $code) {
        $parts = explode('-', $code);
        if (count($parts) >= 2) {
            $number = (int) end($parts);
            $existingNumbers[] = $number;
        }
    }
    
    // Generate new number
    $newNumber = empty($existingNumbers) ? 1 : max($existingNumbers) + 1;
    $newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
    return $newCode;
}
```

---

### 11.5 Edit & Update Job Posting

**Route**: `GET /hr/job-postings/{id}/edit` dan `PUT /hr/job-postings/{id}`

**Controller**: `HR\JobPostingController@edit` dan `HR\JobPostingController@update`

**Kode Controller Edit**:
```php
public function edit($id)
{
    $jobPosting = JobPosting::findOrFail($id);
    $job = $jobPosting; // Alias untuk kompatibilitas view

    $divisions = Division::where('is_active', true)->get();
    $positions = Position::where('is_active', true)->get();
    $locations = Location::where('is_active', true)->get();

    return view('hr.job-postings.edit', compact('jobPosting', 'job', 'divisions', 'positions', 'locations'));
}
```

**Kode Controller Update**:
```php
public function update(Request $request, $id)
{
    $jobPosting = JobPosting::findOrFail($id);

    // Store old data for audit
    $oldData = $jobPosting->toArray();

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'position_id' => 'required|exists:positions,id',
        'division_id' => 'required|exists:divisions,id',
        'location_id' => 'required|exists:locations,id',
        'employment_type' => 'required|in:full_time,part_time,contract,internship',
        'description' => 'required|string',
        'requirements' => 'required|string',
        'responsibilities' => 'required|string',
        'benefits' => 'nullable|string',
        'experience_level' => 'required|in:entry,junior,mid,senior,lead,manager',
        'quota' => 'required|integer|min:1',
        'salary_min' => 'nullable|numeric|min:0',
        'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
        'salary_currency' => 'nullable|in:IDR,USD',
        'published_at' => 'required|date',
        'closed_at' => 'required|date|after_or_equal:published_at',
        'status' => 'nullable|in:draft,active,closed,archived',
    ]);

    // If position changed, regenerate code
    if ($jobPosting->position_id != $validated['position_id']) {
        $validated['code'] = $this->generateJobCode($validated['position_id']);
    }

    // Update
    $jobPosting->update($validated);

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'update',
        'model_type' => 'JobPosting',
        'model_id' => $jobPosting->id,
        'old_data' => json_encode($oldData),
        'new_data' => json_encode($jobPosting->fresh()->toArray()),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('hr.job-postings.index')
        ->with('success', 'Lowongan pekerjaan berhasil diupdate.');
}
```

---

## 12. ALUR MENGELOLA LAMARAN

### 12.1 Application List for HR

**Route**: `GET /hr/applications`

**Controller**: `HR\ApplicationController@index`

**View**: `resources/views/hr/applications/index.blade.php`

**Flow Diagram**:
```
HR akses /hr/applications
    ↓
Query all applications dengan filters:
  - Job posting
  - Status
  - Date range
  - Search kandidat
    ↓
Load dengan pagination
    ↓
Tampilkan tabel applications:
  - Application code
  - Candidate name
  - Job title
  - Status
  - Applied date
  - Action buttons (View, Review, Interview)
    ↓
HR klik action button
    ├── View → Detail lamaran
    ├── Review → Screening (Accept/Reject)
    └── Interview → Schedule interview
```

**Kode Controller**:
```php
public function index(Request $request)
{
    $query = Application::with(['candidate', 'jobPosting.position', 'jobPosting.division'])
        ->orderBy('created_at', 'desc');

    // Filter by job posting
    if ($request->filled('job_posting')) {
        $query->where('job_posting_id', $request->job_posting);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // Search by candidate name or email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    $applications = $query->paginate(20);

    // Get filter options
    $jobPostings = JobPosting::where('status', 'active')->get();
    $statuses = [
        'submitted', 'screening_passed', 'rejected_admin',
        'interview_scheduled', 'interview_passed', 'rejected_interview',
        'offered', 'hired', 'rejected_offer', 'archived'
    ];

    return view('hr.applications.index', compact('applications', 'jobPostings', 'statuses'));
}
```

**View Table**:
```html
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">Lamaran Kandidat</h2>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="search" placeholder="Cari kandidat..." 
                   value="{{ request('search') }}"
                   class="border rounded px-3 py-2">
            
            <select name="job_posting" class="border rounded px-3 py-2">
                <option value="">Semua Lowongan</option>
                @foreach($jobPostings as $job)
                    <option value="{{ $job->id }}" {{ request('job_posting') == $job->id ? 'selected' : '' }}>
                        {{ $job->code }} - {{ $job->title }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" placeholder="Dari Tanggal" 
                   value="{{ request('date_from') }}"
                   class="border rounded px-3 py-2">

            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kandidat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lowongan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($applications as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono">{{ $app->application_code }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{-- ✅ Gunakan accessor dari snapshot --}}
                            <div class="font-medium">{{ $app->candidate_name }}</div>
                            <div class="text-gray-500 text-xs">{{ $app->candidate_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div>{{ $app->jobPosting->title }}</div>
                            <div class="text-gray-500 text-xs">{{ $app->jobPosting->job_code }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $app->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @include('partials.application-status-badge', ['status' => $app->status])
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('hr.applications.show', $app->id) }}" 
                                   class="text-blue-600 hover:underline" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($app->status === 'submitted')
                                    <a href="{{ route('hr.applications.review', $app->id) }}" 
                                       class="text-green-600 hover:underline" title="Review">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                @endif

                                @if(in_array($app->status, ['screening_passed', 'interview_scheduled']))
                                    <a href="{{ route('hr.interviews.schedule', $app->id) }}" 
                                       class="text-purple-600 hover:underline" title="Schedule Interview">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada lamaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t">
        {{ $applications->links() }}
    </div>
</div>
```

> **💡 NOTE**: View menggunakan **accessor methods** dari Model Application:
> - `$app->candidate_name` → Ambil dari snapshot `candidate_snapshot['full_name']`
> - `$app->candidate_email` → Ambil dari snapshot `candidate_snapshot['email']`
> - Data tetap menunjukkan kondisi saat kandidat apply, meskipun kandidat update profil

---

### 12.2 Application Detail & Review

**Route**: `GET /hr/applications/{id}`

**Controller**: `HR\ApplicationController@show`

**View**: `resources/views/hr/applications/show.blade.php`

**Kode Controller**:
```php
public function show($id)
{
    $application = Application::with([
        'candidate',
        'jobPosting.position',
        'jobPosting.division',
        'jobPosting.location',
        'interviews.interviewer',
        'offer'
    ])->findOrFail($id);

    // ✅ Ambil data dari snapshot
    $snapshot = $application->candidate_snapshot ?? [];
    
    // Check if profile changed
    $profileChanged = $application->hasProfileChangedSinceApply();
    
    // Get current candidate data for comparison
    $currentCandidate = $application->candidate;

    return view('hr.applications.show', compact(
        'application', 
        'snapshot', 
        'profileChanged',
        'currentCandidate'
    ));
}
```

> **💡 PENTING - Snapshot Comparison View**:  
> View `hr.applications.show` telah direfactor untuk menampilkan **perbandingan snapshot vs data terkini**.  
> Detail implementasi ada di file `REFACTOR_SNAPSHOT_APPROACH.md`.

**Key Features**:
1. **Warning Alert**: Tampil jika kandidat update profil setelah apply
2. **Side-by-Side Comparison**: Snapshot (data saat apply) vs data terkini
3. **Change Indicators**: Badge "Berubah" pada field yang berbeda
4. **Color Coding**: 
   - Blue box = Data saat melamar (snapshot)
   - Green box = Data terkini (jika berbeda)

**View Detail** (simplified - see actual implementation in `resources/views/hr/applications/show.blade.php`):
```html
<div class="max-w-6xl mx-auto p-6">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('hr.applications.index') }}" class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Lamaran
        </a>
    </div>

    {{-- ✅ Warning Alert jika profil berubah --}}
    @if($profileChanged)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                <div>
                    <p class="font-medium text-yellow-800">Profil Kandidat Telah Berubah</p>
                    <p class="text-sm text-yellow-700 mt-1">
                        Kandidat telah mengupdate profilnya setelah melamar. 
                        Data di bawah menunjukkan perbandingan antara data saat melamar vs data terkini.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                {{-- Gunakan accessor dari snapshot --}}
                <h1 class="text-2xl font-bold">{{ $application->candidate_name }}</h1>
                <p class="text-gray-600">{{ $application->candidate_email }} | {{ $application->candidate_phone }}</p>
                <p class="text-sm text-gray-500 font-mono mt-1">{{ $application->application_code }}</p>
            </div>
            <div class="text-right">
                @include('partials.application-status-badge', ['status' => $application->status])
                <p class="text-sm text-gray-500 mt-2">
                    Melamar: {{ $application->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ✅ Comparison View: Snapshot vs Current --}}
    <div class="grid grid-cols-1 {{ $profileChanged ? 'md:grid-cols-2' : '' }} gap-6 mb-6">
        
        {{-- Data Saat Melamar (Snapshot) --}}
        <div class="bg-blue-50 rounded-lg shadow p-6 border-2 border-blue-200">
            <h2 class="text-lg font-semibold mb-4 text-blue-800">
                <i class="fas fa-camera mr-2"></i>Data Saat Melamar
            </h2>
            <div class="text-sm text-gray-600 mb-4">
                Snapshot: {{ $snapshot['snapshot_at'] ?? '-' }}
            </div>
            
            {{-- Personal Data --}}
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p class="font-medium">{{ $snapshot['full_name'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p>{{ $snapshot['email'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p>{{ $snapshot['phone'] ?? '-' }}</p>
                </div>
                {{-- ... more fields ... --}}
            </div>
        </div>

        {{-- Data Terkini (if changed) --}}
        @if($profileChanged)
            <div class="bg-green-50 rounded-lg shadow p-6 border-2 border-green-200">
                <h2 class="text-lg font-semibold mb-4 text-green-800">
                    <i class="fas fa-user-clock mr-2"></i>Data Terkini
                </h2>
                <div class="text-sm text-gray-600 mb-4">
                    Terakhir update: {{ $currentCandidate->updated_at->format('d M Y H:i') }}
                </div>
                
                {{-- Personal Data with Change Indicators --}}
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-medium">
                            {{ $currentCandidate->full_name }}
                            @if($snapshot['full_name'] !== $currentCandidate->full_name)
                                <span class="ml-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Berubah</span>
                            @endif
                        </p>
                    </div>
                    {{-- ... more fields with change indicators ... --}}
                </div>
            </div>
        @endif
    </div>

    <!-- Documents -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Dokumen</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span>CV</span>
                <a href="{{ asset('storage/' . $application->cv_file) }}" 
                   target="_blank"
                   class="text-blue-600 hover:underline">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>
            @if($application->portfolio_file)
                <div class="flex justify-between items-center">
                    <span>Portfolio</span>
                    <a href="{{ asset('storage/' . $application->portfolio_file) }}" 
                       target="_blank"
                       class="text-blue-600 hover:underline">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                </div>
            @endif
        </div>
    </div>
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cover Letter -->
    @if($application->cover_letter)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Surat Lamaran</h2>
            <p class="whitespace-pre-line">{{ $application->cover_letter }}</p>
        </div>
    @endif

    <!-- Review Form (if status = submitted) -->
    @if($application->status === 'submitted')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Review Lamaran</h2>
            <form method="POST" action="{{ route('hr.applications.review.store', $application->id) }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium mb-2">Keputusan</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="decision" value="accept" required class="mr-2">
                            <span>Lanjut ke Tahap Interview</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="decision" value="reject" required class="mr-2">
                            <span>Tolak Lamaran</span>
                        </label>
                    </div>
                </div>

                <div id="rejection-reason" style="display:none;">
                    <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="3" 
                              class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>

        <script>
        document.querySelectorAll('input[name="decision"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('rejection-reason').style.display = 
                    this.value === 'reject' ? 'block' : 'none';
            });
        });
        </script>
    @endif

</div>
```

---

### 12.3 Review Application (Accept/Reject)

**Route**: `POST /hr/applications/{id}/review`

**Controller**: `HR\ApplicationController@storeReview`

**Kode Controller**:
```php
public function storeReview(Request $request, $id)
{
    $application = Application::findOrFail($id);

    // Validate
    $validated = $request->validate([
        'decision' => 'required|in:accept,reject',
        'rejection_reason' => 'required_if:decision,reject|nullable|string',
    ]);

    $oldStatus = $application->status;

    if ($validated['decision'] === 'accept') {
        // Accept - move to screening_passed
        $application->update([
            'status' => 'screening_passed',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'screening_passed_at' => now(),
        ]);

        // Notify candidate
        Notification::create([
            'user_id' => $application->candidate_id,
            'type' => 'application_accepted',
            'title' => 'Lamaran Diterima',
            'message' => "Selamat! Lamaran Anda untuk posisi {$application->jobPosting->title} telah lulus tahap screening. Kami akan menghubungi Anda untuk tahap interview.",
            'data' => json_encode(['application_id' => $application->id]),
        ]);

        $message = 'Lamaran telah disetujui dan kandidat akan dijadwalkan interview.';

    } else {
        // Reject
        $application->update([
            'status' => 'rejected_admin',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify candidate
        Notification::create([
            'user_id' => $application->candidate_id,
            'type' => 'application_rejected',
            'title' => 'Lamaran Ditolak',
            'message' => "Mohon maaf, lamaran Anda untuk posisi {$application->jobPosting->title} tidak dapat kami proses lebih lanjut. Terima kasih atas minat Anda.",
            'data' => json_encode(['application_id' => $application->id]),
        ]);

        $message = 'Lamaran telah ditolak.';
    }

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'update',
        'model_type' => 'Application',
        'model_id' => $application->id,
        'old_data' => json_encode(['status' => $oldStatus]),
        'new_data' => json_encode(['status' => $application->status, 'decision' => $validated['decision']]),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('hr.applications.index')
        ->with('success', $message);
}
```

---

## 13. ALUR INTERVIEW

### 13.1 Schedule Interview

**Route**: `GET /hr/interviews/schedule/{application_id}` dan `POST /hr/interviews/schedule`

**Controller**: `HR\InterviewController@scheduleForm` dan `HR\InterviewController@store`

**Flow Diagram**:
```
HR akses schedule interview page
    ↓
Tampilkan form:
  - Application info (read-only)
  - Pilih interviewer
  - Tanggal & waktu interview
  - Tipe (online/offline)
  - Lokasi/Link meeting
  - Catatan
    ↓
Submit form
    ↓
Validasi
    ↓
Create interview record
    ↓
Update application status = 'interview_scheduled'
    ↓
Send notification ke:
  - Kandidat (jadwal interview)
  - Interviewer (tugas baru)
    ↓
Redirect ke interview list
```

**Kode Controller**:
```php
public function scheduleForm($applicationId)
{
    $application = Application::with(['candidate', 'jobPosting'])->findOrFail($applicationId);
    
    // Get available interviewers
    $interviewers = User::whereHas('role', function($q) {
        $q->whereIn('name', ['interviewer', 'hr', 'super_admin']);
    })->where('is_active', true)->get();

    return view('hr.interviews.schedule', compact('application', 'interviewers'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'application_id' => 'required|exists:applications,id',
        'interviewer_id' => 'required|exists:users,id',
        'scheduled_at' => 'required|date|after:now',
        'type' => 'required|in:online,offline',
        'location' => 'required|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $application = Application::findOrFail($validated['application_id']);

    // Create interview
    $interview = Interview::create([
        'application_id' => $validated['application_id'],
        'interviewer_id' => $validated['interviewer_id'],
        'scheduled_at' => $validated['scheduled_at'],
        'type' => $validated['type'],
        'location' => $validated['location'],
        'notes' => $validated['notes'],
        'status' => 'scheduled',
    ]);

    // Update application status
    $application->update([
        'status' => 'interview_scheduled',
        'interview_scheduled_at' => now(),
    ]);

    // Notify candidate
    Notification::create([
        'user_id' => $application->candidate_id,
        'type' => 'interview_scheduled',
        'title' => 'Interview Dijadwalkan',
        'message' => "Interview untuk posisi {$application->jobPosting->title} telah dijadwalkan pada " . 
                     \Carbon\Carbon::parse($validated['scheduled_at'])->format('d M Y H:i'),
        'data' => json_encode([
            'interview_id' => $interview->id,
            'application_id' => $application->id,
        ]),
    ]);

    // Notify interviewer
    Notification::create([
        'user_id' => $validated['interviewer_id'],
        'type' => 'interview_assigned',
        'title' => 'Interview Baru',
        'message' => "Anda ditugaskan untuk melakukan interview {$application->full_name} pada " . 
                     \Carbon\Carbon::parse($validated['scheduled_at'])->format('d M Y H:i'),
        'data' => json_encode([
            'interview_id' => $interview->id,
            'application_id' => $application->id,
        ]),
    ]);

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model_type' => 'Interview',
        'model_id' => $interview->id,
        'new_data' => json_encode($interview->toArray()),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('hr.interviews.index')
        ->with('success', 'Interview berhasil dijadwalkan.');
}
```

---

# PART 5: ALUR SUPER ADMIN

## 14. ALUR MANAJEMEN USER

### 14.1 User Management Overview

**Route**: `GET /superadmin/users`

**Controller**: `SuperAdmin\UserController@index`

**View**: `resources/views/superadmin/users/index.blade.php`

**Flow Diagram**:
```
Super Admin akses /superadmin/users
    ↓
Query all users dengan filters:
  - Role
  - Status (active/inactive)
  - Search (name, email)
    ↓
Load dengan pagination
    ↓
Tampilkan tabel users:
  - Name
  - Email
  - Role
  - Status
  - Last login
  - Action buttons (View, Edit, Activate/Deactivate, Delete)
    ↓
Super Admin klik action button
    ├── View → Detail user
    ├── Edit → Update user data
    ├── Activate/Deactivate → Toggle status
    └── Delete → Soft delete user
```

### 14.2 User List Controller

**Kode Controller**:
```php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')
            ->orderBy('created_at', 'desc');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        // Get filter options
        $roles = Role::all();

        return view('superadmin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('superadmin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Internal users auto-complete registration
        if (in_array($validated['role_id'], [1, 2, 3])) {
            $validated['registration_completed'] = true;
            $validated['is_verified'] = true;
        }

        $user = User::create($validated);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'User',
            'model_id' => $user->id,
            'new_data' => json_encode($user->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::with(['role', 'applications.jobPosting'])->findOrFail($id);
        
        // Get user activity logs
        $activityLogs = AuditLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('superadmin.users.show', compact('user', 'activityLogs'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldData = $user->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If role changed to internal, auto-complete registration
        if (in_array($validated['role_id'], [1, 2, 3]) && !$user->registration_completed) {
            $validated['registration_completed'] = true;
            $validated['is_verified'] = true;
        }

        $user->update($validated);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($user->fresh()->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $oldData = $user->toArray();

        // Soft delete
        $user->delete();

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'User',
            'model_id' => $user->id,
            'old_data' => json_encode($oldData),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $oldStatus = $user->is_active;
        $user->update(['is_active' => !$user->is_active]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'old_data' => json_encode(['is_active' => $oldStatus]),
            'new_data' => json_encode(['is_active' => $user->is_active]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('superadmin.users.index')
            ->with('success', "User berhasil {$status}.");
    }
}
```

### 14.3 User List View

**View Table**:
```html
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Manajemen User</h2>
        <a href="{{ route('superadmin.users.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah User
        </a>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Cari nama/email..." 
                   value="{{ request('search') }}"
                   class="border rounded px-3 py-2">
            
            <select name="role" class="border rounded px-3 py-2">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $user->role->name === 'super_admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $user->role->name === 'hr' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $user->role->name === 'interviewer' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->role->name === 'candidate' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                                {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                            ">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('superadmin.users.show', $user->id) }}" 
                                   class="text-blue-600 hover:underline" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.users.edit', $user->id) }}" 
                                   class="text-yellow-600 hover:underline" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                    <form method="POST" 
                                          action="{{ route('superadmin.users.toggle-active', $user->id) }}" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="{{ $user->is_active ? 'text-orange-600' : 'text-green-600' }} hover:underline" 
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                        </button>
                                    </form>

                                    <form method="POST" 
                                          action="{{ route('superadmin.users.destroy', $user->id) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t">
        {{ $users->links() }}
    </div>
</div>
```

---

## 15. ALUR MASTER DATA

### 15.1 Master Data Overview

Super Admin dapat mengelola 3 jenis master data:
1. **Divisions** (Divisi)
2. **Positions** (Posisi/Jabatan)
3. **Locations** (Lokasi Kantor)

Semua master data memiliki CRUD operations yang sama dengan struktur serupa.

### 15.2 Division Management

**Route**: 
- `GET /superadmin/divisions`
- `POST /superadmin/divisions`
- `GET /superadmin/divisions/{id}/edit`
- `PUT /superadmin/divisions/{id}`
- `DELETE /superadmin/divisions/{id}`

**Controller**: `SuperAdmin\DivisionController`

**Kode Controller**:
```php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Division::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $divisions = $query->orderBy('name')->paginate(15);

        return view('superadmin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('superadmin.divisions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:divisions',
            'code' => 'required|string|max:10|unique:divisions',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $division = Division::create($validated);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'Division',
            'model_id' => $division->id,
            'new_data' => json_encode($division->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('superadmin.divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('superadmin.divisions.edit', compact('division'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $oldData = $division->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:divisions,name,' . $division->id,
            'code' => 'required|string|max:10|unique:divisions,code,' . $division->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $division->update($validated);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'Division',
            'model_id' => $division->id,
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($division->fresh()->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('superadmin.divisions.index')
            ->with('success', 'Divisi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);

        // Check if division has job postings
        if ($division->jobPostings()->count() > 0) {
            return redirect()->route('superadmin.divisions.index')
                ->with('error', 'Divisi tidak dapat dihapus karena memiliki lowongan pekerjaan.');
        }

        $oldData = $division->toArray();
        $division->delete();

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'Division',
            'model_id' => $division->id,
            'old_data' => json_encode($oldData),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('superadmin.divisions.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
```

**Division Form View**:
```html
<form method="POST" action="{{ isset($division) ? route('superadmin.divisions.update', $division->id) : route('superadmin.divisions.store') }}" 
      class="space-y-6">
    @csrf
    @if(isset($division))
        @method('PUT')
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            {{ isset($division) ? 'Edit Divisi' : 'Tambah Divisi Baru' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Nama Divisi <span class="text-red-600">*</span>
                </label>
                <input type="text" name="name" 
                       value="{{ old('name', $division->name ?? '') }}" 
                       required 
                       class="w-full border rounded px-3 py-2"
                       placeholder="e.g. Teknologi Informasi">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Kode Divisi <span class="text-red-600">*</span>
                </label>
                <input type="text" name="code" 
                       value="{{ old('code', $division->code ?? '') }}" 
                       required 
                       maxlength="10"
                       class="w-full border rounded px-3 py-2"
                       placeholder="e.g. TI">
                @error('code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">
                    Deskripsi
                </label>
                <textarea name="description" rows="3" 
                          class="w-full border rounded px-3 py-2">{{ old('description', $division->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Status <span class="text-red-600">*</span>
                </label>
                <select name="is_active" required class="w-full border rounded px-3 py-2">
                    <option value="1" {{ old('is_active', $division->is_active ?? true) ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ old('is_active', $division->is_active ?? true) ? '' : 'selected' }}>
                        Inactive
                    </option>
                </select>
                @error('is_active')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4 mt-6">
            <a href="{{ route('superadmin.divisions.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>
                Simpan
            </button>
        </div>
    </div>
</form>
```

### 15.3 Position Management

**Controller**: `SuperAdmin\PositionController`

Position management mirip dengan Division, dengan tambahan field `division_id` dan `level`.

**Kode Store Method**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'division_id' => 'required|exists:divisions,id',
        'name' => 'required|string|max:100',
        'code' => 'required|string|max:10|unique:positions',
        'description' => 'nullable|string',
        'level' => 'nullable|in:entry,junior,mid,senior,manager',
        'is_active' => 'required|boolean',
    ]);

    $position = Position::create($validated);

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model_type' => 'Position',
        'model_id' => $position->id,
        'new_data' => json_encode($position->toArray()),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('superadmin.positions.index')
        ->with('success', 'Posisi berhasil ditambahkan.');
}
```

**Position Form Extra Fields**:
```html
<!-- Division -->
<div>
    <label class="block text-sm font-medium mb-1">
        Divisi <span class="text-red-600">*</span>
    </label>
    <select name="division_id" required class="w-full border rounded px-3 py-2">
        <option value="">Pilih Divisi</option>
        @foreach($divisions as $div)
            <option value="{{ $div->id }}" 
                {{ old('division_id', $position->division_id ?? '') == $div->id ? 'selected' : '' }}>
                {{ $div->name }}
            </option>
        @endforeach
    </select>
    @error('division_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Level -->
<div>
    <label class="block text-sm font-medium mb-1">
        Level (Opsional)
    </label>
    <select name="level" class="w-full border rounded px-3 py-2">
        <option value="">Pilih Level</option>
        <option value="entry" {{ old('level', $position->level ?? '') == 'entry' ? 'selected' : '' }}>
            Entry Level
        </option>
        <option value="junior" {{ old('level', $position->level ?? '') == 'junior' ? 'selected' : '' }}>
            Junior
        </option>
        <option value="mid" {{ old('level', $position->level ?? '') == 'mid' ? 'selected' : '' }}>
            Mid Level
        </option>
        <option value="senior" {{ old('level', $position->level ?? '') == 'senior' ? 'selected' : '' }}>
            Senior
        </option>
        <option value="manager" {{ old('level', $position->level ?? '') == 'manager' ? 'selected' : '' }}>
            Manager
        </option>
    </select>
    @error('level')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
```

### 15.4 Location Management

**Controller**: `SuperAdmin\LocationController`

Location management dengan fields tambahan untuk alamat lengkap.

**Kode Store Method**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'address' => 'required|string',
        'city' => 'required|string|max:50',
        'province' => 'required|string|max:50',
        'postal_code' => 'nullable|string|max:10',
        'is_active' => 'required|boolean',
    ]);

    $location = Location::create($validated);

    // Log audit
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model_type' => 'Location',
        'model_id' => $location->id,
        'new_data' => json_encode($location->toArray()),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('superadmin.locations.index')
        ->with('success', 'Lokasi berhasil ditambahkan.');
}
```

---

## 16. ALUR AUDIT LOG

### 16.1 Audit Log Overview

**Route**: `GET /superadmin/audit-logs`

**Controller**: `SuperAdmin\AuditLogController@index`

**View**: `resources/views/superadmin/audit-logs/index.blade.php`

**Flow Diagram**:
```
Super Admin akses /superadmin/audit-logs
    ↓
Query audit_logs dengan filters:
  - User
  - Action (create, update, delete, login, logout)
  - Model type
  - Date range
    ↓
Load dengan pagination
    ↓
Tampilkan tabel audit logs:
  - Timestamp
  - User
  - Action
  - Model
  - IP Address
  - User Agent
  - Action (View Details)
    ↓
Super Admin klik "View Details"
    ↓
Show modal dengan:
  - Old data (JSON)
  - New data (JSON)
  - Diff visualization
```

### 16.2 Audit Log Controller

**Kode Controller**:
```php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->paginate(30);

        // Get filter options
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $actions = ['create', 'update', 'delete', 'login', 'logout'];
        
        $modelTypes = AuditLog::select('model_type')
            ->distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->toArray();

        return view('superadmin.audit-logs.index', compact(
            'auditLogs',
            'users',
            'actions',
            'modelTypes'
        ));
    }

    public function show($id)
    {
        $auditLog = AuditLog::with('user')->findOrFail($id);

        $oldData = json_decode($auditLog->old_data, true);
        $newData = json_decode($auditLog->new_data, true);

        return view('superadmin.audit-logs.show', compact('auditLog', 'oldData', 'newData'));
    }
}
```

### 16.3 Audit Log View

**Table View**:
```html
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">Audit Log</h2>
        <p class="text-sm text-gray-600 mt-1">
            Riwayat aktivitas semua user dalam sistem
        </p>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <select name="user_id" class="border rounded px-3 py-2">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <select name="action" class="border rounded px-3 py-2">
                <option value="">Semua Action</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>

            <select name="model_type" class="border rounded px-3 py-2">
                <option value="">Semua Model</option>
                @foreach($modelTypes as $model)
                    <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>
                        {{ $model }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" 
                   value="{{ request('date_from') }}"
                   class="border rounded px-3 py-2"
                   placeholder="Dari Tanggal">

            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($auditLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $log->user ? $log->user->name : 'System' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $log->action === 'create' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->action === 'update' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $log->action === 'delete' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $log->action === 'login' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $log->action === 'logout' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $log->model_type }}
                            @if($log->model_id)
                                <span class="text-gray-500">#{{ $log->model_id }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 font-mono text-xs">
                            {{ $log->ip_address }}
                        </td>
                        <td class="px-4 py-3">
                            @if($log->old_data || $log->new_data)
                                <a href="{{ route('superadmin.audit-logs.show', $log->id) }}" 
                                   class="text-blue-600 hover:underline text-xs">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t">
        {{ $auditLogs->links() }}
    </div>
</div>
```

### 16.4 Audit Log Detail View

**Detail Modal/Page**:
```html
<div class="max-w-6xl mx-auto p-6">
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Audit Log Detail</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">User</p>
                <p class="font-medium">{{ $auditLog->user ? $auditLog->user->name : 'System' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Action</p>
                <p class="font-medium">{{ ucfirst($auditLog->action) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Model</p>
                <p class="font-medium">{{ $auditLog->model_type }} #{{ $auditLog->model_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Timestamp</p>
                <p class="font-medium">{{ $auditLog->created_at->format('d M Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">IP Address</p>
                <p class="font-medium font-mono">{{ $auditLog->ip_address }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">User Agent</p>
                <p class="font-medium text-sm">{{ Str::limit($auditLog->user_agent, 50) }}</p>
            </div>
        </div>
    </div>

    <!-- Data Changes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Old Data -->
        @if($oldData)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-red-600">
                    <i class="fas fa-minus-circle mr-2"></i>Old Data
                </h3>
                <pre class="bg-gray-50 p-4 rounded text-xs overflow-x-auto">{{ json_encode($oldData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        <!-- New Data -->
        @if($newData)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-green-600">
                    <i class="fas fa-plus-circle mr-2"></i>New Data
                </h3>
                <pre class="bg-gray-50 p-4 rounded text-xs overflow-x-auto">{{ json_encode($newData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('superadmin.audit-logs.index') }}" 
           class="text-blue-600 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Audit Log
        </a>
    </div>

</div>
```

---

## PENUTUP & RINGKASAN

### Teknologi & Framework

**Backend**:
- Laravel 11.x
- PHP 8.2+
- MySQL 8.0+

**Frontend**:
- Blade Templates
- Tailwind CSS 3.x
- Alpine.js (opsional untuk interactivity)
- Font Awesome 6.x

**Authentication & Security**:
- Laravel Breeze/Sanctum
- CSRF Protection
- Rate Limiting
- Password Hashing (bcrypt)
- Soft Deletes

### Fitur-Fitur Utama yang Sudah Dibahas

1. **Multi-Role Authentication System**
   - 4 Role: Super Admin, HR, Interviewer, Candidate
   - Role-based redirects
   - Registration completion check

2. **Candidate Features**
   - 5-step registration
   - Browse & apply jobs
   - Application tracking
   - Dashboard analytics

3. **HR Features**
   - Job posting CRUD
   - Application review
   - Interview scheduling
   - Offer management

4. **Super Admin Features**
   - User management
   - Master data (Divisions, Positions, Locations)
   - Audit logs
   - System configuration

5. **Interview System**
   - Schedule & assign interviewer
   - Rating & recommendation
   - Status tracking

6. **Notification System**
   - Email notifications
   - In-app notifications
   - Real-time updates

7. **Audit Trail**
   - Full activity logging
   - Data versioning
   - Security tracking

### Database Schema Summary

**Total Tables**: 11 tabel utama
- `roles` (4 records)
- `users` (dynamic)
- `divisions` (6 default)
- `positions` (34 default)
- `locations` (7 default)
- `job_postings` (dynamic)
- `applications` (dynamic)
- `interviews` (dynamic)
- `offers` (dynamic)
- `audit_logs` (dynamic)
- `notifications` (dynamic)

### Best Practices Implemented

1. **Code Organization**
   - MVC Pattern
   - Service Layer (optional)
   - Repository Pattern (optional)
   - Request Validation Classes

2. **Security**
   - Input validation
   - XSS protection
   - SQL injection prevention
   - CSRF tokens
   - Rate limiting

3. **Performance**
   - Eager loading
   - Pagination
   - Query optimization
   - Caching (Redis/Memcached)

4. **Maintenance**
   - Soft deletes
   - Audit logs
   - Error logging
   - Database migrations
   - Seeders

### Testing Checklist

**Functional Testing**:
- ✅ User registration & login
- ✅ Role-based access control
- ✅ Job posting CRUD
- ✅ Application submission
- ✅ Interview scheduling
- ✅ Offer management
- ✅ Master data management
- ✅ Audit logging

**Security Testing**:
- ✅ Authentication & authorization
- ✅ CSRF protection
- ✅ XSS prevention
- ✅ SQL injection prevention
- ✅ Rate limiting

**Performance Testing**:
- ✅ Page load time
- ✅ Database queries optimization
- ✅ Large dataset handling
- ✅ Concurrent users

### Deployment Checklist

1. **Environment Setup**
   - Configure `.env` (production)
   - Set `APP_DEBUG=false`
   - Configure database credentials
   - Set mail configuration
   - Configure queue driver

2. **Optimization**
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
   - `npm run build`

3. **Security**
   - Enable HTTPS
   - Configure CORS
   - Set secure session cookies
   - Configure firewall

4. **Backup**
   - Database backup schedule
   - File storage backup
   - Log rotation

5. **Monitoring**
   - Error tracking (Sentry)
   - Performance monitoring
   - Uptime monitoring
   - Log monitoring

---

## 📝 PERBAIKAN BUG & REFACTORING (NOVEMBER 2025)

### 1. Bug Fix: Registration Lock Issue

**Problem**: 
Kandidat yang logout sebelum menyelesaikan registrasi tidak bisa login lagi karena `is_active = false`.

**Root Cause**:
- RegisterController Step 1 set `is_active = false`
- LoginRequest memblokir login jika `is_active = false`
- Kandidat terkunci dari sistem sebelum selesai registrasi

**Solution**:
1. ✅ **RegisterController** (Step 1): Set `is_active = true` sejak awal
2. ✅ **AuthenticatedSessionController**: Smart redirect ke step terakhir untuk incomplete registration
3. ✅ **LoginRequest**: Hapus validasi `is_active` (dipindahkan ke controller)

**Impact**:
- Kandidat bisa logout kapan saja dan lanjut registrasi nanti
- Hanya akun suspended (is_active=false AND registration_completed=true) yang diblokir
- User experience lebih baik

**Detail**: Lihat file `BUGFIX_REGISTRATION_LOCK.md`

---

### 2. Refactoring: Snapshot Approach untuk Applications

**Problem**:
- Inkonsistensi antara dokumentasi (snapshot approach) dan implementasi (field duplikat)
- Tabel applications duplikat field dari users (full_name, email, phone, dll)
- Sync issue: data di applications tidak update otomatis saat kandidat update profil
- Compliance risk: HR harus lihat data asli saat kandidat apply

**Solution - Snapshot Approach**:
1. ✅ **Migration**: Drop duplicate fields, tambah `candidate_snapshot` (JSON)
2. ✅ **Model**: 
   - Update fillable & casts
   - Tambah 10+ accessor methods (getCandidateNameAttribute, dll)
   - Tambah helper hasProfileChangedSinceApply()
3. ✅ **Controller**: Auto-create snapshot saat submit application
4. ✅ **View**: Side-by-side comparison (snapshot vs current profile)

**Snapshot Structure**:
```json
{
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "081234567890",
    "address": "Jl. Example No. 123",
    "birth_date": "1995-01-15",
    "gender": "male",
    "education": [...],
    "experience": [...],
    "profile_photo": "path/to/photo.jpg",
    "snapshot_at": "2025-11-29 20:45:30"
}
```

**Benefits**:
- ✅ Historical accuracy: HR lihat data asli saat apply
- ✅ No sync issues: Snapshot immutable
- ✅ Audit trail: Timestamp kapan snapshot dibuat
- ✅ Comparison view: Bisa compare snapshot vs data terkini
- ✅ Compliance: Data historis terjaga untuk keperluan audit

**Files Modified**:
- `database/migrations/2025_11_29_204344_refactor_applications_table_use_snapshot.php`
- `app/Models/Application.php`
- `app/Http/Controllers/Candidate/ApplicationController.php`
- `resources/views/hr/applications/show.blade.php`

**Detail**: Lihat file `REFACTOR_SNAPSHOT_APPROACH.md`

---

### 3. Updated Documentation Sections

Berikut section yang telah diupdate di dokumentasi ini:

1. ✅ **PART 1 - Database Schema** (line ~516):
   - Table applications menggunakan `candidate_snapshot` JSON
   - Hapus duplicate fields

2. ✅ **PART 2 - Registration Step 1** (line ~791):
   - `is_active = true` sejak Step 1
   - Penjelasan bug fix

3. ✅ **PART 2 - Login Flow** (line ~1409):
   - Smart redirect untuk incomplete registration
   - Separate handling untuk suspended accounts

4. ✅ **PART 2 - LoginRequest** (line ~1470):
   - Hapus validasi `is_active`
   - Dipindahkan ke AuthenticatedSessionController

5. ✅ **PART 3 - Application Submission** (line ~2580):
   - Snapshot creation saat submit application
   - Simplified validation (tanpa duplicate fields)

6. ✅ **PART 4 - HR Application List** (line ~3815):
   - Gunakan accessor methods ($app->candidate_name)
   - Data dari snapshot

7. ✅ **PART 4 - HR Application Detail** (line ~3935):
   - Comparison view: snapshot vs current
   - Warning alert jika profil berubah
   - Change indicators

---

### 4. Testing Recommendations

**Regression Testing**:
- [ ] Test registration flow end-to-end
- [ ] Test logout/login during registration
- [ ] Test suspended account login (should be blocked)
- [ ] Test application submission
- [ ] Test HR view application detail
- [ ] Verify snapshot data accuracy

**Data Migration Testing**:
- [ ] Backup production database
- [ ] Run migration on staging
- [ ] Verify existing applications still accessible
- [ ] Check accessor methods work correctly
- [ ] Test comparison view with old data

**Edge Cases**:
- [ ] Kandidat update profil setelah apply
- [ ] Kandidat dengan multiple applications
- [ ] Applications submitted sebelum refactoring
- [ ] Profile photo update handling

---

### 5. Referensi Dokumentasi Tambahan

Untuk detail lengkap tentang perbaikan dan refactoring, lihat file-file berikut:

1. **BUGFIX_REGISTRATION_LOCK.md**
   - Root cause analysis
   - Solution detail dengan flow diagram
   - Before/after comparison
   - Test scenarios

2. **REFACTOR_SNAPSHOT_APPROACH.md**
   - Problem analysis
   - Architecture decision
   - Implementation guide
   - Best practices
   - Testing scenarios
   - Future enhancements

---

**DOKUMENTASI LENGKAP SELESAI**

Total 5 Part:
- PART 1: Overview & Arsitektur Sistem
- PART 2: Alur Registrasi & Autentikasi
- PART 3: Alur Kandidat
- PART 4: Alur HR
- PART 5: Alur Super Admin

**Plus**: Bug Fixes & Refactoring (November 2025)

**Catatan**: Dokumentasi ini mencakup alur program dari 0 sampai selesai dengan detail lengkap untuk setiap fitur dan role dalam sistem RekrutPro, termasuk perbaikan bug critical dan refactoring terbaru.

**Last Updated**: 29 November 2025

---


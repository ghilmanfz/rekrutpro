# 🌱 Database Seeding Guide - RekrutPro

Panduan lengkap untuk mengisi database dengan data dummy untuk keperluan development dan testing.

---

## 📋 Apa yang Akan Di-seed?

Seeder ini akan mengisi database dengan data dummy yang lengkap:

### 1. **Master Data**
- ✅ 4 Roles (Super Admin, HR, Interviewer, Candidate)
- ✅ 5 Divisions (IT, Marketing, Finance, HR, Operations)
- ✅ 10+ Positions (Software Engineer, UI/UX Designer, dll)
- ✅ 3 Locations (Jakarta, Bandung, Surabaya)
- ✅ Notification Templates

### 2. **Users** (11 users)
- 👤 1 Super Admin
- 👤 1 HR Manager
- 👤 2 Interviewers
- 👤 5 Candidates (untuk testing aplikasi)

### 3. **Job Postings** (6 lowongan)
- 💼 Software Engineer (Full-time, Open)
- 💼 UI/UX Designer (Full-time, Open)
- 💼 Marketing Manager (Full-time, Open)
- 💼 Data Analyst (Contract, Open)
- 💼 HR Specialist (Full-time, Open)
- 💼 Internship Position (Closed)

### 4. **Applications** (6 aplikasi dengan berbagai status)
- 📄 Applied (baru apply)
- 📄 Screening (sedang direview HR)
- 📄 Interview Scheduled (sudah dijadwalkan interview)
- 📄 Offered (sudah dapat penawaran)
- 📄 Rejected (ditolak)

### 5. **Interviews** (7 interview sessions)
- 🎤 HR Interviews (completed & scheduled)
- 🎤 Technical Interviews (completed)
- 🎤 Final Interviews (completed & scheduled)
- ⭐ Dengan feedback, score, dan result lengkap

### 6. **Offers** (1 penawaran aktif)
- 💰 Job Offer dengan salary, benefits lengkap
- ⏳ Status: Pending (menunggu respon kandidat)

### 7. **Offer Negotiations** (1 negosiasi)
- 💬 Kandidat mengajukan negosiasi gaji
- 📝 Dengan alasan detail

### 8. **Audit Logs**
- 📊 Tracking semua aktivitas (create, update, login)

---

## 🚀 Cara Menjalankan Seeder

### Option 1: Fresh Migration + Seed (Recommended)

**⚠️ WARNING**: Ini akan **MENGHAPUS SEMUA DATA** yang ada di database!

```bash
php artisan migrate:fresh --seed
```

### Option 2: Seed Saja (Tanpa Reset Database)

Jika database sudah ada dan hanya ingin menambah data:

```bash
php artisan db:seed
```

### Option 3: Seed Spesifik Satu Seeder

Jika hanya ingin menjalankan seeder tertentu:

```bash
# Master data
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=DivisionSeeder
php artisan db:seed --class=PositionSeeder
php artisan db:seed --class=LocationSeeder

# Users
php artisan db:seed --class=UserSeeder

# Recruitment data
php artisan db:seed --class=JobPostingSeeder
php artisan db:seed --class=ApplicationSeeder
php artisan db:seed --class=InterviewSeeder
php artisan db:seed --class=OfferSeeder
php artisan db:seed --class=OfferNegotiationSeeder

# Audit logs
php artisan db:seed --class=AuditLogSeeder
```

---

## 👥 Default User Credentials

Setelah seeding selesai, gunakan credentials berikut untuk login:

### 🔑 Super Admin
```
Email: admin@rekrutpro.com
Password: password
```

### 🔑 HR Manager
```
Email: hr@rekrutpro.com
Password: password
```

### 🔑 Interviewer 1
```
Email: interviewer@rekrutpro.com
Password: password
```

### 🔑 Interviewer 2
```
Email: interviewer2@rekrutpro.com
Password: password
```

### 🔑 Candidate 1 (John Developer)
```
Email: candidate1@example.com
Password: password
```

### 🔑 Candidate 2 (Sarah Designer)
```
Email: candidate2@example.com
Password: password
```

### 🔑 Candidate 3 (Michael Marketing)
```
Email: candidate3@example.com
Password: password
```

### 🔑 Candidate 4 (Emma Analyst)
```
Email: candidate4@example.com
Password: password
```

### 🔑 Candidate 5 (David HR)
```
Email: candidate5@example.com
Password: password
```

---

## 📊 Data Relationships Overview

```
Users
  ├── Super Admin (manage all)
  ├── HR (create job postings, manage applications, create offers)
  ├── Interviewers (conduct interviews, give feedback)
  └── Candidates (apply for jobs, respond to offers)

Job Postings (6)
  └── Applications (6)
      ├── Interviews (7 sessions)
      ├── Offers (1)
      │   └── Offer Negotiations (1)
      └── Audit Logs (tracking)
```

---

## 🎭 Testing Scenarios Yang Bisa Dicoba

### Scenario 1: HR Workflow
1. Login sebagai HR (`hr@rekrutpro.com`)
2. Lihat dashboard - ada 6 job postings
3. Review aplikasi yang masuk
4. Schedule interview untuk kandidat
5. Create offer untuk kandidat yang pass interview
6. Review negosiasi gaji dari kandidat

### Scenario 2: Candidate Workflow
1. Login sebagai Candidate 2 (`candidate2@example.com`)
2. Lihat "Aplikasi Saya"
3. Buka detail aplikasi untuk UI/UX Designer position
4. Lihat offer yang diterima
5. **TEST OFFER MANAGEMENT**:
   - ✅ Terima Tawaran
   - 💬 Ajukan Negosiasi Gaji
   - ❌ Tolak Tawaran

### Scenario 3: Interviewer Workflow
1. Login sebagai Interviewer (`interviewer@rekrutpro.com`)
2. Lihat jadwal interview
3. Conduct interview yang scheduled
4. Input feedback dan score
5. Update interview result (Pass/Fail)

### Scenario 4: Offer Negotiation Flow
1. Login sebagai Candidate 2 (sudah ada offer pending)
2. Ajukan negosiasi gaji dengan alasan
3. Logout, login sebagai HR
4. Review negosiasi di offer detail
5. Approve atau Reject negosiasi
6. Jika approve, salary otomatis terupdate

---

## 🔍 Verification Checklist

Setelah seeding, pastikan data berikut ada:

- [ ] **Users table**: 11 users (1 admin, 1 hr, 2 interviewer, 5 candidate)
- [ ] **Roles table**: 4 roles
- [ ] **Divisions table**: 5 divisions
- [ ] **Positions table**: 10+ positions
- [ ] **Locations table**: 3 locations
- [ ] **Job Postings table**: 6 postings (5 open, 1 closed)
- [ ] **Applications table**: 6 applications (various statuses)
- [ ] **Interviews table**: 7 interview sessions
- [ ] **Offers table**: 1 offer (status: pending)
- [ ] **Offer Negotiations table**: 1 negotiation (status: pending)
- [ ] **Audit Logs table**: Multiple log entries

---

## 🛠 Troubleshooting

### Error: "Class not found"
```bash
# Clear composer autoload cache
composer dump-autoload
```

### Error: Foreign key constraint fails
```bash
# Pastikan urutan seeder sudah benar di DatabaseSeeder.php
# Jalankan dengan fresh migration
php artisan migrate:fresh --seed
```

### Error: "Duplicate entry"
```bash
# Hapus data lama terlebih dahulu
php artisan migrate:fresh --seed
```

### Data tidak muncul setelah seeding
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check database connection
php artisan tinker
>>> \DB::table('users')->count()
```

---

## 📝 Customization

### Menambah Lebih Banyak Data Dummy

Edit file seeder yang relevan:

- `database/seeders/UserSeeder.php` - Tambah users
- `database/seeders/JobPostingSeeder.php` - Tambah lowongan
- `database/seeders/ApplicationSeeder.php` - Tambah aplikasi
- `database/seeders/InterviewSeeder.php` - Tambah interview
- `database/seeders/OfferSeeder.php` - Tambah offers

### Mengubah Password Default

Edit di `UserSeeder.php`:

```php
'password' => Hash::make('your_custom_password'),
```

---

## ⚠️ Important Notes

1. **Jangan gunakan di Production!** 
   - Seeder ini hanya untuk development/testing
   - Password default sangat lemah

2. **Backup Data** 
   - Selalu backup database sebelum run `migrate:fresh`

3. **Environment**
   - Pastikan `APP_ENV=local` di file `.env`
   - Jangan run seeder di production environment

4. **File Storage**
   - CV dan dokumen di seeder menggunakan path dummy
   - Untuk testing lengkap, upload file manual setelah seeding

---

## 🎯 Next Steps

Setelah seeding berhasil:

1. ✅ Login dengan berbagai role untuk testing
2. ✅ Test semua fitur (apply job, interview, offer management)
3. ✅ Verify audit logs terekam dengan benar
4. ✅ Test offer negotiation workflow
5. ✅ Check email notifications (jika sudah dikonfigurasi)

---

## 📞 Need Help?

- 📖 Baca dokumentasi: `DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md`
- 🐛 Report issues: GitHub Issues
- 💬 Diskusi: GitHub Discussions

---

**Happy Testing! 🚀**

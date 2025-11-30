# 📋 ALUR TESTING MANUAL - EASYRECRUIT SYSTEM
**Tanggal:** 28 November 2025  
**Tester:** [Nama Anda]  
**Environment:** Development (http://127.0.0.1:8000)

---

## 🎯 OVERVIEW

Testing ini mencakup 3 user roles:
1. **Kandidat** - Registrasi & Dashboard
2. **Super Admin** - User Management & Master Data
3. **HR** - Job Posting & Recruitment (Optional)

**Estimasi Waktu:** 45-60 menit

---

## ✅ CHECKLIST HASIL TESTING

Tandai setiap test dengan:
- ✅ = Passed
- ❌ = Failed (catat error)
- ⏭️ = Skipped

---

# FASE 1: TESTING KANDIDAT (20 menit)

## TEST 1.1: Registrasi Kandidat Baru ⏱️ 5 menit

### Langkah-langkah:

**1. Buka Halaman Registrasi**
```
URL: http://127.0.0.1:8000/register
```
- [ ] Halaman muncul tanpa error
- [ ] Form registrasi tampil lengkap
- [ ] Progress bar menunjukkan "Step 1 dari 5"

**2. Isi Form Step 1 - Buat Akun**
```
Nama Lengkap     : Manual Test User
Email            : manualtest@example.com
Password         : Testing123
Konfirmasi Pass  : Testing123
☑ Setuju dengan syarat & ketentuan
```
- [ ] Semua field dapat diisi
- [ ] Checkbox terms dapat dicentang
- [ ] Button "Selanjutnya" aktif

**3. Submit Step 1**
- [ ] Klik button "Selanjutnya"
- [ ] Success message muncul
- [ ] Redirect ke `/register/step2`
- [ ] Progress bar update (Step 1 complete ✓)
- [ ] User auto-login

**4. Upload CV - Step 2**
```
File: Upload file PDF/DOC (max 5MB)
```
- [ ] Upload area muncul
- [ ] Dapat pilih file
- [ ] File name tampil setelah dipilih
- [ ] Button "Lanjutkan" aktif

**5. Submit Step 2**
- [ ] Klik "Lanjutkan"
- [ ] File ter-upload
- [ ] Redirect ke `/register/step3`
- [ ] Progress bar update (Step 1-2 complete ✓)

**6. Verifikasi OTP - Step 3**
```
Catatan: Kode OTP akan muncul di flash message (development mode)
```
- [ ] Halaman OTP muncul
- [ ] Flash message menampilkan kode OTP 6 digit
- [ ] Input OTP field ada
- [ ] Button "Kirim Ulang OTP" ada

**7. Submit OTP**
```
OTP: [Lihat kode di flash message]
```
- [ ] Input kode OTP
- [ ] Klik "Verifikasi"
- [ ] OTP diterima
- [ ] Redirect ke `/register/step4`
- [ ] Progress bar update (Step 1-3 complete ✓)

**8. Lengkapi Profil - Step 4**
```
Phone       : +628123456789
Address     : Jl. Testing No. 123, Jakarta
Education   : S1
Experience  : 2 tahun sebagai tester (optional)
Skills      : Manual Testing, Automation (optional)
```
- [ ] Form tampil lengkap
- [ ] Semua field dapat diisi
- [ ] Dropdown education berfungsi
- [ ] Button "Lanjutkan" aktif

**9. Submit Step 4**
- [ ] Klik "Lanjutkan"
- [ ] Data tersimpan
- [ ] Redirect ke `/register/step5`
- [ ] Progress bar menunjukkan semua step complete ✓✓✓✓✓

**10. Selesai - Step 5**
- [ ] Halaman success muncul
- [ ] Pesan "Selamat! Akun Anda berhasil dibuat"
- [ ] Progress bar menunjukkan 5/5 complete
- [ ] Button "Mulai Mencari Pekerjaan" ada
- [ ] Link "Lengkapi Profil Terlebih Dahulu" ada

**11. Akses Dashboard**
- [ ] Klik "Mulai Mencari Pekerjaan"
- [ ] Redirect ke `/candidate/dashboard`
- [ ] Dashboard muncul tanpa error 403
- [ ] Nama user "Manual Test User" tampil di top bar

---

## TEST 1.2: Login Kandidat Existing ⏱️ 3 menit

**1. Logout**
- [ ] Klik "Keluar" di sidebar
- [ ] Redirect ke landing page atau login
- [ ] Session cleared

**2. Login Kembali**
```
URL: http://127.0.0.1:8000/login
Email    : manualtest@example.com
Password : Testing123
```
- [ ] Halaman login muncul
- [ ] Field email & password ada
- [ ] Checkbox "Ingat Saya" ada

**3. Submit Login**
- [ ] Klik "Masuk"
- [ ] Login berhasil
- [ ] Redirect ke `/candidate/dashboard`
- [ ] Dashboard muncul tanpa error

---

## TEST 1.3: Menu Kandidat ⏱️ 8 menit

**Menu 1: Dashboard**
```
URL: http://127.0.0.1:8000/candidate/dashboard
```
- [ ] Halaman muncul tanpa error
- [ ] Nama user di top bar correct
- [ ] Sidebar menu lengkap:
  - [ ] Dashboard (active)
  - [ ] Lamaran Saya
  - [ ] Profil
  - [ ] Notifikasi
  - [ ] Keluar (bottom)
- [ ] Welcome message tampil
- [ ] Statistik cards ada (jika ada)

**Menu 2: Lamaran Saya**
- [ ] Klik "Lamaran Saya" di sidebar
- [ ] Redirect ke `/candidate/applications`
- [ ] Halaman muncul tanpa error
- [ ] Title "Lamaran Saya" tampil
- [ ] Search bar ada
- [ ] Filter status ada
- [ ] Tabel lamaran tampil (meski kosong)
- [ ] Message "Belum ada lamaran" jika kosong

**Menu 3: Profil**
- [ ] Klik "Profil" di sidebar
- [ ] Redirect ke `/candidate/profile`
- [ ] Halaman muncul tanpa error
- [ ] Form edit profil tampil
- [ ] Data user ter-load dengan benar:
  - [ ] Name: Manual Test User
  - [ ] Email: manualtest@example.com
  - [ ] Phone: +628123456789
  - [ ] Education: S1
  - [ ] Skills: [data yang diisi]
- [ ] Button "Simpan Perubahan" ada
- [ ] Upload foto profil ada (jika ada)

**Menu 4: Notifikasi**
- [ ] Klik "Notifikasi" di sidebar
- [ ] Redirect ke `/candidate/notifications`
- [ ] Halaman muncul tanpa error
- [ ] Title "Notifikasi" tampil
- [ ] Filter tabs ada:
  - [ ] Semua
  - [ ] Belum Dibaca
  - [ ] Sudah Dibaca
- [ ] List notifikasi tampil (meski kosong)

**Navigation Test**
- [ ] Klik bolak-balik antar menu
- [ ] Active menu highlighted dengan benar
- [ ] URL berubah sesuai menu
- [ ] Breadcrumbs tampil (jika ada)
- [ ] No error saat navigasi

---

## TEST 1.4: Registrasi Incomplete (Negative Test) ⏱️ 4 menit

**1. Logout dari Dashboard**
- [ ] Klik "Keluar"
- [ ] Confirm logout

**2. Buka Registrasi Baru**
```
URL: http://127.0.0.1:8000/register
Email untuk test: incomplete@test.com
Password: Testing123
```
- [ ] Isi Step 1 dengan data di atas
- [ ] Klik "Selanjutnya"
- [ ] Redirect ke Step 2

**3. STOP di Step 2 (Jangan Upload CV)**
- [ ] Catat: User belum complete registrasi
- [ ] Manual navigate ke: http://127.0.0.1:8000/logout

**4. Coba Login dengan User Incomplete**
```
Email: incomplete@test.com
Password: Testing123
```
- [ ] Buka `/login`
- [ ] Isi credentials
- [ ] Klik "Masuk"

**Expected Result:**
- [ ] Login DITOLAK
- [ ] Error message: "Akun Anda belum menyelesaikan proses registrasi"
- [ ] Redirect ke `/register`
- [ ] User tidak bisa akses dashboard

**5. Link Navigation Test (Step 2)**
```
Manual navigate to: http://127.0.0.1:8000/register/step2
Login dulu jika diminta dengan: incomplete@test.com
```
- [ ] Halaman Step 2 muncul
- [ ] Link "Kembali ke Halaman Karir" ada
- [ ] Link "Keluar" ada
- [ ] Kedua link functional

---

# FASE 2: TESTING SUPER ADMIN (20 menit)

## TEST 2.1: Login Super Admin ⏱️ 2 menit

**1. Logout dari Kandidat (jika masih login)**
- [ ] Klik "Keluar"

**2. Login Super Admin**
```
URL: http://127.0.0.1:8000/login
Email    : admin@rekrutpro.com
Password : password
```
- [ ] Form login muncul
- [ ] Input credentials
- [ ] Klik "Masuk"

**3. Verify Login**
- [ ] Login berhasil
- [ ] Redirect ke `/superadmin/dashboard`
- [ ] Dashboard Super Admin tampil
- [ ] Nama "Super Admin" di top bar

---

## TEST 2.2: Super Admin Dashboard ⏱️ 2 menit

```
URL: http://127.0.0.1:8000/superadmin/dashboard
```

**Check Dashboard:**
- [ ] Halaman muncul tanpa error
- [ ] Title "Dashboard Super Admin" atau similar
- [ ] Sidebar menu lengkap:
  - [ ] Dashboard
  - [ ] User Management
  - [ ] Master Data
  - [ ] Configuration
  - [ ] Audit & Reports
- [ ] Statistik cards tampil:
  - [ ] Total Users
  - [ ] Active Job Postings
  - [ ] Total Applications
  - [ ] Pending Interviews
  - [ ] (atau cards lainnya)
- [ ] Charts/graphs tampil (jika ada)
- [ ] Recent activities list (jika ada)

---

## TEST 2.3: User Management ⏱️ 6 menit

**1. Akses User Management**
```
URL: http://127.0.0.1:8000/superadmin/users
```
- [ ] Klik "User Management" di sidebar
- [ ] Halaman users list muncul
- [ ] Table users tampil dengan data:
  - [ ] ID
  - [ ] Name
  - [ ] Email
  - [ ] Role
  - [ ] Division (jika ada)
  - [ ] Status (Active/Inactive)
  - [ ] Actions (Edit, Toggle, Reset Password)

**2. Filter & Search**
- [ ] Filter by role ada (dropdown)
- [ ] Search box ada
- [ ] Pilih role "Kandidat"
- [ ] List ter-filter menunjukkan hanya kandidat
- [ ] Search name "Manual Test User"
- [ ] User muncul di hasil search

**3. Create New User (Optional)**
- [ ] Button "Add New User" atau "+ New User" ada
- [ ] Klik button
- [ ] Form create user muncul
- [ ] Fields:
  - [ ] Name
  - [ ] Email
  - [ ] Role (dropdown)
  - [ ] Division (dropdown)
  - [ ] Phone
  - [ ] Password
- [ ] Isi form:
  ```
  Name: Test HR Staff
  Email: testhr@example.com
  Role: HR / Recruiter
  Phone: +628111111111
  Password: password123
  ```
- [ ] Klik "Save" atau "Create"

**Expected:**
- [ ] Success message muncul
- [ ] User baru muncul di list
- [ ] Redirect ke users list
- [ ] Data tersimpan di database

**4. Edit User**
- [ ] Klik "Edit" pada salah satu user
- [ ] Form edit muncul
- [ ] Data user ter-load
- [ ] Ubah nama: tambahkan " (Edited)"
- [ ] Klik "Update" atau "Save"

**Expected:**
- [ ] Success message
- [ ] Nama ter-update di list
- [ ] Redirect ke users list

**5. Toggle User Status**
- [ ] Klik "Toggle Status" pada user
- [ ] Confirmation dialog muncul (optional)
- [ ] Confirm action

**Expected:**
- [ ] Status berubah (Active ↔ Inactive)
- [ ] Success message
- [ ] Badge status update

---

## TEST 2.4: Master Data ⏱️ 6 menit

**1. Akses Master Data**
```
URL: http://127.0.0.1:8000/superadmin/master-data
```
- [ ] Klik "Master Data" di sidebar
- [ ] Halaman muncul
- [ ] Tabs tersedia:
  - [ ] Divisions
  - [ ] Positions
  - [ ] Locations

**2. Tab Divisions**
- [ ] Klik tab "Divisions"
- [ ] List divisions tampil
- [ ] Columns: ID, Name, Description, Actions
- [ ] Button "Add Division" atau "+ New" ada

**Test Create Division:**
```
Name: Testing & QA
Description: Quality Assurance and Testing Department
```
- [ ] Klik "Add Division"
- [ ] Form muncul
- [ ] Isi form
- [ ] Klik "Save"

**Expected:**
- [ ] Success message
- [ ] Division baru muncul di list
- [ ] Dapat di-edit/delete

**3. Tab Positions**
- [ ] Klik tab "Positions"
- [ ] List positions tampil
- [ ] Button "Add Position" ada

**Test Create Position:**
```
Name: QA Engineer
Description: Quality Assurance Engineer
```
- [ ] Klik "Add Position"
- [ ] Isi form
- [ ] Klik "Save"

**Expected:**
- [ ] Success message
- [ ] Position baru muncul
- [ ] Actions (Edit/Delete) tersedia

**4. Tab Locations**
- [ ] Klik tab "Locations"
- [ ] List locations tampil
- [ ] Button "Add Location" ada

**Test Create Location:**
```
Name: Jakarta Head Office
Address: Jl. Sudirman No. 123, Jakarta Pusat
```
- [ ] Klik "Add Location"
- [ ] Isi form
- [ ] Klik "Save"

**Expected:**
- [ ] Success message
- [ ] Location baru muncul
- [ ] Can be edited/deleted

**5. Edit & Delete Master Data**
- [ ] Pilih salah satu data (Division/Position/Location)
- [ ] Klik "Edit"
- [ ] Ubah data
- [ ] Save

**Expected:**
- [ ] Data ter-update
- [ ] Success message

**Delete Test (Optional):**
- [ ] Klik "Delete" pada salah satu data
- [ ] Confirmation dialog muncul
- [ ] Confirm delete

**Expected:**
- [ ] Data terhapus dari list
- [ ] Success message

---

## TEST 2.5: Configuration ⏱️ 2 menit

**1. Akses Configuration**
```
URL: http://127.0.0.1:8000/superadmin/config
```
- [ ] Klik "Configuration" di sidebar
- [ ] Halaman muncul tanpa error
- [ ] Section "Notification Templates" ada
- [ ] List templates tampil

**2. View Templates**
- [ ] Template categories visible:
  - [ ] Application Submitted
  - [ ] Screening Passed
  - [ ] Interview Scheduled
  - [ ] Interview Passed
  - [ ] Offer Sent
  - [ ] (dan lainnya)
- [ ] Each template has:
  - [ ] Event Name
  - [ ] Subject
  - [ ] Status (Active/Inactive)
  - [ ] Actions (Edit/View)

**3. Edit Template (Optional)**
- [ ] Klik "Edit" pada salah satu template
- [ ] Form edit muncul
- [ ] Fields:
  - [ ] Subject
  - [ ] Body/Message
  - [ ] Placeholders help
  - [ ] Status toggle
- [ ] Placeholder documentation tampil
- [ ] Can see {{nama}}, {{email}}, {{posisi}}, etc.

---

## TEST 2.6: Audit Logs ⏱️ 2 menit

**1. Akses Audit Logs**
```
URL: http://127.0.0.1:8000/superadmin/audit
```
- [ ] Klik "Audit & Reports" di sidebar
- [ ] Halaman audit logs muncul
- [ ] Table logs tampil dengan columns:
  - [ ] Timestamp
  - [ ] User
  - [ ] Action
  - [ ] Module
  - [ ] Description
  - [ ] IP Address (optional)

**2. Filter Logs**
- [ ] Filter by date range ada
- [ ] Filter by user ada
- [ ] Filter by module ada
- [ ] Filter by action type ada

**3. Export (Optional)**
- [ ] Button "Export" atau "Download" ada
- [ ] Klik export
- [ ] File CSV/Excel ter-download

---

# FASE 3: TESTING HR (OPTIONAL - 15 menit)

## TEST 3.1: Login HR ⏱️ 2 menit

**1. Logout dari Super Admin**
- [ ] Klik "Logout"

**2. Login HR**
```
URL: http://127.0.0.1:8000/login
Email    : hr@rekrutpro.com
Password : password
```
- [ ] Login berhasil
- [ ] Redirect ke `/hr/dashboard`
- [ ] Dashboard HR tampil

---

## TEST 3.2: HR Dashboard ⏱️ 2 menit

```
URL: http://127.0.0.1:8000/hr/dashboard
```
- [ ] Halaman muncul
- [ ] Sidebar menu:
  - [ ] Dashboard
  - [ ] Job Postings
  - [ ] Applications
  - [ ] Interviews
  - [ ] Offers
- [ ] Statistik tampil:
  - [ ] Active Jobs
  - [ ] Total Applications
  - [ ] Pending Interviews
  - [ ] Offers Sent

---

## TEST 3.3: Job Postings ⏱️ 5 menit

**1. Akses Job Postings**
```
URL: http://127.0.0.1:8000/hr/job-postings
```
- [ ] Klik "Job Postings"
- [ ] List job postings tampil
- [ ] Button "Create New Job" ada

**2. Create Job Posting**
- [ ] Klik "Create New Job"
- [ ] Form muncul dengan fields:
  - [ ] Position (dropdown)
  - [ ] Division (dropdown)
  - [ ] Location (dropdown)
  - [ ] Employment Type (dropdown)
  - [ ] Description (textarea)
  - [ ] Requirements (textarea)
  - [ ] Salary Range
  - [ ] Quota
  - [ ] Status (dropdown)
- [ ] Isi form:
  ```
  Position: QA Engineer (dari master data)
  Division: Testing & QA
  Location: Jakarta Head Office
  Employment Type: Full Time
  Description: We are looking for QA Engineer...
  Requirements: Min 2 years experience...
  Salary Range: 8000000 - 12000000
  Quota: 2
  Status: Active
  ```
- [ ] Klik "Save" atau "Publish"

**Expected:**
- [ ] Success message
- [ ] Job posting baru muncul di list
- [ ] Status "Active"
- [ ] Can view/edit/delete

**3. View Job Posting**
- [ ] Klik "View" pada job yang baru dibuat
- [ ] Detail page muncul
- [ ] Semua info tampil lengkap
- [ ] Button "Edit" dan "Delete" ada

---

## TEST 3.4: Applications ⏱️ 3 menit

**1. Akses Applications**
```
URL: http://127.0.0.1:8000/hr/applications
```
- [ ] Klik "Applications"
- [ ] List applications tampil (mungkin kosong)
- [ ] Filter by status ada:
  - [ ] All
  - [ ] Submitted
  - [ ] Screening Passed
  - [ ] Interview Scheduled
  - [ ] Hired
  - [ ] Rejected
- [ ] Search box ada
- [ ] If empty: "No applications yet" message

**2. Filter & Search**
- [ ] Pilih filter status
- [ ] List ter-update sesuai filter
- [ ] Search by name/email
- [ ] Results accurate

---

## TEST 3.5: Interviews ⏱️ 2 menit

**1. Akses Interviews**
```
URL: http://127.0.0.1:8000/hr/interviews
```
- [ ] Klik "Interviews"
- [ ] List interviews tampil
- [ ] Button "Schedule Interview" ada (jika ada applications)
- [ ] Columns:
  - [ ] Candidate Name
  - [ ] Position
  - [ ] Interview Date
  - [ ] Interviewer
  - [ ] Status
  - [ ] Actions

**2. View/Edit (if any)**
- [ ] Klik "View" pada interview
- [ ] Detail muncul
- [ ] Can reschedule/cancel

---

## TEST 3.6: Offers ⏱️ 1 menit

**1. Akses Offers**
```
URL: http://127.0.0.1:8000/hr/offers
```
- [ ] Klik "Offers"
- [ ] List offers tampil
- [ ] Button "Create Offer" ada (jika ada passed interviews)
- [ ] Status tracking visible

---

# FASE 4: CROSS-TESTING & SECURITY (5 menit)

## TEST 4.1: Role-Based Access Control

**1. Login sebagai Kandidat**
```
Email: manualtest@example.com
```
- [ ] Login berhasil

**2. Try Access Super Admin Routes**
```
Manual navigate to: http://127.0.0.1:8000/superadmin/dashboard
```
**Expected:**
- [ ] Access DENIED
- [ ] 403 Forbidden error
- [ ] OR redirect to candidate dashboard

**3. Try Access HR Routes**
```
Manual navigate to: http://127.0.0.1:8000/hr/dashboard
```
**Expected:**
- [ ] Access DENIED
- [ ] 403 Forbidden error

---

## TEST 4.2: Session & Logout

**1. Logout Test**
- [ ] Klik "Logout" dari any role
- [ ] Redirect ke login/home
- [ ] Session cleared

**2. Try Access Protected Route After Logout**
```
Manual navigate to: http://127.0.0.1:8000/candidate/dashboard
```
**Expected:**
- [ ] Redirect to login page
- [ ] Message: "Please login first"

---

## TEST 4.3: Landing Page & Public Access

**1. Access Landing Page (Logged Out)**
```
URL: http://127.0.0.1:8000
```
- [ ] Landing page muncul
- [ ] Button "Login" ada
- [ ] Button "Daftar" atau "Register" ada
- [ ] Job listings tampil (jika ada)
- [ ] Can browse jobs without login

**2. View Job Detail (Public)**
```
URL: http://127.0.0.1:8000/jobs/{id}
```
- [ ] Job detail page muncul
- [ ] All info visible
- [ ] Button "Apply" ada
- [ ] Clicking "Apply" → redirect to login/register

---

# 📊 HASIL TESTING

## Summary Checklist:

### Kandidat Module:
- [ ] Registrasi 5 langkah berhasil
- [ ] Login kandidat berhasil
- [ ] Dashboard accessible
- [ ] Semua menu (4) dapat diakses
- [ ] Navigation lancar
- [ ] Logout berhasil
- [ ] Incomplete registration blocked

### Super Admin Module:
- [ ] Login super admin berhasil
- [ ] Dashboard tampil
- [ ] User Management (CRUD) berfungsi
- [ ] Master Data (CRUD) berfungsi
- [ ] Configuration accessible
- [ ] Audit logs tampil
- [ ] Navigation lancar

### HR Module (Optional):
- [ ] Login HR berhasil
- [ ] Dashboard tampil
- [ ] Job Posting (CRUD) berfungsi
- [ ] Applications list accessible
- [ ] Interviews management accessible
- [ ] Offers management accessible

### Security & Access Control:
- [ ] Role-based access berfungsi
- [ ] 403 errors untuk unauthorized access
- [ ] Session management correct
- [ ] Logout clears session
- [ ] Public pages accessible

---

## 🐛 BUGS FOUND

Jika menemukan bug, catat di sini:

**Bug #1:**
- Module: _______________
- Page/URL: _______________
- Steps to Reproduce:
  1. _______________
  2. _______________
  3. _______________
- Expected: _______________
- Actual: _______________
- Screenshot: _______________
- Browser Console Error: _______________

**Bug #2:**
- Module: _______________
- Page/URL: _______________
- Steps to Reproduce:
  1. _______________
  2. _______________
- Expected: _______________
- Actual: _______________

---

## 📈 TESTING METRICS

- **Total Test Cases:** ~80
- **Passed:** ___ / 80
- **Failed:** ___ / 80
- **Skipped:** ___ / 80
- **Pass Rate:** ____%

**Time Taken:**
- Kandidat Testing: ___ menit
- Super Admin Testing: ___ menit
- HR Testing: ___ menit
- Cross-Testing: ___ menit
- **Total:** ___ menit

---

## ✅ SIGN OFF

**Tester:** _______________  
**Date:** _______________  
**Status:** ⬜ PASS / ⬜ FAIL / ⬜ PASS WITH ISSUES

**Notes:**
_______________________________________________
_______________________________________________
_______________________________________________

---

**End of Testing Document**

# 🧪 TESTING SUPER ADMIN - Complete Guide

## 📋 CREDENTIALS

**Super Admin:**
- Email: `admin@rekrutpro.com`
- Password: `password`
- Dashboard URL: `http://127.0.0.1:8000/superadmin/dashboard`

---

## ✅ TEST CHECKLIST

### **TEST 1: Login Super Admin**

```
STEP 1: Logout dari akun kandidat
├── Klik "Keluar" di sidebar
└── Atau buka: http://127.0.0.1:8000/logout

STEP 2: Login Super Admin
├── Buka: http://127.0.0.1:8000/login
├── Email: admin@rekrutpro.com
├── Password: password
└── Klik "Masuk"

EXPECTED:
├── ✓ Login berhasil
├── ✓ Redirect ke: /superadmin/dashboard
└── ✓ Dashboard super admin muncul
```

---

### **TEST 2: Super Admin Dashboard**

```
URL: http://127.0.0.1:8000/superadmin/dashboard

CHECK:
├── ✓ Halaman muncul tanpa error
├── ✓ Nama admin di top bar: "Super Admin"
├── ✓ Sidebar menu tampil:
│   ├── Dashboard
│   ├── User Management
│   ├── Master Data
│   ├── Configuration
│   └── Audit & Reports
├── ✓ Statistik cards tampil (Total Users, Active Jobs, dll)
└── ✓ Recent activities / charts tampil
```

---

### **TEST 3: User Management**

```
MENU: User Management
URL: http://127.0.0.1:8000/superadmin/users

TEST 3A: List Users
├── ✓ Tabel users muncul
├── ✓ Filter by role ada (All, Super Admin, HR, Interviewer, Candidate)
├── ✓ Search box ada
├── ✓ Button "Add New User" ada
└── ✓ Data users tampil dengan lengkap:
    ├── Name
    ├── Email
    ├── Role
    ├── Division (jika ada)
    ├── Status (Active/Inactive)
    └── Actions (Edit, Toggle Status, Reset Password)

TEST 3B: Create User
├── Klik "Add New User"
├── Isi form:
│   ├── Name: Test HR User
│   ├── Email: testhr@rekrutpro.com
│   ├── Role: HR / Recruiter
│   ├── Division: (pilih salah satu)
│   ├── Phone: +628123456789
│   └── Password: password123
├── Klik "Save"
└── EXPECTED:
    ├── ✓ Success message
    ├── ✓ User baru muncul di list
    └── ✓ Redirect ke list users

TEST 3C: Edit User
├── Klik "Edit" pada user yang baru dibuat
├── Ubah nama menjadi: "Test HR User Updated"
├── Klik "Update"
└── EXPECTED:
    ├── ✓ Success message
    └── ✓ Nama berubah di list

TEST 3D: Toggle Status
├── Klik "Toggle Status" pada user
└── EXPECTED:
    ├── ✓ Status berubah (Active ↔ Inactive)
    └── ✓ Success message

TEST 3E: Reset Password
├── Klik "Reset Password" pada user
└── EXPECTED:
    ├── ✓ Confirmation dialog muncul
    ├── ✓ Password direset
    └── ✓ Success message
```

---

### **TEST 4: Master Data**

```
MENU: Master Data
URL: http://127.0.0.1:8000/superadmin/master-data

TEST 4A: Divisions
├── ✓ Tab "Divisions" ada
├── ✓ List divisions tampil
├── ✓ Button "Add Division" ada
├── Create new division:
│   ├── Klik "Add Division"
│   ├── Name: Testing Division
│   ├── Description: For testing purposes
│   └── Klik "Save"
└── EXPECTED:
    ├── ✓ Success message
    └── ✓ Division baru muncul di list

TEST 4B: Positions
├── ✓ Tab "Positions" ada
├── ✓ List positions tampil
├── ✓ Button "Add Position" ada
├── Create new position:
│   ├── Klik "Add Position"
│   ├── Name: QA Tester
│   ├── Description: Quality Assurance Tester
│   └── Klik "Save"
└── EXPECTED:
    ├── ✓ Success message
    └── ✓ Position baru muncul di list

TEST 4C: Locations
├── ✓ Tab "Locations" ada
├── ✓ List locations tampil
├── ✓ Button "Add Location" ada
├── Create new location:
│   ├── Klik "Add Location"
│   ├── Name: Jakarta Selatan Office
│   ├── Address: Jl. Test No. 123
│   └── Klik "Save"
└── EXPECTED:
    ├── ✓ Success message
    └── ✓ Location baru muncul di list

TEST 4D: Edit & Delete
├── Edit salah satu master data
├── Delete salah satu master data
└── EXPECTED:
    ├── ✓ Edit berhasil dengan success message
    └── ✓ Delete berhasil (dengan confirmation)
```

---

### **TEST 5: Configuration**

```
MENU: Configuration
URL: http://127.0.0.1:8000/superadmin/config

TEST 5A: Notification Templates
├── ✓ List templates tampil
├── ✓ Button "Add Template" ada
├── ✓ Template categories:
│   ├── Application Submitted
│   ├── Screening Passed
│   ├── Interview Scheduled
│   ├── Offer Sent
│   └── dll
└── Click on template to edit

TEST 5B: Create/Edit Template
├── Klik "Add Template" atau "Edit" existing
├── Form fields:
│   ├── Event Name
│   ├── Subject
│   ├── Body (with placeholders)
│   ├── Channel (Email, WhatsApp, SMS)
│   └── Status (Active/Inactive)
├── Fill in details
└── EXPECTED:
    ├── ✓ Template saved
    ├── ✓ Success message
    └── ✓ Placeholder help text ada

TEST 5C: Available Placeholders
├── ✓ Documentation placeholders tampil:
│   ├── {{nama}}
│   ├── {{email}}
│   ├── {{posisi}}
│   ├── {{kode_lamaran}}
│   ├── {{tanggal}}
│   └── dll
```

---

### **TEST 6: Audit & Reports**

```
MENU: Audit & Reports
URL: http://127.0.0.1:8000/superadmin/audit

TEST 6A: Audit Logs
├── ✓ Tabel audit logs tampil
├── ✓ Columns:
│   ├── Date/Time
│   ├── User
│   ├── Action
│   ├── Module
│   ├── Description
│   └── IP Address
├── ✓ Filter options:
│   ├── Date Range
│   ├── User
│   ├── Module
│   └── Action Type
└── ✓ Export button ada (CSV/Excel)

TEST 6B: Export Audit Logs
├── Pilih date range
├── Klik "Export"
└── EXPECTED:
    ├── ✓ File download
    └── ✓ Data sesuai dengan filter
```

---

### **TEST 7: Navigation & Access Control**

```
TEST 7A: Sidebar Navigation
├── ✓ Semua menu accessible
├── ✓ Active menu highlighted
└── ✓ Breadcrumbs tampil

TEST 7B: Profile Menu
├── ✓ User dropdown di top bar ada
├── ✓ Menu items:
│   ├── Profile
│   ├── Settings
│   └── Logout
└── ✓ Semua functional

TEST 7C: Logout
├── Klik "Logout"
└── EXPECTED:
    ├── ✓ Redirect ke login page
    └── ✓ Session cleared
```

---

### **TEST 8: Permissions & Security**

```
TEST 8A: Try Access HR Routes
├── Manual navigate to: /hr/dashboard
└── EXPECTED:
    ├── ✓ Access allowed (Super Admin has full access)
    OR
    └── ✓ Redirect with permission message

TEST 8B: Try Access Candidate Routes
├── Manual navigate to: /candidate/dashboard
└── EXPECTED:
    └── ✓ Access denied with 403 error (Super Admin not Candidate)

TEST 8C: Data Visibility
├── ✓ Super Admin dapat melihat ALL data
├── ✓ All users visible (semua roles)
├── ✓ All job postings visible
└── ✓ All applications visible
```

---

## 📊 SUMMARY CHECKLIST

### Core Features:
- [ ] Login Super Admin
- [ ] Dashboard loads without error
- [ ] User Management (CRUD)
- [ ] Master Data Management (Divisions, Positions, Locations)
- [ ] Configuration (Notification Templates)
- [ ] Audit Logs viewing
- [ ] Export functionality
- [ ] Navigation works correctly
- [ ] Logout works

### Data Integrity:
- [ ] Can create new users
- [ ] Can edit existing users
- [ ] Can toggle user status
- [ ] Can reset passwords
- [ ] Can manage master data
- [ ] Changes reflected in database

### Security:
- [ ] Super Admin has full access
- [ ] Cannot access Candidate routes (403)
- [ ] Audit logs track all actions
- [ ] Session management works

---

## 🐛 ERROR TRACKING

Jika menemukan error, catat:
1. URL yang error
2. Screenshot error message
3. Action yang dilakukan sebelum error
4. Browser console errors (F12)

---

## ✅ SUCCESS CRITERIA

Super Admin testing PASSED jika:
- ✅ Login berhasil
- ✅ Dashboard muncul tanpa error
- ✅ Semua menu accessible
- ✅ CRUD operations berfungsi (Create, Read, Update, Delete)
- ✅ Master data dapat dikelola
- ✅ Configuration dapat diatur
- ✅ Audit logs tampil
- ✅ Navigation smooth
- ✅ No 500 errors
- ✅ No 403 errors pada routes Super Admin

---

**Generated:** 2025-11-28  
**Module:** Super Admin Testing  
**System:** EasyRecruit v1.0

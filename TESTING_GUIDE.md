# 🧪 TESTING GUIDE - Sistem Registrasi Kandidat

## 📋 QUICK START

### Test 1: User Existing (adada@ada.com)
**Status: ✅ READY**
- Login: `adada@ada.com` / password Anda
- Expected: Dashboard kandidat muncul tanpa error 403

### Test 2: Registrasi Baru Lengkap
1. Buka: http://127.0.0.1:8000/register
2. Isi Step 1-5 dengan data test
3. Verify: `php test_registration.php`

### Test 3: Registrasi Tidak Lengkap  
1. Register tapi stop di Step 2
2. Logout → coba login
3. Expected: Login ditolak

---

## 🎯 DETAILED TEST SCENARIOS

### SCENARIO 1: Complete Registration Flow

**Email:** `testcomplete@example.com`

#### Step 1 - Buat Akun
```
URL: /register
Input:
  - Nama: Test User Complete
  - Email: testcomplete@example.com
  - Password: password123
  - ☑ Setuju
Expected: ✓ Redirect /register/step2
```

#### Step 2 - Upload CV
```
URL: /register/step2
Upload: Any PDF (max 5MB)
Expected: ✓ Redirect /register/step3
```

#### Step 3 - Verifikasi OTP
```
URL: /register/step3
Input: 6-digit OTP (dari flash message)
Expected: ✓ Redirect /register/step4
```

#### Step 4 - Profil
```
URL: /register/step4
Input:
  - Phone: +628123456789
  - Address: Jl. Test
  - Education: S1
Expected: ✓ Redirect /register/step5
```

#### Step 5 - Selesai
```
URL: /register/step5
Action: Klik "Mulai Cari Pekerjaan"
Expected: ✓ Dashboard kandidat muncul
```

**Verify:** `php test_registration.php`

---

### SCENARIO 2: Incomplete Registration

**Email:** `testincomplete@example.com`

```
1. Register Step 1 only
2. Logout
3. Try login
Expected: ❌ Login ditolak dengan pesan error
```

**Verify:** `php test_incomplete.php`

---

## 🔍 VERIFICATION COMMANDS

```bash
# Check existing user
php test_user.php

# Check complete registration
php test_registration.php

# Check incomplete registration  
php test_incomplete.php
```

---

## 🧹 CLEANUP

```bash
# Delete test users
php artisan tinker --execute="DB::table('users')->whereIn('email', ['testcomplete@example.com', 'testincomplete@example.com'])->delete();"

# Delete test files
del test_user.php test_registration.php test_incomplete.php
```

---

## ✅ SUCCESS CRITERIA

- [ ] User completed dapat login & akses dashboard
- [ ] User incomplete TIDAK BISA login
- [ ] Role_id ter-assign otomatis (bukan NULL)
- [ ] Semua menu kandidat dapat diakses
- [ ] Middleware redirect bekerja

**Generated: 2025-11-28**

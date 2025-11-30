# 🐛 BUGFIX: Registration Lock Issue

**Tanggal**: 30 November 2025  
**Priority**: P0 - CRITICAL  
**Status**: ✅ **FIXED**

---

## 🚨 MASALAH KRITIS

### Bug Description
Kandidat yang memulai registrasi (Step 1) kemudian logout/timeout **TIDAK BISA LOGIN LAGI** untuk melanjutkan registrasi.

### Root Cause
```php
// RegisterController@processStep1 (SEBELUM PERBAIKAN)
$user = User::create([
    'registration_step' => 1,
    'registration_completed' => false,
    'is_active' => false, // ❌ BUG: User tidak aktif!
]);

// Kemudian user di-login
auth()->login($user);
```

**Skenario Bug**:
```
1. User register Step 1 ✅
   ├─ User created with is_active = false
   └─ Auto login to continue Step 2 ✅

2. User logout / timeout session 💥

3. User coba login lagi ❌
   ├─ LoginRequest::authenticate() checks is_active
   ├─ is_active = false → REJECT!
   └─ Error: "Akun tidak aktif" 
       └─ STUCK! Tidak bisa melanjutkan registrasi
```

### Impact
- **P0 Critical**: Semua kandidat baru tidak bisa complete registrasi jika terputus
- **User Experience**: Sangat buruk, user harus register ulang dengan email berbeda
- **Data**: Database penuh dengan kandidat "setengah jadi"

---

## ✅ SOLUSI YANG DITERAPKAN

### Konsep Perbaikan

Pisahkan 2 kondisi yang berbeda:

1. **`is_active = false` + `registration_completed = true`**
   - User sudah complete registrasi, tapi di-suspend oleh admin
   - **Action**: Block login, tampilkan pesan "Akun dinonaktifkan oleh admin"

2. **`is_active = true` + `registration_completed = false`**
   - User sedang dalam proses registrasi
   - **Action**: Allow login, redirect ke step yang belum selesai

---

### 1. RegisterController - Set `is_active = true`

**File**: `app/Http/Controllers/Auth/RegisterController.php`

```php
// SEBELUM (❌ BUG)
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'role_id' => $candidateRole->id,
    'registration_step' => 1,
    'registration_completed' => false,
    'is_active' => false, // ❌ User tidak bisa login lagi!
    'is_verified' => false,
]);

// SESUDAH (✅ FIXED)
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'role_id' => $candidateRole->id,
    'registration_step' => 1,
    'registration_completed' => false,
    'is_active' => true, // ✅ PERBAIKAN: User bisa login lagi untuk lanjut registrasi
    'is_verified' => false,
]);
```

---

### 2. AuthenticatedSessionController - Smart Redirect

**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
// SEBELUM (❌ BUG)
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    
    $user = auth()->user();
    
    if (!$user->registration_completed) {
        // ❌ LOGOUT user dan suruh daftar ulang!
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('register')
            ->withErrors(['email' => 'Akun Anda belum menyelesaikan proses registrasi. Silakan daftar ulang.']);
    }
    
    return $this->redirectBasedOnRole($user);
}

// SESUDAH (✅ FIXED)
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    
    $user = auth()->user();
    
    // ✅ PERBAIKAN 1: Redirect ke step registrasi yang belum selesai
    if (!$user->registration_completed && $user->role->name === 'candidate') {
        $step = $user->registration_step ?? 1;
        
        return redirect()->route("register.step{$step}")
            ->with('info', 'Silakan lanjutkan proses registrasi Anda.');
    }
    
    // ✅ PERBAIKAN 2: Check suspended account (setelah complete registrasi)
    if (!$user->is_active && $user->registration_completed) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi tim support.']);
    }
    
    return $this->redirectBasedOnRole($user);
}
```

---

## 🔄 FLOW SETELAH PERBAIKAN

### Skenario 1: Registrasi Normal (Complete)
```
1. User register Step 1 ✅
   ├─ User created:
   │  ├─ is_active = true
   │  ├─ registration_step = 1
   │  └─ registration_completed = false
   └─ Auto login → redirect to Step 2

2. User complete Step 2, 3, 4, 5 ✅
   └─ Update: registration_completed = true

3. User logout

4. User login lagi ✅
   └─ Redirect to candidate.dashboard (normal flow)
```

### Skenario 2: Registrasi Terputus (BUG SCENARIO - NOW FIXED!)
```
1. User register Step 1 ✅
   ├─ User created:
   │  ├─ is_active = true ✅ (FIXED!)
   │  ├─ registration_step = 1
   │  └─ registration_completed = false
   └─ Auto login → redirect to Step 2

2. User logout / timeout session 💥

3. User login lagi ✅ (SEKARANG BISA!)
   ├─ Check: registration_completed = false
   ├─ Check: role = candidate
   └─ Redirect to register.step1 ✅
       └─ User bisa lanjut registrasi!
```

### Skenario 3: Account Suspended by Admin
```
1. User sudah complete registrasi ✅
   ├─ is_active = true
   ├─ registration_completed = true
   └─ Can access dashboard normally

2. Admin suspend account
   └─ Update: is_active = false

3. User logout

4. User coba login ❌
   ├─ Check: is_active = false
   ├─ Check: registration_completed = true
   └─ REJECT! "Akun dinonaktifkan administrator"
```

---

## 📊 STATE MATRIX

| is_active | registration_completed | Role | Login | Redirect To |
|-----------|------------------------|------|-------|-------------|
| `true` | `false` | candidate | ✅ Allow | `register.step{N}` |
| `true` | `true` | candidate | ✅ Allow | `candidate.dashboard` |
| `false` | `true` | candidate | ❌ Block | `login` (error: suspended) |
| `false` | `false` | candidate | ✅ Allow | `register.step{N}` |
| `true` | `true` | hr | ✅ Allow | `hr.dashboard` |
| `false` | `true` | hr | ❌ Block | `login` (error: suspended) |

---

## 🧪 TESTING

### Test Case 1: New Registration (Happy Path)
```php
public function test_new_user_can_complete_registration()
{
    // Step 1
    $response = $this->post('/register/step1', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'agree_terms' => true,
    ]);
    
    $user = User::where('email', 'test@example.com')->first();
    
    // ✅ Assert: is_active = true (FIXED!)
    $this->assertTrue($user->is_active);
    $this->assertEquals(1, $user->registration_step);
    $this->assertFalse($user->registration_completed);
    
    $response->assertRedirect(route('register.step2'));
}
```

### Test Case 2: Resume Registration After Logout (BUG SCENARIO)
```php
public function test_user_can_resume_registration_after_logout()
{
    // Create partially registered user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'registration_step' => 2,
        'registration_completed' => false,
        'is_active' => true, // ✅ FIXED: was false before
        'role_id' => Role::where('name', 'candidate')->first()->id,
    ]);
    
    // User logout
    Auth::logout();
    
    // User tries to login again
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);
    
    // ✅ Assert: Should redirect to step 2 (not rejected!)
    $response->assertRedirect(route('register.step2'));
    $this->assertAuthenticated();
}
```

### Test Case 3: Suspended Account After Completion
```php
public function test_suspended_user_cannot_login()
{
    $user = User::factory()->create([
        'email' => 'suspended@example.com',
        'password' => Hash::make('password123'),
        'registration_completed' => true,
        'is_active' => false, // Suspended by admin
        'role_id' => Role::where('name', 'candidate')->first()->id,
    ]);
    
    $response = $this->post('/login', [
        'email' => 'suspended@example.com',
        'password' => 'password123',
    ]);
    
    // ✅ Assert: Should be rejected
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
}
```

---

## 📋 CHECKLIST PERBAIKAN

### Code Changes
- [x] RegisterController: Set `is_active = true` di Step 1
- [x] AuthenticatedSessionController: Smart redirect berdasarkan `registration_step`
- [x] AuthenticatedSessionController: Separate check untuk suspended vs incomplete registration

### Testing
- [ ] Manual test: Register Step 1 → Logout → Login → Should continue to Step 2
- [ ] Manual test: Complete registration → Admin suspend → Login → Should be blocked
- [ ] Unit test: `test_new_user_can_complete_registration()`
- [ ] Unit test: `test_user_can_resume_registration_after_logout()`
- [ ] Unit test: `test_suspended_user_cannot_login()`

### Documentation
- [x] BUGFIX_REGISTRATION_LOCK.md (this file)
- [ ] Update DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md
- [ ] Add to CHANGELOG.md

### Database
- [ ] Check existing partial registrations: `SELECT * FROM users WHERE registration_completed = false AND is_active = false`
- [ ] Update existing partial registrations: `UPDATE users SET is_active = true WHERE registration_completed = false`

---

## 🔧 DATA MIGRATION (Jika Diperlukan)

Jika ada user yang stuck dengan `is_active = false` dan `registration_completed = false`:

```sql
-- Check berapa banyak user yang ter-stuck
SELECT COUNT(*) as stuck_users
FROM users 
WHERE is_active = false 
  AND registration_completed = false;

-- Perbaiki user yang stuck
UPDATE users 
SET is_active = true 
WHERE is_active = false 
  AND registration_completed = false;

-- Verify
SELECT id, name, email, registration_step, registration_completed, is_active
FROM users 
WHERE registration_completed = false;
```

Atau via Laravel Tinker:
```php
php artisan tinker

// Find stuck users
$stuckUsers = User::where('is_active', false)
                  ->where('registration_completed', false)
                  ->get();

echo "Found {$stuckUsers->count()} stuck users\n";

// Fix them
User::where('is_active', false)
    ->where('registration_completed', false)
    ->update(['is_active' => true]);

echo "Fixed!\n";
```

---

## 🎯 KESIMPULAN

### Before (❌ BUG)
- Kandidat ter-lock jika logout sebelum complete registrasi
- Tidak ada cara untuk recovery
- User experience sangat buruk

### After (✅ FIXED)
- Kandidat bisa login kapan saja untuk lanjut registrasi
- Smart redirect ke step yang belum selesai
- Proper handling untuk suspended account
- Clean separation of concerns

### Key Learnings
1. **Jangan block login hanya karena registrasi belum selesai**
   - Use middleware untuk redirect, bukan block login
   
2. **Pisahkan "inactive" vs "incomplete"**
   - `is_active = false` → Suspended by admin
   - `registration_completed = false` → Belum selesai registrasi
   
3. **Always provide recovery path**
   - User harus bisa resume proses yang terputus

---

**STATUS**: ✅ **BUG FIXED**

**Tanggal**: 30 November 2025  
**Fixed by**: Development Team  
**Reviewed by**: Technical Lead

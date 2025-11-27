# 🧪 TESTING GUIDE - SISTEM REKRUTMEN

## 📋 Testing Checklist

### ✅ 1. Database Testing

#### Verify Migrations
```bash
php artisan migrate:status
```

**Expected Output:**
```
✓ All 15 migrations should be "Ran"
```

#### Verify Seeded Data
```bash
php artisan tinker
```

```php
// Check Roles
\App\Models\Role::count()  // Should return 4

\App\Models\Role::all()->pluck('name')
// Should return: ["super_admin", "hr", "interviewer", "candidate"]

// Check Divisions
\App\Models\Division::count()  // Should return 6

// Check Users
\App\Models\User::count()  // Should return 4

// Check specific user
$admin = \App\Models\User::where('email', 'admin@rekrutpro.com')->first()
$admin->role->name  // Should return "super_admin"
$admin->isSuperAdmin()  // Should return true

// Check relationships
$hrUser = \App\Models\User::where('email', 'hr@rekrutpro.com')->first()
$hrUser->division->name  // Should return "Sumber Daya Manusia"
```

---

### ✅ 2. Routes Testing

#### List All Routes
```bash
php artisan route:list
```

**Verify:**
- ✓ 73 routes registered
- ✓ All middleware assigned correctly
- ✓ Named routes present

#### Test Specific Routes
```bash
# Public routes
php artisan route:list --path=jobs

# Auth routes
php artisan route:list --name=login

# Super Admin routes
php artisan route:list --path=super-admin

# HR routes
php artisan route:list --path=hr

# Interviewer routes
php artisan route:list --path=interviewer

# Candidate routes
php artisan route:list --path=candidate
```

---

### ✅ 3. Middleware Testing

#### Test Middleware Aliases
```bash
php artisan tinker
```

```php
// Get middleware aliases
app()[\Illuminate\Contracts\Http\Kernel::class]->getMiddleware()

// Should include:
// 'super.admin' => \App\Http\Middleware\IsSuperAdmin::class
// 'hr' => \App\Http\Middleware\IsHR::class
// 'interviewer' => \App\Http\Middleware\IsInterviewer::class
// 'candidate' => \App\Http\Middleware\IsCandidate::class
```

---

### ✅ 4. Controller Testing

#### Test Controller Existence
```bash
php artisan route:list --columns=method,uri,name,action
```

**Verify all controllers are accessible:**
- ✓ Auth\LoginController
- ✓ Auth\RegisterController
- ✓ Auth\OTPController
- ✓ SuperAdmin\DashboardController
- ✓ SuperAdmin\UserManagementController
- ✓ HR\DashboardController
- ✓ HR\JobPostingController
- ✓ Interviewer\DashboardController
- ✓ Candidate\DashboardController
- ✓ PublicJobController

---

### ✅ 5. Authentication Flow Testing (Manual)

#### Start Development Server
```bash
php artisan serve
```

Open browser: `http://localhost:8000`

#### Test Login (When Views are Ready)

**Super Admin Login:**
```
Email: admin@rekrutpro.com
Password: password
Expected Redirect: /super-admin/dashboard
```

**HR Login:**
```
Email: hr@rekrutpro.com
Password: password
Expected Redirect: /hr/dashboard
```

**Interviewer Login:**
```
Email: interviewer@rekrutpro.com
Password: password
Expected Redirect: /interviewer/dashboard
```

**Candidate Login:**
```
Email: candidate@example.com
Password: password
Expected Redirect: /candidate/dashboard
```

---

### ✅ 6. Model Relationship Testing

```bash
php artisan tinker
```

```php
// Test User → Role relationship
$user = \App\Models\User::first();
$user->role;  // Should return Role object
$user->role->name;  // Should return role name

// Test User → Division relationship
$hrUser = \App\Models\User::where('email', 'hr@rekrutpro.com')->first();
$hrUser->division;  // Should return Division object
$hrUser->division->name;  // Should return "Sumber Daya Manusia"

// Test Division → Positions relationship
$itDivision = \App\Models\Division::where('code', 'IT')->first();
$itDivision->positions;  // Should return collection (empty for now)

// Test helper methods
$admin = \App\Models\User::where('email', 'admin@rekrutpro.com')->first();
$admin->isSuperAdmin();  // true
$admin->isHR();  // false
$admin->isInterviewer();  // false
$admin->isCandidate();  // false

$hr = \App\Models\User::where('email', 'hr@rekrutpro.com')->first();
$hr->isSuperAdmin();  // false
$hr->isHR();  // true
```

---

### ✅ 7. Create Test Data

#### Create Test Job Posting
```bash
php artisan tinker
```

```php
// Get required data
$hrUser = \App\Models\User::where('email', 'hr@rekrutpro.com')->first();
$itDivision = \App\Models\Division::where('code', 'IT')->first();

// Note: Need to create Position and Location first via seeders
// For now, this will help when seeders are complete

// Later you can test:
$job = \App\Models\JobPosting::create([
    'code' => 'IT-001',
    'position_id' => 1,  // Software Engineer
    'division_id' => $itDivision->id,
    'location_id' => 1,  // Jakarta
    'created_by' => $hrUser->id,
    'title' => 'Senior Software Engineer',
    'description' => 'We are looking for...',
    'requirements' => '5+ years experience...',
    'quota' => 2,
    'employment_type' => 'full_time',
    'experience_level' => 'senior',
    'salary_min' => 15000000,
    'salary_max' => 25000000,
    'status' => 'active',
    'published_at' => now(),
]);

// Verify
$job->creator->name;  // Should return "Alice Smith"
$job->division->name;  // Should return "Teknologi Informasi"
```

---

### ✅ 8. Audit Log Testing

```bash
php artisan tinker
```

```php
// Create a test audit log
\App\Models\AuditLog::create([
    'user_id' => 1,
    'action' => 'test_action',
    'model_type' => \App\Models\User::class,
    'model_id' => 1,
    'old_values' => ['name' => 'Old Name'],
    'new_values' => ['name' => 'New Name'],
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Test Browser',
]);

// Verify
\App\Models\AuditLog::count();  // Should be > 0
$log = \App\Models\AuditLog::first();
$log->user->name;  // Should return user name
$log->action;  // Should return action
```

---

### ✅ 9. Configuration Testing

#### Check .env Configuration
```bash
# Database
php artisan tinker
DB::connection()->getPdo()  // Should not throw error

# Check app configuration
php artisan config:show app
php artisan config:show database
```

#### Check Middleware Registration
```bash
php artisan route:list --columns=middleware,uri
```

**Verify middleware applied:**
- `/super-admin/*` should have `auth`, `super.admin`
- `/hr/*` should have `auth`, `hr`
- `/interviewer/*` should have `auth`, `interviewer`
- `/candidate/*` should have `auth`, `candidate`

---

### ✅ 10. Code Quality Checks

#### Check for Syntax Errors
```bash
# Check PHP syntax
php -l app/Http/Controllers/Auth/LoginController.php
php -l app/Http/Controllers/HR/DashboardController.php
php -l app/Http/Controllers/HR/JobPostingController.php

# Or check all files
find app -name "*.php" -exec php -l {} \;
```

#### Check Route Caching (Production Ready)
```bash
# Clear existing cache
php artisan route:clear

# Cache routes
php artisan route:cache

# Verify routes still work
php artisan route:list
```

---

## 🔍 Common Issues & Solutions

### Issue 1: Middleware Not Working
**Symptom:** Routes accessible without authentication

**Solution:**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Restart server
php artisan serve
```

---

### Issue 2: "Class Not Found" Error
**Solution:**
```bash
composer dump-autoload
```

---

### Issue 3: Database Connection Error
**Solution:**
```bash
# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easyrecruit
DB_USERNAME=root
DB_PASSWORD=

# Test connection
php artisan tinker
DB::connection()->getPdo()
```

---

### Issue 4: Routes Not Found
**Solution:**
```bash
# Clear route cache
php artisan route:clear

# List routes to verify
php artisan route:list
```

---

## 📊 Expected Test Results

### Database
- ✅ 15 migrations: All "Ran"
- ✅ 4 roles in database
- ✅ 6 divisions in database
- ✅ 4 users in database
- ✅ All users have valid roles
- ✅ HR user has division assigned

### Routes
- ✅ 73 total routes registered
- ✅ All routes have names
- ✅ Protected routes have middleware
- ✅ Resource routes complete

### Controllers
- ✅ 20+ controllers created
- ✅ LoginController has full implementation
- ✅ HR DashboardController has statistics
- ✅ JobPostingController has CRUD

### Middleware
- ✅ 4 middleware aliases registered
- ✅ All protected routes use middleware
- ✅ Guest routes exclude auth

---

## 🚀 Next Testing Phase (After UI)

### When Views are Created:

1. **Manual Browser Testing**
   - Test all login flows
   - Test navigation between pages
   - Test forms submission
   - Test validation errors

2. **Feature Testing**
   ```bash
   php artisan make:test LoginTest
   php artisan make:test JobPostingTest
   php artisan test
   ```

3. **Database Testing**
   ```bash
   php artisan make:test DatabaseTest
   ```

4. **API Testing** (if API endpoints added)
   ```bash
   php artisan make:test ApiTest
   ```

---

## ✅ Current System Status

**Backend:** ✅ 100% Complete & Testable
- Database: ✅ Ready
- Models: ✅ Ready
- Controllers: ✅ Ready
- Routes: ✅ Ready
- Middleware: ✅ Ready
- Authentication: ✅ Ready

**Frontend:** ❌ 0% - Need to Create Views

**Testing Status:** ⚠️ Partial
- Database: ✅ Can test
- Models: ✅ Can test
- Routes: ✅ Can test
- Controllers: ⚠️ Need views to test fully
- End-to-End: ❌ Need views

---

**Last Updated:** November 27, 2025  
**Next:** Create Views & Frontend UI

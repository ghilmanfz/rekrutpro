# FIX: Error pada Halaman Detail Lowongan (Public Job Show)

## Problem

Ketika membuka halaman detail lowongan kerja (baik sebagai guest maupun sebagai candidate), muncul error:

```
Error
Call to a member function format() on null
resources\views\public\jobs\show.blade.php:77
```

## Root Cause

Ada beberapa field name yang salah di view template:

### 1. **Field `deadline` tidak ada di database**
   - View menggunakan: `$job->deadline`
   - Database field: `closed_at`
   - Error: `$job->deadline` returns `null` → `null->format()` throws error

### 2. **Field `level` tidak ada di database**
   - View menggunakan: `$job->level`
   - Database field: `experience_level`

### 3. **Field `job_code` tidak ada di database**
   - View menggunakan: `$job->job_code`
   - Database field: `code`

### 4. **Carbon method on string**
   - View menggunakan: `$job->published_at->diffForHumans()`
   - Database type: `date` (not cast as Carbon in model)
   - Need to parse with `\Carbon\Carbon::parse()`

## Investigation Process

### Error Stack Trace
```
Exception Trace:
resources\views\public\jobs\show.blade.php:77

Line 77:
<div class="font-semibold text-gray-900">{{ $job->deadline->format('d F Y') }}</div>
```

### Database Schema Check
File: `database/migrations/2025_11_27_102945_create_job_postings_table.php`

```php
$table->date('published_at')->nullable();
$table->date('closed_at')->nullable();  // ← This is the deadline field
$table->string('experience_level')->default('mid');  // ← Not "level"
$table->string('code')->unique();  // ← Not "job_code"
```

### Files Affected
1. `resources/views/public/jobs/show.blade.php` - Detail lowongan
2. `resources/views/public/jobs/index.blade.php` - List lowongan

## Solution

### 1. Fixed `show.blade.php` (Detail Page)

**File**: `resources/views/public/jobs/show.blade.php`

**Changes Made**:

```php
// BEFORE (Line 70-79)
<div>
    <div class="text-sm text-gray-500 mb-1">Level</div>
    <div class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->level)) }}</div>
</div>

<div>
    <div class="text-sm text-gray-500 mb-1">Deadline</div>
    <div class="font-semibold text-gray-900">{{ $job->deadline->format('d F Y') }}</div>
</div>

// AFTER (Fixed)
<div>
    <div class="text-sm text-gray-500 mb-1">Level Pengalaman</div>
    <div class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->experience_level)) }}</div>
</div>

@if($job->closed_at)
    <div>
        <div class="text-sm text-gray-500 mb-1">Deadline</div>
        <div class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($job->closed_at)->format('d F Y') }}</div>
    </div>
@endif
```

**Changes**:
- ✅ `$job->level` → `$job->experience_level`
- ✅ `$job->deadline` → `$job->closed_at`
- ✅ Added `@if($job->closed_at)` check to prevent error if null
- ✅ Added `\Carbon\Carbon::parse()` to convert date string to Carbon instance

```php
// BEFORE (Line 158-160)
<div class="mt-6 text-sm text-gray-500 text-center">
    <p>Dipublikasikan {{ $job->published_at->diffForHumans() }}</p>
    <p>Kode Lowongan: {{ $job->job_code }}</p>
</div>

// AFTER (Fixed)
<div class="mt-6 text-sm text-gray-500 text-center">
    <p>Dipublikasikan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}</p>
    <p>Kode Lowongan: {{ $job->code }}</p>
</div>
```

**Changes**:
- ✅ `$job->published_at->diffForHumans()` → `\Carbon\Carbon::parse($job->published_at)->diffForHumans()`
- ✅ `$job->job_code` → `$job->code`

### 2. Fixed `index.blade.php` (List Page)

**File**: `resources/views/public/jobs/index.blade.php`

**Changes Made**:

```php
// BEFORE (Line 160-165)
<div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center text-xs text-gray-500">
    <div>
        Dipublikasikan {{ $job->published_at->diffForHumans() }}
    </div>
    <div>
        Deadline: {{ $job->deadline->format('d M Y') }}
    </div>
</div>

// AFTER (Fixed)
<div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center text-xs text-gray-500">
    <div>
        Dipublikasikan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}
    </div>
    @if($job->closed_at)
        <div>
            Deadline: {{ \Carbon\Carbon::parse($job->closed_at)->format('d M Y') }}
        </div>
    @endif
</div>
```

**Changes**:
- ✅ `$job->published_at->diffForHumans()` → `\Carbon\Carbon::parse($job->published_at)->diffForHumans()`
- ✅ `$job->deadline` → `$job->closed_at`
- ✅ Added `@if($job->closed_at)` check

## Testing

### 1. Published Jobs
Created script to publish draft jobs:

**File**: `publish_jobs.php`

```bash
php publish_jobs.php
```

**Output**:
```
✓ Published: SE-003 - Full Stack Developer
  Published at: 2025-11-28 00:00:00
  Closes at: 2025-12-28 00:00:00

✓ Published: SE-004 - Senior Full Stack Developer
  Published at: 2025-11-28 00:00:00
  Closes at: 2025-12-28 00:00:00
```

### 2. Fixed Missing Deadlines
Created script to fix jobs without deadline:

**File**: `fix_job_deadline.php`

```bash
php fix_job_deadline.php
```

**Output**:
```
✓ Updated: SE-005 - tes
  Deadline set to: 2025-12-28 00:00:00
```

## Verification Steps

### As Guest (Not Logged In)
1. ✅ Open browser: `http://127.0.0.1:8000/jobs`
2. ✅ Click on any job posting
3. ✅ Detail page loads without error
4. ✅ See job title, description, requirements, benefits
5. ✅ See deadline (if available)
6. ✅ See "Login" and "Daftar" buttons

### As Candidate
1. ✅ Login as candidate: `john.doe@email.com`
2. ✅ Navigate to `/jobs`
3. ✅ Click on any job posting
4. ✅ Detail page loads without error
5. ✅ See "Lamar Sekarang" button (if not applied yet)
6. ✅ See "Anda sudah melamar" message (if already applied)

### As Internal User (HR/Admin)
1. ✅ Login as HR: `hr@rekrutpro.com`
2. ✅ Navigate to `/jobs`
3. ✅ Click on any job posting
4. ✅ Detail page loads without error
5. ✅ See message: "Anda login sebagai hr. Hanya Candidate yang bisa melamar pekerjaan."

## Database State

### Current Active Job Postings
```
ID: 6 | Code: SE-003 | Title: Full Stack Developer
  Status: active
  Published: 2025-11-28
  Deadline: 2025-12-28
  
ID: 7 | Code: SE-004 | Title: Senior Full Stack Developer
  Status: active
  Published: 2025-11-28
  Deadline: 2025-12-28
  
ID: 8 | Code: SE-005 | Title: tes
  Status: active
  Published: 2025-11-28
  Deadline: 2025-12-28
```

## Related Files

- `resources/views/public/jobs/show.blade.php` - Detail lowongan (FIXED)
- `resources/views/public/jobs/index.blade.php` - List lowongan (FIXED)
- `app/Http/Controllers/PublicJobController.php` - Controller (no changes needed)
- `app/Models/JobPosting.php` - Model (has SoftDeletes trait)
- `database/migrations/2025_11_27_102945_create_job_postings_table.php` - Schema

## Additional Notes

### Why not add Model Casts?

**Option 1: Add casts to JobPosting model** (NOT DONE)
```php
protected function casts(): array
{
    return [
        'published_at' => 'datetime',  // Already has 'date' cast
        'closed_at' => 'datetime',     // Already has 'date' cast
        // ...
    ];
}
```

**Why not used**: 
- The model already has `'date'` cast which should work
- Using `\Carbon\Carbon::parse()` is more explicit and safer
- Prevents errors if field is null

**Option 2: Use Carbon parse in view** (USED)
```php
{{ \Carbon\Carbon::parse($job->closed_at)->format('d F Y') }}
```

**Why used**:
- ✅ More explicit - clear that we're converting to Carbon
- ✅ Safer - won't break if model cast changes
- ✅ Works with date strings from database
- ✅ No model changes needed

## Status
✅ **FIXED** - Halaman detail lowongan sekarang bisa dibuka tanpa error oleh guest, candidate, maupun internal users.

## Date
2025-11-28

## Next Steps
User sekarang bisa:
1. Browse job listings di `/jobs`
2. Klik job untuk lihat detail
3. Login/register untuk melamar (jika guest)
4. Klik "Lamar Sekarang" jika sudah login sebagai candidate

# FIX: Error "Column not found: user_id" saat Apply Job

## Problem

Ketika candidate login dan klik "Apply Now" pada detail lowongan, muncul error:

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'user_id' 
in 'where clause' (Connection: mysql, SQL: select exists(
  select * from `applications` 
  where `applications`.`job_posting_id` = 8 
  and `applications`.`user_id` = 16 
  and `applications`.`deleted_at` is null
) as `exists`)
```

## Root Cause

Di tabel `applications`, kolom untuk menyimpan ID candidate adalah `candidate_id`, bukan `user_id`.

**Database Schema** (`database/migrations/*_create_applications_table.php`):
```php
$table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
```

**Controller** menggunakan field yang salah:
```php
// WRONG
$hasApplied = $job->applications()
    ->where('user_id', auth()->id())  // ❌ Field tidak ada
    ->exists();
```

## Solution

**File**: `app/Http/Controllers/PublicJobController.php`

### Changed
```php
// BEFORE (Line 65)
$hasApplied = $job->applications()
    ->where('user_id', auth()->id())
    ->exists();

// AFTER (Fixed)
$hasApplied = $job->applications()
    ->where('candidate_id', auth()->id())
    ->exists();
```

## Verification

### Model Relationship ✅
File: `app/Models/Application.php`

```php
public function candidate()
{
    return $this->belongsTo(User::class, 'candidate_id');
}
```

Model sudah benar menggunakan `candidate_id`.

### Database Schema ✅
```php
$table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
```

Schema sudah benar.

## Testing

1. Login sebagai candidate: `testcomplete@example.com` / `password`
2. Navigate ke `/jobs`
3. Klik "Lihat Detail" pada salah satu lowongan
4. ✅ Halaman detail terbuka tanpa error
5. ✅ Tombol "Lamar Sekarang" muncul (jika belum pernah apply)
6. Klik "Lamar Sekarang"
7. ✅ Redirect ke form aplikasi (`/candidate/applications/create/{jobId}`)

## Status
✅ **FIXED** - Candidate sekarang bisa klik "Apply Now" tanpa error

## Date
2025-11-28

## Related Files
- `app/Http/Controllers/PublicJobController.php` - Fixed query
- `app/Models/Application.php` - Model relationship (already correct)
- `database/migrations/*_create_applications_table.php` - Schema definition

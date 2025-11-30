# 📋 DOKUMENTASI UPDATE: SISTEM OFFER MANAGEMENT

**Tanggal Update**: 30 November 2025  
**Versi**: 2.0  
**Framework**: Laravel 11.x

---

## 🎯 RINGKASAN UPDATE

Sistem Offer Management telah diperbaharui dengan fitur lengkap untuk mengelola penawaran kerja, termasuk:

### ✨ **Fitur Baru yang Ditambahkan**:

1. **✅ Kandidat dapat Merespons Offer**
   - Terima Tawaran (Accept)
   - Tolak Tawaran (Reject) dengan alasan
   - Ajukan Negosiasi Gaji

2. **✅ HR dapat Edit Offer**
   - Edit penawaran yang sudah dibuat (hanya status pending)
   - Update gaji, benefits, contract type, dll

3. **✅ Sistem Negosiasi Gaji**
   - Kandidat ajukan negosiasi dengan gaji yang diinginkan + alasan
   - HR review dan approve/reject negosiasi
   - Gaji otomatis terupdate jika disetujui
   - Riwayat negosiasi tersimpan lengkap

4. **✅ UI/UX Improvements**
   - Tombol aksi yang lebih terlihat dengan gradient & shadow
   - Section offer management di HR application detail
   - Timeline status offer yang jelas
   - Modal untuk konfirmasi aksi

---

## 📊 DATABASE CHANGES

### Tabel Baru: `offer_negotiations`

```sql
CREATE TABLE `offer_negotiations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `offer_id` BIGINT UNSIGNED NOT NULL,
    `candidate_id` BIGINT UNSIGNED NOT NULL,
    `proposed_salary` DECIMAL(15,2) NOT NULL,
    `candidate_notes` TEXT,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `hr_notes` TEXT,
    `reviewed_by` BIGINT UNSIGNED,
    `reviewed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`offer_id`) REFERENCES `offers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`candidate_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`reviewed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Field Explanation**:
- `offer_id`: Foreign key ke offers table
- `candidate_id`: Kandidat yang mengajukan negosiasi
- `proposed_salary`: Gaji yang diajukan kandidat
- `candidate_notes`: Alasan negosiasi dari kandidat
- `status`: Status negosiasi (pending/approved/rejected)
- `hr_notes`: Catatan dari HR saat review
- `reviewed_by`: HR yang mereview negosiasi
- `reviewed_at`: Timestamp saat direview

### Update Tabel `offers`

**Field yang ditambahkan**:
```sql
ALTER TABLE `offers` 
ADD COLUMN `contract_type` ENUM('full_time', 'part_time', 'contract', 'internship') AFTER `salary`;
```

**Field yang direname** (untuk konsistensi):
- `offered_salary` → `salary`
- `position_offered` → `position_title`
- `notes` → `internal_notes`
- `accepted_at` → `responded_at`
- `rejected_at` → `responded_at`

---

## 🔧 NEW MODELS & RELATIONSHIPS

### Model: `OfferNegotiation`

**File**: `app/Models/OfferNegotiation.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferNegotiation extends Model
{
    protected $fillable = [
        'offer_id',
        'candidate_id',
        'proposed_salary',
        'candidate_notes',
        'status',
        'hr_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'proposed_salary' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    // Relationships
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
```

### Updated Model: `Offer`

**Relationship Additions**:

```php
// Di app/Models/Offer.php

public function negotiations()
{
    return $this->hasMany(OfferNegotiation::class);
}

public function latestNegotiation()
{
    return $this->hasOne(OfferNegotiation::class)->latestOfMany();
}
```

---

## 🎨 NEW CONTROLLERS

### 1. Candidate Offer Controller

**File**: `app/Http/Controllers/Candidate/OfferController.php`

**Methods**:

#### `accept(Offer $offer)`
**Deskripsi**: Kandidat menerima penawaran kerja

**Flow**:
```
1. Verify offer belongs to current candidate
2. Check offer status = pending
3. Update offer:
   - status = 'accepted'
   - responded_at = now()
4. Update application:
   - status = 'hired'
   - hired_at = now()
5. Log audit
6. Redirect dengan success message
```

**Code**:
```php
public function accept(Offer $offer)
{
    // Verify ownership
    if ($offer->application->candidate_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Only pending offers can be accepted
    if ($offer->status !== 'pending') {
        return redirect()->back()->with('error', 'Penawaran ini tidak dapat diterima.');
    }

    $oldData = $offer->toArray();

    // Update offer status
    $offer->update([
        'status' => 'accepted',
        'responded_at' => now(),
    ]);

    // Update application status to hired
    $offer->application->update([
        'status' => 'hired',
        'hired_at' => now(),
    ]);

    AuditLog::log('update', $offer, $oldData, [
        'status' => 'accepted',
        'action' => 'Kandidat menerima penawaran'
    ]);

    return redirect()->route('candidate.applications.show', $offer->application_id)
        ->with('success', 'Selamat! Anda telah menerima penawaran kerja.');
}
```

---

#### `reject(Request $request, Offer $offer)`
**Deskripsi**: Kandidat menolak penawaran kerja

**Flow**:
```
1. Verify offer belongs to current candidate
2. Check offer status = pending
3. Validate rejection reason (optional)
4. Update offer:
   - status = 'rejected'
   - rejection_reason = input
   - responded_at = now()
5. Update application:
   - status = 'rejected_offer'
6. Log audit
7. Redirect dengan success message
```

**Code**:
```php
public function reject(Request $request, Offer $offer)
{
    // Verify ownership
    if ($offer->application->candidate_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Only pending offers can be rejected
    if ($offer->status !== 'pending') {
        return redirect()->back()->with('error', 'Penawaran ini tidak dapat ditolak.');
    }

    $validated = $request->validate([
        'rejection_reason' => 'nullable|string|max:1000',
    ]);

    $oldData = $offer->toArray();

    // Update offer status
    $offer->update([
        'status' => 'rejected',
        'rejection_reason' => $validated['rejection_reason'] ?? null,
        'responded_at' => now(),
    ]);

    // Update application status
    $offer->application->update([
        'status' => 'rejected_offer',
    ]);

    AuditLog::log('update', $offer, $oldData, [
        'status' => 'rejected',
        'action' => 'Kandidat menolak penawaran'
    ]);

    return redirect()->route('candidate.applications.show', $offer->application_id)
        ->with('success', 'Anda telah menolak penawaran kerja.');
}
```

---

#### `negotiate(Request $request, Offer $offer)`
**Deskripsi**: Kandidat mengajukan negosiasi gaji

**Flow**:
```
1. Verify offer belongs to current candidate
2. Check offer status = pending
3. Check tidak ada negosiasi pending
4. Validate input (proposed_salary, candidate_notes)
5. Create OfferNegotiation record:
   - status = 'pending'
6. Log audit
7. Redirect dengan success message
```

**Code**:
```php
public function negotiate(Request $request, Offer $offer)
{
    // Verify ownership
    if ($offer->application->candidate_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Only pending offers can be negotiated
    if ($offer->status !== 'pending') {
        return redirect()->back()->with('error', 'Penawaran ini tidak dapat dinegosiasikan.');
    }

    // Check if there's already a pending negotiation
    $hasPendingNegotiation = $offer->negotiations()
        ->where('status', 'pending')
        ->exists();

    if ($hasPendingNegotiation) {
        return redirect()->back()->with('error', 'Anda sudah memiliki negosiasi yang sedang diproses.');
    }

    $validated = $request->validate([
        'proposed_salary' => 'required|numeric|min:0',
        'candidate_notes' => 'required|string|max:1000',
    ]);

    // Create negotiation record
    $negotiation = OfferNegotiation::create([
        'offer_id' => $offer->id,
        'candidate_id' => auth()->id(),
        'proposed_salary' => $validated['proposed_salary'],
        'candidate_notes' => $validated['candidate_notes'],
        'status' => 'pending',
    ]);

    AuditLog::log('create', $negotiation, [], $validated);

    return redirect()->route('candidate.applications.show', $offer->application_id)
        ->with('success', 'Negosiasi gaji Anda telah diajukan. HR akan meninjau permintaan Anda.');
}
```

---

### 2. HR Offer Controller (Updated)

**File**: `app/Http/Controllers/HR/OfferController.php`

**New Methods**:

#### `edit(Offer $offer)`
**Deskripsi**: Tampilkan form edit offer

**Code**:
```php
public function edit(Offer $offer)
{
    // Only allow editing pending offers
    if ($offer->status !== 'pending') {
        return redirect()->route('hr.offers.show', $offer)
            ->with('error', 'Hanya penawaran dengan status "Menunggu" yang bisa diedit.');
    }

    $offer->load([
        'application.candidate',
        'application.jobPosting'
    ]);

    return view('hr.offers.edit', compact('offer'));
}
```

---

#### `update(Request $request, Offer $offer)`
**Deskripsi**: Update offer yang sudah ada

**Code**:
```php
public function update(Request $request, Offer $offer)
{
    // Only allow updating pending offers
    if ($offer->status !== 'pending') {
        return redirect()->route('hr.offers.show', $offer)
            ->with('error', 'Hanya penawaran dengan status "Menunggu" yang bisa diedit.');
    }

    $validated = $request->validate([
        'position_title' => 'required|string|max:255',
        'salary' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'contract_type' => 'required|in:full_time,part_time,contract,internship',
        'benefits' => 'nullable|string',
        'internal_notes' => 'nullable|string',
        'valid_until' => 'required|date|after:start_date',
    ]);

    $oldData = $offer->toArray();
    $offer->update($validated);

    AuditLog::log('update', $offer, $oldData, $validated);

    return redirect()->route('hr.offers.show', $offer)
        ->with('success', 'Penawaran kerja berhasil diperbarui.');
}
```

---

#### `approveNegotiation(Request $request, OfferNegotiation $negotiation)`
**Deskripsi**: HR menyetujui negosiasi kandidat

**Flow**:
```
1. Check negotiation status = pending
2. Validate hr_notes (optional)
3. Update negotiation:
   - status = 'approved'
   - hr_notes = input
   - reviewed_by = auth()->id()
   - reviewed_at = now()
4. Update offer:
   - salary = proposed_salary (dari negosiasi)
5. Log audit (negotiation & offer)
6. Redirect dengan success message
```

**Code**:
```php
public function approveNegotiation(Request $request, OfferNegotiation $negotiation)
{
    if ($negotiation->status !== 'pending') {
        return redirect()->back()->with('error', 'Negosiasi ini sudah diproses.');
    }

    $validated = $request->validate([
        'hr_notes' => 'nullable|string|max:1000',
    ]);

    $oldNegotiation = $negotiation->toArray();
    $oldOffer = $negotiation->offer->toArray();

    // Update negotiation status
    $negotiation->update([
        'status' => 'approved',
        'hr_notes' => $validated['hr_notes'] ?? null,
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    // Update offer with new salary
    $negotiation->offer->update([
        'salary' => $negotiation->proposed_salary,
    ]);

    AuditLog::log('update', $negotiation, $oldNegotiation, [
        'status' => 'approved',
        'action' => 'HR menyetujui negosiasi gaji'
    ]);

    AuditLog::log('update', $negotiation->offer, $oldOffer, [
        'salary' => $negotiation->proposed_salary,
        'action' => 'Update gaji berdasarkan negosiasi yang disetujui'
    ]);

    return redirect()->back()->with('success', 'Negosiasi disetujui. Gaji penawaran telah diperbarui.');
}
```

---

#### `rejectNegotiation(Request $request, OfferNegotiation $negotiation)`
**Deskripsi**: HR menolak negosiasi kandidat

**Code**:
```php
public function rejectNegotiation(Request $request, OfferNegotiation $negotiation)
{
    if ($negotiation->status !== 'pending') {
        return redirect()->back()->with('error', 'Negosiasi ini sudah diproses.');
    }

    $validated = $request->validate([
        'hr_notes' => 'nullable|string|max:1000',
    ]);

    $oldData = $negotiation->toArray();

    // Update negotiation status
    $negotiation->update([
        'status' => 'rejected',
        'hr_notes' => $validated['hr_notes'] ?? null,
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    AuditLog::log('update', $negotiation, $oldData, [
        'status' => 'rejected',
        'action' => 'HR menolak negosiasi gaji'
    ]);

    return redirect()->back()->with('success', 'Negosiasi ditolak.');
}
```

---

## 🛣️ NEW ROUTES

**File**: `routes/web.php`

### Candidate Routes (Offer Actions)

```php
Route::middleware(['auth', 'candidate', 'ensure.registration.completed'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
    
    // ... existing routes ...
    
    // Offer Actions
    Route::post('/offers/{offer}/accept', [
        \App\Http\Controllers\Candidate\OfferController::class, 
        'accept'
    ])->name('offers.accept');
    
    Route::post('/offers/{offer}/reject', [
        \App\Http\Controllers\Candidate\OfferController::class, 
        'reject'
    ])->name('offers.reject');
    
    Route::post('/offers/{offer}/negotiate', [
        \App\Http\Controllers\Candidate\OfferController::class, 
        'negotiate'
    ])->name('offers.negotiate');
});
```

### HR Routes (Offer Management & Negotiations)

```php
Route::middleware(['auth', 'hr', 'ensure.registration.completed'])
    ->prefix('hr')
    ->name('hr.')
    ->group(function () {
    
    // ... existing routes ...
    
    // Offers
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{offer}', [OfferController::class, 'show'])->name('offers.show');
    Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    
    // Negotiations
    Route::post('/negotiations/{negotiation}/approve', [
        OfferController::class, 
        'approveNegotiation'
    ])->name('negotiations.approve');
    
    Route::post('/negotiations/{negotiation}/reject', [
        OfferController::class, 
        'rejectNegotiation'
    ])->name('negotiations.reject');
});
```

---

## 🎨 UPDATED VIEWS

### 1. Candidate Application Detail

**File**: `resources/views/candidate/applications/show.blade.php`

**Update di Section Offer (baris ~240-340)**:

```blade
@if($application->offer)
    @php $offer = $application->offer; @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Header dengan Status Badge -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Penawaran Kerja</h2>
            <span class="px-3 py-1 text-sm font-semibold rounded-full
                @if($offer->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($offer->status === 'accepted') bg-green-100 text-green-800
                @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                @if($offer->status === 'pending') Menunggu Respons Anda
                @elseif($offer->status === 'accepted') Diterima
                @elseif($offer->status === 'rejected') Ditolak
                @else Kadaluarsa
                @endif
            </span>
        </div>

        <!-- Offer Details -->
        <div class="space-y-3 mb-6">
            <div>
                <p class="text-sm text-gray-600">Posisi</p>
                <p class="font-semibold text-gray-900">{{ $offer->position_title }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Gaji yang Ditawarkan</p>
                <p class="text-xl font-bold text-green-600">
                    Rp {{ number_format($offer->salary, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tipe Kontrak</p>
                <p class="font-medium text-gray-900">
                    {{ ucfirst(str_replace('_', ' ', $offer->contract_type)) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                <p class="font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($offer->start_date)->format('d M Y') }}
                </p>
            </div>
            @if($offer->benefits)
                <div>
                    <p class="text-sm text-gray-600">Benefits & Fasilitas</p>
                    <p class="text-gray-900">{{ $offer->benefits }}</p>
                </div>
            @endif
        </div>

        @if($offer->status === 'pending')
            <!-- 🎨 TOMBOL AKSI SUPER TERLIHAT -->
            <div class="border-t-4 border-blue-300 pt-6 mt-6 bg-gradient-to-br from-blue-100 to-indigo-100 -mx-6 -mb-6 px-6 pb-8 rounded-b-xl shadow-lg">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-3 animate-pulse">
                        <i class="fas fa-exclamation text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Respons Anda Diperlukan!</h3>
                    <p class="text-sm text-gray-700">
                        Silakan pilih salah satu dari 3 opsi di bawah ini:
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- 🟢 TOMBOL TERIMA -->
                    <form action="{{ route('candidate.offers.accept', $offer) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA penawaran ini?')">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 font-bold text-lg shadow-2xl hover:shadow-green-500/50 transition-all transform hover:scale-105 border-2 border-green-400">
                            <i class="fas fa-check-circle text-2xl mb-2 block"></i>
                            <span class="block">Terima Tawaran</span>
                        </button>
                    </form>

                    <!-- 🔵 TOMBOL NEGOSIASI -->
                    <button type="button" 
                            onclick="document.getElementById('negotiateModal').classList.remove('hidden')"
                            class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 font-bold text-lg shadow-2xl hover:shadow-blue-500/50 transition-all transform hover:scale-105 border-2 border-blue-400">
                        <i class="fas fa-handshake text-2xl mb-2 block"></i>
                        <span class="block">Ajukan Negosiasi</span>
                    </button>

                    <!-- 🔴 TOMBOL TOLAK -->
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 font-bold text-lg shadow-2xl hover:shadow-red-500/50 transition-all transform hover:scale-105 border-2 border-red-400">
                        <i class="fas fa-times-circle text-2xl mb-2 block"></i>
                        <span class="block">Tolak Tawaran</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Status Negosiasi (jika ada) -->
        @if($offer->latestNegotiation)
            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-2">
                    Status Negosiasi:
                    @if($offer->latestNegotiation->status === 'pending')
                        <span class="text-yellow-600">Menunggu Review HR</span>
                    @elseif($offer->latestNegotiation->status === 'approved')
                        <span class="text-green-600">Disetujui!</span>
                    @else
                        <span class="text-red-600">Ditolak</span>
                    @endif
                </h4>
                <p class="text-sm text-blue-800">
                    Gaji yang Anda ajukan: 
                    <strong>Rp {{ number_format($offer->latestNegotiation->proposed_salary, 0, ',', '.') }}</strong>
                </p>
                @if($offer->latestNegotiation->hr_notes)
                    <p class="text-sm text-blue-700 mt-2">
                        Catatan HR: {{ $offer->latestNegotiation->hr_notes }}
                    </p>
                @endif
            </div>
        @endif
    </div>
@endif
```

**Modals** (di akhir file):

```blade
<!-- Modal Negosiasi -->
<div id="negotiateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Ajukan Negosiasi Gaji</h3>
            <button onclick="document.getElementById('negotiateModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('candidate.offers.negotiate', $application->offer ?? 0) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gaji yang Anda Ajukan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="proposed_salary" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                           min="0" step="100000" required
                           placeholder="Contoh: 15000000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Negosiasi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="candidate_notes" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                              required placeholder="Jelaskan alasan Anda..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('negotiateModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Kirim Negosiasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tolak Penawaran</h3>
            <button onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('candidate.offers.reject', $application->offer ?? 0) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Setelah menolak, Anda tidak dapat mengubahnya kembali.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea name="rejection_reason" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                              placeholder="Anda dapat memberikan alasan..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Ya, Tolak Penawaran
                </button>
            </div>
        </form>
    </div>
</div>
```

---

### 2. HR Application Detail (with Offer Management)

**File**: `resources/views/hr/applications/show.blade.php`

**Tambahan di Sidebar (baris ~385)**:

```blade
<!-- Sidebar -->
<div class="space-y-6">
    <!-- 💎 OFFER MANAGEMENT SECTION -->
    @if($application->offer)
    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow-lg p-6 border-2 border-purple-200">
        <h2 class="text-lg font-bold text-purple-900 mb-4 flex items-center gap-2">
            <i class="fas fa-gift text-purple-600"></i>
            Penawaran Kerja
        </h2>
        
        <div class="space-y-3 mb-4">
            <div class="bg-white rounded-lg p-3">
                <p class="text-xs text-gray-600 mb-1">Status</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    @if($application->offer->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($application->offer->status === 'accepted') bg-green-100 text-green-800
                    @elseif($application->offer->status === 'rejected') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($application->offer->status) }}
                </span>
            </div>
            
            <div class="bg-white rounded-lg p-3">
                <p class="text-xs text-gray-600 mb-1">Gaji Ditawarkan</p>
                <p class="font-bold text-green-600 text-lg">
                    Rp {{ number_format($application->offer->salary, 0, ',', '.') }}
                </p>
            </div>
            
            <div class="bg-white rounded-lg p-3">
                <p class="text-xs text-gray-600 mb-1">Berlaku Hingga</p>
                <p class="font-semibold text-gray-900">
                    {{ $application->offer->valid_until->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <a href="{{ route('hr.offers.show', $application->offer) }}" 
               class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold text-center shadow-md transition">
                <i class="fas fa-eye mr-2"></i>Lihat Detail Penawaran
            </a>
            
            @if($application->offer->status === 'pending')
            <a href="{{ route('hr.offers.edit', $application->offer) }}" 
               class="w-full px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-semibold text-center shadow-md transition">
                <i class="fas fa-edit mr-2"></i>Edit Penawaran
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- ... rest of sidebar ... -->
</div>
```

**Update Application Controller untuk load offer**:

```php
// app/Http/Controllers/HR/ApplicationController.php

public function show(Application $application)
{
    $application->load([
        'candidate', 
        'jobPosting.position', 
        'jobPosting.division', 
        'jobPosting.location',
        'offer' // ✅ Load offer relationship
    ]);

    return view('hr.applications.show', compact('application'));
}
```

---

### 3. HR Offer Detail (with Negotiations)

**File**: `resources/views/hr/offers/show.blade.php`

**Section Riwayat Negosiasi**:

```blade
<!-- Negotiations Section -->
@if($offer->negotiations->isNotEmpty())
<div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-900">Riwayat Negosiasi</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @foreach($offer->negotiations()->latest()->get() as $negotiation)
            <div class="p-4 {{ $negotiation->status === 'pending' ? 'bg-yellow-50' : '' }}">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-gray-900">
                            Gaji yang Diajukan: 
                            <span class="text-blue-600">
                                Rp {{ number_format($negotiation->proposed_salary, 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500">
                            Diajukan pada: {{ $negotiation->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($negotiation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($negotiation->status === 'approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($negotiation->status) }}
                    </span>
                </div>

                @if($negotiation->candidate_notes)
                    <div class="mb-3 p-3 bg-white rounded border border-gray-200">
                        <p class="text-xs text-gray-600 mb-1">Alasan Kandidat:</p>
                        <p class="text-sm text-gray-900">{{ $negotiation->candidate_notes }}</p>
                    </div>
                @endif

                @if($negotiation->status === 'pending')
                    <!-- Action Buttons for Pending Negotiation -->
                    <div class="flex gap-2 mt-3">
                        <button type="button" 
                                onclick="showApproveModal({{ $negotiation->id }}, {{ $negotiation->proposed_salary }})"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </button>
                        <button type="button" 
                                onclick="showRejectModal({{ $negotiation->id }})"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </div>
                @else
                    @if($negotiation->hr_notes)
                        <div class="mt-3 p-3 bg-gray-50 rounded border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1">Catatan HR:</p>
                            <p class="text-sm text-gray-900">{{ $negotiation->hr_notes }}</p>
                            @if($negotiation->reviewed_at)
                                <p class="text-xs text-gray-500 mt-1">
                                    Direview oleh {{ $negotiation->reviewer->name }} 
                                    pada {{ $negotiation->reviewed_at->format('d M Y, H:i') }}
                                </p>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif
```

**Update Controller untuk load negotiations**:

```php
// app/Http/Controllers/HR/OfferController.php

public function show(Offer $offer)
{
    $offer->load([
        'application.candidate',
        'application.jobPosting',
        'offeredBy',
        'negotiations.candidate',
        'negotiations.reviewer'
    ]);

    return view('hr.offers.show', compact('offer'));
}
```

---

## 📝 TESTING CHECKLIST

### ✅ Test Scenario 1: Kandidat Terima Offer

**Steps**:
1. Login sebagai kandidat (Loux Saint)
2. Buka "Aplikasi Saya" → Lihat detail aplikasi yang ada offer
3. Scroll ke section "Penawaran Kerja"
4. Klik tombol hijau **"Terima Tawaran"**
5. Konfirmasi dialog
6. Verify:
   - ✅ Status offer berubah menjadi "Diterima"
   - ✅ Status application berubah menjadi "hired"
   - ✅ Timestamp `responded_at` terisi
   - ✅ Success message muncul

---

### ✅ Test Scenario 2: Kandidat Ajukan Negosiasi → HR Approve

**Steps**:
1. Login sebagai kandidat
2. Buka detail aplikasi dengan offer pending
3. Klik tombol biru **"Ajukan Negosiasi"**
4. Isi form:
   - Gaji yang diajukan: Rp 15.000.000
   - Alasan: "Pengalaman 5 tahun di bidang yang sama"
5. Submit
6. Verify:
   - ✅ Record baru di tabel `offer_negotiations`
   - ✅ Status negosiasi = "pending"
   - ✅ Success message muncul

**HR Side**:
7. Logout, login sebagai HR (Alice Smith)
8. Buka "Lowongan Pekerjaan" → "Aplikasi Saya" → Detail aplikasi Loux
9. Klik "Lihat Detail Penawaran" (tombol purple di sidebar)
10. Scroll ke "Riwayat Negosiasi"
11. Lihat negosiasi pending dengan gaji Rp 15.000.000
12. Klik tombol hijau **"Setujui"**
13. Isi catatan HR (optional)
14. Submit
15. Verify:
    - ✅ Status negosiasi = "approved"
    - ✅ Gaji di offer terupdate menjadi Rp 15.000.000
    - ✅ `reviewed_by` dan `reviewed_at` terisi
    - ✅ Success message muncul

**Candidate Side Again**:
16. Logout, login kembali sebagai kandidat
17. Buka detail aplikasi
18. Verify:
    - ✅ Gaji yang ditawarkan sudah Rp 15.000.000
    - ✅ Status negosiasi: "Disetujui!"
    - ✅ Tombol 3 aksi masih muncul (offer masih pending)
19. Klik **"Terima Tawaran"**
20. Verify:
    - ✅ Status offer = "accepted"
    - ✅ Status application = "hired"

---

### ✅ Test Scenario 3: HR Edit Offer

**Steps**:
1. Login sebagai HR
2. Buka "Lowongan Pekerjaan" → "Aplikasi Saya" → Detail aplikasi dengan offer pending
3. Di sidebar, klik tombol kuning **"Edit Penawaran"**
4. Update field:
   - Gaji: Rp 14.000.000
   - Benefits: "Tambah laptop + tunjangan transport"
   - Contract Type: Full Time
5. Submit
6. Verify:
   - ✅ Offer terupdate
   - ✅ Audit log mencatat perubahan
   - ✅ Redirect ke detail offer
   - ✅ Success message muncul

---

### ✅ Test Scenario 4: Kandidat Tolak Offer

**Steps**:
1. Login sebagai kandidat
2. Buka detail aplikasi dengan offer pending
3. Klik tombol merah **"Tolak Tawaran"**
4. Modal muncul
5. Isi alasan (optional): "Mendapat offer lebih baik dari perusahaan lain"
6. Submit
7. Verify:
   - ✅ Status offer = "rejected"
   - ✅ Status application = "rejected_offer"
   - ✅ Field `rejection_reason` terisi
   - ✅ Success message muncul

---

## 📚 ADDITIONAL NOTES

### Security Considerations

1. **Authorization Check**:
   - Semua method di CandidateOfferController verify ownership:
     ```php
     if ($offer->application->candidate_id !== auth()->id()) {
         abort(403, 'Unauthorized');
     }
     ```

2. **Status Validation**:
   - Hanya offer dengan status "pending" yang bisa direspons
   - Hanya offer dengan status "pending" yang bisa diedit HR

3. **Duplicate Negotiation Prevention**:
   - Check apakah sudah ada negosiasi pending sebelum buat baru

### UI/UX Best Practices

1. **Visual Hierarchy**:
   - Tombol aksi utama (Terima) = hijau gradient
   - Tombol negosiasi = biru gradient
   - Tombol tolak = merah gradient
   - Semua dengan shadow & hover effect

2. **Confirmation Dialogs**:
   - Semua aksi penting (terima/tolak) ada konfirmasi
   - Modal untuk input form (negosiasi/reject reason)

3. **Status Indicators**:
   - Badge warna untuk status offer
   - Timeline visual untuk riwayat negosiasi
   - Icon & animasi untuk menarik perhatian

4. **Responsive Design**:
   - Grid 3 kolom di desktop → 1 kolom di mobile
   - Modal responsive dengan max-width

---

## 🔄 WORKFLOW DIAGRAM

```
┌─────────────┐
│  HR Create  │
│   Offer     │
└──────┬──────┘
       │
       ▼
┌─────────────────┐
│ Status: PENDING │◄─────────┐
│ Offer sent to   │          │
│ Candidate       │          │
└────────┬────────┘          │
         │                   │
         ├─────────┬────────┬┴───────────┐
         │         │        │            │
         ▼         ▼        ▼            ▼
    ┌────────┐ ┌──────┐ ┌──────┐    ┌───────┐
    │ ACCEPT │ │REJECT│ │NEGOT │    │HR EDIT│
    └───┬────┘ └──┬───┘ └──┬───┘    └───┬───┘
        │         │         │            │
        ▼         ▼         ▼            │
   ┌────────┐ ┌────────┐   │            │
   │ HIRED  │ │REJECTED│   │            │
   └────────┘ └────────┘   │            │
                            ▼            │
                      ┌──────────┐       │
                      │   HR     │       │
                      │  Review  │       │
                      └────┬─────┘       │
                           │             │
                     ┌─────┴──────┐      │
                     ▼            ▼      │
               ┌─────────┐  ┌─────────┐ │
               │ APPROVE │  │ REJECT  │ │
               │Update $ │  │         │ │
               └────┬────┘  └────┬────┘ │
                    │            │      │
                    └────────────┴──────┘
                           │
                           ▼
                    Back to PENDING
                    (Kandidat pilih lagi)
```

---

## 📞 SUPPORT & DOCUMENTATION

Untuk pertanyaan atau issue terkait sistem Offer Management:

1. **Bug Reports**: Create issue di repository
2. **Feature Requests**: Discussion di GitHub
3. **Documentation**: README.md & DOKUMENTASI_ALUR_PROGRAM_LENGKAP.md

---

**End of Update Documentation** 🎉

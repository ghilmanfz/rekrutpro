@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                ← Kembali ke Detail Lowongan
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Lamar Pekerjaan</h1>
            <p class="text-gray-600 mt-2">{{ $job->title }} - {{ $job->division->name }}</p>
        </div>

        <!-- Application Form -->
        <form action="{{ route('candidate.applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="job_posting_id" value="{{ $job->id }}">

            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ old('full_name', auth()->user()->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('full_name') border-red-500 @enderror"
                            required
                        >
                        @error('full_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone', auth()->user()->phone) }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                            required
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="address" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                            required
                        >{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Education & Experience -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pendidikan & Pengalaman</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pendidikan Terakhir <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="education" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Pendidikan</option>
                            <option value="SMA/SMK" {{ old('education') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                            <option value="D3" {{ old('education') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('education') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('education') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('education') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pengalaman Kerja <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="experience" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Pengalaman</option>
                            <option value="Fresh Graduate" {{ old('experience') == 'Fresh Graduate' ? 'selected' : '' }}>Fresh Graduate</option>
                            <option value="< 1 Tahun" {{ old('experience') == '< 1 Tahun' ? 'selected' : '' }}>< 1 Tahun</option>
                            <option value="1-3 Tahun" {{ old('experience') == '1-3 Tahun' ? 'selected' : '' }}>1-3 Tahun</option>
                            <option value="3-5 Tahun" {{ old('experience') == '3-5 Tahun' ? 'selected' : '' }}>3-5 Tahun</option>
                            <option value="> 5 Tahun" {{ old('experience') == '> 5 Tahun' ? 'selected' : '' }}>> 5 Tahun</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ekspektasi Gaji (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="expected_salary" 
                            value="{{ old('expected_salary') }}"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ketersediaan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="availability" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Ketersediaan</option>
                            <option value="Segera" {{ old('availability') == 'Segera' ? 'selected' : '' }}>Segera</option>
                            <option value="1 Bulan" {{ old('availability') == '1 Bulan' ? 'selected' : '' }}>1 Bulan</option>
                            <option value="2 Bulan" {{ old('availability') == '2 Bulan' ? 'selected' : '' }}>2 Bulan</option>
                            <option value="3 Bulan" {{ old('availability') == '3 Bulan' ? 'selected' : '' }}>3 Bulan</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Documents Upload -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Lamaran</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload CV/Resume <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="file" 
                            name="cv" 
                            accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cv') border-red-500 @enderror"
                            required
                        >
                        <p class="mt-2 text-sm text-gray-500">Format: PDF, DOC, DOCX (Max: 5MB)</p>
                        @error('cv')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Portfolio (Opsional)
                        </label>
                        <input 
                            type="file" 
                            name="portfolio" 
                            accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="mt-2 text-sm text-gray-500">Format: PDF, DOC, DOCX (Max: 5MB)</p>
                    </div>
                </div>
            </div>

            <!-- Cover Letter -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ceritakan tentang diri Anda dan mengapa Anda tertarik dengan posisi ini
                    </label>
                    <textarea 
                        name="cover_letter" 
                        rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tulis cover letter Anda di sini..."
                    >{{ old('cover_letter') }}</textarea>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start">
                    <input 
                        id="terms" 
                        type="checkbox" 
                        name="agree_terms"
                        value="1"
                        class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500 mt-1"
                        required
                    >
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. 
                        Saya memahami bahwa memberikan informasi palsu dapat mengakibatkan pembatalan lamaran.
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('jobs.show', $job->id) }}" class="text-gray-600 hover:text-gray-900">
                    ← Batal
                </a>
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold text-lg"
                >
                    Kirim Lamaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

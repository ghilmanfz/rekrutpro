@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
         
        <div class="mb-8">
            <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                ← Kembali ke Detail Lowongan
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Lamar Pekerjaan</h1>
            <p class="text-gray-600 mt-2">{{ $job->title }} - {{ $job->division->name }}</p>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

         
        <form action="{{ route('candidate.applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="job_posting_id" value="{{ $job->id }}">

             
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h2>
                <p class="text-sm text-gray-600 mb-5">
                    Data ini diambil dari profil Anda. Jika perlu diperbarui, silakan
                    <a href="{{ route('candidate.profile') }}" class="text-blue-600 hover:text-blue-800 font-medium">ubah profil</a>
                    sebelum mengirim lamaran.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            value="{{ auth()->user()->name ?: '-' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            disabled
                            aria-disabled="true"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            value="{{ auth()->user()->email ?: '-' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            disabled
                            aria-disabled="true"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp
                        </label>
                        <input 
                            type="tel" 
                            value="{{ auth()->user()->phone ?: '-' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            disabled
                            aria-disabled="true"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input
                            type="text"
                            value="{{ auth()->user()->date_of_birth?->format('d/m/Y') ?: '-' }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            disabled
                            aria-disabled="true"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap
                        </label>
                        <textarea 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            disabled
                            aria-disabled="true"
                        >{{ auth()->user()->address ?: '-' }}</textarea>
                    </div>
                </div>
            </div>

             
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pendidikan & Informasi Lamaran</h2>
                <p class="text-sm text-gray-600 mb-5">
                    Pendidikan diambil otomatis dari profil. Informasi lainnya dapat berbeda untuk setiap posisi yang dilamar.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pendidikan Terakhir
                        </label>
                        <input type="text"
                               value="{{ auth()->user()->education }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                               disabled aria-disabled="true">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Program Studi / Jurusan
                        </label>
                        <input type="text"
                               value="{{ auth()->user()->study_program }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                               disabled aria-disabled="true">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lama Pengalaman yang Relevan <span class="text-red-500">*</span>
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
                        @error('experience')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Pilih pengalaman yang relevan dengan posisi ini, bukan mengisi ulang riwayat pada profil.</p>
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
                            max="9999999999"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('expected_salary')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                        @error('availability')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

             
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Lamaran</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $hasProfileCv ? 'CV untuk Lamaran Ini' : 'Upload CV/Resume' }}
                            @unless($hasProfileCv)<span class="text-red-500">*</span>@endunless
                        </label>
                        @if($hasProfileCv)
                            <div class="mb-3 flex items-center justify-between gap-4 rounded-lg border border-green-200 bg-green-50 p-3">
                                <div>
                                    <p class="text-sm font-medium text-green-900">CV profil akan digunakan otomatis</p>
                                    <p class="text-xs text-green-700">{{ basename(auth()->user()->cv_path) }}</p>
                                </div>
                                <a href="{{ Storage::url(auth()->user()->cv_path) }}" target="_blank"
                                   class="text-sm font-medium text-green-700 hover:text-green-900">Lihat CV</a>
                            </div>
                        @endif
                        <input 
                            type="file" 
                            name="cv" 
                            accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cv') border-red-500 @enderror"
                            @required(!$hasProfileCv)
                        >
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $hasProfileCv ? 'Opsional: pilih file bila ingin memakai CV berbeda khusus untuk lamaran ini.' : 'Format: PDF, DOC, DOCX (Max: 5MB)' }}
                        </p>
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
                        @error('portfolio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

             
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ceritakan tentang diri Anda dan mengapa Anda tertarik dengan posisi ini
                    </label>
                    <textarea 
                        name="cover_letter" 
                        rows="6"
                        maxlength="255"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tulis cover letter Anda di sini..."
                    >{{ old('cover_letter') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Maksimal 255 karakter.</p>
                    @error('cover_letter')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

             
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start">
                    <input 
                        id="terms" 
                        type="checkbox" 
                        name="agree_terms"
                        value="1"
                        @checked(old('agree_terms'))
                        class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500 mt-1"
                        required
                    >
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. 
                        Saya memahami bahwa memberikan informasi palsu dapat mengakibatkan pembatalan lamaran.
                    </label>
                </div>
                @error('agree_terms')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

             
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

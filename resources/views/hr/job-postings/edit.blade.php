@extends('layouts.hr')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Lowongan Pekerjaan</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi lowongan pekerjaan</p>
        </div>

        <!-- Form -->
        <form action="{{ route('hr.job-postings.update', $job->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Lowongan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            value="{{ old('title', $job->title) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                            required
                        >
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Posisi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="position_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('position_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Posisi</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id', $job->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Division -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Divisi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="division_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('division_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ old('division_id', $job->division_id) == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="location_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $job->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="employment_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="full_time" {{ old('employment_type', $job->employment_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('employment_type', $job->employment_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <!-- Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="level" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="entry" {{ old('level', $job->level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="junior" {{ old('level', $job->level) == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('level', $job->level) == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('level', $job->level) == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ old('level', $job->level) == 'lead' ? 'selected' : '' }}>Lead</option>
                            <option value="manager" {{ old('level', $job->level) == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>

                    <!-- Vacancies -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Lowongan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="vacancies" 
                            value="{{ old('vacancies', $job->vacancies) }}"
                            min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Salary Range -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Rentang Gaji</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gaji Minimum (Rp)
                        </label>
                        <input 
                            type="number" 
                            name="salary_min" 
                            value="{{ old('salary_min', $job->salary_min) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gaji Maximum (Rp)
                        </label>
                        <input 
                            type="number" 
                            name="salary_max" 
                            value="{{ old('salary_max', $job->salary_max) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pekerjaan</h2>
                
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="description" 
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >{{ old('description', $job->description) }}</textarea>
                    </div>

                    <!-- Requirements -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kualifikasi & Persyaratan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="requirements" 
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >{{ old('requirements', $job->requirements) }}</textarea>
                    </div>

                    <!-- Benefits -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Benefit & Fasilitas
                        </label>
                        <textarea 
                            name="benefits" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >{{ old('benefits', $job->benefits) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deadline Lamaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="application_deadline" 
                            value="{{ old('application_deadline', $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai Kerja
                        </label>
                        <input 
                            type="date" 
                            name="expected_start_date" 
                            value="{{ old('expected_start_date', $job->expected_start_date ? $job->expected_start_date->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <a href="{{ route('hr.job-postings.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Kembali
                </a>
                
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        name="action" 
                        value="draft"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
                    >
                        Simpan sebagai Draft
                    </button>
                    <button 
                        type="submit" 
                        name="action" 
                        value="publish"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium"
                    >
                        Perbarui & Publish
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

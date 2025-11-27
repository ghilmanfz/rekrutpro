<x-hr-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Lowongan Pekerjaan Baru
            </h2>
            <a href="{{ route('hr.job-postings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('hr.job-postings.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Job Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Lowongan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            value="{{ old('title') }}" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="e.g. Senior Software Engineer"
                        >
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Posisi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="position_id" 
                            id="position_id" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('position_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Posisi</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Division -->
                    <div>
                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Divisi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="division_id" 
                            id="division_id" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('division_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="location_id" 
                            id="location_id" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="employment_type" 
                            id="employment_type" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('employment_type') border-red-500 @enderror"
                        >
                            <option value="">Pilih Tipe</option>
                            <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                            <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="level" 
                            id="level" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('level') border-red-500 @enderror"
                        >
                            <option value="">Pilih Level</option>
                            <option value="entry" {{ old('level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="junior" {{ old('level') == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('level') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('level') == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ old('level') == 'lead' ? 'selected' : '' }}>Lead</option>
                            <option value="manager" {{ old('level') == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vacancies -->
                    <div>
                        <label for="vacancies" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Posisi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="vacancies" 
                            id="vacancies" 
                            value="{{ old('vacancies', 1) }}" 
                            min="1"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('vacancies') border-red-500 @enderror"
                        >
                        @error('vacancies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Salary Range -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gaji (Opsional)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-1">
                            Gaji Minimum
                        </label>
                        <input 
                            type="number" 
                            name="salary_min" 
                            id="salary_min" 
                            value="{{ old('salary_min') }}" 
                            min="0"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('salary_min') border-red-500 @enderror"
                            placeholder="5000000"
                        >
                        @error('salary_min')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-1">
                            Gaji Maximum
                        </label>
                        <input 
                            type="number" 
                            name="salary_max" 
                            id="salary_max" 
                            value="{{ old('salary_max') }}" 
                            min="0"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('salary_max') border-red-500 @enderror"
                            placeholder="10000000"
                        >
                        @error('salary_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pekerjaan</h3>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="6" 
                        required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Jelaskan tanggung jawab, tugas, dan ekspektasi untuk posisi ini..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requirements -->
                <div class="mb-6">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">
                        Kualifikasi & Persyaratan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="requirements" 
                        id="requirements" 
                        rows="6" 
                        required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('requirements') border-red-500 @enderror"
                        placeholder="Tuliskan kualifikasi, skill, dan pengalaman yang dibutuhkan..."
                    >{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Benefits -->
                <div>
                    <label for="benefits" class="block text-sm font-medium text-gray-700 mb-1">
                        Benefit (Opsional)
                    </label>
                    <textarea 
                        name="benefits" 
                        id="benefits" 
                        rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('benefits') border-red-500 @enderror"
                        placeholder="Tuliskan benefit yang ditawarkan..."
                    >{{ old('benefits') }}</textarea>
                    @error('benefits')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Timeline -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">
                            Deadline Lamaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="deadline" 
                            id="deadline" 
                            value="{{ old('deadline') }}" 
                            min="{{ date('Y-m-d') }}"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror"
                        >
                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai Kerja (Opsional)
                        </label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="start_date" 
                            value="{{ old('start_date') }}" 
                            min="{{ date('Y-m-d') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror"
                        >
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('hr.job-postings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" name="action" value="draft" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Simpan sebagai Draft
                </button>
                <button type="submit" name="action" value="publish" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Publish Lowongan
                </button>
            </div>
        </form>
    </div>
</x-hr-layout>

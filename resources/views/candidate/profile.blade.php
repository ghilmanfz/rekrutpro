<x-candidate-layout>
    <x-slot name="header">Profil Saya</x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-2" role="alert">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
         
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="relative inline-block mb-4">
                        <img src="{{ auth()->user()->profile_photo ? Storage::url(auth()->user()->profile_photo) : asset('images/default-avatar.svg') }}" 
                             alt="{{ auth()->user()->name }}"
                             class="w-32 h-32 rounded-full border-4 border-blue-100">
                        <button class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 shadow-lg">
                            <i class="fas fa-camera text-sm"></i>
                        </button>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ auth()->user()->email }}</p>

                     
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-600">Kelengkapan Profil:</span>
                            <span class="font-semibold text-gray-900">{{ $profileCompletion ?? 75 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $profileCompletion ?? 75 }}%"></div>
                        </div>
                        @if(($profileCompletion ?? 75) < 100)
                            <p class="text-xs text-gray-500 mt-2">Lengkapi profil untuk meningkatkan peluang Anda!</p>
                        @endif
                    </div>

                     
                    <div class="border-t border-gray-200 pt-4 grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalApplications ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Total Lamaran</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $acceptedApplications ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Diterima</p>
                        </div>
                    </div>
                </div>
            </div>

             
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            <i class="fas fa-check-circle"></i>
                            Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Email Verified</span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            <i class="fas fa-check"></i>
                            Verified
                        </span>
                    </div>
                </div>
            </div>
        </div>

         
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if(old('return_job_id', session('candidate_apply_after_profile_job_id')))
                    <input type="hidden" name="return_job_id" value="{{ old('return_job_id', session('candidate_apply_after_profile_job_id')) }}">
                @endif

                 
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        Informasi Pribadi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp *</label>
                            <input type="tel" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="6281234567890 atau 081234567890"
                                   inputmode="numeric"
                                   autocomplete="tel"
                                   pattern="628[0-9]{7,12}|08[0-9]{8,11}"
                                   maxlength="15"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                            <input type="date" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth?->toDateString()) }}"
                                   max="{{ now()->toDateString() }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea name="address" 
                                      rows="3"
                                      maxlength="1000"
                                      required
                                      placeholder="Masukkan alamat lengkap Anda..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                 
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                        Pendidikan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir *</label>
                            <select name="education" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Pendidikan</option>
                                <option value="SMA/SMK" {{ old('education', $user->education) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ old('education', $user->education) == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('education', $user->education) == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('education', $user->education) == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('education', $user->education) == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                            @error('education')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi / Jurusan *</label>
                            <input type="text"
                                   name="study_program"
                                   value="{{ old('study_program', $user->study_program) }}"
                                   maxlength="255"
                                   placeholder="Contoh: Teknik Informatika, RPL, IPA"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('study_program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                 
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-briefcase text-blue-600"></i>
                        Pengalaman & Keahlian
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman Kerja</label>
                            <textarea name="experience" 
                                      rows="4"
                                      maxlength="5000"
                                      placeholder="Jelaskan pengalaman kerja Anda (posisi, perusahaan, durasi, tanggung jawab)..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('experience', $user->experience) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Pisahkan setiap pengalaman dengan baris baru</p>
                            @error('experience')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keahlian/Skills</label>
                            <textarea name="skills" 
                                      rows="3"
                                      maxlength="2000"
                                      placeholder="Contoh: PHP, Laravel, JavaScript, React, MySQL, Communication, Leadership..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('skills', $user->skills) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Pisahkan skills dengan koma</p>
                            @error('skills')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-file-alt text-blue-600"></i>
                        Curriculum Vitae
                    </h3>

                    @php
                        $hasProfileCv = filled($user->cv_path) && Storage::disk('public')->exists($user->cv_path);
                    @endphp

                    @if($hasProfileCv)
                        <div class="mb-4 flex items-center justify-between gap-4 rounded-lg border border-green-200 bg-green-50 p-4">
                            <div>
                                <p class="font-medium text-green-900">CV profil tersimpan</p>
                                <p class="text-sm text-green-700">{{ basename($user->cv_path) }}</p>
                            </div>
                            <a href="{{ Storage::url($user->cv_path) }}" target="_blank"
                               class="text-sm font-medium text-green-700 hover:text-green-900">Lihat CV</a>
                        </div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $hasProfileCv ? 'Ganti CV (Opsional)' : 'Upload CV' }}
                    </label>
                    <input type="file"
                           name="cv"
                           accept=".pdf,.doc,.docx"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Format PDF, DOC, atau DOCX. Maksimal 5MB.</p>
                    @error('cv')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-share-alt text-blue-600"></i>
                        Social Media & Portfolio
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="fab fa-linkedin"></i>
                                </span>
                                <input type="url" 
                                       name="linkedin_url" 
                                       value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                       placeholder="https://linkedin.com/in/username"
                                       maxlength="255"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @error('linkedin_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">GitHub</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="fab fa-github"></i>
                                </span>
                                <input type="url" 
                                       name="github_url" 
                                       value="{{ old('github_url', $user->github_url) }}"
                                       placeholder="https://github.com/username"
                                       maxlength="255"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @error('github_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Portfolio Website</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="fas fa-globe"></i>
                                </span>
                                <input type="url" 
                                       name="portfolio_url" 
                                       value="{{ old('portfolio_url', $user->portfolio_url) }}"
                                       placeholder="https://yourportfolio.com"
                                       maxlength="255"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @error('portfolio_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                 
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('candidate.dashboard') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-candidate-layout>

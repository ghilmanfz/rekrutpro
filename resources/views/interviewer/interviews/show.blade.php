<x-interviewer-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('interviewer.interviews.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Interview</h1>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Candidate Profile -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profil Kandidat</h2>
                
                <div class="flex items-start gap-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-3xl">
                            {{ substr($interview->application->candidate->name, 0, 1) }}
                        </span>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ $interview->application->candidate->name }}</h3>
                        <p class="text-gray-600 mt-1">{{ $interview->application->candidate->email }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-sm text-gray-500">Telepon</p>
                                <p class="font-medium text-gray-900">{{ $interview->application->phone ?? $interview->application->candidate->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Posisi yang Dilamar</p>
                                <p class="font-medium text-gray-900">{{ $interview->application->jobPosting->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $interview->application->jobPosting->location->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'scheduled' => 'Terjadwal',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$interview->status] ?? $interview->status }}
                                </span>
                            </div>
                        </div>

                        @if($interview->application->cv_path)
                            <div class="mt-4">
                                <a href="{{ Storage::url($interview->application->cv_path) }}" target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    <i class="fas fa-file-pdf mr-2"></i>Lihat CV
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assessment Form -->
            @if(($interview->status == 'scheduled' || $interview->status == 'completed') && !$interview->assessment)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Form Penilaian Wawancara</h2>
                        <p class="text-sm text-gray-600 mb-8">Berikan umpan balik terstruktur untuk kandidat dan buat rekomendasi akhir.</p>
                        
                        <form action="{{ route('interviewer.assessments.store', $interview->id) }}" method="POST">
                            @csrf

                            <!-- Profil Kandidat Section -->
                            <div class="mb-8 pb-6 border-b border-gray-200">
                                <h3 class="text-base font-semibold text-gray-900 mb-4">Profil Kandidat</h3>
                                <p class="text-sm text-gray-600 mb-4">Detail kandidat ini tersedia di bawah.</p>
                                
                                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-2xl">
                                            {{ substr($interview->application->candidate->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $interview->application->candidate->name }}</h4>
                                        <p class="text-sm text-gray-600">Posisi Dilamar: {{ $interview->application->jobPosting->title }}</p>
                                        <p class="text-sm text-gray-500">ID Aplikasi: {{ $interview->application->application_code ?? $interview->application->code ?? 'APP-' . $interview->application->id }}</p>
                                    </div>
                                    <a href="#" class="ml-auto text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        Lihat Profil Lengkap →
                                    </a>
                                </div>
                            </div>

                            <!-- Kriteria Penilaian Section -->
                            <div class="mb-8">
                                <h3 class="text-base font-semibold text-gray-900 mb-4">Kriteria Penilaian</h3>
                                <p class="text-sm text-gray-600 mb-6">Berikan nilai untuk setiap kriteria berdasarkan performa kandidat.</p>
                                
                                <div class="space-y-8">
                                    <!-- Keterampilan Teknis -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                                            Keterampilan Teknis
                                        </label>
                                        <p class="text-xs text-gray-600 mb-3">Kemampuan kandidat dalam menyelesaikan masalah teknis dan pemahaman konsep IT.</p>
                                        <div class="flex items-center gap-4">
                                            <input 
                                                type="range" 
                                                name="technical_score" 
                                                id="technical_score"
                                                min="0" 
                                                max="100" 
                                                value="{{ old('technical_score', 0) }}"
                                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                                oninput="document.getElementById('technical_score_output').value = this.value"
                                            >
                                            <output id="technical_score_output" class="w-12 text-right text-lg font-bold text-gray-900">{{ old('technical_score', 0) }}</output>
                                        </div>
                                        @error('technical_score')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Kemampuan Komunikasi -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                                            Kemampuan Komunikasi
                                        </label>
                                        <p class="text-xs text-gray-600 mb-3">Kejelasan, kemampuan, dan kemampuan kandidat untuk menyampaikan ide-ide secara efektif.</p>
                                        <div class="space-y-2">
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="communication_skill" value="sangat_baik" {{ old('communication_skill') == 'sangat_baik' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Sangat Baik</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="communication_skill" value="baik" {{ old('communication_skill') == 'baik' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Baik</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="communication_skill" value="cukup" {{ old('communication_skill') == 'cukup' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Cukup</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="communication_skill" value="kurang" {{ old('communication_skill') == 'kurang' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Kurang</span>
                                            </label>
                                        </div>
                                        @error('communication_skill')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Pemecahan Masalah -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                                            Pemecahan Masalah
                                        </label>
                                        <p class="text-xs text-gray-600 mb-3">Pendekatan kandidat terhadap masalah kompleks dan efektivitas solusinya.</p>
                                        <div class="flex items-center gap-4">
                                            <input 
                                                type="range" 
                                                name="problem_solving_score" 
                                                id="problem_solving_score"
                                                min="0" 
                                                max="100" 
                                                value="{{ old('problem_solving_score', 0) }}"
                                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                                oninput="document.getElementById('problem_solving_score_output').value = this.value"
                                            >
                                            <output id="problem_solving_score_output" class="w-12 text-right text-lg font-bold text-gray-900">{{ old('problem_solving_score', 0) }}</output>
                                        </div>
                                        @error('problem_solving_score')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Potensi Kerja Tim -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                                            Potensi Kerja Tim
                                        </label>
                                        <p class="text-xs text-gray-600 mb-3">Kemampuan kandidat untuk bekerja/kolaborasi dan berkontribusi dalam lingkungan tim.</p>
                                        <div class="space-y-2">
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="teamwork_potential" value="tinggi" {{ old('teamwork_potential') == 'tinggi' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Tinggi</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="teamwork_potential" value="sedang" {{ old('teamwork_potential') == 'sedang' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Sedang</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="radio" name="teamwork_potential" value="rendah" {{ old('teamwork_potential') == 'rendah' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                                <span class="ml-3 text-sm text-gray-900">Rendah</span>
                                            </label>
                                        </div>
                                        @error('teamwork_potential')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Tambahan Section -->
                            <div class="mb-8 pb-6 border-b border-gray-200">
                                <h3 class="text-base font-semibold text-gray-900 mb-4">Catatan Tambahan</h3>
                                <p class="text-sm text-gray-600 mb-4">Sertakan catatan atau observasi penting lainnya tentang kandidat.</p>
                                
                                <textarea 
                                    name="additional_notes" 
                                    rows="5" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    placeholder="Tuliskan catatan detail Anda di sini..."
                                >{{ old('additional_notes') }}</textarea>
                                @error('additional_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rekomendasi Akhir Section -->
                            <div class="mb-8">
                                <h3 class="text-base font-semibold text-gray-900 mb-4">Rekomendasi Akhir</h3>
                                <p class="text-sm text-gray-600 mb-4">Pilih rekomendasi keseluruhan untuk kandidat ini.</p>
                                
                                <div class="space-y-2">
                                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                        <input type="radio" name="recommendation" value="sangat_direkomendasikan" {{ old('recommendation') == 'sangat_direkomendasikan' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
                                        <span class="ml-3 text-sm font-medium text-gray-900">Sangat Direkomendasikan</span>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="recommendation" value="direkomendasikan" {{ old('recommendation') == 'direkomendasikan' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                        <span class="ml-3 text-sm font-medium text-gray-900">Direkomendasikan</span>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                        <input type="radio" name="recommendation" value="tidak_direkomendasikan" {{ old('recommendation') == 'tidak_direkomendasikan' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                                        <span class="ml-3 text-sm font-medium text-gray-900">Tidak Direkomendasikan</span>
                                    </label>
                                </div>
                                @error('recommendation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Overall Score (Hidden/Auto-calculated atau bisa ditampilkan) -->
                            <input type="hidden" name="overall_score" id="overall_score" value="{{ old('overall_score', 50) }}">

                            <!-- Submit Button -->
                            <div class="flex gap-3 pt-6 border-t border-gray-200">
                                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold transition">
                                    Kirim Penilaian
                                </button>
                                <a href="{{ route('interviewer.interviews.index') }}" 
                                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Auto-calculate Overall Score based on inputs -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const technicalSkills = document.getElementById('technical_skills');
                            const problemSolving = document.getElementById('problem_solving');
                            const overallScore = document.getElementById('overall_score');
                            
                            function calculateOverallScore() {
                                const tech = parseInt(technicalSkills.value) || 0;
                                const prob = parseInt(problemSolving.value) || 0;
                                const overall = Math.round((tech + prob) / 2);
                                overallScore.value = overall;
                            }
                            
                            technicalSkills.addEventListener('input', calculateOverallScore);
                            problemSolving.addEventListener('input', calculateOverallScore);
                            calculateOverallScore();
                        });
                    </script>
                @elseif($interview->assessment)
                    <!-- Show Assessment Result -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Hasil Penilaian</h2>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Technical Skills</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $interview->assessment->technical_skills }}/100</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Communication Skills</p>
                                <p class="text-xl font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $interview->assessment->communication_skills) }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Problem Solving</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $interview->assessment->problem_solving }}/100</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Teamwork Potential</p>
                                <p class="text-xl font-semibold text-gray-900 capitalize">{{ $interview->assessment->teamwork_potential }}</p>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg mb-6">
                            <p class="text-sm text-blue-600 font-medium">Overall Score</p>
                            <p class="text-4xl font-bold text-blue-600">{{ $interview->assessment->overall_score }}/100</p>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-2">Recommendation</p>
                            <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold
                                {{ $interview->assessment->recommendation == 'sangat_direkomendasikan' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $interview->assessment->recommendation == 'direkomendasikan' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $interview->assessment->recommendation == 'cukup' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $interview->assessment->recommendation == 'tidak_direkomendasikan' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucwords(str_replace('_', ' ', $interview->assessment->recommendation)) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">Notes</p>
                            <p class="text-gray-900">{{ $interview->assessment->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Candidate Documents -->
                @if($interview->application->cv_path || $interview->application->portfolio_path)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Kandidat</h2>
                        <div class="space-y-3">
                            @if($interview->application->cv_path)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">CV / Resume</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($interview->application->cv_path) }}" 
                                       target="_blank"
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        Download
                                    </a>
                                </div>
                            @endif

                            @if($interview->application->portfolio_path)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-briefcase text-blue-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">Portfolio</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($interview->application->portfolio_path) }}" 
                                       target="_blank"
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        Download
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>


            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Interview Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Interview</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="far fa-calendar text-gray-400 mr-2"></i>
                                {{ $interview->scheduled_at->format('d M Y') }}
                            </p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="far fa-clock text-gray-400 mr-2"></i>
                                {{ $interview->scheduled_at->format('H:i') }} - {{ $interview->scheduled_at->addMinutes((int)($interview->duration ?? 60))->format('H:i') }} WIB
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                {{ $interview->location }}
                            </p>
                        </div>

                        @if($interview->interview_type)
                            <div>
                                <p class="text-sm text-gray-600">Tipe Interview</p>
                                <p class="font-medium text-gray-900 mt-1 capitalize">
                                    <i class="fas fa-video text-gray-400 mr-2"></i>
                                    {{ str_replace('_', ' ', $interview->interview_type) }}
                                </p>
                            </div>
                        @endif

                        @if($interview->duration)
                            <div>
                                <p class="text-sm text-gray-600">Durasi</p>
                                <p class="font-medium text-gray-900 mt-1">
                                    <i class="far fa-hourglass text-gray-400 mr-2"></i>
                                    {{ $interview->duration }} menit
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($interview->notes)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h3>
                        <p class="text-gray-700">{{ $interview->notes }}</p>
                    </div>
                @endif

                <!-- Candidate Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Tambahan</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Pendidikan</p>
                            <p class="font-medium text-gray-900">{{ $interview->application->education ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pengalaman</p>
                            <p class="font-medium text-gray-900">{{ $interview->application->experience ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Melamar</p>
                            <p class="font-medium text-gray-900">{{ $interview->application->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-interviewer-layout>

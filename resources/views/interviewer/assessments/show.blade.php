<x-interviewer-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('interviewer.assessments.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Penilaian</h1>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Candidate Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kandidat</h2>
                
                <div class="flex items-start gap-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-3xl">
                            {{ substr($assessment->interview->application->candidate->name, 0, 1) }}
                        </span>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ $assessment->interview->application->candidate->name }}</h3>
                        <p class="text-gray-600 mt-1">{{ $assessment->interview->application->candidate->email }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-sm text-gray-500">Telepon</p>
                                <p class="font-medium text-gray-900">{{ $assessment->interview->application->phone ?? $assessment->interview->application->candidate->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Posisi yang Dilamar</p>
                                <p class="font-medium text-gray-900">{{ $assessment->interview->application->jobPosting->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $assessment->interview->application->jobPosting->location ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Interview</p>
                                <p class="font-medium text-gray-900">{{ $assessment->interview->scheduled_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Scores -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Hasil Penilaian</h2>
                
                <!-- Overall Score - Highlighted -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg mb-6 text-white">
                    <p class="text-sm font-medium opacity-90">Overall Score</p>
                    <p class="text-5xl font-bold mt-2">{{ $assessment->overall_score }}<span class="text-2xl opacity-75">/100</span></p>
                    <div class="w-full bg-white/20 rounded-full h-3 mt-4">
                        <div class="bg-white h-3 rounded-full transition-all duration-500" style="width: {{ $assessment->overall_score }}%"></div>
                    </div>
                </div>

                <!-- Individual Scores -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Technical Skills</p>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $assessment->technical_skills }}</p>
                            <span class="text-gray-500">/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $assessment->technical_skills }}%"></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Communication Skills</p>
                        <p class="text-2xl font-semibold text-gray-900 capitalize mt-2">
                            {{ str_replace('_', ' ', $assessment->communication_skills) }}
                        </p>
                        @php
                            $commWidth = [
                                'sangat_baik' => 100,
                                'baik' => 75,
                                'cukup' => 50,
                                'kurang' => 25,
                            ][$assessment->communication_skills] ?? 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $commWidth }}%"></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Problem Solving</p>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $assessment->problem_solving }}</p>
                            <span class="text-gray-500">/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $assessment->problem_solving }}%"></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Teamwork Potential</p>
                        <p class="text-2xl font-semibold text-gray-900 capitalize mt-2">
                            {{ $assessment->teamwork_potential }}
                        </p>
                        @php
                            $teamWidth = [
                                'tinggi' => 100,
                                'sedang' => 60,
                                'rendah' => 30,
                            ][$assessment->teamwork_potential] ?? 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $teamWidth }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendation -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Rekomendasi</h2>
                
                <div class="flex items-center gap-4">
                    @php
                        $recIcon = [
                            'sangat_direkomendasikan' => 'fa-thumbs-up',
                            'direkomendasikan' => 'fa-check-circle',
                            'cukup' => 'fa-minus-circle',
                            'tidak_direkomendasikan' => 'fa-times-circle',
                        ][$assessment->recommendation] ?? 'fa-circle';
                    @endphp
                    <div class="p-4 rounded-full 
                        {{ $assessment->recommendation == 'sangat_direkomendasikan' ? 'bg-green-100' : '' }}
                        {{ $assessment->recommendation == 'direkomendasikan' ? 'bg-blue-100' : '' }}
                        {{ $assessment->recommendation == 'cukup' ? 'bg-yellow-100' : '' }}
                        {{ $assessment->recommendation == 'tidak_direkomendasikan' ? 'bg-red-100' : '' }}">
                        <i class="fas {{ $recIcon }} text-3xl
                            {{ $assessment->recommendation == 'sangat_direkomendasikan' ? 'text-green-600' : '' }}
                            {{ $assessment->recommendation == 'direkomendasikan' ? 'text-blue-600' : '' }}
                            {{ $assessment->recommendation == 'cukup' ? 'text-yellow-600' : '' }}
                            {{ $assessment->recommendation == 'tidak_direkomendasikan' ? 'text-red-600' : '' }}"></i>
                    </div>
                    <div>
                        <span class="inline-flex px-6 py-2 rounded-full text-lg font-semibold
                            {{ $assessment->recommendation == 'sangat_direkomendasikan' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $assessment->recommendation == 'direkomendasikan' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $assessment->recommendation == 'cukup' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $assessment->recommendation == 'tidak_direkomendasikan' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucwords(str_replace('_', ' ', $assessment->recommendation)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Interview</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-line leading-relaxed">{{ $assessment->notes }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Penilaian Dibuat</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $assessment->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Interview</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $assessment->interview->scheduled_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-file text-gray-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Lamaran Diterima</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $assessment->interview->application->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interview Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Interview</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-medium text-gray-900 mt-1">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            {{ $assessment->interview->location }}
                        </p>
                    </div>

                    @if($assessment->interview->interview_type)
                        <div>
                            <p class="text-sm text-gray-600">Tipe</p>
                            <p class="font-medium text-gray-900 mt-1 capitalize">
                                <i class="fas fa-video text-gray-400 mr-2"></i>
                                {{ str_replace('_', ' ', $assessment->interview->interview_type) }}
                            </p>
                        </div>
                    @endif

                    @if($assessment->interview->duration)
                        <div>
                            <p class="text-sm text-gray-600">Durasi</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="far fa-hourglass text-gray-400 mr-2"></i>
                                {{ $assessment->interview->duration }} menit
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Status Interview</p>
                        <span class="inline-flex px-3 py-1 mt-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($assessment->interview->application->cv_path || $assessment->interview->application->portfolio_path)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
                    
                    <div class="space-y-2">
                        @if($assessment->interview->application->cv_path)
                            <a href="{{ Storage::url($assessment->interview->application->cv_path) }}" 
                               target="_blank"
                               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                    <span class="text-sm font-medium text-gray-900">CV / Resume</span>
                                </div>
                                <i class="fas fa-external-link-alt text-gray-400 text-sm"></i>
                            </a>
                        @endif

                        @if($assessment->interview->application->portfolio_path)
                            <a href="{{ Storage::url($assessment->interview->application->portfolio_path) }}" 
                               target="_blank"
                               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center">
                                    <i class="fas fa-briefcase text-blue-500 text-xl mr-3"></i>
                                    <span class="text-sm font-medium text-gray-900">Portfolio</span>
                                </div>
                                <i class="fas fa-external-link-alt text-gray-400 text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <a href="{{ route('interviewer.interviews.show', $assessment->interview->id) }}" 
                   class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mb-3">
                    <i class="fas fa-eye mr-2"></i>Lihat Detail Interview
                </a>
                <a href="{{ route('interviewer.assessments.index') }}" 
                   class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>
</x-interviewer-layout>

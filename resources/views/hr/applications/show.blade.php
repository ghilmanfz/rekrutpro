<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Lamaran
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('hr.applications.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                        ← Kembali ke Daftar Lamaran
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $application->candidate_name }}</h1>
                    <p class="text-gray-600 mt-1">Kode: <span class="font-mono font-semibold">{{ $application->application_code }}</span></p>
                </div>
                
                @php
                    $statusColors = [
                        'submitted' => 'bg-yellow-100 text-yellow-800',
                        'screening_passed' => 'bg-blue-100 text-blue-800',
                        'interview_scheduled' => 'bg-purple-100 text-purple-800',
                        'interview_passed' => 'bg-green-100 text-green-800',
                        'offered' => 'bg-indigo-100 text-indigo-800',
                        'hired' => 'bg-green-600 text-white',
                        'rejected_admin' => 'bg-red-100 text-red-800',
                        'rejected_interview' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'submitted' => 'Baru Diterima',
                        'screening_passed' => 'Lolos Screening',
                        'interview_scheduled' => 'Terjadwal Interview',
                        'interview_passed' => 'Lolos Interview',
                        'offered' => 'Ditawarkan',
                        'hired' => 'Diterima Kerja',
                        'rejected_admin' => 'Ditolak Admin',
                        'rejected_interview' => 'Ditolak Interview',
                    ];
                @endphp
                <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$application->status] ?? ucfirst($application->status) }}
                </span>
            </div>

             
            @if($application->hasProfileChangedSinceApply())
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">
                            Kandidat telah mengupdate profil setelah melamar
                        </p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Data di bawah ini menampilkan perbandingan antara data saat melamar (snapshot) dan data profil terkini.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                 
                <div class="lg:col-span-3 space-y-6">
                     
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Kandidat
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             
                            <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                                <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Data Saat Melamar
                                </h3>
                                <div class="text-xs text-blue-600 mb-3">
                                    Snapshot: {{ \Carbon\Carbon::parse($application->candidate_snapshot['snapshot_at'] ?? $application->created_at)->format('d M Y, H:i') }}
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        @if($application->candidate_profile_photo)
                                        <img src="{{ Storage::url($application->candidate_profile_photo) }}" 
                                             alt="Profile" 
                                             class="w-16 h-16 rounded-full object-cover border-2 border-blue-300">
                                        @else
                                        <div class="w-16 h-16 rounded-full bg-blue-200 flex items-center justify-center border-2 border-blue-300">
                                            <span class="text-blue-700 font-bold text-xl">
                                                {{ substr($application->candidate_name, 0, 1) }}
                                            </span>
                                        </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $application->candidate_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $application->candidate_email }}</p>
                                            <p class="text-sm text-gray-600">{{ $application->candidate_phone }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-blue-200">
                                        <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $application->candidate_address }}</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 pt-2">
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Tanggal Lahir</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $application->candidate_birth_date ? \Carbon\Carbon::parse($application->candidate_birth_date)->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Jenis Kelamin</p>
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($application->candidate_gender) }}</p>
                                        </div>
                                    </div>

                                     
                                    @php
                                        $education = $application->candidate_education;
                                        if (is_string($education)) {
                                            $education = json_decode($education, true) ?? [];
                                        }
                                        $education = is_array($education) ? $education : [];
                                    @endphp
                                    @if(count($education) > 0)
                                    <div class="pt-3 border-t border-blue-200">
                                        <p class="text-xs text-gray-600 mb-2">Pendidikan</p>
                                        @foreach($education as $edu)
                                        <div class="text-sm bg-white rounded p-2 mb-2">
                                            <p class="font-medium text-gray-900">{{ $edu['degree'] ?? '-' }} - {{ $edu['major'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-600">{{ $edu['institution'] ?? '-' }} ({{ $edu['graduation_year'] ?? $edu['year'] ?? '-' }})</p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif

                                     
                                    @php
                                        $experience = $application->candidate_experience;
                                        if (is_string($experience)) {
                                            $experience = json_decode($experience, true) ?? [];
                                        }
                                        $experience = is_array($experience) ? $experience : [];
                                    @endphp
                                    @if(count($experience) > 0)
                                    <div class="pt-3 border-t border-blue-200">
                                        <p class="text-xs text-gray-600 mb-2">Pengalaman Kerja</p>
                                        @foreach($experience as $exp)
                                        <div class="text-sm bg-white rounded p-2 mb-2">
                                            <p class="font-medium text-gray-900">{{ $exp['position'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-600">{{ $exp['company'] ?? '-' }} ({{ $exp['duration'] ?? ($exp['start_date'] ?? '-') . ' - ' . ($exp['end_date'] ?? 'Present') }})</p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                             
                            <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                                <h3 class="font-semibold text-green-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    Data Profil Terkini
                                </h3>
                                <div class="text-xs text-green-600 mb-3">
                                    Last Update: {{ $application->candidate->updated_at->format('d M Y, H:i') }}
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        @if($application->candidate->profile_photo)
                                        <img src="{{ Storage::url($application->candidate->profile_photo) }}" 
                                             alt="Profile" 
                                             class="w-16 h-16 rounded-full object-cover border-2 border-green-300">
                                        @else
                                        <div class="w-16 h-16 rounded-full bg-green-200 flex items-center justify-center border-2 border-green-300">
                                            <span class="text-green-700 font-bold text-xl">
                                                {{ substr($application->candidate->name, 0, 1) }}
                                            </span>
                                        </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">
                                                {{ $application->candidate->name }}
                                                @if($application->candidate_name !== $application->candidate->name)
                                                <span class="text-xs text-orange-600 font-normal">(berubah)</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $application->candidate->email }}
                                                @if($application->candidate_email !== $application->candidate->email)
                                                <span class="text-xs text-orange-600">(berubah)</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $application->candidate->phone }}
                                                @if($application->candidate_phone !== $application->candidate->phone)
                                                <span class="text-xs text-orange-600">(berubah)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-t border-green-200">
                                        <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $application->current_candidate_address }}
                                            @if($application->current_candidate_address_changed)
                                            <span class="text-xs text-orange-600">(berubah)</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 pt-2">
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Tanggal Lahir</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $application->current_candidate_birth_date ? \Carbon\Carbon::parse($application->current_candidate_birth_date)->format('d M Y') : '-' }}
                                                @if($application->current_candidate_birth_date_changed)
                                                <span class="text-xs text-orange-600">(berubah)</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Jenis Kelamin</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ ucfirst($application->current_candidate_gender ?? '-') }}
                                                @if($application->current_candidate_gender_changed)
                                                <span class="text-xs text-orange-600">(berubah)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                     
                                    @php
                                        $currentEducation = $application->current_candidate_education;
                                    @endphp
                                    @if(count($currentEducation) > 0)
                                    <div class="pt-3 border-t border-green-200">
                                        <p class="text-xs text-gray-600 mb-2">Pendidikan</p>
                                        @foreach($currentEducation as $edu)
                                        <div class="text-sm bg-white rounded p-2 mb-2">
                                            <p class="font-medium text-gray-900">{{ $edu['degree'] ?? '-' }} - {{ $edu['major'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-600">{{ $edu['institution'] ?? '-' }} ({{ $edu['year'] ?? '-' }})</p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif

                                     
                                    @php
                                        $currentExperience = $application->current_candidate_experience;
                                    @endphp
                                    @if(count($currentExperience) > 0)
                                    <div class="pt-3 border-t border-green-200">
                                        <p class="text-xs text-gray-600 mb-2">Pengalaman Kerja</p>
                                        @foreach($currentExperience as $exp)
                                        <div class="text-sm bg-white rounded p-2 mb-2">
                                            <p class="font-medium text-gray-900">{{ $exp['position'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-600">{{ $exp['company'] ?? '-' }} ({{ $exp['duration'] ?? '-' }})</p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                     
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Posisi yang Dilamar
                        </h2>
                        
                        <div class="space-y-3">
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-600 mb-1">Judul Posisi</p>
                                <p class="font-bold text-lg text-blue-900">{{ $application->jobPosting->title }}</p>
                                <p class="text-sm text-blue-700 mt-1">{{ $application->jobPosting->code }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Divisi</p>
                                    <p class="font-semibold text-gray-900">{{ $application->jobPosting->division->name }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Lokasi</p>
                                    <p class="font-semibold text-gray-900">{{ $application->jobPosting->location->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                     
                    @if($application->cover_letter)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Cover Letter
                        </h2>
                        <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg">
                            {{ $application->cover_letter }}
                        </div>
                    </div>
                    @endif

                     
                    @if($application->interviews->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                            Hasil Interview
                        </h2>

                        @foreach($application->interviews as $interview)
                        <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden @if(!$loop->last) mb-6 @endif">
                             
                            <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-b">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold text-gray-700">
                                        Interview #{{ $loop->iteration }} — {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('d M Y, H:i') }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($interview->interview_type === 'video') bg-blue-100 text-blue-700
                                        @elseif($interview->interview_type === 'onsite') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $interview->interview_type === 'video' ? 'Video' : ($interview->interview_type === 'onsite' ? 'On-site' : 'Telepon') }}
                                    </span>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($interview->status === 'scheduled') bg-yellow-100 text-yellow-800
                                    @elseif($interview->status === 'completed') bg-green-100 text-green-800
                                    @elseif($interview->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    @if($interview->status === 'scheduled') Terjadwal
                                    @elseif($interview->status === 'completed') Selesai
                                    @elseif($interview->status === 'cancelled') Dibatalkan
                                    @else Dijadwalkan Ulang
                                    @endif
                                </span>
                            </div>

                             
                            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Pewawancara</p>
                                    <p class="text-sm font-medium text-gray-900">{{ optional($interview->interviewer)->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Durasi</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $interview->duration }} menit</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Lokasi / Link</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $interview->location ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Catatan Interview</p>
                                    <p class="text-sm text-gray-700">{{ $interview->notes ?? '-' }}</p>
                                </div>
                            </div>

                             
                            @if($interview->assessment)
                            @php $a = $interview->assessment; @endphp
                            <div class="border-t border-gray-200 p-4 bg-purple-50">
                                <h3 class="text-sm font-semibold text-purple-900 mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Hasil Penilaian Interview
                                </h3>

                                 
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                    <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Teknis</p>
                                        @if($a->technical_score !== null)
                                        <p class="text-2xl font-bold {{ $a->technical_score >= 75 ? 'text-green-600' : ($a->technical_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $a->technical_score }}
                                        </p>
                                        <p class="text-xs text-gray-400">/100</p>
                                        @else
                                        <p class="text-sm text-gray-400">-</p>
                                        @endif
                                    </div>
                                    <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Problem Solving</p>
                                        @if($a->problem_solving_score !== null)
                                        <p class="text-2xl font-bold {{ $a->problem_solving_score >= 75 ? 'text-green-600' : ($a->problem_solving_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $a->problem_solving_score }}
                                        </p>
                                        <p class="text-xs text-gray-400">/100</p>
                                        @else
                                        <p class="text-sm text-gray-400">-</p>
                                        @endif
                                    </div>
                                    <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Komunikasi</p>
                                        @if($a->communication_skill)
                                        <p class="text-sm font-semibold {{ $a->communication_skill === 'sangat_baik' ? 'text-green-600' : ($a->communication_skill === 'baik' ? 'text-blue-600' : ($a->communication_skill === 'cukup' ? 'text-yellow-600' : 'text-red-600')) }}">
                                            {{ str_replace('_', ' ', ucfirst($a->communication_skill)) }}
                                        </p>
                                        @else
                                        <p class="text-sm text-gray-400">-</p>
                                        @endif
                                    </div>
                                    <div class="bg-white rounded-lg p-3 text-center shadow-sm border-2 border-purple-200">
                                        <p class="text-xs text-gray-500 mb-1">Nilai Akhir</p>
                                        @if($a->overall_score !== null)
                                        <p class="text-2xl font-bold {{ $a->overall_score >= 75 ? 'text-green-600' : ($a->overall_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($a->overall_score, 1) }}
                                        </p>
                                        <p class="text-xs text-gray-400">/100</p>
                                        @else
                                        <p class="text-sm text-gray-400">-</p>
                                        @endif
                                    </div>
                                </div>

                                 
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                    @if($a->teamwork_potential)
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Potensi Kerjasama Tim</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $a->teamwork_potential === 'tinggi' ? 'bg-green-100 text-green-700' : ($a->teamwork_potential === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ ucfirst($a->teamwork_potential) }}
                                        </span>
                                    </div>
                                    @endif
                                    @if($a->strengths)
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Kelebihan</p>
                                        <p class="text-sm text-gray-700">{{ $a->strengths }}</p>
                                    </div>
                                    @endif
                                    @if($a->weaknesses)
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1">Kekurangan</p>
                                        <p class="text-sm text-gray-700">{{ $a->weaknesses }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if($a->additional_notes)
                                <div class="bg-white rounded-lg p-3 shadow-sm mb-3">
                                    <p class="text-xs text-gray-500 mb-1">Catatan Tambahan</p>
                                    <p class="text-sm text-gray-700">{{ $a->additional_notes }}</p>
                                </div>
                                @endif

                                 
                                @if($a->recommendation)
                                <div class="flex items-center gap-2">
                                    <p class="text-xs text-gray-500">Rekomendasi:</p>
                                    <span class="px-3 py-1 text-sm font-bold rounded-full
                                        @if($a->recommendation === 'sangat_direkomendasikan') bg-green-600 text-white
                                        @elseif($a->recommendation === 'direkomendasikan') bg-blue-500 text-white
                                        @else bg-red-500 text-white
                                        @endif">
                                        @if($a->recommendation === 'sangat_direkomendasikan') ✓ Sangat Direkomendasikan
                                        @elseif($a->recommendation === 'direkomendasikan') ✓ Direkomendasikan
                                        @else ✗ Tidak Direkomendasikan
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="border-t border-gray-200 px-4 py-3 bg-gray-50 text-sm text-gray-500 italic">
                                Belum ada penilaian untuk interview ini.
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                     
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Dokumen Lamaran
                        </h2>
                        
                        @php
                            $cvPath = $application->cv_file;
                            $portfolioPath = $application->portfolio_file;
                            $hasCv = filled($cvPath) && Storage::disk('public')->exists($cvPath);
                            $hasPortfolio = filled($portfolioPath) && Storage::disk('public')->exists($portfolioPath);
                        @endphp

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 rounded-lg border {{ $hasCv ? 'bg-gradient-to-r from-red-50 to-red-100 border-red-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 {{ $hasCv ? 'bg-red-500' : 'bg-gray-400' }} rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Curriculum Vitae</p>
                                        <p class="text-sm text-gray-600">{{ $hasCv ? 'PDF Document' : 'Tidak ada file yang bisa diunduh' }}</p>
                                    </div>
                                </div>
                                @if($hasCv)
                                    <a href="{{ Storage::url($cvPath) }}" target="_blank" 
                                       class="px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 font-medium text-sm">
                                        Download
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                                        Kandidat belum melampirkan CV
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-lg border {{ $hasPortfolio ? 'bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 {{ $hasPortfolio ? 'bg-blue-500' : 'bg-gray-400' }} rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Portfolio</p>
                                        <p class="text-sm text-gray-600">{{ $hasPortfolio ? 'PDF Document' : 'Tidak ada file yang bisa diunduh' }}</p>
                                    </div>
                                </div>
                                @if($hasPortfolio)
                                    <a href="{{ Storage::url($portfolioPath) }}" target="_blank" 
                                       class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-medium text-sm">
                                        Download
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                                        Kandidat belum melampirkan Portfolio
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                 
                <div class="space-y-6">
                     
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
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($application->offer->status === 'pending') Menunggu Respons
                                    @elseif($application->offer->status === 'accepted') Diterima
                                    @elseif($application->offer->status === 'rejected') Ditolak
                                    @else Kadaluarsa
                                    @endif
                                </span>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Gaji Ditawarkan</p>
                                <p class="font-bold text-green-600 text-lg">Rp {{ number_format($application->offer->salary, 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Berlaku Hingga</p>
                                <p class="font-semibold text-gray-900">{{ $application->offer->valid_until->format('d M Y') }}</p>
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
                    @elseif(in_array($application->status, ['interview_passed', 'offered']))
                    <div class="bg-white rounded-lg shadow-sm p-6 border-2 border-indigo-100">
                        <h2 class="text-lg font-bold text-indigo-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-gift text-indigo-600"></i>
                            Buat Penawaran Kerja
                        </h2>

                        <form action="{{ route('hr.offers.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $application->id }}">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Posisi Penawaran</label>
                                <input type="text" name="position_title" value="{{ old('position_title', $application->jobPosting->title) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Ditawarkan</label>
                                <input type="number" name="salary" value="{{ old('salary') }}"
                                       min="0" step="100000"
                                       max="{{ \App\Models\Offer::MAX_SALARY }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Contoh: 10000000"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Maksimal Rp {{ number_format(\App\Models\Offer::MAX_SALARY, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kontrak</label>
                                <select name="contract_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                    <option value="full_time" {{ old('contract_type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ old('contract_type') === 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('contract_type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="internship" {{ old('contract_type') === 'internship' ? 'selected' : '' }}>Magang</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ old('start_date', now()->addDays(30)->format('Y-m-d')) }}"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Berlaku Hingga</label>
                                <input type="date" name="valid_until" value="{{ old('valid_until', now()->addDays(14)->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Benefit</label>
                                <textarea name="benefits" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Tunjangan kesehatan, laptop, cuti tahunan...">{{ old('benefits') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                                <textarea name="internal_notes" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Catatan untuk HR...">{{ old('internal_notes') }}</textarea>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold shadow-md transition">
                                Kirim Penawaran
                            </button>
                        </form>
                    </div>
                    @endif

                     
                    @if($application->status === 'interview_scheduled')
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <button onclick="openInterviewModal()" 
                                style="background-color: #7c3aed; color: white;" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition flex items-center justify-center gap-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            📅 Jadwalkan Interview
                        </button>
                    </div>
                    @endif

                     
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Ubah Status
                        </h2>
                        
                        <form action="{{ route('hr.applications.update-status', $application->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Pilih Status</option>
                                    <option value="screening_passed">Lolos Screening</option>
                                    <option value="interview_scheduled">Terjadwal Interview</option>
                                    <option value="interview_passed">Lolos Interview</option>
                                    <option value="offered">Ditawarkan</option>
                                    <option value="hired">Diterima Kerja</option>
                                    <option value="rejected_admin">Ditolak (Admin)</option>
                                    <option value="rejected_interview">Ditolak (Interview)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tambahkan catatan..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition">
                                Update Status
                            </button>
                        </form>
                    </div>

                     
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Timeline Rekrutmen
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Aplikasi Diterima</p>
                                    <p class="text-xs text-gray-500">{{ $application->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            @if($application->screening_passed_at)
                            <div class="flex gap-3 border-l-2 border-blue-200 ml-4 pl-7 -mt-2">
                                <div class="flex-shrink-0 -ml-11">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Lolos Screening</p>
                                    <p class="text-xs text-gray-500">{{ $application->screening_passed_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     
    <div id="interviewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Jadwalkan Interview</h3>
                <button onclick="closeInterviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('hr.interviews.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interviewer</label>
                        <select name="interviewer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                            <option value="">Pilih Interviewer</option>
                            @foreach($interviewers as $interviewer)
                                <option value="{{ $interviewer->id }}">{{ $interviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu</label>
                        <input type="datetime-local" name="scheduled_at" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" 
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit)</label>
                        <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                            <option value="30">30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60" selected>60 menit</option>
                            <option value="90">90 menit</option>
                            <option value="120">120 menit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Interview</label>
                        <select name="interview_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                            <option value="video">Video Call</option>
                            <option value="onsite">On-site</option>
                            <option value="phone">Telepon</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi / Link</label>
                        <input type="text" name="location" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                               placeholder="Ruang Meeting / Zoom Link" 
                               required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                  placeholder="Catatan tambahan untuk interview..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeInterviewModal()" 
                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-medium transition">
                        Jadwalkan Interview
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openInterviewModal() {
            document.getElementById('interviewModal').classList.remove('hidden');
        }

        function closeInterviewModal() {
            document.getElementById('interviewModal').classList.add('hidden');
        }

        document.getElementById('interviewModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeInterviewModal();
            }
        });
    </script>
</x-hr-layout>

<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Lamaran
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('hr.applications.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                    ← Kembali ke Daftar Lamaran
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $application->candidate->name }}</h1>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Candidate Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Kandidat
                    </h2>
                    
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">
                                    {{ substr($application->candidate->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $application->candidate->name }}</h3>
                            <div class="flex items-center gap-2 text-gray-600 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $application->candidate->email }}
                            </div>
                            @if($application->candidate->phone)
                            <div class="flex items-center gap-2 text-gray-600 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $application->candidate->phone }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Pendidikan</p>
                            <p class="font-semibold text-gray-900">{{ $application->education ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Pengalaman</p>
                            <p class="font-semibold text-gray-900">{{ $application->experience ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Ekspektasi Gaji</p>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($application->expected_salary ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Ketersediaan</p>
                            <p class="font-semibold text-gray-900">{{ $application->availability ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Job Info -->
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
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Tipe Pekerjaan</p>
                                <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $application->jobPosting->employment_type)) }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Level</p>
                                <p class="font-semibold text-gray-900">{{ ucfirst($application->jobPosting->level) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
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

                <!-- Documents -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Dokumen Lamaran
                    </h2>
                    
                    <div class="space-y-3">
                        @if($application->cv_path)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Curriculum Vitae</p>
                                    <p class="text-sm text-gray-600">PDF Document</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($application->cv_path) }}" target="_blank" 
                               class="px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 font-medium text-sm">
                                Download
                            </a>
                        </div>
                        @endif

                        @if($application->portfolio_path)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Portfolio</p>
                                    <p class="text-sm text-gray-600">PDF Document</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($application->portfolio_path) }}" target="_blank" 
                               class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-medium text-sm">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!$application->cv_path && !$application->portfolio_path)
                        <p class="text-gray-500 text-center py-8">Tidak ada dokumen yang dilampirkan</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                @if($application->status === 'screening_passed')
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-lg p-6 text-white">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jadwalkan Interview
                    </h3>
                    <button onclick="document.getElementById('scheduleInterviewModal').classList.remove('hidden')" 
                            class="w-full bg-white text-purple-600 py-2 px-4 rounded-lg hover:bg-purple-50 font-medium">
                        Buat Jadwal Interview
                    </button>
                </div>
                @endif

                @if($application->status === 'interview_passed')
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-lg shadow-lg p-6 text-white">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Buat Penawaran
                    </h3>
                    <button onclick="document.getElementById('createOfferModal').classList.remove('hidden')" 
                            class="w-full bg-white text-green-600 py-2 px-4 rounded-lg hover:bg-green-50 font-medium">
                        Kirim Offer Letter
                    </button>
                </div>
                @endif

                <!-- Status Actions -->
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

                <!-- Timeline -->
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

                        @if($application->interview_scheduled_at)
                        <div class="flex gap-3 border-l-2 border-blue-200 ml-4 pl-7 -mt-2">
                            <div class="flex-shrink-0 -ml-11">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Interview Dijadwalkan</p>
                                <p class="text-xs text-gray-500">{{ $application->interview_scheduled_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($application->interview_passed_at)
                        <div class="flex gap-3 border-l-2 border-blue-200 ml-4 pl-7 -mt-2">
                            <div class="flex-shrink-0 -ml-11">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Lolos Interview</p>
                                <p class="text-xs text-gray-500">{{ $application->interview_passed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($application->offered_at)
                        <div class="flex gap-3 border-l-2 border-blue-200 ml-4 pl-7 -mt-2">
                            <div class="flex-shrink-0 -ml-11">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Penawaran Dikirim</p>
                                <p class="text-xs text-gray-500">{{ $application->offered_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($application->hired_at)
                        <div class="flex gap-3 ml-4 pl-7 -mt-2">
                            <div class="flex-shrink-0 -ml-11">
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Diterima Bekerja</p>
                                <p class="text-xs text-gray-500">{{ $application->hired_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Interview Modal -->
    <div id="scheduleInterviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                <h3 class="text-lg font-semibold text-gray-900">Jadwalkan Interview</h3>
                <button onclick="document.getElementById('scheduleInterviewModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('hr.interviews.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pewawancara *</label>
                        <select name="interviewer_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Pewawancara</option>
                            @foreach(\App\Models\User::where('role', 'interviewer')->where('is_active', true)->get() as $interviewer)
                                <option value="{{ $interviewer->id }}">{{ $interviewer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu *</label>
                        <input type="datetime-local" name="scheduled_at" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit) *</label>
                        <input type="number" name="duration" value="60" min="15" max="480" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Interview *</label>
                        <select name="interview_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="onsite">On-site</option>
                            <option value="video">Video Call</option>
                            <option value="phone">Telepon</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                        <input type="text" name="location" required placeholder="Ruang meeting / Link zoom" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3" placeholder="Tambahkan catatan untuk interview..." 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="document.getElementById('scheduleInterviewModal').classList.add('hidden')" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Jadwalkan Interview
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Offer Modal -->
    <div id="createOfferModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                <h3 class="text-lg font-semibold text-gray-900">Buat Penawaran Kerja</h3>
                <button onclick="document.getElementById('createOfferModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('hr.offers.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi *</label>
                        <input type="text" name="position_title" value="{{ $application->jobPosting->title }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gaji yang Ditawarkan *</label>
                        <input type="number" name="salary_offered" min="0" required 
                               placeholder="5000000" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Ekspektasi kandidat: Rp {{ number_format($application->expected_salary ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                        <input type="date" name="start_date" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berlaku Hingga *</label>
                        <input type="date" name="valid_until" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Benefit & Fasilitas</label>
                        <textarea name="benefits" rows="3" placeholder="BPJS, Tunjangan transport, dll..." 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="2" placeholder="Catatan tambahan..." 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="document.getElementById('createOfferModal').classList.add('hidden')" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Kirim Penawaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-hr-layout>
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('hr.applications.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                    ← Kembali ke Daftar Aplikasi
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Lamaran</h1>
                <p class="text-gray-600 mt-1">Kode: <span class="font-mono font-semibold">{{ $application->application_code }}</span></p>
            </div>
            
            @php
                $statusColors = [
                    'submitted' => 'bg-yellow-100 text-yellow-800',
                    'screening_passed' => 'bg-blue-100 text-blue-800',
                    'interview_scheduled' => 'bg-purple-100 text-purple-800',
                    'interview_passed' => 'bg-green-100 text-green-800',
                    'offered' => 'bg-indigo-100 text-indigo-800',
                    'hired' => 'bg-green-100 text-green-800',
                    'rejected_admin' => 'bg-red-100 text-red-800',
                    'rejected_interview' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Candidate Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kandidat</h2>
                    
                    <div class="flex items-start gap-4 mb-6">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-xl">
                                    {{ substr($application->candidate->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $application->candidate->name }}</h3>
                            <p class="text-gray-600">{{ $application->candidate->email }}</p>
                            <p class="text-gray-600">{{ $application->candidate->phone ?? 'No phone' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Pendidikan</p>
                            <p class="font-medium">{{ $application->education ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pengalaman</p>
                            <p class="font-medium">{{ $application->experience ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ekspektasi Gaji</p>
                            <p class="font-medium">Rp {{ number_format($application->expected_salary ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ketersediaan</p>
                            <p class="font-medium">{{ $application->availability ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Job Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Posisi yang Dilamar</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Judul</p>
                            <p class="font-medium text-lg">{{ $application->jobPosting->title }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Divisi</p>
                                <p class="font-medium">{{ $application->jobPosting->division->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium">{{ $application->jobPosting->location->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tipe</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $application->jobPosting->employment_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Level</p>
                                <p class="font-medium">{{ ucfirst($application->jobPosting->level) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
                @if($application->cover_letter)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
                    <div class="prose max-w-none text-gray-700">
                        {{ $application->cover_letter }}
                    </div>
                </div>
                @endif

                <!-- Documents -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h2>
                    
                    <div class="space-y-3">
                        @if($application->cv_path)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                <div>
                                    <p class="font-medium">Curriculum Vitae</p>
                                    <p class="text-sm text-gray-500">PDF Document</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Download
                            </a>
                        </div>
                        @endif

                        @if($application->portfolio_path)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                <div>
                                    <p class="font-medium">Portfolio</p>
                                    <p class="text-sm text-gray-500">PDF Document</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($application->portfolio_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!$application->cv_path && !$application->portfolio_path)
                        <p class="text-gray-500 text-center py-4">Tidak ada dokumen</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubah Status</h2>
                    
                    <form action="{{ route('hr.applications.update-status', $application->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih Status</option>
                                <option value="screening_passed" {{ $application->status == 'screening_passed' ? 'selected' : '' }}>Screening Passed</option>
                                <option value="interview_scheduled" {{ $application->status == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                                <option value="interview_passed" {{ $application->status == 'interview_passed' ? 'selected' : '' }}>Interview Passed</option>
                                <option value="offered" {{ $application->status == 'offered' ? 'selected' : '' }}>Offered</option>
                                <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                <option value="rejected_admin" {{ $application->status == 'rejected_admin' ? 'selected' : '' }}>Rejected (Admin)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tambahkan catatan..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-medium">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                    
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
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
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
@endsection

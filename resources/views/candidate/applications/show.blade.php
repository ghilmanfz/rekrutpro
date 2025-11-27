@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('candidate.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- Application Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $application->jobPosting->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $application->jobPosting->division->name }} - {{ $application->jobPosting->location->name }}</p>
                    <p class="text-sm text-gray-500 mt-2">Kode Lamaran: <span class="font-mono font-semibold">{{ $application->application_code }}</span></p>
                </div>
                @php
                    $statusConfig = [
                        'submitted' => ['label' => 'Menunggu Review', 'color' => 'yellow'],
                        'screening_passed' => ['label' => 'Lolos Screening', 'color' => 'blue'],
                        'interview_scheduled' => ['label' => 'Dijadwalkan Interview', 'color' => 'purple'],
                        'interview_passed' => ['label' => 'Lolos Interview', 'color' => 'green'],
                        'offered' => ['label' => 'Ditawarkan', 'color' => 'indigo'],
                        'hired' => ['label' => 'Diterima', 'color' => 'green'],
                        'rejected_admin' => ['label' => 'Tidak Lolos Screening', 'color' => 'red'],
                        'rejected_interview' => ['label' => 'Tidak Lolos Interview', 'color' => 'red'],
                    ];
                    $config = $statusConfig[$application->status] ?? ['label' => $application->status, 'color' => 'gray'];
                @endphp
                <span class="px-4 py-2 text-sm font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                    {{ $config['label'] }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Lengkap</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Alamat</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education & Experience -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pendidikan & Pengalaman</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Pendidikan</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->education }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pengalaman</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->experience }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ekspektasi Gaji</p>
                            <p class="font-medium text-gray-900 mt-1">Rp {{ number_format($application->expected_salary, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ketersediaan</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->availability }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
                @if($application->cover_letter)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $application->cover_letter }}</p>
                    </div>
                @endif

                <!-- Documents -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h2>
                    <div class="space-y-3">
                        @if($application->cv_path)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">CV / Resume</p>
                                        <p class="text-sm text-gray-500">{{ basename($application->cv_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($application->cv_path) }}" 
                                   target="_blank"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                    Download
                                </a>
                            </div>
                        @endif

                        @if($application->portfolio_path)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Portfolio</p>
                                        <p class="text-sm text-gray-500">{{ basename($application->portfolio_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($application->portfolio_path) }}" 
                                   target="_blank"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                    Download
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Notes -->
                @if($application->status_notes)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h3 class="font-semibold text-blue-900 mb-2">Catatan dari HR</h3>
                        <p class="text-blue-800">{{ $application->status_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900">Lamaran Dikirim</p>
                                <p class="text-sm text-gray-500">{{ $application->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($application->screening_passed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Lolos Screening</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->screening_passed_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->interview_scheduled_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-purple-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Interview Dijadwalkan</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->interview_scheduled_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->interview_passed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Lolos Interview</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->interview_passed_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->offered_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-indigo-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Ditawarkan</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->offered_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->hired_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Diterima</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->hired_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Interview Details -->
                @if($application->interviews()->exists())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Interview</h2>
                        @foreach($application->interviews as $interview)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                                    <p class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($interview->interview_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($interview->interview_time)->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Lokasi</p>
                                    <p class="font-medium text-gray-900">{{ $interview->location }}</p>
                                </div>
                                @if($interview->notes)
                                    <div>
                                        <p class="text-sm text-gray-600">Catatan</p>
                                        <p class="text-gray-900">{{ $interview->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Job Offer -->
                @if($application->offers()->exists())
                    @php $offer = $application->offers()->latest()->first(); @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Penawaran Kerja</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Gaji yang Ditawarkan</p>
                                <p class="font-medium text-gray-900">Rp {{ number_format($offer->offered_salary, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tipe Kontrak</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $offer->contract_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($offer->start_date)->format('d M Y') }}</p>
                            </div>
                            @if($offer->benefits)
                                <div>
                                    <p class="text-sm text-gray-600">Benefits</p>
                                    <p class="text-gray-900">{{ $offer->benefits }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

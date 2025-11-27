@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Kandidat</h1>
            <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Lamaran</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Menunggu Review</p>
                        <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['submitted'] }}</h3>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Interview</p>
                        <h3 class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['interview'] }}</h3>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Lolos/Ditawarkan</p>
                        <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $stats['success'] }}</h3>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Applications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Lamaran Saya</h2>
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Cari Lowongan
                    </a>
                </div>
            </div>

            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Posisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Apply</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm text-gray-900">{{ $application->application_code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">{{ $application->jobPosting->title }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $application->jobPosting->division->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $application->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
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
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Lamaran</h3>
                    <p class="text-gray-600 mb-6">Anda belum melamar pekerjaan apapun. Mulai cari pekerjaan impian Anda!</p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold">
                        Cari Lowongan Sekarang
                    </a>
                </div>
            @endif
        </div>

        <!-- Upcoming Interviews -->
        @if($upcomingInterviews->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">Jadwal Interview</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($upcomingInterviews as $interview)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $interview->application->jobPosting->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $interview->application->jobPosting->division->name }}</p>
                                    
                                    <div class="flex items-center gap-6 mt-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($interview->interview_date)->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($interview->interview_time)->format('H:i') }} WIB
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $interview->location }}
                                        </div>
                                    </div>

                                    @if($interview->notes)
                                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                            <p class="text-sm text-gray-700">{{ $interview->notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 whitespace-nowrap">
                                    {{ ucfirst($interview->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

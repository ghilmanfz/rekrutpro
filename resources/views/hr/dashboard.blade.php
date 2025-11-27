<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard HR
        </h2>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Active Jobs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Lowongan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_jobs'] }}</p>
                    <a href="{{ route('hr.job-postings.index') }}" class="text-sm text-blue-500 hover:text-blue-600 mt-2 inline-block">
                        Lihat detail
                    </a>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Kandidat Baru (7 hari)</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_screening'] }}</p>
                    <a href="{{ route('hr.applications.index', ['status' => 'submitted']) }}" class="text-sm text-blue-500 hover:text-blue-600 mt-2 inline-block">
                        Lihat detail
                    </a>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Interviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Wawancara Mendatang</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $upcomingInterviews->count() }}</p>
                    <a href="{{ route('hr.interviews.index') }}" class="text-sm text-blue-500 hover:text-blue-600 mt-2 inline-block">
                        Lihat detail
                    </a>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Applications -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lamaran Terbaru</h3>
            </div>
            <div class="p-6">
                @if($recentApplications->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ substr($application->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $application->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $application->jobPosting->title }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('hr.applications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Lihat semua lamaran →
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada lamaran terbaru.</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Interviews -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Interview Mendatang</h3>
            </div>
            <div class="p-6">
                @if($upcomingInterviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingInterviews as $interview)
                            <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $interview->application->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $interview->application->jobPosting->title }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($interview->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('hr.interviews.index') }}" class="text-sm font-medium text-green-600 hover:text-green-500">
                            Lihat semua interview →
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada interview terjadwal.</p>
                @endif
            </div>
        </div>
    </div>
</x-hr-layout>

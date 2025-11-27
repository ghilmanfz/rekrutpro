<x-interviewer-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Interviewer</h1>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left & Center - 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Content (Left & Center) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Jadwal Interview Mendatang -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Jadwal Interview Mendatang</h2>
                        <a href="{{ route('interviewer.interviews.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                            Lihat Semua
                        </a>
                    </div>

                    @if($upcomingInterviews->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($upcomingInterviews as $interview)
                                <div class="px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4 flex-1">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ substr($interview->application->candidate->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Info -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-base font-semibold text-gray-900">
                                                    {{ $interview->application->candidate->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-0.5">
                                                    {{ $interview->application->jobPosting->title }}
                                                </p>
                                                
                                                <!-- Schedule Info -->
                                                <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                                                    <div class="flex items-center gap-1.5">
                                                        <span>{{ $interview->scheduled_at->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span>{{ $interview->scheduled_at->format('H:i') }} - {{ $interview->scheduled_at->addMinutes($interview->duration ?? 60)->format('H:i') }} WIB</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span>{{ $interview->location }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="ml-4">
                                            <a href="{{ route('interviewer.interviews.show', $interview->id) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                                Nilai
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada interview yang dijadwalkan</p>
                            <p class="text-sm text-gray-400 mt-1">Interview yang akan datang akan muncul di sini</p>
                        </div>
                    @endif
                </div>

                <!-- Penilaian Terbaru -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Penilaian Terbaru</h2>
                        <a href="{{ route('interviewer.assessments.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                            Lihat Semua
                        </a>
                    </div>

                    @if($recentAssessments->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($recentAssessments as $assessment)
                                <div class="px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3">
                                                <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                                                    <span class="text-xl font-bold text-yellow-600">{{ number_format($assessment->overall_score, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h3 class="text-base font-semibold text-gray-900">
                                                        {{ $assessment->interview->application->candidate->name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $assessment->interview->application->jobPosting->title }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('interviewer.assessments.show', $assessment->id) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada penilaian</p>
                            <p class="text-sm text-gray-400 mt-1">Penilaian yang telah Anda buat akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                
                <!-- Notifikasi -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Notifikasi</h2>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @php
                            $notifications = [];
                            
                            // Notifikasi interview baru (dari upcoming interviews)
                            foreach($upcomingInterviews->take(3) as $interview) {
                                if($interview->created_at->diffInHours(now()) <= 48) {
                                    $notifications[] = [
                                        'type' => 'interview',
                                        'icon' => 'bell',
                                        'color' => 'blue',
                                        'title' => 'Undangan interview baru untuk ' . $interview->application->candidate->name . ' (' . $interview->application->jobPosting->title . ').',
                                        'time' => $interview->created_at->diffForHumans(),
                                    ];
                                }
                            }
                            
                            // Notifikasi jadwal interview yang sudah diperbarui
                            foreach($upcomingInterviews->take(2) as $interview) {
                                if($interview->updated_at->diffInHours(now()) <= 24 && $interview->updated_at != $interview->created_at) {
                                    $notifications[] = [
                                        'type' => 'update',
                                        'icon' => 'calendar',
                                        'color' => 'blue',
                                        'title' => 'Jadwal interview ' . $interview->application->candidate->name . ' telah diperbarui.',
                                        'time' => $interview->updated_at->diffForHumans(),
                                    ];
                                }
                            }
                            
                            // Notifikasi penilaian jatuh tempo
                            foreach($recentAssessments->take(2) as $assessment) {
                                $notifications[] = [
                                    'type' => 'reminder',
                                    'icon' => 'clipboard',
                                    'color' => 'blue',
                                    'title' => 'Pengingat: Penilaian untuk ' . $assessment->interview->application->candidate->name . ' segera jatuh tempo.',
                                    'time' => $assessment->created_at->diffForHumans(),
                                ];
                            }
                            
                            // Batasi max 5 notifikasi
                            $notifications = array_slice($notifications, 0, 5);
                        @endphp
                        
                        @forelse($notifications as $notif)
                            <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 bg-{{ $notif['color'] }}-100 rounded-lg flex items-center justify-center">
                                            @if($notif['icon'] == 'bell')
                                                <svg class="w-4 h-4 text-{{ $notif['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            @elseif($notif['icon'] == 'calendar')
                                                <svg class="w-4 h-4 text-{{ $notif['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-{{ $notif['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">{{ $notif['title'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notif['time'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Interview Minggu Ini -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Interview Minggu Ini</h3>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-5xl font-bold text-gray-900">{{ $stats['scheduled'] }}</p>
                    </div>
                </div>

                <!-- Penilaian Tertunda -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Penilaian Tertunda</h3>
                        <div class="p-2 bg-orange-50 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-5xl font-bold text-gray-900">{{ max(0, $stats['completed'] - $recentAssessments->count()) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-interviewer-layout>

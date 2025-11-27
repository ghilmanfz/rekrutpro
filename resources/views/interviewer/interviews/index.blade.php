<x-interviewer-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Interview Saya</h1>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('interviewer.interviews.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Kandidat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Nama kandidat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    name="status" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Submit -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('interviewer.interviews.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Interviews List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($interviews->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($interviews as $interview)
                    <div class="p-6 hover:bg-gray-50 transition">
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
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-base font-semibold text-gray-900">
                                                {{ $interview->application->candidate->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-0.5">
                                                {{ $interview->application->jobPosting->title }}
                                            </p>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'rescheduled' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            $statusLabels = [
                                                'scheduled' => 'Terjadwal',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                                'rescheduled' => 'Dijadwal Ulang',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$interview->status] ?? $interview->status }}
                                        </span>
                                    </div>
                                    
                                    <!-- Schedule Info -->
                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500">
                                        <div class="flex items-center gap-1.5">
                                            <i class="far fa-calendar text-gray-400"></i>
                                            <span>{{ $interview->scheduled_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="far fa-clock text-gray-400"></i>
                                            <span>{{ $interview->scheduled_at->format('H:i') }} - {{ $interview->scheduled_at->addMinutes($interview->duration ?? 60)->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            <span>{{ $interview->location }}</span>
                                        </div>
                                        @if($interview->interview_type)
                                            <div class="flex items-center gap-1.5">
                                                <i class="fas fa-video text-gray-400"></i>
                                                <span class="capitalize">{{ str_replace('_', ' ', $interview->interview_type) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($interview->notes)
                                        <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                            <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                                            {{ Str::limit($interview->notes, 100) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="ml-4 flex gap-2">
                                <a href="{{ route('interviewer.interviews.show', $interview->id) }}" 
                                   class="inline-flex items-center px-4 py-2 {{ $interview->status == 'scheduled' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white text-sm font-medium rounded-lg transition">
                                    {{ $interview->status == 'scheduled' ? 'Nilai' : 'Lihat Detail' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($interviews->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $interviews->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 font-medium">Tidak ada interview</p>
                <p class="text-sm text-gray-400 mt-1">Interview yang dijadwalkan akan muncul di sini</p>
            </div>
        @endif
    </div>
</x-interviewer-layout>

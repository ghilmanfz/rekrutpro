<x-guest-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back to Home Button -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Semua Lowongan Pekerjaan</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600">Temukan pekerjaan yang sesuai dengan keahlian dan minat Anda</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6">
                <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                            <input 
                                type="text" 
                                name="search" 
                                id="search" 
                                value="{{ request('search') }}"
                                placeholder="Posisi atau kata kunci..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select 
                                name="division" 
                                id="division" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >
                                <option value="">Semua Divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ request('division') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Filter -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <select 
                                name="location" 
                                id="location" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Employment Type Filter -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pekerjaan</label>
                            <select 
                                name="type" 
                                id="type" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2">
                        <a href="{{ route('jobs.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 text-center">
                            Reset
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-xs sm:text-sm text-gray-600">
                Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan
            </div>

            <!-- Jobs List -->
            @if($jobs->count() > 0)
                <div class="space-y-4">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3 sm:gap-4">
                                            <!-- Logo -->
                                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>

                                            <!-- Job Info -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 truncate">
                                                    {{ $job->title }}
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center">
                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        <span class="truncate">{{ $job->division->name }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span class="truncate">{{ $job->location->name }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                    </div>
                                                </div>

                                                @if($job->salary_min && $job->salary_max)
                                                    <div class="text-xs sm:text-sm font-semibold text-blue-600 mb-2">
                                                        Rp {{ number_format($job->salary_min, 0, ',', '.') }} - Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                                                    </div>
                                                @endif

                                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-2">
                                                    {{ Str::limit(strip_tags($job->description), 150) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="sm:ml-4 w-full sm:w-auto">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="block w-full text-center sm:inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-xs text-gray-500">
                                    <div>
                                        Dipublikasikan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}
                                    </div>
                                    @if($job->closed_at)
                                        <div>
                                            Deadline: {{ \Carbon\Carbon::parse($job->closed_at)->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lowongan ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                            Tampilkan Semua Lowongan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>

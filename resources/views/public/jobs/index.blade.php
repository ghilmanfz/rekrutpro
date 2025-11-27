<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Semua Lowongan Pekerjaan</h1>
                <p class="mt-2 text-gray-600">Temukan pekerjaan yang sesuai dengan keahlian dan minat Anda</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                            <input 
                                type="text" 
                                name="search" 
                                id="search" 
                                value="{{ request('search') }}"
                                placeholder="Posisi atau kata kunci..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select 
                                name="division" 
                                id="division" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('jobs.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan
            </div>

            <!-- Jobs List -->
            @if($jobs->count() > 0)
                <div class="space-y-4">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- Logo -->
                                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>

                                            <!-- Job Info -->
                                            <div class="flex-1">
                                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                                    {{ $job->title }}
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        {{ $job->division->name }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $job->location->name }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                    </div>
                                                </div>

                                                @if($job->salary_min && $job->salary_max)
                                                    <div class="text-sm font-semibold text-blue-600 mb-2">
                                                        Rp {{ number_format($job->salary_min, 0, ',', '.') }} - Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                                                    </div>
                                                @endif

                                                <p class="text-gray-600 text-sm line-clamp-2">
                                                    {{ Str::limit(strip_tags($job->description), 150) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="ml-4">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center text-xs text-gray-500">
                                    <div>
                                        Dipublikasikan {{ $job->published_at->diffForHumans() }}
                                    </div>
                                    <div>
                                        Deadline: {{ $job->deadline->format('d M Y') }}
                                    </div>
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

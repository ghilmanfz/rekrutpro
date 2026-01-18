<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lowongan Pekerjaan - RekrutPro</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo/Brand -->
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                        </svg>
                        <span class="text-xl font-bold text-gray-900">RekrutPro</span>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Daftar
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back to Home Button -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar - Filter -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Filter Pekerjaan</h2>
                        
                        <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
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
                                <label for="division" class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
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
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
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
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pekerjaan</label>
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

                            <div class="flex flex-col gap-2 pt-2">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('jobs.index') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Semua Lowongan Pekerjaan</h1>
                        <p class="text-sm text-gray-600">Temukan pekerjaan impian Anda yang sesuai dengan keahlian dan minat Anda</p>
                    </div>

                    <!-- Results Count -->
                    <div class="mb-4 text-sm text-gray-600">
                        Menampilkan {{ $jobs->count() }} dari {{ $jobs->total() }} lowongan
                    </div>

                    <!-- Jobs List -->
                    @if($jobs->count() > 0)
                        <div class="space-y-4">
                            @foreach($jobs as $job)
                                <div class="bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                    <div class="p-6">
                                        <div class="flex gap-4">
                                            <!-- Company Icon -->
                                            <div class="flex-shrink-0">
                                                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Job Details -->
                                            <div class="flex-1 min-w-0">
                                                <!-- Title & Company -->
                                                <div class="mb-3">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                        {{ $job->title }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600">{{ $job->division->name }}</p>
                                                </div>

                                                <!-- Job Meta -->
                                                <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $job->location->name }}</p>
                                                    </div>
                                                    @if($job->salary_min && $job->salary_max)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Rp {{ number_format($job->salary_min / 1000000, 1, '.', '') }} juta - Rp {{ number_format($job->salary_max / 1000000, 1, '.', '') }} juta
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                    </div>
                                                </div>

                                                <!-- Description Preview -->
                                                <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                                    {{ Str::limit(strip_tags($job->description), 200) }}
                                                </p>

                                                <!-- Footer Meta -->
                                                <div class="text-xs text-gray-500">
                                                    Dibutuhkan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <div class="flex-shrink-0 flex items-start">
                                                <a href="{{ route('jobs.show', $job->id) }}" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                    Lihat Detail
                                                </a>
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
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada lowongan ditemukan</h3>
                            <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                            <div class="mt-6">
                                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                    Tampilkan Semua Lowongan
                                </a>
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                            </svg>
                            <span class="text-lg font-bold text-gray-900">RekrutPro</span>
                        </div>
                        <p class="text-sm text-gray-600">Find your dream job with RekrutPro, your trusted partner in career advancement.</p>
                    </div>

                    <!-- Tentang Kami -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tentang Kami</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-blue-600">Perusahaan</a></li>
                            <li><a href="#" class="hover:text-blue-600">Karir</a></li>
                            <li><a href="#" class="hover:text-blue-600">Blog</a></li>
                        </ul>
                    </div>

                    <!-- Pencari Kerja -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Pencari Kerja</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-blue-600">Cari Lowongan</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-blue-600">Profil Saya</a></li>
                            <li><a href="#" class="hover:text-blue-600">FAQ</a></li>
                        </ul>
                    </div>

                    <!-- Perusahaan -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Perusahaan</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-blue-600">Posting Lowongan</a></li>
                            <li><a href="#" class="hover:text-blue-600">Harga</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-500">© {{ date('Y') }} RekrutPro. All rights reserved.</p>
                        
                        <!-- Social Links -->
                        <div class="flex gap-4">
                            <a href="#" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

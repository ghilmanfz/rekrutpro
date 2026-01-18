<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $job->title }} - RekrutPro</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Back & Logo -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-900">RekrutPro</span>
                    </div>
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

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center text-sm text-gray-600 gap-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('jobs.index') }}" class="hover:text-blue-600">Kembali ke Daftar Lowongan</a>
            </nav>
        </div>

        <!-- Job Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Job Header -->
            <div class="bg-blue-600 text-white p-6 rounded-t-lg">
                <div class="flex items-start gap-4">
                    <!-- Company Icon -->
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                        </svg>
                    </div>

                    <!-- Job Info -->
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold mb-3">{{ $job->title }}</h1>
                        <div class="flex flex-wrap gap-4 text-sm text-blue-100">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ $job->division->name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $job->location->name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="p-6">
                <!-- Quick Info Grid -->
                <div class="grid grid-cols-2 gap-6 mb-8 pb-6 border-b border-gray-200">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Gaji</div>
                        @if($job->salary_min && $job->salary_max)
                            <div class="text-base font-medium text-gray-900">
                                Rp {{ number_format($job->salary_min / 1000000, 1, '.', '') }} juta - Rp {{ number_format($job->salary_max / 1000000, 1, '.', '') }} juta
                            </div>
                        @else
                            <div class="text-base font-medium text-gray-900">Negosiasi</div>
                        @endif
                    </div>
                    
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Level Pengalaman</div>
                        <div class="text-base font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->experience_level)) }}</div>
                    </div>
                </div>

                <!-- Deskripsi Pekerjaan -->
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Deskripsi Pekerjaan</h2>
                    <div class="text-gray-700 space-y-2 whitespace-pre-line leading-relaxed">{{ $job->description }}</div>
                </div>

                <!-- Kualifikasi -->
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Kualifikasi</h2>
                    <div class="text-gray-700 space-y-2 whitespace-pre-line leading-relaxed">{{ $job->requirements }}</div>
                </div>

                @if($job->benefits)
                    <!-- Benefit -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Benefit</h2>
                        <div class="text-gray-700 space-y-2 whitespace-pre-line leading-relaxed">{{ $job->benefits }}</div>
                    </div>
                @endif

                <!-- CTA Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600 mb-4">Silakan login atau daftar untuk melamar posisi ini</p>
                    
                    @auth
                        @if(auth()->user()->role->name === 'candidate')
                            @if($hasApplied)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                    <div class="flex items-center justify-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Anda sudah melamar posisi ini
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-center gap-4">
                                    <a href="{{ route('candidate.applications.create', $job->id) }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                        Login
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                    Daftar
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Job Meta Info -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Dipublikasikan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}</p>
                    <p class="mt-1">Kode Lowongan: {{ $job->code }}</p>
                </div>
            </div>
        </div>
    </main>

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
                        <li><a href="#" class="hover:text-blue-600">Fitur</a></li>
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
</body>
</html>

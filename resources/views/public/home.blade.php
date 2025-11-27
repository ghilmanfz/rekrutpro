<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RekrutPro - Temukan Karier Impian Anda</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">RP</span>
                        </div>
                        <span class="ml-3 text-xl font-bold text-blue-500">RekrutPro</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#jobs" class="text-gray-700 hover:text-blue-500 font-medium transition-colors">Karir</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-500 font-medium transition-colors">Tentang</a>
                    <a href="#faq" class="text-gray-700 hover:text-blue-500 font-medium transition-colors">FAQ</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-500 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">Register</a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                    Temukan Karier Impian Anda<br>di RekrutPro
                </h1>
                <p class="mt-6 text-lg text-white max-w-3xl mx-auto opacity-95">
                    Jelajahi lowongan pekerjaan terbaru dan bangun masa depan Anda bersama kami
                </p>
                
                <!-- Search Box -->
                <div class="mt-10 max-w-2xl mx-auto">
                    <form action="{{ route('jobs.index') }}" method="GET" class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari posisi, divisi, atau lokasi..." 
                            class="flex-1 rounded-lg border-0 px-5 py-4 text-gray-900 shadow-lg placeholder:text-gray-400 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-500"
                        >
                        <button type="submit" class="rounded-lg bg-white px-8 py-4 text-blue-500 font-semibold shadow-lg hover:bg-blue-50 transition-colors">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Jobs Section -->
    <div id="jobs" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filter -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Filter Pekerjaan</h3>
                        
                        <form action="{{ route('jobs.index') }}" method="GET" class="space-y-6">
                            <!-- Divisi Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Divisi</label>
                                <select name="division" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Divisi</option>
                                    <option value="1">IT</option>
                                    <option value="2">Marketing</option>
                                    <option value="3">Finance</option>
                                    <option value="4">HR</option>
                                    <option value="5">Operations</option>
                                </select>
                            </div>

                            <!-- Posisi Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi</label>
                                <select name="position" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Posisi</option>
                                    <option value="entry">Entry Level</option>
                                    <option value="junior">Junior</option>
                                    <option value="mid">Mid Level</option>
                                    <option value="senior">Senior</option>
                                </select>
                            </div>

                            <!-- Lokasi Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                                <select name="location" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Lokasi</option>
                                    <option value="1">Jakarta</option>
                                    <option value="2">Bandung</option>
                                    <option value="3">Surabaya</option>
                                    <option value="4">Yogyakarta</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full px-4 py-2.5 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                                Terapkan Filter
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Job Listings -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Lowongan Pekerjaan Aktif</h2>
                        <a href="{{ route('jobs.index') }}" class="text-blue-500 hover:text-blue-600 font-medium text-sm">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($featuredJobs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($featuredJobs as $job)
                                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                                    <div class="p-6">
                                        <!-- Company Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900">RekrutPro</p>
                                                    <p class="text-xs text-gray-500">{{ $job->division->name }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Job Title -->
                                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
                                            {{ $job->title }}
                                        </h3>

                                        <!-- Job Details -->
                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ $job->location->name }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Dibuka {{ $job->published_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        <!-- Apply Button -->
                                        <a href="{{ route('jobs.show', $job->id) }}" class="block w-full text-center px-4 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                            Apply Now
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada lowongan tersedia</h3>
                            <p class="mt-2 text-sm text-gray-500">Lowongan pekerjaan akan segera hadir. Silakan cek kembali nanti.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Tentang RekrutPro</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        RekrutPro adalah sistem rekrutmen end-to-end yang dirancang untuk memudahkan perusahaan dalam mengelola proses perekrutan dari awal hingga akhir. Misi kami adalah menghubungkan talenta terbaik dengan peluang yang tepat.
                    </p>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Kami percaya bahwa setiap individu memiliki potensi untuk unggul, dan tugas kami adalah membantu mereka menemukannya. Dengan teknologi inovatif dan pendekatan berbasis pada manusia, kami membangun jembatan antara pencari kerja dan perusahaan impian mereka.
                    </p>

                    <!-- Values -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Nilai-nilai kami</h3>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Inovatif</h4>
                                <p class="text-sm text-gray-600">Terus mencari cara baru untuk meningkatkan pengalaman rekrutmen.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Integritas</h4>
                                <p class="text-sm text-gray-600">Menjaga kejujuran dan transparansi di setiap langkah.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Kolaborasi</h4>
                                <p class="text-sm text-gray-600">Bekerja sama untuk mencapai tujuan bersama.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Keunggulan</h4>
                                <p class="text-sm text-gray-600">Berkomitmen pada standar tertinggi dalam setiap yang kami lakukan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="relative">
                    <div class="aspect-[4/3] bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop" 
                             alt="Team collaboration" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'">
                        <!-- Fallback if image doesn't load -->
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-32 h-32 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div id="faq" class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-gray-600">Temukan jawaban untuk pertanyaan umum tentang proses rekrutmen</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer list-none flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Bagaimana cara melamar pekerjaan di RekrutPro?</span>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <p>Untuk melamar pekerjaan, pertama-tama Anda perlu membuat akun di RekrutPro. Setelah login, cari lowongan yang sesuai dengan keahlian Anda, klik tombol "Apply Now", lengkapi formulir aplikasi, upload CV dan dokumen pendukung, lalu submit. Tim HR akan meninjau aplikasi Anda dan menghubungi jika Anda terpilih untuk tahap selanjutnya.</p>
                    </div>
                </details>

                <!-- FAQ Item 2 -->
                <details class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer list-none flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Apa saja kualifikasi umum untuk melamar?</span>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <p>Kualifikasi bervariasi tergantung posisi yang Anda lamar. Umumnya, kami mencari kandidat dengan pendidikan yang relevan, pengalaman kerja sesuai dengan level posisi, keterampilan teknis dan soft skills yang dibutuhkan, serta passion untuk berkembang. Setiap lowongan memiliki deskripsi lengkap tentang kualifikasi yang dibutuhkan.</p>
                    </div>
                </details>

                <!-- FAQ Item 3 -->
                <details class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer list-none flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Berapa lama proses rekrutmen berlangsung?</span>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <p>Proses rekrutmen biasanya memakan waktu 2-4 minggu, tergantung pada posisi dan jumlah kandidat. Tahapannya meliputi: screening CV (3-5 hari), interview HR (1 minggu), interview teknis/user (1-2 minggu), dan offering (3-5 hari). Kami akan selalu mengupdate status aplikasi Anda melalui email dan dashboard.</p>
                    </div>
                </details>

                <!-- FAQ Item 4 -->
                <details class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer list-none flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Apakah saya bisa melamar lebih dari satu posisi?</span>
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        <p>Ya, Anda dapat melamar ke beberapa posisi yang sesuai dengan keahlian Anda. Namun, kami menyarankan untuk fokus pada posisi yang benar-benar sesuai dengan background dan minat Anda agar peluang diterima lebih besar. Pastikan untuk menyesuaikan CV dan cover letter untuk setiap posisi yang Anda lamar.</p>
                    </div>
                </details>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Siap Memulai Karier Anda?
            </h2>
            <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                Daftar sekarang dan temukan peluang kerja terbaik yang sesuai dengan keahlian Anda
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition-colors shadow-lg">
                    Daftar Sekarang
                </a>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-lg text-white border-2 border-white hover:bg-white hover:text-blue-600 transition-colors">
                    Jelajahi Lowongan
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">RP</span>
                        </div>
                        <span class="ml-3 text-xl font-bold text-blue-500">RekrutPro</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Streamlining your hiring process from application to offer.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Perusahaan -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Perusahaan</h3>
                    <ul class="space-y-3">
                        <li><a href="#about" class="text-sm text-gray-600 hover:text-blue-500">Tentang Kami</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-500">Karir</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-500">Kontak</a></li>
                    </ul>
                </div>

                <!-- Dukungan -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Dukungan</h3>
                    <ul class="space-y-3">
                        <li><a href="#faq" class="text-sm text-gray-600 hover:text-blue-500">FAQ</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-500">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <!-- Hukum -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Hukum</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-500">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-500">Ketentuan Layanan</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} RekrutPro. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>

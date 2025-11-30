<x-guest-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Navigation Buttons -->
            <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <!-- Job Header -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <!-- Logo -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Job Title & Info -->
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-2">{{ $job->title }}</h1>
                            <div class="flex flex-wrap gap-2 sm:gap-4 text-sm sm:text-base text-blue-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="truncate">{{ $job->division->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $job->location->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="p-4 sm:p-6 lg:p-8">
                    <!-- Quick Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8 pb-6 sm:pb-8 border-b">
                        @if($job->salary_min && $job->salary_max)
                            <div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-1">Gaji</div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">
                                    Rp {{ number_format($job->salary_min, 0, ',', '.') }} - Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 mb-1">Level Pengalaman</div>
                            <div class="text-sm sm:text-base font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->experience_level)) }}</div>
                        </div>
                        
                        @if($job->closed_at)
                            <div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-1">Deadline</div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($job->closed_at)->format('d F Y') }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Deskripsi Pekerjaan</h2>
                        <div class="prose max-w-none text-sm sm:text-base text-gray-600">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Kualifikasi</h2>
                        <div class="prose max-w-none text-sm sm:text-base text-gray-600">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>

                    @if($job->benefits)
                        <!-- Benefits -->
                        <div class="mb-6 sm:mb-8">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Benefit</h2>
                            <div class="prose max-w-none text-sm sm:text-base text-gray-600">
                                {!! nl2br(e($job->benefits)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Apply Button -->
                    <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t">
                        @auth
                            @if(auth()->user()->role->name === 'candidate')
                                @if($hasApplied)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 text-center">
                                        <div class="flex items-center justify-center text-sm sm:text-base text-green-700">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Anda sudah melamar posisi ini
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('candidate.applications.create', $job->id) }}" class="block w-full bg-blue-600 text-white text-center py-3 px-4 sm:px-6 rounded-lg text-sm sm:text-base font-semibold hover:bg-blue-700 transition-colors">
                                        Lamar Sekarang
                                    </a>
                                @endif
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4 text-center text-sm sm:text-base text-yellow-700">
                                    Anda login sebagai {{ auth()->user()->role->name }}. Hanya Candidate yang bisa melamar pekerjaan.
                                </div>
                            @endif
                        @else
                            <div class="text-center">
                                <p class="text-sm sm:text-base text-gray-600 mb-4">Silakan login atau daftar untuk melamar posisi ini</p>
                                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                                    <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg text-sm sm:text-base font-semibold hover:bg-blue-700 transition-colors">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg text-sm sm:text-base font-semibold hover:bg-blue-50 transition-colors">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Job Meta -->
                    <div class="mt-4 sm:mt-6 text-xs sm:text-sm text-gray-500 text-center">
                        <p>Dipublikasikan {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}</p>
                        <p>Kode Lowongan: {{ $job->code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

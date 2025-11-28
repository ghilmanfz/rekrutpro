<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Kandidat' }} - RekrutPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm fixed h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">RekrutPro</span>
                </div>
            </div>

            <!-- Menu -->
            <nav class="p-4">
                <a href="{{ route('candidate.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('candidate.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} mb-1">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('candidate.applications.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('candidate.applications.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} mb-1">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="font-medium">Aplikasi Saya</span>
                </a>
                
                <a href="{{ route('candidate.profile') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('candidate.profile') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} mb-1">
                    <i class="fas fa-user w-5"></i>
                    <span class="font-medium">Profil</span>
                </a>
                
                <a href="{{ route('candidate.notifications') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('candidate.notifications') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} mb-1">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">Notifikasi</span>
                </a>
            </nav>

            <!-- Bottom Menu -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $header ?? 'Dashboard Kandidat' }}</h1>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img src="{{ auth()->user()->profile_photo ? Storage::url(auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}"
                                 class="w-10 h-10 rounded-full border-2 border-blue-500">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 text-center text-sm text-gray-500 border-t border-gray-200">
                © 2025 RekrutPro. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>

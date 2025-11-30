<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Selesai - RekrutPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-white text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">RekrutPro</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat!</h2>
                <p class="text-gray-600 mb-6">Akun Anda berhasil dibuat</p>

                <!-- All Steps Complete -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Apa Selanjutnya?</h3>
                    <ul class="text-sm text-gray-600 space-y-2 text-left">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span>Akun Anda sudah aktif dan terverifikasi</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-search text-blue-600"></i>
                            <span>Jelajahi lowongan kerja yang tersedia</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-paper-plane text-purple-600"></i>
                            <span>Mulai lamar pekerjaan impian Anda</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-user text-orange-600"></i>
                            <span>Lengkapi profil untuk hasil maksimal</span>
                        </li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('register.complete') }}">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold mb-3">
                        <i class="fas fa-home mr-2"></i>Mulai Mencari Pekerjaan
                    </button>
                </form>

                <a href="{{ route('candidate.profile') }}" class="block w-full text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lengkapi Profil Terlebih Dahulu
                </a>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-sm text-blue-500 hover:underline mr-4">Kembali ke Halaman Karir</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:underline">Keluar</button>
                    </form>
                @endauth
            </div>

            <div class="mt-8 text-center text-sm text-gray-500">
                © 2025 RekrutPro. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>

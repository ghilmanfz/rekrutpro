<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - RekrutPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-white text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">RekrutPro</span>
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Akun Baru</h2>
                    <p class="text-gray-600 mt-2">Bergabunglah dengan RekrutPro untuk menemukan karir impian Anda</p>
                </div>

                <!-- Progress Steps -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                1
                            </div>
                            <span class="text-xs text-blue-600 font-medium mt-1">Detail<br>Akun</span>
                        </div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">
                                2
                            </div>
                            <span class="text-xs text-gray-400 mt-1">Unggah<br>CV</span>
                        </div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">
                                3
                            </div>
                            <span class="text-xs text-gray-400 mt-1">Verifikasi<br>OTP</span>
                        </div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">
                                4
                            </div>
                            <span class="text-xs text-gray-400 mt-1">Profil<br>Dasar</span>
                        </div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">
                                5
                            </div>
                            <span class="text-xs text-gray-400 mt-1">Selesai</span>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register.step1.process') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap Anda"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi *</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Minimal 8 karakter"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Password harus minimal 8 karakter</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi *</label>
                        <input type="password" 
                               name="password_confirmation" 
                               placeholder="Masukkan ulang kata sandi"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start gap-2">
                            <input type="checkbox" 
                                   name="agree_terms" 
                                   value="1"
                                   required
                                   class="mt-1">
                            <span class="text-sm text-gray-600">
                                Saya menyetujui 
                                <a href="#" class="text-blue-600 hover:underline">Syarat dan Ketentuan</a> 
                                serta 
                                <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a> 
                                RekrutPro
                            </span>
                        </label>
                        @error('agree_terms')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold transition-colors">
                        Selanjutnya
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Masuk Sekarang</a>
                    </p>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:underline text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Atau kembali ke Halaman Karir
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 text-center text-sm text-gray-500">
                © 2025 RekrutPro. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>

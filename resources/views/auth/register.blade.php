<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - RekrutPro</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">RP</span>
                        </div>
                        <span class="ml-3 text-xl font-bold text-blue-500">RekrutPro</span>
                    </a>
                    <a href="{{ route('home') }}#jobs" class="text-gray-700 hover:text-blue-500 font-medium">Karir</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl w-full">
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <!-- Title -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Daftar Akun Baru</h2>
                        <p class="text-gray-600">Bergabunglah dengan RekrutPro untuk menemukan karir impian Anda</p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="mb-10">
                        <div class="flex items-center justify-between">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold mb-2">
                                    1
                                </div>
                                <span class="text-xs text-blue-500 font-medium text-center">Detail Akun</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-6"></div>
                            
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-semibold mb-2">
                                    2
                                </div>
                                <span class="text-xs text-gray-400 text-center">Unggah CV</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-6"></div>
                            
                            <!-- Step 3 -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-semibold mb-2">
                                    3
                                </div>
                                <span class="text-xs text-gray-400 text-center">Verifikasi OTP</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-6"></div>
                            
                            <!-- Step 4 -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-semibold mb-2">
                                    4
                                </div>
                                <span class="text-xs text-gray-400 text-center">Profil Dasar</span>
                            </div>
                            <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-6"></div>
                            
                            <!-- Step 5 -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-semibold mb-2">
                                    5
                                </div>
                                <span class="text-xs text-gray-400 text-center">Selesai</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Step 1: Detail Akun -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                        @csrf
                        <input type="hidden" name="role_id" value="4"> <!-- Default: Candidate -->

                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap Anda"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Password harus minimal 8 karakter</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Masukkan ulang kata sandi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            >
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="flex items-start">
                            <input 
                                id="terms" 
                                type="checkbox" 
                                required
                                class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500 mt-1"
                            >
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                Saya menyetujui <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Kebijakan Privasi</a> RekrutPro
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-blue-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Selanjutnya
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-semibold">
                                Masuk Sekarang
                            </a>
                        </p>
                    </div>

                    <!-- Back to Home -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('home') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                            Atau kembali ke Halaman Karir
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">© {{ date('Y') }} RekrutPro. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Multi-step form wizard
        let currentStep = 1;
        const totalSteps = 5;
        
        // Password validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        confirmPassword.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form submission - for now just submit normally
        // In production, you would implement actual multi-step logic with AJAX
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            // Let the form submit normally to Laravel's register route
            // Laravel will handle the validation and user creation
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - RekrutPro</title>
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

            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Verifikasi Email</h2>
                    <p class="text-gray-600 mt-2">Step 3 dari 5 - Masukkan kode OTP yang dikirim ke email Anda</p>
                </div>

                <!-- Progress -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">4</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">5</div>
                    </div>
                </div>

                @if(session('otp_code'))
                    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
                        <strong>Development Mode:</strong> Kode OTP Anda adalah: <strong class="text-lg">{{ session('otp_code') }}</strong>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.step3.process') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Masukkan Kode OTP (6 digit)</label>
                        <input type="text" 
                               name="otp" 
                               maxlength="6"
                               pattern="[0-9]{6}"
                               placeholder="000000"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-2xl font-bold tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('otp')
                            <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold mb-3">
                        Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('register.resend-otp') }}">
                    @csrf
                    <button type="submit" class="w-full text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Kirim Ulang Kode OTP
                    </button>
                </form>
                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-blue-500 hover:underline mr-4">Kembali ke Halaman Karir</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:underline">Keluar</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>
</html>

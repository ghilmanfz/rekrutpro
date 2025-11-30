<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Dasar - RekrutPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-2xl w-full">
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
                    <h2 class="text-2xl font-bold text-gray-900">Lengkapi Profil Anda</h2>
                    <p class="text-gray-600 mt-2">Step 4 dari 5 - Informasi tambahan untuk meningkatkan peluang Anda</p>
                </div>

                <!-- Progress -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">5</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register.step4.process') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+62 812 3456 7890" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir *</label>
                            <select name="education" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Pendidikan</option>
                                <option value="SMA/SMK">SMA/SMK</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            @error('education')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="address" rows="3" required placeholder="Masukkan alamat lengkap Anda..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman Kerja</label>
                        <textarea name="experience" rows="3" placeholder="Jelaskan pengalaman kerja Anda (opsional)..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('experience') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keahlian/Skills</label>
                        <textarea name="skills" rows="2" placeholder="Contoh: PHP, Laravel, JavaScript, Communication (opsional)..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('skills') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Lanjutkan
                    </button>
                </form>
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
        </div>
    </div>
</body>
</html>

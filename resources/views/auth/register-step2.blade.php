<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CV - RekrutPro</title>
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
                    <h2 class="text-2xl font-bold text-gray-900">Upload CV Anda</h2>
                    <p class="text-gray-600 mt-2">Step 2 dari 5 - Unggah CV atau resume Anda</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                <!-- Progress -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm"><i class="fas fa-check"></i></div>
                        <div class="w-12 h-1 bg-green-500"></div>
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">3</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">4</div>
                        <div class="w-12 h-1 bg-gray-200"></div>
                        <div class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm">5</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register.step2.process') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload CV/Resume *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-2">Klik atau drag & drop file Anda di sini</p>
                            <p class="text-xs text-gray-500 mb-4">PDF, DOC, DOCX (Maksimal 5MB)</p>
                            <input type="file" 
                                   name="cv" 
                                   accept=".pdf,.doc,.docx" 
                                   required
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        @error('cv')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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

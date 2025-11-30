<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Penawaran Kerja
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('hr.offers.show', $offer) }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                ← Kembali ke Detail Penawaran
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Penawaran untuk {{ $offer->application->candidate->name }}</h1>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Terjadi kesalahan!</strong>
                    <ul class="mt-2 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('hr.offers.update', $offer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Posisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Posisi yang Ditawarkan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="position_title" 
                               value="{{ old('position_title', $offer->position_title) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>

                    <!-- Gaji -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gaji yang Ditawarkan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="salary" 
                               value="{{ old('salary', $offer->salary) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               min="0"
                               step="1000"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Contoh: 12000000 untuk Rp 12.000.000</p>
                    </div>

                    <!-- Benefits -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Benefits & Fasilitas
                        </label>
                        <textarea name="benefits" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Contoh: BPJS Kesehatan & Ketenagakerjaan, Tunjangan Makan, Laptop">{{ old('benefits', $offer->benefits) }}</textarea>
                    </div>

                    <!-- Tipe Kontrak -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Kontrak <span class="text-red-500">*</span>
                        </label>
                        <select name="contract_type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Pilih Tipe Kontrak</option>
                            <option value="full_time" {{ old('contract_type', $offer->contract_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('contract_type', $offer->contract_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('contract_type', $offer->contract_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('contract_type', $offer->contract_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai Kerja <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ old('start_date', $offer->start_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>

                    <!-- Berlaku Hingga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Penawaran Berlaku Hingga <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="valid_until" 
                               value="{{ old('valid_until', $offer->valid_until?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>

                    <!-- Catatan Internal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Internal (tidak terlihat oleh kandidat)
                        </label>
                        <textarea name="internal_notes" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Catatan untuk tim HR...">{{ old('internal_notes', $offer->internal_notes) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                    <a href="{{ route('hr.offers.show', $offer) }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-hr-layout>

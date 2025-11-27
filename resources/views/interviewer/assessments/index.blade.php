<x-interviewer-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Penilaian</h1>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Penilaian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $assessments->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rata-rata Skor</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($averageScore, 1) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-thumbs-up text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Direkomendasikan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recommendedCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('interviewer.assessments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Kandidat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Nama kandidat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                <input 
                    type="text" 
                    name="position" 
                    value="{{ request('position') }}"
                    placeholder="Nama posisi..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                <select name="recommendation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="sangat_direkomendasikan" {{ request('recommendation') == 'sangat_direkomendasikan' ? 'selected' : '' }}>Sangat Direkomendasikan</option>
                    <option value="direkomendasikan" {{ request('recommendation') == 'direkomendasikan' ? 'selected' : '' }}>Direkomendasikan</option>
                    <option value="cukup" {{ request('recommendation') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                    <option value="tidak_direkomendasikan" {{ request('recommendation') == 'tidak_direkomendasikan' ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('interviewer.assessments.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Assessment List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Interview</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekomendasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assessments as $assessment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($assessment->interview->application->candidate->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $assessment->interview->application->candidate->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $assessment->interview->application->candidate->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $assessment->interview->application->jobPosting->title }}</div>
                                <div class="text-sm text-gray-500">{{ $assessment->interview->application->jobPosting->location ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="far fa-calendar text-gray-400 mr-1"></i>
                                    {{ $assessment->interview->scheduled_at->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock text-gray-400 mr-1"></i>
                                    {{ $assessment->interview->scheduled_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $assessment->overall_score }}
                                    </div>
                                    <span class="text-sm text-gray-500 ml-1">/100</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $assessment->overall_score }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    {{ $assessment->recommendation == 'sangat_direkomendasikan' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $assessment->recommendation == 'direkomendasikan' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $assessment->recommendation == 'cukup' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $assessment->recommendation == 'tidak_direkomendasikan' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucwords(str_replace('_', ' ', $assessment->recommendation)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('interviewer.assessments.show', $assessment->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Belum ada penilaian</p>
                                <p class="text-gray-400 text-sm mt-2">Penilaian yang Anda buat akan muncul di sini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($assessments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assessments->links() }}
            </div>
        @endif
    </div>
</x-interviewer-layout>

<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Penawaran Kerja
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Stats Cards — selalu menampilkan hitungan global, bisa diklik untuk filter --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <a href="{{ route('hr.offers.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between transition hover:shadow-md {{ !request('status') ? 'ring-2 ring-blue-500' : '' }}">
                <div>
                    <p class="text-sm text-gray-600">Total Penawaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('hr.offers.index', ['status' => 'pending', 'search' => request('search')]) }}"
               class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between transition hover:shadow-md {{ request('status') === 'pending' ? 'ring-2 ring-yellow-400' : '' }}">
                <div>
                    <p class="text-sm text-gray-600">Menunggu Respons</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('hr.offers.index', ['status' => 'accepted', 'search' => request('search')]) }}"
               class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between transition hover:shadow-md {{ request('status') === 'accepted' ? 'ring-2 ring-green-500' : '' }}">
                <div>
                    <p class="text-sm text-gray-600">Diterima Kandidat</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('hr.offers.index', ['status' => 'rejected', 'search' => request('search')]) }}"
               class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between transition hover:shadow-md {{ request('status') === 'rejected' ? 'ring-2 ring-red-500' : '' }}">
                <div>
                    <p class="text-sm text-gray-600">Ditolak Kandidat</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </a>
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('hr.offers.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Kandidat</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama kandidat..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Semua Status</option>
                            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                            <option value="accepted"  {{ request('status') === 'accepted'  ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                            <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('hr.offers.index') }}"
                               class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 text-sm font-medium whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Indikator filter aktif --}}
            @if(request('search') || request('status'))
                <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-filter text-blue-500"></i>
                    <span>Filter aktif:</span>
                    @if(request('search'))
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Nama: "{{ request('search') }}"</span>
                    @endif
                    @if(request('status'))
                        @php $statusLabel = ['pending'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak','expired'=>'Kadaluarsa'][request('status')] ?? request('status'); @endphp
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Status: {{ $statusLabel }}</span>
                    @endif
                    <span class="text-gray-400">— menampilkan {{ $offers->total() }} dari {{ $stats['total'] }} penawaran</span>
                </div>
            @endif
        </div>

        {{-- Download Excel --}}
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ route('hr.offers.export') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    <i class="fas fa-file-excel mr-2"></i>Download Excel
                </button>
            </form>
        </div>

        {{-- Tabel Penawaran --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berlaku Hingga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($offers as $offer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ strtoupper(substr($offer->application->candidate->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $offer->application->candidate->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $offer->application->candidate->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $offer->position_title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($offer->salary, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($offer->start_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($offer->valid_until)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($offer->status === 'pending')  bg-yellow-100 text-yellow-800
                                        @elseif($offer->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($offer->status === 'pending')  Menunggu
                                        @elseif($offer->status === 'accepted') Diterima
                                        @elseif($offer->status === 'rejected') Ditolak
                                        @else Kadaluarsa
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('hr.offers.show', $offer) }}"
                                       class="text-blue-600 hover:text-blue-900">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                    Tidak ada data penawaran
                                    @if(request('search') || request('status'))
                                        untuk filter yang dipilih.
                                        <a href="{{ route('hr.offers.index') }}" class="text-blue-600 hover:underline ml-1">Reset filter</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $offers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-hr-layout>

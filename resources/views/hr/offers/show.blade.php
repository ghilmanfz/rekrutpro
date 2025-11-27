<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Penawaran Kerja
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('hr.offers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                ← Kembali ke Daftar Penawaran
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Penawaran untuk {{ $offer->application->candidate->name }}</h1>
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @if($offer->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($offer->status === 'accepted') bg-green-100 text-green-800
                    @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    @if($offer->status === 'pending') Menunggu
                    @elseif($offer->status === 'accepted') Diterima
                    @elseif($offer->status === 'rejected') Ditolak
                    @else Kadaluarsa
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Detail Penawaran</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600">Posisi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->position_title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Gaji</dt>
                            <dd class="text-lg font-bold text-green-600">Rp {{ number_format($offer->salary_offered, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Tanggal Mulai</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($offer->start_date)->format('d M Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Berlaku Hingga</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($offer->valid_until)->format('d M Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Dibuat Oleh</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->createdBy->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Tanggal Dibuat</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $offer->created_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Kandidat</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600">Nama</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->application->candidate->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->application->candidate->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Posisi Dilamar</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->application->jobPosting->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Ekspektasi Gaji</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($offer->application->expected_salary ?? 0, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($offer->benefits)
            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Benefit & Fasilitas</h3>
                <p class="text-sm text-gray-700">{{ $offer->benefits }}</p>
            </div>
            @endif

            @if($offer->notes)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Catatan</h3>
                <p class="text-sm text-gray-700">{{ $offer->notes }}</p>
            </div>
            @endif

            @if($offer->status === 'accepted' && $offer->accepted_at)
            <div class="mb-6 p-4 bg-green-100 rounded-lg">
                <h3 class="font-semibold text-green-900 mb-2">✓ Penawaran Diterima</h3>
                <p class="text-sm text-green-800">Diterima pada: {{ $offer->accepted_at->format('d M Y, H:i') }}</p>
            </div>
            @endif

            @if($offer->status === 'rejected' && $offer->rejected_at)
            <div class="mb-6 p-4 bg-red-100 rounded-lg">
                <h3 class="font-semibold text-red-900 mb-2">✗ Penawaran Ditolak</h3>
                <p class="text-sm text-red-800">Ditolak pada: {{ $offer->rejected_at->format('d M Y, H:i') }}</p>
            </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('hr.applications.show', $offer->application_id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Lihat Aplikasi
                </a>
            </div>
        </div>
    </div>
</x-hr-layout>

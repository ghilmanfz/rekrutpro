<x-hr-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Interview
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('hr.interviews.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mb-2 inline-block">
                ← Kembali ke Daftar Interview
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Interview dengan {{ $interview->application->candidate->name }}</h1>
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @if($interview->status === 'scheduled') bg-yellow-100 text-yellow-800
                    @elseif($interview->status === 'completed') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($interview->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Informasi Interview</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600">Jadwal</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('d M Y, H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Durasi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->duration }} menit</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Tipe</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($interview->interview_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Lokasi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Pewawancara</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->interviewer->name }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Kandidat</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600">Nama</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->application->candidate->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->application->candidate->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Posisi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->application->jobPosting->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Divisi</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $interview->application->jobPosting->division->name }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($interview->notes)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Catatan</h3>
                <p class="text-sm text-gray-700">{{ $interview->notes }}</p>
            </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('hr.applications.show', $interview->application_id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Lihat Aplikasi
                </a>
                @if($interview->status === 'scheduled')
                <form action="{{ route('hr.interviews.destroy', $interview->id) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin membatalkan interview ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Batalkan Interview
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-hr-layout>

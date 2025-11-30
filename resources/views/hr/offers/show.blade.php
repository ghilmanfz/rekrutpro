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
                <div class="flex items-start justify-between">
                    <div>
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
                    @if($offer->status === 'pending')
                        <a href="{{ route('hr.offers.edit', $offer) }}" 
                           class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-semibold shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-edit mr-2"></i>Edit Penawaran
                        </a>
                    @endif
                </div>
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
                            <dd class="text-lg font-bold text-green-600">Rp {{ number_format($offer->salary, 0, ',', '.') }}</dd>
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
                            <dd class="text-sm font-medium text-gray-900">{{ $offer->offeredBy->name }}</dd>
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

            @if($offer->internal_notes)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Catatan Internal</h3>
                <p class="text-sm text-gray-700">{{ $offer->internal_notes }}</p>
            </div>
            @endif

            <!-- Negotiations Section -->
            @if($offer->negotiations->isNotEmpty())
            <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">Riwayat Negosiasi</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($offer->negotiations()->latest()->get() as $negotiation)
                        <div class="p-4 {{ $negotiation->status === 'pending' ? 'bg-yellow-50' : '' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        Gaji yang Diajukan: 
                                        <span class="text-blue-600">Rp {{ number_format($negotiation->proposed_salary, 0, ',', '.') }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Diajukan pada: {{ $negotiation->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($negotiation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($negotiation->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($negotiation->status === 'pending') Menunggu Review
                                    @elseif($negotiation->status === 'approved') Disetujui
                                    @else Ditolak
                                    @endif
                                </span>
                            </div>

                            @if($negotiation->candidate_notes)
                                <div class="mb-3 p-3 bg-white rounded border border-gray-200">
                                    <p class="text-xs text-gray-600 mb-1">Alasan Kandidat:</p>
                                    <p class="text-sm text-gray-900">{{ $negotiation->candidate_notes }}</p>
                                </div>
                            @endif

                            @if($negotiation->status === 'pending')
                                <!-- Action Buttons for Pending Negotiation -->
                                <div class="flex gap-2 mt-3">
                                    <form action="{{ route('hr.negotiations.approve', $negotiation) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" 
                                                onclick="showApproveModal({{ $negotiation->id }}, {{ $negotiation->proposed_salary }})"
                                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                            <i class="fas fa-check mr-1"></i>Setujui
                                        </button>
                                    </form>
                                    <button type="button" 
                                            onclick="showRejectModal({{ $negotiation->id }})"
                                            class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                        <i class="fas fa-times mr-1"></i>Tolak
                                    </button>
                                </div>
                            @else
                                @if($negotiation->hr_notes)
                                    <div class="mt-3 p-3 bg-gray-50 rounded border border-gray-200">
                                        <p class="text-xs text-gray-600 mb-1">Catatan HR:</p>
                                        <p class="text-sm text-gray-900">{{ $negotiation->hr_notes }}</p>
                                        @if($negotiation->reviewed_at)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Direview oleh {{ $negotiation->reviewer->name }} pada {{ $negotiation->reviewed_at->format('d M Y, H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($offer->status === 'accepted' && $offer->responded_at)
            <div class="mb-6 p-4 bg-green-100 rounded-lg">
                <h3 class="font-semibold text-green-900 mb-2">✓ Penawaran Diterima</h3>
                <p class="text-sm text-green-800">Diterima pada: {{ $offer->responded_at->format('d M Y, H:i') }}</p>
            </div>
            @endif

            @if($offer->status === 'rejected' && $offer->responded_at)
            <div class="mb-6 p-4 bg-red-100 rounded-lg">
                <h3 class="font-semibold text-red-900 mb-2">✗ Penawaran Ditolak</h3>
                <p class="text-sm text-red-800">Ditolak pada: {{ $offer->responded_at->format('d M Y, H:i') }}</p>
                @if($offer->rejection_reason)
                    <p class="text-sm text-red-700 mt-2">Alasan: {{ $offer->rejection_reason }}</p>
                @endif
            </div>
            @endif

            <div class="flex justify-between items-center gap-3 pt-6 border-t bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                <a href="{{ route('hr.applications.show', $offer->application_id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-white">
                    <i class="fas fa-arrow-left mr-2"></i>Lihat Aplikasi
                </a>
                
                <div class="flex gap-3">
                    @if($offer->status === 'pending')
                    <a href="{{ route('hr.offers.edit', $offer) }}" 
                       class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-semibold shadow-lg">
                        <i class="fas fa-edit mr-2"></i>Edit Penawaran
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Negotiation Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Setujui Negosiasi</h3>
                <button onclick="document.getElementById('approveModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="approveForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Gaji pada penawaran akan diupdate menjadi: <strong id="newSalary"></strong>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan untuk Kandidat (Opsional)
                        </label>
                        <textarea name="hr_notes" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Ya, Setujui Negosiasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Negotiation Modal -->
    <div id="rejectNegotiationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tolak Negosiasi</h3>
                <button onclick="document.getElementById('rejectNegotiationModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Kandidat akan menerima notifikasi bahwa negosiasi ditolak.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea name="hr_notes" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="document.getElementById('rejectNegotiationModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Ya, Tolak Negosiasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showApproveModal(negotiationId, proposedSalary) {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            const salaryDisplay = document.getElementById('newSalary');
            
            form.action = `/hr/negotiations/${negotiationId}/approve`;
            salaryDisplay.textContent = 'Rp ' + proposedSalary.toLocaleString('id-ID');
            
            modal.classList.remove('hidden');
        }

        function showRejectModal(negotiationId) {
            const modal = document.getElementById('rejectNegotiationModal');
            const form = document.getElementById('rejectForm');
            
            form.action = `/hr/negotiations/${negotiationId}/reject`;
            
            modal.classList.remove('hidden');
        }
    </script>
</x-hr-layout>

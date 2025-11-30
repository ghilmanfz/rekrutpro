@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('candidate.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- Application Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $application->jobPosting->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $application->jobPosting->division->name }} - {{ $application->jobPosting->location->name }}</p>
                    <p class="text-sm text-gray-500 mt-2">Kode Lamaran: <span class="font-mono font-semibold">{{ $application->application_code }}</span></p>
                </div>
                @php
                    $statusConfig = [
                        'submitted' => ['label' => 'Menunggu Review', 'color' => 'yellow'],
                        'screening_passed' => ['label' => 'Lolos Screening', 'color' => 'blue'],
                        'interview_scheduled' => ['label' => 'Dijadwalkan Interview', 'color' => 'purple'],
                        'interview_passed' => ['label' => 'Lolos Interview', 'color' => 'green'],
                        'offered' => ['label' => 'Ditawarkan', 'color' => 'indigo'],
                        'hired' => ['label' => 'Diterima', 'color' => 'green'],
                        'rejected_admin' => ['label' => 'Tidak Lolos Screening', 'color' => 'red'],
                        'rejected_interview' => ['label' => 'Tidak Lolos Interview', 'color' => 'red'],
                    ];
                    $config = $statusConfig[$application->status] ?? ['label' => $application->status, 'color' => 'gray'];
                @endphp
                <span class="px-4 py-2 text-sm font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                    {{ $config['label'] }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Lengkap</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Alamat</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->candidate->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education & Experience -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pendidikan & Pengalaman</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Pendidikan</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->education }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pengalaman</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->experience }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ekspektasi Gaji</p>
                            <p class="font-medium text-gray-900 mt-1">Rp {{ number_format($application->expected_salary, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ketersediaan</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $application->availability }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
                @if($application->cover_letter)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $application->cover_letter }}</p>
                    </div>
                @endif

                <!-- Documents -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h2>
                    <div class="space-y-3">
                        @if($application->cv_path)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">CV / Resume</p>
                                        <p class="text-sm text-gray-500">{{ basename($application->cv_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($application->cv_path) }}" 
                                   target="_blank"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                    Download
                                </a>
                            </div>
                        @endif

                        @if($application->portfolio_path)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Portfolio</p>
                                        <p class="text-sm text-gray-500">{{ basename($application->portfolio_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($application->portfolio_path) }}" 
                                   target="_blank"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                    Download
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Notes -->
                @if($application->status_notes)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h3 class="font-semibold text-blue-900 mb-2">Catatan dari HR</h3>
                        <p class="text-blue-800">{{ $application->status_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900">Lamaran Dikirim</p>
                                <p class="text-sm text-gray-500">{{ $application->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($application->screening_passed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Lolos Screening</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->screening_passed_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->interview_scheduled_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-purple-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Interview Dijadwalkan</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->interview_scheduled_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->interview_passed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Lolos Interview</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->interview_passed_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->offered_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-indigo-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Ditawarkan</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->offered_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->hired_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Diterima</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->hired_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Interview Details -->
                @if($application->interviews()->exists())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Interview</h2>
                        @foreach($application->interviews as $interview)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $interview->scheduled_at->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Lokasi</p>
                                    <p class="font-medium text-gray-900">{{ $interview->location }}</p>
                                </div>
                                @if($interview->notes)
                                    <div>
                                        <p class="text-sm text-gray-600">Catatan</p>
                                        <p class="text-gray-900">{{ $interview->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Job Offer -->
                @if($application->offer)
                    @php $offer = $application->offer; @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Penawaran Kerja</h2>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @if($offer->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($offer->status === 'accepted') bg-green-100 text-green-800
                                @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($offer->status === 'pending') Menunggu Respons Anda
                                @elseif($offer->status === 'accepted') Diterima
                                @elseif($offer->status === 'rejected') Ditolak
                                @else Kadaluarsa
                                @endif
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div>
                                <p class="text-sm text-gray-600">Posisi</p>
                                <p class="font-semibold text-gray-900">{{ $offer->position_title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Gaji yang Ditawarkan</p>
                                <p class="text-xl font-bold text-green-600">Rp {{ number_format($offer->salary, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tipe Kontrak</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $offer->contract_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Mulai</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($offer->start_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Berlaku Hingga</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($offer->valid_until)->format('d M Y') }}</p>
                            </div>
                            @if($offer->benefits)
                                <div>
                                    <p class="text-sm text-gray-600">Benefits & Fasilitas</p>
                                    <p class="text-gray-900">{{ $offer->benefits }}</p>
                                </div>
                            @endif
                        </div>

                        @if($offer->status === 'pending')
                            <!-- Action Buttons for Pending Offers -->
                            <div class="border-t-4 border-blue-300 pt-6 mt-6 bg-gradient-to-br from-blue-100 to-indigo-100 -mx-6 -mb-6 px-6 pb-8 rounded-b-xl shadow-lg">
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-3 animate-pulse">
                                        <i class="fas fa-exclamation text-white text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Respons Anda Diperlukan!</h3>
                                    <p class="text-sm text-gray-700">
                                        Silakan pilih salah satu dari 3 opsi di bawah ini:
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Accept Button -->
                                    <form action="{{ route('candidate.offers.accept', $offer) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA penawaran ini? Anda akan menjadi karyawan tetap.')">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 font-bold text-lg shadow-2xl hover:shadow-green-500/50 transition-all transform hover:scale-105 border-2 border-green-400">
                                            <i class="fas fa-check-circle text-2xl mb-2 block"></i>
                                            <span class="block">Terima Tawaran</span>
                                        </button>
                                    </form>

                                    <!-- Negotiate Button -->
                                    <button type="button" 
                                            onclick="document.getElementById('negotiateModal').classList.remove('hidden')"
                                            class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 font-bold text-lg shadow-2xl hover:shadow-blue-500/50 transition-all transform hover:scale-105 border-2 border-blue-400">
                                        <i class="fas fa-handshake text-2xl mb-2 block"></i>
                                        <span class="block">Ajukan Negosiasi</span>
                                    </button>

                                    <!-- Reject Button -->
                                    <button type="button" 
                                            onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                            class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 font-bold text-lg shadow-2xl hover:shadow-red-500/50 transition-all transform hover:scale-105 border-2 border-red-400">
                                        <i class="fas fa-times-circle text-2xl mb-2 block"></i>
                                        <span class="block">Tolak Tawaran</span>
                                    </button>
                                </div>
                            </div>
                        @elseif($offer->status === 'accepted')
                            <div class="border-t pt-4 bg-green-50 -m-6 mt-0 p-6 rounded-b-xl">
                                <div class="flex items-center text-green-800">
                                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold">Selamat! Anda telah menerima penawaran ini</p>
                                        <p class="text-sm">Diterima pada: {{ $offer->responded_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($offer->status === 'rejected')
                            <div class="border-t pt-4 bg-red-50 -m-6 mt-0 p-6 rounded-b-xl">
                                <div class="flex items-center text-red-800">
                                    <i class="fas fa-times-circle text-2xl mr-3"></i>
                                    <div>
                                        <p class="font-semibold">Anda telah menolak penawaran ini</p>
                                        <p class="text-sm">Ditolak pada: {{ $offer->responded_at->format('d M Y, H:i') }}</p>
                                        @if($offer->rejection_reason)
                                            <p class="text-sm mt-1">Alasan: {{ $offer->rejection_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Check for pending negotiation -->
                        @if($offer->latestNegotiation && $offer->latestNegotiation->status === 'pending')
                            <div class="border-t pt-4 mt-4 bg-blue-50 -m-6 mt-0 p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-blue-900 mb-2">Negosiasi Sedang Diproses</p>
                                        <div class="text-sm text-blue-800 space-y-1">
                                            <p>Gaji yang Anda ajukan: <span class="font-semibold">Rp {{ number_format($offer->latestNegotiation->proposed_salary, 0, ',', '.') }}</span></p>
                                            @if($offer->latestNegotiation->candidate_notes)
                                                <p>Catatan: {{ $offer->latestNegotiation->candidate_notes }}</p>
                                            @endif
                                            <p class="text-xs text-blue-600 mt-2">Diajukan pada: {{ $offer->latestNegotiation->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($offer->latestNegotiation && $offer->latestNegotiation->status === 'approved')
                            <div class="border-t pt-4 mt-4 bg-green-50 -m-6 mt-0 p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 text-xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-900 mb-2">Negosiasi Disetujui!</p>
                                        <div class="text-sm text-green-800 space-y-1">
                                            <p>HR telah menyetujui negosiasi Anda. Penawaran telah diperbarui dengan gaji baru.</p>
                                            @if($offer->latestNegotiation->hr_notes)
                                                <p>Catatan HR: {{ $offer->latestNegotiation->hr_notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($offer->latestNegotiation && $offer->latestNegotiation->status === 'rejected')
                            <div class="border-t pt-4 mt-4 bg-red-50 -m-6 mt-0 p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-times-circle text-red-600 text-xl mr-3 mt-1"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold text-red-900 mb-2">Negosiasi Ditolak</p>
                                        <div class="text-sm text-red-800 space-y-1">
                                            <p>Maaf, negosiasi Anda tidak dapat disetujui.</p>
                                            @if($offer->latestNegotiation->hr_notes)
                                                <p>Catatan HR: {{ $offer->latestNegotiation->hr_notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Negotiate Modal -->
<div id="negotiateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Ajukan Negosiasi Gaji</h3>
            <button onclick="document.getElementById('negotiateModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('candidate.offers.negotiate', $application->offer ?? 0) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gaji yang Anda Ajukan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="proposed_salary" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           min="0"
                           step="100000"
                           required
                           placeholder="Contoh: 15000000">
                    <p class="text-xs text-gray-500 mt-1">Gaji saat ini: Rp {{ number_format($application->offer->salary ?? 0, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Negosiasi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="candidate_notes" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              required
                              placeholder="Jelaskan alasan Anda mengajukan negosiasi gaji..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Berikan alasan yang jelas dan profesional</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('negotiateModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Kirim Negosiasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tolak Penawaran</h3>
            <button onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('candidate.offers.reject', $application->offer ?? 0) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Setelah menolak penawaran, status aplikasi Anda akan berubah dan Anda tidak dapat mengubahnya kembali.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Anda dapat memberikan alasan penolakan..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Ya, Tolak Penawaran
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

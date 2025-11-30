<x-candidate-layout>
    <x-slot name="header">Dashboard Kandidat</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left + Center) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Dikirim -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Dikirim</span>
                        <i class="fas fa-paper-plane text-orange-500 text-xl"></i>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                </div>

                <!-- Wawancara Terjadwal -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Wawancara Terjadwal</span>
                        <i class="fas fa-calendar-alt text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $stats['interview'] }}</h3>
                </div>

                <!-- Ditawarkan -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Ditawarkan</span>
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $stats['success'] }}</h3>
                </div>

                <!-- Ditolak -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Ditolak</span>
                        <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900">{{ $stats['rejected'] }}</h3>
                </div>
            </div>

            <!-- Daftar Aplikasi -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Aplikasi</h2>
                </div>

                @if($applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Pekerjaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Pekerjaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Aplikasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($applications as $application)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900">{{ $application->jobPosting->title }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm text-gray-600">{{ $application->application_code ?? $application->code ?? 'APP-' . $application->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $application->created_at->format('Y-m-d') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = [
                                                    'submitted' => ['label' => 'submitted', 'color' => 'yellow'],
                                                    'screening_passed' => ['label' => 'interview scheduled', 'color' => 'blue'],
                                                    'interview_scheduled' => ['label' => 'interview scheduled', 'color' => 'blue'],
                                                    'interview_passed' => ['label' => 'interview scheduled', 'color' => 'blue'],
                                                    'offered' => ['label' => 'offered', 'color' => 'green'],
                                                    'hired' => ['label' => 'offered', 'color' => 'green'],
                                                    'rejected_admin' => ['label' => 'rejected admin', 'color' => 'red'],
                                                    'rejected_interview' => ['label' => 'rejected admin', 'color' => 'red'],
                                                ];
                                                $config = $statusConfig[$application->status] ?? ['label' => $application->status, 'color' => 'gray'];
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($applications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $applications->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Aplikasi</h3>
                        <p class="text-gray-600 mb-6">Anda belum melamar pekerjaan apapun</p>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Cari Lowongan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <img src="{{ auth()->user()->profile_photo ? Storage::url(auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=200' }}" 
                         alt="{{ auth()->user()->name }}"
                         class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-100">
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ auth()->user()->email }}</p>
                    
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-1">
                        <i class="fas fa-phone text-gray-400"></i>
                        <span>{{ auth()->user()->phone ?? '+62 812 3456 7890' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        <span>{{ auth()->user()->address ?? 'Jakarta, Indonesia' }}</span>
                    </div>

                    <!-- Profile Completion -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-600">Kelengkapan Profil:</span>
                            <span class="font-semibold text-gray-900">{{ $profileCompletion ?? 75 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $profileCompletion ?? 75 }}%"></div>
                        </div>
                    </div>

                    <a href="{{ route('candidate.profile') }}" 
                       class="block w-full py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm mb-2">
                        Edit Profil →
                    </a>
                    
                    <a href="#" 
                       class="block w-full py-2 px-4 text-gray-600 rounded-lg hover:bg-gray-50 font-medium text-sm">
                        Pengaturan Notifikasi →
                    </a>
                </div>
            </div>

            <!-- Notifikasi Terbaru -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notifikasi Terbaru</h3>
                
                <div class="space-y-4">
                    @forelse($notifications ?? [] as $notification)
                        <div class="flex gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-{{ $notification['icon'] ?? 'bell' }} text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 mb-1">{{ $notification['title'] }}</p>
                                <p class="text-xs text-gray-500">{{ $notification['time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 mb-1">Undangan wawancara untuk Posisi Software Engineer.</p>
                                <p class="text-xs text-gray-500">1 hari yang lalu</p>
                            </div>
                        </div>

                        <div class="flex gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bell text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 mb-1">Status aplikasi Anda untuk UI/UX Designer telah berubah menjadi "Ditawarkan".</p>
                                <p class="text-xs text-gray-500">1 hari yang lalu</p>
                            </div>
                        </div>

                        <div class="flex gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clipboard-list text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 mb-1">Aplikasi Anda untuk Data Analyst telah dikirim.</p>
                                <p class="text-xs text-gray-500">3 hari yang lalu</p>
                            </div>
                        </div>

                        <div class="flex gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 mb-1">Pengingat wawancara untuk Posisi Product Manager besok.</p>
                                <p class="text-xs text-gray-500">1 minggu yang lalu</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-candidate-layout>


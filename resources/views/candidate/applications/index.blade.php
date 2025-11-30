<x-candidate-layout>
    <x-slot name="header">Aplikasi Saya</x-slot>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <div class="relative">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Cari posisi atau kode..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="submitted">Submitted</option>
                    <option value="screening_passed">Screening Passed</option>
                    <option value="interview_scheduled">Interview Scheduled</option>
                    <option value="interview_passed">Interview Passed</option>
                    <option value="offered">Offered</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select id="sortBy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="position">Posisi (A-Z)</option>
                </select>
            </div>

            <div class="flex items-end">
                <button onclick="resetFilters()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Aplikasi</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $applications->total() }}</h3>
                </div>
                <i class="fas fa-paper-plane text-orange-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $applications->where('status', 'submitted')->count() }}</h3>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Interview</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $applications->whereIn('status', ['interview_scheduled', 'interview_passed'])->count() }}</h3>
                </div>
                <i class="fas fa-calendar-alt text-blue-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Diterima</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $applications->whereIn('status', ['offered', 'hired'])->count() }}</h3>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Semua Aplikasi</h2>
                <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                    <i class="fas fa-plus mr-2"></i>Lamar Pekerjaan Baru
                </a>
            </div>
        </div>

        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full" id="applicationsTable">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Apply</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($applications as $application)
                            <tr class="hover:bg-gray-50 application-row" 
                                data-status="{{ $application->status }}" 
                                data-position="{{ strtolower($application->jobPosting->title) }}"
                                data-code="{{ strtolower($application->application_code ?? 'APP-' . $application->id) }}"
                                data-date="{{ $application->created_at->timestamp }}">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-gray-900 font-medium">{{ $application->application_code ?? 'APP-' . $application->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $application->jobPosting->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $application->jobPosting->location->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $application->jobPosting->division->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $application->created_at->format('d M Y') }}</span>
                                    <p class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'submitted' => ['label' => 'Submitted', 'color' => 'yellow', 'icon' => 'clock'],
                                            'screening_passed' => ['label' => 'Lolos Screening', 'color' => 'blue', 'icon' => 'check'],
                                            'interview_scheduled' => ['label' => 'Interview Terjadwal', 'color' => 'purple', 'icon' => 'calendar'],
                                            'interview_passed' => ['label' => 'Lolos Interview', 'color' => 'indigo', 'icon' => 'check-circle'],
                                            'offered' => ['label' => 'Ditawarkan', 'color' => 'green', 'icon' => 'gift'],
                                            'hired' => ['label' => 'Diterima', 'color' => 'green', 'icon' => 'check-double'],
                                            'rejected_admin' => ['label' => 'Ditolak Screening', 'color' => 'red', 'icon' => 'times-circle'],
                                            'rejected_interview' => ['label' => 'Ditolak Interview', 'color' => 'red', 'icon' => 'times-circle'],
                                        ];
                                        $config = $statusConfig[$application->status] ?? ['label' => $application->status, 'color' => 'gray', 'icon' => 'question'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                        <i class="fas fa-{{ $config['icon'] }}"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-eye"></i>
                                        Detail
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
                <p class="text-gray-600 mb-6">Anda belum melamar pekerjaan apapun. Mulai cari pekerjaan impian Anda!</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    <i class="fas fa-search mr-2"></i>Cari Lowongan Sekarang
                </a>
            </div>
        @endif
    </div>

    <script>
        // Filter and search functionality
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const sortBy = document.getElementById('sortBy');
        const table = document.getElementById('applicationsTable');
        const rows = table ? Array.from(table.querySelectorAll('.application-row')) : [];

        function filterAndSort() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusFilter = filterStatus.value.toLowerCase();
            
            // Filter rows
            const filteredRows = rows.filter(row => {
                const position = row.dataset.position;
                const code = row.dataset.code;
                const status = row.dataset.status;
                
                const matchSearch = position.includes(searchTerm) || code.includes(searchTerm);
                const matchStatus = !statusFilter || status === statusFilter || 
                                  (statusFilter === 'rejected' && status.includes('rejected'));
                
                return matchSearch && matchStatus;
            });

            // Sort rows
            const sortValue = sortBy.value;
            filteredRows.sort((a, b) => {
                if (sortValue === 'newest') {
                    return b.dataset.date - a.dataset.date;
                } else if (sortValue === 'oldest') {
                    return a.dataset.date - b.dataset.date;
                } else if (sortValue === 'position') {
                    return a.dataset.position.localeCompare(b.dataset.position);
                }
                return 0;
            });

            // Show/hide rows
            rows.forEach(row => {
                row.style.display = filteredRows.includes(row) ? '' : 'none';
            });
        }

        function resetFilters() {
            searchInput.value = '';
            filterStatus.value = '';
            sortBy.value = 'newest';
            filterAndSort();
        }

        if (searchInput) searchInput.addEventListener('input', filterAndSort);
        if (filterStatus) filterStatus.addEventListener('change', filterAndSort);
        if (sortBy) sortBy.addEventListener('change', filterAndSort);
    </script>
</x-candidate-layout>

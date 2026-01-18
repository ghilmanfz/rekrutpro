<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Audit - EasyRecruit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    @include('components.superadmin-sidebar')

    <!-- Main Content -->
    <main style="margin-left: 256px;">
        <!-- Top Bar -->
        <div class="bg-white border-b px-8 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan & Audit</h2>
                <p class="text-sm text-gray-600 mt-1">Monitoring aktivitas & laporan rekrutmen</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Super Admin</p>
                </div>
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-8">
            <!-- Ringkasan Laporan -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Laporan Rekrutmen</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Lamaran -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Lamaran Diterima</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_applications'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Lolos Screening -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Lamaran Lolos Screening</p>
                                <p class="text-3xl font-bold text-green-600">{{ $stats['screening_passed'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Waktu Hiring -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Rata-rata Waktu Hiring</p>
                                <p class="text-3xl font-bold text-purple-600">{{ $stats['avg_hiring_time'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Audit Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Log Audit</h3>
                </div>
                
                <!-- Search Bar -->
                <form method="GET" action="{{ route('superadmin.audit') }}" class="mb-6">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <input type="text" 
                                name="activity" 
                                placeholder="Cari aktivitas, pengguna, atau kandidat..." 
                                value="{{ request('activity') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Audit Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandidat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($auditLogs as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-xs font-semibold text-blue-600">
                                                        {{ $log->user ? substr($log->user->name, 0, 1) : '?' }}
                                                    </span>
                                                </div>
                                                <span>{{ $log->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                // Hanya tampilkan nama kandidat jika model_type adalah Application
                                                $candidateName = '-';
                                                if ($log->model_type === 'App\\Models\\Application') {
                                                    if ($log->new_values && isset($log->new_values['candidate_name'])) {
                                                        $candidateName = $log->new_values['candidate_name'];
                                                    } elseif ($log->old_values && isset($log->old_values['candidate_name'])) {
                                                        $candidateName = $log->old_values['candidate_name'];
                                                    } elseif ($log->new_values && isset($log->new_values['name'])) {
                                                        // Fallback ke 'name' field untuk backward compatibility
                                                        $candidateName = $log->new_values['name'];
                                                    }
                                                }
                                            @endphp
                                            {{ $candidateName }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($log->old_values && $log->new_values && isset($log->old_values['status']) && isset($log->new_values['status']))
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                                    {{ $log->old_values['status'] }} → {{ $log->new_values['status'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <button onclick="showAuditDetail({{ $log->id }})" class="text-blue-600 hover:text-blue-800 font-medium">Detail</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500 font-medium">Tidak ada log audit ditemukan</p>
                                            <p class="text-sm text-gray-400 mt-1">Log aktivitas akan muncul di sini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($auditLogs->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $auditLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Detail Audit -->
    <div id="auditDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Detail Log Audit</h3>
                <button onclick="closeAuditDetail()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="auditDetailContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        const auditData = @json($auditLogs->items());
        
        console.log('Audit Data Loaded:', auditData);

        function showAuditDetail(logId) {
            console.log('Show detail for log ID:', logId);
            const log = auditData.find(item => item.id === logId);
            
            if (!log) {
                console.error('Log not found:', logId);
                alert('Data tidak ditemukan!');
                return;
            }
            
            console.log('Log data:', log);

            // Status Change
            let statusChangeHtml = '';
            if (log.old_values && log.new_values && log.old_values.status && log.new_values.status) {
                statusChangeHtml = `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-900">Perubahan Status</p>
                        <p class="text-sm text-blue-700 mt-1">${log.old_values.status} → ${log.new_values.status}</p>
                    </div>
                `;
            }

            // Model Info
            let modelHtml = '';
            if (log.model_type) {
                const modelName = log.model_type.split('\\').pop();
                modelHtml = `
                    <div>
                        <p class="text-sm font-medium text-gray-700">Model</p>
                        <p class="text-sm text-gray-900 mt-1">${modelName} #${log.model_id || '-'}</p>
                    </div>
                `;
            }

            // Old Values
            let oldValuesHtml = '';
            if (log.old_values && Object.keys(log.old_values).length > 0) {
                const oldRows = Object.entries(log.old_values)
                    .map(([key, value]) => `
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                            <span class="text-sm text-gray-900">${value !== null && value !== '' ? value : '-'}</span>
                        </div>
                    `).join('');
                
                oldValuesHtml = `
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Nilai Lama</p>
                        <div class="bg-red-50 rounded-lg p-3">
                            ${oldRows}
                        </div>
                    </div>
                `;
            }

            // New Values
            let newValuesHtml = '';
            if (log.new_values && Object.keys(log.new_values).length > 0) {
                const newRows = Object.entries(log.new_values)
                    .map(([key, value]) => `
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                            <span class="text-sm text-gray-900">${value !== null && value !== '' ? value : '-'}</span>
                        </div>
                    `).join('');
                
                newValuesHtml = `
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Nilai Baru</p>
                        <div class="bg-green-50 rounded-lg p-3">
                            ${newRows}
                        </div>
                    </div>
                `;
            }

            const content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Waktu</p>
                            <p class="text-sm text-gray-900 mt-1">${new Date(log.created_at).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Pengguna</p>
                            <p class="text-sm text-gray-900 mt-1">${log.user ? log.user.name : '-'}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-700">Aktivitas</p>
                        <p class="text-sm text-gray-900 mt-1">${log.action.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                    </div>

                    ${modelHtml}
                    ${statusChangeHtml}
                    ${oldValuesHtml}
                    ${newValuesHtml}

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Informasi Teknis</p>
                        <p class="text-xs text-gray-600">IP Address: ${log.ip_address || '-'}</p>
                        <p class="text-xs text-gray-600 mt-1">User Agent: ${log.user_agent || '-'}</p>
                    </div>
                </div>
            `;

            document.getElementById('auditDetailContent').innerHTML = content;
            document.getElementById('auditDetailModal').classList.remove('hidden');
        }

        function closeAuditDetail() {
            document.getElementById('auditDetailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('auditDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAuditDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuditDetail();
            }
        });
    </script>
</body>
</html>

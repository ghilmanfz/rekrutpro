<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfigurasi Sistem - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    @include('components.superadmin-sidebar')

    <!-- Main Content -->
    <main style="margin-left: 256px;">
        <!-- Top Bar -->
        <div class="bg-white border-b px-8 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Konfigurasi Sistem</h2>
                <p class="text-sm text-gray-600 mt-1">Pengaturan WhatsApp API, Email & Template Notifikasi</p>
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

        <!-- Content -->
        <div class="p-8">
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            <!-- Konfigurasi WhatsApp API Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Konfigurasi WhatsApp API (Fonnte.com)</h3>
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <form action="{{ route('superadmin.config.whatsapp') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp *</label>
                                <input type="text" name="whatsapp_phone" value="{{ $whatsappPhone }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="628123456789">
                                <p class="text-xs text-gray-500 mt-1">Format: 628xxx (tanpa + atau 0)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">API Key Fonnte *</label>
                                <input type="text" name="whatsapp_api_key" value="{{ $whatsappApiKey }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan API Key dari fonnte.com">
                                <p class="text-xs text-gray-500 mt-1">Dapatkan di <a href="https://fonnte.com" target="_blank" class="text-blue-600 hover:underline">fonnte.com</a></p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
                                Simpan Konfigurasi WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Konfigurasi Email Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Konfigurasi Email</h3>
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <form action="{{ route('superadmin.config.email') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver *</label>
                                <select name="email_driver" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="smtp" {{ $emailDriver == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $emailDriver == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ $emailDriver == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ $emailDriver == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Host *</label>
                                <input type="text" name="email_host" value="{{ $emailHost }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="smtp.gmail.com">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Port *</label>
                                <input type="number" name="email_port" value="{{ $emailPort }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="587">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Encryption *</label>
                                <select name="email_encryption" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="tls" {{ $emailEncryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $emailEncryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" name="email_username" value="{{ $emailUsername }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="your-email@gmail.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password" name="email_password" value="{{ $emailPassword }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Email *</label>
                                <input type="email" name="email_from_address" value="{{ $emailFromAddress }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="noreply@rekrutpro.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Name *</label>
                                <input type="text" name="email_from_name" value="{{ $emailFromName }}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                    placeholder="RekrutPro">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                                Simpan Konfigurasi Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Template Notifikasi Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">Template Notifikasi</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola template email dan WhatsApp</p>
                    </div>
                    <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        + Tambah Template
                    </button>
                </div>

                <!-- Available Placeholders Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2">Placeholder Yang Tersedia:</h4>
                    <div class="grid grid-cols-3 gap-2 text-sm text-blue-800">
                        <div><code class="bg-white px-2 py-1 rounded">@{{ nama }}</code> - Nama kandidat</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ email }}</code> - Email kandidat</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ posisi }}</code> - Posisi yang dilamar</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ kode_lamaran }}</code> - Kode lamaran</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ tanggal }}</code> - Tanggal</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ waktu }}</code> - Waktu</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ lokasi }}</code> - Lokasi interview</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ interviewer }}</code> - Nama interviewer</div>
                        <div><code class="bg-white px-2 py-1 rounded">@{{ gaji }}</code> - Penawaran gaji</div>
                    </div>
                </div>

                <!-- Templates Table -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Channel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($templates as $template)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ $template->event }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($template->channel == 'email')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Email</span>
                                    @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">WhatsApp</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $template->subject ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick='editTemplate(@json($template))' class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <form action="{{ route('superadmin.templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-4">Belum ada template notifikasi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Template -->
    <div id="templateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-3/4 max-w-3xl shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4" id="modalTitle">Tambah Template Notifikasi</h3>
            <form id="templateForm" method="POST" action="{{ route('superadmin.templates.store') }}">
                @csrf
                <input type="hidden" id="templateMethod" name="_method" value="POST">
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Event *</label>
                        <select name="event" id="templateEvent" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">-- Pilih Event --</option>
                            <option value="application_submitted">Lamaran Diterima</option>
                            <option value="screening_passed">Lolos Screening</option>
                            <option value="screening_rejected">Tidak Lolos Screening</option>
                            <option value="interview_scheduled">Undangan Interview</option>
                            <option value="interview_reminder">Reminder Interview</option>
                            <option value="interview_passed">Lolos Interview</option>
                            <option value="interview_rejected">Tidak Lolos Interview</option>
                            <option value="offer_sent">Penawaran Kerja</option>
                            <option value="offer_accepted">Offer Diterima</option>
                            <option value="offer_rejected">Offer Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Channel *</label>
                        <select name="channel" id="templateChannel" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">-- Pilih Channel --</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Subject (untuk Email)</label>
                    <input type="text" name="subject" id="templateSubject" class="w-full px-3 py-2 border rounded-lg" placeholder="Contoh: Konfirmasi Lamaran - @{{ posisi }}">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Body/Isi Pesan *</label>
                    <textarea name="body" id="templateBody" required rows="8" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" placeholder="Dear @{{ nama }},&#10;&#10;Terima kasih telah melamar posisi @{{ posisi }} di perusahaan kami..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Gunakan placeholder seperti @{{ nama }}, @{{ posisi }}, dll.</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Template</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('templateModal').classList.add('hidden');
            document.getElementById('templateForm').reset();
            document.getElementById('templateForm').action = "{{ route('superadmin.templates.store') }}";
            document.getElementById('templateMethod').value = 'POST';
            document.getElementById('modalTitle').textContent = 'Tambah Template Notifikasi';
        }

        function editTemplate(template) {
            document.getElementById('templateForm').action = "{{ url('superadmin/templates') }}/" + template.id;
            document.getElementById('templateMethod').value = 'PUT';
            document.getElementById('templateEvent').value = template.event;
            document.getElementById('templateChannel').value = template.channel;
            document.getElementById('templateSubject').value = template.subject || '';
            document.getElementById('templateBody').value = template.body;
            document.getElementById('modalTitle').textContent = 'Edit Template Notifikasi';
            openModal();
        }
    </script>
</body>
</html>

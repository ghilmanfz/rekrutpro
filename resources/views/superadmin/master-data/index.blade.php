<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    @include('components.superadmin-sidebar')

    <!-- Main Content -->
    <main style="margin-left: 256px;">
        <!-- Top Bar -->
        <div class="bg-white border-b px-8 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Data Master</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data master sistem</p>
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

            <!-- Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="switchTab('divisions')" id="tab-divisions" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                            Divisi
                        </button>
                        <button onclick="switchTab('positions')" id="tab-positions" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Posisi
                        </button>
                        <button onclick="switchTab('locations')" id="tab-locations" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Lokasi
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Divisions Tab -->
            <div id="content-divisions" class="tab-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Divisi</h3>
                    <button onclick="openModal('divisionModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        + Tambah Divisi
                    </button>
                </div>
                <div class="bg-white rounded-lg shadow-sm border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($divisions as $division)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $division->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $division->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $division->users_count }} pengguna</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="editDivision({{ $division->id }}, '{{ $division->name }}', '{{ $division->description }}')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <form action="{{ route('superadmin.divisions.destroy', $division) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada data divisi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Positions Tab -->
            <div id="content-positions" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Posisi</h3>
                    <button onclick="openModal('positionModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        + Tambah Posisi
                    </button>
                </div>
                <div class="bg-white rounded-lg shadow-sm border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Lowongan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($positions as $position)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $position->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $position->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $position->job_postings_count }} lowongan</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="editPosition({{ $position->id }}, '{{ $position->name }}', '{{ $position->description }}')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <form action="{{ route('superadmin.positions.destroy', $position) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada data posisi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Locations Tab -->
            <div id="content-locations" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Lokasi</h3>
                    <button onclick="openModal('locationModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        + Tambah Lokasi
                    </button>
                </div>
                <div class="bg-white rounded-lg shadow-sm border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Lowongan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($locations as $location)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $location->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $location->address ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $location->job_postings_count }} lowongan</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="editLocation({{ $location->id }}, '{{ $location->name }}', '{{ $location->address }}')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <form action="{{ route('superadmin.locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada data lokasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Divisi -->
    <div id="divisionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4" id="divisionModalTitle">Tambah Divisi</h3>
            <form id="divisionForm" method="POST" action="{{ route('superadmin.divisions.store') }}">
                @csrf
                <input type="hidden" id="divisionMethod" name="_method" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama *</label>
                    <input type="text" name="name" id="divisionName" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="description" id="divisionDescription" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('divisionModal')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Posisi -->
    <div id="positionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4" id="positionModalTitle">Tambah Posisi</h3>
            <form id="positionForm" method="POST" action="{{ route('superadmin.positions.store') }}">
                @csrf
                <input type="hidden" id="positionMethod" name="_method" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama *</label>
                    <input type="text" name="name" id="positionName" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="description" id="positionDescription" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('positionModal')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lokasi -->
    <div id="locationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4" id="locationModalTitle">Tambah Lokasi</h3>
            <form id="locationForm" method="POST" action="{{ route('superadmin.locations.store') }}">
                @csrf
                <input type="hidden" id="locationMethod" name="_method" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama *</label>
                    <input type="text" name="name" id="locationName" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Alamat</label>
                    <textarea name="address" id="locationAddress" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('locationModal')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('border-blue-500', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('border-blue-500', 'text-blue-600');
            document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            if(modalId === 'divisionModal') {
                document.getElementById('divisionForm').reset();
                document.getElementById('divisionForm').action = "{{ route('superadmin.divisions.store') }}";
                document.getElementById('divisionMethod').value = 'POST';
                document.getElementById('divisionModalTitle').textContent = 'Tambah Divisi';
            } else if(modalId === 'positionModal') {
                document.getElementById('positionForm').reset();
                document.getElementById('positionForm').action = "{{ route('superadmin.positions.store') }}";
                document.getElementById('positionMethod').value = 'POST';
                document.getElementById('positionModalTitle').textContent = 'Tambah Posisi';
            } else if(modalId === 'locationModal') {
                document.getElementById('locationForm').reset();
                document.getElementById('locationForm').action = "{{ route('superadmin.locations.store') }}";
                document.getElementById('locationMethod').value = 'POST';
                document.getElementById('locationModalTitle').textContent = 'Tambah Lokasi';
            }
        }

        function editDivision(id, name, description) {
            document.getElementById('divisionForm').action = "{{ url('superadmin/divisions') }}/" + id;
            document.getElementById('divisionMethod').value = 'PUT';
            document.getElementById('divisionName').value = name;
            document.getElementById('divisionDescription').value = description;
            document.getElementById('divisionModalTitle').textContent = 'Edit Divisi';
            openModal('divisionModal');
        }

        function editPosition(id, name, description) {
            document.getElementById('positionForm').action = "{{ url('superadmin/positions') }}/" + id;
            document.getElementById('positionMethod').value = 'PUT';
            document.getElementById('positionName').value = name;
            document.getElementById('positionDescription').value = description;
            document.getElementById('positionModalTitle').textContent = 'Edit Posisi';
            openModal('positionModal');
        }

        function editLocation(id, name, address) {
            document.getElementById('locationForm').action = "{{ url('superadmin/locations') }}/" + id;
            document.getElementById('locationMethod').value = 'PUT';
            document.getElementById('locationName').value = name;
            document.getElementById('locationAddress').value = address;
            document.getElementById('locationModalTitle').textContent = 'Edit Lokasi';
            openModal('locationModal');
        }
    </script>
</body>
</html>

<x-candidate-layout>
    <x-slot name="header">Notifikasi</x-slot>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200">
            <button onclick="filterNotifications('all')" 
                    class="filter-tab px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                Semua
            </button>
            <button onclick="filterNotifications('unread')" 
                    class="filter-tab px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Belum Dibaca
                <span class="ml-2 px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">{{ $unreadCount ?? 0 }}</span>
            </button>
            <button onclick="filterNotifications('read')" 
                    class="filter-tab px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Sudah Dibaca
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Semua Notifikasi</h2>
            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-check-double mr-1"></i>Tandai Semua Dibaca
            </button>
        </div>

        <div class="divide-y divide-gray-200" id="notificationsList">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification['read'] ? 'read' : 'unread' }} p-6 hover:bg-gray-50 transition-colors" 
                     data-status="{{ $notification['read'] ? 'read' : 'unread' }}">
                    <div class="flex gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-{{ $notification['color'] }}-100 flex items-center justify-center">
                                <i class="fas fa-{{ $notification['icon'] }} text-{{ $notification['color'] }}-600 text-lg"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                        {{ $notification['title'] }}
                                        @if(!$notification['read'])
                                            <span class="inline-block w-2 h-2 bg-blue-600 rounded-full ml-2"></span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $notification['description'] }}</p>
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-clock"></i>
                                            {{ $notification['time'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    @if(!$notification['read'])
                                        <button onclick="markAsRead({{ $notification['id'] }})" 
                                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteNotification({{ $notification['id'] }})" 
                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="fas fa-bell-slash text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Notifikasi</h3>
                    <p class="text-gray-600">Anda belum memiliki notifikasi apapun.</p>
                </div>
            @endforelse
        </div>

        <!-- Load More -->
        @if(count($notifications) >= 10)
            <div class="p-4 border-t border-gray-200 text-center">
                <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    <i class="fas fa-chevron-down mr-2"></i>Muat Lebih Banyak
                </button>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-envelope text-3xl opacity-80"></i>
                <span class="text-3xl font-bold">{{ $unreadCount ?? 0 }}</span>
            </div>
            <h3 class="text-lg font-semibold mb-1">Belum Dibaca</h3>
            <p class="text-blue-100 text-sm">Notifikasi yang belum dibaca</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
                <span class="text-3xl font-bold">{{ $readCount ?? 0 }}</span>
            </div>
            <h3 class="text-lg font-semibold mb-1">Sudah Dibaca</h3>
            <p class="text-green-100 text-sm">Notifikasi yang sudah dibaca</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-bell text-3xl opacity-80"></i>
                <span class="text-3xl font-bold">{{ count($notifications) }}</span>
            </div>
            <h3 class="text-lg font-semibold mb-1">Total Notifikasi</h3>
            <p class="text-purple-100 text-sm">Semua notifikasi Anda</p>
        </div>
    </div>

    <script>
        // Filter notifications
        function filterNotifications(filter) {
            const items = document.querySelectorAll('.notification-item');
            const tabs = document.querySelectorAll('.filter-tab');
            
            // Update active tab
            tabs.forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-blue-600', 'text-blue-600');

            // Filter items
            items.forEach(item => {
                const status = item.dataset.status;
                if (filter === 'all') {
                    item.style.display = '';
                } else if (filter === 'unread' && status === 'unread') {
                    item.style.display = '';
                } else if (filter === 'read' && status === 'read') {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Mark as read
        function markAsRead(id) {
            // TODO: Implement AJAX call to mark notification as read
            console.log('Mark as read:', id);
            alert('Fitur ini akan segera tersedia!');
        }

        // Mark all as read
        function markAllAsRead() {
            // TODO: Implement AJAX call to mark all notifications as read
            if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
                alert('Fitur ini akan segera tersedia!');
            }
        }

        // Delete notification
        function deleteNotification(id) {
            if (confirm('Hapus notifikasi ini?')) {
                // TODO: Implement AJAX call to delete notification
                console.log('Delete notification:', id);
                alert('Fitur ini akan segera tersedia!');
            }
        }
    </script>
</x-candidate-layout>

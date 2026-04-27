<x-app-layout>
    <x-slot name="header">
        Dashboard Administrator
    </x-slot>

    <!-- Panel Manajemen User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="{ openCreateModal: false, openEditModal: false, editData: {} }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Manajemen Pengguna</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola data Mahasiswa, Dosen, Kaprodi, dan Admin lainnya.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button @click="openCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah User
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabel User -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tl-lg">Nama</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-800 font-medium">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $roleColors = [
                                        'mahasiswa' => 'bg-green-100 text-green-800',
                                        'dosen' => 'bg-blue-100 text-blue-800',
                                        'kaprodi' => 'bg-purple-100 text-purple-800',
                                        'admin' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <button @click="openEditModal = true; editData = { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' }" class="text-indigo-600 hover:text-indigo-900 mr-3 font-medium transition-colors">Edit</button>
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block form-delete" id="delete-form-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $user->id }})" class="text-red-600 hover:text-red-900 font-medium transition-colors" {{ $user->id === auth()->id() ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>

        <!-- Create Modal -->
        <div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openCreateModal" x-transition.scale class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 pt-6 pb-5">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-gray-900">Tambah User Baru</h3>
                                <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email</label>
                                    <input type="email" name="email" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Password</label>
                                    <input type="password" name="password" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Role Akses</label>
                                    <select name="role" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="kaprodi">Kaprodi</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-100">
                            <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openEditModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openEditModal" x-transition.scale class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <form :action="`/admin/users/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-6 pt-6 pb-5">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-gray-900">Edit Data User</h3>
                                <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="editData.name" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email</label>
                                    <input type="email" name="email" x-model="editData.email" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                </div>
                                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                    <label class="block text-xs font-bold text-amber-800 uppercase tracking-wide mb-1">Password Baru (Opsional)</label>
                                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="block w-full border-amber-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm py-2 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Role Akses</label>
                                    <select name="role" x-model="editData.role" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="kaprodi">Kaprodi</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-100">
                            <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for SweetAlert Delete Confirmation -->
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data user yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl border border-gray-100 shadow-2xl',
                    title: 'text-lg font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
</x-app-layout>

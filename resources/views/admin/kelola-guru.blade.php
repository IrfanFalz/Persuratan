@extends('admin.layout')

@section('title', 'Kelola Data Guru')
@section('page-title', 'Kelola Data Guru')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="card-shadow rounded-2xl p-6 bg-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Guru & Staff</h1>
                <p class="text-gray-600 mt-1">Kelola data guru, kepala sekolah, dan staff tata usaha</p>
            </div>
            <button onclick="openAddModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700"
                style="z-index:999; position:relative; display:inline-block;">
                Tambah Data Guru
            </button>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card-shadow rounded-2xl p-6 bg-white">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau NIP..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <div class="absolute left-3 inset-y-0 left-3 flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="lg:w-48">
                <select id="roleFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Semua Role</option>
                    <option value="KEPSEK">Kepala Sekolah</option>
                    <option value="GURU">Guru</option>
                    <option value="TU">Tata Usaha</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-shadow rounded-2xl bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Data Guru & Staff</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="guruTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Nama & NIP</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No. Telepon</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($dataGuru as $index => $guru)
                    <tr class="hover:bg-gray-50 transition-colors duration-200" data-nama="{{ strtolower($guru['nama']) }}" data-nip="{{ $guru['nip'] }}" data-role="{{ $guru['role'] }}">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $guru['nama'] }}</div>
                                <div class="text-sm text-gray-500">NIP: {{ $guru['nip'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                @if($guru['role'] == 'KEPSEK') bg-red-100 text-red-800
                                @elseif($guru['role'] == 'GURU') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                @if($guru['role'] == 'KEPSEK') Kepala Sekolah
                                @elseif($guru['role'] == 'GURU') Guru
                                @else Tata Usaha @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $guru['telp'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ json_encode($guru) }})" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $guru['id'] }}, '{{ $guru['nama'] }}')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="guruModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md mx-auto shadow-2xl transform scale-95 transition-all duration-300" id="modalContent">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Tambah Data Guru</h3>
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="guruForm" class="p-6 space-y-4">
            <input type="hidden" id="guruId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="nama" required 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan nama lengkap">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                <input type="text" id="nip" required 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan NIP">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="role" required 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="" disabled selected hidden>Pilih Role</option>
                    <option value="KEPSEK">Kepala Sekolah</option>
                    <option value="GURU">Guru</option>
                    <option value="TU">Tata Usaha</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                <input type="text" id="telp" required 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan nomor telepon">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" required 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                       placeholder="Masukkan Password">
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" 
                        class="flex-1 px-6 py-3 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl text-white font-medium transition-all duration-300 shadow">
                    Simpan
                </button>

            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md mx-auto shadow-2xl transform scale-95 transition-all duration-300">
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data <strong id="deleteName"></strong>? Data yang dihapus tidak dapat dikembalikan.</p>
            
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-6 py-3 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300">
                    Batal
                </button>
                <button onclick="deleteGuru()" 
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-300">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeleteId = null;

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterTable();
});

document.getElementById('roleFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('#guruTable tbody tr');
    
    rows.forEach(row => {
        const nama = row.dataset.nama;
        const nip = row.dataset.nip.toLowerCase();
        const role = row.dataset.role;
        
        const matchesSearch = nama.includes(searchTerm) || nip.includes(searchTerm);
        const matchesRole = !roleFilter || role === roleFilter;
        
        if (matchesSearch && matchesRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Data Guru';
    document.getElementById('guruForm').reset();
    document.getElementById('guruId').value = '';
    showModal();
}

function openEditModal(guru) {
    document.getElementById('modalTitle').textContent = 'Edit Data Guru';
    document.getElementById('guruId').value = guru.id;
    document.getElementById('nama').value = guru.nama;
    document.getElementById('nip').value = guru.nip;
    document.getElementById('role').value = guru.role;
    document.getElementById('telp').value = guru.telp;
    document.getElementById('password').value = guru.pass;
    showModal();
}

function showModal() {
    const modal = document.getElementById('guruModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('guruModal');
    const modalContent = document.getElementById('modalContent');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

function confirmDelete(id, nama) {
    currentDeleteId = id;
    document.getElementById('deleteName').textContent = nama;
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    currentDeleteId = null;
}

function deleteGuru() {
    if (currentDeleteId) {
        // Simulasi hapus data (dalam implementasi nyata, kirim request ke server)
        const row = document.querySelector(`tr[data-nama][data-role]:nth-child(${currentDeleteId})`);
        if (row) {
            row.remove();
        }
        
        // Show success message
        showNotification('Data guru berhasil dihapus!', 'success');
        closeDeleteModal();
    }
}

document.getElementById('guruForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('guruId').value;
    const nama = document.getElementById('nama').value;
    const nip = document.getElementById('nip').value;
    const role = document.getElementById('role').value;
    const telp = document.getElementById('telp').value;
    const pass = document.getElementById('password').values;
    
    // Validasi form
    if (!nama || !nip || !role || !telp) {
        showNotification('Mohon lengkapi semua field!', 'error');
        return;
    }
    
    // Simulasi simpan data (dalam implementasi nyata, kirim request ke server)
    if (id) {
        // Update existing data
        showNotification('Data guru berhasil diperbarui!', 'success');
    } else {
        // Add new data
        showNotification('Data guru berhasil ditambahkan!', 'success');
    }
    
    closeModal();
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform translate-x-full transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Close modals when clicking outside
document.getElementById('guruModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
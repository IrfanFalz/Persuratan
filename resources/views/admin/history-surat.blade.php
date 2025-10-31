@extends('admin.layout')

@section('title', 'History Surat')
@section('page-title', 'History Surat')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="card-shadow rounded-2xl p-6 bg-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">History Surat</h1>
                <p class="text-gray-600 mt-1">Riwayat semua surat yang diajukan dan telah diproses</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="exportData()" class="p-2 bg-green-600 hover:bg-green-700px-4 py-2 border border-gray-200 text-white rounded-xl  transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card-shadow rounded-2xl p-6 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Surat</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan nama guru..." 
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    
                    <!-- Ikon Search -->
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                <select id="jenisFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Semua Jenis</option>
                    <option value="Surat Dispensasi">Surat Dispensasi</option>
                    <option value="Surat Panggilan Tugas">Surat Panggilan Tugas</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Semua Status</option>
                    <option value="Diajukan">Diajukan</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodeFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Semua Periode</option>
                    <option value="hari-ini">Hari Ini</option>
                    <option value="minggu-ini">Minggu Ini</option>
                    <option value="bulan-ini">Bulan Ini</option>
                    <option value="tahun-ini">Tahun Ini</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
            <button onclick="resetFilters()" class="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                Reset Filter
            </button>
            <div class="text-sm text-gray-600">
                Menampilkan <span id="recordCount">{{ $historySuratPaginated->count() }}</span> dari <span id="totalCount">{{ $pagination['total'] }}</span> data
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-shadow rounded-2xl p-6 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Surat</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pagination['total'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-shadow rounded-2xl p-6 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Diajukan</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $historySuratPaginated->where('status', 'Diajukan')->count() }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-shadow rounded-2xl p-6 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Diproses</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $historySuratPaginated->where('status', 'Diproses')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-shadow rounded-2xl p-6 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Selesai</p>
                    <p class="text-3xl font-bold text-green-600">{{ $historySuratPaginated->where('status', 'Selesai')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-shadow rounded-2xl bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Data History Surat</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="historyTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Guru Pengaju</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Jenis Surat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @foreach($historySuratPaginated as $index => $surat)
                    <tr class="hover:bg-gray-50 transition-colors duration-200" 
                        data-pengaju="{{ strtolower($surat->pengguna->nama_lengkap ?? '-') }}" 
                        data-jenis="{{ $surat->template->nama_template ?? '-' }}" 
                        data-status="{{ $surat->status }}"
                        data-tanggal="{{ $surat->dibuat_pada }}">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ ($pagination['current_page'] - 1) * $pagination['per_page'] + $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $surat->pengguna->nama_lengkap ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                {{ $surat['jenis'] == 'Surat Dispensasi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $surat->template->nama_template ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                @if($surat['status'] == 'Selesai') bg-green-100 text-green-800
                                @elseif($surat['status'] == 'Diproses') bg-yellow-100 text-yellow-800
                                @else bg-orange-100 text-orange-800 @endif">
                                {{ $surat['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($surat->dibuat_pada)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="viewDetail({{ json_encode($surat) }})" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300"
                                        title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                @if($surat['status'] == 'Selesai')
                                <button onclick="downloadSurat({{ $surat['id'] }})" 
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all duration-300"
                                        title="Download Surat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 }} - 
                    {{ min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) }} 
                    dari {{ $pagination['total'] }} data
                </div>
                
                <div class="flex items-center gap-2">
                    @if($pagination['current_page'] > 1)
                    <a href="?page={{ $pagination['current_page'] - 1 }}" 
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-white transition-colors duration-200">
                        ← Sebelumnya
                    </a>
                    @endif
                    
                    @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                    <a href="?page={{ $i }}" 
                       class="px-3 py-2 text-sm rounded-lg transition-colors duration-200 {{ $i == $pagination['current_page'] ? 'btn-gradient text-white' : 'border border-gray-200 hover:bg-white' }}">
                        {{ $i }}
                    </a>
                    @endfor
                    
                    @if($pagination['current_page'] < $pagination['last_page'])
                    <a href="?page={{ $pagination['current_page'] + 1 }}" 
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-white transition-colors duration-200">
                        Selanjutnya →
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-auto shadow-2xl transform scale-95 transition-all duration-300" id="detailModalContent">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Detail Surat</h3>
                <button onclick="closeDetailModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6" id="detailContent">
            <!-- Content will be filled by JavaScript -->
        </div>
    </div>
</div>

<script>
// Search and filter functionality
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('jenisFilter').addEventListener('change', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('periodeFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const jenisFilter = document.getElementById('jenisFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const periodeFilter = document.getElementById('periodeFilter').value;
    const rows = document.querySelectorAll('#tableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const pengaju = row.dataset.pengaju;
        const jenis = row.dataset.jenis;
        const status = row.dataset.status;
        const tanggal = row.dataset.tanggal;
        
        const matchesSearch = pengaju.includes(searchTerm);
        const matchesJenis = !jenisFilter || jenis === jenisFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        let matchesPeriode = true;
        if (periodeFilter) {
            const suratDate = new Date(tanggal);
            const today = new Date();
            
            switch (periodeFilter) {
                case 'hari-ini':
                    matchesPeriode = suratDate.toDateString() === today.toDateString();
                    break;
                case 'minggu-ini':
                    const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
                    matchesPeriode = suratDate >= startOfWeek;
                    break;
                case 'bulan-ini':
                    matchesPeriode = suratDate.getMonth() === today.getMonth() && 
                                   suratDate.getFullYear() === today.getFullYear();
                    break;
                case 'tahun-ini':
                    matchesPeriode = suratDate.getFullYear() === today.getFullYear();
                    break;
            }
        }
        
        if (matchesSearch && matchesJenis && matchesStatus && matchesPeriode) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('recordCount').textContent = visibleCount;
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('jenisFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('periodeFilter').value = '';
    filterTable();
}

function viewDetail(surat) {
    const content = `
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengaju</label>
                    <div class="text-gray-900">${surat.pengaju}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                    <div class="text-gray-900">${surat.jenis}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                        ${surat.status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                          surat.status === 'Diproses' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-orange-100 text-orange-800'}">
                        ${surat.status}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                    <div class="text-gray-900">${new Date(surat.tanggal).toLocaleDateString('id-ID')}</div>
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <button onclick="closeDetailModal()" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300">
                        Tutup
                    </button>
                    ${surat.status === 'Selesai' ? `
                    <button onclick="downloadSurat(${surat.id})" class="btn-gradient px-4 py-2 rounded-xl text-white transition-all duration-300">
                        Download Surat
                    </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('detailContent').innerHTML = content;
    showDetailModal();
}

function showDetailModal() {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('detailModalContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('detailModalContent');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

function downloadSurat(id) {
    // Simulasi download
    showNotification('Surat berhasil didownload!', 'success');
}

function exportData() {
    // Simulasi export
    showNotification('Data berhasil diexport!', 'success');
}

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

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endsection
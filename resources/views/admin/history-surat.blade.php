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
                    <option value="pending">Diajukan</option>
                    <option value="approve">Disetujui</option>
                    <option value="decline">Ditolak</option>
                    <option value="selesai">Selesai</option>
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
                    <p class="text-3xl font-bold text-orange-600">{{ $countPending ?? $historySuratPaginated->where('status_berkas', 'pending')->count() }}</p>
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
                    <p class="text-sm font-medium text-gray-600 mb-1">Disetujui</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $countApproved ?? $historySuratPaginated->where('status_berkas', 'approve')->count() }}</p>
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
                    <p class="text-3xl font-bold text-green-600">{{ $countSelesai ?? $historySuratPaginated->where('status_berkas', 'selesai')->count() }}</p>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            <input type="checkbox" id="selectAll" title="Pilih semua" />
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Guru Pengaju</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Jenis Surat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @foreach ($historySuratPaginated as $index => $surat)
                        @php
                            // Normalize status to canonical keys used by filters
                            $rawStatus = strtolower(trim($surat->status_berkas ?? ''));
                            if (in_array($rawStatus, ['pending', 'diajukan', 'diajukan'])) {
                                $statusKey = 'pending';
                            } elseif (in_array($rawStatus, ['approve', 'approved', 'disetujui', 'diproses'])) {
                                $statusKey = 'approve';
                            } elseif (in_array($rawStatus, ['decline', 'ditolak', 'declined', 'rejected', 'rejected'])) {
                                $statusKey = 'decline';
                            } elseif ($rawStatus === 'selesai' || $rawStatus === 'completed') {
                                $statusKey = 'selesai';
                            } else {
                                $statusKey = $rawStatus ?: 'unknown';
                            }
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors duration-200"
                            data-pengaju="{{ strtolower($surat->pengguna->nama ?? '-') }}"
                            data-jenis="{{ $surat->template->nama ?? '-' }}"
                            data-status="{{ $statusKey }}"
                            data-tanggal="{{ $surat->dibuat_pada }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rowCheckbox" data-id="{{ $surat->id_surat }}" />
                            </td>
                            {{-- Nomor urut --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ ($pagination['current_page'] - 1) * $pagination['per_page'] + $index + 1 }}
                            </td>

                            {{-- Guru Pengaju --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ $surat->pengguna->nama ?? '-' }}
                                </div>
                            </td>

                            {{-- Jenis Surat --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ $surat->template ? $surat->template->nama : '-' }}
                                </span>
                            </td>

                            {{-- Status Surat --}}
                            <td class="px-6 py-4">
                                <span id="status-{{ $surat->id_surat }}"
                                    class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    @if($surat->status_berkas == 'selesai') bg-green-100 text-green-800
                                    @elseif($surat->status_berkas == 'approve') bg-yellow-100 text-yellow-800
                                    @elseif($surat->status_berkas == 'decline') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($surat->status_berkas) }}
                                </span>
                            </td>

                            {{-- Tanggal dibuat --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($surat->dibuat_pada)->format('d/m/Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center overflow-visible">
                                    <div class="inline-flex items-center gap-1 bg-white/0 hover:bg-gray-50 rounded-md p-0.5 overflow-visible">
                                        {{-- Tombol Detail --}}
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center w-6 h-6 p-0.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-150"
                                            title="Lihat Detail"
                                            onclick="viewDetailFromButton(this)"
                                            aria-label="Lihat Detail"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>

                                        {{-- Tombol Download hanya jika status Selesai --}}
                                            @if ($surat->status_berkas === 'selesai')
                                            <button onclick="downloadSurat({{ $surat->id_surat }})"
                                                class="inline-flex items-center justify-center w-6 h-6 p-0.5 text-green-600 hover:bg-green-50 rounded-md transition-colors duration-150"
                                                title="Download Surat" aria-label="Download Surat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21H7a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
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
            const now = new Date();

            switch (periodeFilter) {
                case 'hari-ini':
                    matchesPeriode = suratDate.toDateString() === now.toDateString();
                    break;
                case 'minggu-ini':
                    // compute week start (Monday) and end (Sunday) without mutating shared Date
                    const day = now.getDay(); // 0 (Sun) .. 6 (Sat)
                    const diffToMonday = (day + 6) % 7; // 0 if Monday, 6 if Sunday
                    const startOfWeek = new Date(now);
                    startOfWeek.setHours(0,0,0,0);
                    startOfWeek.setDate(now.getDate() - diffToMonday);
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(startOfWeek.getDate() + 6);
                    endOfWeek.setHours(23,59,59,999);
                    matchesPeriode = suratDate >= startOfWeek && suratDate <= endOfWeek;
                    break;
                case 'bulan-ini':
                    matchesPeriode = suratDate.getMonth() === now.getMonth() && 
                                   suratDate.getFullYear() === now.getFullYear();
                    break;
                case 'tahun-ini':
                    matchesPeriode = suratDate.getFullYear() === now.getFullYear();
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

    const pengaju = surat.pengaju ?? "-";
    const jenis   = surat.jenis ?? "-";
    const status  = surat.status ?? "-";

    let tgl = "-";
    if (surat.tanggal) {
        const conv = new Date(surat.tanggal);
        tgl = isNaN(conv.getTime()) ? "-" : conv.toLocaleDateString("id-ID");
    }

    const content = `
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengaju</label>
                    <div class="text-gray-900">${pengaju}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                    <div class="text-gray-900">${jenis}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                        ${status === 'selesai' || status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                          status === 'diproses' || status === 'Diproses' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-orange-100 text-orange-800'}">
                        ${status}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                    <div class="text-gray-900">${tgl}</div>
                </div>

            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <button onclick="closeDetailModal()" 
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300">
                        Tutup
                    </button>

                    ${status.toLowerCase() === 'selesai' ? `
                    <button onclick="downloadSurat(${surat.id})" 
                        class="btn-gradient px-4 py-2 rounded-xl text-white transition-all duration-300">
                        Download Surat
                    </button>` : ``}

                </div>
            </div>
        </div>
    `;
    
    document.getElementById('detailContent').innerHTML = content;
    showDetailModal();
}

// Helper: build surat object from the clicked button's table row and show detail
function viewDetailFromButton(btn) {
    try {
        const tr = btn.closest('tr');
        if (!tr) return;

        const surat = {
            pengaju: tr.dataset.pengaju ?? '-',
            jenis: tr.dataset.jenis ?? '-',
            status: tr.dataset.status ?? '-',
            tanggal: tr.dataset.tanggal ?? null,
            // try to extract id_surat from status badge id attribute (status-<id>)
            id: (() => {
                const badge = tr.querySelector('[id^="status-"]');
                if (!badge) return null;
                const parts = badge.id.split('-');
                return parts.length > 1 ? parts[1] : null;
            })()
        };

        viewDetail(surat);
    } catch (e) {
        console.error('viewDetailFromButton error', e);
    }
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
    const csrf = '{{ csrf_token() }}';
    const checkboxes = Array.from(document.querySelectorAll('.rowCheckbox'));
    const selected = checkboxes.filter(cb => cb.checked).map(cb => cb.dataset.id);

    // Determine export mode:
    // - if user selected rows: export those (require >=5)
    // - if no selection: export ALL records across pages (require total >=5)
    const totalCountEl = document.getElementById('totalCount');
    const totalCount = totalCountEl ? parseInt(totalCountEl.textContent || '0', 10) : 0;

    let payload = {};

    if (selected.length > 0) {
        if (selected.length < 5) {
            showNotification('Pilih minimal 5 surat untuk diekspor.', 'error');
            return;
        }
        payload.ids = selected;
    } else {
        if (totalCount < 5) {
            showNotification('Terdapat kurang dari 5 surat total. Pilih minimal 5 surat atau tambahkan data.', 'error');
            return;
        }
        // Export all records across pages
        payload.export_all = true;
    }

    // POST to server and download CSV
    fetch('{{ route('admin.history-surat.export') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'text/csv'
        },
        body: JSON.stringify(payload)
    }).then(async res => {
        if (!res.ok) {
            const txt = await res.text();
            showNotification('Gagal mengekspor: ' + txt, 'error');
            return;
        }
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        const filename = `history_surat_export_${new Date().toISOString().slice(0,10)}.csv`;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
        showNotification('Export berhasil, file mulai diunduh.', 'success');
    }).catch(err => {
        console.error(err);
        showNotification('Terjadi kesalahan saat mengekspor.', 'error');
    });
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

// Select all checkbox handling
const selectAllEl = document.getElementById('selectAll');
if (selectAllEl) {
    selectAllEl.addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
        filterTable(); // update visible count
    });
    // keep header selectAll in sync when individual checkboxes change
    document.querySelectorAll('.rowCheckbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const all = Array.from(document.querySelectorAll('.rowCheckbox'));
            selectAllEl.checked = all.length && all.every(x => x.checked);
            filterTable();
        });
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.2.0/dist/web/pusher.min.js"></script>
<script type="module">
import Echo from "laravel-echo";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ config('reverb.app_key') }}',
    wsHost: '{{ config('reverb.host') }}',
    wsPort: '{{ config('reverb.port', 8080) }}',
    wssPort: '{{ config('reverb.port', 8080) }}',
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

// 🔥 Dengarkan event dari channel "surat.status"
window.Echo.channel('surat.status')
    .listen('.SuratStatusUpdated', (data) => {
        const statusEl = document.getElementById(`status-${data.id_surat}`);
        if (statusEl) {
            statusEl.textContent = data.status_berkas.charAt(0).toUpperCase() + data.status_berkas.slice(1);

            // Update warna badge sesuai status baru
            statusEl.className = 'inline-flex px-3 py-1 text-xs font-medium rounded-full ' +
                (data.status_berkas === 'approve' ? 'bg-yellow-100 text-yellow-800' :
                 data.status_berkas === 'selesai' ? 'bg-green-100 text-green-800' :
                 data.status_berkas === 'decline' ? 'bg-red-100 text-red-800' :
                 'bg-gray-100 text-gray-800');
        }
    });
</script>

@endsection
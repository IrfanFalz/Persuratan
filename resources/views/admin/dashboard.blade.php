@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .gradient-subtle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        transition: transform 0.3s ease;
    }
    
    .card-hover:hover .stats-icon {
        transform: scale(1.1);
    }
    
    .table-row-hover:hover {
        background-color: rgb(249 250 251);
        transform: translateX(2px);
        transition: all 0.2s ease;
    }
    
    .badge-blue {
        background-color: rgb(219 234 254);
        color: rgb(29 78 216);
    }
    
    .badge-purple {
        background-color: rgb(237 233 254);
        color: rgb(109 40 217);
    }
    
    .btn-soft {
        background-color: rgb(249 250 251);
        color: rgb(55 65 81);
        border: 1px solid rgb(229 231 235);
        transition: all 0.3s ease;
    }
    
    .btn-soft:hover {
        background-color: rgb(243 244 246);
        border-color: rgb(209 213 219);
        transform: translateY(-1px);
    }
    
    .chart-container {
        position: relative;
        height: 280px;
    }
    
    .divider-subtle {
        border-color: rgb(243 244 246);
    }
    
    .text-muted {
        color: rgb(107 114 128);
    }
    
    .shadow-subtle {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    
    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    
    @media (max-width: 768px) {
        .mobile-stack {
            flex-direction: column;
            gap: 1rem;
        }
        
        .mobile-full {
            width: 100%;
        }
    }
</style>

<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="shadow-soft rounded-3xl p-8 gradient-subtle text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative flex items-center justify-between mobile-stack">
            <div class="mobile-full">
                <h1 class="text-3xl font-bold mb-3 leading-tight">
                    Selamat Datang, {{ session('name', 'Administrator') }}!
                </h1>
                <p class="text-blue-100 text-lg leading-relaxed">
                    Kelola sistem surat dan data guru dengan mudah dari dashboard ini.
                </p>
            </div>
            <div class="hidden md:flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl backdrop-blur-sm">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="card-hover shadow-subtle rounded-2xl p-6 bg-white border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-muted mb-2">Total Surat Diajukan</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_surat'] }}</p>
                    <p class="text-xs text-muted">Seluruh pengajuan</p>
                </div>
                <div class="stats-icon p-3 bg-blue-50 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-hover shadow-subtle rounded-2xl p-6 bg-white border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-muted mb-2">Surat Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['surat_selesai'] }}</p>
                    <p class="text-xs text-muted">Telah diproses</p>
                </div>
                <div class="stats-icon p-3 bg-green-50 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-hover shadow-subtle rounded-2xl p-6 bg-white border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-muted mb-2">Jumlah Guru</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['jumlah_guru'] }}</p>
                    <p class="text-xs text-muted">Tenaga pengajar</p>
                </div>
                <div class="stats-icon p-3 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-hover shadow-subtle rounded-2xl p-6 bg-white border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-muted mb-2">Jumlah TU</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['jumlah_tu'] }}</p>
                    <p class="text-xs text-muted">Tata usaha</p>
                </div>
                <div class="stats-icon p-3 bg-orange-50 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-hover shadow-subtle rounded-2xl p-6 bg-white border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-muted mb-2">Jumlah Kepsek</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['jumlah_kepsek'] }}</p>
                    <p class="text-xs text-muted">Kepala sekolah</p>
                </div>
                <div class="stats-icon p-3 bg-red-50 rounded-xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Summary Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Chart -->
        <div class="xl:col-span-2 shadow-soft rounded-2xl p-8 bg-white border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Grafik Surat per Bulan</h3>
                    <p class="text-sm text-muted">Perkembangan pengajuan surat tahun 2024</p>
                </div>
                <div class="text-sm bg-gray-50 px-3 py-1 rounded-lg text-muted">Tahun 2024</div>
            </div>
            <div class="chart-container">
                <canvas id="suratChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="space-y-6">
            <!-- Today's Summary -->
            <div class="shadow-soft rounded-2xl p-6 bg-white border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-blue-50 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Hari Ini</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-gray-700 font-medium">Surat Baru</span>
                        </div>
                        <span class="font-bold text-blue-600 bg-blue-100 px-3 py-1 rounded-full text-sm">3</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-gray-700 font-medium">Menunggu Persetujuan</span>
                        </div>
                        <span class="font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-full text-sm">5</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-gray-700 font-medium">Selesai Hari Ini</span>
                        </div>
                        <span class="font-bold text-green-600 bg-green-100 px-3 py-1 rounded-full text-sm">2</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="shadow-soft rounded-2xl p-6 bg-white border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-purple-50 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.kelola-guru') }}" 
                       class="flex items-center justify-center w-full py-3 px-4 rounded-xl text-white font-medium transition-all duration-300 shadow-sm hover:shadow-md"
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Kelola Data Guru
                    </a>
                    <a href="{{ route('admin.kelola-surat') }}" 
                       class="flex items-center justify-center w-full py-3 px-4 rounded-xl btn-soft hover:shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Kelola Template Surat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Letters Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Recent Submitted Letters -->
        <div class="shadow-soft rounded-2xl bg-white border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Diajukan Terakhir</h3>
                        <p class="text-sm text-muted">5 pengajuan terbaru</p>
                    </div>
                    <a href="{{ route('admin.history-surat') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-1 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Pengaju</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Jenis</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divider-subtle">
                        @foreach($suratTerbaru as $surat)
                        <tr class="table-row-hover">
                            <td class="py-4 px-6">
                                <div>
                                    <div class="font-semibold text-gray-900 mb-1">{{ $surat['pengaju'] }}</div>
                                    <div class="text-sm text-muted">NIP: {{ $surat['nip'] }}</div>
                                    <div class="text-sm text-muted">{{ $surat['telp'] }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $surat['jenis'] == 'Surat Dispensasi' ? 'badge-blue' : 'badge-purple' }}">
                                    {{ $surat['jenis'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($surat['tanggal'])->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Completed Letters -->
        <div class="shadow-soft rounded-2xl bg-white border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Selesai Terakhir</h3>
                        <p class="text-sm text-muted">5 yang telah diproses</p>
                    </div>
                    <a href="{{ route('admin.history-surat') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-1 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Pengaju</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Jenis</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divider-subtle">
                        @foreach($suratSelesai as $surat)
                        <tr class="table-row-hover">
                            <td class="py-4 px-6">
                                <div>
                                    <div class="font-semibold text-gray-900 mb-1">{{ $surat['pengaju'] }}</div>
                                    <div class="text-sm text-muted">NIP: {{ $surat['nip'] }}</div>
                                    <div class="text-sm text-muted">{{ $surat['telp'] }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $surat['jenis'] == 'Surat Dispensasi' ? 'badge-blue' : 'badge-purple' }}">
                                    {{ $surat['jenis'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($surat['tanggal'])->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('suratChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Surat Diajukan',
                data: {!! json_encode($chartData['diajukan']) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }, {
                label: 'Surat Selesai',
                data: {!! json_encode($chartData['selesai']) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'start',
                    labels: {
                        usePointStyle: true,
                        padding: 25,
                        font: {
                            size: 13,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(55, 65, 81, 0.3)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        padding: 8
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        padding: 8
                    }
                }
            },
            elements: {
                line: {
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round'
                }
            }
        }
    });
});
</script>
@endsection
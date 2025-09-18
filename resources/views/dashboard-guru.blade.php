@php
    $roles = [
        'KEPSEK' => 'Kepala Sekolah',
        'TU' => 'Tata Usaha',
        'GURU' => 'Guru',
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Persuratan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
        }
        nav {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .welcome-card {
            background: linear-gradient(to right, #3b82f6, #6366f1);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }
        @media (min-width: 640px) {
            .card {
                padding: 1.5rem;
            }
        }
        .letter-card {
            background: linear-gradient(to bottom right, #eff6ff, #dbeafe);
            padding: 1rem;
            border-radius: 0.5rem;
            border: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }
        @media (min-width: 640px) {
            .letter-card {
                padding: 1.5rem;
            }
        }
        .letter-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 600px;
        }
        th {
            background: #f3f4f6;
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            white-space: nowrap;
        }
        @media (min-width: 640px) {
            th {
                padding: 0.75rem 1.5rem;
            }
        }
        td {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        @media (min-width: 640px) {
            td {
                padding: 1rem 1.5rem;
            }
        }
        .status-approved {
            background: #dcfce7;
            color: #15803d;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        .status-pending {
            background: #fef9c3;
            color: #a16207;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        .progress-dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            flex-shrink: 0;
        }
        @media (max-width: 639px) {
            .progress-dot {
                width: 0.5rem;
                height: 0.5rem;
            }
        }
        .progress-line {
            width: 1rem;
            height: 0.125rem;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .progress-line {
                width: 2rem;
            }
        }
        .notification {
            border-left-width: 4px;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .mobile-card {
            display: block;
        }
        @media (min-width: 768px) {
            .mobile-card {
                display: none;
            }
            .desktop-table {
                display: block;
            }
        }
        .desktop-table {
            display: none;
        }
        @media (min-width: 768px) {
            .desktop-table {
                display: block;
            }
            .mobile-card {
                display: none;
            }
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .action-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        .modal-content {
            background: white;
            margin: 1rem;
            padding: 0;
            border-radius: 1rem;
            max-width: 90%;
            max-height: 90%;
            width: 600px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transform: scale(0.95);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center min-w-0 flex-1">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/logo grf.png') }}"
                            alt="Logo SMK Negeri 4 Malang" 
                            class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8 object-contain">
                    </div>
                    <div class="ml-2 sm:ml-3 min-w-0">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Sistem Persuratan</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Dashboard Guru</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-800">{{ session('name') }}</p>
                        <p class="text-xs text-gray-600">{{ $roles[session('role')] }}</p>
                    </div>
                     <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 lg:px-4 py-1.5 lg:py-2 rounded-lg transition duration-200 text-xs sm:text-sm lg:text-base flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i><span class="hidden sm:inline">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-4 sm:py-8 px-3 sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang, {{ session('name') }}!</h2>
            <p class="opacity-90 text-sm sm:text-base">Kelola permintaan surat Anda dengan mudah dan efisien.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Buat Surat Baru -->
            <div class="lg:col-span-2 card">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                    <i class="fas fa-plus-circle text-blue-600 mr-2 sm:mr-3"></i>Buat Surat Baru
                </h3>
                
                <div class="space-y-4">
                    @foreach($letter_types as $key => $type)
                        <a href="{{ route('form.surat') }}?type={{ $key }}" 
                        class="group block w-full bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 
                                border-2 border-transparent hover:border-blue-200 rounded-xl p-4 sm:p-6 
                                transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Icon yang lebih besar -->
                                    <div class="bg-blue-500 text-white p-3 rounded-lg group-hover:bg-blue-600 transition-colors">
                                        <i class="fas {{ $key == 'surat-perintah-tugas' ? 'fa-briefcase' : 'fa-hand-paper' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition duration-200 text-base sm:text-lg">
                                            {{ $type }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">Buat permintaan {{ strtolower($type) }} dengan mudah</p>
                                        
                                    </div>
                                </div>
                                <div class="text-blue-500 group-hover:text-blue-600 transition-all duration-200">
                                    <i class="fas fa-chevron-right text-xl group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    
                    
                </div>
            </div>

            <!-- Status Surat -->
            <div class="lg:col-span-1 card">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2 sm:mr-3"></i>Statistik
                </h3>
                
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-check-circle text-green-600 mr-2 sm:mr-3"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Disetujui</span>
                        </div>
                        <span class="bg-green-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-bold">1</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-yellow-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-clock text-yellow-600 mr-2 sm:mr-3"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Pending</span>
                        </div>
                        <span class="bg-yellow-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-bold">1</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-file-alt text-blue-600 mr-2 sm:mr-3"></i>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">Total Surat</span>
                        </div>
                        <span class="bg-blue-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-bold">2</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Surat -->
        <div class="mt-4 sm:mt-8 card">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2 sm:mr-3"></i>Riwayat Permintaan Surat
                </h3>
            </div>
            
            <!-- Mobile Cards View -->
            <div class="mobile-card p-4 space-y-4">
                @foreach($letter_requests as $request) 
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $request['type'] }}</h4>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ date('d/m/Y', strtotime($request['date'])) }}
                                </p>
                            </div>
                            @if($request['status'] === 'approved')
                                <span class="status-approved">
                                    <i class="fas fa-check mr-1"></i>Disetujui
                                </span>
                            @elseif($request['status'] === 'pending')
                                <span class="status-pending">
                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Ditolak
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <div class="progress-dot bg-green-500"></div>
                                <div class="progress-line bg-{{ $request['status'] === 'approved' ? 'green' : 'gray' }}-300"></div>
                                <div class="progress-dot bg-{{ $request['status'] === 'approved' ? 'green' : 'gray' }}-{{ $request['status'] === 'approved' ? '500' : '300' }}"></div>
                                <div class="progress-line bg-{{ $request['processed_by_tu'] ? 'green' : 'gray' }}-300"></div>
                                <div class="progress-dot bg-{{ $request['processed_by_tu'] ? 'green' : 'gray' }}-{{ $request['processed_by_tu'] ? '500' : '300' }}"></div>
                            </div>
                            <div class="action-buttons">
                                <button class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200" onclick="viewLetter({{ $request['id'] }})">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </button>
                                @if($request['processed_by_tu'])
                                    <button class="action-btn bg-green-100 text-green-600 hover:bg-green-200">
                                        <i class="fas fa-download mr-1"></i>Cetak
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="desktop-table table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Jenis Surat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($letter_requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td>
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">{{ $request['type'] }}</span>
                                    </div>
                                </td>
                                <td class="text-sm text-gray-500">
                                    {{ date('d/m/Y', strtotime($request['date'])) }}
                                </td>
                                <td>
                                    @if($request['status'] === 'approved')
                                        <span class="status-approved">
                                            <i class="fas fa-check mr-1"></i>Disetujui
                                        </span>
                                    @elseif($request['status'] === 'pending')
                                        <span class="status-pending">
                                            <i class="fas fa-clock mr-1"></i>Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center space-x-1">
                                        <div class="progress-dot bg-green-500"></div>
                                        <div class="progress-line bg-{{ $request['status'] === 'approved' ? 'green' : 'gray' }}-300"></div>
                                        <div class="progress-dot bg-{{ $request['status'] === 'approved' ? 'green' : 'gray' }}-{{ $request['status'] === 'approved' ? '500' : '300' }}"></div>
                                        <div class="progress-line bg-{{ $request['processed_by_tu'] ? 'green' : 'gray' }}-300"></div>
                                        <div class="progress-dot bg-{{ $request['processed_by_tu'] ? 'green' : 'gray' }}-{{ $request['processed_by_tu'] ? '500' : '300' }}"></div>
                                    </div>
                                </td>
                                <td class="text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewLetter({{ $request['id'] }})">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </button>
                                    @if($request['processed_by_tu'])
                                        <button class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-download mr-1"></i>Cetak
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="mt-4 sm:mt-8 card">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                <i class="fas fa-bell text-orange-600 mr-2 sm:mr-3"></i>Notifikasi Terbaru
            </h3>
            
            <div class="space-y-3 sm:space-y-4">
                <div class="notification bg-green-50 border-green-500">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-800 text-sm sm:text-base">Surat Tugas Anda telah disetujui Kepala Sekolah</p>
                            <p class="text-xs sm:text-sm text-gray-600">25 Agustus 2025, 14:30</p>
                        </div>
                    </div>
                </div>
                
                <div class="notification bg-blue-50 border-blue-500">
                    <div class="flex items-start">
                        <i class="fas fa-file-alt text-blue-500 mr-3 mt-1 flex-shrink-0"></i>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-800 text-sm sm:text-base">Surat Tugas telah selesai diproses TU</p>
                            <p class="text-xs sm:text-sm text-gray-600">Silakan ambil di ruang TU. 26 Agustus 2025, 09:15</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Surat -->
    <div id="letterModal" class="modal">
        <div class="modal-content">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-t-lg">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Detail Surat</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Data Pemohon -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Data Pemohon
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Nama Lengkap:</span>
                            <p class="font-medium text-gray-800" id="modalPemohonNama">-</p>
                        </div>
                        <div>
                            <span class="text-gray-600">NIP:</span>
                            <p class="font-medium text-gray-800" id="modalPemohonNip">-</p>
                        </div>
                         <div>
                            <span class="text-gray-600">No. Telepon:</span>
                            <p class="font-medium text-gray-800" id="modalPemohonTelp">-</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Jenis Surat:</span>
                            <p class="font-medium text-gray-800" id="modalJenisSurat">-</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Surat -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-file-alt text-green-600 mr-2"></i>Detail Surat
                    </h4>
                    
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Keperluan:</label>
                            <p class="text-gray-800 mt-1" id="modalKeperluan">-</p>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Hari:</label>
                                <p class="text-gray-800 mt-1" id="modalHari">-</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tanggal:</label>
                                <p class="text-gray-800 mt-1" id="modalTanggal">-</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Jam:</label>
                                <p class="text-gray-800 mt-1" id="modalJam">-</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tempat:</label>
                                <p class="text-gray-800 mt-1" id="modalTempat">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Guru -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-users text-purple-600 mr-2"></i>Data Guru
                    </h4>
                    <div id="modalGuruData" class="space-y-3">
                        <!-- Data guru akan ditampilkan di sini -->
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>Status Surat
                    </h4>
                    <div class="flex items-center justify-between">
                        <span id="modalStatus" class="px-3 py-1 rounded-full text-sm font-medium">-</span>
                        <span class="text-sm text-gray-600" id="modalTanggalPengajuan">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const letterData = @json($letter_requests);

        function viewLetter(id) {
            const request = letterData.find(item => item.id === id);
            if (!request) return;

            // Data Pemohon
            document.getElementById('modalPemohonNama').textContent = request.pemohon.nama;
            document.getElementById('modalPemohonNip').textContent = request.pemohon.nip;
            document.getElementById('modalPemohonTelp').textContent = request.pemohon.telp;
            document.getElementById('modalJenisSurat').textContent = request.type;

            // Detail Surat
            document.getElementById('modalKeperluan').textContent = request.keperluan;
            document.getElementById('modalHari').textContent = request.hari;
            document.getElementById('modalTanggal').textContent = formatDate(request.tanggal);
            document.getElementById('modalJam').textContent = request.jam;
            document.getElementById('modalTempat').textContent = request.tempat;

            // Data Guru
            const guruContainer = document.getElementById('modalGuruData');
            guruContainer.innerHTML = '';
            
            request.guru_data.forEach((guru, index) => {
                const guruDiv = document.createElement('div');
                guruDiv.className = 'bg-white rounded-lg p-4 border border-gray-200';
                guruDiv.innerHTML = `
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Nama:</span>
                            <p class="font-medium text-gray-800">${guru.nama}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">NIP:</span>
                            <p class="font-medium text-gray-800">${guru.nip}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Keterangan:</span>
                            <p class="font-medium text-gray-800">${guru.keterangan}</p>
                        </div>
                    </div>
                `;
                guruContainer.appendChild(guruDiv);
            });

            // Status
            const statusElement = document.getElementById('modalStatus');
            const tanggalElement = document.getElementById('modalTanggalPengajuan');
            
            if (request.status === 'approved') {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                statusElement.innerHTML = '<i class="fas fa-check mr-1"></i>Disetujui';
            } else if (request.status === 'pending') {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
                statusElement.innerHTML = '<i class="fas fa-clock mr-1"></i>Menunggu Persetujuan';
            } else {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                statusElement.innerHTML = '<i class="fas fa-times mr-1"></i>Ditolak';
            }
            
            tanggalElement.textContent = 'Diajukan: ' + formatDate(request.date);

            // Show modal
            const modal = document.getElementById('letterModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('letterModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return date.toLocaleDateString('id-ID', options);
        }

        // Close modal when clicking outside
        document.getElementById('letterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
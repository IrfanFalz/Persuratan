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
    <title>Dashboard Kepala Sekolah - Sistem Persuratan</title>
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
            background: linear-gradient(to right, #7c3aed, #6366f1);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }
        .icon-bg {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pending-card, .activity-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 50;
        }
        .modal-content {
            background: white;
            border-radius: 0.5rem;
            max-width: 28rem;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
            margin: auto;
        }
        
        .detail-modal-content {
            background: white;
            border-radius: 0.75rem;
            max-width: 48rem;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
            margin: auto;
        }
        
        /* Mobile optimizations */
        @media (max-width: 640px) {
            .welcome-card {
                padding: 1rem;
                margin-bottom: 1.5rem;
            }
            .stat-card {
                padding: 1rem;
            }
            .pending-card, .activity-card {
                padding: 1rem;
                margin-bottom: 1.5rem;
            }
            .modal-content {
                padding: 1rem;
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            .detail-modal-content {
                padding: 1.5rem;
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            .icon-bg {
                width: 2.5rem;
                height: 2.5rem;
            }
        }
        
        /* Tablet optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            .welcome-card {
                padding: 1.25rem;
            }
        }
        
        /* Better text scaling */
        @media (max-width: 480px) {
            .welcome-card h2 {
                font-size: 1.5rem;
                line-height: 1.3;
            }
            .stat-card .text-3xl {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center min-w-0 flex-1">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/logo grf.png') }}" 
                            alt="Logo SMK Negeri 4 Malang" 
                            class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8 object-contain">
                    </div>
                    <div class="ml-3 min-w-0">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Sistem Persuratan</h1>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Dashboard Kepala Sekolah</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-lg sm:text-xl cursor-pointer"></i>
                        @if(count($pending_approval) > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ count($pending_approval) }}</span>
                        @endif
                    </div>
                    
                    <!-- User Info - Hidden on mobile -->
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-medium text-gray-800">{{ session('name') }}</p>
                        <p class="text-xs text-gray-600">{{ $roles[session('role')] }}</p>
                    </div>
                    
                    <!-- Logout Button -->
                     <a href="{{ route('login') }}" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 lg:px-4 py-1.5 lg:py-2 rounded-lg transition duration-200 text-xs sm:text-sm lg:text-base flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i><span class="hidden sm:inline">Keluar</span>
                    </a>    
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
       @if(isset($message))
            @if(session('status') === 'error')
                <!-- Message untuk penolakan -->
                <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 text-red-700 px-4 sm:px-6 py-3 sm:py-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle mr-2 sm:mr-3 text-lg sm:text-xl flex-shrink-0"></i>
                        <p class="text-sm sm:text-base">{{ $message }}</p>
                    </div>
                </div>
            @else
                <!-- Message untuk persetujuan (default) -->
                <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 sm:mr-3 text-lg sm:text-xl flex-shrink-0"></i>
                        <p class="text-sm sm:text-base">{{ $message }}</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang, {{ session('name') }}!</h2>
            <p class="opacity-90 text-sm sm:text-base">Dashboard eksekutif untuk monitoring dan persetujuan surat-surat penting.</p>
        </div>

         <!-- Statistics Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Surat</p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $monthly_stats['total_letters'] }}</p>
                        <p class="text-xs text-gray-500">Bulan ini</p>
                    </div>
                    <div class="icon-bg bg-blue-100 ml-2">
                        <i class="fas fa-file-alt text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Perlu Persetujuan</p>
                        <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ $monthly_stats['pending'] }}</p>
                        <p class="text-xs text-gray-500">Menunggu</p>
                    </div>
                    <div class="icon-bg bg-orange-100 ml-2">
                        <i class="fas fa-clock text-orange-600 text-lg sm:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Disetujui Kepsek</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $monthly_stats['approved_by_kepsek'] }}</p>
                        <p class="text-xs text-gray-500">Bulan ini</p>
                    </div>
                    <div class="icon-bg bg-green-100 ml-2">
                        <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Ditolak</p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ $monthly_stats['rejected'] }}</p>
                        <p class="text-xs text-gray-500">Bulan ini</p>
                    </div>
                    <div class="icon-bg bg-red-100 ml-2">
                        <i class="fas fa-close text-red-600 text-lg sm:text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
            <!-- Pending Approvals -->
            <div class="xl:col-span-2">
                <div class="pending-card">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-clipboard-list text-orange-600 mr-2 sm:mr-3 flex-shrink-0"></i>
                        <span class="truncate">Permohonan Menunggu Persetujuan</span>
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($pending_approval as $request)
                            <div class="p-4 sm:p-6 bg-gray-50 rounded-lg border border-gray-200 hover:border-orange-300 transition duration-200 cursor-pointer"
                                 onclick="openDetailModal({{ json_encode($request) }})">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between space-y-3 sm:space-y-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-white shadow-sm">
                                                <img src="{{ asset('images/pp.png') }}" 
                                                    alt="Avatar {{ $request['teacher'] }}" 
                                                    class="w-full h-full object-cover"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <!-- Fallback jika gambar tidak ditemukan -->
                                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-content text-white font-bold" style="display: none;">
                                                    {{ strtoupper(substr($request['teacher'], 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-semibold text-gray-800 truncate">{{ $request['teacher'] }}</h4>
                                                <p class="text-sm text-gray-600 truncate">NIP: {{ $request['nip'] }}</p>
                                                
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="font-medium text-gray-800">{{ $request['type'] }}</p>
                                            <p class="text-sm text-gray-600">Alasan: {{ $request['reason'] }}</p>
                                            <p class="text-sm text-gray-600">Durasi: {{ $request['duration'] }}</p>
                                            <p class="text-xs text-gray-500">Diajukan: {{ date('d/m/Y', strtotime($request['date_requested'])) }}</p>
                                        </div>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                <i class="fas fa-eye mr-1"></i>
                                                Klik untuk lihat detail
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-row sm:flex-col space-x-2 sm:space-x-0 sm:space-y-2 sm:ml-4" onclick="event.stopPropagation();">
                                        <button onclick="openApprovalModal({{ $request['id'] }}, '{{ $request['teacher'] }}', '{{ $request['type'] }}')" 
                                                class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center text-sm">
                                            <i class="fas fa-check mr-1"></i>
                                            <span class="hidden sm:inline">Setujui</span>
                                        </button>
                                        <button onclick="openRejectionModal({{ $request['id'] }}, '{{ $request['teacher'] }}', '{{ $request['type'] }}')" 
                                                class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center text-sm">
                                            <i class="fas fa-times mr-1"></i>
                                            <span class="hidden sm:inline">Tolak</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="activity-card">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2 sm:mr-3 flex-shrink-0"></i>
                        <span class="truncate">Aktivitas Terbaru</span>
                    </h3>
                    
                    <div class="space-y-4">
                         @foreach($recent_activities as $activity)
                            <div class="flex items-center space-x-3 p-3 sm:p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-green-600 text-sm sm:text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-800 text-sm sm:text-base truncate">Disetujui: {{ $activity['type'] }} - {{ $activity['teacher'] }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ date('d/m/Y H:i', strtotime($activity['date'])) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <!-- Replace the Quick Stats section with this chart section -->
            <div class="xl:col-span-1">
                <div class="stat-card">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2 sm:mr-3 flex-shrink-0"></i>
                        <span class="truncate">Statistik Bulanan</span>
                    </h3>
                    
                    <!-- Chart Container -->
                    <div class="relative">
                        <canvas id="monthlyStatsChart" width="300" height="300" class="max-w-full"></canvas>
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-gray-700">Disetujui</span>
                            </div>
                            <span class="font-semibold text-green-600">{{ $monthly_stats['approved_by_kepsek'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-gray-700">Pending</span>
                            </div>
                            <span class="font-semibold text-yellow-600">{{ $monthly_stats['pending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-gray-700">Ditolak</span>
                            </div>
                            <span class="font-semibold text-red-600">{{ $monthly_stats['rejected'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-gray-700">Total</span>
                            </div>
                            <span class="font-semibold text-blue-600">{{ $monthly_stats['total_letters'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="detail-modal-content">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Detail Surat Permohonan</h3>
                    <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600 flex-shrink-0 ml-2">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <!-- Data Pemohon -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Data Pemohon
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="font-medium text-gray-600">Nama Lengkap</p>
                                <p id="detail_nama_lengkap" class="text-gray-800 font-medium"></p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-600">NIP</p>
                                <p id="detail_nip" class="text-gray-800"></p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-600">No. Telepon</p>
                                <p id="detail_no_telp" class="text-gray-800"></p>
                            </div> 
                        </div>
                    </div>
                    
                    <!-- Keperluan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Keperluan
                        </h4>
                        <p id="detail_keperluan" class="text-gray-700 text-sm leading-relaxed"></p>
                    </div>
                    
                    <!-- Detail Waktu dan Tempat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Waktu Pelaksanaan
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <p class="font-medium text-gray-600">Hari</p>
                                    <p id="detail_hari" class="text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-600">Tanggal</p>
                                    <p id="detail_tanggal" class="text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-600">Jam</p>
                                    <p id="detail_jam" class="text-gray-800"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Tempat
                            </h4>
                            <p id="detail_tempat" class="text-gray-800 text-sm"></p>
                        </div>
                    </div>
                    
                    <!-- Data Guru Terkait -->
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-orange-800 mb-4 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Guru Terkait
                        </h4>
                        <div id="detail_guru_data" class="space-y-3">
                            <!-- Akan diisi oleh JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('detailModal')" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Tutup
                        </button>
                        <button onclick="approveFromDetail()" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Setujui
                        </button>
                        <button onclick="rejectFromDetail()" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="modal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 truncate">Konfirmasi Persetujuan Kepala Sekolah</h3>
                    <button onclick="closeModal('approvalModal')" class="text-gray-400 hover:text-gray-600 flex-shrink-0 ml-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-center text-gray-700 text-sm sm:text-base">
                        Apakah Anda yakin ingin menyetujui <span id="approvalRequestType" class="font-semibold"></span> 
                        dari <span id="approvalTeacherName" class="font-semibold"></span>?
                    </p>
                    <p class="text-center text-xs sm:text-sm text-gray-500 mt-2">
                        Persetujuan ini akan langsung meneruskan surat ke TU untuk diproses.
                    </p>
                </div>
                
                <form method="POST" action="{{ route('dashboard.kepsek.approval') }}">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <input type="hidden" name="request_id" id="approvalRequestId">
                    <input type="hidden" name="teacher" id="approvalTeacher">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Kepala Sekolah (Opsional)</label>
                        <textarea name="kepsek_note" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                  placeholder="Tambahkan catatan khusus..."></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeModal('approvalModal')" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="modal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 truncate">Konfirmasi Penolakan</h3>
                    <button onclick="closeModal('rejectionModal')" class="text-gray-400 hover:text-gray-600 flex-shrink-0 ml-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-times text-red-600 text-2xl"></i>
                    </div>
                    <p class="text-center text-gray-700 text-sm sm:text-base">
                        Apakah Anda yakin ingin menolak <span id="rejectionRequestType" class="font-semibold"></span> 
                        dari <span id="rejectionTeacherName" class="font-semibold"></span>?
                    </p>
                </div>
                
                <form method="POST" action="{{ route('dashboard.kepsek.approval') }}">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="request_id" id="rejectionRequestId">
                    <input type="hidden" name="teacher" id="rejectionTeacher">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="kepsek_rejection_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                  placeholder="Jelaskan alasan penolakan secara detail..."></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeModal('rejectionModal')" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        let currentRequestData = null;

        

        function openDetailModal(requestData) {
            currentRequestData = requestData;
            
            // Populate data pemohon
            document.getElementById('detail_nama_lengkap').textContent = requestData.nama_lengkap;
            document.getElementById('detail_nip').textContent = requestData.nip;
            document.getElementById('detail_no_telp').textContent = requestData.no_telp;
            
            // Populate keperluan
            document.getElementById('detail_keperluan').textContent = requestData.keperluan;
            
            // Populate waktu dan tempat
            document.getElementById('detail_hari').textContent = requestData.hari;
            document.getElementById('detail_tanggal').textContent = formatDate(requestData.tanggal);
            document.getElementById('detail_jam').textContent = requestData.jam;
            document.getElementById('detail_tempat').textContent = requestData.tempat;
            
            // Populate data guru
            const guruContainer = document.getElementById('detail_guru_data');
            guruContainer.innerHTML = '';
            
            requestData.guru_data.forEach((guru, index) => {
                const guruDiv = document.createElement('div');
                guruDiv.className = 'p-3 bg-white rounded-lg border border-orange-200';
                guruDiv.innerHTML = `
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm">
                        <div>
                            <p class="font-medium text-gray-600">Nama</p>
                            <p class="text-gray-800 font-medium">${guru.nama}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600">NIP</p>
                            <p class="text-gray-800">${guru.nip}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-600">Keterangan</p>
                            <p class="text-gray-800">${guru.keterangan}</p>
                        </div>
                    </div>
                `;
                guruContainer.appendChild(guruDiv);
            });
            
            document.getElementById('detailModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function approveFromDetail() {
            if (currentRequestData) {
                closeModal('detailModal');
                openApprovalModal(currentRequestData.id, currentRequestData.teacher, currentRequestData.type);
            }
        }

        function rejectFromDetail() {
            if (currentRequestData) {
                closeModal('detailModal');
                openRejectionModal(currentRequestData.id, currentRequestData.teacher, currentRequestData.type);
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        function openApprovalModal(requestId, teacherName, requestType) {
            document.getElementById('approvalRequestId').value = requestId;
            document.getElementById('approvalTeacherName').textContent = teacherName;
            document.getElementById('approvalRequestType').textContent = requestType;
            document.getElementById('approvalModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openRejectionModal(requestId, teacherName, requestType) {
            document.getElementById('rejectionRequestId').value = requestId;
            document.getElementById('rejectionTeacherName').textContent = teacherName;
            document.getElementById('rejectionRequestType').textContent = requestType;
            document.getElementById('rejectionModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('detailModal')) {
                closeModal('detailModal');
            }
            if (e.target === document.getElementById('approvalModal')) {
                closeModal('approvalModal');
            }
            if (e.target === document.getElementById('rejectionModal')) {
                closeModal('rejectionModal');
            }
        });

        // Handle keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('detailModal');
                closeModal('approvalModal');
                closeModal('rejectionModal');
            }
        });


        // Chart initialization script (add this to your existing script section)
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
            
            // Get data from PHP variables
            const approved = {{ $monthly_stats['approved_by_kepsek'] }};
            const pending = {{ $monthly_stats['pending'] }};
            const rejected = {{ $monthly_stats['rejected'] }};
            
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Disetujui', 'Pending', 'Ditolak'],
                    datasets: [{
                        data: [approved, pending, rejected],
                        backgroundColor: [
                            '#10B981', // Green for approved
                            '#F59E0B', // Yellow for pending  
                            '#EF4444'  // Red for rejected
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Hide default legend since we have custom one
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%', // Creates donut effect
                    animation: {
                        animateRotate: true,
                        animateScale: false
                    }
                }
            });
            
            // Resize chart on window resize
            window.addEventListener('resize', function() {
                chart.resize();
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KTU - Sistem Persuratan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom scrollbar untuk semua device */
        .custom-scroll::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animasi untuk modal */
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }
        
        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Custom break untuk text panjang */
        .break-word {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        /* Responsive font sizes */
        .text-responsive-xs { font-size: clamp(0.6rem, 2vw, 0.75rem); }
        .text-responsive-sm { font-size: clamp(0.75rem, 2.5vw, 0.875rem); }
        .text-responsive-base { font-size: clamp(0.875rem, 3vw, 1rem); }
        .text-responsive-lg { font-size: clamp(1rem, 3.5vw, 1.125rem); }
        .text-responsive-xl { font-size: clamp(1.125rem, 4vw, 1.25rem); }
        .text-responsive-2xl { font-size: clamp(1.25rem, 5vw, 1.5rem); }
        .text-responsive-3xl { font-size: clamp(1.5rem, 6vw, 1.875rem); }

        /* Improved button touch targets for mobile */
        .btn-touch {
            min-height: 44px;
            min-width: 44px;
        }

        /* Safe area padding untuk iPhone */
        .safe-area-padding {
            padding-left: max(1rem, env(safe-area-inset-left));
            padding-right: max(1rem, env(safe-area-inset-right));
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        /* Hover effects yang disabled di touch device */
        @media (hover: hover) {
            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }
        }

        /* Layout improvements untuk landscape mobile */
        @media screen and (max-height: 500px) and (orientation: landscape) {
            .landscape-compact {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            .landscape-compact .welcome-card {
                padding: 1rem;
            }
            .landscape-compact .stats-card {
                padding: 0.75rem;
            }
        }

        /* Fixes untuk very small screens */
        @media screen and (max-width: 320px) {
            .ultra-small {
                font-size: 0.7rem;
            }
            .ultra-small-padding {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto safe-area-padding">
            <div class="flex justify-between items-center h-12 sm:h-14 md:h-16">
                <!-- Logo dan Title -->
                <div class="flex items-center min-w-0 flex-1">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/logo grf.png') }}" 
                            alt="Logo SMK Negeri 4 Malang" 
                            class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8 object-contain">
                    </div>
                    <div class="ml-2 sm:ml-3 min-w-0 flex-1">
                        <h1 class="text-responsive-lg font-bold text-gray-800 truncate">Sistem Persuratan</h1>
                        <p class="text-responsive-xs text-gray-600 hidden sm:block truncate">Dashboard KTU</p>
                    </div>
                </div>
                
                <!-- Right side nav -->
                <div class="flex items-center space-x-1 sm:space-x-2 md:space-x-4">
                    <!-- Notification -->
                    <div class="relative">
                        <button class="btn-touch flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-all">
                            <i class="fas fa-bell text-gray-600 text-base sm:text-lg md:text-xl cursor-pointer"></i>
                        </button>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium transform translate-x-1 -translate-y-1">2</span>
                    </div>
                    
                    <!-- User Info - Responsive visibility -->
                    <div class="text-right hidden md:block">
                        <p class="text-responsive-sm font-medium text-gray-800 truncate max-w-[120px]">{{ session('name') }}</p>
                        <p class="text-responsive-xs text-gray-600">{{ session('role') }}</p>
                    </div>
                    
                    <!-- Logout button -->
                     <a href="{{ route('login') }}" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 lg:px-4 py-1.5 lg:py-2 rounded-lg transition duration-200 text-xs sm:text-sm lg:text-base flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i><span class="hidden sm:inline">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-6 lg:py-8 px-2 sm:px-4 lg:px-8 landscape-compact">
        <!-- Success Message -->
        @if (!empty($message))
            <div class="mb-3 sm:mb-4 md:mb-6 bg-green-50 border border-green-200 text-green-700 px-3 sm:px-4 md:px-6 py-2 sm:py-3 md:py-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 sm:mr-3 text-base sm:text-lg md:text-xl flex-shrink-0"></i>
                    <p class="text-responsive-sm break-word">{{ $message }}</p>
                </div>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 mb-4 sm:mb-6 md:mb-8 text-white welcome-card">
            <h2 class="text-responsive-xl md:text-responsive-2xl font-bold mb-1 sm:mb-2 break-word">Selamat Datang, {{ session('name') }}!</h2>
            <p class="opacity-90 text-responsive-sm break-word">Kelola persetujuan surat dengan efisien dan tepat waktu.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6 mb-4 sm:mb-6 md:mb-8">
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg hover-lift transition-all stats-card">
                <div class="p-2 sm:p-3 md:p-4 lg:p-6 ultra-small-padding">
                    <div class="flex flex-col space-y-2 sm:space-y-1 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-responsive-xs font-medium text-gray-600 break-word">Menunggu Persetujuan</p>
                            <p class="text-responsive-2xl md:text-responsive-3xl font-bold text-orange-600">{{ count($pending_requests) }}</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 bg-orange-100 rounded-lg flex items-center justify-center self-end md:self-auto flex-shrink-0">
                            <i class="fas fa-clock text-orange-600 text-xs sm:text-sm md:text-base lg:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg md:rounded-xl shadow-lg hover-lift transition-all stats-card">
                <div class="p-2 sm:p-3 md:p-4 lg:p-6 ultra-small-padding">
                    <div class="flex flex-col space-y-2 sm:space-y-1 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-responsive-xs font-medium text-gray-600 break-word">Disetujui Hari Ini</p>
                            <p class="text-responsive-2xl md:text-responsive-3xl font-bold text-green-600">1</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center self-end md:self-auto flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-xs sm:text-sm md:text-base lg:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg md:rounded-xl shadow-lg hover-lift transition-all stats-card">
                <div class="p-2 sm:p-3 md:p-4 lg:p-6 ultra-small-padding">
                    <div class="flex flex-col space-y-2 sm:space-y-1 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-responsive-xs font-medium text-gray-600 break-word">Total Bulan Ini</p>
                            <p class="text-responsive-2xl md:text-responsive-3xl font-bold text-blue-600">15</p>
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center self-end md:self-auto flex-shrink-0">
                            <i class="fas fa-file-alt text-blue-600 text-xs sm:text-sm md:text-base lg:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg md:rounded-xl shadow-lg hover-lift transition-all stats-card">
                <div class="p-2 sm:p-3 md:p-4 lg:p-6 ultra-small-padding">
                    <div class="flex flex-col space-y-2 sm:space-y-1 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-responsive-xs font-medium text-gray-600 break-word">Jumlah surat ditolak</p>
                            <p class="text-responsive-2xl md:text-responsive-3xl font-bold text-red-600">2</p>
                            
                        </div>
                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg flex items-center justify-center self-end md:self-auto flex-shrink-0">
                            <i class="fas fa-close text-red-600 text-xs sm:text-sm md:text-base lg:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg overflow-hidden mb-4 sm:mb-6 md:mb-8 hover-lift transition-all">
            <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
                <h3 class="text-responsive-lg md:text-responsive-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-orange-600 mr-2 sm:mr-3 flex-shrink-0"></i>
                    <span class="break-word">Permohonan Menunggu Persetujuan</span>
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($pending_requests as $request)
                    <div class="p-3 sm:p-4 md:p-6 hover:bg-gray-50 transition-all">
                        <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between space-y-4 xl:space-y-0 xl:space-x-6">
                            <div class="flex-1 min-w-0">
                                <!-- Header dengan avatar dan info guru -->
                                <div class="flex items-start space-x-3 mb-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 text-responsive-xs">
                                        {{ strtoupper(substr($request['teacher'], 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-800 text-responsive-sm md:text-responsive-base break-word">{{ $request['teacher'] }}</h4>
                                        <p class="text-responsive-xs text-gray-600 break-word">
                                            NIP: {{ $request['nip'] }} • {{ $request['subject'] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Info Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <div class="bg-gray-50 p-2 sm:p-3 rounded-lg">
                                        <p class="text-responsive-xs text-gray-600 mb-1">Jenis Surat:</p>
                                        <p class="font-medium text-gray-800 text-responsive-sm flex items-center break-word">
                                            <i class="fas fa-file-alt text-blue-500 mr-2 flex-shrink-0 text-xs"></i>
                                            <span>{{ $request['type'] }}</span>
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-2 sm:p-3 rounded-lg">
                                        <p class="text-responsive-xs text-gray-600 mb-1">Waktu Pengajuan:</p>
                                        <p class="font-medium text-gray-800 text-responsive-sm flex items-start break-all">
                                            <i class="fas fa-calendar text-green-500 mr-2 flex-shrink-0 text-xs mt-0.5"></i>
                                            <span>{{ \Carbon\Carbon::parse($request['date_requested'])->format('d/m/Y H:i') }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Reason Box -->
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                                    <p class="text-responsive-xs text-blue-600 mb-1 font-medium">Alasan/Keperluan:</p>
                                    <p class="text-gray-800 text-responsive-sm break-word leading-relaxed">{{ $request['reason'] }}</p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row xl:flex-col xl:min-w-[120px] gap-2">
                                <button onclick="openDetailModal({{ json_encode($request) }})" 
                                        class="btn-touch px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center text-responsive-sm font-medium">
                                    <i class="fas fa-eye mr-1 sm:mr-2 text-xs"></i>
                                    <span>Lihat</span>
                                </button>
                                <button onclick="openApprovalModal({{ $request['id'] }}, '{{ e($request['teacher']) }}', '{{ e($request['type']) }}')" 
                                        class="btn-touch px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all flex items-center justify-center text-responsive-sm font-medium">
                                    <i class="fas fa-check mr-1 sm:mr-2 text-xs"></i>
                                    <span>Setujui</span>
                                </button>
                                <button onclick="openRejectionModal({{ $request['id'] }}, '{{ e($request['teacher']) }}', '{{ e($request['type']) }}')" 
                                        class="btn-touch px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all flex items-center justify-center text-responsive-sm font-medium">
                                    <i class="fas fa-times mr-1 sm:mr-2 text-xs"></i>
                                    <span>Tolak</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recently Approved Section -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg overflow-hidden hover-lift transition-all">
            <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
                <h3 class="text-responsive-lg md:text-responsive-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2 sm:mr-3 flex-shrink-0"></i>
                    <span class="break-word">Surat yang Telah Disetujui</span>
                </h3>
            </div>
            
            <div class="p-3 sm:p-4 md:p-6">
                 @if(empty($approved_requests))
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-2xl md:text-4xl mb-4"></i>
                        <p class="text-gray-600 text-responsive-sm">Belum ada surat yang disetujui hari ini</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($approved_requests as $request)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-responsive-sm">{{ $request['teacher'] }}</p>
                                        <p class="text-responsive-xs text-gray-600">{{ $request['type'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-responsive-xs text-green-700 font-medium">Disetujui</p>
                                    <p class="text-responsive-xs text-gray-500">{{ \Carbon\Carbon::parse($request['date_approved'])->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto custom-scroll modal-enter">
                <div class="sticky top-0 bg-white border-b p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Detail Permohonan Surat</h3>
                        <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600 btn-touch flex items-center justify-center">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <!-- Data Pemohon -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 sm:p-6 mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            Data Pemohon
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nama Lengkap:</p>
                                <p class="font-medium text-gray-800" id="detail-full-name">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">NIP:</p>
                                <p class="font-medium text-gray-800" id="detail-nip">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">No. Telepon:</p>
                                <p class="font-medium text-gray-800" id="detail-phone">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <p class="text-gray-800" id="detail-keperluan">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                                <div class="bg-gray-50 p-3 rounded-lg border">
                                    <p class="text-gray-800" id="detail-hari">-</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                <div class="bg-gray-50 p-3 rounded-lg border">
                                    <p class="text-gray-800" id="detail-tanggal">-</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                                <div class="bg-gray-50 p-3 rounded-lg border">
                                    <p class="text-gray-800" id="detail-jam">-</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat</label>
                                <div class="bg-gray-50 p-3 rounded-lg border">
                                    <p class="text-gray-800" id="detail-tempat">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Guru -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-users text-gray-600 mr-2"></i>
                                Data Guru (Nama, NIP, Keterangan)
                            </h4>
                            <div id="guru-data-container" class="space-y-3">
                                <!-- Data guru akan diisi via JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons di modal -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-3 pt-6 border-t">
                        <button onclick="closeModal('detailModal')" class="flex-1 btn-touch px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center text-sm font-medium">
                            
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-4 sm:p-6 modal-enter max-h-[90vh] overflow-y-auto custom-scroll">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-responsive-lg font-semibold text-gray-800">Konfirmasi Persetujuan</h3>
                    <button onclick="closeModal('approvalModal')" class="text-gray-400 hover:text-gray-600 btn-touch flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-lg sm:text-2xl"></i>
                    </div>
                    <p class="text-center text-gray-700 text-responsive-sm break-word">
                        Apakah Anda yakin ingin menyetujui <span id="approvalRequestType" class="font-semibold"></span> 
                        dari <span id="approvalTeacherName" class="font-semibold"></span>?
                    </p>
                </div>
                
                <form method="POST" class="space-y-4" onsubmit="return handleFormSubmit(this)" action="{{ route('dashboard.ktu.approval') }}">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <input type="hidden" name="request_id" id="approvalRequestId">
                    
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" onclick="closeModal('approvalModal')" 
                                class="flex-1 btn-touch px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-responsive-sm transition-all">
                            Batal
                        </button>
                        <button type="submit" id="approveBtn"
                                class="flex-1 btn-touch px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-responsive-sm transition-all">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-4 sm:p-6 modal-enter max-h-[90vh] overflow-y-auto custom-scroll">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-responsive-lg font-semibold text-gray-800">Konfirmasi Penolakan</h3>
                    <button onclick="closeModal('rejectionModal')" class="text-gray-400 hover:text-gray-600 btn-touch flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-red-100 rounded-full mx-auto mb-4">
                        <i class="fas fa-times text-red-600 text-lg sm:text-2xl"></i>
                    </div>
                    <p class="text-center text-gray-700 text-responsive-sm break-word">
                        Apakah Anda yakin ingin menolak <span id="rejectionRequestType" class="font-semibold"></span> 
                        dari <span id="rejectionTeacherName" class="font-semibold"></span>?
                    </p>
                </div>
                
                <form method="POST" class="space-y-4" onsubmit="return handleFormSubmit(this)" action="{{ route('dashboard.ktu.approval') }}">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="request_id" id="rejectionRequestId">
                    
                    <div>
                        <label class="block text-responsive-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="3" required
                                  class="w-full px-3 py-2 text-responsive-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" onclick="closeModal('rejectionModal')" 
                                class="flex-1 btn-touch px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-responsive-sm transition-all">
                            Batal
                        </button>
                        <button type="submit" id="rejectBtn"
                                class="flex-1 btn-touch px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-responsive-sm transition-all">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Global variable to store current request data
        let currentRequestData = null;

        // Fungsi untuk membuka modal detail
        function openDetailModal(requestData) {
            currentRequestData = requestData;
            
            // Populate data pemohon
            document.getElementById('detail-full-name').textContent = requestData.full_name || '-';
            document.getElementById('detail-nip').textContent = requestData.nip || '-';
            document.getElementById('detail-phone').textContent = requestData.phone || '-';
            
            // Populate detail surat
            document.getElementById('detail-keperluan').textContent = requestData.keperluan || '-';
            document.getElementById('detail-hari').textContent = requestData.hari || '-';
            document.getElementById('detail-tanggal').textContent = formatDate(requestData.tanggal) || '-';
            document.getElementById('detail-jam').textContent = requestData.jam || '-';
            document.getElementById('detail-tempat').textContent = requestData.tempat || '-';
            
            // Populate data guru
            const guruContainer = document.getElementById('guru-data-container');
            guruContainer.innerHTML = '';
            
            if (requestData.guru_data && requestData.guru_data.length > 0) {
                requestData.guru_data.forEach((guru, index) => {
                    const guruRow = document.createElement('div');
                    guruRow.className = 'bg-gray-50 p-4 rounded-lg border';
                    guruRow.innerHTML = `
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Nama Guru:</p>
                                <p class="font-medium text-gray-800">${guru.nama}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">NIP:</p>
                                <p class="font-medium text-gray-800">${guru.nip}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Keterangan:</p>
                                <p class="font-medium text-gray-800">${guru.keterangan}</p>
                            </div>
                        </div>
                    `;
                    guruContainer.appendChild(guruRow);
                });
            } else {
                guruContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada data guru</p>';
            }
            
            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk format tanggal
        function formatDate(dateString) {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                return dateString;
            }
        }

        // Fungsi untuk close modal dan langsung approve
        function closeModalAndApprove() {
            if (currentRequestData) {
                closeModal('detailModal');
                setTimeout(() => {
                    openApprovalModal(currentRequestData.id, currentRequestData.teacher, currentRequestData.type);
                }, 300);
            }
        }

        // Fungsi untuk close modal dan langsung reject
        function closeModalAndReject() {
            if (currentRequestData) {
                closeModal('detailModal');
                setTimeout(() => {
                    openRejectionModal(currentRequestData.id, currentRequestData.teacher, currentRequestData.type);
                }, 300);
            }
        }

        // PERBAIKAN: Fungsi untuk menangani form submission dengan loading state
        function handleFormSubmit(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Set loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
            
            // Reset button setelah 5 detik jika tidak redirect
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 5000);
            
            return true; // Allow form submission
        }

        function openApprovalModal(requestId, teacherName, requestType) {
            console.log('Opening approval modal for:', requestId, teacherName, requestType); // Debug log
            
            document.getElementById('approvalRequestId').value = requestId;
            document.getElementById('approvalTeacherName').textContent = teacherName;
            document.getElementById('approvalRequestType').textContent = requestType;
            document.getElementById('approvalModal').classList.remove('hidden');
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
            
            // Focus pada tombol setujui untuk accessibility
            setTimeout(() => {
                document.getElementById('approveBtn').focus();
            }, 100);
        }

        function openRejectionModal(requestId, teacherName, requestType) {
            console.log('Opening rejection modal for:', requestId, teacherName, requestType); // Debug log
            
            document.getElementById('rejectionRequestId').value = requestId;
            document.getElementById('rejectionTeacherName').textContent = teacherName;
            document.getElementById('rejectionRequestType').textContent = requestType;
            document.getElementById('rejectionModal').classList.remove('hidden');
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
            
            // Focus pada textarea untuk user experience yang lebih baik
            setTimeout(() => {
                document.querySelector('textarea[name="rejection_reason"]').focus();
            }, 100);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                
                // Restore body scroll when modal is closed
                document.body.style.overflow = 'auto';
                
                // Reset current request data if closing detail modal
                if (modalId === 'detailModal') {
                    currentRequestData = null;
                }
                
                // Reset form jika ada
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    // Reset button state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        const icon = submitBtn.querySelector('i');
                        const text = submitBtn.dataset.action === 'approve' ? 'Setujui' : 'Tolak';
                        const iconClass = submitBtn.dataset.action === 'approve' ? 'fa-check' : 'fa-times';
                        submitBtn.innerHTML = `<i class="fas ${iconClass} mr-1"></i>${text}`;
                    }
                }
            }
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('detailModal');
            }
        });

        document.getElementById('approvalModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('approvalModal');
            }
        });

        document.getElementById('rejectionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('rejectionModal');
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('detailModal');
                closeModal('approvalModal');
                closeModal('rejectionModal');
            }
        });

        // PERBAIKAN: Validasi form rejection sebelum submit
        document.addEventListener('DOMContentLoaded', function() {
            const rejectionForm = document.querySelector('#rejectionModal form');
            if (rejectionForm) {
                rejectionForm.addEventListener('submit', function(e) {
                    const textarea = this.querySelector('textarea[name="rejection_reason"]');
                    if (!textarea.value.trim()) {
                        e.preventDefault();
                        alert('Alasan penolakan harus diisi!');
                        textarea.focus();
                        return false;
                    }
                });
            }

            // Set button data attributes for proper reset
            document.getElementById('approveBtn').dataset.action = 'approve';
            document.getElementById('rejectBtn').dataset.action = 'reject';
        });

        // Improved touch handling untuk mobile devices
        let touchStartY = 0;
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchmove', function(e) {
            // Prevent rubber band scrolling on iOS
            if (e.target.closest('.modal-enter')) {
                const touchY = e.touches[0].clientY;
                const touchYDelta = touchStartY - touchY;
                const modal = e.target.closest('.modal-enter');
                
                if (modal.scrollTop === 0 && touchYDelta < 0) {
                    e.preventDefault();
                } else if (modal.scrollHeight - modal.scrollTop === modal.clientHeight && touchYDelta > 0) {
                    e.preventDefault();
                }
            }
        });

        // Handle window resize dan orientation changes
        window.addEventListener('resize', function() {
            updateResponsiveElements();
        });

        window.addEventListener('orientationchange', function() {
            setTimeout(function() {
                updateResponsiveElements();
            }, 100);
        });

        function updateResponsiveElements() {
            const viewportHeight = window.innerHeight;
            const modals = document.querySelectorAll('.modal-enter');
            modals.forEach(modal => {
                modal.style.maxHeight = Math.min(viewportHeight * 0.9, 600) + 'px';
            });
        }

        // Performance optimization: Debounce resize events
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(updateResponsiveElements, 250);
        });

        // Add haptic feedback untuk supported devices
        function hapticFeedback(type = 'light') {
            if ('vibrate' in navigator) {
                switch(type) {
                    case 'light':
                        navigator.vibrate(10);
                        break;
                    case 'medium':
                        navigator.vibrate(20);
                        break;
                    case 'heavy':
                        navigator.vibrate(40);
                        break;
                }
            }
        }

        // Add haptic feedback ke important buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bg-green-600, .bg-red-600').forEach(button => {
                button.addEventListener('click', () => hapticFeedback('light'));
            });
        });

        // Prevent zoom on double tap untuk iOS
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                const activeModal = document.querySelector('[id$="Modal"]:not(.hidden)');
                if (activeModal) {
                    const focusableElements = activeModal.querySelectorAll(
                        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                    );
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];

                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            lastElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            firstElement.focus();
                            e.preventDefault();
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
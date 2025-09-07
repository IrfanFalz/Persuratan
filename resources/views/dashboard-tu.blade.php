<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard TU - Sistem Persuratan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for better mobile experience */
        @media (max-width: 640px) {
            .mobile-hide { display: none !important; }
            .mobile-text-sm { font-size: 0.875rem; }
            .mobile-text-xs { font-size: 0.75rem; }
            .mobile-p-2 { padding: 0.5rem; }
            .mobile-p-3 { padding: 0.75rem; }
            .mobile-p-4 { padding: 1rem; }
        }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.3s ease; }
        
        /* Better scrolling on mobile */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        /* Modal improvements for mobile */
        @media (max-width: 768px) {
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }
        }

        /* Responsive table */
        .responsive-table {
            display: block;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }

        /* Card stacking improvements */
        .card-stack > * + * {
            margin-top: 1rem;
        }

        /* Button group responsive */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        @media (min-width: 640px) {
            .button-group {
                flex-direction: row;
                align-items: center;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="flex justify-between h-14 sm:h-16">
                <div class="flex items-center min-w-0 flex-1">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/logo grf.png') }}" 
                            alt="Logo SMK Negeri 4 Malang" 
                            class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8 object-contain">
                    </div>
                    <div class="ml-2 sm:ml-3 min-w-0">
                        <h1 class="text-base sm:text-xl font-bold text-gray-800 truncate">Sistem Persuratan</h1>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Dashboard TU</p>
                    </div>
                </div>
                
                <!-- All devices navigation - responsive without hamburger -->
                <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-lg lg:text-xl cursor-pointer"></i>
                        <span class="absolute -top-1 -right-1 lg:-top-2 lg:-right-2 bg-red-500 text-white text-xs rounded-full px-1 lg:px-1.5 py-0.5">1</span>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-medium text-gray-800">{{ session('name') }}</p>
                        <p class="text-xs text-gray-600">{{ session('role')}}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 lg:px-4 py-1.5 lg:py-2 rounded-lg transition duration-200 text-xs sm:text-sm lg:text-base flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i><span class="hidden sm:inline">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-8">
        <!-- Success Message -->
         @if(session('message'))
            <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 sm:mr-3 text-lg sm:text-xl"></i>
                    <p class="text-sm sm:text-base">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl lg:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-white">
            <h2 class="text-lg sm:text-2xl font-bold mb-1 sm:mb-2">Selamat Datang, {{ session('name') }}!</h2>
            <p class="opacity-90 text-sm sm:text-base">Proses dan cetak surat dengan efisien untuk memenuhi kebutuhan guru.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-2 sm:mb-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Perlu Diproses</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-600">{{ collect($approved_letters)->where('status', 'need_processing')->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-orange-100 rounded-lg flex items-center justify-center self-end sm:self-auto">
                        <i class="fas fa-hourglass-start text-orange-600 text-sm sm:text-base lg:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-2 sm:mb-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Sedang Diproses</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">{{ collect($approved_letters)->where('status', 'processing')->count() }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center self-end sm:self-auto">
                        <i class="fas fa-cogs text-blue-600 text-sm sm:text-base lg:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-2 sm:mb-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Selesai Hari Ini</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">{{ count($completed_letters) }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center self-end sm:self-auto">
                        <i class="fas fa-check-circle text-green-600 text-sm sm:text-base lg:text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-2 sm:mb-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Bulan Ini</p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-600">{{ count($approved_letters) + count($completed_letters) }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg flex items-center justify-center self-end sm:self-auto">
                        <i class="fas fa-file-alt text-purple-600 text-sm sm:text-base lg:text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Letters to Process -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden mb-6 sm:mb-8">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-2 sm:mr-3"></i>Surat Perlu Diproses
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($approved_letters as $letter)
                    <div class="p-4 sm:p-6 hover:bg-gray-50">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between space-y-4 lg:space-y-0">
                            <div class="flex-1 lg:mr-6">
                                <!-- Header Section -->
                                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($letter['teacher'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $letter['teacher'] }}</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">NIP: {{ $letter['nip'] }} • {{ $letter['subject'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Info Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4">
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Jenis Surat:</p>
                                        <p class="font-medium text-gray-800 flex items-center text-sm sm:text-base">
                                            <i class="fas fa-file-alt text-blue-500 mr-2"></i>{{ $letter['type'] }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Disetujui:</p>
                                        <p class="font-medium text-gray-800 flex items-center text-sm sm:text-base">
                                            <i class="fas fa-calendar text-green-500 mr-2"></i>{{ \Carbon\Carbon::parse($letter['approved_date'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Letter Details -->
                                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg mb-4">
                                    @if($letter['type'] === 'Surat Tugas')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Keperluan:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['keperluan'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Tempat:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['tempat'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Tanggal:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($letter['tanggal_tugas'])->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Waktu:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['waktu'] }}</p>
                                            </div>
                                        </div>
                                    @elseif($letter['type'] === 'Surat Perintah Tugas')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Keperluan:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['keperluan'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Tempat:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['tempat'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Tanggal:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($letter['tanggal_tugas'])->format('d/m/Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Waktu:</p>
                                                <p class="text-gray-800 text-sm sm:text-base">{{ $letter['jam'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="button-group w-full lg:w-auto">
                                 @if($letter['status'] === 'need_processing')
                                    <form method="POST" class="w-full lg:w-auto" action="{{ route('dashboard.tu.process') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="start_process">
                                        <input type="hidden" name="letter_id" value="{{ $letter['id'] }}">
                                        <button type="submit" 
                                                class="w-full lg:w-auto px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center text-sm">
                                            <i class="fas fa-play mr-2"></i>Mulai Proses
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" class="w-full lg:w-auto" action="{{ route('dashboard.tu.process') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="complete">
                                        <input type="hidden" name="letter_id" value="{{ $letter['id'] }}">
                                        <button type="submit" 
                                                class="w-full lg:w-auto px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center text-sm">
                                            <i class="fas fa-check mr-2"></i>Selesai
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="openPreviewModal({{ $letter['id'] }})"
                                        class="w-full lg:w-auto px-3 sm:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 flex items-center justify-center text-sm">
                                    <i class="fas fa-eye mr-2"></i>Preview
                                </button>
                                
                                <button class="w-full lg:w-auto px-3 sm:px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 flex items-center justify-center text-sm">
                                    <i class="fas fa-print mr-2"></i>Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if(empty($approved_letters))
                    <div class="p-6 sm:p-8 text-center">
                        <i class="fas fa-inbox text-gray-300 text-3xl sm:text-4xl mb-4"></i>
                        <p class="text-gray-500 text-sm sm:text-base">Tidak ada surat yang perlu diproses</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Completed Letters -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden mb-6 sm:mb-8">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-check-double text-green-600 mr-2 sm:mr-3"></i>Surat Selesai - Siap Diambil
                </h3>
            </div>
            
            <!-- Mobile Card View -->
            <div class="block sm:hidden">
                 @foreach($completed_letters as $letter)
                    <div class="p-4 border-b border-gray-200 hover:bg-gray-50">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($letter['teacher'], 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 text-sm">{{ $letter['teacher'] }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-alt mr-1"></i>{{ $letter['type'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-3">
                            <div>
                                <p class="text-xs text-gray-600">Selesai:</p>
                                <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($letter['completed_date'])->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                @if($letter['pickup_notified'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-bell mr-1"></i>Sudah Diberitahu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-bell-slash mr-1"></i>Belum Diberitahu
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <button onclick="openCompletedLetterModal({{ $letter['id'] }})" class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 border border-blue-200 rounded">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </button>
                            @if(!$letter['pickup_notified'])
                                <button class="text-green-600 hover:text-green-900 text-xs px-2 py-1 border border-green-200 rounded">
                                    <i class="fas fa-bell mr-1"></i>Beritahu
                                </button>
                            @endif
                            <button class="text-purple-600 hover:text-purple-900 text-xs px-2 py-1 border border-purple-200 rounded">
                                <i class="fas fa-print mr-1"></i>Cetak Ulang
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                         @foreach($completed_letters as $letter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($letter['teacher'], 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900 text-sm lg:text-base">{{ $letter['teacher'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-alt mr-1"></i>{{ $letter['type'] }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-500">
                                     {{ \Carbon\Carbon::parse($letter['completed_date'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                     @if($letter['pickup_notified'])
                                        <span class="inline-flex items-center px-2 lg:px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-bell mr-1"></i>Sudah Diberitahu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 lg:px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-bell-slash mr-1"></i>Belum Diberitahu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm font-medium">
                                    <button onclick="openCompletedLetterModal({{ $letter['id'] }})" class="text-blue-600 hover:text-blue-900 mr-2 lg:mr-3">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </button>
                                     @if(!$letter['pickup_notified'])
                                        <button class="text-green-600 hover:text-green-900 mr-2 lg:mr-3">
                                            <i class="fas fa-bell mr-1"></i>Beritahu
                                        </button>
                                    @endif
                                    <button class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-print mr-1"></i>Cetak Ulang
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(empty($completed_letters))
                            <tr>
                                <td colspan="5" class="px-4 lg:px-6 py-6 lg:py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl lg:text-4xl mb-4 block text-gray-300"></i>
                                    <span class="text-sm lg:text-base">Tidak ada surat yang sudah selesai hari ini</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Empty state for mobile -->
            @if(empty($completed_letters))
                <div class="block sm:hidden p-6 text-center">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-4"></i>
                    <p class="text-gray-500 text-sm">Tidak ada surat yang sudah selesai hari ini</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Templates -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-contract text-indigo-600 mr-2 sm:mr-3"></i>Template Surat
                </h3>
                
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-briefcase text-blue-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Surat Tugas</span>
                        </div>
                        <div class="flex space-x-1 sm:space-x-2 ml-2">
                            <button class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-edit text-xs sm:text-sm"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800 p-1">
                                <i class="fas fa-eye text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-calendar-times text-orange-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Surat Izin</span>
                        </div>
                        <div class="flex space-x-1 sm:space-x-2 ml-2">
                            <button class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-edit text-xs sm:text-sm"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800 p-1">
                                <i class="fas fa-eye text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-certificate text-purple-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Surat Perintah Tugas</span>
                        </div>
                        <div class="flex space-x-1 sm:space-x-2 ml-2">
                            <button class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-edit text-xs sm:text-sm"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800 p-1">
                                <i class="fas fa-eye text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-umbrella-beach text-red-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <span class="font-medium text-sm sm:text-base truncate">Surat Cuti</span>
                        </div>
                        <div class="flex space-x-1 sm:space-x-2 ml-2">
                            <button class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-edit text-xs sm:text-sm"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800 p-1">
                                <i class="fas fa-eye text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-green-600 mr-2 sm:mr-3"></i>Aktivitas Terbaru
                </h3>
                
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-green-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800">Surat Izin Maya Sari selesai</p>
                            <p class="text-xs text-gray-500">2 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cogs text-blue-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800">Mulai memproses Surat Tugas Eko</p>
                            <p class="text-xs text-gray-500">4 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bell text-purple-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800">Notifikasi pengambilan dikirim</p>
                            <p class="text-xs text-gray-500">Kemarin, 16:30</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-alt text-indigo-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800">Template Surat Keterangan diupdate</p>
                            <p class="text-xs text-gray-500">Kemarin, 14:20</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
            <div class="bg-white rounded-lg w-full max-w-5xl max-h-[90vh] overflow-hidden modal-content">
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Detail Surat</h3>
                    <button onclick="closeModal('previewModal')" class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
                
                <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                    <div id="letterDetailContent" class="space-y-6">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('previewModal')" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm sm:text-base">
                            Tutup
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm sm:text-base">
                            <i class="fas fa-print mr-1"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-60 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Memuat detail surat...</span>
        </div>
    </div>

    <script>
    // Data surat dari controller
    const letterData = @json($approved_letters);
    
    console.log('Letter data:', letterData); // Debug log

    function openPreviewModal(letterId) {
        console.log('Opening modal for letter ID:', letterId); // Debug log
        
        document.getElementById('loadingSpinner').classList.remove('hidden');
        
        // Simulasi loading delay
        setTimeout(() => {
            try {
                // Cari berdasarkan ID, bukan index array
                const letter = letterData.find(item => item.id == letterId);
                console.log('Found letter:', letter); // Debug log
                
                if (letter) {
                    displayLetterDetails(letter);
                    document.getElementById('previewModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    console.error('Letter not found with ID:', letterId);
                    alert('Data surat tidak ditemukan');
                }
            } catch (error) {
                console.error('Error in openPreviewModal:', error);
                alert('Terjadi error saat membuka preview');
            }
            
            document.getElementById('loadingSpinner').classList.add('hidden');
        }, 500); // Berikan delay yang cukup untuk melihat loading
    }

    function displayLetterDetails(letter) {
        const content = document.getElementById('letterDetailContent');
        
        let html = `
            <!-- Header Information -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 sm:p-6 rounded-lg border border-blue-200">
                <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-3 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>${letter.type}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Nama Lengkap:</span>
                            <p class="font-semibold text-gray-900">${letter.full_name}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">NIP:</span>
                            <p class="font-mono text-gray-900">${letter.nip}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">No. Telepon:</span>
                            <p class="font-mono text-gray-900">${letter.phone}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Mata Pelajaran:</span>
                            <p class="font-semibold text-gray-900">${letter.subject}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${letter.status === 'need_processing' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800'}">
                                ${letter.status === 'need_processing' ? 'Perlu Diproses' : 'Sedang Diproses'}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Disetujui:</span>
                            <p class="text-gray-900">${new Date(letter.approved_date).toLocaleDateString('id-ID')} oleh ${letter.approved_by}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Surat -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Detail Keperluan
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Keperluan:</span>
                            <p class="text-gray-900">${letter.keperluan}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Tempat:</span>
                            <p class="text-gray-900">${letter.tempat}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Hari/Tanggal:</span>
                            <p class="text-gray-900">${letter.hari}, ${new Date(letter.tanggal_tugas).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="block text-sm font-medium text-gray-600 mb-1">Jam:</span>
                            <p class="text-gray-900">${letter.waktu || letter.jam}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Guru -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users text-green-600 mr-2"></i>Data Guru Terlibat
                </h5>
                <div class="space-y-3">
        `;

        letter.guru_list.forEach((guru, index) => {
            html += `
                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        ${guru.nama.split(' ').map(n => n[0]).join('').toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <span class="text-xs text-gray-600">Nama:</span>
                                <p class="font-medium text-gray-900">${guru.nama}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-600">NIP:</span>
                                <p class="font-mono text-sm text-gray-900">${guru.nip}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-600">Keterangan:</span>
                                <p class="text-gray-900">${guru.keterangan}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>

            <!-- Preview Surat -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-eye text-purple-600 mr-2"></i>Preview Surat
                </h5>
                <div class="bg-gray-50 p-4 sm:p-6 rounded-lg border-2 border-dashed border-gray-300">
                    <div class="text-center mb-6">
                        <h6 class="text-base sm:text-lg font-bold mb-2">PEMERINTAH PROVINSI JAWA TIMUR</h6>
                        <p class="text-sm font-semibold mb-1">DINAS PENDIDIKAN</p>
                        <p class="text-sm font-semibold mb-4">SMA NEGERI 1 SURABAYA</p>
                        <div class="border-b-2 border-black mb-4"></div>
                        <h4 class="text-base font-bold underline">${letter.type.toUpperCase()}</h4>
                        <p class="text-xs">Nomor: 421/00${letter.id}/SMA1-SBY/VIII/2025</p>
                    </div>
                    
                    <div class="text-justify leading-relaxed text-sm">
                        <p class="mb-3">Yang bertanda tangan di bawah ini Kepala SMA Negeri 1 Surabaya dengan ini ${letter.type === 'Surat Tugas' ? 'menugaskan' : 'memerintahkan'}:</p>
                        
                        <div class="ml-6 my-4 space-y-1">
                            <p>Nama : ${letter.full_name}</p>
                            <p>NIP : ${letter.nip}</p>
                            <p>Jabatan : Guru ${letter.subject}</p>
                        </div>
                        
                        <p class="mb-3">Untuk melaksanakan tugas ${letter.keperluan} yang akan dilaksanakan di ${letter.tempat} pada hari ${letter.hari} tanggal ${new Date(letter.tanggal_tugas).toLocaleDateString('id-ID')} pukul ${letter.waktu || letter.jam} WIB.</p>
                        <p>Demikian surat ${letter.type.toLowerCase()} ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <div></div>
                        <div class="text-center">
                            <p class="text-sm">Surabaya, ${new Date().toLocaleDateString('id-ID')}</p>
                            <p class="mb-12 text-sm">Kepala Sekolah</p>
                            <p class="font-bold text-sm">Dr. Ahmad Wijaya, M.Pd</p>
                            <p class="text-sm">NIP. 196805121994031002</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        content.innerHTML = html;
    }

    const completedLetterData = @json($completed_letters_indexed ?? []);

console.log('Completed Letter data:', completedLetterData); // Debug log

// Fungsi untuk completed letters
function openCompletedLetterModal(letterId) {
    console.log('Opening completed modal for letter ID:', letterId);
    
    document.getElementById('loadingSpinner').classList.remove('hidden');
    
    setTimeout(() => {
        try {
            const letter = completedLetterData[letterId];
            console.log('Found completed letter:', letter);
            
            if (letter) {
                displayCompletedLetterDetails(letter);
                document.getElementById('previewModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('Completed letter not found with ID:', letterId);
                alert('Data surat tidak ditemukan');
            }
        } catch (error) {
            console.error('Error in openCompletedLetterModal:', error);
            alert('Terjadi error saat membuka preview');
        }
        
        document.getElementById('loadingSpinner').classList.add('hidden');
    }, 500);
}

function displayCompletedLetterDetails(letter) {
    const content = document.getElementById('letterDetailContent');
    
    let html = `
        <!-- Header Information -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 sm:p-6 rounded-lg border border-green-200">
            <h4 class="text-lg sm:text-xl font-bold text-green-900 mb-3 flex items-center">
                <i class="fas fa-file-alt text-green-600 mr-2"></i>${letter.type}
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check mr-1"></i>Selesai
                </span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Nama Lengkap:</span>
                        <p class="font-semibold text-gray-900">${letter.full_name}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">NIP:</span>
                        <p class="font-mono text-gray-900">${letter.nip}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">No. Telepon:</span>
                        <p class="font-mono text-gray-900">${letter.phone}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Mata Pelajaran:</span>
                        <p class="font-semibold text-gray-900">${letter.subject}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Tanggal Selesai:</span>
                        <p class="text-gray-900">${new Date(letter.completed_date).toLocaleDateString('id-ID')} ${new Date(letter.completed_date).toLocaleTimeString('id-ID')}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Status Notifikasi:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${letter.pickup_notified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            <i class="fas ${letter.pickup_notified ? 'fa-bell' : 'fa-bell-slash'} mr-1"></i>
                            ${letter.pickup_notified ? 'Sudah Diberitahu' : 'Belum Diberitahu'}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Surat -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Detail Keperluan
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="block text-sm font-medium text-gray-600 mb-1">Keperluan:</span>
                        <p class="text-gray-900">${letter.keperluan}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="block text-sm font-medium text-gray-600 mb-1">Tempat:</span>
                        <p class="text-gray-900">${letter.tempat}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="block text-sm font-medium text-gray-600 mb-1">Hari/Tanggal:</span>
                        <p class="text-gray-900">${letter.hari}, ${new Date(letter.tanggal_tugas).toLocaleDateString('id-ID')}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="block text-sm font-medium text-gray-600 mb-1">Jam:</span>
                        <p class="text-gray-900">${letter.jam}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Guru -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users text-green-600 mr-2"></i>Data Guru Terlibat
            </h5>
            <div class="space-y-3">`;

    letter.guru_list.forEach((guru, index) => {
        html += `
            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                    ${guru.nama.split(' ').map(n => n[0]).join('').toUpperCase()}
                </div>
                <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <div>
                            <span class="text-xs text-gray-600">Nama:</span>
                            <p class="font-medium text-gray-900">${guru.nama}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">NIP:</span>
                            <p class="font-mono text-sm text-gray-900">${guru.nip}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">Keterangan:</span>
                            <p class="text-gray-900">${guru.keterangan}</p>
                        </div>
                    </div>
                </div>
            </div>`;
    });

    html += `
            </div>
        </div>`;

    content.innerHTML = html;
}

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('previewModal');
        }
    });

    // Handle escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('previewModal');
            if (!modal.classList.contains('hidden')) {
                closeModal('previewModal');
            }
        }
    });

    // Smooth scrolling for better UX
    document.documentElement.style.scrollBehavior = 'smooth';
</script>
</body>
</html>
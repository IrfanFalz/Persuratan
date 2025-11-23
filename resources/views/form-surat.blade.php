
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form {{ $letter_types[$letter_type] ?? 'Surat' }} - Sistem Persuratan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            /* Custom scrollbar for better mobile experience */
            ::-webkit-scrollbar {
                width: 6px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            ::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 3px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            /* Smooth transitions */
            * {
                transition: all 0.2s ease-in-out;
            }

            /* Better focus styles for mobile */
            input:focus, select:focus, textarea:focus {
                transform: scale(1.02);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            /* Mobile-first responsive design */
            @media (max-width: 640px) {
                .mobile-padding {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                
                .mobile-text-sm {
                    font-size: 0.875rem;
                }
                
                .mobile-icon-sm {
                    font-size: 1.25rem;
                }
            }

            /* Prevent horizontal scroll on mobile */
            body {
                overflow-x: hidden;
            }

            /* Better touch targets for mobile */
            @media (max-width: 768px) {
                button, input[type="submit"], input[type="button"], .btn {
                    min-height: 44px;
                    min-width: 44px;
                }
            }
            /* Autocomplete dropdown styling */
            #autocomplete-dropdown {
                border-top: none;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }

            #autocomplete-dropdown div:hover {
                background-color: #f3f4f6;
            }

            /* Prevent dropdown from being cut off */
            .relative {
                position: relative;
            }
        </style>
    </head>
    <body class="bg-gray-50 min-h-screen">
        <!-- Navigation - Mobile Optimized -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="w-full px-3 sm:px-4 md:px-6 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16">
                    <div class="flex items-center min-w-0 flex-1">
                        <a href="{{ route('dashboard.guru') }}" class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                            <i class="fas fa-arrow-left text-blue-600 text-lg sm:text-xl flex-shrink-0"></i>
                            <div class="flex-shrink-0">
                            <img src="{{ asset('images/logo grf.png') }}"
                                alt="Logo SMK Negeri 4 Malang" 
                                class="h-5 w-5 sm:h-6 sm:w-6 lg:h-8 lg:w-8 object-contain">
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Sistem Persuratan</h1>
                                <p class="text-xs sm:text-sm text-gray-600 truncate">Form Pengajuan</p>
                            </div>
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        <div class="text-right min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-800 truncate max-w-24 sm:max-w-none">{{ session('name') }}</p>
                            <p class="text-xs text-gray-600">{{ session('role') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="w-full max-w-none sm:max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 md:px-6 lg:px-8">
            @php
                $lt = in_array($letter_type, ['spt', 'surat-perintah-tugas']) ? 'spt' : (in_array($letter_type, ['dispensasi', 'surat-dispensasi']) ? 'dispensasi' : $letter_type);
            @endphp
            @if($success_message)
                <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-lg sm:text-xl flex-shrink-0 mt-0.5"></i>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-sm sm:text-base">Berhasil!</h4>
                            <p class="text-sm sm:text-base break-words">{{ $success_message }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header - Mobile Optimized -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-alt text-white text-lg sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 break-words">{{ $letter_types[$letter_type] ?? 'Surat' }}</h2>
                            <p class="text-sm sm:text-base text-gray-600 break-words">Lengkapi form di bawah untuk pengajuan surat</p>
                        </div>
                    </div>
                    <div class="text-left sm:text-right flex-shrink-0">
                        <p class="text-xs sm:text-sm text-gray-500">Tanggal: {{ date('d/m/Y') }}</p>
                        <p class="text-xs sm:text-sm text-gray-500">Waktu: {{ date('H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            <!-- Form - Mobile Optimized -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8">
                <form method="POST" action="{{ route('surat.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_template" 
                        value="{{ $lt === 'spt' ? 1 : ($lt === 'dispensasi' ? 3 : '') }}">
                    <input type="hidden" name="jenis" value="{{ $lt === 'spt' ? 'spt' : ($lt === 'dispensasi' ? 'dispensasi' : '') }}">

                    <!-- Data Pemohon -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-3 flex-shrink-0"></i>
                            <span>Data Pemohon</span>
                        </h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" id="nama-input" placeholder="Ketik nama guru..." required
                                        class="w-full px-3 sm:px-4 py-3 pr-12 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        autocomplete="off">
                                    <button type="button" id="search-guru-btn" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div id="autocomplete-dropdown" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <div class="relative">
                                    <input type="text" id="nip-input" placeholder="NIP akan terisi otomatis" required 
                                        class="w-full px-3 sm:px-4 py-3 pr-12 border border-gray-300 rounded-lg bg-gray-50 text-sm sm:text-base">
                                    <button type="button" id="search-guru-btn-2" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <div class="relative">
                                    <input type="tel" id="phone-input" placeholder="Ketik nomor telepon..." required 
                                        class="w-full px-3 sm:px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    <button type="button" id="search-guru-btn-3" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div id="phone-autocomplete-dropdown" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM SURAT PERINTAH TUGAS -->
                    @if($lt === 'spt')
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-briefcase text-green-600 mr-3 flex-shrink-0"></i>
                                <span>Detail Surat Perintah Tugas</span>
                            </h3>

                            <div class="space-y-4 sm:space-y-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Keperluan</label>
                                    <textarea name="keperluan" rows="3" placeholder="Jelaskan keperluan..." required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"></textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Hari</label>
                                        <input type="text" name="hari" placeholder="Masukkan hari" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                        <input type="date" name="tanggal" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Jam</label>
                                        <input type="text" name="jam" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                                        <input type="text" name="tempat" placeholder="Masukkan tempat" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-700">Data Guru (Nama, NIP, Keterangan)</h4>

                                    <div id="guru-container" class="space-y-4">
                                        <div class="guru-row space-y-3 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-gray-50 rounded-lg">
                                            <div class="space-y-1 relative">
                                                <label class="block text-xs text-gray-600 sm:hidden">Nama Guru</label>
                                                <input type="text" name="nama_guru[]" placeholder="Ketik nama guru..." required autocomplete="off"
                                                    class="guru-nama-input w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                                <div class="guru-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                                            </div>

                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">NIP</label>
                                                <input type="text" name="nip_guru[]" placeholder="NIP akan terisi otomatis" required 
                                                    class="guru-nip-input w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-sm sm:text-base">
                                            </div>

                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Keterangan</label>
                                                <input type="text" name="keterangan_guru[]" placeholder="Keterangan (peran)" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" id="add-guru" class="w-full sm:w-auto px-4 py-2 text-blue-600 hover:text-blue-900 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center justify-center sm:justify-start">
                                        <i class="fas fa-plus mr-2"></i> Tambah Guru
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- FORM SURAT DISPENSASI -->
                    @if($lt === 'dispensasi')
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-times text-orange-600 mr-3 flex-shrink-0"></i>
                                <span>Detail Surat Dispensasi</span>
                            </h3>

                            <div class="space-y-4 sm:space-y-6">

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Keperluan</label>
                                    <textarea name="keperluan" rows="3" placeholder="Jelaskan keperluan..." required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"></textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Hari</label>
                                        <input type="text" name="hari" placeholder="Masukkan hari" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                        <input type="date" name="tanggal" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Jam</label>
                                        <input type="text" name="jam" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                                        <input type="text" name="tempat" placeholder="Masukkan tempat" required
                                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-700">Data Siswa (Nama, NISN, Kelas)</h4>

                                    <div id="siswa-dispensasi-container" class="space-y-4">

                                        <div class="siswa-row space-y-3 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-gray-50 rounded-lg">
                                                <div class="space-y-1 relative">
                                                    <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                                                    <input type="text" name="nama_siswa[]" value="{{ old('nama_siswa.0') }}" placeholder="Masukkan nama siswa..." required 
                                                        class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-nama-input">
                                                    <div class="siswa-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                                                </div>

                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">NISN</label>
                                                <input type="text" name="nisn[]" value="{{ old('nisn.0') }}" placeholder="Masukkan NISN..." required 
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-nisn-input">
                                            </div>

                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                                                <input type="text" name="kelas_siswa[]" value="{{ old('kelas_siswa.0') }}" placeholder="Masukkan kelas..." required 
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-kelas-input">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" id="add-siswa-dispensasi" class="w-full sm:w-auto px-4 py-2 text-blue-600 hover:text-blue-900 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center justify-center sm:justify-start">
                                        <i class="fas fa-plus mr-2"></i> Tambah Siswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Lampiran -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-paperclip text-indigo-600 mr-3 flex-shrink-0"></i>
                            <span>Lampiran Pendukung</span>
                        </h3>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Upload Dokumen (Opsional)@if($lt === 'spt') - Termasuk Bukti Undangan @endif</label>

                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center hover:border-blue-400 transition duration-200">
                                    <i class="fas fa-cloud-upload-alt text-2xl sm:text-4xl text-gray-400 mb-2 sm:mb-4"></i>
                                    <input type="file" name="lampiran" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="hidden" id="file-upload">
                                    <label for="file-upload" class="cursor-pointer block">
                                        <span class="text-blue-600 hover:text-blue-800 font-medium text-sm sm:text-base">Klik untuk upload</span>
                                        <span class="text-gray-600 text-sm sm:text-base block sm:inline"> atau drag and drop file</span>
                                    </label>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-2">PDF, JPG, PNG, DOC, DOCX (Max: 5MB)</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)..."
                                        class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0 pt-6">
                        <a href="{{ route('dashboard.guru') }}"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>

                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-semibold shadow-lg flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Surat
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 sm:mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-blue-600 text-lg sm:text-xl mt-1 flex-shrink-0"></i>
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2 text-sm sm:text-base">Informasi Proses Persetujuan</h4>
                        <div class="text-xs sm:text-sm text-blue-700 space-y-1">
                            <p>• Setelah submit, surat akan dikirim ke KTU untuk persetujuan</p>
                            <p>• Jika disetujui KTU, akan diteruskan ke Kepala Sekolah (untuk jenis surat tertentu)</p>
                            <p>• Setelah disetujui, TU akan memproses dan mencetak surat</p>
                            <p>• Anda akan mendapat notifikasi di setiap tahap proses</p>
                            <p>• Surat yang sudah jadi dapat diambil di ruang TU</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Search Guru -->
        <div id="guru-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-50 transition-opacity"></div>
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Pilih Data Guru</h3>
                        <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <input type="text" id="search-input" placeholder="Cari nama atau NIP..." 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-4">
                        <div id="guru-list" class="max-h-60 overflow-y-auto space-y-2">
                            <!-- Data akan diload via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // File upload preview
document.getElementById('file-upload').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
        const fileNames = Array.from(files).map(file => file.name).join(', ');
        const label = document.querySelector('label[for="file-upload"]');
        label.innerHTML = `<span class="text-green-600"><i class="fas fa-check mr-1"></i>${files.length} file dipilih</span>`;
    }
});

// Data storage
let guruData = [];
let siswaData = [];

// Load data saat halaman dimuat
Promise.all([
    fetch('/guru-data').then(r => r.json()),
    fetch('/siswa-data').then(r => r.json())
]).then(([guru, siswa]) => {
    guruData = guru;
    siswaData = siswa;
    initializeAllAutocomplete();
}).catch(() => {
    // Fallback jika fetch gagal
    guruData = [];
    siswaData = [];
});

// Initialize semua autocomplete
function initializeAllAutocomplete() {
    setupExistingAutocomplete();
    setupDynamicAutocomplete();
}

// Setup autocomplete untuk input yang sudah ada
function setupExistingAutocomplete() {
    // Autocomplete untuk Data Pemohon - Nama, NIP, dan Phone (sinkronisasi)
    const namaInput = document.getElementById('nama-input');
    const nipInput = document.getElementById('nip-input');
    const phoneInput = document.getElementById('phone-input');
    const dropdown = document.getElementById('autocomplete-dropdown');
    const phoneDropdown = document.getElementById('phone-autocomplete-dropdown');

    if (namaInput && dropdown) {
        setupSingleAutocomplete(namaInput, dropdown, {nip: nipInput, phone: phoneInput}, guruData, 'guru-pemohon');
    }

    if (phoneInput && phoneDropdown) {
        setupSingleAutocomplete(phoneInput, phoneDropdown, {nama: namaInput, nip: nipInput}, guruData, 'guru-phone');
    }

    // Setup untuk input yang sudah ada di form
    setupAutocompleteForExisting();
}

// Setup autocomplete untuk input yang sudah ada di form
function setupAutocompleteForExisting() {
    // Surat Perintah Tugas - input yang sudah ada
    document.querySelectorAll('.guru-nama-input').forEach(input => {
        const dropdown = input.nextElementSibling;
        const nipInput = input.closest('.guru-row').querySelector('.guru-nip-input');
        if (dropdown) setupSingleAutocomplete(input, dropdown, nipInput, guruData, 'guru');
    });

    // Surat Dispensasi - Enable autocomplete for siswa name inputs
    document.querySelectorAll('.siswa-row input[name="nama_siswa[]"]').forEach(input => {
        const dropdown = input.parentElement.querySelector('.siswa-dropdown');
        const nisnInput = input.closest('.siswa-row').querySelector('input[name="nisn[]"]');
        const kelasInput = input.closest('.siswa-row').querySelector('input[name="kelas_siswa[]"]');
        if (dropdown) setupSingleAutocomplete(input, dropdown, { nisn: nisnInput, kelas: kelasInput }, siswaData, 'siswa');
    });

    // Surat Panggilan Ortu - Guru Ditemui
    document.querySelectorAll('.guru-ditemui-input').forEach(input => {
        const dropdown = input.nextElementSibling;
        if (dropdown) setupSingleAutocomplete(input, dropdown, null, guruData, 'guru-only');
    });

    // Surat Panggilan Ortu - Siswa
    document.querySelectorAll('.siswa-ortu-nama-input').forEach(input => {
        const dropdown = input.nextElementSibling;
        const kelasInput = input.closest('.siswa-ortu-row').querySelector('.siswa-ortu-kelas-input');
        if (dropdown) setupSingleAutocomplete(input, dropdown, kelasInput, siswaData, 'siswa-ortu');
    });
}

// Setup autocomplete untuk satu input
function setupSingleAutocomplete(input, dropdown, secondaryInput, data, type) {
    input.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        if (searchTerm.length === 0) {
            dropdown.classList.add('hidden');
            clearSecondaryInputs(secondaryInput);
            return;
        }
        
        let filtered = [];
        
        if (type === 'guru-phone') {
            // Search berdasarkan nama atau nomor telepon
            filtered = data.filter(item => 
                item.nama.toLowerCase().includes(searchTerm) ||
                item.phone.includes(searchTerm)
            ).slice(0, 5);
        } else {
            filtered = data.filter(item => 
                item.nama.toLowerCase().includes(searchTerm)
            ).slice(0, 5);
        }
        
        showAutocompleteDropdown(dropdown, filtered, input, secondaryInput, type);
    });

    input.addEventListener('blur', function() {
        setTimeout(() => dropdown.classList.add('hidden'), 150);
    });

    input.addEventListener('focus', function(e) {
        if (e.target.value.length > 0) {
            const searchTerm = e.target.value.toLowerCase().trim();
            let filtered = [];
            
            if (type === 'guru-phone') {
                filtered = data.filter(item => 
                    item.nama.toLowerCase().includes(searchTerm) ||
                    item.phone.includes(searchTerm)
                ).slice(0, 5);
            } else {
                filtered = data.filter(item => 
                    item.nama.toLowerCase().includes(searchTerm)
                ).slice(0, 5);
            }
            
            showAutocompleteDropdown(dropdown, filtered, input, secondaryInput, type);
        }
    });
}

// Clear secondary inputs
function clearSecondaryInputs(secondaryInput) {
    if (!secondaryInput) return;
    
    if (typeof secondaryInput === 'object') {
        // Untuk data pemohon (nama, nip, phone)
        if (secondaryInput.nip) secondaryInput.nip.value = '';
        if (secondaryInput.phone) secondaryInput.phone.value = '';
        if (secondaryInput.nama) secondaryInput.nama.value = '';
        // Untuk siswa (nisn & kelas)
        if (secondaryInput.nisn) secondaryInput.nisn.value = '';
        if (secondaryInput.kelas) secondaryInput.kelas.value = '';
    } else if (secondaryInput) {
        // Untuk input tunggal
        secondaryInput.value = '';
    }
}

// Show autocomplete dropdown
function showAutocompleteDropdown(dropdown, suggestions, input, secondaryInput, type) {
    if (suggestions.length === 0) {
        dropdown.classList.add('hidden');
        return;
    }
    
    dropdown.innerHTML = '';
    dropdown.classList.remove('hidden');
    
        suggestions.forEach(item => {
        const div = document.createElement('div');
        div.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b last:border-b-0';
        
        if (type === 'guru' || type === 'guru-pemohon') {
            div.innerHTML = `
                <div class="font-medium text-gray-900">${item.nama}</div>
                <div class="text-sm text-gray-600">${item.nip}</div>
            `;
        } else if (type === 'guru-phone') {
            div.innerHTML = `
                <div class="font-medium text-gray-900">${item.nama}</div>
                <div class="text-sm text-gray-600">${item.phone}</div>
            `;
        } else if (type === 'siswa') {
            div.innerHTML = `
                <div class="font-medium text-gray-900">${item.nama}</div>
                <div class="text-sm text-gray-600">${item.nisn} - ${item.kelas}</div>
            `;
        } else if (type === 'guru-only') {
            div.innerHTML = `<div class="font-medium text-gray-900">${item.nama}</div>`;
        } else if (type === 'siswa-ortu') {
            div.innerHTML = `
                <div class="font-medium text-gray-900">${item.nama}</div>
                <div class="text-sm text-gray-600">${item.kelas}</div>
            `;
        }

        // Use mousedown to avoid input blur hiding the dropdown before click fires
        div.addEventListener('mousedown', function(e) {
            e.preventDefault();
            selectAutocompleteItem(item, input, dropdown, secondaryInput, type);
        });

        dropdown.appendChild(div);
    });
}

// Select autocomplete item
function selectAutocompleteItem(item, input, dropdown, secondaryInput, type) {
    dropdown.classList.add('hidden');
    
    if (type === 'guru-phone') {
        input.value = item.phone;
        // Sinkronisasi dengan nama dan NIP
        if (secondaryInput && typeof secondaryInput === 'object') {
            if (secondaryInput.nama) secondaryInput.nama.value = item.nama;
            if (secondaryInput.nip) secondaryInput.nip.value = item.nip;
        }
    } else if (type === 'guru-pemohon') {
        input.value = item.nama;
        // Sinkronisasi dengan NIP dan Phone
        if (secondaryInput && typeof secondaryInput === 'object') {
            if (secondaryInput.nip) secondaryInput.nip.value = item.nip;
            if (secondaryInput.phone) secondaryInput.phone.value = item.phone;
        }
    } else {
        input.value = item.nama;
        
        if (secondaryInput) {
            if (type === 'guru' && secondaryInput) {
                secondaryInput.value = item.nip;
            } else if (type === 'siswa' && typeof secondaryInput === 'object') {
                if (secondaryInput.nisn) secondaryInput.nisn.value = item.nisn;
                if (secondaryInput.kelas) secondaryInput.kelas.value = item.kelas;
            } else if (type === 'siswa-ortu' && secondaryInput) {
                secondaryInput.value = item.kelas;
            }
        }
    }
}

// Setup dynamic autocomplete untuk row baru
function setupDynamicAutocomplete() {
    // Tidak perlu setup awal karena akan dipanggil saat row baru ditambah
}

// Function untuk setup autocomplete pada row baru
function setupAutocompleteForNewRow(newRow, type) {
    if (type === 'guru') {
        const namaInput = newRow.querySelector('input[name="nama[]"]');
        const nipInput = newRow.querySelector('input[name="nip[]"]');
        if (namaInput && nipInput) {
            // Tambah class dan dropdown
            namaInput.classList.add('guru-nama-input');
            namaInput.setAttribute('autocomplete', 'off');
            nipInput.classList.add('guru-nip-input');
            nipInput.setAttribute('readonly', true);
            nipInput.classList.add('bg-gray-50');
            
            // Buat dropdown
            const dropdown = document.createElement('div');
            dropdown.className = 'guru-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto';
            namaInput.parentElement.style.position = 'relative';
            namaInput.parentElement.appendChild(dropdown);
            
            setupSingleAutocomplete(namaInput, dropdown, nipInput, guruData, 'guru');
        }
    } else if (type === 'siswa') {
        // Setup autocomplete for siswa row: create dropdown and wire selection to nisn & kelas
        const namaInput = newRow.querySelector('input[name="nama_siswa[]"]');
        const nisnInput = newRow.querySelector('input[name="nisn[]"]');
        const kelasInput = newRow.querySelector('input[name="kelas_siswa[]"]');
        if (namaInput) {
            namaInput.classList.add('siswa-nama-input');
            namaInput.setAttribute('autocomplete', 'off');
            namaInput.parentElement.style.position = 'relative';
            const dropdown = document.createElement('div');
            dropdown.className = 'siswa-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto';
            namaInput.parentElement.appendChild(dropdown);
            setupSingleAutocomplete(namaInput, dropdown, { nisn: nisnInput, kelas: kelasInput }, siswaData, 'siswa');
        }
    } else if (type === 'guru-ditemui') {
        const namaInput = newRow.querySelector('input[name="guru_ditemui[]"]');
        if (namaInput) {
            namaInput.classList.add('guru-ditemui-input');
            namaInput.setAttribute('autocomplete', 'off');
            
            // Buat dropdown
            const dropdown = document.createElement('div');
            dropdown.className = 'guru-ditemui-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto';
            namaInput.parentElement.style.position = 'relative';
            namaInput.parentElement.appendChild(dropdown);
            
            setupSingleAutocomplete(namaInput, dropdown, null, guruData, 'guru-only');
        }
    } else if (type === 'siswa-ortu') {
        const namaInput = newRow.querySelector('input[name="nama_siswa[]"]');
        const kelasInput = newRow.querySelector('input[name="kelas[]"]');
        if (namaInput && kelasInput) {
            namaInput.classList.add('siswa-ortu-nama-input');
            namaInput.setAttribute('autocomplete', 'off');
            kelasInput.classList.add('siswa-ortu-kelas-input');
            kelasInput.setAttribute('readonly', true);
            kelasInput.classList.add('bg-gray-50');
            
            // Buat dropdown
            const dropdown = document.createElement('div');
            dropdown.className = 'siswa-ortu-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto';
            namaInput.parentElement.style.position = 'relative';
            namaInput.parentElement.appendChild(dropdown);
            
            setupSingleAutocomplete(namaInput, dropdown, kelasInput, siswaData, 'siswa-ortu');
        }
    }
}

// Dynamic fields for Surat Perintah Tugas
const addGuruBtn = document.getElementById('add-guru');
const guruContainer = document.getElementById('guru-container');
if (addGuruBtn) {
    addGuruBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('guru-row', 'space-y-3', 'sm:space-y-0', 'sm:grid', 'sm:grid-cols-3', 'sm:gap-4', 'p-4', 'bg-gray-50', 'rounded-lg');
        newRow.innerHTML = `
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Nama Guru</label>
                <div class="relative">
                    <input type="text" name="nama_guru[]" placeholder="Ketik nama guru..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg guru-nama-input focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                </div>
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">NIP</label>
                <input type="text" name="nip_guru[]" placeholder="NIP" required readonly class="w-full px-3 py-3 border border-gray-300 rounded-lg guru-nip-input bg-gray-50 text-sm sm:text-base">
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Keterangan</label>
                <input type="text" name="keterangan_guru[]" placeholder="Keterangan (peran)" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
            </div>
            <div class="sm:col-span-3 flex justify-end">
                <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                    <i class="fas fa-times mr-1"></i>Hapus
                </button>
            </div>
        `;
        guruContainer.appendChild(newRow);
        
        // Setup autocomplete untuk row baru
        setupAutocompleteForNewRow(newRow, 'guru');
        
        // Add remove functionality
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });

        newRow.querySelector('.guru-nip-input').removeAttribute('readonly');
    });
}

// Dynamic fields for Surat Dispensasi - MANUAL INPUT ONLY
const addSiswaDispensasiBtn = document.getElementById('add-siswa-dispensasi');
const siswaDispensasiContainer = document.getElementById('siswa-dispensasi-container');
if (addSiswaDispensasiBtn) {
    addSiswaDispensasiBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('siswa-row', 'space-y-3', 'sm:space-y-0', 'sm:grid', 'sm:grid-cols-3', 'sm:gap-4', 'p-4', 'bg-gray-50', 'rounded-lg');
        newRow.innerHTML = `
            <div class="space-y-1 relative">
                <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                <input type="text" name="nama_siswa[]" placeholder="Masukkan nama siswa..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-nama-input">
                <div class="siswa-dropdown hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"></div>
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">NISN</label>
                <input type="text" name="nisn[]" placeholder="Masukkan NISN..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-nisn-input">
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                <input type="text" name="kelas_siswa[]" placeholder="Masukkan kelas..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base siswa-kelas-input">
            </div>
            <div class="sm:col-span-3 flex justify-end">
                <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                    <i class="fas fa-times mr-1"></i>Hapus
                </button>
            </div>
        `;
        siswaDispensasiContainer.appendChild(newRow);
        
        // Setup autocomplete for the new siswa row
        setupAutocompleteForNewRow(newRow, 'siswa');
        
        // Add remove functionality
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });
    });
}

// Dynamic fields for Surat Panggilan Orang Tua - Guru Ditemui
const addGuruDitemuiBtn = document.getElementById('add-guru-ditemui');
const guruDitemuiContainer = document.getElementById('guru-ditemui-container');
if (addGuruDitemuiBtn) {
    addGuruDitemuiBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('guru-ditemui-row', 'p-4', 'bg-gray-50', 'rounded-lg');
        newRow.innerHTML = `
            <div class="flex space-x-3">
                <div class="relative flex-1">
                    <input type="text" name="guru_ditemui[]" placeholder="Ketik nama guru..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                </div>
                <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        guruDitemuiContainer.appendChild(newRow);
        
        // Setup autocomplete untuk row baru
        setupAutocompleteForNewRow(newRow, 'guru-ditemui');
        
        // Add remove functionality
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });
    });
}

// Dynamic fields for Surat Panggilan Orang Tua - Siswa dan Ortu
const addSiswaOrtuBtn = document.getElementById('add-siswa-ortu');
const siswaOrtuContainer = document.getElementById('siswa-ortu-container');
if (addSiswaOrtuBtn) {
    addSiswaOrtuBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('siswa-ortu-row', 'space-y-3', 'sm:space-y-0', 'sm:grid', 'sm:grid-cols-3', 'sm:gap-4', 'p-4', 'bg-gray-50', 'rounded-lg');
        newRow.innerHTML = `
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                <div class="relative">
                    <input type="text" name="nama_siswa[]" placeholder="Ketik nama siswa..." required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                </div>
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                <input type="text" name="kelas[]" placeholder="Kelas otomatis" required class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm sm:text-base">
            </div>
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 sm:hidden">Nama Orang Tua</label>
                <input type="text" name="nama_ortu[]" placeholder="Nama Orang Tua" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
            </div>
            <div class="sm:col-span-3 flex justify-end">
                <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                    <i class="fas fa-times mr-1"></i>Hapus
                </button>
            </div>
        `;
        siswaOrtuContainer.appendChild(newRow);
        
        // Setup autocomplete untuk row baru
        setupAutocompleteForNewRow(newRow, 'siswa-ortu');
        
        // Add remove functionality
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });
    });
}

// Modal dan Search Guru functionality (untuk Data Pemohon)
if (document.getElementById('guru-modal')) {
    // Function untuk menampilkan modal
    function showModal() {
        document.getElementById('guru-modal').classList.remove('hidden');
        displayGuruList(guruData);
    }

    // Function untuk menyembunyikan modal
    function hideModal() {
        document.getElementById('guru-modal').classList.add('hidden');
        document.getElementById('search-input').value = '';
    }

    // Function untuk menampilkan daftar guru
    function displayGuruList(data) {
        const container = document.getElementById('guru-list');
        container.innerHTML = '';
        
        data.forEach(guru => {
            const item = document.createElement('div');
            item.className = 'p-3 border rounded-lg hover:bg-gray-50 cursor-pointer';
            item.innerHTML = `
                <div class="font-medium text-gray-900">${guru.nama}</div>
                <div class="text-sm text-gray-600">${guru.nip}</div>
            `;
            // use mousedown so selection works reliably on touch/blur
            item.addEventListener('mousedown', function(e){ e.preventDefault(); selectGuru(guru); });
            container.appendChild(item);
        });
    }

    // Function untuk memilih guru
    function selectGuru(guru) {
        document.getElementById('nama-input').value = guru.nama;
        document.getElementById('nip-input').value = guru.nip;
        // Tambahkan phone juga jika ada
        if (document.getElementById('phone-input')) {
            document.getElementById('phone-input').value = guru.phone || '';
        }
        hideModal();
    }

    // Event listeners
    const searchBtn1 = document.getElementById('search-guru-btn');
    const searchBtn2 = document.getElementById('search-guru-btn-2');
    const searchBtn3 = document.getElementById('search-guru-btn-3');
    const closeModalBtn = document.getElementById('close-modal');
    const searchInput = document.getElementById('search-input');
    const modal = document.getElementById('guru-modal');

    if (searchBtn1) searchBtn1.addEventListener('click', showModal);
    if (searchBtn2) searchBtn2.addEventListener('click', showModal);
    if (searchBtn3) searchBtn3.addEventListener('click', function() {
        // Untuk button phone, bisa buka modal atau focus ke input phone
        const phoneInput = document.getElementById('phone-input');
        if (phoneInput) {
            phoneInput.focus();
        }
    });
    if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = guruData.filter(guru => 
                guru.nama.toLowerCase().includes(searchTerm) || 
                guru.nip.includes(searchTerm) ||
                (guru.phone && guru.phone.includes(searchTerm))
            );
            displayGuruList(filtered);
        });
    }

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
    }
}

// Enhanced mobile touch experience
document.addEventListener('DOMContentLoaded', function() {
    // Add touch feedback for mobile devices
    const interactiveElements = document.querySelectorAll('button, input, select, textarea');
    
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (window.innerWidth <= 768) {
                this.style.transform = 'scale(0.98)';
            }
        });
        
        element.addEventListener('touchend', function() {
            if (window.innerWidth <= 768) {
                this.style.transform = 'scale(1)';
            }
        });
    });
    
    // Smooth scroll to validation errors
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('invalid', function(e) {
            e.preventDefault();
            const firstInvalid = form.querySelector(':invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstInvalid.focus();
            }
        }, true);
    }
});

// Prevent zoom on input focus for iOS
if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (this.style.fontSize !== '16px') {
                this.style.fontSize = '16px';
            }
        });
    });
}

// Hide dropdown saat klik di luar untuk semua dropdown
document.addEventListener('click', function(e) {
    // Hide semua dropdown autocomplete
    const dropdowns = document.querySelectorAll('.guru-dropdown, .siswa-dropdown, .guru-ditemui-dropdown, .siswa-ortu-dropdown, #autocomplete-dropdown, #phone-autocomplete-dropdown');
    dropdowns.forEach(dropdown => {
        const input = dropdown.previousElementSibling;
        if (input && !input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
        </script>
    </body>
    </html>
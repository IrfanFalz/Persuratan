
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
                <form method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                    <!-- Data Pemohon -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-3 flex-shrink-0"></i>
                            <span>Data Pemohon</span>
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" placeholder="Nama Lengkap"
                                    class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm sm:text-base">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <input type="text" name="nip" placeholder="Masukkan NIP" required
                                    class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                                <select name="mapel" required
                                        class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <option value="Matematika">Matematika</option>
                                    <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                                    <option value="Bahasa Inggris">Bahasa Inggris</option>
                                    <option value="Fisika">Fisika</option>
                                    <option value="Kimia">Kimia</option>
                                    <option value="Biologi">Biologi</option>
                                    <option value="Sejarah">Sejarah</option>
                                    <option value="Geografi">Geografi</option>
                                    <option value="Ekonomi">Ekonomi</option>
                                    <option value="Sosiologi">Sosiologi</option>
                                    <option value="PKN">PKN</option>
                                    <option value="Agama">Agama</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Seni Budaya">Seni Budaya</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="tel" name="phone" placeholder="Masukkan nomor telepon" required
                                    class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                            </div>
                        </div>
                    </div>

                    <!-- Form berdasarkan jenis surat -->
                    @if($letter_type === 'surat-perintah-tugas')
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
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Nama Guru</label>
                                                <input type="text" name="nama[]" placeholder="Nama Guru" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">NIP</label>
                                                <input type="text" name="nip[]" placeholder="NIP" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Keterangan</label>
                                                <input type="text" name="keterangan[]" placeholder="Keterangan (peran)" required
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

                    @elseif($letter_type === 'surat-dispensasi')
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
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                                                <input type="text" name="nama[]" placeholder="Nama Siswa" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">NISN</label>
                                                <input type="text" name="nisn[]" placeholder="NISN" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                                                <input type="text" name="kelas[]" placeholder="Kelas" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-siswa-dispensasi" class="w-full sm:w-auto px-4 py-2 text-blue-600 hover:text-blue-900 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center justify-center sm:justify-start">
                                        <i class="fas fa-plus mr-2"></i> Tambah Siswa
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($letter_type === 'surat-panggilan-ortu')
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-phone text-purple-600 mr-3 flex-shrink-0"></i>
                                <span>Detail Surat Panggilan Orang Tua</span>
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
                                    <h4 class="text-sm font-medium text-gray-700">Guru yang Ditemui</h4>
                                    <div id="guru-ditemui-container" class="space-y-4">
                                        <div class="guru-ditemui-row p-4 bg-gray-50 rounded-lg">
                                            <input type="text" name="guru_ditemui[]" placeholder="Nama Guru yang Ditemui" required
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                        </div>
                                    </div>
                                    <button type="button" id="add-guru-ditemui" class="w-full sm:w-auto px-4 py-2 text-blue-600 hover:text-blue-900 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center justify-center sm:justify-start">
                                        <i class="fas fa-plus mr-2"></i> Tambah Guru
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-700">Data Siswa dan Orang Tua (Nama Siswa, Kelas, Nama Orang Tua)</h4>
                                    <div id="siswa-ortu-container" class="space-y-4">
                                        <div class="siswa-ortu-row space-y-3 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-gray-50 rounded-lg">
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                                                <input type="text" name="nama_siswa[]" placeholder="Nama Siswa" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                                                <input type="text" name="kelas[]" placeholder="Kelas" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs text-gray-600 sm:hidden">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu[]" placeholder="Nama Orang Tua" required
                                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-siswa-ortu" class="w-full sm:w-auto px-4 py-2 text-blue-600 hover:text-blue-900 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center justify-center sm:justify-start">
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
                                <label class="block text-sm font-medium text-gray-700">Upload Dokumen (Opsional)@if ($letter_type === 'surat-perintah-tugas') - Termasuk Bukti Undangan @endif</label>
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

                    <!-- Persetujuan -->
                    <div class="bg-gray-50 p-4 sm:p-6 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="agreement" name="agreement" required 
                                class="mt-1 w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 flex-shrink-0">
                            <label for="agreement" class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                                <span class="font-medium">Pernyataan:</span> Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. Saya bersedia menerima sanksi apabila dikemudian hari terbukti data yang saya sampaikan tidak benar.
                            </label>
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
                            <input type="text" name="nama[]" placeholder="Nama Guru" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">NIP</label>
                            <input type="text" name="nip[]" placeholder="NIP" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">Keterangan</label>
                            <input type="text" name="keterangan[]" placeholder="Keterangan (peran)" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="sm:col-span-3 flex justify-end">
                            <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                                <i class="fas fa-times mr-1"></i>Hapus
                            </button>
                        </div>
                    `;
                    guruContainer.appendChild(newRow);
                    
                    // Add remove functionality
                    const removeBtn = newRow.querySelector('.remove-row');
                    removeBtn.addEventListener('click', function() {
                        newRow.remove();
                    });
                });
            }

            // Dynamic fields for Surat Dispensasi
            const addSiswaDispensasiBtn = document.getElementById('add-siswa-dispensasi');
            const siswaDispensasiContainer = document.getElementById('siswa-dispensasi-container');
            if (addSiswaDispensasiBtn) {
                addSiswaDispensasiBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.classList.add('siswa-row', 'space-y-3', 'sm:space-y-0', 'sm:grid', 'sm:grid-cols-3', 'sm:gap-4', 'p-4', 'bg-gray-50', 'rounded-lg');
                    newRow.innerHTML = `
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">Nama Siswa</label>
                            <input type="text" name="nama[]" placeholder="Nama Siswa" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">NISN</label>
                            <input type="text" name="nisn[]" placeholder="NISN" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                            <input type="text" name="kelas[]" placeholder="Kelas" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="sm:col-span-3 flex justify-end">
                            <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                                <i class="fas fa-times mr-1"></i>Hapus
                            </button>
                        </div>
                    `;
                    siswaDispensasiContainer.appendChild(newRow);
                    
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
                            <input type="text" name="guru_ditemui[]" placeholder="Nama Guru yang Ditemui" required class="flex-1 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                            <button type="button" class="remove-row text-red-600 hover:text-red-800 px-3 py-1 text-sm flex-shrink-0">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    guruDitemuiContainer.appendChild(newRow);
                    
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
                            <input type="text" name="nama_siswa[]" placeholder="Nama Siswa" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 sm:hidden">Kelas</label>
                            <input type="text" name="kelas[]" placeholder="Kelas" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
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
                    
                    // Add remove functionality
                    const removeBtn = newRow.querySelector('.remove-row');
                    removeBtn.addEventListener('click', function() {
                        newRow.remove();
                    });
                });
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
        </script>
    </body>
    </html>
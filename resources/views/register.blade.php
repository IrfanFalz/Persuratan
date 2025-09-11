<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Persuratan - SMA Negeri 1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f0f4ff, #e0e7ff);
            transition: all 0.3s ease;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }
        /* Button global - KECUALI password toggle */
        button:not(.password-toggle-btn) {
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:not(.password-toggle-btn):hover {
            transform: scale(1.05);
        }
        
        /* Password toggle button - NO HOVER EFFECTS tapi ada click effect */
        .password-toggle-btn {
            transition: none !important;
            transform: translateY(-50%) !important;
        }
        .password-toggle-btn:hover {
            transform: translateY(-50%) !important;
            background: none !important;
            color: inherit !important;
            scale: none !important;
        }
        .password-toggle-btn:hover i {
            transform: none !important;
        }
        /* Click effect untuk password toggle */
        .password-toggle-btn:active {
            transform: translateY(-50%) scale(0.95) !important;
            transition: transform 0.1s ease !important;
        }
        .password-toggle-btn i {
            transition: opacity 0.2s ease;
        }
        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }
        .password-match {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        .password-mismatch {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#0f172a'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 mb-4">
                    <img src="{{ asset('images/logo grf.png') }}" 
                        alt="Logo SMK Negeri 4 Malang" 
                        class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun</h1>
                <p class="text-gray-600">Sistem persuratan SMK Negeri 4 Malang</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 card">
                <form id="registerForm" class="space-y-5">
                    <!-- Error/Success Messages -->
                    <div id="message" class="hidden"></div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-blue-500"></i>NIP
                        </label>
                        <input type="text" name="nip" id="nip" required
                               placeholder="Masukkan NIP"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-blue-500"></i>Role
                        </label>
                        <select name="role" id="role" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white">
                            <option value="" disabled selected hidden>Pilih Role</option>
                            <option value="GURU">Guru</option>
                            <option value="TU">Tata Usaha (TU)</option>
                            <option value="KEPSEK">Kepala Sekolah</option>
                        </select>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-at mr-2 text-blue-500"></i>Username
                        </label>
                        <input type="text" name="username" id="username" required
                               placeholder="Masukkan username"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   placeholder="Masukkan password"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <button type="button" onclick="togglePassword('password', 'togglePassword1')" 
                                    class="password-toggle-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i id="togglePassword1" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirm_password" required
                                   placeholder="Konfirmasi password"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <button type="button" onclick="togglePassword('confirm_password', 'togglePassword2')" 
                                    class="password-toggle-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i id="togglePassword2" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-2 text-sm hidden">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            <span class="text-green-600">Password cocok</span>
                        </div>
                        <div id="passwordMismatch" class="mt-2 text-sm hidden">
                            <i class="fas fa-times-circle text-red-500 mr-1"></i>
                            <span class="text-red-600">Password tidak cocok</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-1 transition duration-200 font-semibold shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition duration-200">
                            Masuk di sini
                        </a>
                    </p>
                </div>

                <!-- Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-semibold mb-1">Informasi Pendaftaran:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Gunakan NIP yang valid</li>
                                <li>Password minimal 6 karakter</li>
                                <li>Username harus unik</li>
                                <li>Pilih role sesuai jabatan Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-gray-500">
                <p>&copy; 2025 SMK Negeri 4 Malang. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('passwordMatch');
            const mismatchDiv = document.getElementById('passwordMismatch');
            const confirmInput = document.getElementById('confirm_password');

            if (confirmPassword === '') {
                matchDiv.classList.add('hidden');
                mismatchDiv.classList.add('hidden');
                confirmInput.classList.remove('password-match', 'password-mismatch');
                return;
            }

            if (password === confirmPassword) {
                matchDiv.classList.remove('hidden');
                mismatchDiv.classList.add('hidden');
                confirmInput.classList.add('password-match');
                confirmInput.classList.remove('password-mismatch');
            } else {
                matchDiv.classList.add('hidden');
                mismatchDiv.classList.remove('hidden');
                confirmInput.classList.add('password-mismatch');
                confirmInput.classList.remove('password-match');
            }
        }

        // Add event listeners
        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

        // Form validation and submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            const messageDiv = document.getElementById('message');

            // Basic validation
            if (data.password !== data.confirm_password) {
                showMessage('Password dan konfirmasi password tidak cocok!', 'error');
                return;
            }

            if (data.password.length < 6) {
                showMessage('Password minimal 6 karakter!', 'error');
                return;
            }

            // Simulate registration process
            showMessage('Sedang memproses pendaftaran...', 'info');
            
            setTimeout(() => {
                // Simulate success
                showMessage('Pendaftaran berhasil! Anda akan dialihkan ke halaman login...', 'success');
                
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 2000);
            }, 1500);
        });

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = 'px-4 py-3 rounded-lg flex items-center';
            
            switch(type) {
                case 'success':
                    messageDiv.className += ' bg-green-50 border border-green-200 text-green-700';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + message;
                    break;
                case 'error':
                    messageDiv.className += ' bg-red-50 border border-red-200 text-red-700';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + message;
                    break;
                case 'info':
                    messageDiv.className += ' bg-blue-50 border border-blue-200 text-blue-700';
                    messageDiv.innerHTML = '<i class="fas fa-info-circle mr-2"></i>' + message;
                    break;
            }
            
            messageDiv.classList.remove('hidden');
            
            // Auto hide info/error messages after 5 seconds
            if (type === 'error' || type === 'info') {
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                }, 3000);
            }
        }

        // Auto-generate username from nama lengkap
        document.getElementById('nama_lengkap').addEventListener('input', function(e) {
            const nama = e.target.value;
            const username = nama.toLowerCase()
                              .replace(/\s+/g, '')
                              .replace(/[^a-z0-9]/g, '')
                              .substring(0, 15);
            
            if (username) {
                document.getElementById('username').value = username;
            }
        });

        // Format NIP input (numbers only)
        document.getElementById('nip').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Responsive adjustments
        function adjustLayout() {
            const card = document.querySelector('.card');
            if (window.innerWidth < 640) {
                card.classList.add('mx-2');
                card.style.padding = '1.5rem';
            } else {
                card.classList.remove('mx-2');
                card.style.padding = '2rem';
            }
        }

        window.addEventListener('load', adjustLayout);
        window.addEventListener('resize', adjustLayout);
    </script>
</body>
</html>
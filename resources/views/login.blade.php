<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Persuratan - SMK Negeri 4 Malang</title>
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
        button {
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            transform: scale(1.05);
        }
        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
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
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistem Persuratan</h1>
                <p class="text-gray-600">SMK Negeri 4 Malang</p>
            </div>

            

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 card">
                <form method="POST" class="space-y-6" action="{{ route('login') }}">
                    @csrf
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>NIP
                        </label>
                        <input type="text" name="username" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-1 transition duration-200 font-semibold shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </form>
                
                <!-- Divider -->
                <!-- <div class="my-6 flex items-center">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="mx-4 text-gray-500 text-sm">atau</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>-->

                <!-- Register Link -->
                <!-- <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition duration-200">
                            Daftar di sini
                        </a>
                    </p>
                </div> -->

                

                <!-- Demo Accounts 
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 text-center">
                        <i class="fas fa-users mr-2 text-gray-500"></i>Demo Akun:
                    </h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-white p-2 rounded border hover:shadow-sm transition">
                            <div class="font-semibold text-blue-600">KTU</div>
                            <div class="text-gray-600">ktu / ktu123</div>
                        </div>
                        <div class="bg-white p-2 rounded border hover:shadow-sm transition">
                            <div class="font-semibold text-green-600">TU</div>
                            <div class="text-gray-600">tu / tu123</div>
                        </div>
                        <div class="bg-white p-2 rounded border hover:shadow-sm transition">
                            <div class="font-semibold text-purple-600">Kepsek</div>
                            <div class="text-gray-600">kepsek / kepsek123</div>
                        </div>
                        <div class="bg-white p-2 rounded border hover:shadow-sm transition">
                            <div class="font-semibold text-orange-600">Guru</div>
                            <div class="text-gray-600">guru1 / guru123</div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Footer -->
            <div class="text-center mt-8 text-sm text-gray-500">
                <p>&copy; 2025 SMK Negeri 4 Malang. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
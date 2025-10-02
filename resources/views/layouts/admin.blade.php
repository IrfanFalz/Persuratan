<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - SIMS Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b fixed w-full top-0 z-20">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Mobile menu button -->
                <div class="flex items-center">
                    <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center ml-2 lg:ml-0">
                        <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">SIMS Sekolah</h1>
                            <p class="text-xs text-gray-600 hidden sm:block">Sistem Informasi Manajemen Surat</p>
                        </div>
                    </div>
                </div>

                <!-- User info -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ session('name') }}</p>
                            <p class="text-xs text-gray-600">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex pt-16 h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out pt-16 lg:pt-0">
            <div class="flex flex-col h-full overflow-hidden">
                <!-- User info mobile -->
                <div class="lg:hidden p-4 border-b bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ session('name') }}</p>
                            <p class="text-sm text-gray-600">Administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard.admin') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 {{ request()->routeIs('dashboard.admin') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.kelola-guru') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 {{ request()->routeIs('admin.kelola-guru') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-users mr-3 text-lg"></i>
                        <span class="font-medium">Kelola Data Guru</span>
                    </a>
                    
                    <a href="{{ route('admin.kelola-surat') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 {{ request()->routeIs('admin.kelola-surat') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-file-alt mr-3 text-lg"></i>
                        <span class="font-medium">Kelola Surat</span>
                    </a>
                    
                    <a href="{{ route('admin.history-surat') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 {{ request()->routeIs('admin.history-surat') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-history mr-3 text-lg"></i>
                        <span class="font-medium">History Surat</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t flex-shrink-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0 overflow-y-auto">
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        mobileMenuButton.addEventListener('click', toggleMobileMenu);
        overlay.addEventListener('click', toggleMobileMenu);

        // Close sidebar when clicking on a link on mobile
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    toggleMobileMenu();
                }
            });
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
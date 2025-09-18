<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Sistem Surat Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-effect {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glass-sidebar {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }
        .card-shadow {
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.08), 0 2px 6px -2px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .soft-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .accent-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #1d4ed8 100%);
        }
        .text-accent {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
        }
        .sidebar-link {
            position: relative;
            overflow: hidden;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(37, 99, 235, 0.1);
            transform: translateX(4px);
        }
        .sidebar-link:hover::before {
            transform: scaleY(1);
        }
        .sidebar-link.active {
            background: rgba(37, 99, 235, 0.15);
            color: #1d4ed8;
            font-weight: 500;
        }
        .sidebar-link.active::before {
            transform: scaleY(1);
        }
        .profile-avatar {
            transition: all 0.3s ease;
        }
        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }
        .logo-container {
            transition: all 0.3s ease;
        }
        .logo-container img:hover {
            transform: scale(1.02);
        }
        @media (max-width: 768px) {
            .sidebar-link span {
                font-size: 0.9rem;
            }
        }
        /* Smooth loading animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        /* Mobile optimization */
        @media (max-width: 640px) {
            .glass-effect {
                backdrop-filter: blur(8px);
            }
            .sidebar-link {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-100 min-h-screen">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="lg:hidden fixed top-3 left-3 z-50 p-2.5 rounded-xl glass-effect shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 glass-sidebar sidebar-transition transform -translate-x-full lg:translate-x-0 shadow-xl">
        <div class="flex flex-col h-full">
            <!-- Logo/Header -->
            <div class="logo-container flex items-center justify-center px-6 py-8 accent-gradient shadow-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white/25 backdrop-blur-sm flex items-center justify-center overflow-hidden shadow-md">
                        <img src="{{ asset('images/logo grf.png') }}" alt="Logo Sekolah" class="w-10 h-10 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-10 h-10 rounded-lg bg-white/20 items-center justify-center text-white font-bold text-lg hidden">
                            S
                        </div>
                    </div>
                    <div class="text-white">
                        <h1 class="text-lg font-bold leading-tight drop-shadow-sm">Sistem Surat</h1>
                        <p class="text-sm opacity-95 font-medium">Admin Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard.admin') }}" class="sidebar-link flex items-center px-4 py-3.5 text-slate-700 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                    <div class="w-5 h-5 mr-4 flex-shrink-0">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v1H8V5z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.kelola-guru') }}" class="sidebar-link flex items-center px-4 py-3.5 text-slate-700 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.kelola-guru') ? 'active' : '' }}">
                    <div class="w-5 h-5 mr-4 flex-shrink-0">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Kelola Data Guru</span>
                </a>

                <a href="{{ route('admin.kelola-surat') }}" class="sidebar-link flex items-center px-4 py-3.5 text-slate-700 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.kelola-surat') ? 'active' : '' }}">
                    <div class="w-5 h-5 mr-4 flex-shrink-0">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Kelola Surat</span>
                </a>

                <a href="{{ route('admin.history-surat') }}" class="sidebar-link flex items-center px-4 py-3.5 text-slate-700 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.history-surat') ? 'active' : '' }}">
                    <div class="w-5 h-5 mr-4 flex-shrink-0">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">History Surat</span>
                </a>

                <div class="pt-4 mt-6 border-t border-slate-200/50">
                    <a href="{{ route('logout') }}" class="sidebar-link flex items-center px-4 py-3.5 text-red-600 rounded-xl transition-all duration-300 hover:bg-red-50/80">
                        <div class="w-5 h-5 mr-4 flex-shrink-0">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-slate-900/40 opacity-0 invisible lg:hidden transition-all duration-300 backdrop-blur-sm"></div>

    <!-- Main Content -->
    <div class="lg:pl-72">
        <!-- Header -->
        <header class="glass-effect sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                <div class="flex items-center ml-12 lg:ml-0">
                    <h2 class="text-xl font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:block text-right">
                        <div class="text-sm font-semibold text-slate-700">{{ session('name', 'Administrator') }}</div>
                        <div class="text-xs text-slate-500">{{ session('role', 'ADMIN') }}</div>
                    </div>
                    <div class="profile-avatar w-10 h-10 rounded-xl overflow-hidden ring-2 ring-white shadow-lg cursor-pointer">
                        <img src="{{ asset('images/pp.png') }}" alt="Profile Picture" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full accent-gradient items-center justify-center text-white font-semibold text-sm hidden">
                            {{ substr(session('name', 'A'), 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8">
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('opacity-0');
            overlay.classList.toggle('invisible');
        }

        mobileMenuButton.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('opacity-0', 'invisible');
            }
        });

        // Enhanced loading animations
        document.addEventListener('DOMContentLoaded', function() {
            // Staggered animation for cards
            const cards = document.querySelectorAll('.card-shadow');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        // Add touch support for mobile devices
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
        }

        // Performance optimization: Reduce animations on low-end devices
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
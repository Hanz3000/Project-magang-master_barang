<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Master Data')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- JS Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-gradient: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --glow-primary: rgba(102, 126, 234, 0.4);
            --glow-secondary: rgba(118, 75, 162, 0.3);
        }

        body {
            background: #e3f2fd;
            background-attachment: fixed;
            position: relative;
        }

        .sidebar-gradient {
            background: var(--sidebar-gradient);
            position: relative;
            overflow: hidden;
        }

        .sidebar-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg,
                    transparent 30%,
                    rgba(255, 255, 255, 0.05) 50%,
                    transparent 70%);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .hover-glow {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .hover-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .hover-glow:hover::before {
            transform: translateX(100%);
        }

        .hover-glow:hover {
            box-shadow:
                0 0 30px var(--glow-primary),
                0 10px 25px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transform: translateY(-2px) scale(1.02);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: left 0.5s ease;
        }

        .menu-item:hover::after {
            left: 100%;
        }

        .menu-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .icon-glow {
            filter: drop-shadow(0 0 8px currentColor);
            transition: all 0.3s ease;
        }

        .menu-item:hover .icon-glow {
            filter: drop-shadow(0 0 12px currentColor) brightness(1.2);
            transform: scale(1.1) rotate(5deg);
        }

        .slide-in-right {
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%) scale(0.9);
                opacity: 0;
            }

            to {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow:
                0 0 20px rgba(102, 126, 234, 0.4),
                0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow:
                0 0 30px rgba(102, 126, 234, 0.6),
                0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .toggle-btn {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .toggle-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .toggle-btn:hover::before {
            width: 100px;
            height: 100px;
        }

        .toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .submenu-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            transition: height 0.3s ease;
            transform: translateY(-50%);
        }

        .submenu-item:hover::before {
            height: 100%;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1rem;
            transform: translateX(5px);
        }

        .main-content {
            background: transparent;
            backdrop-filter: none;
        }

        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        .particle:nth-child(odd) {
            animation-delay: -3s;
            background: rgba(102, 126, 234, 0.4);
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes spin-reverse {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        .animate-spin-reverse {
            animation: spin-reverse 1s linear infinite;
        }

        .animate-progress {
            animation: progress 3s ease-in-out infinite;
        }

        .content-area {
            background: #fff;
            backdrop-filter: none;
            border-radius: 20px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(33, 150, 243, 0.08);
            position: relative;
            overflow: hidden;
        }

        .content-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.5), transparent);
        }

        .auth-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .auth-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .auth-button:hover::before {
            left: 100%;
        }

        .auth-button:hover {
            transform: translateY(-1px);
            text-shadow: 0 0 8px currentColor;
        }

        .user-avatar {
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), transparent);
            padding-left: 1.25rem;
        }

        /* Animasi untuk teks sidebar */
        .sidebar-text-enter {
            opacity: 0;
            transform: translateX(-10px);
        }

        .sidebar-text-enter-active {
            opacity: 1;
            transform: translateX(0);
            transition: all 300ms ease-out;
        }

        .sidebar-text-exit {
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar-text-exit-active {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 200ms ease-in;
        }

        /* Optimasi untuk sidebar kecil */
        .sidebar-small .menu-item {
            padding: 0.75rem 0;
            justify-content: center;
        }

        .sidebar-small .menu-icon {
            margin: 0 auto;
        }

        .swal2-toast {
            animation: slideInRight 0.5s ease-out, fadeOut 0.5s ease-out 2.5s forwards !important;
            border-left: 4px solid;
            border-radius: 0.375rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        .swal2-toast.swal2-success {
            border-left-color: #10B981 !important;
        }

        .swal2-toast.swal2-info {
            border-left-color: #3B82F6 !important;
        }

        .swal2-toast.swal2-warning {
            border-left-color: #EF4444 !important;
        }

        .swal2-toast.swal2-error {
            border-left-color: #DC2626 !important;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="min-h-screen"
    x-data="{ sidebarOpen: true, showWelcome: {{ session('show_welcome', false) ? 'true' : 'false' }} }"
    x-init="if(showWelcome){ setTimeout(() => showWelcome = false, 3000); }">
    <!-- Welcome Animation -->
    <div x-show="showWelcome" x-transition:leave="transition ease-in duration-300"
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#e3f2fd]">
        <div class="text-center space-y-6">
            <div
                class="mx-auto w-24 h-24 border-4 border-blue-200 border-t-transparent rounded-full animate-spin flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-blue-300 border-b-transparent rounded-full animate-spin-reverse">
                </div>
            </div>
            <div class="space-y-2">
                <h2 class="text-3xl font-bold tracking-tight text-blue-800">Selamat Datang</h2>
                <p class="text-blue-500 font-medium">Sistem Master Data</p>
            </div>
            <div class="pt-4">
                <div class="h-1.5 w-40 mx-auto bg-blue-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-400 rounded-full animate-progress"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- Hapus session setelah welcome tampil --}}
    @if(session('show_welcome'))
    {{ session()->forget('show_welcome') }}
    @endif

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="sidebar-gradient text-white shadow-2xl transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] relative flex flex-col overflow-hidden"
            :class="sidebarOpen ? 'w-64 px-4' : 'w-20 px-2'">

            <!-- Header Logo -->
            <div class="flex items-center p-4 border-b border-white/20">
                <div class="flex items-center gap-4 w-full" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <div class="logo-container p-2 rounded-xl hover:rotate-6 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white icon-glow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="overflow-hidden">
                        <h1
                            class="text-xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            <span class="block">Admin Panel</span>
                            <span class="block text-sm font-medium text-white/80">Kontrol Data Master</span>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="py-4 px-1 flex-1 overflow-y-auto">
                <ul class="space-y-2 text-sm">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ url('/dashboard') }}"
                            class="group flex items-center gap-3 px-2 py-3 text-white rounded-lg hover-glow transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-600/30 hover:to-purple-600/30">
                            <div
                                class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-md group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:leave="transition ease-in duration-200"
                                class="whitespace-nowrap text-left">
                                <span class="font-medium block">Dashboard</span>
                                <p class="text-xs text-slate-300">Beranda sistem</p>
                            </div>
                        </a>
                    </li>

                    <!-- Manajemen Struk -->


                    <!-- Master Section -->
                    <li x-data="{ open: false }">
                        <button @click="sidebarOpen = true; open = !open"
                            class="group flex items-center gap-3 px-2 py-3 text-white rounded-lg w-full hover-glow transition-all duration-200 hover:bg-gradient-to-r hover:from-purple-600/30 hover:to-pink-600/30">
                            <div
                                class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-md group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-250"
                                x-transition:leave="transition ease-in duration-200"
                                class="flex-1 whitespace-nowrap text-left">
                                <span class="font-medium block">Master Data</span>
                                <p class="text-xs text-slate-300">Kelola data master</p>
                            </div>
                            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-300"
                                x-transition:leave="transition ease-in duration-200">
                                <svg :class="{ 'rotate-180': open }"
                                    class="w-4 h-4 text-white transform transition-transform duration-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <!-- Submenu -->
                        <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2" class="ml-11 mt-2 space-y-2 text-sm">
                            <a href="{{ route('barang.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-md transition-all duration-200">
                                <div
                                    class="w-2 h-2 bg-blue-400 rounded-full group-hover:scale-110 transition-transform duration-200">
                                </div>
                                <span class="whitespace-nowrap">Master Barang</span>
                            </a>
                            <a href="{{ route('pegawai.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-md transition-all duration-200">
                                <div
                                    class="w-2 h-2 bg-green-400 rounded-full group-hover:scale-110 transition-transform duration-200">
                                </div>
                                <span class="whitespace-nowrap">Master Pegawai</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 h-screen overflow-y-auto main-content">
            <header class="header-glass px-6 py-4 shadow-lg sticky top-0 z-40">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="toggle-btn p-3 rounded-xl shadow-md relative z-10">
                            <svg class="w-6 h-6 text-slate-600 relative z-10" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                        <h2
                            class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            Sistem Master Data
                        </h2>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                                <div class="relative">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                                    </div>
                                </div>
                                <span
                                    class="hidden md:inline-block font-medium text-slate-700">{{ Auth::user()->name }}</span>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-slate-500 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden">
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b border-slate-100">
                                        <p class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}"
                            class="auth-button px-6 py-2 text-blue-600 hover:text-blue-700 font-semibold rounded-lg">
                            Login
                        </a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-6 min-h-full">
                <div class="content-area p-6 fade-in">


                    <!-- Main Content -->
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Floating Particles Background -->
    <div class="floating-particles">
        @for ($i = 0; $i < 20; $i++) <div class="particle" style="
                left: {{ rand(0, 100) }}%;
                animation-duration: {{ rand(5, 15) }}s;
                animation-delay: {{ rand(0, 5) }}s;
                width: {{ rand(2, 6) }}px;
                height: {{ rand(2, 6) }}px;
            ">
    </div>
    @endfor
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Fungsi untuk notifikasi
                function showActionNotification(action, message) {
                    const settings = {
                        create: {
                            icon: 'success',
                            title: 'Data Berhasil Ditambahkan',
                            color: '#10B981'
                        },
                        update: {
                            icon: 'info',
                            title: 'Data Berhasil Diperbarui',
                            color: '#3B82F6'
                        },
                        delete: {
                            icon: 'warning',
                            title: 'Data Berhasil Dihapus',
                            color: '#EF4444'
                        },
                        error: {
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            color: '#DC2626'
                        }
                    };

                    const config = settings[action] || {
                        icon: 'info',
                        title: 'Notifikasi'
                    };

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: 'white',
                        iconColor: config.color,
                        color: '#1F2937',
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: config.icon,
                        title: config.title,
                        text: message
                    });
                }

                // Debug session
                console.log("Checking for notifications...");

                // Handle notifications
                @if(session('created'))
                console.log("Found created notification");
                showActionNotification('create', '{{ session('
                    created ') }}');
                @endif

                @if(session('updated'))
                console.log("Found updated notification");
                showActionNotification('update', '{{ session('
                    updated ') }}');
                @endif

                @if(session('deleted'))
                console.log("Found deleted notification");
                showActionNotification('delete', '{{ session('
                    deleted ') }}');
                @endif

                @if(session('error'))
                console.log("Found error notification");
                showActionNotification('error', '{{ session('
                    error ') }}');
                @endif

            } catch (error) {
                console.error("Notification error:", error);
            }
        });

        // Fungsi konfirmasi delete
        function confirmDelete(event, itemName = 'data') {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: `Hapus ${itemName}?`,
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: 'white'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>
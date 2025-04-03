
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel de Administración') - {{ config('app.name') }}</title>


    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
        .sidebar-active {
            border-left: 4px solid #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex">

<div class="bg-white w-64 shadow-md flex-shrink-0 hidden md:block">
    <div class="py-6 px-4 border-b border-gray-200">
        <div class="flex items-center justify-center">
            <i class="fas fa-share-alt text-blue-600 text-xl mr-2"></i>
            <span class="text-lg font-semibold">{{ config('app.name') }}</span>
        </div>
        <div class="text-gray-500 text-xs text-center mt-1">Panel de Administración</div>
    </div>
    <div class="py-4">
        <ul>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.links.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.links.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-link w-6"></i>
                    <span>Enlaces</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tags.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.tags.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tag w-6"></i>
                    <span>Categorías</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.comments.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-comment w-6"></i>
                    <span>Comentarios</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.imports.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.imports.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-rss w-6"></i>
                    <span>Importaciones</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appearance.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.appearance.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-paint-brush w-6"></i>
                    <span>Apariencia</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-cogs w-6"></i>
                    <span>Configuración</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stats.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.stats.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span>Estadísticas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.activitypub.index') }}" class="flex items-center py-3 px-4 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.activitypub.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-globe w-6"></i>
                    <span>ActivityPub</span>
                </a>
            </li>
        </ul>
    </div>
</div>


<div class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center">
                <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-lg font-medium ml-4">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center">
                <div class="mr-4 relative">
                    @if(isset($notificationsCount) && $notificationsCount > 0)
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center">
                                {{ $notificationsCount }}
                            </span>
                    @endif
                    <button class="text-gray-500 focus:outline-none">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
                <div class="relative">
                    <button id="userDropdown" class="flex items-center focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            {{ Auth::user()->username[0] ?? 'A' }}
                        </div>
                        <span class="ml-2">{{ Auth::user()->username ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>

                    <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <!--route('users.show', Auth::user()->username ?? 'admin')-->
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi perfil</a>
                        <!--route('settings.account')-->
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configuración</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>


    @hasSection('tabs')
        <div class="px-6 py-4 bg-white shadow-sm">
            <div class="flex space-x-4 overflow-x-auto pb-1">
                @yield('tabs')
            </div>
        </div>
    @endif


    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <p>{{ session('info') }}</p>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>


<script>

    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        const sidebar = document.querySelector('.md\\:block');
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('fixed');
        sidebar.classList.toggle('inset-0');
        sidebar.classList.toggle('z-40');
    });


    document.getElementById('userDropdown')?.addEventListener('click', function() {
        document.getElementById('userDropdownMenu').classList.toggle('hidden');
    });


    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdownMenu');
        const button = document.getElementById('userDropdown');
        if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>

@stack('scripts')
</body>
</html>

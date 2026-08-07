<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Phone Station Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.tailwind')
</head>
<body class="bg-[#f5f7fa] text-ink font-sans antialiased">

@php
    $user = auth()->user();
    $nav = collect([
        ['label' => 'Dashboard', 'icon' => 'chart', 'route' => 'admin.dashboard', 'permission' => 'dashboard.view'],
        ['label' => 'Products', 'icon' => 'box', 'route' => 'admin.products.index', 'permission' => 'products.manage'],
        ['label' => 'Categories', 'icon' => 'folder', 'route' => 'admin.categories.index', 'permission' => 'categories.manage'],
        ['label' => 'Orders', 'icon' => 'bag', 'route' => 'admin.orders.index', 'permission' => 'orders.manage'],
        ['label' => 'Users', 'icon' => 'users', 'route' => 'admin.users.index', 'permission' => 'users.manage'],
        ['label' => 'Permissions', 'icon' => 'key', 'route' => 'admin.permissions.index', 'permission' => 'permissions.manage'],
    ])->filter(fn ($item) => $user->hasPermission($item['permission']))->values();
    $active = request()->route()?->getName();
    $icons = [
        'chart' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>',
        'box' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05"/><path d="M12 22.08V12"/></svg>',
        'folder' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
        'bag' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'users' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'key' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>',
    ];
@endphp

<div class="min-h-screen lg:flex">

    {{-- SIDEBAR --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-line flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
        <div class="h-20 px-6 flex items-center gap-3 border-b border-line">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Phone Station" class="h-9 w-9 rounded-full object-cover">
            <div class="leading-tight">
                <p class="font-semibold text-sm">Phone Station</p>
                <p class="text-xs text-ash">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-5 px-4">
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-ash">Menu</p>
            <ul class="space-y-1">
                @foreach ($nav as $item)
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $active === $item['route'] ? 'bg-mist text-brand' : 'text-muted hover:text-brand hover:bg-mist' }}">
                            {!! $icons[$item['icon']] !!}
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="px-4 pb-5 space-y-1 border-t border-line pt-4">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-muted hover:text-brand hover:bg-mist rounded-lg transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                View Store
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-muted hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-40 h-16 bg-white border-b border-line flex items-center justify-between gap-4 px-4 lg:px-8">
            <div class="flex items-center gap-4 min-w-0">
                <button id="admin-sidebar-toggle" class="lg:hidden p-2 border border-field rounded-lg" aria-label="Toggle menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>
                <div class="truncate">
                    <p class="text-xs text-ash">Dashboards / <span class="text-ink font-medium">{{ $active ? ucwords(str_replace('.', ' ', preg_replace('/^admin\./', '', $active))) : 'Home' }}</span></p>
                    <p class="font-semibold text-sm hidden sm:block">{{ $active ? ucwords(str_replace('.', ' ', preg_replace('/^admin\./', '', $active))) : 'Home' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">
                <a href="{{ route('products.index') }}" target="_blank"
                   class="hidden md:inline-block text-xs font-semibold text-brand border border-brand px-4 py-2 rounded-lg hover:bg-brand hover:text-white transition-colors">
                    + New Sale
                </a>
                <a href="{{ route('admin.orders.index') }}" aria-label="Orders"
                   class="relative text-muted hover:text-brand transition-colors">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if ($user->hasPermission('orders.manage') && \App\Models\Order::query()->where('status', 'placed')->exists())
                        <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-brand"></span>
                    @endif
                </a>
                <div class="flex items-center gap-3 pl-3 sm:pl-5 border-l border-line">
                    <div class="w-9 h-9 rounded-full bg-brand text-white flex items-center justify-center font-semibold text-sm shrink-0">
                        {{ str($user->name)->substr(0, 1)->upper() }}
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-sm font-semibold">{{ $user->name }}</p>
                        <p class="text-xs text-ash capitalize">{{ $user->role }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- FLASHES --}}
        @if (session('status') || session('error'))
            <div class="px-4 lg:px-8 pt-4">
                <div class="px-4 py-3 text-sm font-medium rounded-lg {{ session('error') ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                    {{ session('error') ?? session('status') }}
                </div>
            </div>
        @endif

        <main class="flex-1 px-4 lg:px-8 py-6">
            @yield('content')
        </main>

        <footer class="px-4 lg:px-8 py-5 border-t border-line text-xs text-ash flex flex-wrap items-center justify-between gap-2">
            <p>© {{ date('Y') }} Phone Station. All rights reserved.</p>
            <p>No 131 Iwo Road, Opposite Item 7go, Iwo Road, Ibadan</p>
        </footer>
    </div>
</div>

@stack('scripts')

<script>
    document.getElementById('admin-sidebar-toggle')?.addEventListener('click', function () {
        document.getElementById('admin-sidebar').classList.toggle('-translate-x-full');
    });
</script>
</body>
</html>

@props([])

@php
    $cartCount = array_sum(session('cart', []));
    $user = auth()->user();
    $links = [
        ['label' => 'Shop All', 'href' => route('products.index')],
        ['label' => 'Flagships', 'href' => route('products.index', ['category' => 'flagships'])],
        ['label' => 'Foldables', 'href' => route('products.index', ['category' => 'foldables'])],
        ['label' => 'Budget', 'href' => route('products.index', ['category' => 'budget'])],
        ['label' => 'Gaming', 'href' => route('products.index', ['category' => 'gaming'])],
    ];
@endphp

<header class="relative z-[60]">
    {{-- TOP BAR --}}
    <div class="bg-ink text-white">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 h-10 flex items-center justify-between gap-8 text-xs font-semibold">
            <a href="mailto:phonestation31@gmail.com" class="hidden lg:block hover:underline underline-offset-2">
                No 131 Iwo Road, Opposite Item 7go, Iwo Road, Ibadan <span class="text-brand">Contact Us</span>
            </a>

            <div class="flex items-center gap-8 w-full lg:w-auto justify-between lg:justify-end">
                <span class="hidden md:block">Mon-Sat: 9:00 AM - 6:00 PM</span>
                <span>Call Us: +234 701 111 1499</span>
                <span class="flex items-center gap-3">
                    <a href="#" aria-label="Facebook" class="hover:opacity-70 transition-opacity">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127c-.292-.04-1.292-.126-2.455-.126-2.429 0-4.095 1.483-4.095 4.207v2.384H7.319v3.209h2.799v8.196h3.279z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="hover:opacity-70 transition-opacity">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN NAV --}}
    <nav class="bg-white border-b border-line sticky top-0 shadow-[0_1px_0_0_rgba(2,2,3,0.04)]">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 flex items-center justify-between gap-8 h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Phone Station" class="h-10 w-auto object-contain">
                <span class="hidden sm:block font-semibold text-xl tracking-tight">Phone Station</span>
            </a>

            <ul class="hidden xl:flex items-center gap-8">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}"
                           class="font-semibold text-sm text-ink hover:text-brand transition-colors duration-200">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ route('products.index') }}" class="font-semibold text-sm text-brand">
                        Our Deals
                    </a>
                </li>
            </ul>

            <div class="flex items-center gap-7">
                @auth
                    @if (auth()->user()->hasPermission('dashboard.view'))
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-block text-xs font-semibold text-brand border border-brand px-4 py-2 rounded-lg hover:bg-brand hover:text-white transition-colors">
                            Admin
                        </a>
                    @endif

                    <a href="{{ route('products.index') }}" aria-label="Search"
                       class="hidden sm:block text-ink hover:text-brand transition-colors duration-200">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11" cy="11" r="7"/>
                            <line x1="16.5" y1="16.5" x2="21" y2="21"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-block text-xs font-semibold text-ink border border-ink px-4 py-2 rounded-lg hover:bg-ink hover:text-white transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="hidden sm:inline-block text-xs font-semibold bg-brand text-white px-4 py-2 rounded-lg hover:bg-brand-dark transition-colors">
                        Register
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" aria-label="Shopping cart" class="relative text-ink hover:text-brand transition-colors duration-200">
                    <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-brand text-white text-[10px] font-bold flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                </a>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="hidden sm:block text-xs font-semibold text-ash border border-field px-4 py-2 rounded-lg hover:text-red-600 hover:border-red-300 transition-colors"
                                title="Log out ({{ $user->name }})">
                            Log Out
                        </button>
                    </form>
                @endauth

                <button id="menu-toggle"
                        aria-expanded="false"
                        aria-label="Toggle menu"
                        class="xl:hidden flex flex-col gap-1.5 p-2 border border-field hover:border-brand transition-colors duration-200">
                    <span class="block w-6 h-px bg-ink"></span>
                    <span class="block w-6 h-px bg-ink"></span>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden xl:hidden bg-white border-t border-line">
            <ul class="px-6 py-6 flex flex-col">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}"
                           class="block py-3 font-semibold text-sm border-b border-line">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</header>

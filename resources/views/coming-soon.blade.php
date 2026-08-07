<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coming soon — Phone Station</title>
    <meta name="description" content="Phone Station is launching soon. New and ex-display phones, laptops and desktop builds — be the first to know.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.tailwind')
</head>
<body class="bg-[#ffccc7] text-white font-sans antialiased">

    <div class="relative min-h-screen flex flex-col overflow-hidden">

        {{-- BACKGROUND --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/coming-soon.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#35141c]/90 via-[#35141c]/55 to-[#35141c]/15"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#35141c]/70 via-transparent to-transparent"></div>

        {{-- TOP BAR --}}
        <header class="relative z-10 flex items-center justify-between px-6 py-6 lg:px-14 lg:py-8">
            <a href="{{ route('coming-soon') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Phone Station" class="h-11 w-11 rounded-full object-cover ring-2 ring-white/30">
                <span class="font-semibold text-lg tracking-tight">Phone Station</span>
            </a>
        </header>

        {{-- CONTENT --}}
        <main class="relative z-10 flex-1 flex items-center px-6 pb-16 lg:px-14">
            <div class="max-w-2xl">
                <span class="inline-block text-[11px] font-semibold tracking-[0.3em] uppercase text-white/80">
                    New store · Launching soon
                </span>

                <h1 class="mt-4 font-bold leading-tight" style="font-size: clamp(2.75rem, 6vw, 4.5rem);">
                    Coming soon
                </h1>

                <p class="mt-5 text-white/85 text-base lg:text-lg font-normal leading-relaxed max-w-xl">
                    We are launching a new home for new and ex-display phones, laptops and desktop builds.
                    Be the first to know when we go live.
                </p>

                <form method="POST" action="{{ route('coming-soon.subscribe') }}" class="mt-9 flex flex-col sm:flex-row gap-3 max-w-lg">
                    @csrf
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Your email address"
                           class="flex-1 px-5 py-3.5 bg-white text-ink text-sm rounded-full outline-none placeholder:text-ash focus:ring-2 focus:ring-white/60">
                    <button type="submit"
                            class="px-8 py-3.5 bg-white text-[#35141c] text-sm font-semibold rounded-full hover:bg-[#ffccc7] transition-colors">
                        Notify me
                    </button>
                </form>
                @error('email') <p class="mt-2 text-sm text-white/90">{{ $message }}</p> @enderror
                @if (session('status'))
                    <p class="mt-3 text-sm font-medium text-white">{{ session('status') }}</p>
                @endif

                <div class="mt-10 flex items-center gap-4">
                    <span class="text-[11px] font-semibold tracking-[0.2em] uppercase text-white/70">Follow us</span>
                    <div class="flex items-center gap-3">
                        <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-white/15 border border-white/30 flex items-center justify-center hover:bg-white hover:text-[#35141c] transition-colors">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127c-.292-.04-1.292-.126-2.455-.126-2.429 0-4.095 1.483-4.095 4.207v2.384H7.319v3.209h2.799v8.196h3.279z"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="w-10 h-10 rounded-full bg-white/15 border border-white/30 flex items-center justify-center hover:bg-white hover:text-[#35141c] transition-colors">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-white/15 border border-white/30 flex items-center justify-center hover:bg-white hover:text-[#35141c] transition-colors">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 px-6 pb-6 lg:px-14 flex flex-wrap items-center justify-between gap-2 text-xs text-white/60">
            <p>© {{ date('Y') }} Phone Station — Ibadan, Nigeria</p>
            <p>No 131 Iwo Road, Opposite Item 7go, Iwo Road, Ibadan</p>
        </footer>
    </div>
</body>
</html>

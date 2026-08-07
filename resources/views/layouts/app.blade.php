<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Phone Station') — Phone Station</title>
    <meta name="description" content="Phone Station — new and ex-display phones, laptops and desktop builds. Stock loaded daily, shipped from our city showroom.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.tailwind')
</head>
<body class="min-h-screen flex flex-col">

    <x-navigation />

    <main class="flex-1">
        @if (session('status'))
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] bg-ink text-white text-sm font-medium px-6 py-4">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>

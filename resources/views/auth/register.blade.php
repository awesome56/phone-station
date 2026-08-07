<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create account — Phone Station</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.tailwind')
</head>
<body class="min-h-screen bg-mist text-ink font-sans antialiased flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Phone Station" class="h-14 w-14 rounded-full object-cover">
            </a>
            <h1 class="mt-4 text-2xl font-semibold tracking-tight">Create your account</h1>
            <p class="text-sm text-ash mt-1">Join Phone Station today</p>
        </div>

        <div class="bg-white border border-line rounded-xl p-8 shadow-sm">
            @if ($errors->any())
                <div class="mb-5 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-muted mb-1.5">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="input-box rounded-lg" placeholder="Jane Doe">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="input-box rounded-lg" placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted mb-1.5">Password</label>
                    <input type="password" name="password" required class="input-box rounded-lg" placeholder="At least 8 characters">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted mb-1.5">Confirm password</label>
                    <input type="password" name="password_confirmation" required class="input-box rounded-lg" placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn-brand w-full rounded-lg !py-3.5">Create Account</button>
            </form>
        </div>

        <p class="text-center text-sm text-ash mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand font-semibold hover:underline">Sign in</a>
        </p>

        <p class="text-center text-xs text-ash mt-8">
            <a href="{{ route('home') }}" class="hover:text-brand">← Back to store</a>
        </p>
    </div>
</body>
</html>

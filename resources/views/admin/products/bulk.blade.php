@extends('layouts.admin')

@section('title', 'Bulk Upload Products')

@section('content')
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Bulk Upload Products</h1>
            <p class="text-xs text-ash mt-0.5">Import many products at once from a CSV file</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-ash hover:text-brand">← Back to products</a>
    </div>

    @if (session('status'))
        <div class="mt-5 px-4 py-3 text-sm font-medium rounded-lg {{ session('import_errors') ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-5 px-4 py-3 text-sm font-medium rounded-lg bg-red-50 text-red-700 border border-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-5 grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- IMPORT FORM --}}
        <div class="bg-white border border-line rounded-xl p-6">
            <h2 class="font-semibold text-sm">Upload CSV + Images</h2>
            <p class="text-xs text-ash mt-1 leading-relaxed">
                Upload your filled-in CSV. To add multiple images per product, zip the images together with the
                CSV and upload both — the <code class="text-brand">images</code> column should list the file names
                separated by semicolons (<code class="text-brand">photo-a.jpg;photo-b.jpg</code>).
            </p>

            <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                @csrf

                <div class="border-2 border-dashed border-field rounded-xl p-6 text-center">
                    <label class="block cursor-pointer">
                        <input type="file" name="csv" accept=".csv,.txt" required class="hidden">
                        <svg class="mx-auto text-ash" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                        <p class="mt-3 text-sm font-medium text-ink">Click to choose your <span class="text-brand">CSV file</span></p>
                        <p class="mt-1 text-xs text-ash">products.csv — required</p>
                    </label>
                </div>

                <div class="border-2 border-dashed border-field rounded-xl p-6 text-center">
                    <label class="block cursor-pointer">
                        <input type="file" name="zip" accept=".zip" class="hidden">
                        <svg class="mx-auto text-ash" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        <p class="mt-3 text-sm font-medium text-ink">Optionally add an <span class="text-brand">image ZIP</span></p>
                        <p class="mt-1 text-xs text-ash">images.zip — file names must match the images column</p>
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand text-white text-sm font-semibold px-4 py-3 rounded-lg hover:bg-brand-dark transition-colors">
                    Import Products
                </button>
            </form>
        </div>

        {{-- TEMPLATE / HELP --}}
        <div class="space-y-5">
            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Start with the template</h2>
                <p class="text-xs text-ash mt-1 leading-relaxed">
                    Download the CSV template, fill in your products and upload it back. The template includes
                    two example rows — delete them before importing.
                </p>
                <a href="{{ route('admin.products.template') }}"
                   class="mt-4 inline-flex items-center gap-2 bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-brand transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                    Download CSV Template
                </a>
            </div>

            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Column guide</h2>
                <ul class="mt-4 space-y-2.5 text-xs text-muted leading-relaxed">
                    <li><span class="font-semibold text-ink">name, brand, tagline, description</span> — required text</li>
                    <li><span class="font-semibold text-ink">price</span> — required, full naira amount e.g. <code class="text-brand">1099000</code></li>
                    <li><span class="font-semibold text-ink">compare_at_price</span> — optional "was" price in naira</li>
                    <li><span class="font-semibold text-ink">category</span> — slug: <code class="text-brand">flagships</code>, <code class="text-brand">foldables</code>, <code class="text-brand">budget</code>, <code class="text-brand">gaming</code></li>
                    <li><span class="font-semibold text-ink">stock</span> — quantity (default 0)</li>
                    <li><span class="font-semibold text-ink">featured</span> — <code class="text-brand">yes</code>/<code class="text-brand">no</code></li>
                    <li><span class="font-semibold text-ink">specs</span> — <code class="text-brand">key:value|key2:value2</code> separated by pipes</li>
                    <li><span class="font-semibold text-ink">images</span> — file names separated by semicolons; upload them in the ZIP</li>
                </ul>
            </div>
        </div>
    </div>

    @if (session('import_errors'))
        <div class="mt-5 bg-white border border-amber-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-line">
                <h2 class="font-semibold text-sm text-amber-700">Skipped rows ({{ count(session('import_errors')) }})</h2>
            </div>
            <ul class="divide-y divide-line text-xs text-muted max-h-72 overflow-y-auto">
                @foreach (session('import_errors') as $error)
                    <li class="px-6 py-3">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

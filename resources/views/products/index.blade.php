@extends('layouts.app')

@section('title', strtoupper(request()->query('category') ?: 'All devices') . ' — Phone Station')

@section('content')
    {{-- BREADCRUMB --}}
    <section class="border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-5 breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('products.index') }}">Shop</a>
            @if (request()->query('category'))
                <span class="sep">›</span>
                <span>{{ ucfirst(request()->query('category')) }}</span>
            @endif
        </div>
    </section>

    {{-- BANNER --}}
    <section class="relative bg-ink overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/banner-catalog.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/60 to-ink/25"></div>

        <div class="relative max-w-[1440px] mx-auto px-4 lg:px-8 py-16 lg:py-20">
            <p class="font-light text-sm text-white/70 uppercase tracking-[0.2em]">Phone Station catalogue</p>
            <h1 class="mt-4 font-semibold text-white" style="font-size: clamp(2rem, 4vw, 2.4rem);">
                {{ request()->query('category') ? ucfirst(request()->query('category')) : 'All Products' }}
                ({{ $products->total() }})
            </h1>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-14">
        <div class="grid grid-cols-12 gap-x-8 gap-y-10">
            {{-- SIDEBAR --}}
            <aside class="col-span-12 lg:col-span-3 space-y-5">
                <div class="bg-mist p-6">
                    <p class="font-bold text-base">Compare Products</p>
                    <p class="mt-2 text-[13px] font-normal text-ink">You have no items to compare.</p>
                </div>

                <div class="bg-mist p-6">
                    <p class="font-bold text-base">My Wish List</p>
                    <p class="mt-2 text-[13px] font-normal text-ink">You have no items in your wish list.</p>
                </div>

                <div class="bg-mist p-6">
                    <p class="font-bold text-base mb-4">Brands</p>
                    <ul class="space-y-2.5">
                        @foreach ($brands as $brand)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->except('brand'), ['brand' => $brand])) }}"
                                   class="text-sm font-normal text-ink hover:text-brand transition-colors duration-200 {{ request()->query('brand') === $brand ? 'text-brand font-medium' : '' }}">
                                    {{ $brand }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-mist p-6">
                    <p class="font-bold text-base mb-4">Categories</p>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="text-sm font-normal text-ink hover:text-brand transition-colors duration-200 {{ ! request()->query('category') ? 'text-brand font-medium' : '' }}">
                                All products
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                   class="text-sm font-normal text-ink hover:text-brand transition-colors duration-200 {{ request()->query('category') === $category->slug ? 'text-brand font-medium' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="bg-mist p-6">
                    @if (request()->query('category'))
                        <input type="hidden" name="category" value="{{ request()->query('category') }}">
                    @endif
                    <p class="font-bold text-base mb-4">Search</p>
                    <input type="text" name="q" value="{{ request()->query('q') }}" placeholder="Search products…"
                           class="input-box bg-white mb-3">
                    <button type="submit" class="btn-brand w-full text-center !py-2.5">Find</button>
                </form>
            </aside>

            {{-- GRID --}}
            <div class="col-span-12 lg:col-span-9">
                @forelse ($products as $product)
                    <div class="grid grid-cols-2 xl:grid-cols-3 gap-x-5 gap-y-10">
                        @foreach ($products as $item)
                            <x-product-card :product="$item" />
                        @endforeach
                    </div>

                    <div class="mt-14 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <p class="text-sm text-faint">
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
                        </p>
                        {{ $products->withQueryString()->links() }}
                    </div>
                @empty
                    <div class="py-32 text-center">
                        <p class="font-semibold text-3xl">No products found</p>
                        <p class="mt-4 text-sm text-faint">Try a different filter or search term.</p>
                        <a href="{{ route('products.index') }}" class="btn-brand inline-block mt-10">Reset filters</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Phone Station')

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-ink">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/70 to-ink/30"></div>

        <div class="relative max-w-[1440px] mx-auto px-4 lg:px-8 py-24 lg:py-32 grid grid-cols-12 items-center">
            <div class="col-span-12 lg:col-span-8">
                <p class="font-light text-sm text-white/70 uppercase tracking-[0.2em]">New & ex-display hardware</p>
                <h1 class="mt-6 font-medium leading-[1.1] tracking-tight text-white"
                    style="font-size: clamp(2.5rem, 5vw, 4.2rem);">
                    Phones for the<br>modern operator.
                </h1>
                <p class="mt-6 max-w-md font-light text-lg text-white/80">
                    Flagships, foldables, budget builds and gaming rigs — every unit passes a 48-point inspection before it ships.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products.index') }}" class="btn-brand text-center">Shop Now</a>
                    <a href="{{ route('products.index', ['category' => 'foldables']) }}"
                       class="text-center font-semibold text-sm border border-white/60 text-white px-[2.6rem] py-[0.9rem] hover:bg-white hover:text-ink transition-colors duration-200">Foldables</a>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4 hidden lg:block">
                @if ($featured->isNotEmpty())
                    <div class="relative bg-white border border-line p-6 shadow-[0_20px_60px_-20px_rgba(2,2,3,0.25)]">
                        <span class="absolute top-3 left-3 z-10 bg-brand text-white text-[10px] font-semibold px-3 py-1.5">FEATURED</span>
                        <div class="aspect-[3/4] max-h-[380px] mx-auto">
                            <x-phone-art :product="$featured->first()" />
                        </div>
                        <p class="mt-2 text-center text-[13px] font-medium">{{ $featured->first()->name }}</p>
                        <p class="text-center font-semibold text-lg">{{ naira($featured->first()->price) }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CAROUSEL ARROWS --}}
        <div class="hidden lg:flex flex-col gap-2 absolute right-10 top-1/2 -translate-y-1/2" aria-hidden="true">
            <span class="w-9 h-12 bg-[#252931] flex items-center justify-center">
                <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 1.5L2 7L7.5 12.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="w-9 h-12 bg-[#252931] flex items-center justify-center">
                <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.5 1.5L7 7L1.5 12.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
        </div>
    </section>

    {{-- ZIP / PAYMENT BANNER --}}
    <section class="bg-mist border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 h-[70px] flex items-center justify-center gap-3">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-zip">
                <rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                <path d="M2 10h20" stroke="currentColor" stroke-width="2"/>
            </svg>
            <p class="text-lg text-zip">
                own it now, up to 6 months interest free <a href="#" class="font-semibold underline underline-offset-2">learn more</a>
            </p>
        </div>
    </section>

    {{-- NEW PRODUCTS --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-14 lg:py-20">
        <div class="flex items-center justify-between gap-6 mb-10">
            <h2 class="font-semibold text-2xl">New Products</h2>
            <a href="{{ route('products.index') }}" class="text-[13px] text-brand font-medium flex items-center gap-1.5 hover:underline underline-offset-2">
                See All New Products
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- PROMO / VIDEO BLOCK --}}
    <section class="relative overflow-hidden bg-ink">
        <video class="w-full max-h-[520px] object-cover opacity-90" autoplay muted loop playsinline
               aria-label="Phone Station promo video">
            <source src="{{ Storage::url('videos/promo.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </section>

    {{-- CATEGORY TABS + PRODUCTS --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 pb-14 lg:pb-20">
        <div class="flex flex-wrap items-center gap-8 border-b border-line pb-4">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="font-semibold text-base transition-colors duration-200 {{ $loop->first ? 'text-ink border-b-2 border-brand pb-4 -mb-4' : 'text-slategray hover:text-ink' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10 mt-10">
            @foreach ($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
            @if ($featured->count() < 4)
                @foreach ($products->slice(0, 4 - $featured->count()) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            @endif
        </div>
    </section>

    {{-- CATEGORY TILES --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 pb-14 lg:pb-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($categories as $category)
                @php $sample = $categorySamples[$category->slug] ?? null; @endphp
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="group relative bg-mist border border-line hover:border-brand transition-colors duration-300 overflow-hidden">
                    @if ($sample)
                        <div class="absolute inset-0 bg-white p-6 transition-transform duration-500 group-hover:scale-[1.04]">
                            <x-phone-art :product="$sample" />
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-ink/10 to-transparent"></div>
                    @endif
                    <span class="relative z-10 flex items-end justify-between p-5 h-full min-h-[240px]">
                        <span class="font-semibold text-xl {{ $sample ? 'text-white' : 'text-ink group-hover:text-brand transition-colors duration-300' }}">{{ $category->name }}</span>
                        <span class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- INSTAGRAM / JOURNAL --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 pb-14 lg:pb-20">
        <h2 class="font-semibold text-2xl mb-10">Follow us on Instagram for News, Offers &amp; More</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($products->take(4) as $product)
                <a href="{{ route('products.show', $product) }}" class="group bg-mist border border-line hover:border-brand transition-colors duration-300">
                    <div class="aspect-square p-6">
                        <x-phone-art :product="$product" />
                    </div>
                    <div class="p-4 border-t border-line">
                        <p class="text-xs leading-relaxed line-clamp-2">{{ $product->tagline }}</p>
                        <p class="mt-2 text-[10px] text-ash">{{ $product->created_at->format('d.m.Y') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- TESTIMONIAL --}}
    <section class="bg-mist border-y border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-20 text-center">
            <p class="text-[96px] leading-none font-normal text-ink select-none" aria-hidden="true">‘’</p>
            <blockquote class="mx-auto max-w-3xl text-lg font-normal leading-relaxed">
                My first order arrived today in perfect condition. From the time I sent the query, to receiving the parcel, everything was painless. Cannot recommend this shop enough.
            </blockquote>
            <div class="mt-6 flex items-center justify-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#E9A426" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2l2.955 6.585 7.045.61-5.36 4.68 1.605 6.925L12 17.2l-6.245 3.6 1.605-6.925L2 9.195l7.045-.61L12 2z"/>
                    </svg>
                @endfor
            </div>
            <p class="mt-4 text-sm font-normal">- Tama Brown</p>
            <a href="#" class="mt-8 inline-block text-sm font-semibold text-brand hover:underline underline-offset-2">Leave Us A Review</a>
        </div>
    </section>

    {{-- SUPPORT STRIP --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @php
                $supports = [
                    ['Product Support', 'Up to 3 years on-site warranty available for your peace of mind.', 'support'],
                    ['Personal Account', 'With big discounts, free delivery and a dedicated support specialist.', 'account'],
                    ['Amazing Savings', 'Up to 70% off new products, you can be sure of the best price.', 'tag'],
                ];
            @endphp
            @foreach ($supports as $support)
                <div class="flex gap-6 items-start bg-white border border-line p-8 hover:border-brand transition-colors duration-300">
                    <span class="shrink-0 w-[60px] h-[60px] rounded-full bg-brand flex items-center justify-center">
                        @if ($support[2] === 'support')
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 18v-6a9 9 0 0118 0v6"/>
                                <path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/>
                            </svg>
                        @elseif ($support[2] === 'account')
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>
                            </svg>
                        @else
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                                <circle cx="7" cy="7" r="1.5"/>
                            </svg>
                        @endif
                    </span>
                    <div>
                        <h3 class="text-lg font-bold">{{ $support[0] }}</h3>
                        <p class="mt-2 text-sm font-normal leading-relaxed">{{ $support[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

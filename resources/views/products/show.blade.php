@extends('layouts.app')

@section('title', $product->name . ' — Phone Station')

@section('content')
    {{-- BREADCRUMB --}}
    <section class="border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-5 breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('products.index') }}">Shop</a>
            <span class="sep">›</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            <span class="sep">›</span>
            <span>{{ $product->name }}</span>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-16">
        <div class="grid grid-cols-12 gap-x-10 gap-y-12">
            {{-- GALLERY --}}
            <div class="col-span-12 lg:col-span-6">
                <div class="relative border border-line bg-mist overflow-hidden">
                    <div class="aspect-[3/4] max-h-[640px] mx-auto w-full p-10">
                        <x-phone-art :product="$product" />
                    </div>
                    @if ($product->badge)
                        <span class="absolute top-4 left-4 bg-sale text-ink text-[10px] font-semibold px-3 py-1.5">{{ $product->badge }}</span>
                    @endif
                    @if ($product->discount_percent > 0)
                        <span class="absolute top-4 right-4 bg-avail text-white text-[10px] font-semibold px-3 py-1.5">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                {{-- THUMBNAILS --}}
                <div class="mt-4 flex items-center gap-3">
                    <button type="button" class="qty-btn" aria-label="Previous image">
                        <svg width="9" height="14" viewBox="0 0 9 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M7.5 1.5L2 7L7.5 12.5"/></svg>
                    </button>
                    @foreach (collect([1, 2, 3]) as $thumb)
                        <div class="{{ $thumb === 1 ? 'border-brand' : 'border-line' }} border bg-mist w-[72px] h-[72px] p-2 hover:border-brand transition-colors duration-200 cursor-pointer">
                            <x-phone-art :product="$product" />
                        </div>
                    @endforeach
                    <button type="button" class="qty-btn" aria-label="Next image">
                        <svg width="9" height="14" viewBox="0 0 9 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M1.5 1.5L7 7L1.5 12.5"/></svg>
                    </button>
                </div>
            </div>

            {{-- INFO --}}
            <div class="col-span-12 lg:col-span-6">
                <h1 class="font-medium" style="font-size: clamp(1.8rem, 3vw, 2.2rem); line-height: 1.2;">
                    {{ $product->name }}
                </h1>

                <div class="mt-3 flex items-center gap-3">
                    <span class="flex items-center gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= 4 ? '#E9A426' : '#CACDD8' }}" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2l2.955 6.585 7.045.61-5.36 4.68 1.605 6.925L12 17.2l-6.245 3.6 1.605-6.925L2 9.195l7.045-.61L12 2z"/>
                            </svg>
                        @endfor
                    </span>
                    <a href="#" class="text-xs text-brand hover:underline underline-offset-2">Be the first to review this product</a>
                </div>

                <p class="mt-5 font-light text-lg leading-relaxed">{{ $product->tagline }}</p>

                {{-- COLOUR SWATCHES --}}
                <div class="mt-6 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full border border-line" style="background: #EAE8EB;"></span>
                    <span class="w-7 h-7 rounded-full border border-line" style="background: #F2E9DC;"></span>
                    <span class="w-7 h-7 rounded-full border border-line" style="background: #4B4D4F;"></span>
                </div>

                <a href="#" class="mt-6 inline-block text-xs font-light text-brand hover:underline underline-offset-2">Have a Question? Contact Us</a>

                <div class="mt-4 flex items-center gap-3 text-xs text-zip">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                        <rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 10h20" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span>own it now, up to 6 months interest free <a href="#" class="font-semibold underline underline-offset-2">learn more</a></span>
                </div>

                <p class="mt-3 text-xs font-light text-ink">SKU D5515AI</p>

                {{-- PRICE + SALE CHIP --}}
                <div class="mt-6 flex items-center gap-4 border-y border-line py-5">
                    <p class="text-sm font-normal">On Sale from <span class="font-semibold text-2xl">{{ naira($product->price) }}</span></p>
                    @if ($product->compare_at_price)
                        <span class="bg-sale text-ink text-[11px] font-semibold px-3 py-1.5">Save {{ $product->discount_percent }}%</span>
                    @endif
                </div>

                {{-- QTY + ADD TO CART --}}
                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-7 flex items-center gap-4" data-add-to-cart>
                    @csrf
                    <div class="flex items-center border border-line bg-mist">
                        <button type="button" onclick="this.parentNode.querySelector('input').stepDown()" class="qty-btn" aria-label="Decrease quantity">
                            <svg width="10" height="2" viewBox="0 0 10 2" fill="currentColor"><rect width="10" height="2" rx="1"/></svg>
                        </button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}"
                               class="w-12 text-center font-semibold text-sm bg-transparent outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" onclick="this.parentNode.querySelector('input').stepUp()" class="qty-btn" aria-label="Increase quantity">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><rect y="4" width="10" height="2" rx="1"/><rect x="4" width="2" height="10" rx="1"/></svg>
                        </button>
                    </div>
                    <button type="submit" data-label="Add to Cart"
                            class="btn-brand flex-1 {{ $product->in_stock ? '' : 'opacity-50 pointer-events-none' }}">
                        {{ $product->in_stock ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </form>

                {{-- TABS --}}
                <div class="mt-10 border-b border-line flex items-center gap-10" role="tablist">
                    <button type="button" data-tab="about" role="tab" aria-selected="true"
                            class="tab-link py-4 font-semibold text-sm border-b-2 -mb-px border-brand">
                        About Product
                    </button>
                    <button type="button" data-tab="details" role="tab" aria-selected="false"
                            class="tab-link py-4 font-semibold text-sm border-b-2 -mb-px border-transparent text-muted hover:text-ink transition-colors duration-200">
                        Details
                    </button>
                    <button type="button" data-tab="specs" role="tab" aria-selected="false"
                            class="tab-link py-4 font-semibold text-sm border-b-2 -mb-px border-transparent text-muted hover:text-ink transition-colors duration-200">
                        Specs
                    </button>
                </div>

                <div class="py-8">
                    <div data-panel="about" class="tab-panel">
                        <p class="text-[15px] font-light leading-relaxed">{{ $product->description }}</p>
                    </div>
                    <div data-panel="details" class="tab-panel hidden">
                        <p class="text-[15px] font-light leading-relaxed">{{ $product->tagline }}</p>
                        <p class="mt-4 text-[13px] font-normal text-muted">{{ $product->brand }} — {{ $product->category->name }}</p>
                    </div>
                    <div data-panel="specs" class="tab-panel hidden">
                        <dl class="divide-y divide-line border border-line">
                            @foreach ($product->specs ?? [] as $key => $value)
                                <div class="flex justify-between gap-6 px-5 py-3.5">
                                    <dt class="text-[13px] font-normal text-muted">{{ $key }}</dt>
                                    <dd class="text-[13px] font-normal text-right">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURE BANNER --}}
    <section class="bg-ink text-white">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-20 grid grid-cols-12 gap-10 items-center">
            <div class="col-span-12 lg:col-span-5">
                <h2 class="font-medium" style="font-size: clamp(2rem, 3.5vw, 2.8rem); line-height: 1.15;">
                    Outplay the Competition
                </h2>
                <p class="mt-5 font-light text-lg leading-relaxed text-white/85">
                    Experience a 40% boost in computing from last generation. Every unit is bench-tested and 48-point inspected before it ships.
                </p>
                <a href="{{ route('products.index') }}" class="btn-brand mt-8">Shop the range</a>
            </div>
            <div class="col-span-12 lg:col-span-6 lg:col-start-7">
                <ul class="space-y-6">
                    @foreach (collect($product->specs ?? [])->take(4) as $key => $value)
                        <li class="flex items-start gap-4 border-b border-white/15 pb-5">
                            <span class="w-2 h-2 rounded-full bg-brand mt-2 shrink-0"></span>
                            <p class="font-light text-sm leading-relaxed text-white/85">
                                <span class="font-semibold text-white">{{ $key }}:</span> {{ $value }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- SUPPORT STRIP --}}
    <section class="bg-mist border-y border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-14 grid grid-cols-1 md:grid-cols-3 gap-5">
            @php
                $supports = [
                    ['Product Support', 'Up to 3 years on-site warranty available for your peace of mind.'],
                    ['Personal Account', 'With big discounts, free delivery and a dedicated support specialist.'],
                    ['Amazing Savings', 'Up to 70% off new products, you can be sure of the best price.'],
                ];
            @endphp
            @foreach ($supports as $support)
                <div class="flex gap-5 items-start">
                    <span class="shrink-0 w-[46px] h-[46px] rounded-full bg-brand flex items-center justify-center">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-bold">{{ $support[0] }}</h3>
                        <p class="mt-1.5 text-sm font-normal leading-relaxed">{{ $support[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- RELATED --}}
    @if ($related->isNotEmpty())
        <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16 lg:py-20">
            <div class="flex items-end justify-between gap-6 mb-10">
                <h2 class="font-semibold text-2xl">Related Products</h2>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-[13px] text-brand font-medium hover:underline underline-offset-2">
                    More {{ $product->category->name }}
                </a>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-10">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tab-link').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab-link').forEach((t) => {
                t.classList.remove('border-brand');
                t.classList.add('border-transparent', 'text-muted');
            });
            tab.classList.add('border-brand');
            tab.classList.remove('border-transparent', 'text-muted');
            document.querySelectorAll('.tab-panel').forEach((p) => p.classList.add('hidden'));
            document.querySelector(`[data-panel="${tab.dataset.tab}"]`).classList.remove('hidden');
        });
    });
</script>
@endpush

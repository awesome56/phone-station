@extends('layouts.app')

@section('title', 'Shopping Cart — Phone Station')

@section('content')
    {{-- BREADCRUMB --}}
    <section class="border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-5 breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <span>Shopping Cart</span>
        </div>
    </section>

    <section class="relative bg-ink overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/60 to-ink/25"></div>
        <div class="relative max-w-[1440px] mx-auto px-4 lg:px-8 py-16 lg:py-20">
            <h1 class="font-semibold text-white" style="font-size: clamp(2rem, 4vw, 2.4rem);">Shopping Cart</h1>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-14">
        @if ($lines->isEmpty())
            <div class="py-32 text-center">
                <p class="font-semibold text-3xl">Your cart is empty</p>
                <p class="mt-4 text-sm text-faint">You have no items in your shopping cart.</p>
                <a href="{{ route('products.index') }}" class="btn-brand inline-block mt-10">Continue Shopping</a>
            </div>
        @else
            <div class="mt-10 grid grid-cols-12 gap-x-10 gap-y-12">
                {{-- LINES --}}
                <div class="col-span-12 lg:col-span-8">
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="hidden md:grid grid-cols-12 gap-4 border-b border-line pb-4">
                            <p class="col-span-5 font-semibold text-sm">Item</p>
                            <p class="col-span-2 font-semibold text-sm">Price</p>
                            <p class="col-span-2 font-semibold text-sm">Qty</p>
                            <p class="col-span-3 font-semibold text-sm text-right">Subtotal</p>
                        </div>

                        <div class="divide-y divide-line">
                            @foreach ($lines as $line)
                                @php $item = $line['product']; @endphp
                                <div class="py-6 grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-12 md:col-span-5 flex items-center gap-4">
                                        <a href="{{ route('products.show', $item) }}" class="w-[72px] h-[72px] shrink-0 border border-line bg-mist p-2 overflow-hidden">
                                            <x-phone-art :product="$item" />
                                        </a>
                                        <div class="min-w-0">
                                            <p class="text-[10px] text-ash font-normal">{{ $item->brand }}</p>
                                            <a href="{{ route('products.show', $item) }}"
                                               class="mt-1 block text-sm font-normal leading-snug hover:text-brand transition-colors duration-200 line-clamp-2">
                                                {{ $item->name }}
                                            </a>
                                            <p class="mt-1.5 text-xs text-ash md:hidden">{{ naira($item->price) }}</p>
                                        </div>
                                    </div>

                                    <p class="hidden md:block col-span-2 font-semibold text-base">{{ naira($item->price) }}</p>

                                    <div class="col-span-6 md:col-span-2">
                                        <div class="flex items-center justify-start border border-line bg-mist w-fit">
                                            <button type="button" onclick="const i=this.parentNode.querySelector('input'); i.value=Math.max(1, Number(i.value)-1)" class="qty-btn" aria-label="Decrease quantity">
                                                <svg width="10" height="2" viewBox="0 0 10 2" fill="currentColor"><rect width="10" height="2" rx="1"/></svg>
                                            </button>
                                            <input type="number" name="quantity[{{ $item->getKey() }}]" value="{{ $line['quantity'] }}" min="1" max="99"
                                                   class="w-11 text-center font-semibold text-sm bg-transparent outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="const i=this.parentNode.querySelector('input'); i.value=Math.min(99, Number(i.value)+1)" class="qty-btn" aria-label="Increase quantity">
                                                <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><rect y="4" width="10" height="2" rx="1"/><rect x="4" width="2" height="10" rx="1"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-span-6 md:col-span-3 flex items-center justify-between md:justify-end gap-4">
                                        <p class="font-semibold text-base">{{ naira($item->price * $line['quantity']) }}</p>
                                        <span class="flex items-center gap-2">
                                            <a href="{{ route('products.show', $item) }}" aria-label="Edit {{ $item->name }}" class="text-ash hover:text-brand transition-colors duration-200">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Remove {{ $item->name }}" class="text-ash hover:text-avail transition-colors duration-200">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between items-start">
                            <a href="{{ route('products.index') }}" class="btn-outline !py-3">Continue Shopping</a>
                            <button type="submit" class="btn-dark !py-3">Update Cart</button>
                        </div>
                    </form>
                </div>

                {{-- SUMMARY --}}
                <div class="col-span-12 lg:col-span-4">
                    <div class="bg-mist p-8 sticky top-24">
                        <p class="font-semibold text-2xl">Summary</p>

                        <details class="mt-8 group">
                            <summary class="cursor-pointer flex items-center justify-between text-lg font-normal">
                                Estimate Shipping and Tax
                                <span class="group-open:rotate-180 transition-transform duration-200 text-ink">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                            </summary>
                            <p class="mt-3 text-sm font-normal text-muted">Enter your destination to get a shipping estimate. Standard rate — price may vary depending on the item/destination.</p>
                        </details>

                        <details class="mt-6 group">
                            <summary class="cursor-pointer flex items-center justify-between text-lg font-normal">
                                Apply Discount Code
                                <span class="group-open:rotate-180 transition-transform duration-200 text-ink">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                            </summary>
                            <p class="mt-3 text-sm font-normal text-muted">Enter your discount code at checkout.</p>
                        </details>

                        <div class="mt-8 space-y-3 text-[13px] font-semibold">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>{{ naira($subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span>{{ $subtotal >= 50000 ? 'FREE' : naira(399, true) }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between border-t border-line pt-4 font-semibold text-lg">
                            <span>Order Total</span>
                            <span>{{ naira($subtotal + ($subtotal >= 50000 ? 0 : 399), true) }}</span>
                        </div>

                        <div class="mt-8 space-y-3">
                            <a href="{{ route('checkout.create') }}" class="btn-brand w-full text-center block">Proceed to Checkout</a>
                            <a href="#" class="btn-outline w-full text-center block !text-ash">Check Out with Multiple Addresses</a>
                        </div>

                        <p class="mt-5 text-[10px] text-ash font-normal leading-relaxed">
                            (Standard Rate - Price may vary depending on the item/destination. Shop Staff will contact you with a delivery date.)
                        </p>

                        <div class="mt-6 flex items-center gap-2 text-xs text-zip">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                <rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M2 10h20" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>own it now, up to 6 months interest free <a href="#" class="font-semibold underline underline-offset-2">learn more</a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

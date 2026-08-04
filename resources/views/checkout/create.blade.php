@extends('layouts.app')

@section('title', 'Checkout — Phone Station')

@section('content')
    {{-- BREADCRUMB --}}
    <section class="border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-5 breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('cart.index') }}">Shopping Cart</a>
            <span class="sep">›</span>
            <span>Checkout Process</span>
        </div>
    </section>

    <section class="relative bg-ink overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/60 to-ink/25"></div>
        <div class="relative max-w-[1440px] mx-auto px-4 lg:px-8 py-16 lg:py-20 flex items-center justify-between gap-6">
            <h1 class="font-semibold text-white" style="font-size: clamp(2rem, 4vw, 2.4rem);">Checkout</h1>

            {{-- STEPS --}}
            <div class="hidden md:flex items-center gap-4 shrink-0">
                <span class="w-10 h-10 rounded-full bg-brand text-white font-semibold text-[15px] flex items-center justify-center">1</span>
                <span class="w-16 h-px bg-brand"></span>
                <span class="w-10 h-10 rounded-full border border-ash text-ash font-semibold text-[15px] flex items-center justify-center">2</span>
                <span class="text-base font-normal text-white">Shipping</span>
                <span class="mx-2 text-base font-normal text-ash">Review &amp; Payments</span>
            </div>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-14">
        <form method="POST" action="{{ route('checkout.store') }}" class="mt-12 grid grid-cols-12 gap-x-10 gap-y-12">
            @csrf

            {{-- FORM --}}
            <div class="col-span-12 lg:col-span-8">
                <h2 class="text-lg font-semibold pb-4 border-b border-line">Shipping Address</h2>
                <p class="mt-4 text-[13px] font-normal text-muted">You can create an account after checkout.</p>

                <div class="mt-8 grid grid-cols-12 gap-x-6 gap-y-6">
                    <div class="col-span-12 md:col-span-6">
                        <label for="customer_name" class="block text-[13px] font-semibold mb-2">Full Name *</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                               placeholder="First and last name" class="input-box">
                        @error('customer_name')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label for="customer_email" class="block text-[13px] font-semibold mb-2">Email Address *</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required
                               placeholder="you@example.com" class="input-box">
                        @error('customer_email')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label for="customer_phone" class="block text-[13px] font-semibold mb-2">Phone Number *</label>
                        <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required
                               placeholder="(00) 1234 5678" class="input-box">
                        @error('customer_phone')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12">
                        <label for="shipping_address" class="block text-[13px] font-semibold mb-2">Street Address *</label>
                        <input type="text" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required
                               placeholder="1234 Street Address" class="input-box">
                        @error('shipping_address')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label for="shipping_city" class="block text-[13px] font-semibold mb-2">City *</label>
                        <input type="text" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required
                               placeholder="City" class="input-box">
                        @error('shipping_city')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label for="shipping_postal_code" class="block text-[13px] font-semibold mb-2">Zip/Postal Code *</label>
                        <input type="text" id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                               placeholder="1234" class="input-box">
                        @error('shipping_postal_code')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label for="shipping_country" class="block text-[13px] font-semibold mb-2">Country *</label>
                        <select id="shipping_country" name="shipping_country" required class="input-box cursor-pointer">
                            @foreach (['UK', 'Ireland', 'Germany', 'France', 'Netherlands', 'Spain', 'Italy', 'Other'] as $country)
                                <option value="{{ $country }}" @selected(old('shipping_country', 'UK') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('shipping_country')<p class="mt-2 text-xs text-avail">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- SHIPPING METHOD --}}
                <h2 class="mt-14 text-lg font-semibold pb-4 border-b border-line">Shipping Method</h2>

                <div class="mt-6 space-y-4">
                    <label class="flex items-start gap-3 border border-brand bg-mist p-5 cursor-pointer">
                        <input type="radio" name="shipping_method" value="standard" checked class="mt-1 accent-brand">
                        <span class="flex-1">
                            <span class="block text-[13px] font-semibold">Standard Rate</span>
                            <span class="mt-1 block text-sm font-normal text-muted">Price may vary depending on the item/destination. Shop Staff will contact you with a delivery date.</span>
                        </span>
                        <span class="font-semibold text-sm">{{ $shipping === 0 ? 'FREE' : naira($shipping, true) }}</span>
                    </label>

                    <label class="flex items-start gap-3 border border-line p-5 cursor-pointer hover:border-brand transition-colors duration-200">
                        <input type="radio" name="shipping_method" value="pickup" class="mt-1 accent-brand">
                        <span class="flex-1">
                            <span class="block text-[13px] font-semibold">Pickup from store</span>
                            <span class="mt-1 block text-sm font-normal text-muted">1234 Street Address, City Address, 1234</span>
                        </span>
                        <span class="font-semibold text-sm">{{ naira(0, true) }}</span>
                    </label>
                </div>

                <p class="mt-8 text-[13px] font-normal text-muted leading-relaxed">
                    Payment: cash on delivery or bank transfer confirmation. No cards stored. No subscriptions.
                </p>
            </div>

            {{-- ORDER SUMMARY --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-mist p-8 sticky top-24">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-2xl">Order Summary</p>
                        <span class="text-sm font-normal text-ink">{{ $products->count() }} Items in Cart</span>
                    </div>

                    <div class="mt-6 divide-y divide-line">
                        @foreach ($products as $product)
                            <div class="py-4 flex items-center gap-4">
                                <div class="w-[52px] h-[52px] shrink-0 border border-line bg-white p-1.5">
                                    <x-phone-art :product="$product" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-normal leading-snug line-clamp-2">{{ $product->name }}</p>
                                    <p class="mt-1 text-sm font-semibold text-ash">Qty {{ $cart[$product->getKey()] }}</p>
                                </div>
                                <p class="font-semibold text-sm shrink-0">{{ naira($product->price * $cart[$product->getKey()]) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 space-y-3 text-[13px] font-semibold border-t border-line pt-5">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>{{ naira($subtotal, true) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>{{ $shipping === 0 ? 'FREE' : naira($shipping, true) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between border-t border-line pt-4 font-semibold text-lg">
                        <span>Order Total</span>
                        <span>{{ naira($subtotal + $shipping, true) }}</span>
                    </div>

                    <button type="submit" class="btn-brand w-full text-center block mt-8">Next</button>

                    <p class="mt-5 text-[10px] text-ash font-normal leading-relaxed">
                        By placing this order you agree to our terms. Stock is held for 15 minutes after purchase.
                    </p>
                </div>
            </div>
        </form>
    </section>
@endsection

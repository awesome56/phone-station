@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Phone Station')

@section('content')
    {{-- BREADCRUMB --}}
    <section class="border-b border-line">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-5 breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">›</span>
            <a href="{{ route('cart.index') }}">Shopping Cart</a>
            <span class="sep">›</span>
            <a href="{{ route('checkout.create') }}">Checkout</a>
            <span class="sep">›</span>
            <span>Order Confirmation</span>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-20">
        <div class="grid grid-cols-12 gap-x-10 gap-y-12">
            <div class="col-span-12 lg:col-span-7">
                <div class="flex items-center gap-4">
                    <span class="w-[60px] h-[60px] rounded-full bg-stock flex items-center justify-center shrink-0">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </span>
                    <div>
                        <h1 class="font-semibold" style="font-size: clamp(1.8rem, 3.5vw, 2.4rem);">Order confirmed</h1>
                        <p class="mt-1 text-sm font-normal text-muted">Reference: <span class="font-semibold text-ink">{{ $order->order_number }}</span></p>
                    </div>
                </div>

                <p class="mt-8 text-sm font-normal text-muted leading-relaxed">
                    A confirmation is on its way to <span class="font-semibold text-ink">{{ $order->customer_email }}</span>.<br>
                    Your units are being picked right now.
                </p>

                <div class="mt-10 border border-line divide-y divide-line">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-6 p-5">
                            <div>
                                <p class="font-medium">{{ $item->product_name }}</p>
                                <p class="mt-1 text-xs font-normal text-ash">Qty {{ $item->quantity }} × {{ naira($item->unit_price) }}</p>
                            </div>
                            <p class="font-semibold text-sm">{{ naira($item->line_total) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 space-y-3 text-[13px] font-semibold max-w-sm">
                    <div class="flex justify-between"><span class="text-ash">Subtotal</span><span>{{ naira($order->subtotal, true) }}</span></div>
                    <div class="flex justify-between"><span class="text-ash">Shipping</span><span>{{ $order->shipping === 0 ? 'FREE' : naira($order->shipping, true) }}</span></div>
                    <div class="flex justify-between border-t border-line pt-3"><span class="text-brand">Total paid</span><span>{{ naira($order->total, true) }}</span></div>
                </div>

                <a href="{{ route('products.index') }}" class="btn-brand inline-block mt-12">Back to Shop</a>
            </div>

            <div class="col-span-12 lg:col-span-4 lg:col-start-9">
                <div class="bg-mist p-8">
                    <p class="font-semibold text-xl">Shipping to</p>
                    <p class="mt-5 font-medium">{{ $order->customer_name }}</p>
                    <p class="mt-3 text-sm font-normal text-muted leading-relaxed">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }} {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </p>
                    <div class="mt-8 border-t border-line pt-5 text-[13px] font-semibold space-y-2">
                        <p>Status: <span class="text-stock">PLACED</span></p>
                        <p class="font-normal text-muted">ETA: 24–48 hours</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

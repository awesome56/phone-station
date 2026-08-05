@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Phone Station')

@section('content')
    @php $paid = $order->isPaid(); @endphp

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
                    <span class="w-[60px] h-[60px] rounded-full {{ $paid ? 'bg-stock' : 'bg-sale' }} flex items-center justify-center shrink-0">
                        @if ($paid)
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        @else
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10V8a6 6 0 1 1 12 0v2"/>
                                <rect x="2" y="10" width="20" height="12" rx="2"/>
                            </svg>
                        @endif
                    </span>
                    <div>
                        <h1 class="font-semibold" style="font-size: clamp(1.8rem, 3.5vw, 2.4rem);">
                            {{ $paid ? 'Order confirmed' : 'Order placed' }}
                        </h1>
                        <p class="mt-1 text-sm font-normal text-muted">Reference: <span class="font-semibold text-ink">{{ $order->order_number }}</span></p>
                    </div>
                </div>

                <div class="mt-8 px-5 py-4 border {{ $paid ? 'border-stock/40 bg-stock/5' : 'border-sale/40 bg-sale/5' }} text-sm font-medium">
                    @if ($paid)
                        Payment of <span class="font-semibold">{{ naira($order->total, true) }}</span> received
                        {{ $order->paid_at ? '· '.$order->paid_at->format('M j, Y g:i A') : '' }}.
                        Your units are being picked right now.
                    @elseif ($order->payment_method === 'paystack')
                        Payment of <span class="font-semibold">{{ naira($order->total, true) }}</span> is still pending.
                        If you left Paystack before paying, you can try again below.
                    @else
                        You chose Cash on Delivery — <span class="font-semibold">{{ naira($order->total, true) }}</span> due when your order arrives.
                    @endif
                </div>

                <p class="mt-8 text-sm font-normal text-muted leading-relaxed">
                    A confirmation is on its way to <span class="font-semibold text-ink">{{ $order->customer_email }}</span>.
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
                    <div class="flex justify-between border-t border-line pt-3">
                        <span class="{{ $paid ? 'text-brand' : 'text-ink' }}">{{ $paid ? 'Total paid' : 'Order total' }}</span>
                        <span>{{ naira($order->total, true) }}</span>
                    </div>
                </div>

                <div class="mt-12 flex flex-wrap gap-4">
                    @if (! $paid && $order->payment_method === 'paystack')
                        <a href="{{ route('payments.retry', $order) }}" class="btn-brand">Pay Now — {{ naira($order->total, true) }}</a>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn-outline">Back to Shop</a>
                </div>
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
                        <p>Status: <span class="{{ $paid ? 'text-stock' : 'text-sale' }}">{{ $paid ? 'PAID' : strtoupper($order->status) }}</span></p>
                        <p class="font-normal text-muted">
                            {{ $paid ? 'ETA: 24–48 hours' : 'We will contact you to arrange delivery.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

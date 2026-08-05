@extends('layouts.admin')

@section('title', "Order {$order->order_number}")

@section('content')
    @php
        $colors = [
            'gray' => 'bg-gray-100 text-gray-600',
            'blue' => 'bg-blue-50 text-blue-600',
            'amber' => 'bg-amber-50 text-amber-600',
            'indigo' => 'bg-indigo-50 text-indigo-600',
            'green' => 'bg-green-50 text-green-600',
            'red' => 'bg-red-50 text-red-600',
        ];
    @endphp

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Order {{ $order->order_number }}</h1>
            <p class="text-xs text-ash mt-0.5">Placed {{ $order->created_at->format('M j, Y g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-ash hover:text-brand">← Back to orders</a>
    </div>

    <div class="mt-5 grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- ITEMS --}}
        <div class="xl:col-span-2 bg-white border border-line rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-line flex items-center justify-between">
                <h2 class="font-semibold text-sm">Items ({{ $order->items->count() }})</h2>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $colors[$statuses[$order->status]['color']] }}">
                    {{ $statuses[$order->status]['label'] }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                            <th class="px-6 py-3 font-semibold">Product</th>
                            <th class="px-6 py-3 font-semibold">Unit price</th>
                            <th class="px-6 py-3 font-semibold">Qty</th>
                            <th class="px-6 py-3 font-semibold text-right">Line total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-3.5">
                                    <p class="font-medium">{{ $item->product_name }}</p>
                                    @if ($item->product_slug)
                                        <a href="{{ route('products.show', $item->product_slug) }}" target="_blank" class="text-xs text-brand hover:underline">View product</a>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-muted">{{ naira($item->unit_price) }}</td>
                                <td class="px-6 py-3.5 text-muted">{{ $item->quantity }}</td>
                                <td class="px-6 py-3.5 text-right font-semibold">{{ naira($item->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-line space-y-1.5 text-sm">
                <div class="flex justify-between text-muted"><span>Subtotal</span><span>{{ naira($order->subtotal) }}</span></div>
                <div class="flex justify-between text-muted"><span>Shipping</span><span>{{ $order->shipping ? naira($order->shipping) : 'Free' }}</span></div>
                <div class="flex justify-between font-semibold pt-1.5 border-t border-line"><span>Total</span><span>{{ naira($order->total) }}</span></div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-5">
            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Update Status</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="input-box rounded-lg">
                        @foreach ($statuses as $key => $status)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $status['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-ink text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-brand transition-colors">
                        Save Status
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-3"
                      onsubmit="return confirm('Delete order {{ $order->order_number }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full border border-red-200 text-red-600 text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-red-50 transition-colors">
                        Delete Order
                    </button>
                </form>
            </div>

            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Payment</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-xs text-ash">Method</dt>
                        <dd class="font-medium capitalize">{{ $order->payment_method === 'paystack' ? 'Paystack' : 'Cash on Delivery' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-xs text-ash">Status</dt>
                        <dd>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $order->isPaid() ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $order->isPaid() ? 'Paid' : 'Unpaid' }}
                            </span>
                        </dd>
                    </div>
                    @if ($order->payment_reference)
                        <div>
                            <dt class="text-xs text-ash">Reference</dt>
                            <dd class="font-medium break-all">{{ $order->payment_reference }}</dd>
                        </div>
                    @endif
                    @if ($order->paid_at)
                        <div>
                            <dt class="text-xs text-ash">Paid at</dt>
                            <dd class="font-medium">{{ $order->paid_at->format('M j, Y g:i A') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Customer</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-xs text-ash">Name</dt><dd class="font-medium">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-xs text-ash">Email</dt><dd class="font-medium break-all">{{ $order->customer_email }}</dd></div>
                    <div><dt class="text-xs text-ash">Phone</dt><dd class="font-medium">{{ $order->customer_phone ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold text-sm">Shipping Address</h2>
                <p class="mt-4 text-sm leading-relaxed">
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}{{ $order->shipping_postal_code ? ', '.$order->shipping_postal_code : '' }}<br>
                    {{ $order->shipping_country }}
                </p>
            </div>
        </div>
    </div>
@endsection

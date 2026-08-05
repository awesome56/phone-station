@extends('layouts.admin')

@section('title', 'Orders')

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
            <h1 class="text-lg font-semibold">Orders</h1>
            <p class="text-xs text-ash mt-0.5">{{ $orders->total() }} orders</p>
        </div>
        <div class="flex gap-1.5 flex-wrap">
            <a href="{{ route('admin.orders.index') }}"
               class="text-xs font-semibold px-3 py-2 rounded-lg {{ empty($filters['status']) ? 'bg-ink text-white' : 'bg-white border border-line text-ash hover:text-ink' }}">All</a>
            @foreach ($statuses as $key => $status)
                <a href="{{ route('admin.orders.index', ['status' => $key, 'q' => $filters['q'] ?? null]) }}"
                   class="text-xs font-semibold px-3 py-2 rounded-lg {{ ($filters['status'] ?? '') === $key ? 'bg-ink text-white' : 'bg-white border border-line text-ash hover:text-ink' }}">
                    {{ $status['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <form method="GET" action="{{ route('admin.orders.index') }}"
          class="mt-5 bg-white border border-line rounded-xl p-4 flex flex-wrap items-end gap-3">
        <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
        <div class="flex-1 min-w-52">
            <label class="block text-xs font-medium text-ash mb-1.5">Search</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Order number, customer name or email…" class="input-box rounded-lg">
        </div>
        <button type="submit" class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-brand transition-colors">Search</button>
    </form>

    <div class="mt-5 bg-white border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                        <th class="px-5 py-3.5 font-semibold">Order</th>
                        <th class="px-5 py-3.5 font-semibold">Customer</th>
                        <th class="px-5 py-3.5 font-semibold">Date</th>
                        <th class="px-5 py-3.5 font-semibold">Items</th>
                        <th class="px-5 py-3.5 font-semibold">Total</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-mist/50 transition-colors">
                            <td class="px-5 py-3.5 font-medium">{{ $order->order_number }}</td>
                            <td class="px-5 py-3.5">
                                <p>{{ $order->customer_name }}</p>
                                <p class="text-xs text-ash">{{ $order->customer_email }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-muted">{{ $order->created_at->format('M j, Y') }}</td>
                            <td class="px-5 py-3.5 text-muted">{{ $order->items_count }}</td>
                            <td class="px-5 py-3.5 font-semibold">{{ naira($order->total) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $colors[$statuses[$order->status]['color']] }}">
                                    {{ $statuses[$order->status]['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-xs font-semibold text-brand hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center text-ash">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-line">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

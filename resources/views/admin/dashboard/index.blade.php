@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach ($stats as $stat)
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs font-medium text-ash">{{ $stat['label'] }}</p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <p class="text-2xl font-semibold tracking-tight">{{ $stat['value'] }}</p>
                    @if ($stat['delta'] !== null)
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $stat['delta'] >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            {{ $stat['delta'] >= 0 ? '▲' : '▼' }} {{ number_format(abs($stat['delta']), 1) }}%
                        </span>
                    @else
                        <span class="text-xs text-ash">—</span>
                    @endif
                </div>
                <p class="mt-1.5 text-xs text-ash">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- TOTALS STRIP --}}
    <div class="mt-5 grid grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach (['Total revenue' => $totals['revenue'], 'Total orders' => $totals['orders'], 'Total customers' => $totals['customers'], 'Average order' => $totals['average']] as $label => $value)
            <div class="bg-white border border-line rounded-xl px-5 py-4 flex items-center justify-between">
                <p class="text-xs text-ash">{{ $label }}</p>
                <p class="font-semibold text-sm">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-5 grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- REVENUE CHART --}}
        <div class="xl:col-span-2 bg-white border border-line rounded-xl p-5">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h2 class="font-semibold text-sm">Revenue</h2>
                    <p class="text-xs text-ash mt-0.5">Gross sales by month (last 12 months)</p>
                </div>
                <div class="flex gap-2">
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-mist text-ink">This year</span>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full text-ash">Last year</span>
                </div>
            </div>
            <div class="mt-6 flex items-end gap-2 h-44">
                @foreach ($revenueSeries as $month)
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <div class="w-full flex flex-col justify-end bg-mist rounded-md overflow-hidden" style="height: 100%;">
                            <div class="w-full bg-brand rounded-md transition-all duration-500 group-hover:bg-brand-dark"
                                 style="height: {{ $month['value'] ? max($month['value'] / $maxRevenue * 100, 3) : 0 }}%;"></div>
                        </div>
                        <span class="text-[10px] text-ash">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ORDERS BY STATUS --}}
        <div class="bg-white border border-line rounded-xl p-5">
            <h2 class="font-semibold text-sm">Orders by Status</h2>
            <p class="text-xs text-ash mt-0.5">All time distribution</p>
            <ul class="mt-5 space-y-3.5">
                @foreach ($statusLabels as $key => $label)
                    @php $count = $statusCounts[$key] ?? 0; @endphp
                    <li>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand"></span>
                                {{ $label }}
                            </span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                        <div class="mt-1.5 h-1.5 bg-mist rounded-full overflow-hidden">
                            <div class="h-full bg-brand rounded-full" style="width: {{ $statusCounts->max() ? $count / $statusCounts->max() * 100 : 0 }}%"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- TOP PRODUCTS --}}
        <div class="bg-white border border-line rounded-xl p-5">
            <h2 class="font-semibold text-sm">Top Products</h2>
            <p class="text-xs text-ash mt-0.5">By revenue generated</p>
            <ul class="mt-5 divide-y divide-line">
                @forelse ($topProducts as $product)
                    <li class="py-3 flex items-center gap-3">
                        @if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover bg-mist shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-mist flex items-center justify-center text-ash shrink-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="M2 2l20 20"/></svg>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                            <p class="text-xs text-ash">{{ $product->units_sold ?? 0 }} sold</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold">{{ naira($product->revenue ?? 0) }}</p>
                            <p class="text-xs text-ash">{{ $product->stock }} in stock</p>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ash">No sales yet.</li>
                @endforelse
            </ul>
        </div>

        {{-- CATEGORY SPLIT --}}
        <div class="bg-white border border-line rounded-xl p-5">
            <h2 class="font-semibold text-sm">Store Categories</h2>
            <p class="text-xs text-ash mt-0.5">Products per category</p>
            <ul class="mt-5 space-y-3.5">
                @foreach ($categories as $category)
                    <li>
                        <div class="flex items-center justify-between text-sm">
                            <span>{{ $category->name }}</span>
                            <span class="font-semibold">{{ $category->products_count }}</span>
                        </div>
                        <div class="mt-1.5 h-1.5 bg-mist rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-brand" style="width: {{ $categories->max('products_count') ? $category->products_count / $categories->max('products_count') * 100 : 0 }}%"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- RECENT ORDERS --}}
        <div class="bg-white border border-line rounded-xl p-5">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-sm">Recent Orders</h2>
                @if (auth()->user()->hasPermission('orders.manage'))
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand hover:underline">View all</a>
                @endif
            </div>
            <ul class="mt-4 divide-y divide-line">
                @forelse ($recentOrders as $order)
                    <li class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate">{{ $order->customer_name }}</p>
                            <p class="text-xs text-ash">{{ $order->order_number }} · {{ $order->created_at->format('M j, Y') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold">{{ naira($order->total) }}</p>
                            <p class="text-[11px] capitalize text-ash">{{ $statusLabels[$order->status] ?? $order->status }}</p>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ash">No orders yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

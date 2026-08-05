@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Products</h1>
            <p class="text-xs text-ash mt-0.5">{{ $products->total() }} products in store</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 bg-brand text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-brand-dark transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Add Product
        </a>
    </div>

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('admin.products.index') }}"
          class="mt-5 bg-white border border-line rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-52">
            <label class="block text-xs font-medium text-ash mb-1.5">Search</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name, brand or slug…"
                   class="input-box rounded-lg">
        </div>
        <div class="w-48">
            <label class="block text-xs font-medium text-ash mb-1.5">Category</label>
            <select name="category" class="input-box rounded-lg">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-ash mb-1.5">Stock</label>
            <select name="stock" class="input-box rounded-lg">
                <option value="">Any</option>
                <option value="in" @selected(($filters['stock'] ?? '') === 'in')>In stock</option>
                <option value="low" @selected(($filters['stock'] ?? '') === 'low')>Low (&lt; 10)</option>
                <option value="out" @selected(($filters['stock'] ?? '') === 'out')>Out of stock</option>
            </select>
        </div>
        <button type="submit" class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-brand transition-colors">Filter</button>
        @if (collect($filters)->filter()->isNotEmpty())
            <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-ash hover:text-brand self-center">Clear</a>
        @endif
    </form>

    {{-- TABLE --}}
    <div class="mt-5 bg-white border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                        <th class="px-5 py-3.5 font-semibold">Product</th>
                        <th class="px-5 py-3.5 font-semibold">Category</th>
                        <th class="px-5 py-3.5 font-semibold">Price</th>
                        <th class="px-5 py-3.5 font-semibold">Stock</th>
                        <th class="px-5 py-3.5 font-semibold">Sold</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($products as $product)
                        <tr class="hover:bg-mist/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3 min-w-52">
                                    @if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover bg-mist shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-mist shrink-0"></div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-ash">{{ $product->brand }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-muted">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold">{{ naira($product->price) }}</p>
                                @if ($product->discount_percent)
                                    <p class="text-[11px] text-green-600">-{{ $product->discount_percent }}%</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="{{ $product->stock === 0 ? 'text-red-600 font-medium' : ($product->stock < 10 ? 'text-amber-600 font-medium' : 'text-muted') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-muted">{{ $product->items_count }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $product->featured ? 'bg-brand/10 text-brand' : 'bg-mist text-muted' }}">
                                    {{ $product->featured ? 'Featured' : 'Standard' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="p-2 rounded-lg border border-line text-muted hover:text-brand hover:border-brand transition-colors" aria-label="Edit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg border border-line text-muted hover:text-red-600 hover:border-red-300 transition-colors" aria-label="Delete">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center text-ash">No products match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-line">
            {{ $products->links() }}
        </div>
    </div>
@endsection

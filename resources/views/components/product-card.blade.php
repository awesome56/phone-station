@props(['product'])

@php
    $rating = ($product->id % 5) + 1;
    $rated = $product->id % 5 + 1;
    $stars = min(5, max(1, $product->id % 5 + 1));
@endphp

<article class="group relative flex flex-col bg-white border border-line hover:border-brand transition-colors duration-300">
    <div class="relative">
        <a href="{{ route('products.show', $product) }}" class="block aspect-square">
            <div class="w-full h-full p-8 transition-transform duration-500 group-hover:scale-[1.04]">
                <x-phone-art :product="$product" />
            </div>
        </a>

        {{-- STATUS BADGES --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if ($product->in_stock)
                <span class="flex items-center gap-1.5 bg-white px-2.5 py-1.5 text-[10px] text-stock">
                    <span class="w-2.5 h-2.5 rounded-full bg-stock flex items-center justify-center">
                        <svg width="7" height="5" viewBox="0 0 8 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 3L3 5L7 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    in stock
                </span>
            @else
                <span class="flex items-center gap-1.5 bg-white px-2.5 py-1.5 text-[10px] text-avail">
                    <span class="w-2.5 h-2.5 rounded-full bg-avail flex items-center justify-center">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 5L5 1M1 1L5 5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                    check availability
                </span>
            @endif
        </div>

        @if ($product->badge)
            <span class="absolute top-3 right-3 bg-sale text-ink text-[10px] font-semibold px-2.5 py-1.5">
                {{ $product->badge }}
            </span>
        @endif

        {{-- HOVER ACTION --}}
        <div class="absolute inset-0 flex flex-col justify-center items-center gap-4 bg-white/85 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <form method="POST" action="{{ route('cart.add', $product) }}" data-add-to-cart>
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        data-label="Add to Cart"
                        class="bg-brand text-white text-sm font-semibold px-8 py-3 hover:bg-brand-dark transition-colors duration-200 {{ $product->in_stock ? '' : 'opacity-50 pointer-events-none' }}">
                    {{ $product->in_stock ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>
            <a href="{{ route('products.show', $product) }}" class="text-sm text-brand font-semibold hover:underline underline-offset-2">
                View details
            </a>
        </div>
    </div>

    <div class="px-4 pb-5 flex flex-col">
        {{-- RATING --}}
        <div class="flex items-center gap-3 h-[26px]">
            <span class="flex items-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= $stars ? '#E9A426' : '#CACDD8' }}" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2l2.955 6.585 7.045.61-5.36 4.68 1.605 6.925L12 17.2l-6.245 3.6 1.605-6.925L2 9.195l7.045-.61L12 2z"/>
                    </svg>
                @endfor
            </span>
            <span class="text-xs text-ash">Reviews ({{ $product->id % 9 + 1 }})</span>
        </div>

        <h3 class="mt-2 text-[13px] leading-[1.5] min-h-[39px]">
            <a href="{{ route('products.show', $product) }}" class="hover:text-brand transition-colors duration-200 line-clamp-3">
                {{ $product->name }}
            </a>
        </h3>

        <p class="mt-2 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
            <span class="font-semibold text-lg">{{ naira($product->price) }}</span>
            @if ($product->compare_at_price)
                <span class="text-sm text-ash line-through">{{ naira($product->compare_at_price) }}</span>
            @endif
        </p>
    </div>
</article>

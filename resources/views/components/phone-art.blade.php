@props(['product'])

@if ($product->image)
    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
         loading="lazy" class="w-full h-full object-contain">
@else
@php
    $palettes = [
        [
            'body' => ['#1b0e0d', '#2c1a15'],
            'screen' => ['#e3e2de', '#b9b4ac'],
            'text' => '#1b0e0d',
            'accent' => '#c72a09',
            'glow' => '#31ef07',
        ],
        [
            'body' => ['#61220f', '#3d2b27'],
            'screen' => ['#1b0e0d', '#2c1a15'],
            'text' => '#e3e2de',
            'accent' => '#31ef07',
            'glow' => '#c72a09',
        ],
        [
            'body' => ['#c72a09', '#61220f'],
            'screen' => ['#e3e2de', '#cfcdc6'],
            'text' => '#1b0e0d',
            'accent' => '#31ef07',
            'glow' => '#c72a09',
        ],
    ];
    $p = $palettes[$product->id % count($palettes)];
    $letter = strtoupper(mb_substr($product->brand, 0, 1));
    $price = naira($product->price);
    $camera = $product->id % 3;
@endphp

<svg viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="{{ $product->name }}"
     class="w-full h-full">
    <defs>
        <linearGradient id="body-{{ $product->id }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="{{ $p['body'][0] }}"/>
            <stop offset="1" stop-color="{{ $p['body'][1] }}"/>
        </linearGradient>
        <linearGradient id="screen-{{ $product->id }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="{{ $p['screen'][0] }}"/>
            <stop offset="1" stop-color="{{ $p['screen'][1] }}"/>
        </linearGradient>
        <pattern id="dots-{{ $product->id }}" width="14" height="14" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="1" fill="{{ $p['text'] }}" opacity="0.18"/>
        </pattern>
    </defs>

    <g transform="translate(0, 6)">
        <rect x="88" y="0" width="124" height="378" rx="26" fill="url(#body-{{ $product->id }})"/>
        <rect x="88" y="0" width="124" height="378" rx="26" fill="none" stroke="{{ $p['glow'] }}" stroke-width="1.5" opacity="0.35"/>
        <line x1="88" y1="96" x2="88" y2="120" stroke="{{ $p['glow'] }}" stroke-width="2" opacity="0.7"/>
        <line x1="212" y1="96" x2="212" y2="120" stroke="{{ $p['glow'] }}" stroke-width="2" opacity="0.7"/>

        <rect x="104" y="18" width="92" height="344" rx="16" fill="url(#screen-{{ $product->id }})"/>
        <rect x="104" y="18" width="92" height="344" rx="16" fill="url(#dots-{{ $product->id }})"/>

        @if ($camera === 0)
            <g transform="translate(104, 18)">
                <circle cx="46" cy="46" r="20" fill="none" stroke="{{ $p['accent'] }}" stroke-width="2"/>
                <circle cx="46" cy="46" r="11" fill="{{ $p['accent'] }}" opacity="0.85"/>
                <circle cx="42" cy="42" r="4" fill="{{ $p['text'] }}" opacity="0.6"/>
            </g>
        @elseif ($camera === 1)
            <g transform="translate(104, 18)">
                <rect x="20" y="28" width="52" height="36" rx="8" fill="none" stroke="{{ $p['accent'] }}" stroke-width="2"/>
                <circle cx="33" cy="46" r="9" fill="{{ $p['accent'] }}" opacity="0.85"/>
                <circle cx="58" cy="46" r="9" fill="none" stroke="{{ $p['accent'] }}" stroke-width="2"/>
            </g>
        @else
            <g transform="translate(104, 18)">
                <circle cx="46" cy="42" r="14" fill="none" stroke="{{ $p['accent'] }}" stroke-width="2"/>
                <circle cx="46" cy="42" r="7" fill="{{ $p['accent'] }}" opacity="0.85"/>
                <circle cx="80" cy="36" r="4" fill="{{ $p['glow'] }}"/>
                <circle cx="80" cy="50" r="4" fill="none" stroke="{{ $p['accent'] }}" stroke-width="1.5"/>
            </g>
        @endif

        <text x="150" y="252" text-anchor="middle" font-family="'Clash Grotesk', sans-serif" font-size="72"
              font-weight="700" fill="{{ $p['text'] }}">{{ $letter }}</text>
        <text x="150" y="286" text-anchor="middle" font-family="'JetBrains Mono', monospace" font-size="11"
              letter-spacing="3" fill="{{ $p['text'] }}" opacity="0.85">{{ $price }}</text>
        <text x="150" y="308" text-anchor="middle" font-family="'JetBrains Mono', monospace" font-size="8"
              letter-spacing="2" fill="{{ $p['accent'] }}">PHONE STATION</text>

        <line x1="120" y1="340" x2="180" y2="340" stroke="{{ $p['text'] }}" stroke-width="1" opacity="0.5"/>
        <line x1="120" y1="348" x2="162" y2="348" stroke="{{ $p['text'] }}" stroke-width="1" opacity="0.3"/>
    </g>
</svg>
@endif

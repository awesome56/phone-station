<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        $lines = collect($cart)->map(function (int $quantity, int $productId) use ($products) {
            return [
                'product' => $products->get($productId),
                'quantity' => $quantity,
            ];
        })->filter(fn ($line) => $line['product'] !== null)->values();

        $subtotal = $lines->reduce(
            fn (int $carry, array $line) => $carry + $line['product']->price * $line['quantity'],
            0
        );

        return view('cart.index', [
            'lines' => $lines,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session()->get('cart', []);
        $cart[$product->getKey()] = min(($cart[$product->getKey()] ?? 0) + $request->integer('quantity'), $product->stock > 0 ? $product->stock : 99);

        session()->put('cart', $cart);

        return back()->with('status', 'Added to cart');
    }

    public function update(Request $request)
    {
        $request->validate([
            'quantity' => ['required', 'array'],
            'quantity.*' => ['integer', 'min:1', 'max:99'],
        ]);

        $cart = collect($request->input('quantity'))
            ->filter(fn ($quantity) => $quantity > 0)
            ->map(fn ($quantity) => (int) $quantity)
            ->all();

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'Cart updated');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->getKey()]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'Removed from cart');
    }

    public function count(): int
    {
        return array_sum(session()->get('cart', []));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = session()->get('cart', []);

        abort_if(empty($cart), 404, 'Your cart is empty.');

        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        $subtotal = $products->reduce(
            fn (int $carry, $product) => $carry + $product->price * $cart[$product->getKey()],
            0
        );

        return view('checkout.create', [
            'subtotal' => $subtotal,
            'shipping' => $subtotal >= 50000 ? 0 : 399,
            'cart' => $cart,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        abort_if(empty($cart), 404, 'Your cart is empty.');

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:32'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_postal_code' => ['nullable', 'string', 'max:16'],
            'shipping_country' => ['required', 'string', 'max:64'],
        ]);

        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        abort_if($products->isEmpty(), 404, 'Your cart is empty.');

        $subtotal = $products->reduce(
            fn (int $carry, $product) => $carry + $product->price * $cart[$product->getKey()],
            0
        );

        $shipping = $subtotal >= 50000 ? 0 : 399;

        $order = Order::create([
            'order_number' => 'PS-'.Str::upper(Str::random(6)),
            ...$validated,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ]);

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            $order->items()->create([
                'product_id' => $product->getKey(),
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'unit_price' => $product->price,
                'quantity' => $quantity,
                'line_total' => $product->price * $quantity,
            ]);

            $product->decrement('stock', $quantity);
        }

        session()->forget('cart');

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        return view('checkout.confirmation', ['order' => $order]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->query('category'))))
            ->when($request->filled('brand'), fn ($q) => $q->where('brand', $request->query('brand')))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', '%'.$request->query('q').'%')->orWhere('tagline', 'like', '%'.$request->query('q').'%')->orWhere('brand', 'like', '%'.$request->query('q').'%')))
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'brands' => Product::query()->distinct()->orderBy('brand')->pluck('brand'),
        ]);
    }

    public function show(Product $product)
    {
        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}

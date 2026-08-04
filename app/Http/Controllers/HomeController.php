<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'featured' => Product::query()->where('featured', true)->inRandomOrder()->take(3)->get(),
            'products' => Product::query()->inRandomOrder()->take(12)->get(),
            'categories' => Category::all(),
            'categorySamples' => Category::query()->with('products')->get()->mapWithKeys(
                fn ($category) => [$category->slug => $category->products->first()]
            ),
        ]);
    }
}
